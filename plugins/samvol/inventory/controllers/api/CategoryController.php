<?php namespace Samvol\Inventory\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Samvol\Inventory\Models\Category;

class CategoryController extends BaseApiController
{
    public function index(Request $request)
    {
        $maxPerPage = max(1, (int) config('samvol.inventory::api.max_per_page', 100));
        $validator = Validator::make($request->query(), [
            'updated_since' => 'sometimes|date',
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:' . $maxPerPage,
            'sort_by' => 'sometimes|string|in:id,name,updated_at',
            'sort_dir' => 'sometimes|string|in:asc,desc',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation failed', 422, $validator->errors()->toArray());
        }

        $perPage = (int) $request->input('per_page', 50);
        $page = (int) $request->input('page', 1);
        $updatedSince = $this->resolveUpdatedSince($request);

        $sort = $this->normalizeSort(
            (string) $request->input('sort_by', 'name'),
            (string) $request->input('sort_dir', $updatedSince ? 'asc' : 'asc'),
            ['id', 'name', 'updated_at'],
            'name',
            'asc'
        );

        $query = Category::query()->withCount('children');

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

        if ($updatedSince) {
            $query->where('updated_at', '>', $updatedSince->toDateTimeString());
        }

        $query->orderBy($sort['field'], $sort['dir']);
        if ($sort['field'] !== 'id') {
            $query->orderBy('id', 'asc');
        }

        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->paginated($paginator, fn(Category $category) => $this->mapCategory($category));
    }

    public function show(Request $request, int $id)
    {
        $category = $this->cached('categories.show', [
            'id' => $id,
        ], function () use ($id, $request) {
            $query = Category::query()->with(['children', 'parent'])->where('id', $id);

            return $query->first();
        });
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
            'children_count' => (int) ($category->children_count ?? $category->children()->count()),
            'updated_at' => optional($category->updated_at)->toIso8601String(),
        ];
    }
}
