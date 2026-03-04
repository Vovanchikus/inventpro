<?php namespace Samvol\Inventory\Classes\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;

class JwtTokenService
{
    private const ALGO = 'HS256';

    public function issueAccessToken(int $userId, string $tokenId, array $scopes = []): array
    {
        $issuedAt = Carbon::now();
        $expiresAt = $issuedAt->copy()->addMinutes((int) config('samvol.inventory::api.access_ttl_minutes', 30));

        $payload = [
            'iss' => config('app.url', 'inventpro'),
            'sub' => $userId,
            'jti' => $tokenId,
            'scopes' => array_values(array_unique($scopes)),
            'iat' => $issuedAt->timestamp,
            'exp' => $expiresAt->timestamp,
        ];

        return [
            'token' => $this->encode($payload),
            'expires_at' => $expiresAt->toIso8601String(),
            'token_id' => $tokenId,
        ];
    }

    public function newTokenId(): string
    {
        return (string) Str::uuid();
    }

    public function decode(string $jwt): array
    {
        $parts = explode('.', $jwt);
        if (count($parts) !== 3) {
            throw new \RuntimeException('Invalid JWT format');
        }

        [$encodedHeader, $encodedPayload, $signature] = $parts;
        $signedData = $encodedHeader . '.' . $encodedPayload;

        $expectedSignature = $this->base64UrlEncode(hash_hmac('sha256', $signedData, $this->signingKey(), true));
        if (!hash_equals($expectedSignature, $signature)) {
            throw new \RuntimeException('JWT signature mismatch');
        }

        $payload = json_decode($this->base64UrlDecode($encodedPayload), true);
        if (!is_array($payload)) {
            throw new \RuntimeException('JWT payload is invalid');
        }

        $now = time();
        if (($payload['exp'] ?? 0) < $now) {
            throw new \RuntimeException('JWT expired');
        }

        return $payload;
    }

    private function encode(array $payload): string
    {
        $header = [
            'typ' => 'JWT',
            'alg' => self::ALGO,
        ];

        $encodedHeader = $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES));
        $encodedPayload = $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signedData = $encodedHeader . '.' . $encodedPayload;
        $signature = $this->base64UrlEncode(hash_hmac('sha256', $signedData, $this->signingKey(), true));

        return $signedData . '.' . $signature;
    }

    private function signingKey(): string
    {
        $appKey = (string) config('app.key', '');

        if (str_starts_with($appKey, 'base64:')) {
            $decoded = base64_decode(substr($appKey, 7), true);
            return $decoded === false ? '' : $decoded;
        }

        return $appKey;
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): string
    {
        $padding = 4 - (strlen($value) % 4);
        if ($padding < 4) {
            $value .= str_repeat('=', $padding);
        }

        return (string) base64_decode(strtr($value, '-_', '+/'));
    }
}
