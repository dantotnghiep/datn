<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Models\Category;
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

Route::get('/', function () {
    return view('client.index');
});

// Route::prefix('admin')
//     ->group(function () {
//         Route::get('/', [ProductController::class, 'dashboard'])->name('dashboard');
//         Route::get('/add-product', [ProductController::class, 'add-product'])->name('add-product');

//     });

//Route Admin code đây cho mình nhé iu anh emmmm
//admin/Product
Route::get('/admin',[ProductController::class,'dashboard'])->name('admin.dashboard');
Route::get('/admin/product-list',[ProductController::class,'index'])->name('admin.products.product-list');
Route::get('/admin/add-product',[ProductController::class,'create'])->name('admin.products.add-product');

Route::get('/admin/login',[AuthController::class,'login'])->name('admin.auth.login');

//admin/Category
Route::get('/admin/category',[CategoryController::class,'index'])->name('admin.category');
Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
Route::post('/admin/category',[CategoryController::class,'store'])->name('admin.category.store');

Route::delete('/admin/category/{id}',[CategoryController::class,'destroy'])->name('admin.category.destroy');

Route::get('/admin/category/{id}/edit',[CategoryController::class,'edit'])->name('admin.category.edit');
Route::put('admin/category/{id}',[CategoryController::class,'update'])->name('admin.category.update');
