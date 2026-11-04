<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/dashboard', function () {
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

    // 1. Initialize trend parameters with safe fallback strings upfront
    $salesTrend = 'No sales revenue today';
    $salesDiffText = 'No sales revenue today';
    $salesDiffColor = 'text-muted';

    // 2. Compute Cumulative Completed Sales Revenue Metrics
    $totalSales = DB::table('orders')
        ->where('status', 'Completed')
        ->sum('total_amount') ?? 0;

    $salesToday = DB::table('orders')
        ->where('status', 'Completed')
        ->whereDate('created_at', $today)
        ->sum('total_amount') ?? 0;

    $salesYesterday = DB::table('orders')
        ->where('status', 'Completed')
        ->whereDate('created_at', $yesterday)
        ->sum('total_amount') ?? 0;

    // 3. Process Sales Trends & Visual Color States
    if ($salesYesterday > 0) {
        $salesChangePercentage = (($salesToday - $salesYesterday) / $salesYesterday) * 100;
        $salesTrend = ($salesChangePercentage >= 0 ? '+' : '') . number_format($salesChangePercentage, 1) . '% from yesterday';
        $salesDiffText = $salesTrend;
        $salesDiffColor = $salesChangePercentage >= 0 ? 'text-success' : 'text-danger';
    } elseif ($salesToday > 0) {
        $salesTrend = '₱' . number_format($salesToday, 2) . ' today';
        $salesDiffText = $salesTrend;
        $salesDiffColor = 'text-success';
    }

    // 4. Calculate Order Growth Activity Telemetry
    $ordersToday = DB::table('orders')->whereDate('created_at', $today)->count();
    $ordersYesterday = DB::table('orders')->whereDate('created_at', $yesterday)->count();

    if ($ordersYesterday > 0) {
        $ordersChange = (($ordersToday - $ordersYesterday) / $ordersYesterday) * 100;
        $ordersTrend = ($ordersChange >= 0 ? '+' : '') . number_format($ordersChange, 1) . '% new checkouts';
    } else {
        $ordersTrend = $ordersToday > 0 ? "+{$ordersToday} new checkouts" : "No recent checkouts";
    }

    // 5. Run Low Stock Threshold System Sweeps
    $lowStockCount = DB::table('products')->whereRaw('stock_count <= reorder_level')->count();
    if ($lowStockCount > 0) {
        $stockStatus = "⚠️ {$lowStockCount} items low on stock";
    } else {
        $stockStatus = "✅ All products in stock";
    }

    // 6. Calculate Distinct Unique Customer Footprint Shifting
    $customersToday = DB::table('orders')->whereDate('created_at', $today)->distinct()->count('customer_name');
    $customersYesterday = DB::table('orders')->whereDate('created_at', $yesterday)->distinct()->count('customer_name');

    if ($customersYesterday > 0) {
        $customerChange = (($customersToday - $customersYesterday) / $customersYesterday) * 100;
        $customerTrend = ($customerChange >= 0 ? '+' : '') . number_format($customerChange, 1) . '% active sessions';
    } else {
        $customerTrend = $customersToday > 0 ? "+{$customersToday} active sessions" : "0 active sessions";
    }

    // 7. Fetch Top Products for Leaderboard Iterations
    $topProducts = DB::table('products')
        ->limit(5)
        ->get();

    // 8. Fetch Recent Orders for Activity Logs (MUST BE BEFORE RETURN STATEMENT)
    $orders = DB::table('orders')
        ->latest('created_at')
        ->limit(5)
        ->get();

    // 9. Bind variables cleanly to the view context array
    return view('dashboard', [
        'totalSales' => $totalSales,
        'salesTrend' => $salesTrend,
        'salesDiffText' => $salesDiffText,
        'salesDiffColor' => $salesDiffColor, 
        'ordersToday' => $ordersToday,
        'ordersTrend' => $ordersTrend,
        'totalProducts' => DB::table('products')->count(),
        'lowStockCount' => $lowStockCount,
        'stockStatus' => $stockStatus,
        'activeCustomers' => $customersToday,
        'customerTrend' => $customerTrend,
        'topProducts' => $topProducts,
        'orders' => $orders,
    ]);
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

Route::get('/reports', function () {
    $totalSalesSum = Order::where('status', 'Completed')->sum('total_amount');
    $formattedRevenue = '₱' . number_format($totalSalesSum, 2);
    
    return view('reports', compact('formattedRevenue'));
})->name('reports.index');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
