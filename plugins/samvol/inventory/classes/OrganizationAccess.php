<?php namespace Samvol\Inventory\Classes;

class OrganizationAccess
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    public const ROLE_READER = 'reader';
    public const ROLE_RESPONSIBLE = 'responsible';
    public const ROLE_INVENTORIZER = 'inventorizer';
    public const ROLE_ADMIN = 'admin';

    public static function defaultRole(): string
    {
        return self::ROLE_READER;
    }

    public static function defaultStatus(): string
    {
        return self::STATUS_PENDING;
    }

    public static function roleLabels(): array
    {
        return [
            self::ROLE_READER => 'Читач',
            self::ROLE_RESPONSIBLE => 'Відповідальний',
            self::ROLE_INVENTORIZER => 'Інвентаризатор',
            self::ROLE_ADMIN => 'Адміністратор',
        ];
    }

    public static function roleRank(string $role): int
    {
        $map = [
            self::ROLE_READER => 1,
            self::ROLE_RESPONSIBLE => 2,
            self::ROLE_INVENTORIZER => 3,
            self::ROLE_ADMIN => 4,
        ];

        return $map[$role] ?? 0;
    }

    public static function hasAtLeastRole($user, string $role): bool
    {
        if (!$user) {
            return false;
        }

        $status = strtolower(trim((string)($user->organization_status ?? '')));
        if ($status !== self::STATUS_APPROVED) {
            return false;
        }

        $currentRole = strtolower(trim((string)($user->organization_role ?? '')));
        return self::roleRank($currentRole) >= self::roleRank($role);
    }

    public static function isOrganizationAdmin($user): bool
    {
        return self::hasAtLeastRole($user, self::ROLE_ADMIN);
    }

    public static function isProjectAdmin($user): bool
    {
        if (!$user) {
            return false;
        }

        try {
            if (method_exists($user, 'isSuperUser') && $user->isSuperUser()) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        if ((bool) ($user->is_superuser ?? false) || (bool) ($user->is_super_user ?? false)) {
            return true;
        }

        try {
            if (method_exists($user, 'hasAccess') && ($user->hasAccess('backend.*') || $user->hasAccess('samvol.inventory.*'))) {
                return true;
            }
        } catch (\Throwable $e) {
        }

        return false;
    }
}
