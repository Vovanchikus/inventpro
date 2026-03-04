<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Samvol\Inventory\Models\Product;

class ProductController extends BaseApiController
{
    public function index(Request $request)
    {
        $perPage = max(1, min(100, (int) $request->input('per_page', 20)));
        $sort = $this->normalizeSort(
            (string) $request->input('sort_by', 'updated_at'),
            (string) $request->input('sort_dir', 'desc'),
            ['id', 'name', 'inv_number', 'price', 'updated_at'],
            'updated_at',
            'desc'
        );

        $query = Product::query()->apiList()->with(['category', 'images']);

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('inv_number', 'like', '%' . $search . '%');
            });
        }

        if ($categoryId = (int) $request->input('category_id', 0)) {
            $query->where('category_id', $categoryId);
        }

        if ($updatedAfter = $request->input('updated_after')) {
            $query->where('updated_at', '>=', $updatedAfter);
        }

        $query->orderBy($sort['field'], $sort['dir']);
        $queryData = [
            'page' => (int) $request->input('page', 1),
            'per_page' => $perPage,
            'q' => (string) $request->input('q', ''),
            'category_id' => (int) $request->input('category_id', 0),
            'updated_after' => (string) $request->input('updated_after', ''),
            'sort_by' => $sort['field'],
            'sort_dir' => $sort['dir'],
        ];

        $paginator = $this->cached('products.index', $queryData, fn() => $query->paginate($perPage));

        return $this->paginated($paginator, fn(Product $product) => $this->mapProduct($product));
    }

    public function show(int $id)
    {
        $item = $this->cached('products.show', ['id' => $id], fn() => Product::query()->apiList()->with(['category', 'images'])->find($id));
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
