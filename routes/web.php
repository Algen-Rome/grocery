<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $totalSales = Order::where('status', 'Completed')->sum('total_amount');
    $ordersToday = Order::whereDate('created_at', Carbon::today())->count();
    $totalProducts = Product::count();
    $activeCustomers = Order::distinct('customer_name')->count('customer_name');
    $lowStockCount = Product::whereRaw('stock_count <= reorder_level')->count();
    
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

Route::get('/sales-ledger', function () {
    $totalRevenue = Order::where('status', 'Completed')->sum('total_amount');
    $totalOrdersCount = Order::count();
    $avgOrderValue = $totalOrdersCount > 0 ? ($totalRevenue / $totalOrdersCount) : 0;
    $allSales = Order::latest()->get();

    return view('sales', compact(
        'totalRevenue', 
        'totalOrdersCount', 
        'avgOrderValue', 
        'allSales'
    ));
})->name('sales.index');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
