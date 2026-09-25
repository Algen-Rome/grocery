<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SaleController;
use App\Models\Product;


Route::get('/', function () {
    $products = Product::all();
    return view('pos', compact('products'));
})->name('home');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';

Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');