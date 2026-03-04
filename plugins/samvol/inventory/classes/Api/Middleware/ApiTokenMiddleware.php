<?php namespace Samvol\Inventory\Classes\Api\Middleware;

use Closure;
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
        $bearerToken = (string) $request->bearerToken();
        if ($bearerToken === '') {
            return response()->json(['success' => false, 'error' => 'Bearer token is required'], 401);
        }

        try {
            $payload = $this->jwtTokenService->decode($bearerToken);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'error' => 'Invalid access token'], 401);
        }

        $tokenId = (string) ($payload['jti'] ?? '');
        $userId = (int) ($payload['sub'] ?? 0);

        if ($tokenId === '' || $userId <= 0) {
            return response()->json(['success' => false, 'error' => 'Invalid token payload'], 401);
        }

        if (!$this->refreshTokenService->isActiveTokenId($tokenId)) {
            return response()->json(['success' => false, 'error' => 'Token session has been revoked'], 401);
        }

        $user = User::query()->find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'User not found'], 401);
        }

        $request->attributes->set('api_user', $user);
        $request->attributes->set('api_token_payload', $payload);
        $request->setUserResolver(fn() => $user);
        app('auth')->setUser($user);

        return $next($request);
    }
}
