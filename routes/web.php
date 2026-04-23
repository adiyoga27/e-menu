<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Kustomer E-Menu
Route::get('/', [FrontController::class, 'index'])->name('front.index');
Route::get('/checkout', [FrontController::class, 'checkoutForm'])->name('front.checkout.form');
Route::post('/checkout', [FrontController::class, 'checkout'])->name('front.checkout');
Route::get('/success/{order}', [FrontController::class, 'success'])->name('front.success');
Route::get('/payment/qris/{order}', [PaymentController::class, 'qris'])->name('payment.qris');
Route::post('/payment/midtrans-webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('categories', CategoryController::class)->names('admin.categories')->except(['create', 'show', 'edit']);
    Route::resource('menus', MenuController::class)->names('admin.menus')->except(['create', 'show', 'edit']);
    
    Route::get('/orders/history', [OrderController::class, 'report'])->name('admin.orders.report');
    Route::get('/orders/export', [OrderController::class, 'exportReport'])->name('admin.orders.report.export');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::get('/orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::put('/orders/{order}/items', [OrderController::class, 'updateItem'])->name('admin.orders.update-item');
    Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
