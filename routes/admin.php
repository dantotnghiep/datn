<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('admin.components.dashboard');
});

Route::get('/them-danh-muc', function () {
    return view('admin.components.categories.create');
})->name('categories.create');

Route::get('/danh-muc', function () {
    return view('admin.components.categories.index');
})->name('categories.index');
Route::get('/san-pham', function () {
    return view('admin.components.product.index');
})->name('product.index');
Route::get('/them-san-pham', function () {
    return view('admin.components.product.create');
})->name('product.create');

Route::get('/khach-hang', function () {
    return view('admin.components.customers.index');
})->name('customers.index');

Route::get('/don-hang', function () {
    return view('admin.components.orders.index');
})->name('orders.index');

Route::get('/chi-tiet-don-hang', function () {
    return view('admin.components.orders.details');
})->name('orders.details');


// Route::prefix('admin')->name('admin.')->group(function () {
//     // Dashboard
//     Route::get('/', function () {
//         return view('admin.layouts.app');
//     })->name('dashboard');

//     // Routes cho Category
//     Route::prefix('categories')->name('categories.')->group(function () {
//         // Hiển thị danh sách
//         Route::get('/', [CategoryController::class, 'index'])->name('index');

//         // Form tạo mới
//         Route::get('/create', [CategoryController::class, 'create'])->name('create');

//         // Lưu dữ liệu mới
//         Route::post('/', [CategoryController::class, 'store'])->name('store');

//         // Form chỉnh sửa
//         Route::get('/{slug}/edit', [CategoryController::class, 'edit'])->name('edit');

//         // Cập nhật dữ liệu
//         Route::put('/{slug}', [CategoryController::class, 'update'])->name('update');

//         // Xóa mềm
//         Route::delete('/{slug}', [CategoryController::class, 'destroy'])->name('destroy');

//         // Khôi phục từ thùng rác
//         Route::put('/{slug}/restore', [CategoryController::class, 'restore'])->name('restore');
//     });

//     // Routes cho Product
//     Route::prefix('products')->name('products.')->group(function () {
//         // Hiển thị danh sách
//         Route::get('/', [ProductController::class, 'index'])->name('index');

//         // Form tạo mới
//         Route::get('/create', [ProductController::class, 'create'])->name('create');

//         // Lưu dữ liệu mới
//         Route::post('/', [ProductController::class, 'store'])->name('store');

//         // Form chỉnh sửa
//         Route::get('/{slug}/edit', [ProductController::class, 'edit'])->name('edit');

//         // Cập nhật dữ liệu
//         Route::put('/{slug}', [ProductController::class, 'update'])->name('update');

//         // Xóa mềm
//         Route::delete('/{slug}', [ProductController::class, 'destroy'])->name('destroy');

//         // Khôi phục từ thùng rác
//         Route::put('/{slug}/restore', [ProductController::class, 'restore'])->name('restore');
//     });
//     // Routes cho Name
//     Route::prefix('names')->name('names.')->group(function () {
//         // Hiển thị danh sách
//         Route::get('/', [NameController::class, 'index'])->name('index');

//         // Form tạo mới
//         Route::get('/create', [NameController::class, 'create'])->name('create');

//         // Lưu dữ liệu mới
//         Route::post('/', [NameController::class, 'store'])->name('store');

//         // Form chỉnh sửa
//         Route::get('/{slug}/edit', [NameController::class, 'edit'])->name('edit');

//         // Cập nhật dữ liệu
//         Route::put('/{slug}', [NameController::class, 'update'])->name('update');

//         // Xóa mềm
//         Route::delete('/{slug}', [NameController::class, 'destroy'])->name('destroy');

//         // Khôi phục từ thùng rác
//         Route::put('/{slug}/restore', [NameController::class, 'restore'])->name('restore');
//     });
// });
