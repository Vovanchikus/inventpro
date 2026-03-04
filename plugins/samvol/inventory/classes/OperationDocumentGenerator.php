<?php namespace Samvol\Inventory\Classes;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Classes\DocumentTemplateSettings;
use Symfony\Component\Process\Process;

class OperationDocumentGenerator
{
    protected string $templatesDir = 'templates/operations';
    protected string $generatedDir = 'temp/generated-documents';

    public function listTemplates(): array
    {
        $templates = [
            [
                'id' => 'default',
                'name' => 'Стандартный шаблон',
                'type' => 'builtin',
            ],
        ];

        if (!class_exists(TemplateProcessor::class)) {
            return $templates;
        }

        $disk = Storage::disk('local');
        if (!$disk->exists($this->templatesDir)) {
            return $templates;
        }

        foreach ($disk->files($this->templatesDir) as $path) {
            if (mb_strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'docx') {
                continue;
            }

            $fileName = pathinfo($path, PATHINFO_FILENAME);
            $templates[] = [
                'id' => 'file:' . basename($path),
                'name' => $fileName,
                'type' => 'file',
            ];
        }

        return $templates;
    }

    public function getPrimaryDocumentName(Operation $operation): ?string
    {
        $operation->loadMissing('documents');

        if ($operation->documents && $operation->documents->count()) {
            $docName = trim((string)($operation->documents->sortBy('id')->first()->doc_name ?? ''));
            return $docName !== '' ? $docName : null;
        }

        return null;
    }

    public function resolveTemplateIdForOperation(Operation $operation, ?array $templates = null): string
    {
        $docName = $this->getPrimaryDocumentName($operation);
        if (!$docName) {
            return 'default';
        }

        $templates = $templates ?? $this->listTemplates();
        $normalizedDocName = $this->normalizeTemplateName($docName);

        if ($normalizedDocName === '') {
            return 'default';
        }

        $bestMatch = null;

        foreach ($templates as $template) {
            if (($template['type'] ?? null) !== 'file') {
                continue;
            }

            $templateName = (string)($template['name'] ?? '');
            $normalizedTemplateName = $this->normalizeTemplateName($templateName);
            if ($normalizedTemplateName === '') {
                continue;
            }

            if ($normalizedTemplateName === $normalizedDocName) {
                return (string)($template['id'] ?? 'default');
            }

            if (str_contains($normalizedTemplateName, $normalizedDocName)
                || str_contains($normalizedDocName, $normalizedTemplateName)
            ) {
                $bestMatch = (string)($template['id'] ?? 'default');
            }
        }

        return $bestMatch ?: 'default';
    }

    public function countItems(Operation $operation): int
    {
        return count($this->extractItems($operation));
    }

    public function estimateDocSizeBytes(Operation $operation): int
    {
        $items = $this->countItems($operation);
        return 120000 + ($items * 4500);
    }

    public function generateDocx(Operation $operation, string $templateId = 'default', array $context = []): array
    {
        $disk = Storage::disk('local');
        $disk->makeDirectory($this->generatedDir);

        $fileBase = sprintf('operation_%d_%s', $operation->id, str_replace('.', '', uniqid('_', true)));
        $relativePath = $this->generatedDir . '/' . $fileBase . '.docx';
        $absolutePath = storage_path('app/' . $relativePath);

        if ($templateId !== 'default' && str_starts_with($templateId, 'file:') && class_exists(TemplateProcessor::class)) {
            $this->generateFromTemplate($operation, $templateId, $absolutePath, $context);
        } else {
            $this->generateDefault($operation, $absolutePath);
        }

        return [
            'relative_path' => $relativePath,
            'absolute_path' => $absolutePath,
            'size_bytes' => file_exists($absolutePath) ? filesize($absolutePath) : 0,
            'items_count' => $this->countItems($operation),
            'file_name' => sprintf('operation_%d.docx', $operation->id),
        ];
    }

