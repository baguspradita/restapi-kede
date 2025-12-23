<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\ProductController;
use App\Models\Product;

use App\Models\User;

use App\Http\Controllers\Admin\UserController;

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
        return view('admin.dashboard', compact('productCount', 'userCount'));
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('users', UserController::class)->only(['index']);
});
