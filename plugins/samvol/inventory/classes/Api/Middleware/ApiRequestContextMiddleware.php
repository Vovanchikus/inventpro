<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ApiRequestContextMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $incomingRequestId = trim((string) $request->header('X-Request-Id', ''));
        $requestId = $incomingRequestId !== '' ? $incomingRequestId : (string) Str::uuid();

        $request->attributes->set('api_request_id', $requestId);
        $startedAt = microtime(true);

        $response = $next($request);
        $durationMs = (int) round((microtime(true) - $startedAt) * 1000);

        $user = $request->attributes->get('api_user');
        Log::info('api.request', [
            'request_id' => $requestId,
            'path' => '/' . ltrim((string) $request->path(), '/'),
            'method' => strtoupper((string) $request->method()),
            'status' => method_exists($response, 'getStatusCode') ? (int) $response->getStatusCode() : null,
            'duration_ms' => $durationMs,
            'user_id' => (int) ($user->id ?? 0) ?: null,
            'organization_id' => (int) ($user->organization_id ?? 0) ?: null,
        ]);

        if (method_exists($response, 'headers')) {
            $response->headers->set('X-Request-Id', $requestId);
        }

        return $response;
    }
}
