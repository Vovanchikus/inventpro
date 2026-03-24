<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Input;
use Log;
use DB;

class ImportExcel extends ComponentBase
{
    protected const EPSILON = 0.0001;

    protected function looksLikeInvNumber($value)
    {
        $normalized = $this->normalizeInvNumber($value);
        if ($normalized === '') {
            return false;
        }

        return (bool) preg_match('/^\d+(?:\/\d+)?(?:[,;]\d+(?:\/\d+)?)*$/u', $normalized);
    }

    protected function parseInvData($value)
    {
        $normalized = $this->normalizeInvNumber($value);
        if ($normalized === '') {
            return [
                'normalized' => '',
                'primary' => '',
                'tokens' => [],
            ];
        }

        $tokens = [];
        $parts = preg_split('/[,;]+/u', $normalized) ?: [];

        foreach ($parts as $part) {
            $part = trim($part);
            if ($part === '') {
                continue;
            }

            $tokens[$part] = true;

            if (strpos($part, '/') !== false) {
                $slashParts = preg_split('/\//u', $part) ?: [];
                $base = trim($slashParts[0] ?? '');
                if ($base !== '') {
                    $tokens[$base] = true;
                }
            }
        }

        $primary = '';
        $firstPart = trim((preg_split('/[,;]+/u', $normalized) ?: [''])[0] ?? '');
        if ($firstPart !== '') {
            if (strpos($firstPart, '/') !== false) {
                $firstSlashParts = preg_split('/\//u', $firstPart) ?: [];
                $primary = trim($firstSlashParts[0] ?? '');
            } else {
                $primary = $firstPart;
            }
        }

        if ($primary !== '') {
            $tokens[$primary] = true;
        }

        $tokens[$normalized] = true;

        return [
            'normalized' => $normalized,
            'primary' => $primary,
            'tokens' => array_keys($tokens),
        ];
    }

    protected function buildProductLookup($products)
    {
        $byExact = [];
        $byToken = [];
        $meta = [];

        foreach ($products as $product) {
            $parsed = $this->parseInvData($product->inv_number);
            $normalized = $parsed['normalized'];

            $meta[$product->id] = [
                'tokens' => $parsed['tokens'],
                'normalized' => $normalized,
            ];

            if ($normalized !== '') {
                if (!isset($byExact[$normalized])) {
                    $byExact[$normalized] = [];
                }
                $byExact[$normalized][] = $product;
            }

            foreach ($parsed['tokens'] as $token) {
                if (!isset($byToken[$token])) {
                    $byToken[$token] = [];
                }
                $byToken[$token][$product->id] = $product;
            }
        }

        return [
            'byExact' => $byExact,
            'byToken' => $byToken,
            'meta' => $meta,
        ];
    }

    protected function areInvNumbersEquivalent($left, $right)
    {
        $leftParsed = $this->parseInvData($left);
        $rightParsed = $this->parseInvData($right);

        $leftNormalized = $leftParsed['normalized'] ?? '';
        $rightNormalized = $rightParsed['normalized'] ?? '';

        if ($leftNormalized === '' || $rightNormalized === '') {
            return false;
        }

        if ($leftNormalized === $rightNormalized) {
            return true;
        }

        $leftTokens = array_fill_keys($leftParsed['tokens'] ?? [], true);
        foreach (($rightParsed['tokens'] ?? []) as $token) {
            if (isset($leftTokens[$token])) {
                return true;
            }
        }

        return false;
    }

    protected function resolveProductByInv($excelInvRaw, $lookup)
    {
        $parsedExcel = $this->parseInvData($excelInvRaw);
        $normalized = $parsedExcel['normalized'];
        $tokens = $parsedExcel['tokens'];

        if ($normalized === '') {
            return [
                'status' => 'none',
                'product' => null,
                'candidates' => [],
                'excel_inv_normalized' => '',
                'excel_inv_tokens' => [],
            ];
        }

        $byExact = $lookup['byExact'] ?? [];
        $byToken = $lookup['byToken'] ?? [];
        $meta = $lookup['meta'] ?? [];

        $exactMatches = $byExact[$normalized] ?? [];
        if (count($exactMatches) === 1) {
            return [
                'status' => 'exact',
                'product' => $exactMatches[0],
                'candidates' => [],
                'excel_inv_normalized' => $normalized,
                'excel_inv_tokens' => $tokens,
            ];
        }

        if (count($exactMatches) > 1) {
            return [
                'status' => 'ambiguous',
                'product' => null,
                'candidates' => $exactMatches,
                'excel_inv_normalized' => $normalized,
                'excel_inv_tokens' => $tokens,
            ];
        }

        $candidateScores = [];
        foreach ($tokens as $token) {
            $productsByToken = $byToken[$token] ?? [];
            foreach ($productsByToken as $candidateProduct) {
                $candidateId = $candidateProduct->id;
                if (!isset($candidateScores[$candidateId])) {
                    $candidateScores[$candidateId] = [
                        'product' => $candidateProduct,
                        'score' => 0,
                    ];
                }
                $candidateScores[$candidateId]['score']++;
            }
        }

        if (empty($candidateScores)) {
            return [
                'status' => 'none',
                'product' => null,
                'candidates' => [],
                'excel_inv_normalized' => $normalized,
                'excel_inv_tokens' => $tokens,
            ];
        }

        uasort($candidateScores, function ($a, $b) {
            if ($a['score'] === $b['score']) {
                return ($a['product']->id <=> $b['product']->id);
            }

            return $b['score'] <=> $a['score'];
        });

        $scoredCandidates = array_values($candidateScores);
        $top = $scoredCandidates[0];
        $second = $scoredCandidates[1] ?? null;

        if (!$second || $top['score'] > $second['score']) {
            return [
                'status' => 'token',
                'product' => $top['product'],
                'candidates' => [],
                'excel_inv_normalized' => $normalized,
                'excel_inv_tokens' => $tokens,
            ];
        }

        $ambiguousCandidates = array_map(function ($item) {
            return $item['product'];
        }, $scoredCandidates);

        return [
            'status' => 'ambiguous',
            'product' => null,
            'candidates' => $ambiguousCandidates,
            'excel_inv_normalized' => $normalized,
            'excel_inv_tokens' => $tokens,
        ];
    }

    protected function normalizeInvNumber($value)
    {
        if ($value === null) {
            return '';
        }

        $value = (string)$value;
        $value = str_replace(['\\', '／', '⁄', '∕'], '/', $value);
        $value = preg_replace('/[\s\x{00A0}\x{202F}\x{2000}-\x{200F}\x{2060}-\x{2064}\x{FEFF}]+/u', '', $value);
        return trim($value);
    }

    protected function canonicalInvPrimary($value)
    {
        $normalized = $this->normalizeInvNumber($value);
        if ($normalized === '') {
            return '';
        }

        $normalized = preg_replace('/[^\p{L}\p{N}]+/u', '', $normalized);
        return function_exists('mb_strtolower')
            ? mb_strtolower($normalized, 'UTF-8')
            : strtolower($normalized);
    }

    protected function parseNumeric($value)
    {
        if ($value === null || $value === '') {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $clean = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', (string) $value);
        $clean = str_replace(',', '.', $clean);

        return (float) $clean;
    }

    protected function getIncomingOperationsForProduct($productId)
    {
        $organizationId = $this->organizationId();
        if ($organizationId <= 0) {
            return [];
        }

        $rows = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'o.id', '=', 'op.operation_id')
            ->join('samvol_inventory_operation_types as t', 't.id', '=', 'o.type_id')
            ->leftJoin('samvol_inventory_documents as d', 'd.operation_id', '=', 'o.id')
            ->where('op.product_id', $productId)
            ->where('o.organization_id', $organizationId)
            ->whereIn(DB::raw('LOWER(t.name)'), [
                'приход',
                'импорт',
                'импорт приход',
            ])
            ->select(
                'op.operation_id',
                'op.quantity',
                'op.sum',
                'op.counteragent',
                't.name as operation_type',
                DB::raw('MIN(d.doc_num) as doc_num'),
                DB::raw('MIN(d.doc_date) as doc_date')
            )
            ->groupBy('op.operation_id', 'op.quantity', 'op.sum', 'op.counteragent', 't.name')
            ->orderBy('doc_date')
            ->orderBy('op.operation_id')
            ->get();

        return $rows->map(function ($row) {
            return [
                'operation_id' => (int) $row->operation_id,
                'quantity' => (float) $row->quantity,
                'sum' => (float) $row->sum,
                'counteragent' => $row->counteragent,
                'operation_type' => $row->operation_type,
                'doc_num' => $row->doc_num,
                'doc_date' => $row->doc_date,
            ];
        })->values()->all();
    }

