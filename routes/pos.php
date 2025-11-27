<?php
// routes/pos.php

use App\Http\Controllers\POSController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;

// POS Routes
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [POSController::class, 'index'])->name('index');
    Route::post('/add-to-cart', [POSController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/update-cart', [POSController::class, 'updateCart'])->name('update-cart');
    Route::post('/remove-from-cart', [POSController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::post('/checkout', [POSController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{transaction}', [SaleController::class, 'receipt'])->name('receipt');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/receipt/{id}', [SaleController::class, 'receipt'])->name('receipt');
    Route::get('/products/search', [SaleController::class, 'searchProducts'])->name('products.search');
    Route::get('/products/{id}', [SaleController::class, 'getProduct'])->name('products.get');
    Route::get('/transactions/today', [SaleController::class, 'todayTransactions'])->name('transactions.today');
});

// Dashboard untuk kasir
Route::get('/kasir-dashboard', function () {
    return view('kasir.dashboard');
})->name('kasir.dashboard');

// Transaction history untuk kasir
Route::get('/transactions', function () {
    return view('kasir.transactions');
})->name('transactions.index');
