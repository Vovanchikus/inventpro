<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Classes\Api\ApiPolicy;
use Samvol\Inventory\Events\InventoryEntityChanged;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\Operation;

class DocumentController extends BaseApiController
{
    public function __construct(private ApiPolicy $apiPolicy)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $perPage = max(1, min(100, (int) $request->input('per_page', 20)));
        $sort = $this->normalizeSort(
            (string) $request->input('sort_by', 'id'),
            (string) $request->input('sort_dir', 'desc'),
            ['id', 'doc_date', 'updated_at', 'operation_id'],
            'id',
            'desc'
        );

        $query = Document::query()->apiList()->with(['operation.type', 'doc_file']);
        $query = $this->apiPolicy->constrainByOrganization($query, $request);

        if ($operationId = (int) $request->input('operation_id', 0)) {
            $query->where('operation_id', $operationId);
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('doc_date', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('doc_date', '<=', $dateTo);
        }

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('doc_name', 'like', '%' . $search . '%')
                    ->orWhere('doc_num', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy($sort['field'], $sort['dir']);
        $queryData = [
            'organization_id' => $this->apiPolicy->organizationId($request),
            'page' => (int) $request->input('page', 1),
            'per_page' => $perPage,
            'operation_id' => (int) $request->input('operation_id', 0),
            'date_from' => (string) $request->input('date_from', ''),
            'date_to' => (string) $request->input('date_to', ''),
            'q' => (string) $request->input('q', ''),
            'sort_by' => $sort['field'],
            'sort_dir' => $sort['dir'],
        ];

        $paginator = $this->cached('documents.index', $queryData, fn() => $query->paginate($perPage));

        return $this->paginated($paginator, fn(Document $document) => $this->mapDocument($document));
    }

    public function show(Request $request, int $id)
    {
        $document = $this->cached('documents.show', [
            'organization_id' => $this->apiPolicy->organizationId($request),
            'id' => $id,
        ], function () use ($id, $request) {
            $query = Document::query()->apiList()->with(['operation.type', 'doc_file'])->where('id', $id);
            return $this->apiPolicy->constrainByOrganization($query, $request)->first();
        });
        if (!$document) {
            return $this->fail('Document not found', 404);
        }

        return $this->ok($this->mapDocument($document));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'operation_id' => 'required|integer',
            'doc_name' => 'required|string|max:255',
            'doc_num' => 'nullable|string|max:120',
            'doc_date' => 'nullable|date',
            'doc_purpose' => 'nullable|string|max:500',
            'mime_type' => 'nullable|string|max:120',
            'file_size' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $operation = Operation::query()->where('id', (int) $request->input('operation_id'));
        $operation = $this->apiPolicy->constrainByOrganization($operation, $request)->first();
        if (!$operation) {
            return $this->fail('Operation not found', 404);
        }

        $document = new Document();
        $document->operation_id = (int) $request->input('operation_id');
        $document->doc_name = (string) $request->input('doc_name');
        $document->doc_num = (string) $request->input('doc_num', '');
        $document->doc_date = $request->input('doc_date');
        $document->doc_purpose = (string) $request->input('doc_purpose', '');
        $document->mime_type = (string) $request->input('mime_type', '');
        $document->file_size = (int) $request->input('file_size', 0);
        $document->organization_id = $operation->organization_id;
        $document->save();

        $document->load(['operation.type', 'doc_file']);
        $this->bumpCacheVersion('documents.index');
        $this->bumpCacheVersion('documents.show');
        if ($document->organization_id) {
            event(new InventoryEntityChanged((int) $document->organization_id, 'document', 'created', (int) $document->id, optional($document->updated_at)->toIso8601String()));
        }
        return $this->ok($this->mapDocument($document), 201);
    }

    public function update(Request $request, int $id)
    {
        $documentQuery = Document::query()->where('id', $id);
        $document = $this->apiPolicy->constrainByOrganization($documentQuery, $request)->first();
        if (!$document) {
            return $this->fail('Document not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'doc_name' => 'sometimes|string|max:255',
            'doc_num' => 'nullable|string|max:120',
            'doc_date' => 'nullable|date',
            'doc_purpose' => 'nullable|string|max:500',
            'mime_type' => 'nullable|string|max:120',
            'file_size' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        foreach (['doc_name', 'doc_num', 'doc_date', 'doc_purpose', 'mime_type', 'file_size'] as $field) {
            if ($request->has($field)) {
                $document->{$field} = $request->input($field);
            }
        }

        $document->save();
        $document->load(['operation.type', 'doc_file']);
        $this->bumpCacheVersion('documents.index');
        $this->bumpCacheVersion('documents.show');

        if ($document->organization_id) {
            event(new InventoryEntityChanged((int) $document->organization_id, 'document', 'updated', (int) $document->id, optional($document->updated_at)->toIso8601String()));
        }

        return $this->ok($this->mapDocument($document));
    }

    public function destroy(Request $request, int $id)
    {
        $documentQuery = Document::query()->where('id', $id);
        $document = $this->apiPolicy->constrainByOrganization($documentQuery, $request)->first();
        if (!$document) {
            return $this->fail('Document not found', 404);
        }

        $organizationId = (int) ($document->organization_id ?? 0);
        $deletedId = (int) $document->id;
        $document->delete();
        $this->bumpCacheVersion('documents.index');
        $this->bumpCacheVersion('documents.show');

        if ($organizationId > 0) {
            event(new InventoryEntityChanged($organizationId, 'document', 'deleted', $deletedId, now()->toIso8601String()));
        }

        return $this->ok(['deleted' => true]);
    }

    private function mapDocument(Document $document): array
    {
        return [
            'id' => (int) $document->id,
            'operation_id' => (int) $document->operation_id,
            'doc_name' => (string) $document->doc_name,
            'doc_num' => (string) $document->doc_num,
            'doc_date' => $document->doc_date,
            'doc_purpose' => (string) $document->doc_purpose,
            'mime_type' => (string) ($document->mime_type ?? ''),
            'file_size' => (int) ($document->file_size ?? 0),
            'file' => $document->doc_file ? [
                'id' => (int) $document->doc_file->id,
                'url' => url($document->doc_file->getPath()),
                'name' => (string) $document->doc_file->file_name,
            ] : null,
            'updated_at' => optional($document->updated_at)->toIso8601String(),
        ];
    }
}
