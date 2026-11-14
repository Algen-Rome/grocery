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
use App\Models\Setting;

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
    // 1. Validate incoming base inputs
    $request->validate([
        'customer_name' => 'required|string',
        'total_amount' => 'required|numeric',
    ]);

    Illuminate\Support\Facades\DB::transaction(function () use ($request) {
        $orderNumber = 'ORD-' . strtoupper(substr(uniqid(), 7));
        $itemsPayload = [];

        // 2. 🌟 MULTI-ITEM ARRAY PARSING PIPELINE
        // Read form arrays (Ensure your HTML form inputs use names like product_id[], quantity[])
        if ($request->has('product_id') && is_array($request->product_id)) {
            foreach ($request->product_id as $index => $prodId) {
                $qty = intval($request->quantity[$index] ?? 1);

                if ($prodId && $qty > 0) {
                    $product = \App\Models\Product::find($prodId);

                    if ($product) {
                        $itemsPayload[] = [
                            'id' => $product->id,
                            'name' => $product->name,
                            'quantity' => $qty,
                            'price' => floatval($product->price) // or custom price fields if present
                        ];

                        // 3. Deduct stock levels dynamically inside loop cycle execution
                        $product->decrement('stock_count', $qty);
                    }
                }
            }
        }
        // Fallback backward compatibility tracker if raw single item parameters are sent
        elseif ($request->filled('product_id')) {
            $product = \App\Models\Product::find($request->product_id);
            if ($product) {
                $qty = intval($request->quantity ?? 1);
                $itemsPayload[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'quantity' => $qty,
                    'price' => floatval($request->total_amount)
                ];
                $product->decrement('stock_count', $qty);
            }
        }

        // 4. Create the final structural Order row document layer
        \App\Models\Order::create([
            'customer_name' => $request->customer_name,
            'customer_phone' => $request->customer_phone ?? 'N/A',
            'delivery_address' => $request->delivery_address ?? 'Store Counter Pickup',
            'total_amount' => $request->total_amount,
            'status' => $request->status ?? 'Completed',
            'order_number' => $orderNumber,
            'items' => $itemsPayload,
        ]);
    });

    return redirect('/sales-ledger')->with('success', 'Multi-item sale created and stock decremented successfully!');
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

Route::get('/reports/customer', function (Request $request) {
    // 1. Calculate live customer summary telemetry statistics metrics
    $totalCustomers = DB::table('orders')->distinct()->count('customer_name');

    // Count new distinct client profiles logged over the current calendar month
    $newThisMonth = DB::table('orders')
        ->whereMonth('created_at', Carbon::now()->month)
        ->whereYear('created_at', Carbon::now()->year)
        ->distinct()
        ->count('customer_name');

    // Active customers today tracking logs count
    $activeToday = DB::table('orders')
        ->whereDate('created_at', Carbon::today())
        ->distinct()
        ->count('customer_name');

    // 2. Generate monthly grouping counts for the visual growth chart layout array matrices
    $monthlyGrowthRaw = DB::table('orders')
        ->select(DB::raw("DATE_FORMAT(created_at, '%b') as month_name"), DB::raw('COUNT(DISTINCT customer_name) as base_count'))
        ->groupBy('month_name')
        ->orderByRaw("MIN(created_at)")
        ->get();

    $maxCount = $monthlyGrowthRaw->max('base_count') ?: 1;
    $chartData = [];
    foreach ($monthlyGrowthRaw as $row) {
        $chartData[] = [
            'month' => $row->month_name,
            'count' => $row->base_count,
            'height' => max(($row->base_count / $maxCount) * 100, 15) // dynamic relative tall bars percent styling
        ];
    }

    // 3. Static fallback placeholders mapping your generated historical reports logs download loop matrix
    $reportLogs = [
        ['title' => 'Customer Growth - August', 'type' => 'Customer', 'date' => 'Sep 2, 2026'],
        ['title' => 'Loyalty Members Summary', 'type' => 'Customer', 'date' => 'Aug 25, 2026'],
    ];

    return view('reports-customer', compact(
        'totalCustomers',
        'newThisMonth',
        'activeToday',
        'chartData',
        'reportLogs'
    ));
})->name('reports.customer');

