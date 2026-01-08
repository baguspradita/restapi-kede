<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Models\Order;
use App\Http\Controllers\Api\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    Route::get('/dashboard', function () {
        $productCount = Product::count();
        $userCount = User::count();
        $orderCount = Order::count();
        $recentOrders = Order::with('user')->latest()->take(5)->get();
        return view('admin.dashboard', compact('productCount', 'userCount', 'orderCount', 'recentOrders'));
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class)->only(['index']);
    Route::resource('orders', OrderController::class);
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('reviews', ReviewController::class)->only(['index', 'destroy']);
});

// Payment callbacks (public)
Route::get('/payments/return', [PaymentController::class, 'returnHandler']);
Route::get('/payments/cancel', [PaymentController::class, 'cancelHandler']);
