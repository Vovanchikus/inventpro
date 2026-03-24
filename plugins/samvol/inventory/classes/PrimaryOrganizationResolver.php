<?php namespace Samvol\Inventory\Classes;

use Samvol\Inventory\Models\Organization;

class PrimaryOrganizationResolver
{
    private static ?int $cachedId = null;

    public static function resolveId(bool $createIfMissing = true): ?int
    {
        if (self::$cachedId !== null) {
            return self::$cachedId > 0 ? self::$cachedId : null;
        }

        $organization = Organization::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if (!$organization) {
            $organization = Organization::query()->orderBy('id')->first();
        }

        if (!$organization && $createIfMissing) {
            $organization = self::createDefaultOrganization();
        }

        self::$cachedId = (int) ($organization->id ?? 0);

        return self::$cachedId > 0 ? self::$cachedId : null;
    }

    private static function createDefaultOrganization(): ?Organization
    {
        $baseCode = 'main';
        $code = $baseCode;

        for ($i = 0; $i < 50; $i++) {
            $exists = Organization::query()
                ->whereRaw('LOWER(code) = ?', [strtolower($code)])
                ->exists();

            if (!$exists) {
                break;
            }

            $code = $baseCode . '-' . ($i + 2);
        }

        try {
            return Organization::query()->create([
                'name' => 'Main organization',
                'code' => $code,
                'is_active' => true,
            ]);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
