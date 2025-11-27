<?php
// routes/admin.php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\ReportController;
use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// User management (owner only)
Route::middleware('role:owner')->group(function () {
    Route::resource('users', UserController::class)->except([]);
    Route::patch('users/{user}/restore', [UserController::class, 'restore'])->name('users.restore');
    Route::delete('users/{user}/force-delete', [UserController::class, 'forceDelete'])->name('users.force-delete');
});

// Product management
Route::resource('products', ProductController::class);
Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
Route::post('products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
Route::get('products-search', [ProductController::class, 'searchProducts'])->name('products.search');

// Category management
Route::resource('categories', CategoryController::class);

// Supplier management
Route::resource('suppliers', SupplierController::class);

// Reports
Route::prefix('reports')->name('reports.')->group(function () {
    Route::get('/sales', [ReportController::class, 'sales'])->name('sales');
    Route::get('/products', [ReportController::class, 'products'])->name('products');
    Route::get('/stock', [ReportController::class, 'stock'])->name('stock');
    Route::get('/export-sales', [ReportController::class, 'exportSales'])->name('export-sales');
});

// Settings
Route::get('/settings', function () {
    return view('admin.settings');
})->name('settings');
