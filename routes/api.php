<?php

use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// --- Health ---
Route::get('/health', fn () => response()->json([
    'status' => 'ok',
    'time' => now()->toISOString(),
]));

// --- Public (read-only) ---
Route::get('/products', [ProductController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// --- Auth ---
Route::post('/auth/login', [AuthController::class, 'login'])->middleware('throttle:20,15');
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,15');
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:10,15');
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/change-password', [AuthController::class, 'changePassword'])->middleware('throttle:10,15');
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});

// --- Admin (token + admin role) ---
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/products', [AdminProductController::class, 'index']);
    Route::post('/products', [AdminProductController::class, 'store']);
    Route::get('/products/{product}', [AdminProductController::class, 'show']);
    Route::post('/products/{product}/update', [AdminProductController::class, 'update']);
    Route::put('/products/{product}', [AdminProductController::class, 'update']);
    Route::post('/products/{product}/status', [AdminProductController::class, 'updateStatus']);
    Route::patch('/products/{product}/status', [AdminProductController::class, 'updateStatus']);
    Route::post('/products/{product}/delete', [AdminProductController::class, 'destroy']);
    Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);

    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::post('/categories/{category}/update', [AdminCategoryController::class, 'update']);
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::post('/categories/{category}/delete', [AdminCategoryController::class, 'destroy']);
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);

    // Image upload for the product form (returns an absolute URL).
    Route::post('/uploads/image', [UploadController::class, 'image']);
});
