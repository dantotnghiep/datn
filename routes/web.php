<?php

use App\Http\Controllers\AttributeController;
use App\Http\Controllers\AttributeValueController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

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

Route::get('/',[CategoryController::class,'dashboard'])->name('client.index');

// Route::prefix('admin')
//     ->group(function () {
//         Route::get('/', [ProductController::class, 'dashboard'])->name('dashboard');
//         Route::get('/add-product', [ProductController::class, 'add-product'])->name('add-product');

//     });

//Route Admin code đây cho mình nhé iu anh emmmm
//admin/Product


//client/Auth
Route::get('/login',[AuthController::class,'loginclient'])->name('client.auth.login');
Route::get('/register',[AuthController::class,'register'])->name('clinet.auth.register');

//client/cart
Route::get('/cart',[CartController::class,'cart'])->name('client.cart.cart');
Route::get('/order',[CartController::class,'order'])->name('client.cart.order');
Route::get('/checkout',[CartController::class,'checkout'])->name('client.cart.checkout');

//client/product
Route::get('/list-product',[ProductController::class,'listproduct'])->name('client.product.list-product');
Route::get('/product-details',[ProductController::class,'productdetails'])->name('client.product.product-details');


//ADMIN CODE BẮT ĐẦU TỪ ĐÂY NHÉ

Route::get('/admin',[ProductController::class,'dashboard'])->name('admin.dashboard');
//admin/Auth
Route::get('/admin/login',[AuthController::class,'login'])->name('admin.auth.login');
Route::get('/admin/forgot-password',[AuthController::class,'forgotpassword'])->name('admin.auth.forgot-password');


//admin/Category
Route::get('/admin/category',[CategoryController::class,'index'])->name('admin.category');
Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
Route::post('/admin/category',[CategoryController::class,'store'])->name('admin.category.store');
Route::get('/admin/category/{id}/edit',[CategoryController::class,'edit'])->name('admin.category.edit');
Route::put('admin/category/{id}',[CategoryController::class,'update'])->name('admin.category.update');
Route::delete('/admin/category/{id}',[CategoryController::class,'destroy'])->name('admin.category.destroy');

//admin/attributes
Route::get('/admin/attribute',[AttributeController::class,'index'])->name('admin.attribute.attribute');
Route::get('/admin/attribute/create',[AttributeController::class,'create'])->name('admin.attribute.create');
Route::post('/admin/attribute',[AttributeController::class,'store'])->name('admin.attribute.store');
Route::get('/admin/attribute/{id}/edit',[AttributeController::class,'edit'])->name('admin.attribute.edit');
Route::put('/admin/attribute/{id}',[AttributeController::class,'update'])->name('admin.attribute.update');
Route::delete('admin/attribute/{id}/delete',[AttributeController::class,'destroy'])->name('admin.attribute.destroy');

//admin/attributesValues
Route::get('/admin/attribute-values',[AttributeValueController::class,'create'])->name('admin.attribute.attribute-values');


//admin/Product
Route::get('/admin',[ProductController::class,'dashboard'])->name('admin.dashboard');
Route::get('/admin/product-list',[ProductController::class,'index'])->name('admin.product.product-list');
Route::get('/admin/add-product',[ProductController::class,'create'])->name('admin.product.add-product');







