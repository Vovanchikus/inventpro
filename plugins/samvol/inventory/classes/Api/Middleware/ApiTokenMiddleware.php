<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Samvol\Inventory\Classes\Api\ApiResponse;
use Samvol\Inventory\Classes\Api\JwtTokenService;
use Samvol\Inventory\Classes\Api\RefreshTokenService;
use Winter\User\Models\User;

class ApiTokenMiddleware
{
    public function __construct(
        private JwtTokenService $jwtTokenService,
        private RefreshTokenService $refreshTokenService
    ) {
    }

    public function handle($request, Closure $next)
    {
        $bearerToken = $this->resolveBearerToken($request);
        if ($bearerToken === '') {
            return ApiResponse::error($request, 'AUTH_BEARER_REQUIRED', 'Bearer token is required', 401);
        }

        try {
            $payload = $this->jwtTokenService->decode($bearerToken);
        } catch (\Throwable $e) {
            $message = strtolower((string) $e->getMessage());
            $code = str_contains($message, 'expired') ? 'AUTH_TOKEN_EXPIRED' : 'AUTH_TOKEN_INVALID';

            return ApiResponse::error($request, $code, 'Invalid access token', 401);
        }

        $tokenId = (string) ($payload['jti'] ?? '');
        $userId = (int) ($payload['sub'] ?? 0);

        if ($tokenId === '' || $userId <= 0) {
            return ApiResponse::error($request, 'AUTH_TOKEN_INVALID', 'Invalid token payload', 401);
        }

        if (!$this->refreshTokenService->isActiveTokenId($tokenId)) {
            return ApiResponse::error($request, 'AUTH_TOKEN_INVALID', 'Token session has been revoked', 401);
        }

        $user = User::query()->find($userId);
        if (!$user) {
            return ApiResponse::error($request, 'AUTH_TOKEN_INVALID', 'User not found', 401);
        }

        $request->attributes->set('api_user', $user);
        $request->attributes->set('api_token_payload', $payload);
        $request->setUserResolver(fn() => $user);

        try {
            // Winter/Laravel facade is safer here than container alias resolution.
            if (class_exists('Auth')) {
                \Auth::setUser($user);
            }
        } catch (\Throwable $e) {
            Log::warning('api.auth_context_init_failed', [
                'request_id' => (string) $request->attributes->get('api_request_id', ''),
                'message' => $e->getMessage(),
            ]);

            return ApiResponse::error($request, 'AUTH_TOKEN_INVALID', 'Unable to initialize auth context', 401);
        }

        return $next($request);
    }

    protected function resolveBearerToken($request): string
    {
        $candidates = [];

        // Standard Laravel source.
        $candidates[] = (string) $request->bearerToken();

        // Header bag fallback (can work even when bearerToken() fails).
        $candidates[] = (string) $request->header('Authorization', '');

        // Common server variables used by Apache/Nginx/fastcgi/proxies.
        $candidates[] = (string) $request->server('HTTP_AUTHORIZATION', '');
        $candidates[] = (string) $request->server('REDIRECT_HTTP_AUTHORIZATION', '');
        $candidates[] = (string) $request->server('X-HTTP_AUTHORIZATION', '');

        // Some proxies forward custom auth headers.
        $candidates[] = (string) $request->header('X-Authorization', '');

        try {
            if (function_exists('getallheaders')) {
                $headers = (array) getallheaders();
                foreach ($headers as $name => $value) {
                    if (strcasecmp((string) $name, 'Authorization') === 0) {
                        $candidates[] = (string) $value;
                    }
                }
            }
        } catch (\Throwable $e) {
        }

        // Last-resort compatibility for clients/proxies that move token to params.
        $candidates[] = 'Bearer ' . (string) $request->query('access_token', '');
        $candidates[] = 'Bearer ' . (string) $request->input('access_token', '');

        foreach ($candidates as $candidate) {
            $token = $this->parseBearerToken($candidate);
            if ($token !== '') {
                return $token;
            }
        }

        return '';
    }

    protected function parseBearerToken(string $raw): string
    {
        $raw = trim($raw);
        if ($raw === '') {
            return '';
        }

        if (preg_match('/^Bearer\s+(.+)$/i', $raw, $matches)) {
            return trim((string) ($matches[1] ?? ''));
        }

        return '';
    }
}
