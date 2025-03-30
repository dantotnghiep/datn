<?php

use App\Http\Controllers\Admin\HotProductController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\UserController;
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
Route::post('/login', [LoginController::class, 'loginUser'])->name('login.post');
Route::get('/register', [App\Http\Controllers\Client\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\Client\AuthController::class, 'register'])->name('register.post');
Route::get('/forgot-password', [App\Http\Controllers\Client\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
Route::post('/forgot-password', [App\Http\Controllers\Client\AuthController::class, 'sendResetLinkEmail'])->name('forgot-password.post');
Route::get('/reset-password/{token}', [App\Http\Controllers\Client\AuthController::class, 'showResetPasswordForm'])->name('reset-password');
Route::post('/reset-password', [App\Http\Controllers\Client\AuthController::class, 'resetPassword'])->name('reset-password.post');

<<<<<<< HEAD
Route::middleware(['auth'])->group(function () {
    Route::resource('/cart', CartController::class);
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add'); // Thêm route này
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
=======
Route::middleware('auth')->group(function () {
    Route::post('/logout', [App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [App\Http\Controllers\Client\ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [App\Http\Controllers\Client\ProfileController::class, 'update'])->name('profile.update');

    Route::get('/order', [OrderController::class, 'order'])->name('order');
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/{id}', [OrderController::class, 'show'])->name('orders.show');
    });
>>>>>>> f83408911b97f63d36d14c99c115e19ce80e9761
});


// Admin Routes
Route::prefix('admin')->group(function () {
<<<<<<< HEAD
    Route::get('/', [ProductController::class, 'dashboard'])->name('admin.dashboard');
=======
    

    //admin/Auth
>>>>>>> f83408911b97f63d36d14c99c115e19ce80e9761
    Route::get('/login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::get('/forgot-password', [AuthController::class, 'forgotpassword'])->name('admin.auth.forgot-password');

    // Admin Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    });

<<<<<<< HEAD
    // Admin Category
    Route::resource('/category', CategoryController::class);
    
    // Admin AttributesValues
    Route::resource('/attribute-values', AttributeValueController::class);
    
    // Admin Product
    Route::resource('/product', ProductController::class);
=======
    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('admin.dashboard');
    });

    Route::post('/admin/login', [LoginController::class, 'loginAdmin'])->name('vh.dz');

    //admin/Category
    Route::get('/category', [CategoryController::class, 'index'])->name('admin.category');
    Route::get('/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/category', [CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('admin.category.destroy');

    //admin/attributesValues
    Route::get('/attribute-values', [AttributeValueController::class, 'index'])->name('admin.attribute-values');
    Route::get('/attribute-values/create', [AttributeValueController::class, 'create'])->name('admin.attribute-values.create');
    Route::post('/attribute-values', [AttributeValueController::class, 'store'])->name('admin.attribute-values.store');

    //admin/Product
    Route::get('/product-list', [ProductController::class, 'index'])->name('admin.product.product-list');
    Route::get('/add-product', [ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/product', [ProductController::class, 'store'])->name('admin.product.store');
    Route::get('/product/{product}/edit', [ProductController::class, 'edit'])->name('admin.product.edit');
    Route::put('/product/{product}', [ProductController::class, 'update'])->name('admin.product.update');
    Route::delete('/products/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy');

    //admin/Variation
>>>>>>> f83408911b97f63d36d14c99c115e19ce80e9761
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
<<<<<<< HEAD
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
=======

    // Routes cho checkout và thanh toán
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/process-checkout', [OrderController::class, 'store'])->name('cart.process-checkout');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
>>>>>>> f83408911b97f63d36d14c99c115e19ce80e9761
    Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');

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
<<<<<<< HEAD
        
        return response($pusher->socket_auth($request->input('channel_name'), $request->input('socket_id')));
=======

        $channel = $request->input('channel_name');
        $socket_id = $request->input('socket_id');

        $auth = $pusher->socket_auth($channel, $socket_id);

        return response($auth);
    } else {
        abort(403);
>>>>>>> f83408911b97f63d36d14c99c115e19ce80e9761
    }
    abort(403);
});

Broadcast::routes();
<<<<<<< HEAD
=======

// Route cho admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::post('/admin/orders/{id}/update-status', [OrderController::class, 'updateStatus'])
        ->name('admin.orders.updateStatus');
});

// Route cho client
Route::middleware(['auth'])->group(function () {
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
});

Route::resource('/admin/users', UserController::class);
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::resource('/admin/users', UserController::class);
// });

Route::get('/staff/dashboard', [LoginController::class, 'sta'])->name('staff.dashboard');
>>>>>>> f83408911b97f63d36d14c99c115e19ce80e9761
