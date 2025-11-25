<?php
// routes/pos.php

use App\Http\Controllers\POSController;
use Illuminate\Support\Facades\Route;

// POS Routes
Route::prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [POSController::class, 'index'])->name('index');
    Route::post('/add-to-cart', [POSController::class, 'addToCart'])->name('add-to-cart');
    Route::post('/update-cart', [POSController::class, 'updateCart'])->name('update-cart');
    Route::post('/remove-from-cart', [POSController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::post('/checkout', [POSController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{transaction}', [POSController::class, 'receipt'])->name('receipt');
});

// Dashboard untuk kasir
Route::get('/kasir-dashboard', function () {
    return view('kasir.dashboard');
})->name('kasir.dashboard');

// Transaction history untuk kasir
Route::get('/transactions', function () {
    return view('kasir.transactions');
})->name('transactions.index');
