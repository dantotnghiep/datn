<?php

use App\Http\Controllers\Admin\HomeCustomizationController;
use App\Http\Controllers\Admin\HotProductController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Client\CategoryController as ClientCategoryController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariationController;
use App\Http\Controllers\VariationController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
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




//client/product
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
});


//ADMIN CODE BẮT ĐẦU TỪ ĐÂY NHÉ

Route::prefix('admin')->group(function () {
    Route::get('/', [ProductController::class, 'dashboard'])->name('admin.dashboard');

    //admin/Auth
    Route::get('/login', [AuthController::class, 'login'])->name('admin.auth.login');
    Route::get('/forgot-password', [AuthController::class, 'forgotpassword'])->name('admin.auth.forgot-password');

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
});

Route::middleware(['auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::put('/cart/{id}', [CartController::class, 'update'])->name('cart.update');
});
