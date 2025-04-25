<?php

use App\Http\Controllers\Admin\AdminCustomerController;
use App\Http\Controllers\Admin\AdminEmployeeController;
use App\Http\Controllers\Admin\ChatControllerAdmin;
use App\Http\Controllers\Admin\HotProductController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BotmanController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VariationController;
use App\Http\Controllers\WishlistController;
use App\Jobs\UnlockUserAfterThreeDays;
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

Route::middleware(['checkaccount'])->group(function () {
    Route::get('/', [HomeController::class, 'dashboard'])->name('client.index');
    Route::get('/categories', [HomeController::class, 'category'])->name('categories.index');
    Route::match(['get', 'post'], '/botmanc', [BotmanController::class, 'handle'])->name('botman');
    Route::get('/botman/chat', function () {
        return view('client.botman.chat');
    })->name('botman.chat');
    Route::get('/botman/checkid', [BotmanController::class, 'checkId'])->name('botman.checkId');
    Route::post('/save-chat-messages', [BotmanController::class, 'saveChatMessages'])->name('saveChatMessages');
    Route::post('/StopConversation', [BotmanController::class, 'StopConversation'])->name('StopConversation');
    Route::get('/get-chat-messages', [BotmanController::class, 'getChatMessages'])->name('getChatMessages');
    // Route::get('/order', [CartController::class, 'order'])->name('cart.order');

    // Broadcast authentication cho Pusher
    Route::post('/broadcasting/auth', function () {
        return auth()->check() ? auth()->user() : abort(403);
    });


    //client/product
    Route::get('/list-product', [ProductController::class, 'listproduct'])->name('client.product.list-product');
    Route::get('/product-details/{id}', [ProductController::class, 'show'])->name('client.product.product-details');

Route::get('/', [HomeController::class, 'dashboard'])->name('client.index');
Route::get('/categories', [HomeController::class, 'category'])->name('categories.index');
Route::get('/search', [HomeController::class, 'search'])->name('client.search');
Route::get('/search-suggestions', [HomeController::class, 'searchSuggestions'])->name('client.search-suggestions');
Route::get('/contact', [HomeController::class, 'contact'])->name('home.contact');
Route::get('/about', [HomeController::class, 'about'])->name('home.about');


    // Auth
    Route::get('/login', [App\Http\Controllers\Client\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\Client\AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [App\Http\Controllers\Client\AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [App\Http\Controllers\Client\AuthController::class, 'register'])->name('register.post')->middleware('web');

    Route::get('/forgot-password', [App\Http\Controllers\Client\AuthController::class, 'showForgotPasswordForm'])->name('forgot-password');
    Route::post('/forgot-password', [App\Http\Controllers\Client\AuthController::class, 'sendResetLinkEmail'])->name('forgot-password.post');
    Route::get('/reset-password', [App\Http\Controllers\Client\AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [App\Http\Controllers\Client\AuthController::class, 'resetPassword'])->name('reset-password.post');




    Route::middleware('auth')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Client\AuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
        Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.store');
        Route::get('/profile/addresses', [ProfileController::class, 'showAddresses'])->name('profile.addresses');
        Route::post('/addresses', [ProfileController::class, 'storeAddress'])->name('addresses.store');
        Route::put('/addresses/{address}', [ProfileController::class, 'updateAddress'])->name('addresses.update');
        Route::delete('/addresses/{address}', [ProfileController::class, 'destroyAddress'])->name('addresses.destroy');
        Route::post('/addresses/{address}/set-default', [ProfileController::class, 'setDefaultAddress'])->name('addresses.set-default');


        Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');



        Route::get('/order', [OrderController::class, 'order'])->name('order');
        Route::prefix('orders')->group(function () {
            Route::get('/', [App\Http\Controllers\Client\OrderController::class, 'index'])->name('orders.index');
        });

    Route::middleware(['admin'])->group(function () {
        Route::get('/dashboard', [ProductController::class, 'dashboard'])->name('admin.dashboard');
        Route::post('/dashboard/data', [ProductController::class, 'dashboardData'])->name('admin.dashboard.data');

    });


    //ADMIN CODE BẮT ĐẦU TỪ ĐÂY NHÉ

    Route::prefix('admin')->group(function () {


        //admin/Auth
        Route::get('/login', [AuthController::class, 'login'])->name('admin.auth.login');
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

        Route::middleware(['auth'])->prefix('chat')->name('admin.chat.')->group(
            function () {
                Route::get('/', [ChatControllerAdmin::class, 'index'])->name('index');
                Route::post('/send', [ChatControllerAdmin::class, 'sendMessageToUser'])->name('sendMessageToUser');
                Route::get('/getListPerson', [ChatControllerAdmin::class, 'getListPerson'])->name('getListPerson');
                Route::get('/getMessages', [ChatControllerAdmin::class, 'getMessages'])->name('getMessages');
                Route::post('info', [ChatControllerAdmin::class, 'showInfo'])->name('showInfoUser');
                Route::put('edit/{id?}', [ChatControllerAdmin::class, 'postEdit'])->name('postEditUser');
                Route::post('delete/{id?}', [ChatControllerAdmin::class, 'deleteChat'])->name('deleteChatUser');
            }
        );
    });

    Route::middleware(['auth'])->group(function () {
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
        Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
        Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
        Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.apply-coupon');

        // Routes cho checkout và thanh toán
        Route::get('/cart/checkout', [OrderController::class, 'showCheckout'])->name('cart.checkout');
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


        Route::get('/email/verify/{id}/{hash}', [UserController::class, 'checkVerify'])->name('verification.verify');
        Route::get('/account/verify', [UserController::class, 'verify'])->name('verify');
        Route::post('/account/verify', [UserController::class, 'postVerify'])->name('postVerify');
    });

    //Route::middleware(['auth', 'staff'])->prefix('admin/users')->group(function () {
    Route::middleware(['auth'])->prefix('admin/users')->group(function () {
        // Khách hàng
        Route::get('/clients', [AdminCustomerController::class, 'index'])->name('admin.users.clients.index');
        Route::get('/clients/{id}/lock', [AdminCustomerController::class, 'lock'])->name('admin.users.clients.lock');
        Route::get('/clients/{id}/unlock', [AdminCustomerController::class, 'unlock'])->name('admin.users.clients.unlock');
        Route::get('/unlock-users', function () {
            UnlockUserAfterThreeDays::dispatch();
            return 'Job dispatched!';
        });
        Route::get('/clients/{id}', [AdminCustomerController::class, 'show'])->name('admin.users.clients.detail');
        Route::post('/clients', [AdminCustomerController::class, 'store'])->name('admin.users.clients.store');
        Route::post('/clients/{id}/reset-password', [AdminCustomerController::class, 'resetPassword'])->name('admin.users.clients.reset-password');
        Route::post('/clients/{id}/lock', [AdminCustomerController::class, 'lock'])->name('admin.users.clients.lock.detail');
        Route::post('/clients/{id}/unlock', [AdminCustomerController::class, 'unlock'])->name('admin.users.clients.unlock.detail');
        Route::post('/clients/{id}/warn', [AdminCustomerController::class, 'warn'])->name('admin.users.clients.warn');


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
});
Route::get('/staff/dashboard', [LoginController::class, 'sta'])->name('staff.dashboard');

// Product Variation Routes
Route::post('/admin/variation/{productId}', [ProductController::class, 'storeVariation'])->name('admin.variation.store');

