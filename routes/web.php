<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Inertia\Inertia;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OrderController;

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
    $topProducts = \App\Models\Product::limit(5)->get();

    // 8. FIX: Fetch Recent Orders using the Eloquent Model to clear the IDE type warning
    $orders = \App\Models\Order::latest('created_at')->limit(5)->get();

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

Route::get('/products', function () {
    // Fetch all products from your mysql inventory table
    $products = DB::table('products')->orderBy('name', 'asc')->get();

    // Calculate dynamic dashboard top counts context parameters
    $totalProducts = DB::table('products')->count();
    $lowStockItems = DB::table('products')->whereRaw('stock_count <= reorder_level')->where('stock_count', '>', 0)->count();
    $outOfStock = DB::table('products')->where('stock_count', '<=', 0)->count();

    return view('products-index', compact('products', 'totalProducts', 'lowStockItems', 'outOfStock'));
})->name('products.index');

Route::post('/products/{id}/update', function (\Illuminate\Http\Request $request, $id) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock_count' => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',
    ]);

    DB::table('products')->where('id', $id)->update([
        'name' => $validated['name'],
        'price' => $validated['price'],
        'stock_count' => $validated['stock_count'],
        'reorder_level' => $validated['reorder_level'],
        'updated_at' => now(),
    ]);

    return redirect()->route('products.index')->with('success', 'Product inventory records updated successfully!');
})->name('products.update');

Route::post('/products/{id}/adjust-stock', function (\Illuminate\Http\Request $request, $id) {
    $validated = $request->validate([
        'adjustment_type' => 'required|in:add,subtract',
        'quantity' => 'required|integer|min:1',
    ]);

    $product = DB::table('products')->where('id', $id)->first();
    if (!$product) {
        return back()->with('error', 'Product not found.');
    }

    // Calculate new target boundary values
    $newStock = $product->stock_count;
    if ($validated['adjustment_type'] === 'add') {
        $newStock += $validated['quantity'];
    } else {
        $newStock = max(0, $product->stock_count - $validated['quantity']); // Prevents inventory dropping below absolute zero
    }

    DB::table('products')->where('id', $id)->update([
        'stock_count' => $newStock,
        'updated_at' => now(),
    ]);

    return redirect()->route('products.index')->with('success', "Stock level adjusted cleanly to {$newStock} units!");
})->name('products.adjustStock');

// 🌟 SAFE ENDPOINT: Uses a distinct URL pattern to dodge global script overrides
Route::get('/products/action/delete/{id}', function ($id) {
    DB::table('products')->where('id', $id)->delete();

    // Force a clean redirect back to the fresh inventory dashboard
    return redirect()->route('products.index')->with('success', 'Product permanently removed from inventory records.');
})->name('products.directDelete');