    public function convertDocxToPdf(string $docxAbsolutePath): array
    {
        $outputDir = dirname($docxAbsolutePath);
        $docBase = pathinfo($docxAbsolutePath, PATHINFO_FILENAME);
        $expectedPdf = $outputDir . DIRECTORY_SEPARATOR . $docBase . '.pdf';
        $lockHandle = $this->acquireLibreOfficeLock();

        try {
            $baseArgs = [
                '--headless',
                '--nologo',
                '--nolockcheck',
                '--nodefault',
                '--norestore',
                '--convert-to',
                'pdf',
                $docxAbsolutePath,
                '--outdir',
                $outputDir,
            ];

            $binaryCandidates = $this->getSofficeBinaryCandidates();

            $attempts = 3;
            $lastError = '';
            $lastExitCode = 1;
            $lastBinary = '';

            for ($attempt = 1; $attempt <= $attempts; $attempt++) {
                foreach ($binaryCandidates as $binary) {
                    $lastBinary = $binary;

                    clearstatcache(true, $expectedPdf);
                    if (file_exists($expectedPdf)) {
                        @unlink($expectedPdf);
                    }

                    $command = array_merge([$binary], $baseArgs);
                    $process = new Process($command);
                    $process->setTimeout(120);
                    $process->run();

                    $lastExitCode = (int)$process->getExitCode();
                    $lastError = trim($process->getErrorOutput() ?: $process->getOutput());
                    $lastError = $this->sanitizeProcessOutput($lastError);

                    if ($this->waitForFileAppearance($expectedPdf, 5.0)) {
                        break 2;
                    }
                }

                if ($attempt < $attempts) {
                    usleep(600000);
                }
            }

            if (!file_exists($expectedPdf)) {
                if ($lastError === '') {
                    $lastError = sprintf(
                        'PDF не создан (exit=%d, binary=%s, expected=%s).',
                        $lastExitCode,
                        $lastBinary !== '' ? $lastBinary : 'unknown',
                        $expectedPdf
                    );
                }
                throw new \RuntimeException(
                    'Не удалось сформировать PDF-превью. Проверьте LibreOffice (soffice). ' . $lastError
                );
            }

            $relativePdf = $this->generatedDir . '/' . basename($expectedPdf);

            return [
                'relative_path' => $relativePdf,
                'absolute_path' => $expectedPdf,
                'size_bytes' => filesize($expectedPdf),
                'file_name' => basename($expectedPdf),
            ];
        } finally {
            $this->releaseLibreOfficeLock($lockHandle);
        }
    }

    protected function acquireLibreOfficeLock()
    {
        $lockPath = storage_path('app/temp/libreoffice-convert.lock');
        $dir = dirname($lockPath);
        if (!is_dir($dir)) {
            @mkdir($dir, 0777, true);
        }

        $handle = @fopen($lockPath, 'c+');
        if ($handle === false) {
            return null;
        }

        $timeoutSeconds = 20;
        $waitUntil = microtime(true) + $timeoutSeconds;
        while (microtime(true) < $waitUntil) {
            if (@flock($handle, LOCK_EX | LOCK_NB)) {
                return $handle;
            }
            usleep(200000);
        }

        @fclose($handle);
        throw new \RuntimeException('Не удалось получить блокировку конвертации PDF (LibreOffice занято).');
    }

    protected function releaseLibreOfficeLock($handle): void
    {
        if (!is_resource($handle)) {
            return;
        }

        @flock($handle, LOCK_UN);
        @fclose($handle);
    }

    protected function waitForFileAppearance(string $filePath, float $timeoutSeconds): bool
    {
        $waitUntil = microtime(true) + $timeoutSeconds;
        while (microtime(true) < $waitUntil) {
            clearstatcache(true, $filePath);
            if (file_exists($filePath)) {
                return true;
            }
            usleep(200000);
        }

        clearstatcache(true, $filePath);
        return file_exists($filePath);
    }

    protected function getSofficeBinaryCandidates(): array
    {
        $configured = trim((string)env('DOC_PREVIEW_SOFFICE_PATH', 'soffice'));
        if ($configured === '') {
            $configured = 'soffice';
        }

        $candidates = [];
        $push = static function (string $value) use (&$candidates): void {
            $value = trim($value);
            if ($value === '') {
                return;
            }

            if (!in_array($value, $candidates, true)) {
                $candidates[] = $value;
            }
        };

        $isWindows = DIRECTORY_SEPARATOR === '\\';
        if (!$isWindows) {
            $push($configured);
            return $candidates;
        }

        $normalized = str_replace('/', '\\', $configured);
        $hasExe = (bool)preg_match('/\.exe$/i', $normalized);
        $hasCom = (bool)preg_match('/\.com$/i', $normalized);

        if ($hasCom) {
            $push($configured);
            return $candidates;
        }

        if ($hasExe) {
            $comVariant = (string)preg_replace('/\.exe$/i', '.com', $configured);
            if ($comVariant !== '') {
                $push($comVariant);
            }
            $push($configured);
            return $candidates;
        }

        $push($configured . '.com');
        $push($configured . '.exe');
        $push($configured);

        return $candidates;
    }

