<?php namespace Samvol\Inventory\Controllers;

use Auth;
use Cache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Samvol\Inventory\Classes\DocumentTemplateSettings;
use Samvol\Inventory\Classes\Jobs\GenerateOperationDocumentJob;
use Samvol\Inventory\Classes\OrganizationAccess;
use Samvol\Inventory\Classes\OperationDocumentGenerator;
use Samvol\Inventory\Classes\SettingsScopeResolver;
use Samvol\Inventory\Models\Operation;

class OperationDocumentController
{
    protected const ASYNC_ITEMS_THRESHOLD = 120;
    protected const ASYNC_SIZE_THRESHOLD_MB = 5;

    public function templates(Request $request, OperationDocumentGenerator $generator)
    {
        if (!$this->authorizeAdmin()) {
            return $this->error('Недостаточно прав', 403);
        }

        $templates = $generator->listTemplates();
        $operationId = (int)$request->query('operation_id', 0);

        $data = [
            'templates' => $templates,
            'resolved_template_id' => 'default',
            'document_name' => null,
        ];

        if ($operationId > 0) {
            $operation = Operation::with(['documents'])->find($operationId);
            if ($operation) {
                $data['document_name'] = $generator->getPrimaryDocumentName($operation);
                $data['resolved_template_id'] = $generator->resolveTemplateIdForOperation($operation, $templates);
            }
        }

        return $this->success($data);
    }