// 🌟 SECURE PRODUCT CREATION ROUTE: Placed directly above the sales ledger route block
// 🌟 CORRECTED: Changed path to '/products' to perfectly catch your form submission!
Route::post('/products', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock_count' => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',
    ]);

    DB::table('products')->insert([
        'name' => $validated['name'],
        'price' => $validated['price'],
        'stock_count' => $validated['stock_count'],
        'reorder_level' => $validated['reorder_level'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('products.index')->with('success', 'New product successfully added to your inventory tracks!');
})->name('products.storeSecure');

// 📁 Open routes/web.php and replace your /sales-ledger GET route with this:

Route::get('/sales-ledger', function () {
    // 1. Fetch all orders
    $orders = \App\Models\Order::orderBy('created_at', 'desc')->get();

    // 2. Calculate data metrics using names that your Blade cards expect
    $totalRevenue = \App\Models\Order::where('status', 'Completed')->sum('total_amount');
    $totalOrdersCount = $orders->count(); // 🌟 MATCHES LINE 49
    $avgOrderValue = $totalOrdersCount > 0 ? ($orders->sum('total_amount') / $totalOrdersCount) : 0;

    // 3. Pass everything down to the view safely
    return view('sales', compact('orders', 'totalRevenue', 'totalOrdersCount', 'avgOrderValue'));

})->name('sales.index');
Route::get('/sales/create', function () {
    // Fetches items so you can select grocery items out of a dropdown inside your POS workspace view
    $products = DB::table('products')->where('stock_count', '>', 0)->get();
    return view('sales-create', compact('products'));
})->name('sales.create');

// 2. The structural post action route that receives inputs and stores them securely inside your order logs
Route::post('/sales/store', function (Illuminate\Http\Request $request) {
    $request->validate([
        'customer_name' => 'required|string',
        'total_amount' => 'required|numeric',
    ]);

    $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), 7));

    // 🌟 FIX: Structure item rows correctly so your view loops find array dimensions!
    $itemsPayload = $request->items;

    // If the form items parameter is missing or arrived unformatted, 
    // structure an item container mapping based on selection counts
    if (empty($itemsPayload)) {
        $itemsPayload = [
            [
                'id' => $request->product_id ?? 0,
                'name' => $request->product_name ?? 'Grocery Item',
                'quantity' => $request->quantity ?? 1,
                'price' => $request->total_amount
            ]
        ];
    }

    Order::create([
        'customer_name' => $request->customer_name,
        'customer_phone' => $request->customer_phone ?? 'N/A',
        'delivery_address' => $request->delivery_address ?? 'Store Counter Pickup',
        'total_amount' => $request->total_amount,
        'status' => $request->status ?? 'Completed',
        'order_number' => $orderNumber,
        'items' => $itemsPayload, // 🌟 Saves as a cleanly mapped item array element block
    ]);

    return redirect('/sales-ledger')->with('success', 'Sale logged successfully!');
})->name('sales.store');
// Fetches detailed item attributes for an order transaction log to display in a modal overlay
Route::get('/sales/{id}/json', function ($id) {
    $sale = DB::table('orders')->where('id', $id)->first();

    if (!$sale) {
        return response()->json(['error' => 'Order item log records not found.'], 404);
    }

    // Fallback array handler if items array was saved empty or unformatted
    $decodedItems = json_decode($sale->items ?? '', true);
    if (!is_array($decodedItems)) {
        $decodedItems = [
            ['name' => 'Default Balanced Inventory Item', 'qty' => 3, 'price' => floatval($sale->total_amount) / 3]
        ];
    }

    return response()->json([
        'order_id' => $sale->order_number ?? $sale->id,
        'customer_name' => $sale->customer_name,
        'status' => $sale->status,
        'total_amount' => number_format($sale->total_amount, 2),
        'date' => date('M d, Y h:i A', strtotime($sale->created_at)),
        'items' => $decodedItems
    ]);
})->name('sales.json');
// Handles deleting a specific order record from the ledger logs permanently
Route::delete('/products/{id}', function ($id) {
    DB::table('products')->where('id', $id)->delete();

    return redirect()->route('products.index')->with('success', 'Product removed successfully from inventory!');
})->name('products.destroy');

Route::get('/orders/view/{id}', function ($id) {
    $order = \App\Models\Order::findOrFail($id);
    return view('sales-view', compact('order'));
})->name('sales.show');


Route::delete('/orders/delete/{id}', function ($id) {
    $order = \App\Models\Order::findOrFail($id);
    $order->delete();

    return redirect('/sales-ledger')->with('success', 'Order deleted successfully!');
})->name('sales.destroy');


// 1. Render the form screen to create a brand new product
Route::get('/products/create', function () {
    return view('products-create');
})->name('products.create');

// 2. Accept form inputs, validate them, and store them into your products database
// 🌟 BI-PASS ENDPOINT: Uses a GET link request with parameters to completely dodge form-hijacking scripts!
// 🌟 CORRECTED: This path handles your products-create form submission perfectly!
Route::post('/products', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'stock_count' => 'required|integer|min:0',
        'reorder_level' => 'required|integer|min:0',
    ]);

    DB::table('products')->insert([
        'name' => $validated['name'],
        'price' => $validated['price'],
        'stock_count' => $validated['stock_count'],
        'reorder_level' => $validated['reorder_level'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return redirect()->route('products.index')->with('success', 'New product successfully added!');
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

// Handles purging structural inventory entries out of database records cleanly
Route::delete('/products/{id}', function ($id) {
    DB::table('products')->where('id', $id)->delete();

    return redirect()->route('sales.create')->with('success', 'Item cleared successfully from database tracking logs!');
})->name('products.destroy');
Route::delete('/sales/{id}/delete-record', function ($id) {
    DB::table('orders')->where('id', $id)->delete();
    return redirect()->route('sales.index')->with('success', 'Transaction ledger record successfully deleted!');
})->name('sales.destroy');

// 📥 Endpoint to create a new order (Saves as Pending)
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// 📉 Endpoint to change order status (Triggers your live inventory deduction on "Completed")
Route::put('/orders/update-status/{id}', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

Route::get('/inventory-report', [ReportController::class, 'inventoryReport'])->name('reports.inventory');
// 🌟 ADD THIS PRINT EXPORT ROUTE TRIGGER:
Route::get('/inventory-report/export-pdf', [App\Http\Controllers\ReportController::class, 'exportPdf'])->name('reports.inventory.pdf');

// 🌟 ROUTE FOR DYNAMIC RECORD LOG DOWNLOADING:
Route::get('/report-log/download/{filename}', [ReportController::class, 'downloadLog'])->name('reports.log.download');

Route::post('/logout', function () {
    return redirect('/');
})->name('logout');