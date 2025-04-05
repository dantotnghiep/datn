<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\HotProductController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProfileController;
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
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|php artisan key:generate
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'dashboard'])->name('client.index');
Route::get('/categories', [HomeController::class, 'category'])->name('categories.index');

// Route::get('/order', [CartController::class, 'order'])->name('cart.order');

// Broadcast authentication cho Pusher
Route::post('/broadcasting/auth', function () {
    return auth()->check() ? auth()->user() : abort(403);
});


//client/product
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

Route::middleware('auth')->group(function () {
    // Route::post('/logout', [App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('addresses.destroy');
    Route::post('/addresses/{address}/set-default', [ProfileController::class, 'setDefaultAddress'])->name('addresses.set-default');


    Route::get('/order', [OrderController::class, 'order'])->name('order');
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Http\Controllers\Client\OrderController::class, 'index'])->name('orders.index');
    });
});


//ADMIN CODE BẮT ĐẦU TỪ ĐÂY NHÉ

Route::prefix('admin')->group(function () {


    //admin/Auth
    Route::get('/login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/forgot-password', [AuthController::class, 'forgotpassword'])->name('admin.auth.forgot-password');

    // Admin Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('admin.orders.index');
        Route::get('/{order}', [App\Http\Controllers\Admin\OrderController::class, 'show'])->name('admin.orders.show');
        Route::post('/{order}/update-status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    });

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
    Route::get('/products/{id}/variations', [ProductController::class, 'showVariations'])->name('product.variations');
    Route::get('/products/{id}/variations/create', [ProductVariationController::class, 'create'])->name('product-variations.create');
    Route::post('/product-variations/store', [ProductVariationController::class, 'store'])->name('product-variations.store');

    Route::put('/variation/{id}', [VariationController::class, 'update'])->name('admin.variation.update');

    Route::get('/homesetting/hot-products', [HotProductController::class, 'index'])->name('hot-products.index');
    Route::post('/homesetting/hot-products', [HotProductController::class, 'store'])->name('hot-products.store');
    Route::delete('/homesetting/hot-products/{id}', [HotProductController::class, 'destroy'])->name('hot-products.destroy');
    Route::get('/homesetting/hot-products/search', [HotProductController::class, 'search'])->name('hot_products.search');

    // Admin Discount Routes
    Route::get('/discounts', [App\Http\Controllers\Admin\DiscountController::class, 'index'])
        ->name('admin.discounts.index');

    Route::get('/discounts/create', [App\Http\Controllers\Admin\DiscountController::class, 'create'])
        ->name('admin.discounts.create');

    Route::post('/discounts', [App\Http\Controllers\Admin\DiscountController::class, 'store'])
        ->name('admin.discounts.store');

    Route::get('/discounts/{discount}/edit', [App\Http\Controllers\Admin\DiscountController::class, 'edit'])
        ->name('admin.discounts.edit');

    Route::put('/discounts/{discount}', [App\Http\Controllers\Admin\DiscountController::class, 'update'])
        ->name('admin.discounts.update');

    Route::delete('/discounts/{discount}', [App\Http\Controllers\Admin\DiscountController::class, 'destroy'])
        ->name('admin.discounts.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

    // Routes cho checkout và thanh toán
    Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/process-checkout', [OrderController::class, 'store'])->name('cart.process-checkout');
    // Routes cho trang checkout và quản lý địa chỉ
    Route::get('/cart/checkout', [OrderController::class, 'showCheckout'])->name('cart.checkout');
    Route::post('/checkout/address', [OrderController::class, 'storeAddress'])->name('order.storeAddress');

    Route::post('/order/store', [OrderController::class, 'store'])->name('order.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus'])
        ->name('orders.updateStatus');
    Route::post('/orders/{id}/cancel', [App\Http\Controllers\Api\OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/vnpay-return', [OrderController::class, 'vnpayReturn'])->name('vnpay.return');
});

//Route::middleware(['auth', 'staff'])->prefix('admin/users')->group(function () {
Route::middleware(['auth'])->prefix('admin/users')->group(function () {
    // Khách hàng
    Route::get('/clients', [AdminCustomerController::class, 'index'])->name('admin.users.clients.index');
    Route::post('/clients/{id}/lock', [AdminCustomerController::class, 'lock'])->name('admin.users.clients.lock');
    Route::post('/clients/{id}/unlock', [AdminCustomerController::class, 'unlock'])->name('admin.users.clients.unlock');
    Route::get('/clients/{id}', [AdminCustomerController::class, 'show'])->name('admin.users.clients.detail');

    // Nhân viên/admin
    Route::get('/staffs', [AdminEmployeeController::class, 'index'])->name('admin.users.staffs.index');
    Route::post('/staffs', [AdminEmployeeController::class, 'store'])->name('admin.users.staffs.store');
    Route::put('/staffs/{id}', [AdminEmployeeController::class, 'update'])->name('admin.users.staffs.update');
    Route::delete('/staffs/{id}', [AdminEmployeeController::class, 'destroy'])->name('admin.users.staffs.destroy');
    Route::post('/staffs/{id}/lock', [AdminEmployeeController::class, 'lock'])->name('admin.users.staffs.lock');
    Route::post('/staffs/{id}/unlock', [AdminEmployeeController::class, 'unlock'])->name('admin.users.staffs.unlock');

});

// Thêm route này cho client hủy đơn hàng
// Route::post('/orders/{id}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

// Thêm route mới này
Route::post('/pusher/auth', function (Request $request) {
    if (auth()->check()) {
        $pusher = new Pusher(
            config('broadcasting.connections.pusher.key'),
            config('broadcasting.connections.pusher.secret'),
            config('broadcasting.connections.pusher.app_id'),
            config('broadcasting.connections.pusher.options')
        );

        $channel = $request->input('channel_name');
        $socket_id = $request->input('socket_id');

        $auth = $pusher->socket_auth($channel, $socket_id);

        return response($auth);
    } else {
        abort(403);
    }
});

Broadcast::routes();

// Route cho admin
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/order-list', [OrderController::class, 'index'])->name('admin.orders.list');

    // Route cập nhật trạng thái đơn hàng
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