    protected function applySplitResolutions($splitCandidates, $splitResolutions, &$warnings)
    {
        $organizationId = $this->organizationId();
        if ($organizationId <= 0) {
            return [];
        }

        if (!is_array($splitCandidates) || !is_array($splitResolutions)) {
            return [];
        }

        $affectedBaseProductIds = [];

        $candidateByBase = [];
        foreach ($splitCandidates as $candidate) {
            $baseId = (int) ($candidate['base_product_id'] ?? 0);
            if ($baseId > 0) {
                $candidateByBase[$baseId] = $candidate;
            }
        }

        $resolutionsByBase = [];
        foreach ($splitResolutions as $resolution) {
            $baseId = (int) ($resolution['base_product_id'] ?? 0);
            $operationId = (int) ($resolution['operation_id'] ?? 0);
            $excelInv = $this->normalizeInvNumber($resolution['excel_inv_number'] ?? null);
            if ($baseId <= 0 || $operationId <= 0 || $excelInv === '') {
                continue;
            }

            if (!isset($resolutionsByBase[$baseId])) {
                $resolutionsByBase[$baseId] = [];
            }

            $resolutionsByBase[$baseId][$excelInv] = [
                'operation_id' => $operationId,
                'excel_inv_number' => $excelInv,
            ];
        }

        foreach ($candidateByBase as $baseId => $candidate) {
            $baseProduct = Product::find($baseId);
            if (!$baseProduct) {
                continue;
            }

            $rows = is_array($candidate['rows'] ?? null) ? $candidate['rows'] : [];
            if (count($rows) < 2) {
                continue;
            }

            $availableOperations = [];
            foreach ((array) ($candidate['operations'] ?? []) as $op) {
                $opId = (int) ($op['operation_id'] ?? 0);
                if ($opId > 0) {
                    $availableOperations[$opId] = true;
                }
            }

            $baseResolutions = $resolutionsByBase[$baseId] ?? [];
            $resolvedRows = [];
            $usedOperationIds = [];
            $invalidCandidate = false;

            foreach ($rows as $row) {
                $excelInv = $this->normalizeInvNumber($row['excel_inv_number'] ?? null);
                if ($excelInv === '' || !isset($baseResolutions[$excelInv])) {
                    $invalidCandidate = true;
                    break;
                }

                $opId = (int) ($baseResolutions[$excelInv]['operation_id'] ?? 0);
                if ($opId <= 0 || isset($usedOperationIds[$opId]) || !isset($availableOperations[$opId])) {
                    $invalidCandidate = true;
                    break;
                }

                $usedOperationIds[$opId] = true;
                $resolvedRows[] = [
                    'excel_inv_number' => $excelInv,
                    'excel_quantity' => $this->parseNumeric($row['excel_quantity'] ?? 0),
                    'excel_price' => $this->parseNumeric($row['excel_price'] ?? 0),
                    'operation_id' => $opId,
                ];
            }

            if ($invalidCandidate || count($resolvedRows) !== count($rows)) {
                $warnings[] = "Не вдалося застосувати розподіл для {$baseProduct->name}: неповні або некоректні відповідності";
                continue;
            }

            $primaryRow = $resolvedRows[0];
            $existingPrimaryInv = Product::where('inv_number', $primaryRow['excel_inv_number'])
                ->where('id', '!=', $baseProduct->id)
                ->first();

            if (!$existingPrimaryInv) {
                $baseProduct->inv_number = $primaryRow['excel_inv_number'];
                $baseProduct->price = $primaryRow['excel_price'];
                $baseProduct->save();
            }

            foreach ($resolvedRows as $index => $row) {
                if ($index === 0) {
                    continue;
                }

                $targetProduct = Product::where('inv_number', $row['excel_inv_number'])->first();
                if (!$targetProduct) {
                    $targetProduct = Product::create([
                        'name' => $baseProduct->name,
                        'unit' => $baseProduct->unit,
                        'inv_number' => $row['excel_inv_number'],
                        'price' => $row['excel_price'],
                        'organization_id' => $organizationId,
                    ]);
                }

                $targetProduct->price = $row['excel_price'];
                $targetProduct->save();

                $sourcePivot = DB::table('samvol_inventory_operation_products')
                    ->where('operation_id', $row['operation_id'])
                    ->where('product_id', $baseProduct->id)
                    ->where('organization_id', $organizationId)
                    ->first();

                if (!$sourcePivot) {
                    $warnings[] = "Операція {$row['operation_id']} не знайдена для товару {$baseProduct->inv_number}";
                    continue;
                }

                $sourceQty = $this->parseNumeric($sourcePivot->quantity ?? 0);
                $requiredQty = $this->parseNumeric($row['excel_quantity'] ?? 0);
                if (abs($sourceQty - $requiredQty) > self::EPSILON) {
                    $warnings[] = "Операція {$row['operation_id']} має кількість {$sourceQty}, очікувалось {$requiredQty}";
                    continue;
                }

                $existingTargetPivot = DB::table('samvol_inventory_operation_products')
                    ->where('operation_id', $row['operation_id'])
                    ->where('product_id', $targetProduct->id)
                    ->where('organization_id', $organizationId)
                    ->first();

                if ($existingTargetPivot) {
                    DB::table('samvol_inventory_operation_products')
                        ->where('id', $existingTargetPivot->id)
                        ->where('organization_id', $organizationId)
                        ->update([
                            'quantity' => $this->parseNumeric($existingTargetPivot->quantity ?? 0) + $sourceQty,
                            'sum' => $this->parseNumeric($existingTargetPivot->sum ?? 0) + $this->parseNumeric($sourcePivot->sum ?? 0),
                            'counteragent' => $sourcePivot->counteragent ?? $existingTargetPivot->counteragent,
                        ]);

                    DB::table('samvol_inventory_operation_products')
                        ->where('id', $sourcePivot->id)
                        ->where('organization_id', $organizationId)
                        ->delete();
                } else {
                    DB::table('samvol_inventory_operation_products')
                        ->where('id', $sourcePivot->id)
                        ->where('organization_id', $organizationId)
                        ->update([
                            'product_id' => $targetProduct->id,
                        ]);
                }
            }

            $affectedBaseProductIds[$baseProduct->id] = true;
        }

        return array_keys($affectedBaseProductIds);
    }
    public function componentDetails()
    {
        return [
            'name' => 'Импорт Excel',
            'description' => 'Импорт остатков через Excel с проверкой и выводом результатов'
        ];
    }

