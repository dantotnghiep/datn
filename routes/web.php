<?php
 
 use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Client\HomeController;
 use App\Http\Controllers\Client\ProductController;
 use App\Http\Controllers\Client\AuthController;
 use App\Http\Controllers\Client\WishlistController;
 use App\Http\Controllers\Client\CartController;
 
 /*
 |--------------------------------------------------------------------------
 | Web Routes
 |--------------------------------------------------------------------------
 |
 | Here is where you can register web routes for your application. These
 | routes are loaded by the RouteServiceProvider and all of them will
 | be assigned to the "web" middleware group. Make something great!
 |
 */
 
 // Route::get('/', function () {
 //     return view('client.master');
 // });
 
 Route::get('/', [HomeController::class, 'index'])->name('home');
 
 // Authentication Routes
 Route::middleware('guest')->group(function () {
     Route::get('/dang-ky', [AuthController::class, 'showRegisterForm'])->name('register');
     Route::post('/dang-ky', [AuthController::class, 'register']);
 
     Route::get('/dang-nhap', [AuthController::class, 'showLoginForm'])->name('login');
     Route::post('/dang-nhap', [AuthController::class, 'login']);
 
     Route::get('/quen-mat-khau', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
     Route::post('/quen-mat-khau', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
     Route::get('/dat-lai-mat-khau/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
     Route::post('/dat-lai-mat-khau', [AuthController::class, 'resetPassword'])->name('password.update');
 });
 
 // Cart view route (public)
 Route::get('/gio-hang', [CartController::class, 'index'])->name('cart');
 
 // Only logged-in users can add/update/remove cart
 Route::middleware('auth')->group(function () {
     Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
     Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
     Route::post('/gio-hang/xoa', [CartController::class, 'remove'])->name('cart.remove');
     Route::post('/dang-xuat', [AuthController::class, 'logout'])->name('logout');
     Route::get('/thong-tin', function () {
         return view('client.auth.profile');
     })->name('profile');
 
     // Wishlist routes
     Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
     Route::delete('/wishlist/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');
 
     // Checkout routes (Vietnamese)
     Route::post('/gio-hang/checkout-chon', [CartController::class, 'saveSelectedItems'])->name('cart.checkout.selected');
     Route::get('/thanh-toan', [CartController::class, 'checkout'])->name('checkout');
     Route::post('/dat-hang', [CartController::class, 'store'])->name('orders.store');
     
     // VNPay return route - must be public
     Route::get('/vnpay-return', [CartController::class, 'vnpayReturn'])->name('vnpay.return');
 });
 
 
 Route::get('/san-pham/{slug}', [ProductController::class, 'show'])->name('product.detail');
 
 Route::get('/san-pham', function () {
     return view('client.product.index');
 })->name('product');
 
 Route::get('/yeu-thich', [WishlistController::class, 'index'])->name('wishlist');