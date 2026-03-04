<?php namespace Samvol\Inventory\Classes\Api;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Samvol\Inventory\Models\ApiRefreshToken;

class RefreshTokenService
{
    public function issue(int $userId, string $tokenId, ?Request $request = null): array
    {
        $plainToken = Str::random(80);
        $ttlDays = (int) config('samvol.inventory::api.refresh_ttl_days', 30);
        $expiresAt = Carbon::now()->addDays($ttlDays);

        ApiRefreshToken::query()->create([
            'user_id' => $userId,
            'token_id' => $tokenId,
            'token_hash' => hash('sha256', $plainToken),
            'expires_at' => $expiresAt,
            'user_agent' => (string) ($request?->userAgent() ?? ''),
            'ip_address' => (string) ($request?->ip() ?? ''),
        ]);

        return [
            'token' => $plainToken,
            'expires_at' => $expiresAt->toIso8601String(),
        ];
    }

    public function rotate(string $plainToken, string $newTokenId, ?Request $request = null): ?ApiRefreshToken
    {
        $current = ApiRefreshToken::query()
            ->active()
            ->where('token_hash', hash('sha256', $plainToken))
            ->first();

        if (!$current) {
            return null;
        }

        $current->revoked_at = now();
        $current->replaced_by_token_id = $newTokenId;
        $current->save();

        return $current;
    }

    public function revokeByPlainToken(string $plainToken): void
    {
        ApiRefreshToken::query()
            ->where('token_hash', hash('sha256', $plainToken))
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }

    public function revokeByTokenId(string $tokenId): void
    {
        ApiRefreshToken::query()
            ->where('token_id', $tokenId)
            ->whereNull('revoked_at')
            ->update(['revoked_at' => now()]);
    }

    public function isActiveTokenId(string $tokenId): bool
    {
        return ApiRefreshToken::query()
            ->active()
            ->where('token_id', $tokenId)
            ->exists();
    }
}
