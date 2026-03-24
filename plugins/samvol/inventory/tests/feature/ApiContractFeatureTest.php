<?php

namespace Samvol\Inventory\Tests\Feature;

use Illuminate\Http\Request;
use Samvol\Inventory\Classes\Api\ApiResponse;
use Samvol\Inventory\Classes\Api\Middleware\ApiRequestContextMiddleware;
use Samvol\Inventory\Classes\Api\Middleware\ApiTokenMiddleware;
use Samvol\Inventory\Controllers\Api\AuthController;
use Samvol\Inventory\Tests\InventoryPluginTestCase;

class ApiContractFeatureTest extends InventoryPluginTestCase
{
    public function testAuthHealthReturnsUnifiedEnvelope(): void
    {
        $request = Request::create('/api/v1/auth/health', 'GET');
        $requestContext = app(ApiRequestContextMiddleware::class);

        $response = $requestContext->handle($request, function (Request $request) {
            app()->instance('request', $request);
            return app(AuthController::class)->health($request);
        });

        $this->assertSame(200, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertTrue($payload['success']);
        $this->assertNull($payload['error']);
        $this->assertArrayHasKey('data', $payload);
        $this->assertArrayHasKey('meta', $payload);
        $this->assertArrayHasKey('request_id', $payload['meta']);
        $this->assertArrayHasKey('status', $payload['data']);
        $this->assertArrayHasKey('auth_pipeline', $payload['data']);
        $this->assertArrayHasKey('server_time_utc', $payload['data']);
        $this->assertTrue($response->headers->has('X-Request-Id'));
    }

    public function testProtectedRouteWithoutBearerReturnsDeterministic401(): void
    {
        $request = Request::create('/api/v1/products', 'GET');
        $requestContext = app(ApiRequestContextMiddleware::class);
        $tokenMiddleware = app(ApiTokenMiddleware::class);

        $response = $requestContext->handle($request, function (Request $request) use ($tokenMiddleware) {
            return $tokenMiddleware->handle($request, function () use ($request) {
                return ApiResponse::success($request, ['items' => []]);
            });
        });

        $this->assertSame(401, $response->getStatusCode());
        $payload = $response->getData(true);
        $this->assertFalse($payload['success']);
        $this->assertSame('AUTH_BEARER_REQUIRED', $payload['error']['code']);
        $this->assertSame('Bearer token is required', $payload['error']['message']);
        $this->assertArrayHasKey('details', $payload['error']);
        $this->assertArrayHasKey('request_id', $payload['meta']);
        $this->assertTrue($response->headers->has('X-Request-Id'));
    }
}
