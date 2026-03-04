<?php namespace Samvol\Inventory\Classes\Jobs;

use Cache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Samvol\Inventory\Classes\OperationDocumentGenerator;
use Samvol\Inventory\Models\Operation;

class GenerateOperationDocumentJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public string $taskId;
    public int $operationId;
    public string $action;
    public string $templateId;
    public string $documentName;
    public array $settings;
    public int $userId;

    public function __construct(string $taskId, int $operationId, string $action, string $templateId, string $documentName, array $settings, int $userId)
    {
        $this->taskId = $taskId;
        $this->operationId = $operationId;
        $this->action = $action;
        $this->templateId = $templateId;
        $this->documentName = $documentName;
        $this->settings = $settings;
        $this->userId = $userId;
    }

    public function handle(OperationDocumentGenerator $generator): void
    {
        $this->updateTask(['status' => 'processing', 'message' => 'Формируем документ...']);

        try {
            $operation = Operation::with(['products', 'type'])->find($this->operationId);
            if (!$operation) {
                $this->failWith('Операция не найдена');
                return;
            }

            $docx = $generator->generateDocx($operation, $this->templateId, [
                'document_name' => $this->documentName,
                'settings' => $this->settings,
            ]);
            $previewError = null;
            $pdf = null;

            if ($this->action === 'preview') {
                try {
                    $pdf = $generator->convertDocxToPdf($docx['absolute_path']);
                } catch (\Throwable $e) {
                    $previewError = $e->getMessage();
                }
            }

            $this->updateTask([
                'status' => 'ready',
                'message' => 'Документ сформирован',
                'operation_id' => $this->operationId,
                'action' => $this->action,
                'template_id' => $this->templateId,
                'docx_relative_path' => $docx['relative_path'],
                'pdf_relative_path' => $pdf['relative_path'] ?? null,
                'docx_size_bytes' => $docx['size_bytes'] ?? null,
                'items_count' => $docx['items_count'] ?? null,
                'preview_error' => $previewError,
                'error' => null,
            ]);

            $this->writeAudit([
                'operation_id' => $this->operationId,
                'user_id' => $this->userId,
                'template_id' => $this->templateId,
                'action' => $this->action,
                'mode' => 'async',
                'status' => 'ready',
                'message' => $previewError,
                'items_count' => (int)($docx['items_count'] ?? 0),
                'file_size' => (int)($docx['size_bytes'] ?? 0),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Throwable $e) {
            \Log::error('Generate document async error', [
                'task_id' => $this->taskId,
                'operation_id' => $this->operationId,
                'message' => $e->getMessage(),
            ]);

            $this->writeAudit([
                'operation_id' => $this->operationId,
                'user_id' => $this->userId,
                'template_id' => $this->templateId,
                'action' => $this->action,
                'mode' => 'async',
                'status' => 'error',
                'message' => $e->getMessage(),
                'items_count' => null,
                'file_size' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->failWith('Ошибка генерации: ' . $e->getMessage());
        }
    }

    protected function failWith(string $message): void
    {
        $this->updateTask([
            'status' => 'error',
            'message' => $message,
            'error' => $message,
        ]);
    }

    protected function updateTask(array $attributes): void
    {
        $key = $this->taskCacheKey($this->taskId);
        $current = Cache::get($key, []);
        Cache::put($key, array_merge($current, $attributes), now()->addMinutes(30));
    }

    protected function taskCacheKey(string $taskId): string
    {
        return 'samvol:inventory:docgen:task:' . $taskId;
    }

    protected function writeAudit(array $data): void
    {
        try {
            DB::table('samvol_inventory_document_generations')->insert($data);
        } catch (\Throwable $e) {
            \Log::warning('Failed to write async document generation audit', ['message' => $e->getMessage()]);
        }
    }
}
