<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('site.access')->prefix('v1')->group(function () {
    Route::post('/site/check-key-update-status', [App\Http\Controllers\Member\API\ApiController::class, 'siteKeyUpdateStatus']);
    Route::post('/site/update-plugin-version', [App\Http\Controllers\Member\API\ApiController::class, 'updatePluginVersion']);
    Route::post('/products', [App\Http\Controllers\Member\API\ApiController::class, 'products']);
    Route::post('/products-bulk', [App\Http\Controllers\Member\API\ApiController::class, 'productsBulk']);
    Route::post('/products/change-state', [App\Http\Controllers\Member\API\ApiController::class, 'productChangeState']);
    Route::post('/products/product-status', [App\Http\Controllers\Member\API\ApiController::class, 'productsGetStatus']);
    Route::post('/products/check-ordered', [App\Http\Controllers\Member\API\ApiController::class, 'checkOrdered']);

});
