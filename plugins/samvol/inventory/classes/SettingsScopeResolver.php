<?php namespace Samvol\Inventory\Classes;

class SettingsScopeResolver
{
    public static function resolveScopeKey($user = null): string
    {
        if (!$user) {
            return 'global';
        }

        $organizationId = (int)($user->organization_id ?? 0);
        if ($organizationId > 0) {
            return 'org:' . $organizationId;
        }

        $groups = [];
        try {
            $groups = $user->groups ?? [];
        } catch (\Throwable $e) {
            $groups = [];
        }

        if ($groups && method_exists($groups, 'all')) {
            $groups = $groups->all();
        }

        if (is_array($groups) && !empty($groups)) {
            $orgGroup = null;
            foreach ($groups as $group) {
                $code = strtolower(trim((string)($group->code ?? '')));
                if ($code !== 'admin') {
                    $orgGroup = $group;
                    break;
                }
            }

            $group = $orgGroup ?: $groups[0];
            $groupId = (int)($group->id ?? 0);
            if ($groupId > 0) {
                return 'group:' . $groupId;
            }
        }

        $userId = (int)($user->id ?? 0);
        if ($userId > 0) {
            return 'user:' . $userId;
        }

        return 'global';
    }
}
