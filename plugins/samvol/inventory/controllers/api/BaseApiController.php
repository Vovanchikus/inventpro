<?php namespace Samvol\Inventory\Controllers\Api;

use Backend\Classes\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Samvol\Inventory\Classes\Api\ApiResponse;

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

    protected function ok($data, int $code = 200, array $meta = []): JsonResponse
    {
        return ApiResponse::success(request(), $data, $code, $meta);
    }

    protected function fail(string $message, int $code = 400, array $details = [], ?string $errorCode = null): JsonResponse
    {
        return ApiResponse::error(
            request(),
            $errorCode ?: $this->mapErrorCode($message, $code),
            $message,
            $code,
            $details
        );
    }

    protected function apiError(string $code, string $message, int $status = 400, array $details = [], array $meta = []): JsonResponse
    {
        return ApiResponse::error(request(), $code, $message, $status, $details, $meta);
    }

    protected function paginated(LengthAwarePaginator $paginator, callable $mapper, array $meta = []): JsonResponse
    {
        return $this->ok([
            'items' => collect($paginator->items())->map($mapper)->values(),
        ], 200, array_merge([
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ], $meta));
    }

    protected function resolveUpdatedSince(Request $request): ?Carbon
    {
        $raw = trim((string) ($request->input('updated_since', $request->input('updated_after', ''))));
        if ($raw === '') {
            return null;
        }

        try {
            return Carbon::parse($raw)->utc();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function withIdempotency(Request $request, string $scope, callable $callback, int $ttlSeconds = 86400): JsonResponse
    {
        $ttlSeconds = max(60, (int) config('samvol.inventory::api.idempotency_ttl_seconds', $ttlSeconds));

        $clientRequestId = trim((string) $request->header('X-Client-Request-Id', ''));
        if ($clientRequestId === '') {
            /** @var JsonResponse $response */
            $response = $callback();
            return $response;
        }

        if (mb_strlen($clientRequestId) > 128) {
            return $this->apiError('VALIDATION_ERROR', 'X-Client-Request-Id is too long', 422, [
                'X-Client-Request-Id' => ['Must be 128 characters or fewer.'],
            ]);
        }

        $user = $request->attributes->get('api_user');
        $organizationId = (int) ($user->organization_id ?? 0);
        $userId = (int) ($user->id ?? 0);
        $cacheKey = 'api:idempotency:' . md5($scope . '|' . $organizationId . '|' . $userId . '|' . $clientRequestId);

        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            $body = (array) ($cached['body'] ?? []);
            $status = (int) ($cached['status'] ?? 200);
            $body['meta'] = (array) ($body['meta'] ?? []);
            $body['meta']['idempotency'] = [
                'request_id' => $clientRequestId,
                'replayed' => true,
            ];

            return ApiResponse::json($request, $body, $status);
        }

        /** @var JsonResponse $response */
        $response = $callback();
        $body = (array) $response->getData(true);
        $body['meta'] = (array) ($body['meta'] ?? []);
        $body['meta']['idempotency'] = [
            'request_id' => $clientRequestId,
            'replayed' => false,
        ];

        Cache::put($cacheKey, [
            'status' => $response->getStatusCode(),
            'body' => $body,
        ], $ttlSeconds);

        return ApiResponse::json($request, $body, $response->getStatusCode());
    }

    private function mapErrorCode(string $message, int $status): string
    {
        $needle = strtolower($message);

        if ($status === 422) {
            return 'VALIDATION_ERROR';
        }

        if (str_contains($needle, 'bearer token')) {
            return 'AUTH_BEARER_REQUIRED';
        }

        if (str_contains($needle, 'expired')) {
            return 'AUTH_TOKEN_EXPIRED';
        }

        if (str_contains($needle, 'invalid') || str_contains($needle, 'unauthorized')) {
            return $status === 401 ? 'AUTH_TOKEN_INVALID' : 'RESOURCE_UNAVAILABLE';
        }

        if ($status === 403) {
            return 'AUTH_FORBIDDEN';
        }

        if ($status === 404) {
            return 'RESOURCE_UNAVAILABLE';
        }

        if ($status >= 500) {
            return 'SERVER_ERROR';
        }

        return 'RESOURCE_UNAVAILABLE';
    }
}
