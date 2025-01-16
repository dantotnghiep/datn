<?php

use App\Http\Controllers\CategoryController;
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



















//Route Admin code đây cho mình nhé iu anh emmmm
Route::get('/admin', function () {
    return view('admin.dashboard');
})->name('admin.dashboard');

Route::get('/admin/product-list', function () {
    return view('admin.product-list');
});
Route::get('/admin/add-product', function () {
    return view('admin.add-product');
});

//admin/Category
Route::get('/admin/category',[CategoryController::class,'index'])->name('admin.category');
Route::get('/admin/category/create', [CategoryController::class, 'create'])->name('admin.category.create');
Route::post('/admin/category',[CategoryController::class,'store'])->name('admin.category.store');
Route::delete('/admin/category/{id}',[CategoryController::class,'destroy'])->name('admin.category.destroy');