    // -----------------------------
    // Применение выбранных различий
    // -----------------------------
    public function onApplyDifferences()
    {
        $organizationId = $this->organizationId();
        if ($organizationId <= 0) {
            return ['success' => false, 'toast' => [
                'message' => 'Користувач не прив\'язаний до організації',
                'type' => 'error',
                'timeout' => 4500,
                'position' => 'top-center'
            ]];
        }

        $updates = post('updates', []);
        $report = post('report', []);

        // If frontend sent JSON-serialized payloads (to avoid max_input_vars), decode them
        if (is_string($updates) && $updates !== '') {
            $decodedUpdates = json_decode($updates, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $updates = $decodedUpdates;
            }
        }

        if (is_string($report) && $report !== '') {
            $decoded = json_decode($report, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $report = $decoded;
            }
        }

        $newProducts = is_array($report) && is_array($report['new_products'] ?? null)
            ? $report['new_products']
            : [];
        $missingProducts = is_array($report) && is_array($report['missing_products'] ?? null)
            ? $report['missing_products']
            : [];
        $ambiguousMatches = is_array($report) && is_array($report['ambiguous_matches'] ?? null)
            ? $report['ambiguous_matches']
            : [];
        $ambiguousResolutions = is_array($report) && is_array($report['ambiguous_resolutions'] ?? null)
            ? $report['ambiguous_resolutions']
            : [];
        $invSyncRows = is_array($report) && is_array($report['inv_sync_rows'] ?? null)
            ? $report['inv_sync_rows']
            : [];
        $splitCandidates = is_array($report) && is_array($report['split_candidates'] ?? null)
            ? $report['split_candidates']
            : [];
        $splitResolutions = is_array($report) && is_array($report['split_resolutions'] ?? null)
            ? $report['split_resolutions']
            : [];

        Log::info('Применение различий — данные с фронта:', [
            'updates' => $updates,
            'new_products_count' => count($newProducts),
            'missing_products_count' => count($missingProducts),
            'ambiguous_resolutions_count' => count($ambiguousResolutions),
            'inv_sync_rows_count' => count($invSyncRows),
            'split_resolutions_count' => count($splitResolutions),
        ]);

        if (empty($updates) && empty($newProducts) && empty($missingProducts) && empty($ambiguousResolutions) && empty($invSyncRows) && empty($splitResolutions)) {
            return ['error' => 'Нет выбранных продуктов для обновления'];
        }

        $counteragent = post('counteragent', 'Не указан');
        $warnings = [];
        $parseExcelNumber = function ($value) {
            if ($value === null || $value === '') return 0.0;
            if (is_numeric($value)) return (float) $value;
            $clean = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', (string) $value);
            $clean = str_replace(',', '.', $clean);
            return (float) $clean;
        };

        $applyExcelDataToProduct = function (Product $product, $excelInvNumber, $excelQuantity, $price, $excelSum) use ($counteragent, $organizationId, &$warnings) {
            $currentQuantity = $product->calculated_quantity ?? 0;
            $currentSum = $product->calculated_sum ?? 0;

            $deltaQuantity = $excelQuantity - $currentQuantity;
            $deltaSum = $excelSum - $currentSum;

            if (abs($deltaQuantity) < self::EPSILON) {
                $deltaQuantity = 0.0;
            }

            if (abs($deltaSum) < self::EPSILON) {
                $deltaSum = 0.0;
            }

            if ($deltaQuantity != 0.0 || $deltaSum != 0.0) {
                $isIncoming = $deltaSum > 0 || ($deltaSum == 0.0 && $deltaQuantity > 0);
                $operationTypeName = $isIncoming ? 'Импорт приход' : 'Импорт расход';
                $operationType = OperationType::firstOrCreate(['name' => $operationTypeName]);

                $operation = new Operation();
                $operation->type_id = $operationType->id;
                $operation->save();

                $pivotQuantity = abs($deltaQuantity);
                if ($pivotQuantity < self::EPSILON) {
                    $pivotQuantity = 0.0;
                }

                $pivotSum = abs($deltaSum);
                if ($pivotSum < self::EPSILON) {
                    $pivotSum = 0.0;
                }

                $operation->products()->attach($product->id, [
                    'quantity' => $pivotQuantity,
                    'sum' => $pivotSum,
                    'counteragent' => $counteragent,
                    'organization_id' => $organizationId,
                ]);
            }

            $product->price = $price;

            $currentInvNormalized = $this->normalizeInvNumber($product->inv_number);
            $excelInvNormalized = $this->normalizeInvNumber($excelInvNumber);

            if ($excelInvNormalized !== '' && $currentInvNormalized !== $excelInvNormalized) {
                $existingByNewInv = Product::where('inv_number', $excelInvNumber)
                    ->where('id', '!=', $product->id)
                    ->first();

                if ($existingByNewInv) {
                    $warnings[] = "Номер {$excelInvNumber} уже прив'язан до іншого товару";
                } else {
                    $product->inv_number = $excelInvNormalized;
                }
            }

            $product->save();
        };

        $splitAffectedProductIds = $this->applySplitResolutions($splitCandidates, $splitResolutions, $warnings);
        $splitAffectedMap = array_fill_keys(array_map('intval', $splitAffectedProductIds), true);

        foreach ($invSyncRows as $syncItem) {
            $syncProductId = (int) ($syncItem['id'] ?? 0);
            if ($syncProductId <= 0 || isset($splitAffectedMap[$syncProductId])) {
                continue;
            }

            $product = Product::find($syncProductId);
            if (!$product) {
                continue;
            }

            $excelInvNumber = $this->normalizeInvNumber($syncItem['excel_inv_number'] ?? null);
            $currentInvNumber = $this->normalizeInvNumber($product->inv_number);

            if ($excelInvNumber === '' || $excelInvNumber === $currentInvNumber) {
                continue;
            }

            $existingByNewInv = Product::where('inv_number', $excelInvNumber)
                ->where('id', '!=', $product->id)
                ->first();

            if ($existingByNewInv) {
                $warnings[] = "Номер {$excelInvNumber} уже прив'язан до іншого товару";
                continue;
            }

            $product->inv_number = $excelInvNumber;
            $product->save();
        }

        foreach ($updates as $productId => $item) {
            $productIdInt = (int) $productId;
            if ($productIdInt > 0 && isset($splitAffectedMap[$productIdInt])) {
                continue;
            }

            $invNumber = $this->normalizeInvNumber($item['inv_number'] ?? null);
            $excelInvNumber = $this->normalizeInvNumber($item['excel_inv_number'] ?? $invNumber);
            $excelQuantity = $parseExcelNumber($item['quantity'] ?? 0);
            $price = $parseExcelNumber($item['price'] ?? 0);
            $excelSum = $parseExcelNumber($item['sum'] ?? 0);

            Log::info("Обрабатываем продукт {$invNumber}", [
                'excelQuantity' => $excelQuantity,
                'price' => $price,
                'excelSum' => $excelSum,
            ]);

            if (!$invNumber && !$excelInvNumber) continue;

            $product = null;
            if (is_numeric($productId)) {
                $product = Product::find($productIdInt);
            }

            if (!$product && $invNumber) {
                $product = Product::where('inv_number', $invNumber)->first();
            }

            if (!$product) continue;
            $applyExcelDataToProduct($product, $excelInvNumber, $excelQuantity, $price, $excelSum);
        }

        foreach ($missingProducts as $missingItem) {
            if (!is_array($missingItem)) {
                continue;
            }

            $missingProductId = (int) ($missingItem['id'] ?? 0);
            if ($missingProductId <= 0 || isset($splitAffectedMap[$missingProductId])) {
                continue;
            }

            $product = Product::find($missingProductId);
            if (!$product) {
                continue;
            }

            $currentQuantity = $parseExcelNumber($product->calculated_quantity ?? 0);
            $currentSum = $parseExcelNumber($product->calculated_sum ?? 0);

            if (abs($currentQuantity) <= self::EPSILON && abs($currentSum) <= self::EPSILON) {
                continue;
            }

            $currentPrice = $parseExcelNumber($product->price ?? 0);
            $applyExcelDataToProduct($product, null, 0.0, $currentPrice, 0.0);
        }

        foreach ($ambiguousResolutions as $resolution) {
            $resolvedProductId = (int) ($resolution['product_id'] ?? 0);
            if ($resolvedProductId <= 0) {
                continue;
            }

            $product = Product::find($resolvedProductId);
            if (!$product) {
                continue;
            }

            $excelInvNumber = $this->normalizeInvNumber($resolution['excel_inv_number'] ?? null);
            $excelQuantity = $parseExcelNumber($resolution['excel_quantity'] ?? 0);
            $excelPrice = $parseExcelNumber($resolution['excel_price'] ?? 0);
            $excelSum = $parseExcelNumber($resolution['excel_sum'] ?? 0);

            $applyExcelDataToProduct($product, $excelInvNumber, $excelQuantity, $excelPrice, $excelSum);
        }

        if (!empty($newProducts)) {
            $importType = null;
            $operation = null;

            foreach ($newProducts as $item) {
                $invNumber = $this->normalizeInvNumber($item['inv_number'] ?? null);
                if (!$invNumber) {
                    continue;
                }

                $name = trim((string) ($item['name'] ?? ''));
                $unit = trim((string) ($item['unit'] ?? 'шт'));
                $excelPrice = $parseExcelNumber($item['excel_price'] ?? ($item['price'] ?? 0));
                $excelQuantity = $parseExcelNumber($item['excel_quantity'] ?? ($item['quantity'] ?? 0));
                $excelSum = $parseExcelNumber($item['excel_sum'] ?? ($item['sum'] ?? 0));

                $existingProduct = Product::where('inv_number', $invNumber)->first();
                if ($existingProduct) {
                    Log::info('[samvol] apply import: new row mapped by inv_number to existing product', [
                        'inv_number' => $invNumber,
                        'product_id' => $existingProduct->id,
                    ]);
                    $applyExcelDataToProduct($existingProduct, $invNumber, $excelQuantity, $excelPrice, $excelSum);
                    continue;
                }

                $nameKey = mb_strtolower(trim((string) $name));
                $unitKey = mb_strtolower(trim((string) $unit));
                $nameUnitCandidates = Product::whereRaw('LOWER(TRIM(name)) = ?', [$nameKey])
                    ->whereRaw('LOWER(TRIM(unit)) = ?', [$unitKey])
                    ->get();

                if ($nameUnitCandidates->count() === 1) {
                    $existingByNameUnit = $nameUnitCandidates->first();
                    Log::info('[samvol] apply import: new row mapped by name+unit to existing product', [
                        'excel_inv' => $invNumber,
                        'product_id' => $existingByNameUnit->id,
                    ]);
                    $applyExcelDataToProduct($existingByNameUnit, $invNumber, $excelQuantity, $excelPrice, $excelSum);
                    continue;
                }

                if ($nameUnitCandidates->count() > 1) {
                    $warnings[] = "Неоднозначний збіг за назвою/одиницею для {$name}";
                    continue;
                }

                $product = Product::create([
                    'name' => $name,
                    'unit' => $unit,
                    'inv_number' => $invNumber,
                    'price' => $excelPrice,
                    'organization_id' => $organizationId,
                ]);

                if (abs($excelQuantity) > self::EPSILON || abs($excelSum) > self::EPSILON) {
                    if (!$operation) {
                        $importType = OperationType::firstOrCreate(['name' => 'Импорт']);
                        $operation = new Operation();
                        $operation->type_id = $importType->id;
                        $operation->save();
                    }

                    $operation->products()->attach($product->id, [
                        'quantity' => $excelQuantity,
                        'sum' => $excelSum,
                        'counteragent' => $counteragent,
                        'organization_id' => $organizationId,
                    ]);
                }
            }
        }

        $unresolvedAmbiguous = max(0, count($ambiguousMatches) - count($ambiguousResolutions));
        if ($unresolvedAmbiguous > 0) {
            $warnings[] = "{$unresolvedAmbiguous} неоднозначних позицій залишилися без підтвердження";
        }

        $message = 'Изменения применены';
        $type = 'success';
        if (!empty($warnings)) {
            $message = $message . '. ' . implode('; ', $warnings);
            $type = 'info';
        }

        return ['success' => true, 'toast' => [
            'message' => $message,
            'type' => $type,
            'timeout' => 6500,
            'position' => 'top-center'
        ]];
    }