    protected function generateDefault(Operation $operation, string $absolutePath): void
    {
        if (!class_exists(PhpWord::class)) {
            throw new \RuntimeException('Пакет phpoffice/phpword не установлен.');
        }

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('Документ по операции №' . $operation->id, ['bold' => true, 'size' => 14]);
        $section->addText('Дата: ' . $this->formatDate($operation->created_at));
        $section->addText('Тип операции: ' . ($operation->type->name ?? '-'));
        $section->addText('Контрагент: ' . ($operation->first_counteragent ?? '-'));
        $section->addTextBreak(1);

        $table = $section->addTable(['borderSize' => 6, 'borderColor' => '999999']);
        $table->addRow();
        foreach (['№', 'Наименование', 'Кол-во', 'Ед.', 'Цена', 'Сумма', 'Инв.№'] as $header) {
            $table->addCell(1700)->addText($header, ['bold' => true]);
        }

        $items = $this->extractItems($operation);
        $total = 0.0;

        foreach ($items as $idx => $item) {
            $table->addRow();
            $table->addCell(700)->addText((string)($idx + 1));
            $table->addCell(3500)->addText($item['name'] ?: '-');
            $table->addCell(1200)->addText($this->formatNumber($item['quantity']));
            $table->addCell(900)->addText($item['unit'] ?: '-');
            $table->addCell(1200)->addText($this->formatNumber($item['price']));
            $table->addCell(1200)->addText($this->formatNumber($item['sum']));
            $table->addCell(1800)->addText($item['inv_number'] ?: '-');

            $total += (float)($item['sum'] ?? 0);
        }

        $section->addTextBreak(1);
        $section->addText('Итого: ' . $this->formatNumber($total), ['bold' => true]);

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save($absolutePath);
    }

