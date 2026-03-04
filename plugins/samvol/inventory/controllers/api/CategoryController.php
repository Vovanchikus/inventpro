<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Samvol\Inventory\Models\Category;

class CategoryController extends BaseApiController
{
    public function index(Request $request)
    {
        $perPage = max(1, min(100, (int) $request->input('per_page', 50)));
        $sort = $this->normalizeSort(
            (string) $request->input('sort_by', 'name'),
            (string) $request->input('sort_dir', 'asc'),
            ['id', 'name', 'updated_at'],
            'name',
            'asc'
        );

        $query = Category::query()->with(['children']);

        if ($parentId = $request->input('parent_id')) {
            if ($parentId === 'null') {
                $query->whereNull('parent_id');
            } else {
                $query->where('parent_id', (int) $parentId);
            }
        }

        if ($search = trim((string) $request->input('q', ''))) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $query->orderBy($sort['field'], $sort['dir']);
        $queryData = [
            'page' => (int) $request->input('page', 1),
            'per_page' => $perPage,
            'parent_id' => (string) $request->input('parent_id', ''),
            'q' => (string) $request->input('q', ''),
            'sort_by' => $sort['field'],
            'sort_dir' => $sort['dir'],
        ];

        $paginator = $this->cached('categories.index', $queryData, fn() => $query->paginate($perPage));

        return $this->paginated($paginator, fn(Category $category) => $this->mapCategory($category));
    }

    public function show(int $id)
    {
        $category = $this->cached('categories.show', ['id' => $id], fn() => Category::query()->with(['children', 'parent'])->find($id));
        if (!$category) {
            return $this->fail('Category not found', 404);
        }

        return $this->ok($this->mapCategory($category));
    }

    private function mapCategory(Category $category): array
    {
        return [
            'id' => (int) $category->id,
            'name' => (string) $category->name,
            'slug' => (string) $category->slug,
            'parent_id' => $category->parent_id ? (int) $category->parent_id : null,
            'children_count' => $category->relationLoaded('children') ? $category->children->count() : $category->children()->count(),
        ];
    }
}
