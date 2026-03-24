<?php

namespace Samvol\Inventory\Tests\Unit\Middleware;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Samvol\Inventory\Classes\Api\JwtTokenService;
use Samvol\Inventory\Classes\Api\Middleware\ApiTokenMiddleware;
use Samvol\Inventory\Classes\Api\RefreshTokenService;
use Samvol\Inventory\Tests\InventoryPluginTestCase;
use Winter\User\Models\User;

class ApiTokenMiddlewareTest extends InventoryPluginTestCase
{
    public function testMalformedBearerReturns401Json(): void
    {
        $jwtService = $this->createMock(JwtTokenService::class);
        $jwtService->method('decode')->willThrowException(new \RuntimeException('Invalid JWT format'));

        $refreshService = $this->createMock(RefreshTokenService::class);
        $middleware = new ApiTokenMiddleware($jwtService, $refreshService);

        $request = Request::create('/api/v1/products', 'GET', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer malformed-token',
        ]);
        $request->attributes->set('api_request_id', 'test-request-id');

        $response = $middleware->handle($request, fn() => response('ok'));

        $this->assertSame(401, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertSame('AUTH_TOKEN_INVALID', $payload['error']['code']);
    }

    public function testExpiredBearerReturnsSpecificCode(): void
    {
        $jwtService = $this->createMock(JwtTokenService::class);
        $jwtService->method('decode')->willThrowException(new \RuntimeException('JWT expired'));

        $refreshService = $this->createMock(RefreshTokenService::class);
        $middleware = new ApiTokenMiddleware($jwtService, $refreshService);

        $request = Request::create('/api/v1/products', 'GET', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer expired-token',
        ]);
        $request->attributes->set('api_request_id', 'test-request-id');

        $response = $middleware->handle($request, fn() => response('ok'));

        $this->assertSame(401, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertSame('AUTH_TOKEN_EXPIRED', $payload['error']['code']);
    }

    public function testValidBearerInjectsApiUserIntoRequest(): void
    {
        $user = new User();
        $user->name = 'Api Test User';
        $user->email = 'api.test@example.com';
        $user->password = 'password123';
        $user->password_confirmation = 'password123';

        if (Schema::hasColumn('users', 'login')) {
            $user->login = 'api.test@example.com';
        }

        if (Schema::hasColumn('users', 'organization_id')) {
            $user->organization_id = 1;
        }

        if (Schema::hasColumn('users', 'organization_role')) {
            $user->organization_role = 'admin';
        }

        if (Schema::hasColumn('users', 'organization_status')) {
            $user->organization_status = 'approved';
        }

        $user->save();

        $jwtService = $this->createMock(JwtTokenService::class);
        $jwtService->method('decode')->willReturn([
            'sub' => (int) $user->id,
            'jti' => 'token-id-1',
            'scopes' => ['inventory.read'],
        ]);

        $refreshService = $this->createMock(RefreshTokenService::class);
        $refreshService->method('isActiveTokenId')->willReturn(true);

        $middleware = new ApiTokenMiddleware($jwtService, $refreshService);
        $request = Request::create('/api/v1/products', 'GET', [], [], [], [
            'HTTP_AUTHORIZATION' => 'Bearer valid-token',
        ]);
        $request->attributes->set('api_request_id', 'test-request-id');

        $response = $middleware->handle($request, fn() => response()->json(['ok' => true]));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertNotNull($request->attributes->get('api_user'));
        $this->assertSame((int) $user->id, (int) $request->attributes->get('api_user')->id);
    }
}
