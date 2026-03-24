<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Classes\Api\ApiPolicy;
use Samvol\Inventory\Models\Product;

class ProductController extends BaseApiController
{
    public function __construct(private ApiPolicy $apiPolicy)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $maxPerPage = max(1, (int) config('samvol.inventory::api.max_per_page', 100));
        $validator = Validator::make($request->query(), [
            'updated_since' => 'sometimes|date',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:' . $maxPerPage,
            'sort_by' => 'sometimes|string|in:id,name,inv_number,price,updated_at',
            'sort_dir' => 'sometimes|string|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);
        $updatedSince = $this->resolveUpdatedSince($request);
        $organizationId = $this->apiPolicy->organizationId($request);

        $sort = $this->normalizeSort(
            (string) $request->input('sort_by', 'updated_at'),
            (string) $request->input('sort_dir', $updatedSince ? 'asc' : 'desc'),
            ['id', 'name', 'inv_number', 'price', 'updated_at'],
            'updated_at',
            $updatedSince ? 'asc' : 'desc'
        );

        $query = Product::query()->apiList()->with(['category', 'images']);
        $query = $this->apiPolicy->constrainByOrganization($query, $request);

        // Precompute quantity/sum in SQL to avoid expensive per-item aggregate queries.
        $quantityQuery = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
            ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
            ->whereColumn('op.product_id', 'samvol_inventory_products.id')
            ->when($organizationId, fn($q) => $q->where('o.organization_id', $organizationId))
            ->selectRaw("COALESCE(SUM(CASE WHEN LOWER(t.name) = 'приход' THEN op.quantity WHEN LOWER(t.name) = 'передача' THEN -op.quantity WHEN LOWER(t.name) = 'списание' THEN -op.quantity WHEN LOWER(t.name) = 'импорт' THEN op.quantity WHEN LOWER(t.name) = 'импорт приход' THEN op.quantity WHEN LOWER(t.name) = 'импорт расход' THEN -op.quantity ELSE 0 END), 0)");

        $sumQuery = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'op.operation_id', '=', 'o.id')
            ->join('samvol_inventory_operation_types as t', 'o.type_id', '=', 't.id')
            ->whereColumn('op.product_id', 'samvol_inventory_products.id')
            ->when($organizationId, fn($q) => $q->where('o.organization_id', $organizationId))
            ->selectRaw("COALESCE(SUM(CASE WHEN LOWER(t.name) = 'приход' THEN op.sum WHEN LOWER(t.name) = 'передача' THEN -op.sum WHEN LOWER(t.name) = 'списание' THEN -op.sum WHEN LOWER(t.name) = 'импорт' THEN op.sum WHEN LOWER(t.name) = 'импорт приход' THEN op.sum WHEN LOWER(t.name) = 'импорт расход' THEN -op.sum ELSE 0 END), 0)");

        $query->selectSub($quantityQuery, 'calculated_quantity');
        $query->selectSub($sumQuery, 'calculated_sum');

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('inv_number', 'like', '%' . $search . '%');
            });
        }

        if ($categoryId = (int) $request->input('category_id', 0)) {
            $query->where('category_id', $categoryId);
        }

        if ($updatedSince) {
            // Exclusive cursor semantics avoids duplicate rows during incremental sync.
            $query->where('updated_at', '>', $updatedSince->toDateTimeString());
        }

        $query->orderBy($sort['field'], $sort['dir']);
        if ($sort['field'] !== 'id') {
            $query->orderBy('id', 'asc');
        }

        $queryData = [
            'organization_id' => $organizationId,
            'page' => $page,
            'per_page' => $perPage,
            'q' => (string) $request->input('q', ''),
            'category_id' => (int) $request->input('category_id', 0),
            'updated_since' => (string) ($updatedSince?->toIso8601String() ?? ''),
            'sort_by' => $sort['field'],
            'sort_dir' => $sort['dir'],
        ];

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->paginated($paginator, fn(Product $product) => $this->mapProduct($product));
    }

    public function show(Request $request, int $id)
    {
        $item = $this->cached('products.show', [
            'organization_id' => $this->apiPolicy->organizationId($request),
            'id' => $id,
        ], function () use ($id, $request) {
            $query = Product::query()->apiList()->with(['category', 'images'])->where('id', $id);

            return $this->apiPolicy->constrainByOrganization($query, $request)->first();
        });
        if (!$item) {
            return $this->fail('Product not found', 404);
        }

        return $this->ok($this->mapProduct($item));
    }

    private function mapProduct(Product $product): array
    {
        return [
            'id' => (int) $product->id,
            'name' => (string) $product->name,
            'inv_number' => (string) $product->inv_number,
            'unit' => (string) $product->unit,
            'price' => (float) $product->price,
            'quantity' => (float) $product->calculated_quantity,
            'sum' => (float) $product->calculated_sum,
            'mobile_summary' => (string) ($product->mobile_summary ?? ''),
            'external_id' => (string) ($product->external_id ?? ''),
            'category' => $product->category ? [
                'id' => (int) $product->category->id,
                'name' => (string) $product->category->name,
            ] : null,
            'images' => $product->images->map(fn($image) => [
                'id' => (int) $image->id,
                'url' => url($image->getPath()),
                'name' => (string) $image->file_name,
                'size' => (int) $image->file_size,
            ])->values(),
            'updated_at' => optional($product->updated_at)->toIso8601String(),
        ];
    }
}
