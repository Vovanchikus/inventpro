<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;

class ApiScopeMiddleware
{
    public function handle($request, Closure $next, string $requiredScope)
    {
        $payload = (array) $request->attributes->get('api_token_payload', []);
        $scopes = (array) ($payload['scopes'] ?? []);

        if (!in_array($requiredScope, $scopes, true) && !in_array('*', $scopes, true)) {
            return response()->json([
                'success' => false,
                'error' => 'Insufficient API scope',
            ], 403);
        }

        return $next($request);
    }
}
