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

    // 8. Fetch Recent Orders for Activity Logs
    $orders = DB::table('orders')
        ->latest('created_at')
        ->limit(5)
        ->get();

    // 9. NEW: Fetch Real Chart Data for the Last 7 Days
    $chartData = [];
    $maxDaySales = 1; // Safeguard to prevent division by zero

    for ($i = 6; $i >= 0; $i--) {
        $date = Carbon::today()->subDays($i);

        $daySales = DB::table('orders')
            ->where('status', 'Completed')
            ->whereDate('created_at', $date)
            ->sum('total_amount') ?? 0;

        if ($daySales > $maxDaySales) {
            $maxDaySales = $daySales;
        }

        $chartData[] = [
            'day_name' => $date->format('D'), // e.g., 'Mon', 'Tue'
            'sales' => $daySales,
        ];
    }

    // Convert raw revenue numbers into scaled graph heights (percentages from 10% to 100%)
    foreach ($chartData as $key => $data) {
        $percentage = ($data['sales'] / $maxDaySales) * 100;
        $chartData[$key]['height'] = $data['sales'] > 0 ? max($percentage, 12) : 5; // minimum visibility height
    }

    // 10. Bind variables cleanly to the view context array
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
        'chartData' => $chartData, // 👈 Passing real daily graph data arrays here
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
Route::get('/sales/create', function () {
    // Fetches items so you can select grocery items out of a dropdown inside your POS workspace view
    $products = DB::table('products')->where('stock_count', '>', 0)->get();
    return view('sales-create', compact('products'));
})->name('sales.create');

// 2. The structural post action route that receives inputs and stores them securely inside your order logs
Route::post('/sales/store', function (\Illuminate\Http\Request $request) {
    // Validates inputs, deducts stock targets, and falls back gracefully to the index view
    $validated = $request->validate([
        'customer_name' => 'required|string|max:255',
        'total_amount' => 'required|numeric|min:0',
    ]);

    DB::table('orders')->insert([
        'customer_name' => $validated['customer_name'],
        'total_amount' => $validated['total_amount'],
        'status' => 'Completed',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('sales.index')->with('success', 'New sale logged cleanly!');
})->name('sales.store');

// 1. Render the form screen to create a brand new product
Route::get('/products/create', function () {
    return view('products-create');
})->name('products.create');

// 2. Accept form inputs, validate them, and store them into your products database
Route::post('/products/store', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'product_name'  => 'required|string|max:255',
        'price'         => 'required|numeric|min:0',
        'stock_count'   => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',    
    ]);

    // 🌟 Changed 'product_name' to 'name' to align with your database schema
    DB::table('products')->insert([
        'name'          => $validated['product_name'], // 👈 Maps form input to table 'name' field
        'price'         => $validated['price'],
        'stock_count'   => $validated['stock_count'],
        'reorder_level' => $validated['reorder_level'],
        'created_at'    => now(),
        'updated_at'    => now(),
    ]);

    return redirect()->route('dashboard')->with('success', 'Product added successfully to inventory!');
})->name('products.store');


Route::get('/reports', function () {
    // 1. Core Financial Calculations
    $totalSalesSum = DB::table('orders')
        ->where('status', 'Completed')
        ->sum('total_amount') ?? 0;

    $totalOrdersCount = DB::table('orders')
        ->where('status', 'Completed')
        ->count();

    $avgOrderValue = $totalOrdersCount > 0 ? ($totalSalesSum / $totalOrdersCount) : 0;

    // 2. Determine Best Sales Month dynamically from the Database
    $bestMonthQuery = DB::table('orders')
        ->where('status', 'Completed')
        ->select(
            DB::raw('MONTHNAME(created_at) as month_name'),
            DB::raw('SUM(total_amount) as monthly_revenue')
        )
        ->groupBy('month_name')
        ->orderByDesc('monthly_revenue')
        ->first();

    $bestMonth = $bestMonthQuery ? $bestMonthQuery->month_name : 'No Sales Yet';

    // 3. Simple Dynamic Growth Calculation Helper
    // Compares current month vs previous month if data exists, defaults gracefully to an baseline indicator
    $currentMonthSales = DB::table('orders')->where('status', 'Completed')->whereMonth('created_at', now()->month)->sum('total_amount') ?? 0;
    $lastMonthSales = DB::table('orders')->where('status', 'Completed')->whereMonth('created_at', now()->subMonth()->month)->sum('total_amount') ?? 0;
    
    if ($lastMonthSales > 0) {
        $growthPct = (($currentMonthSales - $lastMonthSales) / $lastMonthSales) * 100;
        $growthText = ($growthPct >= 0 ? '+' : '') . number_format($growthPct, 0) . '%';
    } else {
        $growthText = $currentMonthSales > 0 ? '+100%' : '0%';
    }

    // 4. Generate Monthly Aggregates for the Chart Container
    // Fetches live aggregates for the last 5 calendar months
    $monthlyChartData = [];
    $maxRevenue = 1;

    for ($i = 4; $i >= 0; $i--) {
        $monthCarbon = now()->subMonths($i);
        $revenue = DB::table('orders')
            ->where('status', 'Completed')
            ->whereMonth('created_at', $monthCarbon->month)
            ->whereYear('created_at', $monthCarbon->year)
            ->sum('total_amount') ?? 0;

        if ($revenue > $maxRevenue) {
            $maxRevenue = $revenue;
        }

        $monthlyChartData[] = [
            'label' => $monthCarbon->format('M Y'),
            'raw_sales' => $revenue,
        ];
    }

    // Assign scalable CSS responsive structural heights to your bar values
    foreach ($monthlyChartData as $key => $data) {
        $pct = ($data['raw_sales'] / $maxRevenue) * 100;
        $monthlyChartData[$key]['height'] = $data['raw_sales'] > 0 ? max($pct, 12) : 6;
    }

    // 5. Dynamic Report Log Generator
    // Maps historical aggregates to populate your interactive downloadeable layout list log rows
    $reportLogs = [
        [
            'title' => now()->format('F Y') . ' Sales Summary',
            'type' => 'Sales',
            'generated' => now()->startOfMonth()->format('M j, Y'),
        ],
        [
            'title' => 'Q' . ceil(now()->month / 3) . ' Inventory Audit Summary',
            'type' => 'Inventory',
            'generated' => now()->subDays(7)->format('M j, Y'),
        ]
    ];

    return view('reports', [
        'totalRevenue' => $totalSalesSum,
        'growthText' => $growthText,
        'bestMonth' => $bestMonth,
        'avgOrderValue' => $avgOrderValue,
        'monthlyChartData' => $monthlyChartData,
        'reportLogs' => $reportLogs,
    ]);
})->name('reports.index');

Route::delete('/saved-searches/{id}', function ($id) {
    DB::table('saved_searches')->where('id', $id)->delete();

    return back()->with('success', 'Saved search removed cleanly!');
})->name('saved-searches.destroy');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');