    public function generate($id, Request $request, OperationDocumentGenerator $generator)
    {
        $user = $this->resolveUser();
        if (!$this->authorizeAdmin($user)) {
            return $this->error('Недостаточно прав', 403);
        }

        $operation = Operation::with(['products', 'type', 'documents'])->find($id);
        if (!$operation) {
            return $this->error('Операция не найдена', 404);
        }

        $action = $request->input('action', 'preview');
        if (!in_array($action, ['preview', 'download'])) {
            return $this->error('Некорректное действие', 422);
        }

        $templateId = (string)$request->input('template_id', 'auto');
        if ($templateId === '' || $templateId === 'auto') {
            $templateId = $generator->resolveTemplateIdForOperation($operation);
        }
        $documentName = trim((string)$request->input('document_name', ''));
        $settings = $this->normalizeDocSettings($request->input('settings', []), $user);
        $itemCount = $generator->countItems($operation);
        $estimatedBytes = $generator->estimateDocSizeBytes($operation);

        $needAsync = $itemCount > self::ASYNC_ITEMS_THRESHOLD
            || $estimatedBytes > (self::ASYNC_SIZE_THRESHOLD_MB * 1024 * 1024);

        if ($needAsync) {
            $taskId = (string) Str::uuid();
            Cache::put($this->taskCacheKey($taskId), [
                'status' => 'queued',
                'operation_id' => (int)$operation->id,
                'action' => $action,
                'template_id' => $templateId,
                'document_name' => $documentName !== '' ? $documentName : null,
                'error' => null,
            ], now()->addMinutes(30));

            GenerateOperationDocumentJob::dispatch(
                $taskId,
                (int)$operation->id,
                $action,
                $templateId,
                $documentName,
                $settings,
                (int)($user->id ?? 0)
            )->afterResponse();

            return $this->success([
                'queued' => true,
                'task_id' => $taskId,
                'message' => 'Документ поставлен в очередь формирования.',
            ]);
        }

        try {
            $docx = $generator->generateDocx($operation, $templateId, [
                'document_name' => $documentName,
                'settings' => $settings,
            ]);
            $previewError = null;
            $pdf = null;

            if ($action === 'preview') {
                try {
                    $pdf = $generator->convertDocxToPdf($docx['absolute_path']);
                } catch (\Throwable $e) {
                    $previewError = $e->getMessage();
                    \Log::warning('Generate document preview error', [
                        'operation_id' => (int)$operation->id,
                        'message' => $previewError,
                    ]);
                }
            }

            $this->writeAudit([
                'operation_id' => (int)$operation->id,
                'user_id' => (int)($user->id ?? 0),
                'template_id' => $templateId,
                'action' => $action,
                'mode' => 'sync',
                'status' => 'ready',
                'message' => $previewError,
                'items_count' => (int)($docx['items_count'] ?? 0),
                'file_size' => (int)($docx['size_bytes'] ?? 0),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $response = [
                'queued' => false,
                'operation_id' => (int)$operation->id,
            ];

            if ($action === 'download' || !$pdf) {
                $response['download_url'] = $this->issueFileUrl(
                    $docx['relative_path'],
                    $docx['file_name'],
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'attachment',
                    (int)($user->id ?? 0)
                );
            }

            if ($pdf) {
                $response['preview_url'] = $this->issueFileUrl(
                    $pdf['relative_path'],
                    sprintf('operation_%d_preview.pdf', (int)$operation->id),
                    'application/pdf',
                    'inline',
                    (int)($user->id ?? 0)
                );
            }

            if ($previewError) {
                $response['preview_error'] = $previewError;
            }

            return $this->success($response);
        } catch (\Throwable $e) {
            \Log::error('Generate document sync error', [
                'operation_id' => $operation->id,
                'message' => $e->getMessage(),
            ]);

            $this->writeAudit([
                'operation_id' => (int)$operation->id,
                'user_id' => (int)($user->id ?? 0),
                'template_id' => $templateId,
                'action' => $action,
                'mode' => 'sync',
                'status' => 'error',
                'message' => $e->getMessage(),
                'items_count' => $itemCount,
                'file_size' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return $this->error('Ошибка генерации документа: ' . $e->getMessage(), 500);
        }
    }

    public function status($taskId)
    {
        $user = $this->resolveUser();
        if (!$this->authorizeAdmin($user)) {
            return $this->error('Недостаточно прав', 403);
        }

        $payload = Cache::get($this->taskCacheKey($taskId));
        if (!$payload) {
            return $this->error('Задача не найдена или устарела', 404);
        }

        $result = [
            'task_id' => $taskId,
            'status' => $payload['status'] ?? 'unknown',
            'message' => $payload['message'] ?? null,
            'preview_error' => $payload['preview_error'] ?? null,
            'error' => $payload['error'] ?? null,
        ];

        if (($payload['status'] ?? null) === 'ready') {
            $docxPath = $payload['docx_relative_path'] ?? null;
            $pdfPath = $payload['pdf_relative_path'] ?? null;
            $operationId = (int)($payload['operation_id'] ?? 0);

            if ($docxPath) {
                $result['download_url'] = $this->issueFileUrl(
                    $docxPath,
                    sprintf('operation_%d.docx', $operationId),
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'attachment',
                    (int)($user->id ?? 0)
                );
            }

            if ($pdfPath) {
                $result['preview_url'] = $this->issueFileUrl(
                    $pdfPath,
                    sprintf('operation_%d_preview.pdf', $operationId),
                    'application/pdf',
                    'inline',
                    (int)($user->id ?? 0)
                );
            }
        }

        return $this->success($result);
    }

    public function file($token)
    {
        $user = $this->resolveUser();
        if (!$this->authorizeAdmin($user)) {
            abort(403, 'Недостаточно прав');
        }

        $payload = Cache::get($this->fileTokenCacheKey($token));
        if (!$payload) {
            abort(404, 'Файл недоступен');
        }

        $userId = (int)($user->id ?? 0);
        $expectedUserId = (int)($payload['user_id'] ?? 0);
        if ($expectedUserId > 0 && $expectedUserId !== $userId) {
            abort(403, 'Нет доступа к файлу');
        }

        $relativePath = $payload['path'] ?? '';
        $absolutePath = storage_path('app/' . ltrim($relativePath, '/'));
        if (!is_file($absolutePath)) {
            abort(404, 'Файл не найден');
        }

        $content = file_get_contents($absolutePath);
        $contentType = $payload['content_type'] ?? 'application/octet-stream';
        $disposition = $payload['disposition'] ?? 'attachment';
        $fileName = $payload['file_name'] ?? basename($absolutePath);

        return Response::make($content, 200, [
            'Content-Type' => $contentType,
            'Content-Disposition' => $disposition . '; filename="' . $fileName . '"',
            'Content-Length' => filesize($absolutePath),
        ]);
    }

    protected function issueFileUrl(
        string $relativePath,
        string $fileName,
        string $contentType,
        string $disposition,
        int $userId
    ): string {
        $token = (string) Str::uuid();
        Cache::put($this->fileTokenCacheKey($token), [
            'path' => $relativePath,
            'file_name' => $fileName,
            'content_type' => $contentType,
            'disposition' => $disposition,
            'user_id' => $userId,
        ], now()->addMinutes(20));

        return url('/generated-documents/' . $token);
    }

    protected function taskCacheKey(string $taskId): string
    {
        return 'samvol:inventory:docgen:task:' . $taskId;
    }

    protected function normalizeDocSettings($settings, $user = null): array
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

        $normalized = [];
        $scopeKey = SettingsScopeResolver::resolveScopeKey($this->resolveUser($user));
        $defaults = DocumentTemplateSettings::extractDocDefaults($scopeKey);
        $settingsSnapshot = DocumentTemplateSettings::get($scopeKey);
        $rolesSnapshot = (array)($settingsSnapshot['roles'] ?? []);

        foreach ($personKeys as $key) {
            $value = trim((string)($settings[$key] ?? ''));
            $normalized[$key] = $value !== ''
                ? $value
                : trim((string)($defaults[$key] ?? ''));
        }

        foreach ($fieldKeys as $key) {
            $value = trim((string)($settings[$key] ?? ''));
            if ($key === 'document_year') {
                $value = preg_replace('/\D+/', '', $value);
                $value = $value !== '' ? substr($value, 0, 4) : '';
            }

            $fallback = trim((string)($settingsSnapshot[$key] ?? ''));
            if ($key === 'document_year' && $fallback === '') {
                $fallback = (string)date('Y');
            }

            $normalized[$key] = $value !== '' ? $value : $fallback;
        }

        $commissionRoleKeys = [
            'commission_head',
            'commission_member_1',
            'commission_member_2',
            'commission_member_3',
        ];

        foreach ($commissionRoleKeys as $roleKey) {
            $selectedName = trim((string)($normalized[$roleKey] ?? ''));
            [$personName, $personPosition] = $this->resolveRolePersonFromSnapshot($rolesSnapshot, $roleKey, $selectedName);

            if ($personName === '') {
                $personName = $selectedName;
            }

            $normalized[$roleKey . '_name'] = $personName;
            $normalized[$roleKey . '_position'] = $personPosition;
            $normalized[$roleKey] = $this->composeRoleDisplay($personName, $personPosition);
        }

        $singleRoleKeys = [
            'receiver_name',
            'responsible_person',
        ];

        foreach ($singleRoleKeys as $roleKey) {
            $selectedName = trim((string)($normalized[$roleKey] ?? ''));
            [$personName, $personPosition] = $this->resolveRolePersonFromSnapshot($rolesSnapshot, $roleKey, $selectedName);

            if ($personName === '') {
                $personName = $selectedName;
            }

            $fullValue = $this->composeRoleDisplay($personName, $personPosition);

            $normalized[$roleKey . '_name'] = $personName;
            $normalized[$roleKey . '_position'] = $personPosition;
            $normalized[$roleKey . '_full'] = $fullValue;

            if ($roleKey === 'receiver_name') {
                $normalized['receiver_name'] = $personName !== '' ? $personName : '-';
                $normalized['receiver_position'] = $personPosition !== '' ? $personPosition : '-';
                $normalized['receiver_full'] = $fullValue;
            }

            if ($roleKey === 'responsible_person') {
                $normalized['responsible_person'] = $personName !== '' ? $personName : '-';
                $normalized['responsible_person_name'] = $personName !== '' ? $personName : '-';
                $normalized['responsible_person_position'] = $personPosition !== '' ? $personPosition : '-';
                $normalized['responsible_person_full'] = $fullValue;
                $normalized['materially_responsible_person'] = $normalized['responsible_person'];
                $normalized['materially_responsible_person_name'] = $normalized['responsible_person_name'];
                $normalized['materially_responsible_person_position'] = $normalized['responsible_person_position'];
                $normalized['materially_responsible_person_full'] = $normalized['responsible_person_full'];
            }
        }

        $normalized['commission_members'] = trim(implode(', ', array_filter([
            $normalized['commission_member_1'] ?? '',
            $normalized['commission_member_2'] ?? '',
            $normalized['commission_member_3'] ?? '',
        ], static function ($value) {
            $value = trim((string)$value);
            return $value !== '' && $value !== '-';
        })));

        return $normalized;
    }

    protected function resolveRolePersonFromSnapshot(array $rolesSnapshot, string $roleKey, string $preferredName = ''): array
    {
        $roleData = (array)($rolesSnapshot[$roleKey] ?? []);
        $people = is_array($roleData['people'] ?? null) ? $roleData['people'] : [];
        $preferredName = trim($preferredName);

        if ($preferredName !== '') {
            foreach ($people as $person) {
                $name = trim((string)($person['name'] ?? ''));
                if ($name !== '' && $name === $preferredName) {
                    return [$name, trim((string)($person['position'] ?? ''))];
                }
            }
        }

        $selectedId = trim((string)($roleData['selected_id'] ?? ''));
        if ($selectedId !== '') {
            foreach ($people as $person) {
                if ((string)($person['id'] ?? '') === $selectedId) {
                    return [
                        trim((string)($person['name'] ?? '')),
                        trim((string)($person['position'] ?? '')),
                    ];
                }
            }
        }

        foreach ($people as $person) {
            $name = trim((string)($person['name'] ?? ''));
            if ($name !== '') {
                return [$name, trim((string)($person['position'] ?? ''))];
            }
        }

        return ['', ''];
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

    protected function fileTokenCacheKey(string $token): string
    {
        return 'samvol:inventory:docgen:file:' . $token;
    }

    protected function resolveUser($user = null)
    {
        if ($user) {
            return $user;
        }

        try {
            $frontendUser = \Auth::getUser();
            if ($frontendUser) {
                return $frontendUser;
            }
        } catch (\Throwable $e) {
        }

        try {
            $defaultUser = Auth::user();
            if ($defaultUser) {
                return $defaultUser;
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

    protected function authorizeAdmin($user = null): bool
    {
        $user = $this->resolveUser($user);
        $allow = false;

        if (!$user) {
            $this->logAuthContext('docgen_auth', null, false, 'no_user');
            return false;
        }

        if (OrganizationAccess::isOrganizationAdmin($user)) {
            $allow = true;
        }

        if (!$allow && method_exists($user, 'isInGroup') && $user->isInGroup('admin')) {
            $allow = true;
        }

        if (!$allow && property_exists($user, 'is_superuser') && (bool)$user->is_superuser === true) {
            $allow = true;
        }

        if (!$allow && method_exists($user, 'hasAccess')) {
            $allow = (bool)$user->hasAccess('samvol.inventory.*');
        }

        $this->logAuthContext('docgen_auth', $user, $allow, $allow ? 'allowed' : 'not_admin');

        return $allow;
    }

    protected function logAuthContext(string $scope, $user, bool $allowed, string $reason): void
    {
        try {
            $groupCodes = [];
            if ($user && method_exists($user, 'groups')) {
                try {
                    $groupCodes = $user->groups()->pluck('code')->all();
                } catch (\Throwable $e) {
                    $groupCodes = [];
                }
            }

            \Log::info('[samvol] doc generation auth', [
                'scope' => $scope,
                'allowed' => $allowed,
                'reason' => $reason,
                'user_present' => (bool)$user,
                'user_class' => $user ? get_class($user) : null,
                'user_id' => $user->id ?? null,
                'login' => $user->login ?? null,
                'email' => $user->email ?? null,
                'is_superuser' => isset($user->is_superuser) ? (bool)$user->is_superuser : null,
                'groups' => $groupCodes,
                'url' => request()->fullUrl(),
                'method' => request()->method(),
                'ip' => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            \Log::warning('[samvol] doc generation auth log failed', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    protected function success(array $data)
    {
        $data = $this->sanitizeUtf8Recursive($data);

        return Response::json([
            'success' => true,
            'data' => $data,
            'error' => null,
        ], 200, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function error(string $message, int $status = 400)
    {
        $message = $this->sanitizeUtf8String($message);

        return Response::json([
            'success' => false,
            'data' => null,
            'error' => $message,
        ], $status, [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    protected function writeAudit(array $data): void
    {
        try {
            $data = $this->sanitizeUtf8Recursive($data);
            DB::table('samvol_inventory_document_generations')->insert($data);
        } catch (\Throwable $e) {
            \Log::warning('Failed to write document generation audit', ['message' => $e->getMessage()]);
        }
    }

    protected function sanitizeUtf8Recursive($value)
    {
        if (is_array($value)) {
            foreach ($value as $key => $item) {
                $value[$key] = $this->sanitizeUtf8Recursive($item);
            }

            return $value;
        }

        if (is_string($value)) {
            return $this->sanitizeUtf8String($value);
        }

        return $value;
    }

    protected function sanitizeUtf8String(string $value): string
    {
        if ($value === '') {
            return '';
        }

        if (function_exists('mb_check_encoding') && mb_check_encoding($value, 'UTF-8')) {
            return $value;
        }

        if (function_exists('mb_convert_encoding')) {
            $converted = @mb_convert_encoding($value, 'UTF-8', 'UTF-8,Windows-1251,CP1251,CP866,KOI8-R,ISO-8859-1');
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        if (function_exists('iconv')) {
            $converted = @iconv('Windows-1251', 'UTF-8//IGNORE', $value);
            if (is_string($converted) && $converted !== '') {
                return $converted;
            }
        }

        $clean = @preg_replace('/[^\x09\x0A\x0D\x20-\x7E\x{0400}-\x{04FF}]/u', '', $value);
        return is_string($clean) ? $clean : 'Ошибка кодировки строки.';
    }
}