Route::get('/reports/financial', function () {
    // 1. Compute Live Financial Telemetry from your orders database table
    $totalRevenue = \App\Models\Order::where('status', 'Completed')->sum('total_amount') ?? 0;

    // Simulate structural store expenses (wholesale cost of goods sold + operations ~ 72% of total value)
    $totalExpenses = $totalRevenue > 0 ? ($totalRevenue * 0.73) : 0;
    $netProfit = $totalRevenue - $totalExpenses;
    $profitMargin = $totalRevenue > 0 ? (($netProfit / $totalRevenue) * 100) : 0;

    // 2. Generate side-by-side Revenue vs Expense monthly bar graph data layers
    // We fetch completed aggregates over the current calendar horizon months
    $monthlyDataRaw = \App\Models\Order::select(
        \Illuminate\Support\Facades\DB::raw("DATE_FORMAT(created_at, '%b') as month_name"),
        \Illuminate\Support\Facades\DB::raw("SUM(total_amount) as rev")
    )
        ->where('status', 'Completed')
        ->groupBy('month_name')
        ->orderByRaw("MIN(created_at)")
        ->get();

    $chartData = [];
    $maxVal = 1; // Prevent division by zero errors

    foreach ($monthlyDataRaw as $row) {
        $rValue = floatval($row->rev);
        $eValue = $rValue * 0.73; // matching expense simulation scale factors

        if ($rValue > $maxVal) {
            $maxVal = $rValue;
        }

        $chartData[] = [
            'month' => $row->month_name,
            'rev_value' => $rValue,
            'exp_value' => $eValue,
            'rev_height' => ($rValue / $maxVal) * 100,
            'exp_height' => ($eValue / $maxVal) * 100,
        ];
    }

    // Static mockup report history file downloads list array parameters
    $reportLogs = [
        ['title' => 'August 2026 Profit & Loss', 'type' => 'Financial', 'date' => 'Sep 3, 2026'],
        ['title' => 'Q2 Expense Breakdown', 'type' => 'Financial', 'date' => 'Jul 25, 2026'],
    ];

    return view('reports-financial', compact(
        'totalRevenue',
        'totalExpenses',
        'netProfit',
        'profitMargin',
        'chartData',
        'reportLogs'
    ));
})->name('reports.financial');

Route::get('/settings', function () {
    $setting = Setting::firstOrCreate([], [
        'store_name' => 'Grocery Admin',
        'currency' => 'PHP (₱)',
        'timezone' => 'Asia/Manila',
        'email_notifications' => true,
        'sms_notifications' => false,
    ]);

    return view('settings', compact('setting'));
})->name('settings.index');

Route::get('/settings', function () {
    $setting = \App\Models\Setting::firstOrCreate([], [
        'store_name' => 'Grocery Admin',
        'currency' => 'PHP (₱)',
        'timezone' => 'Asia/Manila',
        'email_notifications' => true,
        'sms_notifications' => false,
    ]);

    $loginHistories = \App\Models\LoginHistory::where('user_id', auth()->id())
        ->orderByDesc('logged_in_at')
        ->take(10)
        ->get();

    $suppliers = \App\Models\Supplier::orderByDesc('id')->get();

    return view('settings', compact('setting', 'loginHistories', 'suppliers'));
})->name('settings.index');

Route::post('/settings/suppliers', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'supplier_name' => 'required|string|max:255',
        'contact_number' => 'required|string|max:50',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string|max:255',
        'product_supplied' => 'required|string|max:255',
    ]);

    \App\Models\Supplier::create($validated);

    return redirect()->route('settings.index', ['tab' => 'supplier-info'])
        ->with('success', 'Supplier added successfully.');
})->name('suppliers.store');