    protected function generateFromTemplate(Operation $operation, string $templateId, string $absolutePath, array $context = []): void
    {
        $templateFile = str_replace('file:', '', $templateId);
        $templatePath = storage_path('app/' . $this->templatesDir . '/' . $templateFile);

        if (!file_exists($templatePath)) {
            throw new \RuntimeException('Шаблон не найден: ' . $templateFile);
        }

        $template = new TemplateProcessor($templatePath);
        $templateVariables = method_exists($template, 'getVariables') ? (array)$template->getVariables() : [];

        $operation->loadMissing('documents');
        $documents = $operation->documents
            ? $operation->documents->sortBy('id')->values()
            : collect();
        $firstDocument = $documents->count() ? $documents->first() : null;
        $requestedDocumentName = trim((string)($context['document_name'] ?? ''));
        $selectedDocument = $this->resolveDocumentByName($documents, $requestedDocumentName)
            ?? $this->resolveDocumentForTemplate($documents, $templateFile)
            ?? $firstDocument;

        $documentRows = $documents->map(function ($document, $index) {
            return [
                'doc_row_no' => (string)($index + 1),
                'doc_row_name' => trim((string)($document->doc_name ?? '')) ?: '-',
                'doc_row_num' => trim((string)($document->doc_num ?? '')) ?: '-',
                'doc_row_date' => $this->formatDate($document->doc_date ?? null),
            ];
        })->values()->all();

        $template->setValue('operation_number', (string)$operation->id);
        $template->setValue('operation_date', $this->formatDate($operation->created_at));
        $template->setValue('operation_type', $operation->type->name ?? '-');
        $template->setValue('counteragent', $operation->first_counteragent ?? '-');
        $template->setValue('doc_name', (string)($selectedDocument->doc_name ?? ''));
        $template->setValue('doc_num', (string)($selectedDocument->doc_num ?? ''));
        $template->setValue('doc_count', (string)count($documentRows));
        $template->setValue('doc_date', $this->formatDate($selectedDocument->doc_date ?? null));

        $docSettings = $this->normalizeDocSettings($context['settings'] ?? []);
        $template->setValue('receiver_name', $docSettings['receiver_name']);
        $template->setValue('receiver', $docSettings['receiver_name']);
        $template->setValue('doc_receiver', $docSettings['receiver_name']);
        $template->setValue('receiver_position', $docSettings['receiver_position']);
        $template->setValue('receiver_full', $docSettings['receiver_full']);
        $template->setValue('commission_head', $docSettings['commission_head']);
        $template->setValue('commission_head_name', $docSettings['commission_head_name']);
        $template->setValue('commission_head_position', $docSettings['commission_head_position']);
        $template->setValue('commission_member_1', $docSettings['commission_member_1']);
        $template->setValue('commission_member_1_name', $docSettings['commission_member_1_name']);
        $template->setValue('commission_member_1_position', $docSettings['commission_member_1_position']);
        $template->setValue('commission_member_2', $docSettings['commission_member_2']);
        $template->setValue('commission_member_2_name', $docSettings['commission_member_2_name']);
        $template->setValue('commission_member_2_position', $docSettings['commission_member_2_position']);
        $template->setValue('commission_member_3', $docSettings['commission_member_3']);
        $template->setValue('commission_member_3_name', $docSettings['commission_member_3_name']);
        $template->setValue('commission_member_3_position', $docSettings['commission_member_3_position']);
        $template->setValue('commission_members', $docSettings['commission_members']);
        $template->setValue('responsible_person', $docSettings['responsible_person']);
        $template->setValue('materially_responsible_person', $docSettings['responsible_person']);
        $template->setValue('responsible_person_name', $docSettings['responsible_person_name']);
        $template->setValue('responsible_person_position', $docSettings['responsible_person_position']);
        $template->setValue('responsible_person_full', $docSettings['responsible_person_full']);
        $template->setValue('materially_responsible_person_name', $docSettings['materially_responsible_person_name']);
        $template->setValue('materially_responsible_person_position', $docSettings['materially_responsible_person_position']);
        $template->setValue('materially_responsible_person_full', $docSettings['materially_responsible_person_full']);
        $template->setValue('edrpou', $docSettings['edrpou']);
        $template->setValue('organization_edrpou', $docSettings['edrpou']);
        $template->setValue('org_edrpou', $docSettings['edrpou']);
        $template->setValue('document_year', $docSettings['document_year']);
        $template->setValue('doc_year', $docSettings['document_year']);
        $template->setValue('year', $docSettings['document_year']);
        $template->setValue('commission_order_details', $docSettings['commission_order_details']);
        $template->setValue('commission_order', $docSettings['commission_order_details']);
        $template->setValue('commission_order_info', $docSettings['commission_order_details']);

        if (!empty($documentRows)) {
            $docCloneAnchor = $this->resolveDocumentCloneAnchor($templateVariables);
            if ($docCloneAnchor !== null) {
                try {
                    $template->cloneRowAndSetValues($docCloneAnchor, $documentRows);
                } catch (\Throwable $e) {
                    $this->fillDocumentRowsFallback($template, $documentRows);
                }
            } else {
                $this->fillDocumentRowsFallback($template, $documentRows);
            }
        } else {
            $template->setValue('doc_row_no', '-');
            $template->setValue('doc_row_name', '-');
            $template->setValue('doc_row_num', '-');
            $template->setValue('doc_row_date', '-');
        }

        $rows = [];
        $items = $this->extractItems($operation);
        $totalQuantity = 0.0;
        $totalSum = 0.0;

        foreach ($items as $idx => $item) {
            $totalQuantity += $this->toFloat($item['quantity'] ?? null);
            $totalSum += $this->toFloat($item['sum'] ?? null);

            $rows[] = [
                'row_no' => (string)($idx + 1),
                'row_name' => $item['name'] ?: '-',
                'row_quantity' => $this->formatNumber($item['quantity']),
                'row_unit' => $item['unit'] ?: '-',
                'row_price' => $this->formatNumber($item['price']),
                'row_sum' => $this->formatNumber($item['sum']),
                'row_inv_number' => $item['inv_number'] ?: '-',
            ];
        }

        $template->setValue('total_quantity', $this->formatNumber($totalQuantity));
        $template->setValue('total_sum', $this->formatNumber($totalSum));
        $template->setValue('operation_total_quantity', $this->formatNumber($totalQuantity));
        $template->setValue('operation_total_sum', $this->formatNumber($totalSum));
        $template->setValue('total_quantity_text', $this->quantityToUkrainianText($totalQuantity));
        $template->setValue('total_sum_text', $this->moneyToUkrainianText($totalSum));

        if (!empty($rows)) {
            $cloneAnchor = $this->resolveRowCloneAnchor($templateVariables);

            if ($cloneAnchor !== null) {
                try {
                    $template->cloneRowAndSetValues($cloneAnchor, $rows);
                } catch (\Throwable $e) {
                    $this->fillRowsFallback($template, $rows);
                }
            } else {
                $this->fillRowsFallback($template, $rows);
            }
        } else {
            $template->setValue('row_no', '-');
            $template->setValue('row_name', '-');
            $template->setValue('row_quantity', '-');
            $template->setValue('row_unit', '-');
            $template->setValue('row_price', '-');
            $template->setValue('row_sum', '-');
            $template->setValue('row_inv_number', '-');
        }

        $template->saveAs($absolutePath);
    }

