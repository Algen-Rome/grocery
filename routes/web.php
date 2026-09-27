<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SaleController;
use App\Models\Product;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', function () {
        $products = Product::all();
        return view('pos', compact('products'));
    })->name('dashboard');

    // Secure transactional actions inside your protected authentication ring
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