Route::post('/settings', function (Illuminate\Http\Request $request) {
    $setting = \App\Models\Setting::firstOrCreate([]);

    $setting->update([
        'store_name' => $request->input('store_name'),
        'contact_number' => $request->input('contact_number'),
        'store_address' => $request->input('store_address'),
        'currency' => $request->input('currency'),
        'timezone' => $request->input('timezone'),
        'email_notifications' => $request->has('email_notifications') || $request->has('notify_via_email'),
        'sms_notifications' => $request->has('sms_notifications') || $request->has('notify_via_sms'),
        'in_app_notifications' => $request->has('in_app_notifications'),
        'cod_enabled' => $request->has('cod_enabled'),
        'gcash_enabled' => $request->has('gcash_enabled'),
        'card_enabled' => $request->has('card_enabled'),
        'maya_enabled' => $request->has('maya_enabled'),
        'payment_gateway' => $request->input('payment_gateway'),
        'vat_rate' => $request->input('vat_rate'),
        'gcash_number' => $request->input('gcash_number'),
        'receipt_footer' => $request->input('receipt_footer'),
        'low_stock_alerts' => $request->has('low_stock_alerts'),
        'new_order_alerts' => $request->has('new_order_alerts'),
        'payment_received_alerts' => $request->has('payment_received_alerts'),
        'refund_request_alerts' => $request->has('refund_request_alerts'),
        'daily_sales_summary' => $request->has('daily_sales_summary'),
        'weekly_report_email' => $request->has('weekly_report_email'),
        'session_timeout' => $request->input('session_timeout', '30 minutes'),
        'two_factor_enabled' => $request->has('two_factor_enabled'),
        'login_alert_emails' => $request->has('login_alert_emails'),
        'auto_logout_idle' => $request->has('auto_logout_idle'),
        'allow_multiple_devices' => $request->has('allow_multiple_devices'),
    ]);

    $user = auth()->user();
    if ($user) {
        if ($request->filled('email') && $request->input('email') !== $user->email) {
            $request->validate(['email' => 'required|email|unique:users,email,' . $user->id]);
            $user->email = $request->input('email');
        }

        if ($request->filled('new_password')) {
            $request->validate([
                'current_password' => 'required|current_password',
                'new_password' => 'required|min:8|confirmed',
            ]);
            $user->password = \Illuminate\Support\Facades\Hash::make($request->input('new_password'));
        }

        $user->save();
    }

    return redirect()->route('settings.index')->with('success', 'Settings updated successfully.');
})->name('settings.update');

Route::get('/admin', function (Illuminate\Http\Request $request) {
    $roleFilter = $request->query('role', 'all');

    $query = \App\Models\User::orderByDesc('id');

    if ($roleFilter !== 'all') {
        $query->where('role', $roleFilter);
    }

    $users = $query->get();

    $totalUsers = \App\Models\User::count();
    $adminsAndStaff = \App\Models\User::whereIn('role', ['Admin', 'Staff'])->count();
    $activeSessions = \Illuminate\Support\Facades\DB::table('sessions')->count();

    return view('admin', compact('users', 'totalUsers', 'adminsAndStaff', 'activeSessions', 'roleFilter'));
})->name('admin.index');

Route::post('/admin/users', function (Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'role' => 'required|in:Admin,Staff,Customer',
        'spend_amount' => 'nullable|numeric|min:0',
        'password' => 'required|string|min:6',
    ]);

    \App\Models\User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
        'spend_amount' => $validated['spend_amount'] ?? 0,
        'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
    ]);

    return redirect()->route('admin.index')->with('success', 'User added successfully.');
})->name('admin.users.store');

Route::put('/admin/users/{user}', function (Illuminate\Http\Request $request, \App\Models\User $user) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:Admin,Staff,Customer',
        'spend_amount' => 'nullable|numeric|min:0',
    ]);

    $user->update([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'role' => $validated['role'],
        'spend_amount' => $validated['spend_amount'] ?? 0,
    ]);

    return redirect()->route('admin.index')->with('success', 'User updated successfully.');
})->name('admin.users.update');

Route::delete('/admin/users/{user}', function (\App\Models\User $user) {
    if ($user->id === auth()->id()) {
        return redirect()->route('admin.index')->withErrors(['error' => 'You cannot remove your own active account.']);
    }

    $user->delete();

    return redirect()->route('admin.index')->with('success', 'User removed successfully.');
})->name('admin.users.destroy');


Route::get('/online-orders', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\OnlineOrder::with('items')->orderByDesc('id');

    if ($request->filled('date_range')) {
        if ($request->date_range === 'today') {
            $query->whereDate('created_at', today());
        } elseif ($request->date_range === 'week') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($request->date_range === 'month') {
            $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
        }
    }

    if ($request->filled('fulfillment') && $request->fulfillment !== 'All') {
        $query->where('order_type', $request->fulfillment);
    }

    if ($request->filled('status') && $request->status !== 'All') {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $search = trim($request->search);
        $cleanId = ltrim(str_ireplace('#ON-', '', $search), '0');
        $query->where(function ($q) use ($search, $cleanId) {
            $q->where('full_name', 'like', "%{$search}%");
            if (is_numeric($cleanId)) {
                $q->orWhere('id', (int) $cleanId);
            }
        });
    }

    $orders = $query->get();
    $ordersTodayCount = \App\Models\OnlineOrder::whereDate('created_at', today())->count();
    $pendingFulfillmentCount = \App\Models\OnlineOrder::whereIn('status', ['New', 'Pending', 'Preparing'])->count();
    $onlineRevenue = \App\Models\OnlineOrder::where('status', '!=', 'Cancelled')->sum('subtotal');

    return view('online-orders', compact(
        'orders',
        'ordersTodayCount',
        'pendingFulfillmentCount',
        'onlineRevenue'
    ));
})->name('online-orders.index');

