<?php

namespace Samvol\Inventory\Tests\Feature;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Samvol\Inventory\Classes\Api\Middleware\ApiExceptionMiddleware;
use Samvol\Inventory\Classes\Api\Middleware\ApiRequestContextMiddleware;
use Samvol\Inventory\Classes\Api\Middleware\ApiScopeMiddleware;
use Samvol\Inventory\Classes\Api\Middleware\ApiTokenMiddleware;
use Samvol\Inventory\Classes\Api\JwtTokenService;
use Samvol\Inventory\Classes\Api\RefreshTokenService;
use Samvol\Inventory\Classes\PrimaryOrganizationResolver;
use Samvol\Inventory\Controllers\Api\CategoryController;
use Samvol\Inventory\Controllers\Api\DocumentController;
use Samvol\Inventory\Controllers\Api\HistoryController;
use Samvol\Inventory\Controllers\Api\OperationController;
use Samvol\Inventory\Controllers\Api\ProductController;
use Samvol\Inventory\Models\Category;
use Samvol\Inventory\Models\Document;
use Samvol\Inventory\Models\OperationType;
use Samvol\Inventory\Models\Organization;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Tests\InventoryPluginTestCase;
use Winter\User\Models\User;

class ApiV1ListStabilityFeatureTest extends InventoryPluginTestCase
{
    public function testListEndpointsReturnEnvelopeWithPaginationMeta(): void
    {
        $user = $this->seedInventoryFixture();

        $this->assertListResponse(ProductController::class, '/api/v1/products');
        $this->assertListResponse(CategoryController::class, '/api/v1/categories');
        $this->assertListResponse(OperationController::class, '/api/v1/operations');
        $this->assertListResponse(DocumentController::class, '/api/v1/documents');
        $this->assertListResponse(HistoryController::class, '/api/v1/history');
    }

    public function testHistoryReturnsProductFieldsForMobileScreen(): void
    {
        $user = $this->seedInventoryFixture();

        $request = Request::create('/api/v1/history', 'GET', [
            'per_page' => 20,
            'sort_by' => 'doc_date',
            'sort_dir' => 'desc',
        ]);
        $request->attributes->set('api_user', $user);

        $response = $this->runApiRequest($request, fn(Request $request) => app(HistoryController::class)->index($request));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertArrayHasKey('filters', $payload['meta']);
        $this->assertArrayHasKey('types', $payload['meta']['filters']);
        $this->assertArrayHasKey('counteragents', $payload['meta']['filters']);
        $this->assertArrayHasKey('years', $payload['meta']['filters']);

        if (!empty($payload['data']['items'])) {
            $item = $payload['data']['items'][0];
            $this->assertArrayHasKey('product_name', $item);
            $this->assertArrayHasKey('product_inv_number', $item);
            $this->assertArrayHasKey('product_unit', $item);
            $this->assertArrayHasKey('product_price', $item);
            $this->assertArrayHasKey('quantity', $item);
            $this->assertArrayHasKey('sum', $item);
            $this->assertArrayHasKey('counteragent', $item);
            $this->assertArrayHasKey('operation_type', $item);
            $this->assertArrayHasKey('doc_name', $item);
            $this->assertArrayHasKey('doc_num', $item);
            $this->assertArrayHasKey('doc_date', $item);
        }
    }

    public function testProductsUpdatedSinceIncrementalCursorWorks(): void
    {
        $user = $this->seedInventoryFixture();

        $request = Request::create('/api/v1/products', 'GET', [
            'updated_since' => '2026-01-02T00:00:00Z',
            'per_page' => 20,
            'sort_by' => 'updated_at',
            'sort_dir' => 'asc',
        ]);
        $request->attributes->set('api_user', $user);

        $response = $this->runApiRequest($request, fn(Request $request) => app(ProductController::class)->index($request));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertArrayHasKey('pagination', $payload['meta']);
        $this->assertArrayHasKey('request_id', $payload['meta']);
    }

