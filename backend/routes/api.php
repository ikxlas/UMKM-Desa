<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Test Ping Route
Route::get('/ping', function () {
    return response()->json([
        'status' => 'success',
        'message' => 'Backend Laravel Berhasil Terhubung ke Vue!',
        'timestamp' => now()->toDateTimeString()
    ]);
});

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);

// Public GET Routes (for visitors/home page)
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('merchants', MerchantController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::get('settings', [SettingController::class, 'index']);

// Protected Admin Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::apiResource('categories', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('merchants', MerchantController::class)->except(['index', 'show']);
    Route::apiResource('products', ProductController::class)->except(['index', 'show']);
    Route::post('settings', [SettingController::class, 'update']);
});
