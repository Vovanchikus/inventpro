<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Samvol\Inventory\Controllers\Api;
use Samvol\Inventory\Controllers\DocumentsDownloadController;
use Samvol\Inventory\Controllers\OperationDocumentController;
use Samvol\Inventory\Controllers\Api\AuthController;
use Samvol\Inventory\Controllers\Api\CategoryController;
use Samvol\Inventory\Controllers\Api\DocumentController;
use Samvol\Inventory\Controllers\Api\MediaController;
use Samvol\Inventory\Controllers\Api\OperationController;
use Samvol\Inventory\Controllers\Api\ProductController;
use Samvol\Inventory\Controllers\Api\RealtimeController;
use Samvol\Inventory\Controllers\Api\HistoryController;
use Samvol\Inventory\Classes\Api\ApiResponse;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::group(['prefix' => 'api'], function () {

    /*--------------------------
    | Products
    --------------------------*/
    Route::get('products', [Api::class, 'products']);
    Route::get('products/{id}', [Api::class, 'product']);

    /*--------------------------
    | Operations
    --------------------------*/
    Route::get('operations', [Api::class, 'operations']);
    Route::get('operations/{id}', [Api::class, 'operation']);

    /*--------------------------
    | Documents
    --------------------------*/
    Route::get('documents', [Api::class, 'documents']);
    Route::get('documents/{id}', [Api::class, 'document']);
    Route::get('documents/file/{id}', [Api::class, 'documentFile']);

    /*--------------------------
    | Categories
    --------------------------*/
    Route::get('categories', [Api::class, 'categories']);
    Route::get('categories/{id}', [Api::class, 'category']);

    /*--------------------------
    | Operation Types
    --------------------------*/
    Route::get('operation-types', [Api::class, 'operationTypes']);
    Route::get('operation-types/{id}', [Api::class, 'operationType']);

    /*--------------------------
    | Warehouse
    --------------------------*/
    Route::get('warehouse-products', [Api::class, 'warehouseProducts']);

    /*--------------------------
    | History
    --------------------------*/
    Route::get('history', [Api::class, 'history']);

    /*--------------------------
    | Counteragents
    --------------------------*/
    Route::get('counteragents', [Api::class, 'counteragents']);
    Route::get('counteragents/{name}', [Api::class, 'counteragent']);

    /*--------------------------
    | Images
    --------------------------*/
    Route::post('upload', [Api::class, 'upload']);
    Route::get('check-image', [Api::class, 'checkImage']);

    /*--------------------------
    | Operation document generation
    --------------------------*/
    Route::get('operation-doc-templates', [OperationDocumentController::class, 'templates'])->middleware('web');
    Route::post('operations/{id}/generate-doc', [OperationDocumentController::class, 'generate'])->middleware('web');
    Route::get('operations/doc-generation-status/{taskId}', [OperationDocumentController::class, 'status'])->middleware('web');

    /*--------------------------
    | Settings: Document template
    --------------------------*/
    Route::get('settings/document-template', [Api::class, 'documentTemplateSettings'])->middleware('web');
    Route::post('settings/document-template/field', [Api::class, 'saveDocumentTemplateField'])->middleware('web');
    Route::post('settings/document-template/person/add', [Api::class, 'addDocumentTemplatePerson'])->middleware('web');
    Route::post('settings/document-template/person/update', [Api::class, 'updateDocumentTemplatePerson'])->middleware('web');
    Route::post('settings/document-template/person/delete', [Api::class, 'deleteDocumentTemplatePerson'])->middleware('web');
    Route::post('settings/document-template/person/select', [Api::class, 'selectDocumentTemplatePerson'])->middleware('web');
});

/*--------------------------
| Mobile API V1
--------------------------*/
Route::group(['prefix' => 'api/v1', 'middleware' => ['api.request_context', 'api.exception_json']], function () {
    Route::post('auth/login', [AuthController::class, 'login']);
    Route::post('auth/register', [AuthController::class, 'register']);
    Route::post('auth/refresh', [AuthController::class, 'refresh']);
    Route::get('auth/health', [AuthController::class, 'health']);
    Route::get('public/organizations', [AuthController::class, 'organizations']);
    Route::get('public/organizations/check-name', [AuthController::class, 'checkOrganizationName']);
    Route::get('openapi.yaml', function () {
        $path = plugins_path('samvol/inventory/docs/openapi-v1.yaml');
        if (!is_file($path)) {
            return ApiResponse::error(request(), 'RESOURCE_UNAVAILABLE', 'OpenAPI specification is not available', 404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/yaml; charset=UTF-8',
        ]);
    });
    Route::get('docs', function () {
        $path = plugins_path('samvol/inventory/docs/swagger-ui.html');
        if (!is_file($path)) {
            return response('Swagger UI is not available', 404);
        }

        return response()->file($path, [
            'Content-Type' => 'text/html; charset=UTF-8',
        ]);
    });

    Route::group(['middleware' => ['api.token']], function () {

        Route::get('auth/me', [AuthController::class, 'me']);
        Route::post('auth/logout', [AuthController::class, 'logout']);
        Route::post('realtime/auth', [RealtimeController::class, 'auth']);
        Route::get('realtime/health', [RealtimeController::class, 'health']);

        Route::get('products', [ProductController::class, 'index'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);
        Route::get('products/{id}', [ProductController::class, 'show'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);

        Route::get('operations', [OperationController::class, 'index'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);
        Route::get('operations/{id}', [OperationController::class, 'show'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);
        Route::post('operations', [OperationController::class, 'store'])->middleware(['api.scope:inventory.write', 'api.org_role:responsible']);
        Route::patch('operations/{id}', [OperationController::class, 'update'])->middleware(['api.scope:inventory.write', 'api.org_role:responsible']);
        Route::delete('operations/{id}', [OperationController::class, 'destroy'])->middleware(['api.scope:inventory.write', 'api.org_role:admin']);

        Route::get('documents', [DocumentController::class, 'index'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);
        Route::get('documents/{id}', [DocumentController::class, 'show'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);
        Route::post('documents', [DocumentController::class, 'store'])->middleware(['api.scope:inventory.write', 'api.org_role:responsible']);
        Route::patch('documents/{id}', [DocumentController::class, 'update'])->middleware(['api.scope:inventory.write', 'api.org_role:responsible']);
        Route::delete('documents/{id}', [DocumentController::class, 'destroy'])->middleware(['api.scope:inventory.write', 'api.org_role:admin']);

        Route::get('categories', [CategoryController::class, 'index'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);
        Route::get('categories/{id}', [CategoryController::class, 'show'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);

        Route::get('history', [HistoryController::class, 'index'])->middleware(['api.scope:inventory.read', 'api.org_role:reader']);

        Route::post('media/images', [MediaController::class, 'uploadImage'])->middleware(['api.scope:inventory.write', 'api.org_role:responsible']);
        Route::post('media/documents', [MediaController::class, 'uploadDocument'])->middleware(['api.scope:inventory.write', 'api.org_role:responsible']);
    });

    // Keep /api/v1 strictly JSON, including unknown endpoints.
    Route::any('{any}', function (Request $request) {
        return ApiResponse::error($request, 'RESOURCE_UNAVAILABLE', 'Endpoint not found', 404);
    })->where('any', '.*');
});

/*--------------------------
| Documents download
--------------------------*/
Route::get('document-file/{id}', [
    DocumentsDownloadController::class,
    'download'
])->middleware('web');

Route::get('generated-documents/{token}', [
    OperationDocumentController::class,
    'file'
])->middleware('web');
