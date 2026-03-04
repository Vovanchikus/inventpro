<?php namespace Samvol\Inventory\Controllers\Api;

use Backend\Classes\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

abstract class BaseApiController extends Controller
{
    protected function normalizeSort(string $requestedField, string $requestedDir, array $allowedFields, string $defaultField = 'id', string $defaultDir = 'desc'): array
    {
        $field = in_array($requestedField, $allowedFields, true) ? $requestedField : $defaultField;
        $dir = strtolower($requestedDir) === 'asc' ? 'asc' : 'desc';

        if (!in_array($defaultField, $allowedFields, true)) {
            $defaultField = $allowedFields[0] ?? $field;
        }

        return [
            'field' => $field ?: $defaultField,
            'dir' => $dir ?: $defaultDir,
        ];
    }

    protected function cached(string $namespace, array $payload, callable $resolver)
    {
        $ttl = max(5, (int) config('samvol.inventory::api.cache_ttl_seconds', 60));
        $version = (int) Cache::get($this->cacheVersionKey($namespace), 1);
        $key = 'api:v1:' . $namespace . ':v' . $version . ':' . md5(json_encode($payload, JSON_UNESCAPED_SLASHES));

        return Cache::remember($key, $ttl, $resolver);
    }

    protected function bumpCacheVersion(string $namespace): void
    {
        $versionKey = $this->cacheVersionKey($namespace);
        if (!Cache::has($versionKey)) {
            Cache::forever($versionKey, 1);
        }

        Cache::increment($versionKey);
    }

    private function cacheVersionKey(string $namespace): string
    {
        return 'api:v1:ver:' . $namespace;
    }

    protected function ok($data, int $code = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'error' => null,
        ], $code, [], JSON_UNESCAPED_SLASHES);
    }

    protected function fail(string $message, int $code = 400, array $meta = []): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'error' => $message,
            'meta' => $meta,
        ], $code, [], JSON_UNESCAPED_SLASHES);
    }

    protected function paginated(LengthAwarePaginator $paginator, callable $mapper): JsonResponse
    {
        return $this->ok([
            'items' => collect($paginator->items())->map($mapper)->values(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ]);
    }
}
