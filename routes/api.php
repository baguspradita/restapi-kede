<?php

use App\Http\Controllers\Api\AddressController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public routes - Authentication
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']);
});

// Public routes - Categories
Route::prefix('categories')->group(function () {
    Route::get('/', [CategoryController::class, 'index']);
    Route::get('{id}', [CategoryController::class, 'show']);
    Route::get('{id}/products', [CategoryController::class, 'products']);
});

// Public routes - Products
Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('search', [ProductController::class, 'search']);
    Route::get('popular', [ProductController::class, 'popular']);
    Route::get('deals', [ProductController::class, 'deals']);
    Route::get('{id}', [ProductController::class, 'show']);
    Route::get('{id}/reviews', [ReviewController::class, 'index']);
});

// Public routes - Banners
Route::get('banners', [BannerController::class, 'index']);

// Protected routes - require authentication
Route::middleware('auth:api')->group(function () {

    // Auth routes
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh-token', [AuthController::class, 'refreshToken']);
    });

    // User routes
    Route::prefix('users')->group(function () {
        Route::get('profile', [UserController::class, 'profile']);
        Route::put('profile', [UserController::class, 'updateProfile']);
        Route::put('password', [UserController::class, 'updatePassword']);
        Route::post('profile-image', [UserController::class, 'uploadProfileImage']);
    });

    // Cart routes
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('items', [CartController::class, 'addItem']);
        Route::put('items/{id}', [CartController::class, 'updateItem']);
        Route::delete('items/{id}', [CartController::class, 'removeItem']);
        Route::delete('/', [CartController::class, 'clear']);
    });

    // Address routes
    Route::prefix('addresses')->group(function () {
        Route::get('/', [AddressController::class, 'index']);
        Route::get('{id}', [AddressController::class, 'show']);
        Route::post('/', [AddressController::class, 'store']);
        Route::put('{id}', [AddressController::class, 'update']);
        Route::delete('{id}', [AddressController::class, 'destroy']);
        Route::put('{id}/default', [AddressController::class, 'setDefault']);
    });

    // Order routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('{id}', [OrderController::class, 'show']);
        Route::post('/', [OrderController::class, 'store']);
        Route::put('{id}/cancel', [OrderController::class, 'cancel']);
        Route::get('{id}/track', [OrderController::class, 'track']);
        Route::post('{orderId}/reviews', [ReviewController::class, 'store']);
    });

    // Review routes
    Route::prefix('reviews')->group(function () {
        Route::put('{id}', [ReviewController::class, 'update']);
        Route::delete('{id}', [ReviewController::class, 'destroy']);
    });

    // Favorite routes
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'index']);
        Route::post('/', [FavoriteController::class, 'store']);
        Route::delete('{productId}', [FavoriteController::class, 'destroy']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::put('{id}/read', [NotificationController::class, 'markAsRead']);
        Route::put('read-all', [NotificationController::class, 'markAllAsRead']);
        Route::delete('{id}', [NotificationController::class, 'destroy']);
    });

    // Payment routes
    Route::prefix('payments')->group(function () {
        Route::post('process', [PaymentController::class, 'process']);
        Route::get('{id}/status', [PaymentController::class, 'status']);
    });
});

// Payment webhook (public)
Route::post('payments/webhook', [PaymentController::class, 'webhook']);