    protected function extractItems(Operation $operation): array
    {
        $operation->loadMissing(['products', 'type']);

        if ($operation->products && $operation->products->count()) {
            return $operation->products->map(function ($product) {
                $quantity = $product->pivot->quantity ?? null;
                $price = $product->price ?? null;
                $sum = $product->pivot->sum ?? null;

                if (($sum === null || $sum === '') && $quantity !== null && $price !== null) {
                    $sum = (float)$quantity * (float)$price;
                }

                return [
                    'name' => $product->name,
                    'quantity' => $quantity,
                    'unit' => $product->unit,
                    'price' => $price,
                    'sum' => $sum,
                    'inv_number' => $product->inv_number,
                ];
            })->values()->all();
        }

        $draft = $operation->draft_products;
        if (is_string($draft)) {
            $decoded = json_decode($draft, true);
            $draft = is_array($decoded) ? $decoded : [];
        }

        if (!is_array($draft)) {
            return [];
        }

        return array_map(function ($item) {
            $quantity = $item['quantity'] ?? null;
            $price = $item['price'] ?? null;
            $sum = $item['sum'] ?? null;
            if (($sum === null || $sum === '') && $quantity !== null && $price !== null) {
                $sum = (float)$quantity * (float)$price;
            }

            return [
                'name' => $item['name'] ?? null,
                'quantity' => $quantity,
                'unit' => $item['unit'] ?? null,
                'price' => $price,
                'sum' => $sum,
                'inv_number' => $item['inv_number'] ?? null,
            ];
        }, $draft);
    }

    protected function formatDate($value): string
    {
        if (!$value) {
            return '-';
        }

        return Carbon::parse($value)
            ->locale(app()->getLocale() ?: 'ru')
            ->translatedFormat('d.m.Y');
    }

    protected function formatNumber($value): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        if (class_exists(\NumberFormatter::class)) {
            $formatter = new \NumberFormatter(app()->getLocale() ?: 'ru_RU', \NumberFormatter::DECIMAL);
            $formatter->setAttribute(\NumberFormatter::MIN_FRACTION_DIGITS, 2);
            $formatter->setAttribute(\NumberFormatter::MAX_FRACTION_DIGITS, 2);
            $formatted = $formatter->format((float)$value);
            if ($formatted !== false) {
                return $formatted;
            }
        }

