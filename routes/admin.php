<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariationController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.components.dashboard');
    })->name('dashboard');

    // CRUD routes cho danh mục (category)
    Route::prefix('categories')->name('categories.')->group(function () {
        // Hiển thị danh sách
        Route::get('/', [CategoryController::class, 'index'])->name('index');

        // Form tạo mớiimage.png
        Route::get('/create', [CategoryController::class, 'create'])->name('create');

        // Lưu dữ liệu mới
        Route::post('/', [CategoryController::class, 'store'])->name('store');

        // Form chỉnh sửa
        Route::get('/{id}/edit', [CategoryController::class, 'edit'])->name('edit');

        // Cập nhật dữ liệu
        Route::put('/{id}', [CategoryController::class, 'update'])->name('update');

        // Xóa mềm
        Route::delete('/{id}', [CategoryController::class, 'destroy'])->name('destroy');

        // Khôi phục từ thùng rác
        Route::put('/{id}/restore', [CategoryController::class, 'restore'])->name('restore');
    });

    // CRUD routes for products
    Route::prefix('products')->name('products.')->group(function () {
        // List
        Route::get('/', [ProductController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [ProductController::class, 'create'])->name('create');

        // Store
        Route::post('/', [ProductController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [ProductController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [ProductController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [ProductController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [ProductController::class, 'restore'])->name('restore');
    });

    // CRUD routes for product images
    Route::prefix('product-images')->name('product-images.')->group(function () {
        // List
        Route::get('/', [ProductImageController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [ProductImageController::class, 'create'])->name('create');

        // Store
        Route::post('/', [ProductImageController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [ProductImageController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [ProductImageController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [ProductImageController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [ProductImageController::class, 'restore'])->name('restore');
    });

    // CRUD routes for product variations
    Route::prefix('product-variations')->name('product-variations.')->group(function () {
        // List
        Route::get('/', [ProductVariationController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [ProductVariationController::class, 'create'])->name('create');

        // Store
        Route::post('/', [ProductVariationController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [ProductVariationController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [ProductVariationController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [ProductVariationController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [ProductVariationController::class, 'restore'])->name('restore');
    });

});
