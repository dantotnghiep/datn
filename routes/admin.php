<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\AttributeValueController;
use App\Http\Controllers\Admin\InventoryReceiptController;
use App\Http\Controllers\Admin\ProductVariationController;
use App\Http\Controllers\Admin\OrderCancellationController;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

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
        Route::get('/edit/{slug}', [ProductController::class, 'edit'])->name('edit');

        // Update
        Route::put('/update/{slug}', [ProductController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/delete/{slug}', [ProductController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/restore/{slug}', [ProductController::class, 'restore'])->name('restore');
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

        // Store attribute value
        Route::post('/values', [AttributeController::class, 'storeValue'])->name('values.store');
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

    // Orders routes
    Route::prefix('orders')->name('orders.')->group(function () {
        // List
        Route::get('/', [OrderController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [OrderController::class, 'create'])->name('create');

        // Store
        Route::post('/', [OrderController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [OrderController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [OrderController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [OrderController::class, 'restore'])->name('restore');

        // Update status
        Route::put('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');

        // Order details
        Route::get('/{id}/details', [OrderController::class, 'details'])->name('details');

        // Cancelled orders
        Route::get('/cancelled', [OrderCancellationController::class, 'index'])->name('cancelled');

        // Approve cancellation request
        Route::post('/cancellation/{id}/approve', [OrderCancellationController::class, 'approve'])->name('cancellation.approve');

        // Reject cancellation request
    });
    // Inventory Receipts routes
    Route::prefix('inventory-receipts')->name('inventory-receipts.')->group(function () {
        // List
        Route::get('/', [InventoryReceiptController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [InventoryReceiptController::class, 'create'])->name('create');

        // Store
        Route::post('/', [InventoryReceiptController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [InventoryReceiptController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [InventoryReceiptController::class, 'update'])->name('update');

        // Soft delete
        Route::delete('/{id}', [InventoryReceiptController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [InventoryReceiptController::class, 'restore'])->name('restore');

        // Update status
        Route::put('/{id}/update-status', [InventoryReceiptController::class, 'updateStatus'])->name('update-status');
    });

    // Promotions routes
    Route::prefix('promotions')->name('promotions.')->group(function () {
        // List
        Route::get('/', [PromotionController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [PromotionController::class, 'create'])->name('create');

        // Store
        Route::post('/', [PromotionController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [PromotionController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [PromotionController::class, 'update'])->name('update');

        // Delete
        Route::delete('/{id}', [PromotionController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [PromotionController::class, 'restore'])->name('restore');
    });
    Route::prefix('auth')->name('auth.')->group(function () {
        // List
        Route::get('/', [AuthController::class, 'index'])->name('index');

        // Create form
        Route::get('/create', [AuthController::class, 'create'])->name('create');

        // Store
        Route::post('/', [AuthController::class, 'store'])->name('store');

        // Edit form
        Route::get('/{id}/edit', [AuthController::class, 'edit'])->name('edit');

        // Update
        Route::put('/{id}', [AuthController::class, 'update'])->name('update');

        // Delete
        Route::delete('/{id}', [AuthController::class, 'destroy'])->name('destroy');

        // Restore from trash
        Route::put('/{id}/restore', [AuthController::class, 'restore'])->name('restore');
    });


});