    public function testProductsFullBackfillIsStableAndReturnsItems(): void
    {
        $user = $this->seedInventoryFixture();

        $request = Request::create('/api/v1/products', 'GET', [
            'updated_since' => '1970-01-01T00:00:00Z',
            'per_page' => 20,
            'sort_by' => 'updated_at',
            'sort_dir' => 'asc',
        ]);
        $request->attributes->set('api_user', $user);

        $response = $this->runApiRequest($request, fn(Request $request) => app(ProductController::class)->index($request));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertArrayHasKey('pagination', $payload['meta']);
        $this->assertArrayHasKey('request_id', $payload['meta']);
    }

    public function testInvalidListParamsReturn422Envelope(): void
    {
        $user = $this->seedInventoryFixture();

        $request = Request::create('/api/v1/products', 'GET', [
            'page' => 0,
            'per_page' => -1,
            'sort_dir' => 'sideways',
        ]);
        $request->attributes->set('api_user', $user);

        $response = $this->runApiRequest($request, fn(Request $request) => app(ProductController::class)->index($request));
        $payload = $response->getData(true);

        $this->assertSame(422, $response->getStatusCode());
        $this->assertFalse($payload['success']);
        $this->assertSame('VALIDATION_ERROR', $payload['error']['code']);
        $this->assertArrayHasKey('request_id', $payload['meta']);
    }

    public function testEnvelopeFor401403And500HasNoHtmlBody(): void
    {
        $request401 = Request::create('/api/v1/products', 'GET');
        $request401->attributes->set('api_request_id', 'rid-401');

        $jwtService = $this->createMock(JwtTokenService::class);
        $refreshService = $this->createMock(RefreshTokenService::class);
        $tokenMiddleware = new ApiTokenMiddleware($jwtService, $refreshService);

        $response401 = $tokenMiddleware->handle($request401, fn() => response('ok'));
        $payload401 = $response401->getData(true);

        $this->assertSame(401, $response401->getStatusCode());
        $this->assertFalse($payload401['success']);
        $this->assertSame('AUTH_BEARER_REQUIRED', $payload401['error']['code']);
        $this->assertStringNotContainsString('<html', strtolower((string) $response401->getContent()));

        $request403 = Request::create('/api/v1/products', 'GET');
        $request403->attributes->set('api_request_id', 'rid-403');
        $request403->attributes->set('api_token_payload', ['scopes' => ['inventory.read']]);
        $scopeMiddleware = new ApiScopeMiddleware();

        $response403 = $scopeMiddleware->handle($request403, fn() => response('ok'), 'inventory.write');
        $payload403 = $response403->getData(true);

        $this->assertSame(403, $response403->getStatusCode());
        $this->assertFalse($payload403['success']);
        $this->assertSame('AUTH_FORBIDDEN', $payload403['error']['code']);
        $this->assertStringNotContainsString('<html', strtolower((string) $response403->getContent()));

        $request500 = Request::create('/api/v1/products', 'GET');
        $request500->attributes->set('api_request_id', 'rid-500');
        $exceptionMiddleware = app(ApiExceptionMiddleware::class);

        $response500 = $exceptionMiddleware->handle($request500, function () {
            throw new \RuntimeException('boom');
        });
        $payload500 = $response500->getData(true);

        $this->assertSame(500, $response500->getStatusCode());
        $this->assertFalse($payload500['success']);
        $this->assertSame('SERVER_ERROR', $payload500['error']['code']);
        $this->assertStringNotContainsString('<html', strtolower((string) $response500->getContent()));
    }

    private function assertListResponse(string $controllerClass, string $path): void
    {
        $user = $this->seedInventoryFixture();

        $request = Request::create($path, 'GET', [
            'page' => 1,
            'per_page' => 20,
            'sort_by' => 'updated_at',
            'sort_dir' => 'asc',
            'updated_since' => '1970-01-01T00:00:00Z',
        ]);
        $request->attributes->set('api_user', $user);

        $response = $this->runApiRequest($request, fn(Request $request) => app($controllerClass)->index($request));
        $payload = $response->getData(true);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($payload['success']);
        $this->assertArrayHasKey('items', $payload['data']);
        $this->assertArrayHasKey('meta', $payload);
        $this->assertArrayHasKey('request_id', $payload['meta']);
        $this->assertArrayHasKey('pagination', $payload['meta']);
        $this->assertArrayHasKey('current_page', $payload['meta']['pagination']);
        $this->assertArrayHasKey('per_page', $payload['meta']['pagination']);
    }

