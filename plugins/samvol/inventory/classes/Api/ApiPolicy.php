<?php namespace Samvol\Inventory\Classes\Api;

use Illuminate\Http\Request;
use Samvol\Inventory\Classes\PrimaryOrganizationResolver;

class ApiPolicy
{
    public function user(Request $request)
    {
        return $request->attributes->get('api_user');
    }

    public function organizationId(Request $request): ?int
    {
        $user = $this->user($request);
        $id = (int) ($user->organization_id ?? 0);

        if ($id > 0) {
            return $id;
        }

        return PrimaryOrganizationResolver::resolveId(true);
    }

    public function constrainByOrganization($query, Request $request, string $column = 'organization_id')
    {
        $organizationId = $this->organizationId($request);
        if (!$organizationId) {
            return $query;
        }

        return $query->where($column, $organizationId);
    }
}