    // -----------------------------
    // Скачивание различий в Excel
    // -----------------------------
    public function onDownloadDifferencesExcel()
    {
        $report = post('report', []);
        if (is_string($report) && $report !== '') {
            $decoded = json_decode($report, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $report = $decoded;
            }
        }

        $differencesRows = [];
        $newRows = [];
        $missingRows = [];
        $ambiguousRows = [];
        $invSyncRows = [];

        if (is_array($report) && (
            array_key_exists('differences', $report)
            || array_key_exists('new_products', $report)
            || array_key_exists('missing_products', $report)
            || array_key_exists('ambiguous_matches', $report)
        )) {
            $differencesRows = is_array($report['differences'] ?? null) ? $report['differences'] : [];
            $newRows = is_array($report['new_products'] ?? null) ? $report['new_products'] : [];
            $missingRows = is_array($report['missing_products'] ?? null) ? $report['missing_products'] : [];
            $ambiguousRows = is_array($report['ambiguous_matches'] ?? null) ? $report['ambiguous_matches'] : [];
            $invSyncRows = is_array($report['inv_sync_rows'] ?? null) ? $report['inv_sync_rows'] : [];
        } else {
            $rows = post('rows', []);
            $differencesRows = is_array($rows) ? $rows : [];
        }

        if (!empty($invSyncRows)) {
            $existingDiffKeys = [];
            foreach ($differencesRows as $diffRow) {
                $pid = (int) ($diffRow['id'] ?? 0);
                $excelInv = $this->normalizeInvNumber($diffRow['excel_inv_number'] ?? ($diffRow['inv_number'] ?? null));
                if ($pid > 0 && $excelInv !== '') {
                    $existingDiffKeys[$pid . '|' . $excelInv] = true;
                }
            }

            foreach ($invSyncRows as $syncRow) {
                if (!is_array($syncRow)) {
                    continue;
                }

                $pid = (int) ($syncRow['id'] ?? 0);
                $currentInv = $this->normalizeInvNumber($syncRow['inv_number'] ?? null);
                $excelInv = $this->normalizeInvNumber($syncRow['excel_inv_number'] ?? null);
                if ($pid <= 0 || $excelInv === '') {
                    continue;
                }

                $key = $pid . '|' . $excelInv;
                if (isset($existingDiffKeys[$key])) {
                    continue;
                }

                $differencesRows[] = [
                    'id' => $pid,
                    'inv_number' => $currentInv,
                    'excel_inv_number' => $excelInv,
                    'name' => $syncRow['name'] ?? '',
                    'current_quantity' => '',
                    'excel_quantity' => '',
                    'current_price' => '',
                    'excel_price' => '',
                    'current_sum' => '',
                    'excel_sum' => '',
                ];
                $existingDiffKeys[$key] = true;
            }
        }

        if (empty($differencesRows) && empty($newRows) && empty($missingRows) && empty($ambiguousRows)) {
            return [
                'toast' => [
                    'message' => 'Немає відмінностей для експорту',
                    'type' => 'info',
                    'timeout' => 3500,
                    'position' => 'top-center'
                ]
            ];
        }

        try {
            $spreadsheet = new Spreadsheet();
            $spreadsheet->removeSheetByIndex(0);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman')->setSize(11);

            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE9EEF5',
                    ],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => 'FFD3DCE8'],
                    ],
                ],
            ];

            $diffStyle = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FF5B4A00',
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFFFF1C2',
                    ],
                ],
            ];

            $confirmationYesStyle = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FF1E5E2D',
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFE8F5E9',
                    ],
                ],
            ];

            $confirmationNoStyle = [
                'font' => [
                    'bold' => true,
                    'color' => [
                        'argb' => 'FF8B1E1E',
                    ],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FFFDEBEC',
                    ],
                ],
            ];

            $toFloat = function ($value) {
                if ($value === null || $value === '') {
                    return 0.0;
                }

                if (is_numeric($value)) {
                    return (float) $value;
                }

                $normalized = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', (string) $value);
                $normalized = str_replace(',', '.', $normalized);

                return (float) $normalized;
            };

            $addSheetWithRows = function (
                $title,
                $headers,
                $rows,
                $diffColumns = [],
                $textColumns = [],
                $centerColumns = [],
                $wrapColumns = [],
                $columnWidthOverrides = []
            ) use ($spreadsheet, $headerStyle, $diffStyle, $toFloat) {
                $sheet = $spreadsheet->createSheet();
                $sheet->setTitle($title);
                $lastColumn = chr(ord('A') + count($headers) - 1);
                $sheet->fromArray($headers, null, 'A1');
                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray($headerStyle);
                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->freezePane('A2');

                $rowIndex = 2;
                foreach ($rows as $row) {
                    foreach (array_values($row) as $columnIndex => $cellValue) {
                        $columnLetter = chr(ord('A') + $columnIndex);
                        $cellAddress = $columnLetter . $rowIndex;

                        if (in_array($columnIndex, $wrapColumns, true)) {
                            $sheet->getStyle($cellAddress)->getAlignment()->setWrapText(true);
                        }

                        if (in_array($columnIndex, $centerColumns, true)) {
                            $sheet->getStyle($cellAddress)->getAlignment()->setHorizontal(
                                \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER
                            );
                            $sheet->getStyle($cellAddress)->getAlignment()->setVertical(
                                \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                            );
                        }

                        if (in_array($columnIndex, $textColumns, true)) {
                            $sheet->setCellValueExplicit(
                                $cellAddress,
                                (string) ($cellValue ?? ''),
                                \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
                            );
                            continue;
                        }

                        $sheet->setCellValue($cellAddress, $cellValue);
                    }

                    foreach ($diffColumns as $pair) {
                        $leftIndex = $pair[0];
                        $rightIndex = $pair[1];
                        $mode = $pair[2] ?? 'numeric';

                        if ($mode === 'string') {
                            $leftValue = trim((string) ($row[$leftIndex] ?? ''));
                            $rightValue = trim((string) ($row[$rightIndex] ?? ''));
                            $hasDiff = $leftValue !== $rightValue;
                        } else {
                            $leftValue = $toFloat($row[$leftIndex] ?? 0);
                            $rightValue = $toFloat($row[$rightIndex] ?? 0);
                            $hasDiff = ($leftValue != $rightValue);
                        }

                        if ($hasDiff) {
                            $leftColumn = chr(ord('A') + $leftIndex);
                            $rightColumn = chr(ord('A') + $rightIndex);
                            $sheet->getStyle($leftColumn . $rowIndex . ':' . $rightColumn . $rowIndex)->applyFromArray($diffStyle);
                        }
                    }

                    $rowIndex++;
                }

                for ($heightRow = 2; $heightRow < $rowIndex; $heightRow++) {
                    $sheet->getRowDimension($heightRow)->setRowHeight(-1);
                }

                $lastDataRow = max(1, $rowIndex - 1);
                $sheet->getStyle('A1:' . $lastColumn . $lastDataRow)
                    ->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A1:' . $lastColumn . $lastDataRow)
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->getColor()
                    ->setARGB('FFDCE4EF');

                foreach (range('A', $lastColumn) as $columnLetter) {
                    $columnIndex = ord($columnLetter) - ord('A');
                    $highestRow = $sheet->getHighestRow();
                    $maxLength = 0;

                    for ($scanRow = 1; $scanRow <= $highestRow; $scanRow++) {
                        $value = $sheet->getCell($columnLetter . $scanRow)->getFormattedValue();
                        $length = function_exists('mb_strlen')
                            ? mb_strlen((string) $value)
                            : strlen((string) $value);

                        if ($length > $maxLength) {
                            $maxLength = $length;
                        }
                    }

                    $padding = 2;
                    $minWidth = 10;
                    $maxWidth = 48;
                    $calculatedWidth = max($minWidth, min($maxWidth, $maxLength + $padding));

                    if (array_key_exists($columnIndex, $columnWidthOverrides)) {
                        $overrideWidth = (float) $columnWidthOverrides[$columnIndex];
                        if ($overrideWidth > 0) {
                            $calculatedWidth = $overrideWidth;
                        }
                    }

                    $sheet->getColumnDimension($columnLetter)->setAutoSize(false);
                    $sheet->getColumnDimension($columnLetter)->setWidth($calculatedWidth);
                }

                return $sheet;
            };

            if (!empty($differencesRows)) {
                $differenceExportRows = array_map(function ($row) {
                    return [
                        $row['id'] ?? '',
                        $row['inv_number'] ?? '',
                        $row['excel_inv_number'] ?? ($row['inv_number'] ?? ''),
                        $row['name'] ?? '',
                        $row['current_quantity'] ?? '',
                        $row['excel_quantity'] ?? '',
                        $row['current_price'] ?? ($row['price'] ?? ''),
                        $row['excel_price'] ?? '',
                        $row['current_sum'] ?? ($row['sum'] ?? ''),
                        $row['excel_sum'] ?? '',
                    ];
                }, $differencesRows);

                $addSheetWithRows('Відмінності', [
                    'ID',
                    'Інв. номер на складі',
                    'Інв. номер в Excel',
                    'Найменування',
                    'К-сть на складі',
                    'К-сть в Excel',
                    'Ціна на складі',
                    'Ціна в Excel',
                    'Сума на складі',
                    'Сума в Excel',
                ], $differenceExportRows, [[1, 2, 'string'], [4, 5], [6, 7], [8, 9]], [1, 2], [4, 5, 6, 7, 8, 9], [1, 2, 3], [1 => 30, 2 => 30, 3 => 44]);
            }

            if (!empty($newRows)) {
                $newExportRows = array_map(function ($row) {
                    return [
                        $row['inv_number'] ?? '',
                        $row['name'] ?? '',
                        $row['unit'] ?? '',
                        $row['excel_quantity'] ?? ($row['quantity'] ?? ''),
                        $row['excel_price'] ?? ($row['price'] ?? ''),
                        $row['excel_sum'] ?? ($row['sum'] ?? ''),
                    ];
                }, $newRows);

                $addSheetWithRows('Нові в Excel', [
                    'Інвентарний номер',
                    'Найменування',
                    'Од. вим.',
                    'К-сть в Excel',
                    'Ціна в Excel',
                    'Сума в Excel',
                ], $newExportRows, [], [0], [2, 3, 4, 5], [0, 1], [0 => 30, 1 => 44]);
            }

            if (!empty($missingRows)) {
                $missingProductIds = array_values(array_unique(array_filter(array_map(function ($row) {
                    return (int) ($row['id'] ?? 0);
                }, $missingRows))));

                $outgoingOperationCounts = [];
                if (!empty($missingProductIds)) {
                    $outgoingOperationCounts = DB::table('samvol_inventory_operation_products as op')
                        ->join('samvol_inventory_operations as o', 'o.id', '=', 'op.operation_id')
                        ->join('samvol_inventory_operation_types as t', 't.id', '=', 'o.type_id')
                        ->whereIn('op.product_id', $missingProductIds)
                        ->where('o.organization_id', $this->organizationId())
                        ->whereRaw('LOWER(TRIM(t.name)) IN (?, ?, ?, ?)', ['расход', 'передача', 'списание', 'импорт расход'])
                        ->select('op.product_id', DB::raw('COUNT(*) as cnt'))
                        ->groupBy('op.product_id')
                        ->pluck('cnt', 'op.product_id')
                        ->all();
                }

                $missingExportRows = array_map(function ($row) use ($outgoingOperationCounts) {
                    $productId = (int) ($row['id'] ?? 0);
                    $operationsCount = $productId > 0 ? (int) ($outgoingOperationCounts[$productId] ?? 0) : 0;
                    $hasConfirmation = $operationsCount > 0;
                    $comment = $hasConfirmation
                        ? "Знайдено {$operationsCount} операцій"
                        : 'Операцій не знайдено';

                    return [
                        $row['inv_number'] ?? '',
                        $row['name'] ?? '',
                        $row['unit'] ?? '',
                        $row['current_quantity'] ?? '',
                        $row['current_price'] ?? ($row['price'] ?? ''),
                        $row['current_sum'] ?? ($row['sum'] ?? ''),
                        $hasConfirmation ? 'Є підтвердження' : 'Немає підтвердження',
                        $comment,
                    ];
                }, $missingRows);

                $missingSheet = $addSheetWithRows('Відсутні в Excel', [
                    'Інвентарний номер',
                    'Найменування',
                    'Од. вим.',
                    'К-сть на складі',
                    'Ціна на складі',
                    'Сума на складі',
                    'Підтвердження',
                    'Коментар',
                ], $missingExportRows, [], [0], [2, 3, 4, 5, 6], [0, 1, 7], [0 => 30, 1 => 44, 6 => 24, 7 => 28]);

                for ($statusRow = 2; $statusRow <= $missingSheet->getHighestRow(); $statusRow++) {
                    $statusCell = 'G' . $statusRow;
                    $commentCell = 'H' . $statusRow;
                    $statusValue = trim((string) $missingSheet->getCell($statusCell)->getValue());

                    if ($statusValue === 'Є підтвердження') {
                        $missingSheet->getStyle($statusCell)->applyFromArray($confirmationYesStyle);
                        $missingSheet->getStyle($commentCell)->applyFromArray($confirmationYesStyle);
                    } elseif ($statusValue === 'Немає підтвердження') {
                        $missingSheet->getStyle($statusCell)->applyFromArray($confirmationNoStyle);
                        $missingSheet->getStyle($commentCell)->applyFromArray($confirmationNoStyle);
                    }
                }
            }

            if (!empty($ambiguousRows)) {
                $ambiguousExportRows = [];
                foreach ($ambiguousRows as $row) {
                    $excelInv = $row['excel_inv_number'] ?? '';
                    $excelName = $row['excel_name'] ?? '';
                    $excelQuantity = $row['excel_quantity'] ?? '';
                    $excelPrice = $row['excel_price'] ?? '';
                    $excelSum = $row['excel_sum'] ?? '';
                    $candidates = is_array($row['candidates'] ?? null) ? $row['candidates'] : [];

                    if (empty($candidates)) {
                        $ambiguousExportRows[] = [
                            $excelInv,
                            $excelName,
                            $excelQuantity,
                            $excelPrice,
                            $excelSum,
                            '',
                            '',
                        ];
                        continue;
                    }

                    foreach ($candidates as $candidate) {
                        $ambiguousExportRows[] = [
                            $excelInv,
                            $excelName,
                            $excelQuantity,
                            $excelPrice,
                            $excelSum,
                            ($candidate['inv_number'] ?? ''),
                            ($candidate['name'] ?? ''),
                        ];
                    }
                }

                $addSheetWithRows('Потрібна перевірка', [
                    'Інв. номер в Excel',
                    'Найменування в Excel',
                    'К-сть в Excel',
                    'Ціна в Excel',
                    'Сума в Excel',
                    'Можливий інв. номер у БД',
                    'Можлива назва у БД',
                ], $ambiguousExportRows, [], [0, 5], [2, 3, 4], [0, 1, 5, 6], [0 => 30, 1 => 44, 5 => 30, 6 => 44]);
            }

            $spreadsheet->setActiveSheetIndex(0);

            $tempBasePath = tempnam(storage_path('temp'), 'diff_');
            if ($tempBasePath === false) {
                throw new \RuntimeException('Не удалось создать временный файл');
            }

            $tempXlsxPath = $tempBasePath . '.xlsx';
            $writer = new Xlsx($spreadsheet);
            $writer->save($tempXlsxPath);

            $binary = file_get_contents($tempXlsxPath);
            if ($binary === false) {
                throw new \RuntimeException('Не удалось прочитать временный XLSX');
            }

            @unlink($tempBasePath);
            @unlink($tempXlsxPath);

            return [
                'download' => [
                    'filename' => 'import-reconciliation-' . date('Y-m-d') . '.xlsx',
                    'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                    'content' => base64_encode($binary),
                ]
            ];
        } catch (\Throwable $e) {
            Log::error('Ошибка экспорта различий в Excel', [
                'message' => $e->getMessage(),
            ]);

            return [
                'toast' => [
                    'message' => 'Не вдалося сформувати Excel файл відмінностей',
                    'type' => 'error',
                    'timeout' => 4500,
                    'position' => 'top-center'
                ]
            ];
        }
    }

    // -----------------------------
    // Импорт Excel
    // -----------------------------
    public function onImportExcel()
    {
        $organizationId = $this->organizationId();
        if ($organizationId <= 0) {
            return ['toast'=>['message'=>'Користувач не прив\'язаний до організації','type'=>'error']];
        }

        function parseExcelNumber($value) {
            if (!$value) return 0;
            $clean = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $value);
            $clean = str_replace(',', '.', $clean);
            return floatval($clean);
        }

        $file = Input::file('excel_file');
        Log::info('$_FILES:', $_FILES);

        if (!$file) {
            Log::info('Файл не получен через Input::file');
            return ['toast'=>['message'=>'Файл не загружен','type'=>'error']];
        }
        Log::info('Файл получен: '.$file->getClientOriginalName());

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
            if (empty($rows)) return ['toast' => ['message'=>'Файл пустой','type'=>'error']];

            unset($rows[0]); // пропускаем заголовок

            $allDbProducts = Product::whereNotNull('inv_number')
                ->where('inv_number', '!=', '')
                ->get();

            $lookup = $this->buildProductLookup($allDbProducts);
            $productsById = [];
            foreach ($allDbProducts as $dbProduct) {
                $productsById[$dbProduct->id] = $dbProduct;
            }
            $nameUnitLookup = [];
            foreach ($allDbProducts as $dbProduct) {
                $nameKey = mb_strtolower(trim((string) ($dbProduct->name ?? '')));
                $unitKey = mb_strtolower(trim((string) ($dbProduct->unit ?? '')));
                $compoundKey = $nameKey . '|' . $unitKey;
                if (!isset($nameUnitLookup[$compoundKey])) {
                    $nameUnitLookup[$compoundKey] = [];
                }
                $nameUnitLookup[$compoundKey][] = $dbProduct;
            }

            $differences = [];
            $matchedRowsByProduct = [];
            $newProductsForModal = [];
            $ambiguousMatchesForModal = [];
            $splitCandidatesForModal = [];
            $rowMappingStats = ['default' => 0, 'shifted' => 0];
            $newCount = 0;
            $matchedProductIds = [];
            $ambiguousCandidateIds = [];
            $matchStats = [
                'exact' => 0,
                'token' => 0,
                'ambiguous' => 0,
                'none' => 0,
                'name_unit' => 0,
                'name_unit_ambiguous' => 0,
            ];
            $diffStats = [
                'quantity' => 0,
                'price' => 0,
                'sum' => 0,
                'inv' => 0,
                'inv_only' => 0,
            ];
            $invOnlySamples = [];
            $invSyncRowsMap = [];

            foreach ($rows as $row) {
                if (empty($row[0])) continue;

                $isShiftedRow = false;
                if (
                    is_numeric($row[0] ?? null)
                    && $this->looksLikeInvNumber($row[3] ?? null)
                    && is_numeric(parseExcelNumber($row[4] ?? null))
                    && is_numeric(parseExcelNumber($row[5] ?? null))
                ) {
                    $isShiftedRow = true;
                }

                if ($isShiftedRow) {
                    $rowMappingStats['shifted']++;
                    $name       = trim((string) ($row[1] ?? ''));
                    $unit       = trim((string) ($row[2] ?? 'шт'));
                    $inv_number = $this->normalizeInvNumber($row[3] ?? '');
                    $price      = parseExcelNumber($row[4] ?? 0);
                    $quantity   = parseExcelNumber($row[5] ?? 0);
                    $sum        = parseExcelNumber($row[6] ?? 0);
                } else {
                    $rowMappingStats['default']++;
                    $name       = trim((string) ($row[0] ?? ''));
                    $unit       = trim((string) ($row[1] ?? 'шт'));
                    $inv_number = $this->normalizeInvNumber($row[2] ?? '');
                    $price      = parseExcelNumber($row[3] ?? 0);
                    $quantity   = parseExcelNumber($row[4] ?? 0);
                    $sum        = parseExcelNumber($row[5] ?? 0);
                }
                $nameKey = mb_strtolower(trim((string) $name));
                $unitKey = mb_strtolower(trim((string) $unit));
                $nameUnitKey = $nameKey . '|' . $unitKey;

                $resolved = $this->resolveProductByInv($inv_number, $lookup);
                $product = $resolved['product'] ?? null;
                $resolvedStatus = $resolved['status'] ?? 'none';
                if (!isset($matchStats[$resolvedStatus])) {
                    $matchStats[$resolvedStatus] = 0;
                }
                $matchStats[$resolvedStatus]++;

                if (($resolved['status'] ?? '') === 'ambiguous') {
                    $candidates = $resolved['candidates'] ?? [];
                    foreach ($candidates as $candidate) {
                        $ambiguousCandidateIds[$candidate->id] = true;
                    }

                    $ambiguousMatchesForModal[] = [
                        'excel_name' => $name,
                        'excel_inv_number' => $inv_number,
                        'excel_unit' => $unit,
                        'excel_quantity' => $quantity,
                        'excel_price' => $price,
                        'excel_sum' => $sum,
                        'candidates' => array_map(function ($candidate) {
                            return [
                                'id' => $candidate->id,
                                'inv_number' => $candidate->inv_number,
                                'name' => $candidate->name,
                                'unit' => $candidate->unit,
                                'current_quantity' => (float) ($candidate->calculated_quantity ?? 0),
                            ];
                        }, $candidates),
                    ];

                    continue;
                }

                if (!$product && $resolvedStatus === 'none') {
                    $nameUnitCandidates = $nameUnitLookup[$nameUnitKey] ?? [];

                    if (count($nameUnitCandidates) === 1) {
                        $product = $nameUnitCandidates[0];
                        $resolvedStatus = 'name_unit';
                        $matchStats['name_unit']++;
                    } elseif (count($nameUnitCandidates) > 1) {
                        $matchStats['name_unit_ambiguous']++;

                        foreach ($nameUnitCandidates as $candidate) {
                            $ambiguousCandidateIds[$candidate->id] = true;
                        }

                        $ambiguousMatchesForModal[] = [
                            'excel_name' => $name,
                            'excel_inv_number' => $inv_number,
                            'excel_unit' => $unit,
                            'excel_quantity' => $quantity,
                            'excel_price' => $price,
                            'excel_sum' => $sum,
                            'candidates' => array_map(function ($candidate) {
                                return [
                                    'id' => $candidate->id,
                                    'inv_number' => $candidate->inv_number,
                                    'name' => $candidate->name,
                                    'unit' => $candidate->unit,
                                    'current_quantity' => (float) ($candidate->calculated_quantity ?? 0),
                                ];
                            }, $nameUnitCandidates),
                        ];

                        continue;
                    }

                }

                if (!$product) {
                    if (!$inv_number) {
                        Log::warning('[samvol] import: empty inv_number, row skipped to avoid duplicates', [
                            'name' => $name,
                            'unit' => $unit,
                            'price' => $price,
                            'quantity' => $quantity,
                            'sum' => $sum
                        ]);
                        continue;
                    }
                    Log::info('[samvol] import: found new product in Excel (pending apply)', [
                        'inv_number' => $inv_number,
                        'name' => $name,
                        'unit' => $unit,
                        'price' => $price
                    ]);
                    $newProductsForModal[] = [
                        'inv_number' => $inv_number,
                        'name' => $name,
                        'unit' => $unit,
                        'excel_quantity' => $quantity,
                        'excel_price' => $price,
                        'excel_sum' => $sum,
                    ];
                    $newCount++;
                    continue;
                }

                $matchedProductIds[$product->id] = true;

                $currentQuantity = $product->calculated_quantity ?? 0;
                $currentSum = $product->calculated_sum ?? 0;
                $currentInvNumber = $this->normalizeInvNumber($product->inv_number);
                $invChanged = $inv_number && $currentInvNumber !== $inv_number;

                if (!isset($matchedRowsByProduct[$product->id])) {
                    $matchedRowsByProduct[$product->id] = [];
                }
                $matchedRowsByProduct[$product->id][] = [
                    'excel_inv_number' => $inv_number ?: $product->inv_number,
                    'excel_quantity' => $quantity,
                    'excel_price' => $price,
                    'excel_sum' => $sum,
                    'current_quantity' => $currentQuantity,
                    'current_sum' => $currentSum,
                    'price' => $product->price,
                ];

                // Если есть различия по количеству, цене или сумме
                $hasQuantityDiff = abs($currentQuantity - $quantity) > self::EPSILON;
                $hasPriceDiff = abs(((float) $product->price) - $price) > self::EPSILON;
                $hasSumDiff = abs($currentSum - $sum) > self::EPSILON;
                $hasInvDiff = $invChanged;

                if ($hasQuantityDiff || $hasPriceDiff || $hasSumDiff || $hasInvDiff) {
                    if ($hasQuantityDiff) {
                        $diffStats['quantity']++;
                    }
                    if ($hasPriceDiff) {
                        $diffStats['price']++;
                    }
                    if ($hasSumDiff) {
                        $diffStats['sum']++;
                    }
                    if ($hasInvDiff) {
                        $diffStats['inv']++;
                    }

                    if ($hasInvDiff && !$hasQuantityDiff && !$hasPriceDiff && !$hasSumDiff) {
                        $diffStats['inv_only']++;
                        if (count($invOnlySamples) < 30) {
                            $invOnlySamples[] = [
                                'product_id' => $product->id,
                                'name' => $product->name,
                                'db_inv' => $product->inv_number,
                                'excel_inv' => $inv_number,
                                'match_status' => $resolvedStatus,
                            ];
                        }
                    }

                    if ($hasInvDiff) {
                        $invSyncRowsMap[$product->id] = [
                            'id' => $product->id,
                            'inv_number' => $product->inv_number,
                            'excel_inv_number' => $inv_number,
                            'name' => $product->name,
                        ];
                    }

                    if ($hasQuantityDiff || $hasPriceDiff || $hasSumDiff) {
                        $differences[] = [
                            'id'=>$product->id,
                            'name'=>$product->name,
                            'inv_number'=>$product->inv_number,
                            'excel_inv_number'=>$inv_number ?: $product->inv_number,
                            'inv_changed'=>false,
                            'current_quantity'=>$currentQuantity,
                            'excel_quantity'=>$quantity,
                            'price'=>$product->price,
                            'excel_price'=>$price,
                            'sum'=>$currentSum,
                            'current_sum'=>$currentSum,
                            'excel_sum'=>$sum,
                            'unit'=>$unit
                        ];
                    }
                }
            }

            $invSyncRowsForApply = array_values($invSyncRowsMap);

            $splitDifferenceKeys = [];
            $splitDebug = [
                'considered_products' => 0,
                'created_candidates' => 0,
                'reject_stats' => [
                    'invalid_excel_inv' => 0,
                    'not_unique_excel_inv' => 0,
                    'primary_mismatch' => 0,
                    'quantity_mismatch' => 0,
                    'sum_mismatch' => 0,
                    'price_mismatch' => 0,
                    'operations_insufficient' => 0,
                ],
                'reject_samples' => [],
            ];
            foreach ($matchedRowsByProduct as $productId => $items) {
                if (count($items) < 2) {
                    continue;
                }

                $splitDebug['considered_products']++;

                $baseProduct = $productsById[$productId] ?? null;
                if (!$baseProduct) {
                    continue;
                }

                $currentQty = $this->parseNumeric($items[0]['current_quantity'] ?? 0);
                $currentSum = $this->parseNumeric($items[0]['current_sum'] ?? 0);
                $currentPrice = $this->parseNumeric($items[0]['price'] ?? 0);
                $baseInvParsed = $this->parseInvData($baseProduct->inv_number);
                $baseInvPrimary = $baseInvParsed['primary'] ?? '';
                if ($baseInvPrimary === '') {
                    $baseInvPrimary = $baseInvParsed['normalized'] ?? '';
                }
                $baseInvPrimaryCanonical = $this->canonicalInvPrimary($baseInvPrimary);

                $excelQtySum = 0.0;
                $excelSumTotal = 0.0;
                $allExcelInvValid = true;
                $allPriceSame = true;
                $hasPriceData = false;
                $hasSumData = false;
                $excelInvSet = [];
                $excelPrimarySet = [];
                $excelPrimarySetCanonical = [];
                $rowsForSplit = [];

                foreach ($items as $item) {
                    $excelInv = $this->normalizeInvNumber($item['excel_inv_number'] ?? null);
                    if ($excelInv === '') {
                        $allExcelInvValid = false;
                        break;
                    }

                    $excelInvSet[$excelInv] = true;
                    $excelParsed = $this->parseInvData($excelInv);
                    $excelPrimary = $excelParsed['primary'] ?? '';
                    if ($excelPrimary === '') {
                        $excelPrimary = $excelParsed['normalized'] ?? '';
                    }
                    if ($excelPrimary !== '') {
                        $excelPrimarySet[$excelPrimary] = true;
                        $excelPrimaryCanonical = $this->canonicalInvPrimary($excelPrimary);
                        if ($excelPrimaryCanonical !== '') {
                            $excelPrimarySetCanonical[$excelPrimaryCanonical] = true;
                        }
                    }

                    $excelQty = $this->parseNumeric($item['excel_quantity'] ?? 0);
                    $excelPrice = $this->parseNumeric($item['excel_price'] ?? 0);
                    $excelSum = $this->parseNumeric($item['excel_sum'] ?? 0);

                    if (abs($excelPrice) > self::EPSILON) {
                        $hasPriceData = true;
                    }
                    if (abs($excelSum) > self::EPSILON) {
                        $hasSumData = true;
                    }

                    $excelQtySum += $excelQty;
                    $excelSumTotal += $excelSum;
                    $allPriceSame = $allPriceSame && abs($excelPrice - $currentPrice) <= self::EPSILON;

                    $rowsForSplit[] = [
                        'excel_inv_number' => $excelInv,
                        'excel_quantity' => $excelQty,
                        'excel_price' => $excelPrice,
                        'excel_sum' => $excelSum,
                    ];
                }

                $samePrimaryGroup = false;
                if ($baseInvPrimary !== '' && count($excelPrimarySet) === 1) {
                    $onlyPrimary = (string) array_key_first($excelPrimarySet);
                    $samePrimaryGroup = ($onlyPrimary === $baseInvPrimary);
                }
                if (!$samePrimaryGroup && $baseInvPrimaryCanonical !== '' && count($excelPrimarySetCanonical) === 1) {
                    $onlyPrimaryCanonical = (string) array_key_first($excelPrimarySetCanonical);
                    $samePrimaryGroup = ($onlyPrimaryCanonical === $baseInvPrimaryCanonical);
                }

                $priceCompatible = !$hasPriceData || $allPriceSame;
                $sumCompatible = abs($excelSumTotal - $currentSum) <= 0.01;
                if (!$sumCompatible && $priceCompatible) {
                    $calculatedExcelSum = $excelQtySum * $currentPrice;
                    $sumCompatible = abs($calculatedExcelSum - $currentSum) <= 0.01;
                }
                if (!$sumCompatible && !$hasSumData) {
                    $sumCompatible = true;
                }

                $isSplitCandidate = $allExcelInvValid
                    && count($excelInvSet) === count($items)
                    && $samePrimaryGroup
                    && abs($excelQtySum - $currentQty) <= self::EPSILON
                    && $sumCompatible
                    && $priceCompatible;

                $rejectReason = null;
                if (!$allExcelInvValid) {
                    $rejectReason = 'invalid_excel_inv';
                } elseif (count($excelInvSet) !== count($items)) {
                    $rejectReason = 'not_unique_excel_inv';
                } elseif (!$samePrimaryGroup) {
                    $rejectReason = 'primary_mismatch';
                } elseif (abs($excelQtySum - $currentQty) > self::EPSILON) {
                    $rejectReason = 'quantity_mismatch';
                } elseif (!$sumCompatible) {
                    $rejectReason = 'sum_mismatch';
                } elseif (!$priceCompatible) {
                    $rejectReason = 'price_mismatch';
                }

                if (!$isSplitCandidate) {
                    if ($rejectReason !== null) {
                        $splitDebug['reject_stats'][$rejectReason]++;
                        if (count($splitDebug['reject_samples']) < 25) {
                            $splitDebug['reject_samples'][] = [
                                'product_id' => $productId,
                                'base_inv' => $baseProduct->inv_number,
                                'excel_invs' => array_values(array_keys($excelInvSet)),
                                'current_qty' => $currentQty,
                                'excel_qty_sum' => $excelQtySum,
                                'current_sum' => $currentSum,
                                'excel_sum_total' => $excelSumTotal,
                                'base_primary' => $baseInvPrimary,
                                'base_primary_canonical' => $baseInvPrimaryCanonical,
                                'excel_primaries' => array_map('strval', array_values(array_keys($excelPrimarySet))),
                                'excel_primaries_canonical' => array_map('strval', array_values(array_keys($excelPrimarySetCanonical))),
                                'reason' => $rejectReason,
                            ];
                        }
                    }
                    continue;
                }

                $incomingOperations = $this->getIncomingOperationsForProduct($productId);
                if (count($incomingOperations) < count($rowsForSplit)) {
                    $splitDebug['reject_stats']['operations_insufficient']++;
                    if (count($splitDebug['reject_samples']) < 25) {
                        $splitDebug['reject_samples'][] = [
                            'product_id' => $productId,
                            'base_inv' => $baseProduct->inv_number,
                            'excel_invs' => array_values(array_keys($excelInvSet)),
                            'incoming_ops' => count($incomingOperations),
                            'required_ops' => count($rowsForSplit),
                            'reason' => 'operations_insufficient',
                        ];
                    }
                    continue;
                }

                foreach ($rowsForSplit as $row) {
                    $rowInv = $this->normalizeInvNumber($row['excel_inv_number'] ?? null);
                    if ($rowInv === '') {
                        continue;
                    }
                    $splitDifferenceKeys[$productId . '|' . $rowInv] = true;
                }

                $splitCandidatesForModal[] = [
                    'base_product_id' => $productId,
                    'base_name' => $baseProduct->name,
                    'base_inv_number' => $baseProduct->inv_number,
                    'base_quantity' => $currentQty,
                    'rows' => $rowsForSplit,
                    'operations' => $incomingOperations,
                ];
                $splitDebug['created_candidates']++;
            }

            if (!empty($splitDifferenceKeys)) {
                $differences = array_values(array_filter($differences, function ($diff) use ($splitDifferenceKeys) {
                    $pid = (int) ($diff['id'] ?? 0);
                    $excelInv = $this->normalizeInvNumber($diff['excel_inv_number'] ?? ($diff['inv_number'] ?? null));
                    if ($pid <= 0 || $excelInv === '') {
                        return true;
                    }

                    return !isset($splitDifferenceKeys[$pid . '|' . $excelInv]);
                }));
            }

            $missingProductsForModal = [];
            $missingProductsForExport = [];

            foreach ($allDbProducts as $dbProduct) {
                if (isset($matchedProductIds[$dbProduct->id])) {
                    continue;
                }

                if (isset($ambiguousCandidateIds[$dbProduct->id])) {
                    continue;
                }

                $currentQuantity = (float) ($dbProduct->calculated_quantity ?? 0);
                $currentSum = (float) ($dbProduct->calculated_sum ?? 0);
                $currentPrice = (float) ($dbProduct->price ?? 0);

                $missingRow = [
                    'id' => $dbProduct->id,
                    'inv_number' => $dbProduct->inv_number,
                    'name' => $dbProduct->name,
                    'unit' => $dbProduct->unit,
                    'current_quantity' => $currentQuantity,
                    'current_price' => $currentPrice,
                    'current_sum' => $currentSum,
                ];

                $missingProductsForExport[] = $missingRow;
                $missingProductsForModal[] = $missingRow;
            }

            Log::info('[samvol] import debug summary', [
                'excel_rows_total' => count($rows),
                'row_mapping' => $rowMappingStats,
                'matched_products' => count($matchedProductIds),
                'differences_total' => count($differences),
                'new_products_total' => count($newProductsForModal),
                'missing_products_total' => count($missingProductsForModal),
                'ambiguous_total' => count($ambiguousMatchesForModal),
                'split_total' => count($splitCandidatesForModal),
                'inv_sync_total' => count($invSyncRowsForApply),
                'match_stats' => $matchStats,
                'diff_stats' => $diffStats,
                'split_debug' => $splitDebug,
            ]);

            if (!empty($invOnlySamples)) {
                Log::info('[samvol] import inv-only differences sample', [
                    'count' => count($invOnlySamples),
                    'items' => $invOnlySamples,
                ]);
            }

            $downloadReport = [
                'differences' => array_map(function ($diff) {
                    return [
                        'id' => $diff['id'] ?? '',
                        'inv_number' => $diff['inv_number'] ?? '',
                        'excel_inv_number' => $diff['excel_inv_number'] ?? ($diff['inv_number'] ?? ''),
                        'name' => $diff['name'] ?? '',
                        'current_quantity' => $diff['current_quantity'] ?? '',
                        'excel_quantity' => $diff['excel_quantity'] ?? '',
                        'current_price' => $diff['price'] ?? ($diff['current_price'] ?? ''),
                        'excel_price' => $diff['excel_price'] ?? '',
                        'current_sum' => $diff['current_sum'] ?? ($diff['sum'] ?? ''),
                        'excel_sum' => $diff['excel_sum'] ?? '',
                    ];
                }, $differences),
                'new_products' => $newProductsForModal,
                'missing_products' => $missingProductsForExport,
                'ambiguous_matches' => $ambiguousMatchesForModal,
                'split_candidates' => $splitCandidatesForModal,
                'inv_sync_rows' => $invSyncRowsForApply,
            ];

            $downloadReportJson = json_encode($downloadReport, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $downloadReportEncoded = $downloadReportJson !== false ? base64_encode($downloadReportJson) : '';

            // Если есть различия или контрольные списки, показываем модалку
            if (!empty($differences) || !empty($newProductsForModal) || !empty($missingProductsForModal) || !empty($ambiguousMatchesForModal) || !empty($splitCandidatesForModal) || !empty($invSyncRowsForApply)) {
                $hasDifferences = !empty($differences);
                $hasNewProducts = !empty($newProductsForModal);
                $hasMissingProducts = !empty($missingProductsForModal);
                $hasAmbiguous = !empty($ambiguousMatchesForModal);
                $hasSplit = !empty($splitCandidatesForModal);
                $hasInvSync = !empty($invSyncRowsForApply);

                $activeSections = [];
                if ($hasDifferences) {
                    $activeSections[] = 'відмінності';
                }
                if ($hasNewProducts) {
                    $activeSections[] = 'нові позиції';
                }
                if ($hasMissingProducts) {
                    $activeSections[] = 'позиції, що зникли';
                }
                if ($hasAmbiguous) {
                    $activeSections[] = 'неоднозначні відповідності';
                }
                if ($hasSplit) {
                    $activeSections[] = 'розподіл операцій';
                }
                if ($hasInvSync) {
                    $activeSections[] = 'оновлення інвентарних номерів';
                }

                $modalTitle = 'Результати імпорту';
                $modalSubtitle = 'Перевірте секції нижче та підтвердьте застосування змін.';

                if (count($activeSections) === 1) {
                    if ($hasDifferences) {
                        $modalTitle = 'Знайдено відмінності';
                        $modalSubtitle = 'Є розбіжності між складом та поточним Excel файлом.';
                    } elseif ($hasNewProducts) {
                        $modalTitle = 'Знайдено нові позиції';
                        $modalSubtitle = 'У файлі є товари, яких раніше не було в базі.';
                    } elseif ($hasMissingProducts) {
                        $modalTitle = 'Знайдено позиції, що зникли';
                        $modalSubtitle = 'У БД є товари, яких немає у поточному Excel файлі.';
                    } elseif ($hasAmbiguous) {
                        $modalTitle = 'Потрібна ручна перевірка';
                        $modalSubtitle = 'Оберіть відповідності для неоднозначних інвентарних номерів.';
                    } elseif ($hasSplit) {
                        $modalTitle = 'Потрібен розподіл операцій';
                        $modalSubtitle = 'Оберіть, яку операцію застосувати до кожної нової позиції.';
                    } elseif ($hasInvSync) {
                        $modalTitle = 'Оновлення інвентарних номерів';
                        $modalSubtitle = 'Інвентарні номери будуть синхронізовані з Excel після підтвердження.';
                    }
                } else {
                    $modalSubtitle = 'Знайдено: ' . implode(', ', $activeSections) . '. Підтвердьте зміни вручну.';
                }

                $html = $this->renderPartial('modals/modal_import_result', [
                    'differences'=>$differences,
                    'newProducts'=>$newProductsForModal,
                    'missingProducts'=>$missingProductsForModal,
                    'ambiguousMatches'=>$ambiguousMatchesForModal,
                    'splitCandidates'=>$splitCandidatesForModal,
                    'newCount'=>$newCount,
                    'downloadReportEncoded'=>$downloadReportEncoded
                ]);

                return [
                    'modalContent'=>$html,
                    'modalType'=>'warning',
                    'modalTitle'=>$modalTitle,
                    'modalSubtitle'=>$modalSubtitle,
                    'modalIconSvg'=>'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 8.25C12.4142 8.25 12.75 8.58579 12.75 9V13C12.75 13.4142 12.4142 13.75 12 13.75C11.5858 13.75 11.25 13.4142 11.25 13V9C11.25 8.58579 11.5858 8.25 12 8.25Z" fill="currentColor"/><path d="M12 16.75C12.5523 16.75 13 16.3023 13 15.75C13 15.1977 12.5523 14.75 12 14.75C11.4477 14.75 11 15.1977 11 15.75C11 16.3023 11.4477 16.75 12 16.75Z" fill="currentColor"/><path fill-rule="evenodd" clip-rule="evenodd" d="M10.3196 3.41968C11.0599 2.14559 12.9401 2.14558 13.6804 3.41968L21.4111 16.725C22.157 18.0088 21.2293 19.625 19.7307 19.625H4.26925C2.77067 19.625 1.84304 18.0088 2.58893 16.725L10.3196 3.41968ZM12.3831 4.17304C12.2597 3.96064 11.9403 3.96064 11.8169 4.17304L4.08619 17.4784C3.96187 17.6924 4.11647 17.9688 4.26925 17.9688H19.7307C19.8835 17.9688 20.0381 17.6924 19.9138 17.4784L12.3831 4.17304Z" fill="currentColor"/></svg>',
                ];
            }

            return ['toast'=>['message'=>'Импорт завершен','type'=>'success', 'timeout' => 5000]];

        } catch (\Exception $e) {
            return ['toast'=>['message'=>'Ошибка: '.$e->getMessage(),'type'=>'error']];
        }
    }

    protected function resolveCurrentUser()
    {
        try {
            $frontendUser = \Auth::getUser();
            if ($frontendUser) {
                return $frontendUser;
            }
        } catch (\Throwable $e) {
        }

        try {
            if (class_exists(\Backend\Facades\BackendAuth::class)) {
                $backendUser = \Backend\Facades\BackendAuth::getUser();
                if ($backendUser) {
                    return $backendUser;
                }
            }
        } catch (\Throwable $e) {
        }

        return null;
    }

    protected function organizationId(): int
    {
        $user = $this->resolveCurrentUser();
        return (int) ($user->organization_id ?? 0);
    }
}
