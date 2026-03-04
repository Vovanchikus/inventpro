<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Classes\Api\ApiPolicy;
use Samvol\Inventory\Events\InventoryEntityChanged;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\Product;

class OperationController extends BaseApiController
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
            ['id', 'created_at', 'updated_at', 'type_id'],
            'id',
            'desc'
        );

        $query = Operation::query()
            ->apiList()
            ->with(['type', 'documents', 'products'])
            ->withCount('products');
        $query = $this->apiPolicy->constrainByOrganization($query, $request);

        if ($typeId = (int) $request->input('type_id', 0)) {
            $query->where('type_id', $typeId);
        }

        if (($isDraft = $request->input('is_draft')) !== null) {
            $query->where('is_draft', filter_var($isDraft, FILTER_VALIDATE_BOOL));
        }

        if ($dateFrom = $request->input('date_from')) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo = $request->input('date_to')) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        $query->orderBy($sort['field'], $sort['dir']);
        $queryData = [
            'organization_id' => $this->apiPolicy->organizationId($request),
            'page' => (int) $request->input('page', 1),
            'per_page' => $perPage,
            'type_id' => (int) $request->input('type_id', 0),
            'is_draft' => (string) $request->input('is_draft', ''),
            'date_from' => (string) $request->input('date_from', ''),
            'date_to' => (string) $request->input('date_to', ''),
            'sort_by' => $sort['field'],
            'sort_dir' => $sort['dir'],
        ];

        $paginator = $this->cached('operations.index', $queryData, fn() => $query->paginate($perPage));

        return $this->paginated($paginator, fn(Operation $operation) => $this->mapOperation($operation));
    }

    public function show(Request $request, int $id)
    {
        $operation = $this->cached('operations.show', [
            'organization_id' => $this->apiPolicy->organizationId($request),
            'id' => $id,
        ], function () use ($id, $request) {
            $query = Operation::query()
                ->apiList()
                ->with(['type', 'documents.doc_file', 'products'])
                ->withCount('products')
                ->where('id', $id);

            return $this->apiPolicy->constrainByOrganization($query, $request)->first();
        });

        if (!$operation) {
            return $this->fail('Operation not found', 404);
        }

        return $this->ok($this->mapOperation($operation));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type_id' => 'required|integer',
            'is_draft' => 'nullable|boolean',
            'is_posted' => 'nullable|boolean',
            'mobile_note' => 'nullable|string|max:500',
            'external_id' => 'nullable|string|max:120',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required_with:products|integer',
            'products.*.quantity' => 'required_with:products|numeric|min:0',
            'products.*.sum' => 'nullable|numeric|min:0',
            'products.*.counteragent' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $operation = new Operation();
        $operation->type_id = (int) $request->input('type_id');
        $operation->is_draft = (bool) $request->boolean('is_draft', false);
        $operation->is_posted = (bool) $request->boolean('is_posted', false);
        $operation->mobile_note = (string) $request->input('mobile_note', '');
        $operation->external_id = (string) $request->input('external_id', '');
        $operation->organization_id = $this->apiPolicy->organizationId($request);
        $operation->save();

        $this->syncProducts($operation, (array) $request->input('products', []));
        $operation->load(['type', 'documents', 'products']);
        $this->bumpCacheVersion('operations.index');
        $this->bumpCacheVersion('operations.show');
        if ($operation->organization_id) {
            event(new InventoryEntityChanged((int) $operation->organization_id, 'operation', 'created', (int) $operation->id, optional($operation->updated_at)->toIso8601String()));
        }

        return $this->ok($this->mapOperation($operation), 201);
    }

    public function update(Request $request, int $id)
    {
        $operationQuery = Operation::query()->where('id', $id);
        $operation = $this->apiPolicy->constrainByOrganization($operationQuery, $request)->first();
        if (!$operation) {
            return $this->fail('Operation not found', 404);
        }

        $validator = Validator::make($request->all(), [
            'type_id' => 'sometimes|integer',
            'is_draft' => 'sometimes|boolean',
            'is_posted' => 'sometimes|boolean',
            'mobile_note' => 'nullable|string|max:500',
            'external_id' => 'nullable|string|max:120',
            'products' => 'nullable|array',
            'products.*.product_id' => 'required_with:products|integer',
            'products.*.quantity' => 'required_with:products|numeric|min:0',
            'products.*.sum' => 'nullable|numeric|min:0',
            'products.*.counteragent' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        foreach (['type_id', 'mobile_note', 'external_id'] as $field) {
            if ($request->has($field)) {
                $operation->{$field} = $request->input($field);
            }
        }

        if ($request->has('is_draft')) {
            $operation->is_draft = (bool) $request->boolean('is_draft');
        }
        if ($request->has('is_posted')) {
            $operation->is_posted = (bool) $request->boolean('is_posted');
        }

        $operation->save();

        if ($request->has('products')) {
            $this->syncProducts($operation, (array) $request->input('products', []));
        }

        $operation->load(['type', 'documents', 'products']);
        $this->bumpCacheVersion('operations.index');
        $this->bumpCacheVersion('operations.show');
        if ($operation->organization_id) {
            event(new InventoryEntityChanged((int) $operation->organization_id, 'operation', 'updated', (int) $operation->id, optional($operation->updated_at)->toIso8601String()));
        }
        return $this->ok($this->mapOperation($operation));
    }

    public function destroy(Request $request, int $id)
    {
        $operationQuery = Operation::query()->where('id', $id);
        $operation = $this->apiPolicy->constrainByOrganization($operationQuery, $request)->first();
        if (!$operation) {
            return $this->fail('Operation not found', 404);
        }

        $organizationId = (int) ($operation->organization_id ?? 0);
        $deletedId = (int) $operation->id;
        $operation->products()->detach();
        $operation->delete();
        $this->bumpCacheVersion('operations.index');
        $this->bumpCacheVersion('operations.show');

        if ($organizationId > 0) {
            event(new InventoryEntityChanged($organizationId, 'operation', 'deleted', $deletedId, now()->toIso8601String()));
        }

        return $this->ok(['deleted' => true]);
    }

    private function syncProducts(Operation $operation, array $products): void
    {
        $syncPayload = [];

        foreach ($products as $item) {
            $productId = (int) ($item['product_id'] ?? 0);
            if ($productId <= 0) {
                continue;
            }

            if (!Product::query()->where('id', $productId)->exists()) {
                continue;
            }

            $syncPayload[$productId] = [
                'quantity' => (float) ($item['quantity'] ?? 0),
                'sum' => (float) ($item['sum'] ?? 0),
                'counteragent' => (string) ($item['counteragent'] ?? ''),
            ];
        }

        $operation->products()->sync($syncPayload);
    }

    private function mapOperation(Operation $operation): array
    {
        return [
            'id' => (int) $operation->id,
            'slug' => (string) $operation->slug,
            'type' => $operation->type ? [
                'id' => (int) $operation->type->id,
                'name' => (string) $operation->type->name,
            ] : null,
            'status' => [
                'is_draft' => (bool) $operation->is_draft,
                'is_posted' => (bool) $operation->is_posted,
            ],
            'products_count' => (int) ($operation->products_count ?? $operation->products->count()),
            'documents_count' => $operation->relationLoaded('documents') ? $operation->documents->count() : $operation->documents()->count(),
            'mobile_note' => (string) ($operation->mobile_note ?? ''),
            'external_id' => (string) ($operation->external_id ?? ''),
            'created_at' => optional($operation->created_at)->toIso8601String(),
            'updated_at' => optional($operation->updated_at)->toIso8601String(),
        ];
    }
}
