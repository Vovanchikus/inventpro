<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;
use Samvol\Inventory\Classes\Api\ApiResponse;

class ApiScopeMiddleware
{
    public function handle($request, Closure $next, string $requiredScope)
    {
        $payload = (array) $request->attributes->get('api_token_payload', []);
        $scopes = (array) ($payload['scopes'] ?? []);

        if (!in_array($requiredScope, $scopes, true) && !in_array('*', $scopes, true)) {
            return ApiResponse::error($request, 'AUTH_FORBIDDEN', 'Insufficient API scope', 403);
        }

        return $next($request);
    }
}