        return number_format((float)$value, 2, ',', ' ');
    }

    protected function toFloat($value): float
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float)$value;
        }

        if (is_string($value)) {
            $normalized = str_replace(["\xC2\xA0", ' '], '', $value);
            $normalized = str_replace(',', '.', $normalized);
            $normalized = preg_replace('/[^0-9\.\-]/', '', $normalized) ?? '';

            if ($normalized === '' || $normalized === '-' || $normalized === '.') {
                return 0.0;
            }

            return (float)$normalized;
        }

        return (float)$value;
    }

    protected function resolveRowCloneAnchor(array $templateVariables): ?string
    {
        $preferred = ['row_no', 'row_name', 'row_quantity', 'row_unit', 'row_price', 'row_sum', 'row_inv_number'];

        if (empty($templateVariables)) {
            return 'row_no';
        }

        foreach ($preferred as $variable) {
            if (in_array($variable, $templateVariables, true)) {
                return $variable;
            }
        }

        return null;
    }

    protected function fillRowsFallback(TemplateProcessor $template, array $rows): void
    {
        $keys = ['row_no', 'row_name', 'row_quantity', 'row_unit', 'row_price', 'row_sum', 'row_inv_number'];

        foreach ($keys as $key) {
            $values = array_column($rows, $key);
            $values = array_values(array_filter($values, static fn($value) => $value !== null && $value !== ''));
            $template->setValue($key, !empty($values) ? implode("\n", $values) : '-');
        }
    }

    protected function resolveDocumentCloneAnchor(array $templateVariables): ?string
    {
        $preferred = ['doc_row_no', 'doc_row_name', 'doc_row_num', 'doc_row_date'];

        if (empty($templateVariables)) {
            return 'doc_row_no';
        }

        foreach ($preferred as $variable) {
            if (in_array($variable, $templateVariables, true)) {
                return $variable;
            }
        }

        return null;
    }

    protected function fillDocumentRowsFallback(TemplateProcessor $template, array $rows): void
    {
        $keys = ['doc_row_no', 'doc_row_name', 'doc_row_num', 'doc_row_date'];

        foreach ($keys as $key) {
            $values = array_column($rows, $key);
            $values = array_values(array_filter($values, static fn($value) => $value !== null && $value !== ''));
            $template->setValue($key, !empty($values) ? implode("\n", $values) : '-');
        }
    }

    protected function resolveDocumentForTemplate($documents, string $templateFile)
    {
        if (!$documents || !$documents->count()) {
            return null;
        }

        $templateBaseName = pathinfo($templateFile, PATHINFO_FILENAME);
        $normalizedTemplateName = $this->normalizeTemplateName($templateBaseName);

        if ($normalizedTemplateName === '') {
            return $documents->first();
        }

        $bestDocument = null;
        $bestScore = -1;

        foreach ($documents as $document) {
            $documentName = trim((string)($document->doc_name ?? ''));
            $normalizedDocumentName = $this->normalizeTemplateName($documentName);

            if ($normalizedDocumentName === '') {
                continue;
            }

            if ($normalizedDocumentName === $normalizedTemplateName) {
                return $document;
            }

            $score = 0;

            if (str_contains($normalizedDocumentName, $normalizedTemplateName)
                || str_contains($normalizedTemplateName, $normalizedDocumentName)
            ) {
                $score += 10;
            }

            $templateTokens = array_values(array_filter(explode(' ', $normalizedTemplateName)));
            $documentTokens = array_values(array_filter(explode(' ', $normalizedDocumentName)));
            if (!empty($templateTokens) && !empty($documentTokens)) {
                $commonTokens = array_intersect($templateTokens, $documentTokens);
                $score += count($commonTokens);
            }

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestDocument = $document;
            }
        }

        return $bestDocument ?? $documents->first();
    }

    protected function resolveDocumentByName($documents, string $documentName)
    {
        if ($documentName === '' || !$documents || !$documents->count()) {
            return null;
        }

        $normalizedRequested = $this->normalizeTemplateName($documentName);
        if ($normalizedRequested === '') {
            return null;
        }

        foreach ($documents as $document) {
            $docName = trim((string)($document->doc_name ?? ''));
            if ($docName === '') {
                continue;
            }

            if ($this->normalizeTemplateName($docName) === $normalizedRequested) {
                return $document;
            }
        }

        return null;
    }

    protected function normalizeDocSettings($settings): array
    {
        if (!is_array($settings)) {
            $settings = [];
        }

        $personKeys = [
            'receiver_name',
            'commission_head',
            'commission_member_1',
            'commission_member_2',
            'commission_member_3',
            'responsible_person',
        ];

        $fieldKeys = [
            'edrpou',
            'document_year',
            'commission_order_details',
        ];

        $result = [];
        $globalDefaults = DocumentTemplateSettings::extractDocDefaults();
        $settingsSnapshot = DocumentTemplateSettings::get();

        foreach ($personKeys as $key) {
            $value = trim((string)($settings[$key] ?? ''));
            if ($value === '') {
                $value = trim((string)($globalDefaults[$key] ?? ''));
            }
            $result[$key] = $value !== '' ? $value : '-';
        }

        foreach ($fieldKeys as $key) {
            $value = trim((string)($settings[$key] ?? ''));
            if ($key === 'document_year') {
                $value = preg_replace('/\D+/', '', $value);
                $value = $value !== '' ? substr($value, 0, 4) : '';
            }

            if ($value === '') {
                $value = trim((string)($settingsSnapshot[$key] ?? ''));
            }

            if ($key === 'document_year' && $value === '') {
                $value = (string)date('Y');
            }

            $result[$key] = $value !== '' ? $value : '-';
        }

        $commissionRoleKeys = [
            'commission_head',
            'commission_member_1',
            'commission_member_2',
            'commission_member_3',
        ];

        foreach ($commissionRoleKeys as $roleKey) {
            $explicitName = trim((string)($settings[$roleKey . '_name'] ?? ''));
            $explicitPosition = trim((string)($settings[$roleKey . '_position'] ?? ''));
            $currentValue = trim((string)($result[$roleKey] ?? ''));

            $name = $explicitName !== '' ? $explicitName : ($currentValue !== '-' ? $currentValue : '');
            $position = $explicitPosition;

            $result[$roleKey . '_name'] = $name !== '' ? $name : '-';
            $result[$roleKey . '_position'] = $position !== '' ? $position : '-';
            $result[$roleKey] = $this->composeRoleDisplay($name, $position);
        }

        $receiverName = trim((string)($settings['receiver_name_name'] ?? $settings['receiver_name'] ?? ''));
        $receiverPosition = trim((string)($settings['receiver_name_position'] ?? $settings['receiver_position'] ?? ''));
        $result['receiver_name'] = $receiverName !== '' ? $receiverName : '-';
        $result['receiver_position'] = $receiverPosition !== '' ? $receiverPosition : '-';
        $result['receiver_full'] = $this->composeRoleDisplay($receiverName, $receiverPosition);

        $responsibleName = trim((string)($settings['responsible_person_name'] ?? $settings['responsible_person'] ?? ''));
        $responsiblePosition = trim((string)($settings['responsible_person_position'] ?? ''));
        $result['responsible_person'] = $responsibleName !== '' ? $responsibleName : '-';
        $result['responsible_person_name'] = $responsibleName !== '' ? $responsibleName : '-';
        $result['responsible_person_position'] = $responsiblePosition !== '' ? $responsiblePosition : '-';
        $result['responsible_person_full'] = $this->composeRoleDisplay($responsibleName, $responsiblePosition);
        $result['materially_responsible_person'] = $result['responsible_person'];
        $result['materially_responsible_person_name'] = $result['responsible_person_name'];
        $result['materially_responsible_person_position'] = $result['responsible_person_position'];
        $result['materially_responsible_person_full'] = $result['responsible_person_full'];

        $commissionMembers = trim((string)($settings['commission_members'] ?? ''));
        if ($commissionMembers === '') {
            $commissionMembers = trim(implode(', ', array_filter([
                $result['commission_member_1'] ?? '',
                $result['commission_member_2'] ?? '',
                $result['commission_member_3'] ?? '',
            ], static function ($value) {
                $value = trim((string)$value);
                return $value !== '' && $value !== '-';
            })));
        }
        $result['commission_members'] = $commissionMembers !== '' ? $commissionMembers : '-';

        return $result;
    }

    protected function composeRoleDisplay(string $name, string $position): string
    {
        $name = trim($name);
        $position = trim($position);

        if ($position !== '' && $name !== '') {
            return $position . ' ' . $name;
        }

        if ($position !== '') {
            return $position;
        }

        if ($name !== '') {
            return $name;
        }

        return '-';
    }

    protected function quantityToUkrainianText(float $value): string
    {
        $normalized = number_format(round($value, 3), 3, '.', '');
        [$wholePart, $fractionPart] = explode('.', $normalized);

        $whole = (int)$wholePart;
        $wholeText = $this->numberToUkrainianWords($whole, 'feminine');
        $fractionRaw = rtrim($fractionPart, '0');

        if ($fractionRaw === '') {
            $unitsWord = $this->pluralForm($whole, 'одиниця', 'одиниці', 'одиниць');
            return $this->capitalizeFirst($wholeText . ' цілих ' . $unitsWord);
        }

        $fractionNumber = (int)$fractionRaw;
        $fractionDigits = strlen($fractionRaw);

        $fractionName = match ($fractionDigits) {
            1 => 'десятих',
            2 => 'сотих',
            default => 'тисячних',
        };

        return $this->capitalizeFirst(
            $wholeText
            . ' цілих '
            . $fractionNumber
            . ' '
            . $fractionName
            . ' одиниць'
        );
    }

    protected function moneyToUkrainianText(float $value): string
    {
        $normalized = number_format(round($value, 2), 2, '.', '');
        [$wholePart, $fractionPart] = explode('.', $normalized);

        $whole = (int)$wholePart;
        $kopecks = (int)$fractionPart;

        $wholeText = $this->numberToUkrainianWords($whole, 'feminine');

        return $this->capitalizeFirst($wholeText . ' грн. ' . str_pad((string)$kopecks, 2, '0', STR_PAD_LEFT) . ' коп.');
    }

    protected function numberToUkrainianWords(int $number, string $gender = 'masculine'): string
    {
        if ($number === 0) {
            return 'нуль';
        }

        $isNegative = $number < 0;
        $number = abs($number);

        $groups = [];
        while ($number > 0) {
            $groups[] = $number % 1000;
            $number = intdiv($number, 1000);
        }

        $groupNames = [
            0 => ['', '', ''],
            1 => ['тисяча', 'тисячі', 'тисяч'],
            2 => ['мільйон', 'мільйони', 'мільйонів'],
            3 => ['мільярд', 'мільярди', 'мільярдів'],
            4 => ['трильйон', 'трильйони', 'трильйонів'],
        ];

        $parts = [];
        for ($index = count($groups) - 1; $index >= 0; $index--) {
            $groupValue = $groups[$index];
            if ($groupValue === 0) {
                continue;
            }

            $groupGender = $index === 1 ? 'feminine' : ($index === 0 ? $gender : 'masculine');
            $parts[] = $this->tripletToWords($groupValue, $groupGender);

            if ($index > 0) {
                $forms = $groupNames[$index] ?? ['', '', ''];
                $parts[] = $this->pluralForm($groupValue, $forms[0], $forms[1], $forms[2]);
            }
        }

        $result = trim(implode(' ', array_filter($parts)));

        return $isNegative ? ('мінус ' . $result) : $result;
    }

    protected function tripletToWords(int $value, string $gender = 'masculine'): string
    {
        $hundredsMap = [
            1 => 'сто',
            2 => 'двісті',
            3 => 'триста',
            4 => 'чотириста',
            5 => 'п\'ятсот',
            6 => 'шістсот',
            7 => 'сімсот',
            8 => 'вісімсот',
            9 => 'дев\'ятсот',
        ];

        $tensMap = [
            2 => 'двадцять',
            3 => 'тридцять',
            4 => 'сорок',
            5 => 'п\'ятдесят',
            6 => 'шістдесят',
            7 => 'сімдесят',
            8 => 'вісімдесят',
            9 => 'дев\'яносто',
        ];

        $teensMap = [
            10 => 'десять',
            11 => 'одинадцять',
            12 => 'дванадцять',
            13 => 'тринадцять',
            14 => 'чотирнадцять',
            15 => 'п\'ятнадцять',
            16 => 'шістнадцять',
            17 => 'сімнадцять',
            18 => 'вісімнадцять',
            19 => 'дев\'ятнадцять',
        ];

        $unitsMasculine = [
            1 => 'один',
            2 => 'два',
            3 => 'три',
            4 => 'чотири',
            5 => 'п\'ять',
            6 => 'шість',
            7 => 'сім',
            8 => 'вісім',
            9 => 'дев\'ять',
        ];

        $unitsFeminine = [
            1 => 'одна',
            2 => 'дві',
            3 => 'три',
            4 => 'чотири',
            5 => 'п\'ять',
            6 => 'шість',
            7 => 'сім',
            8 => 'вісім',
            9 => 'дев\'ять',
        ];

        $parts = [];

        $hundreds = intdiv($value, 100);
        $remainder = $value % 100;
        $tens = intdiv($remainder, 10);
        $units = $remainder % 10;

        if ($hundreds > 0) {
            $parts[] = $hundredsMap[$hundreds] ?? '';
        }

        if ($remainder >= 10 && $remainder <= 19) {
            $parts[] = $teensMap[$remainder] ?? '';
        } else {
            if ($tens > 1) {
                $parts[] = $tensMap[$tens] ?? '';
            }

            if ($units > 0) {
                $unitsMap = $gender === 'feminine' ? $unitsFeminine : $unitsMasculine;
                $parts[] = $unitsMap[$units] ?? '';
            }
        }

        return trim(implode(' ', array_filter($parts)));
    }

    protected function pluralForm(int $number, string $one, string $few, string $many): string
    {
        $n = abs($number) % 100;
        $n1 = $n % 10;

        if ($n > 10 && $n < 20) {
            return $many;
        }

        if ($n1 > 1 && $n1 < 5) {
            return $few;
        }

        if ($n1 === 1) {
            return $one;
        }

        return $many;
    }

    protected function capitalizeFirst(string $text): string
    {
        if ($text === '') {
            return '';
        }

        if (function_exists('mb_strtoupper') && function_exists('mb_substr')) {
            return mb_strtoupper(mb_substr($text, 0, 1), 'UTF-8') . mb_substr($text, 1);
        }

        return ucfirst($text);
    }

    protected function normalizeTemplateName(string $value): string
    {
        $normalized = mb_strtolower(trim($value));
        $normalized = str_replace(['.docx', '.doc'], '', $normalized);
        $normalized = strtr($normalized, [
            'ё' => 'е',
            'і' => 'и',
            'ї' => 'и',
            'є' => 'е',
            'ґ' => 'г',
        ]);
        $normalized = preg_replace('/[^a-z0-9а-я\s]/u', ' ', $normalized) ?? '';
        $normalized = preg_replace('/\s+/u', ' ', $normalized) ?? '';

        return trim($normalized);
    }

    protected function sanitizeProcessOutput(string $text): string
    {
        if ($text === '') {
            return '';
        }

        $normalized = str_replace(["\r\n", "\r"], "\n", $text);

        if (function_exists('mb_check_encoding') && mb_check_encoding($normalized, 'UTF-8')) {
            return $normalized;
        }

        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($normalized, 'UTF-8', 'UTF-8,Windows-1251,CP1251,CP866,KOI8-R,ISO-8859-1');
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('Windows-1251', 'UTF-8//IGNORE', $normalized);
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        $clean = @preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{0400}-\x{04FF}]/u', '', $normalized);
        return is_string($clean) ? $clean : 'Ошибка кодировки вывода процесса.';
    }
}
