<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Barryvdh\DomPDF\Facade\Pdf; // 🌟 MANDATORY FOR GENERATING PRINTS
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Track A: Sales Report (Revenue Dashboard Overview)
     */
    public function index()
    {
        // 1. Calculate Summary Aggregates
        $totalRevenue = DB::table('orders')->where('status', 'Completed')->sum('total_amount') ?? 0;
        $growthPercent = 18; 
        $bestMonth = 'August';
        
        $orderCount = DB::table('orders')->where('status', 'Completed')->count();
        $avgOrderValue = $orderCount > 0 ? ($totalRevenue / $orderCount) : 0;

        // 2. Fetch Chart Heights & Labels
        $monthlyChartData = [
            ['label' => 'Jul 2026', 'height' => 30, 'raw_sales' => 12000],
            ['label' => 'Aug 2026', 'height' => 45, 'raw_sales' => 18500],
            ['label' => 'Sep 2026', 'height' => 35, 'raw_sales' => 14000],
            ['label' => 'Oct 2026', 'height' => 50, 'raw_sales' => 21000],
            ['label' => 'Nov 2026', 'height' => 85, 'raw_sales' => 34500],
        ];

        // 3. Document Download Lists
        $reportLogs = [
            ['title' => 'August 2026 Sales Summary', 'type' => 'Sales', 'generated' => 'Sep 1, 2026'],
            ['title' => 'Q3 Inventory Audit', 'type' => 'Inventory', 'generated' => 'Aug 28, 2026'],
        ];

        return view('report', compact(
            'totalRevenue',
            'growthPercent',
            'bestMonth',
            'avgOrderValue',
            'monthlyChartData',
            'reportLogs'
        ));
    }

    /**
     * Track B: Inventory Report (Warehouse Stock Overview)
     */
    public function inventoryReport()
    {
        // 1. Calculate KPI Summary Card Counters using live tables
        $totalProducts = DB::table('products')->count();

        $lowStockItems = DB::table('products')
            ->whereRaw('stock_count <= reorder_level')
            ->where('stock_count', '>', 0)
            ->count();

        $outOfStock = DB::table('products')
            ->where('stock_count', '<=', 0)
            ->count();

        $rawStockValue = DB::table('products')->sum(DB::raw('stock_count * price')) ?? 0;
        
        if ($rawStockValue >= 1000000) {
            $stockValueDisplay = '₱' . number_format($rawStockValue / 1000000, 1) . 'M';
        } else {
            $stockValueDisplay = '₱' . number_format($rawStockValue, 2);
        }

        // 2. Dynamic category grouping by analyzing item names live
        $allProducts = DB::table('products')->select('name', 'stock_count')->get();

        $categoryStocks = [
            'Fruits & Vegetables' => 0,
            'Dairy'               => 0,
            'Meat & Seafood'      => 0,
            'Beverages'           => 0,
            'Pantry & Canned'     => 0,
        ];

        foreach ($allProducts as $product) {
            $name = strtolower($product->name);
            $qty = intval($product->stock_count);

            if (str_contains($name, 'banana') || str_contains($name, 'apple') || str_contains($name, 'mango') || str_contains($name, 'potato') || str_contains($name, 'onion')) {
                $categoryStocks['Fruits & Vegetables'] += $qty;
            } elseif (str_contains($name, 'milk') || str_contains($name, 'cheese') || str_contains($name, 'butter') || str_contains($name, 'yogurt')) {
                $categoryStocks['Dairy'] += $qty;
            } elseif (str_contains($name, 'hotdog') || str_contains($name, 'chicken') || str_contains($name, 'pork') || str_contains($name, 'beef') || str_contains($name, 'fish')) {
                $categoryStocks['Meat & Seafood'] += $qty;
            } elseif (str_contains($name, 'soda') || str_contains($name, 'juice') || str_contains($name, 'coke') || str_contains($name, 'coffee') || str_contains($name, 'water')) {
                $categoryStocks['Beverages'] += $qty;
            } else {
                $categoryStocks['Pantry & Canned'] += $qty;
            }
        }

        // 3. Document Download Lists
        $reportLogs = [
            ['report' => 'Low Stock Alert Report', 'type' => 'Inventory', 'generated' => 'Sep 10, 2026'],
            ['report' => 'Q3 Inventory Audit', 'type' => 'Inventory', 'generated' => 'Aug 28, 2026']
        ];

        return view('inventory-report', compact(
            'totalProducts',
            'lowStockItems',
            'outOfStock',
            'stockValueDisplay',
            'categoryStocks',
            'reportLogs'
        ));
    }

    /**
     * Track C: Export Inventory PDF
     */
    public function exportPdf()
    {
        $totalProducts = DB::table('products')->count();

        $lowStockItems = DB::table('products')
            ->whereRaw('stock_count <= reorder_level')
            ->where('stock_count', '>', 0)
            ->count();

        $outOfStock = DB::table('products')
            ->where('stock_count', '<=', 0)
            ->count();

        $rawStockValue = DB::table('products')->sum(DB::raw('stock_count * price')) ?? 0;
        $stockValueDisplay = '₱' . number_format($rawStockValue, 2);

        $inventoryItems = DB::table('products')->orderBy('name', 'asc')->get();
        $generatedAt = now()->format('M d, Y h:i A');

        $pdf = Pdf::loadView('exports.inventory-pdf', compact(
            'totalProducts',
            'lowStockItems',
            'outOfStock',
            'stockValueDisplay',
            'inventoryItems',
            'generatedAt'
        ));

        return $pdf->download('inventory-report-' . now()->format('Y-m-d') . '.pdf');
    }
        /**
     * Compile a live database CSV spreadsheet file block and stream it dynamically.
     */
    public function downloadLog($filename)
    {
        // Clean up the parameter input strings into clean identifiers
        $slug = Str::slug($filename);
        
        // Headers forcing the web browser window to treat the server stream path as an Excel CSV file download
        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=" . $slug . "-" . now()->format('Y-m-d') . ".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        // Fetch live database rows to compile into the spreadsheet matrix rows data rows
        $products = DB::table('products')->orderBy('name', 'asc')->get();

        // Open an automated memory execution data pipe channel to write structural row entries
        $callback = function() use($products) {
            $file = fopen('php://output', 'w');
            
            // Insert standard spreadsheet data column label ribbon rows
            fputcsv($file, ['PRODUCT ID', 'PRODUCT NAME', 'STOCK UNIT COUNT', 'PRICE', 'REORDER LAYER VALUE']);

            foreach ($products as $item) {
                fputcsv($file, [
                    $item->id,
                    $item->name,
                    $item->stock_count,
                    $item->price,
                    $item->reorder_level
                ]);
            }

            fclose($file);
        };

        // Stream the compiled calculation matrix block directly to the computer disk channel ports
        return response()->stream($callback, 200, $headers);
    }

}