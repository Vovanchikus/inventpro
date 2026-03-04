<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;
use Samvol\Inventory\Classes\OrganizationAccess;

class ApiOrganizationRoleMiddleware
{
    public function handle($request, Closure $next, string $requiredRole = OrganizationAccess::ROLE_READER)
    {
        $user = $request->attributes->get('api_user');
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        }

        if (!OrganizationAccess::hasAtLeastRole($user, $requiredRole) && !($user->is_superuser ?? false)) {
            return response()->json(['success' => false, 'error' => 'Insufficient organization role'], 403);
        }

        return $next($request);
    }
}