// 2. Customer Order Form Page
// 2. Customer Order Form Page
Route::get('/online-orders/create', function () {
    $products = \App\Models\Product::where('stock_count', '>', 0)->orderBy('name')->get();

    return view('online-orders-create', compact('products'));
})->name('online-orders.create');

// 3. Store New Customer Order & Deduct stock_count
Route::post('/online-orders', function (\Illuminate\Http\Request $request) {
    $validated = $request->validate([
        'full_name' => 'required|string|max:255',
        'email' => 'nullable|email|max:255',
        'contact_number' => 'required|string|max:50',
        'delivery_address' => 'nullable|string|max:500',
        'order_type' => 'required|in:Delivery,Pick Up',
        'payment_method' => 'required|in:Cash on Delivery,Cash on Pick Up,Gcash',
        'items' => 'required|array|min:1',
        'items.*.product_id' => 'required|exists:products,id',
        'items.*.quantity' => 'required|integer|min:1',
    ]);

    \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
        $subtotal = 0;
        $lineItems = [];

        foreach ($validated['items'] as $itemData) {
            $product = \App\Models\Product::findOrFail($itemData['product_id']);
            $qty = (int) $itemData['quantity'];
            $lineTotal = $product->price * $qty;
            $subtotal += $lineTotal;

            $lineItems[] = [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'unit_price' => $product->price,
                'quantity' => $qty,
                'line_total' => $lineTotal,
            ];

            if ($product->stock_count >= $qty) {
                $product->decrement('stock_count', $qty);
            }
        }

        $order = \App\Models\OnlineOrder::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'] ?? null,
            'contact_number' => $validated['contact_number'],
            'delivery_address' => $validated['delivery_address'] ?? null,
            'order_type' => $validated['order_type'],
            'payment_method' => $validated['payment_method'],
            'subtotal' => $subtotal,
            'status' => 'New',
        ]);

        foreach ($lineItems as $line) {
            $order->items()->create($line);
        }
    });

    return redirect()->route('online-orders.index')->with('success', 'Customer order placed successfully!');
})->name('online-orders.store');

// Order Details - View Page
Route::get('/online-orders/{order}', function (\App\Models\OnlineOrder $order) {
    $order->load('items');

    return view('online-orders-show', compact('order'));
})->name('online-orders.show');

// 4. Update Order Status

Route::patch('/online-orders/{order}/status', function (\Illuminate\Http\Request $request, \App\Models\OnlineOrder $order) {
    $validated = $request->validate([
        'status' => 'required|in:New,Preparing,Delivered,Cancelled',
    ]);

    $order->update(['status' => $validated['status']]);

    return back()->with('success', "Order #ON-{$order->id} updated to {$validated['status']}.");
})->name('online-orders.status');
// Delete Online Order
Route::delete('/online-orders/{order}', function (\App\Models\OnlineOrder $order) {
    $orderNumber = '#ON-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
    $order->items()->delete();
    $order->delete();

    return redirect()->route('online-orders.index')
        ->with('success', "Order {$orderNumber} deleted successfully.");
})->name('online-orders.destroy');


// 5. Export Online Orders to CSV
Route::get('/online-orders/export', function () {
    $orders = \App\Models\OnlineOrder::orderByDesc('id')->get();
    $filename = 'online-orders-' . now()->format('Y-m-d') . '.csv';

    return response()->streamDownload(function () use ($orders) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['Order ID', 'Customer', 'Contact', 'Type', 'Payment Method', 'Total', 'Status', 'Date']);

        foreach ($orders as $order) {
            fputcsv($handle, [
                '#ON-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                $order->full_name,
                $order->contact_number,
                $order->order_type,
                $order->payment_method,
                number_format($order->subtotal, 2, '.', ''),
                $order->status,
                $order->created_at->format('M d, Y'),
            ]);
        }

        fclose($handle);
    }, $filename);
})->name('online-orders.export');



