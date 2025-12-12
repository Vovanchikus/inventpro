<?php

use Illuminate\Support\Facades\Route;
use Samvol\Inventory\Controllers\Api;

// API prefix
Route::group(['prefix' => 'api'], function () {

    // Products
    Route::get('/products', [Api::class, 'products']);
    Route::get('/products/{id}', [Api::class, 'product']);

    // Operations
    Route::get('/operations', [Api::class, 'operations']);
    Route::get('/operations/{id}', [Api::class, 'operation']);

    // Documents
    Route::get('/documents', [Api::class, 'documents']);
    Route::get('/documents/{id}', [Api::class, 'document']);
    Route::get('/documents/file/{id}', [Api::class, 'documentFile']);

    // Categories
    Route::get('/categories', [Api::class, 'categories']);
    Route::get('/categories/{id}', [Api::class, 'category']);

    // Operation Types
    Route::get('/operation-types', [Api::class, 'operationTypes']);
    Route::get('/operation-types/{id}', [Api::class, 'operationType']);

    // Warehouse products
    Route::get('/warehouse-products', [Api::class, 'warehouseProducts']);
});
