<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Dashboard universal (akan redirect berdasarkan role)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS route untuk kasir
    Route::get('/pos', [SaleController::class, 'index'])->name('pos.index');

    // API routes untuk dashboard
    Route::get('/dashboard/sales-data', [DashboardController::class, 'getSalesData'])->name('dashboard.sales-data');
    Route::get('/dashboard/top-products', [DashboardController::class, 'getTopProducts'])->name('dashboard.top-products');

    // Admin routes group - Semua route admin dikelola di sini
    Route::middleware('role:owner,admin')->prefix('admin')->name('admin.')->group(function () {
        // Include admin routes dari file terpisah
        require __DIR__.'/admin.php';
    });

    // POS routes group (untuk semua role yang terlibat di POS)
    Route::middleware('role:owner,admin,kasir')->group(function () {
        require __DIR__.'/pos.php';
    });

    // User registration (owner only)
    Route::middleware('role:owner')->group(function () {
        Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
        Route::post('register', [RegisteredUserController::class, 'store']);
    });

    // Fallback untuk admin routes
    Route::get('/admin/{any?}', function () {
        // Selalu arahkan ke admin.dashboard jika URL diawali /admin
        return redirect()->route('admin.dashboard');
    })->where('any', '.*');
});

// Jika menggunakan Laravel UI, tambahkan ini
// Auth::routes();
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