    private function runApiRequest(Request $request, callable $action)
    {
        $requestContext = app(ApiRequestContextMiddleware::class);
        $exceptionMiddleware = app(ApiExceptionMiddleware::class);

        return $requestContext->handle($request, function (Request $request) use ($exceptionMiddleware, $action) {
            return $exceptionMiddleware->handle($request, function (Request $request) use ($action) {
                app()->instance('request', $request);
                return $action($request);
            });
        });
    }

    private function seedInventoryFixture(): User
    {
        static $seededUser = null;

        if ($seededUser instanceof User) {
            return $seededUser;
        }

        $organizationId = (int) (PrimaryOrganizationResolver::resolveId(true) ?? 0);
        $organization = $organizationId > 0
            ? Organization::query()->find($organizationId)
            : null;

        if (!$organization) {
            $organization = Organization::query()->create([
                'name' => 'QA Org',
                'code' => 'qa-org',
                'is_active' => true,
            ]);
            $organizationId = (int) $organization->id;
        }

        if (is_array(config('winter.user::minPasswordLength'))) {
            config(['winter.user::minPasswordLength' => 8]);
        }

        $userEmail = 'fixture.user.' . uniqid('', true) . '@example.com';

        $user = new User();
        $user->name = 'API Fixture User';
        $user->email = $userEmail;
        $user->password = 'password123';
        $user->password_confirmation = 'password123';

        if (Schema::hasColumn('users', 'login')) {
            $user->login = $userEmail;
        }

        if (Schema::hasColumn('users', 'organization_id')) {
            $user->organization_id = $organizationId;
        }

        if (Schema::hasColumn('users', 'organization_role')) {
            $user->organization_role = 'admin';
        }

        if (Schema::hasColumn('users', 'organization_status')) {
            $user->organization_status = 'approved';
        }

        $user->save();

        $category = Category::query()->create([
            'name' => 'Fixture Category',
            'organization_id' => $organizationId,
        ]);

        $oldProduct = Product::query()->create([
            'organization_id' => $organizationId,
            'category_id' => (int) $category->id,
            'name' => 'Product Old',
            'unit' => 'pcs',
            'inv_number' => 'INV-OLD',
            'price' => 10,
            'mobile_summary' => 'Old',
            'external_id' => 'ext-old',
        ]);

        $newProduct = Product::query()->create([
            'organization_id' => $organizationId,
            'category_id' => (int) $category->id,
            'name' => 'Product New',
            'unit' => 'pcs',
            'inv_number' => 'INV-NEW',
            'price' => 20,
            'mobile_summary' => 'New',
            'external_id' => 'ext-new',
        ]);

        DB::table('samvol_inventory_products')->where('id', (int) $oldProduct->id)->update([
            'updated_at' => Carbon::parse('2026-01-01T00:00:00Z')->toDateTimeString(),
        ]);
        DB::table('samvol_inventory_products')->where('id', (int) $newProduct->id)->update([
            'updated_at' => Carbon::parse('2026-01-03T00:00:00Z')->toDateTimeString(),
        ]);

        $operationType = OperationType::query()->create(['name' => 'Приход']);

        $operationId = DB::table('samvol_inventory_operations')->insertGetId([
            'type_id' => (int) $operationType->id,
            'doc_name' => 'Fixture Op Doc',
            'doc_num' => 'OP-1',
            'doc_date' => '2026-01-03',
            'is_draft' => true,
            'is_posted' => false,
            'organization_id' => $organizationId,
            'mobile_note' => 'fixture op',
            'external_id' => 'op-1',
            'slug' => 'op-1',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Document::query()->create([
            'operation_id' => (int) $operationId,
            'organization_id' => $organizationId,
            'doc_name' => 'Fixture Document',
            'doc_num' => 'DOC-1',
            'doc_date' => '2026-01-03',
            'doc_purpose' => 'testing',
            'mime_type' => 'application/pdf',
            'file_size' => 123,
        ]);

        DB::table('samvol_inventory_operation_products')->insert([
            'operation_id' => (int) $operationId,
            'product_id' => (int) $newProduct->id,
            'organization_id' => $organizationId,
            'quantity' => 5,
            'sum' => 100,
            'counteragent' => 'Test Counteragent',
        ]);

        $seededUser = $user;

        return $seededUser;
    }
}
