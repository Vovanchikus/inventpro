<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Classes\Api\ApiPolicy;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\OperationProduct;
use Samvol\Inventory\Models\Product;

class HistoryController extends BaseApiController
{
    public function __construct(private ApiPolicy $apiPolicy)
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $maxPerPage = max(1, (int) config('samvol.inventory::api.max_per_page', 100));
        $validator = Validator::make($request->query(), [
            'slug' => 'sometimes|string|max:255',
            'type_id' => 'sometimes|integer',
            'counteragent' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer|min:2000|max:2100',
            'updated_since' => 'sometimes|date',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:' . $maxPerPage,
            'sort_by' => 'sometimes|string|in:id,doc_date,updated_at,quantity,sum',
            'sort_dir' => 'sometimes|string|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $organizationId = $this->apiPolicy->organizationId($request);
        if (!$organizationId) {
            return $this->apiError('AUTH_FORBIDDEN', 'Organization is not defined for current user', 403);
        }

        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);
        $updatedSince = $this->resolveUpdatedSince($request);

        $sort = $this->normalizeSort(
            (string) $request->input('sort_by', 'doc_date'),
            (string) $request->input('sort_dir', 'desc'),
            ['id', 'doc_date', 'updated_at', 'quantity', 'sum'],
            'doc_date',
            'desc'
        );

        $query = OperationProduct::query()
            ->with([
                'product:id,name,unit,inv_number,price,mobile_summary,external_id,updated_at',
                'operation:id,type_id,updated_at,created_at,is_draft,is_posted,mobile_note,external_id',
                'operation.type:id,name',
                'operation.documents:id,operation_id,doc_name,doc_num,doc_date,doc_purpose,mime_type,file_size,updated_at',
            ])
            ->where('organization_id', $organizationId)
            ->whereDoesntHave('operation', fn($q) => $q->whereIn('type_id', [6, 7]));

        $historyProductId = null;

        if ($slug = trim((string) $request->input('slug', ''))) {
            $product = Product::query()
                ->where('slug', $slug)
                ->where('organization_id', $organizationId)
                ->first();

            if (!$product) {
                return $this->ok([
                    'items' => [],
                ], 200, [
                    'pagination' => [
                        'current_page' => $page,
                        'per_page' => $perPage,
                        'total' => 0,
                        'last_page' => 1,
                    ],
                    'filters' => [
                        'types' => [],
                        'counteragents' => [],
                        'years' => [],
                    ],
                ]);
            }

            $historyProductId = (int) $product->id;
            $query->where('product_id', $historyProductId);
        }

        if ($typeId = (int) $request->input('type_id', 0)) {
            $query->whereHas('operation', fn($q) => $q->where('type_id', $typeId));
        }

        if ($counteragent = trim((string) $request->input('counteragent', ''))) {
            $query->where('counteragent', $counteragent);
        }

        if ($year = (int) $request->input('year', 0)) {
            $query->whereHas('operation.documents', fn($q) => $q->whereYear('doc_date', $year));
        }

        if ($updatedSince) {
            $query->whereHas('operation', fn($q) => $q->where('updated_at', '>', $updatedSince->toDateTimeString()));
        }

        if ($sort['field'] === 'doc_date') {
            $query->orderByRaw('(SELECT MAX(doc_date) FROM samvol_inventory_documents d WHERE d.operation_id = samvol_inventory_operation_products.operation_id) ' . strtoupper($sort['dir']));
            $query->orderBy('id', 'asc');
        } elseif ($sort['field'] === 'updated_at') {
            $query->orderByRaw('(SELECT o.updated_at FROM samvol_inventory_operations o WHERE o.id = samvol_inventory_operation_products.operation_id) ' . strtoupper($sort['dir']));
            $query->orderBy('id', 'asc');
        } else {
            $query->orderBy($sort['field'], $sort['dir']);
            if ($sort['field'] !== 'id') {
                $query->orderBy('id', 'asc');
            }
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->paginated(
            $paginator,
            fn(OperationProduct $item) => $this->mapHistoryItem($item),
            ['filters' => $this->buildFilters($organizationId, $historyProductId)]
        );
    }

    private function buildFilters(int $organizationId, ?int $productId = null): array
    {
        $types = DB::table('samvol_inventory_operation_products as op')
            ->join('samvol_inventory_operations as o', 'o.id', '=', 'op.operation_id')
            ->join('samvol_inventory_operation_types as t', 't.id', '=', 'o.type_id')
            ->where('op.organization_id', $organizationId)
            ->whereNotIn('o.type_id', [6, 7])
            ->when($productId, fn($q) => $q->where('op.product_id', $productId))
            ->select('o.type_id as id', 't.name')
            ->distinct()
            ->orderBy('t.name')
            ->get()
            ->map(fn($row) => [
                'id' => (int) ($row->id ?? 0),
                'name' => (string) ($row->name ?? ''),
            ])
            ->values();

        $counteragents = OperationProduct::query()
            ->where('organization_id', $organizationId)
            ->whereNotNull('counteragent')
            ->where('counteragent', '!=', '')
            ->whereHas('operation', fn($q) => $q->whereNotIn('type_id', [6, 7]))
            ->when($productId, fn($q) => $q->where('product_id', $productId))
            ->distinct()
            ->orderBy('counteragent')
            ->pluck('counteragent')
            ->map(fn($value) => (string) $value)
            ->values();

        $years = Document::query()
            ->where('organization_id', $organizationId)
            ->whereNotNull('doc_date')
            ->whereHas('operation', function ($query) use ($productId) {
                $query->whereNotIn('type_id', [6, 7]);

                if ($productId) {
                    $query->whereHas('products', fn($q) => $q->where('samvol_inventory_products.id', $productId));
                }
            })
            ->selectRaw('YEAR(doc_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->map(fn($value) => (int) $value)
            ->values();

        return [
            'types' => $types,
            'counteragents' => $counteragents,
            'years' => $years,
        ];
    }

    private function mapHistoryItem(OperationProduct $item): array
    {
        $documents = $item->operation?->documents;
        $latestDoc = $documents ? $documents->sortByDesc('doc_date')->first() : null;

        return [
            'id' => (int) $item->id,
            'operation_id' => (int) $item->operation_id,
            'product_id' => (int) $item->product_id,
            'product_name' => (string) ($item->product_name ?? ''),
            'product_inv_number' => (string) ($item->product_inv_number ?? ''),
            'product_unit' => (string) ($item->product_unit ?? ''),
            'product_price' => (float) ($item->product_price ?? 0),
            'quantity' => (float) ($item->quantity ?? 0),
            'sum' => (float) ($item->sum ?? 0),
            'counteragent' => (string) ($item->counteragent ?? ''),
            'operation_type' => (string) ($item->operation_type ?? ''),
            'doc_date' => $latestDoc?->doc_date,
            'doc_name' => (string) ($latestDoc?->doc_name ?? ''),
            'doc_num' => (string) ($latestDoc?->doc_num ?? ''),
            'product' => $item->product ? [
                'id' => (int) $item->product->id,
                'name' => (string) $item->product->name,
                'unit' => (string) $item->product->unit,
                'inv_number' => (string) $item->product->inv_number,
                'price' => (float) $item->product->price,
                'mobile_summary' => (string) ($item->product->mobile_summary ?? ''),
                'external_id' => (string) ($item->product->external_id ?? ''),
                'updated_at' => optional($item->product->updated_at)->toIso8601String(),
            ] : null,
            'operation' => $item->operation ? [
                'id' => (int) $item->operation->id,
                'type' => $item->operation->type ? [
                    'id' => (int) $item->operation->type->id,
                    'name' => (string) $item->operation->type->name,
                ] : null,
                'status' => [
                    'is_draft' => (bool) $item->operation->is_draft,
                    'is_posted' => (bool) $item->operation->is_posted,
                ],
                'mobile_note' => (string) ($item->operation->mobile_note ?? ''),
                'external_id' => (string) ($item->operation->external_id ?? ''),
                'created_at' => optional($item->operation->created_at)->toIso8601String(),
                'updated_at' => optional($item->operation->updated_at)->toIso8601String(),
            ] : null,
            'updated_at' => optional($item->operation?->updated_at)->toIso8601String(),
        ];
    }
}
