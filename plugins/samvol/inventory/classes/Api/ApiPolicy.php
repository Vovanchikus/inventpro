<?php namespace Samvol\Inventory\Classes\Api;

use Illuminate\Http\Request;

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

        return $id > 0 ? $id : null;
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
