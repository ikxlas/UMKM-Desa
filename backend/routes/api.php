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

Route::apiResource('categories', CategoryController::class);
Route::apiResource('merchants', MerchantController::class);
Route::apiResource('products', ProductController::class);

Route::get('settings', [SettingController::class, 'index']);
Route::post('settings', [SettingController::class, 'update']);
