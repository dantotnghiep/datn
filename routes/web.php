<?php

use App\Http\Controllers\Admin\HotProductController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\VariationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Pusher\Pusher;

Route::get('/', [HomeController::class, 'dashboard'])->name('client.index');
Route::get('/categories', [HomeController::class, 'category'])->name('categories.index');

Route::post('/broadcasting/auth', function () {
    return auth()->check() ? auth()->user() : abort(403);
});

// Client Product
Route::get('/list-product', [ProductController::class, 'listproduct'])->name('client.product.list-product');
Route::get('/product-details/{id}', [ProductController::class, 'show'])->name('client.product.product-details');

// Auth
Route::get('/login', [App\Http\Controllers\Client\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Client\AuthController::class, 'login'])->name('login.post');
Route::get('/register', [App\Http\Controllers\Client\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Client\AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [App\Http\Controllers\Client\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [App\Http\Controllers\Client\AuthController::class, 'sendResetLinkEmail'])->name('forgot-password.post');
Route::get('/reset-password/{token}', [App\Http\Controllers\Client\AuthController::class, 'showResetPasswordForm'])->name('reset-password');
Route::post('/reset-password', [App\Http\Controllers\Client\AuthController::class, 'resetPassword'])->name('reset-password.post');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Client\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout.index');
    Route::post('/checkout', [OrderController::class, 'store'])->name('checkout.store');
    Route::get('/order', [OrderController::class, 'order'])->name('order');
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
    });
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/', [ProductController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::get('/forgot-password', [AuthController::class, 'forgotpassword'])->name('admin.auth.forgot-password');

    // Admin Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    });

    // Admin Category
    Route::resource('/category', CategoryController::class);
    
    // Admin AttributesValues
    Route::resource('/attribute-values', AttributeValueController::class);
    
    // Admin Product
    Route::resource('/product', ProductController::class);
    Route::get('/products/{id}/variations', [ProductController::class, 'showVariations'])->name('product.variations');
    Route::resource('/product-variations', ProductVariationController::class);
    
    // Admin Variation
    Route::put('/variation/{id}', [VariationController::class, 'update'])->name('admin.variation.update');

    // Admin Hot Products
    Route::resource('/homesetting/hot-products', HotProductController::class);
    
    // Admin Discount
    Route::resource('/discounts', App\Http\Controllers\Admin\DiscountController::class);
});

Route::middleware(['auth'])->group(function () {
    Route::resource('/cart', CartController::class);
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Pusher Authentication
Route::post('/pusher/auth', function (Request $request) {
    if (auth()->check()) {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );
        
        return response($pusher->socket_auth($request->input('channel_name'), $request->input('socket_id')));
    }
    abort(403);
});

Broadcast::routes();
