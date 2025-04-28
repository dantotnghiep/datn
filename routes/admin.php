<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ProductVariationController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AttributeValueController;

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

        // Temporary image upload for Dropzone
        Route::post('/upload-temp', [ProductImageController::class, 'uploadTemp'])->name('upload-temp');

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

    // Attribute routes
    Route::prefix('attributes')->name('attributes.')->group(function () {
        // List
        Route::get('/', [AttributeController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [AttributeController::class, 'create'])->name('create');

        // Store
        Route::post('/', [AttributeController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [AttributeController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [AttributeController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [AttributeController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [AttributeController::class, 'restore'])->name('restore');

        // Get values for a specific attribute
        Route::get('/{id}/values', [AttributeController::class, 'getValues'])->name('values');
    });

    // Attribute Value routes
    Route::prefix('attribute-values')->name('attribute-values.')->group(function () {
        // List
        Route::get('/', [AttributeValueController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [AttributeValueController::class, 'create'])->name('create');

        // Store
        Route::post('/', [AttributeValueController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [AttributeValueController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [AttributeValueController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [AttributeValueController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [AttributeValueController::class, 'restore'])->name('restore');
    });

});
