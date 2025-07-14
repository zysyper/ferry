<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductDetailsController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('admin')->name('admin.')->group(function () {
    // Route::resource('orders', OrderController::class)->except(['update']);

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::patch('products/{product}/stock', [ProductController::class, 'updateStock'])->name('products.updateStock');

    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('new', [OrderController::class, 'create'])->name('orders.create');
    Route::post('orders/create/post', [OrderController::class, 'store'])->name('orders.store');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');
    Route::patch('orders/{order}/confirm', [OrderController::class, 'confirm'])->name('orders.confirm');
    Route::patch('orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders/{order}/edit', [OrderController::class, 'edit'])->name('orders.edit');
    Route::put('/orders/{order}', [OrderController::class, 'update'])->name('orders.update');
});

Route::group(['prefix' => 'cart', 'as' => 'cart.'], function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/update/{productId}', [CartController::class, 'update'])->name('update');
    Route::delete('/remove/{productId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout');
    Route::post('/checkout', [CartController::class, 'processCheckout'])->name('process-checkout');
});

Route::get('/produk', [ProductDetailsController::class, 'index'])->name('products.index');
Route::get('/produk/{id}', [ProductDetailsController::class, 'show'])->name('products.show');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

// Register
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Forgot Password
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

// Reset Password
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/order/success/{orderId}', [CartController::class, 'orderSuccess'])->name('order.success');
    Route::post('/buy-now', [App\Http\Controllers\CartController::class, 'buyNow'])->name('buy.now');
    Route::get('/buy-now/{productId}', [App\Http\Controllers\CartController::class, 'buyNowCheckout'])->name('buy.now.form');
    Route::post('/buy-now/checkout', [App\Http\Controllers\CartController::class, 'buy'])->name('buy.now.checkout');
});