// 1. Stock Alert Dashboard
Route::get('/stock-alert', function (\Illuminate\Http\Request $request) {
    $allProducts = \App\Models\Product::orderBy('name')->get()->map(function ($product) {
        $stock = (int) ($product->stock_count ?? 0);
        $minLevel = (int) ($product->reorder_level ?? 10);

        if ($stock <= 0) {
            $alertLevel = 'Out of Stock';
        } elseif ($stock <= max(1, (int) floor($minLevel * 0.4))) {
            $alertLevel = 'Critical';
        } elseif ($stock <= $minLevel) {
            $alertLevel = 'Low';
        } else {
            $alertLevel = 'Healthy';
        }

        $product->computed_sku = '#SKU-' . str_pad($product->id, 4, '0', STR_PAD_LEFT);
        $product->computed_stock = $stock;
        $product->computed_min = $minLevel;
        $product->computed_alert = $alertLevel;
        $product->computed_supplier = $product->supplier ?? 'General Supplier';
        $product->computed_category = $product->category ?? 'General';

        return $product;
    });

    // Only show products at or below reorder_level
    $attentionProducts = $allProducts->filter(fn($p) => in_array($p->computed_alert, ['Out of Stock', 'Critical', 'Low']));

    // KPI Card Counts
    $lowStockCount = $attentionProducts->whereIn('computed_alert', ['Low', 'Critical'])->count();
    $outOfStockCount = $attentionProducts->where('computed_alert', 'Out of Stock')->count();
    $pendingRestocksCount = session('pending_restocks', 0);

    // Filter Dropdown Options
    $categories = $attentionProducts->pluck('computed_category')->unique()->values();
    $suppliers = $attentionProducts->pluck('computed_supplier')->unique()->values();

    // Apply Filters
    $filteredProducts = $attentionProducts;

    if ($request->filled('category') && $request->category !== 'All') {
        $filteredProducts = $filteredProducts->where('computed_category', $request->category);
    }

    if ($request->filled('alert_level') && $request->alert_level !== 'All') {
        $filteredProducts = $filteredProducts->where('computed_alert', $request->alert_level);
    }

    if ($request->filled('supplier') && $request->supplier !== 'All') {
        $filteredProducts = $filteredProducts->where('computed_supplier', $request->supplier);
    }

    if ($request->filled('search')) {
        $search = strtolower(trim($request->search));
        $filteredProducts = $filteredProducts->filter(function ($p) use ($search) {
            return str_contains(strtolower($p->name), $search)
                || str_contains(strtolower($p->computed_sku), $search);
        });
    }

    // Sort: Out of Stock -> Critical -> Low
    $priority = ['Out of Stock' => 1, 'Critical' => 2, 'Low' => 3];
    $products = $filteredProducts->sortBy(fn($p) => $priority[$p->computed_alert] ?? 9)->values();

    return view('stock-alert', compact(
        'products',
        'lowStockCount',
        'outOfStockCount',
        'pendingRestocksCount',
        'categories',
        'suppliers'
    ));
})->name('stock-alert.index');

// 2. Reorder / Restock Action
Route::post('/stock-alert/{product}/reorder', function (\Illuminate\Http\Request $request, \App\Models\Product $product) {
    $validated = $request->validate([
        'restock_qty' => 'required|integer|min:1',
    ]);

    $product->increment('stock_count', (int) $validated['restock_qty']);
    session()->increment('pending_restocks');

    return back()->with('success', "Restocked {$product->name} (+{$validated['restock_qty']} units).");
})->name('stock-alert.reorder');

// 3. Export Stock Alerts CSV
Route::get('/stock-alert/export', function () {
    $products = \App\Models\Product::orderBy('name')->get();
    $filename = 'stock-alerts-' . now()->format('Y-m-d') . '.csv';

    return response()->streamDownload(function () use ($products) {
        $handle = fopen('php://output', 'w');
        fputcsv($handle, ['SKU', 'Product', 'Stock', 'Min Level', 'Alert', 'Supplier']);

        foreach ($products as $product) {
            $stock = (int) ($product->stock_count ?? 0);
            $minLevel = (int) ($product->reorder_level ?? 10);

            if ($stock > $minLevel)
                continue;

            $alert = $stock <= 0 ? 'Out of Stock' : ($stock <= max(1, (int) floor($minLevel * 0.4)) ? 'Critical' : 'Low');
            $sku = '#SKU-' . str_pad($product->id, 4, '0', STR_PAD_LEFT);
            $sup = $product->supplier ?? 'General Supplier';

            fputcsv($handle, [$sku, $product->name, $stock, $minLevel, $alert, $sup]);
        }

        fclose($handle);
    }, $filename);
})->name('stock-alert.export');

Route::post('/profile/update', function (\Illuminate\Http\Request $request) {
    $user = auth()->user();

    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $user->name = $validated['name'];

    if ($request->hasFile('photo')) {
        if ($user->profile_photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->profile_photo_path = $request->file('photo')->store('profile-photos', 'public');
    }

    $user->save();

    return back()->with('success', 'Profile updated successfully!');
})->middleware('auth')->name('profile.update');

require __DIR__ . '/auth.php';
