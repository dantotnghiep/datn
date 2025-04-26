<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ProductController;

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

Route::get('/chi-tiet-san-pham', [HomeController::class, 'show'])->name('detail');


Route::get('/san-pham', function () {
    return view('client.product.index');
})->name('product');
Route::get('/gio-hang', function () {
    return view('client.cart.cart');
})->name('cart');
Route::get('/checkout', function () {
    return view('client.cart.checkout');
})->name('checkout');
Route::get('/yeu-thich', function () {
    return view('client.wishlist.index');
})->name('wishlist');
Route::get('/thong-tin', function () {
    return view('client.auth.profile');
})->name('profile');
Route::get('/dang-ky', function () {
    return view('client.auth.register');
})->name('register');
Route::get('/dang-nhap', function () {
    return view('client.auth.login');
})->name('login');
Route::get('/quen-mat-khau', function () {
    return view('client.auth.forgot');
})->name('forgot');
