<?php namespace Samvol\Inventory\Classes\Concerns;

use Illuminate\Support\Facades\Auth;
use Samvol\Inventory\Classes\OrganizationAccess;

trait HasOrganizationScope
{
    protected static function bootHasOrganizationScope(): void
    {
        static::addGlobalScope('organization_scope', function ($query) {
            if (app()->runningInConsole()) {
                return;
            }

            [$organizationId, $bypass] = static::resolveOrganizationContext();
            if ($bypass) {
                return;
            }

            if ($organizationId > 0) {
                $query->where(static::qualifyOrganizationColumn(), $organizationId);
                return;
            }

            $query->whereRaw('1 = 0');
        });

        static::creating(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }

            if (!empty($model->organization_id)) {
                return;
            }

            [$organizationId, $bypass] = static::resolveOrganizationContext();
            if ($bypass || $organizationId <= 0) {
                return;
            }

            $model->organization_id = $organizationId;
        });
    }

    protected static function qualifyOrganizationColumn(): string
    {
        $instance = new static();
        return $instance->getTable() . '.organization_id';
    }

    protected static function resolveOrganizationContext(): array
    {
        $user = null;

        try {
            $user = Auth::getUser();
        } catch (\Throwable $e) {
        }

        if (!$user) {
            try {
                $user = Auth::user();
            } catch (\Throwable $e) {
            }
        }

        if (!$user) {
            try {
                if (class_exists(\Backend\Facades\BackendAuth::class)) {
                    $user = \Backend\Facades\BackendAuth::getUser();
                }
            } catch (\Throwable $e) {
            }
        }

        if (!$user) {
            try {
                if (app()->bound('request')) {
                    $request = app('request');
                    $apiUser = $request?->attributes?->get('api_user');
                    if ($apiUser) {
                        $user = $apiUser;
                    }
                }
            } catch (\Throwable $e) {
            }
        }

        if (!$user) {
            return [0, false];
        }

        if (OrganizationAccess::isProjectAdmin($user)) {
            return [0, true];
        }

        return [(int)($user->organization_id ?? 0), false];
    }
}
