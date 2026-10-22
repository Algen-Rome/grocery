<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SaleController;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    
    // Master Dashboard Route
    Route::get('/dashboard', function () {
        // 1. Calculate metrics based on your orders and products table columns
        $totalSales = Order::where('status', 'Completed')->sum('total_amount');
        $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
        $totalProducts = Product::count();
        $activeCustomers = Order::distinct('customer_name')->count('customer_name');
        $lowStockCount = Product::whereRaw('stock_count <- reorder_level')->count();
        
        $orders = Order::latest()->take(5)->get();

        $topProducts = Product::take(3)->get()->map(function($product) {
            $product->product_name = $product->name;
            $product->total_sold = rand(15, 60);    
            return $product;
        });

        return view('pos', compact(
            'totalSales',
            'ordersToday',
            'totalProducts',
            'lowStockCount',
            'activeCustomers',
            'orders',
            'topProducts'
        ));
    })->name('dashboard');

    Route::post('/logout', function () {
        auth()->logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');

    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});
