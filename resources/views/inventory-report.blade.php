@extends('layouts.blade-layout')

@section('content')
    <!-- Outer layout wrapper ensuring proper full-width rendering across your dashboard viewport -->
    <div class="w-full min-h-screen bg-slate-50 p-8 font-sans block">

        <!-- 📊 Dynamic Centralized Report Navigation Pills Ribbon Bar -->
        @include('layouts.report-nav')

        <!-- ==================== TOP SECTION: TWO-COLUMN ANCHOR SPLIT GRID ==================== -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mt-6">

            <!-- LEFT SECTOR: Spans 2 Columns for Stock Category Progress Bars -->
            <div class="lg:col-span-2 w-full">
                <!-- 📊 STOCK LEVEL BY CATEGORY PROGRESS GRAPH BOX PANEL -->
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between shadow-sm w-full min-h-[320px]">
                    <div class="mb-4">
                        <h2 class="text-xs font-black text-gray-900 uppercase tracking-wider">Stock Level by Category
                            (units)</h2>
                    </div>

                    <div class="space-y-5 mt-2">
                        @php
                            $displayCategories = [
                                'Fruits & Vegetables' => ['units' => $categoryStocks['Fruits & Vegetables'] ?? 0, 'max' => 2000, 'color' => 'bg-emerald-600/30'],
                                'Dairy' => ['units' => $categoryStocks['Dairy'] ?? 0, 'max' => 2000, 'color' => 'bg-emerald-600/30'],
                                'Meat & Seafood' => ['units' => $categoryStocks['Meat & Seafood'] ?? 0, 'max' => 2000, 'color' => 'bg-emerald-900'],
                                'Beverages' => ['units' => $categoryStocks['Beverages'] ?? 0, 'max' => 2000, 'color' => 'bg-emerald-600/30'],
                                'Pantry & Canned' => ['units' => $categoryStocks['Pantry & Canned'] ?? 0, 'max' => 2000, 'color' => 'bg-emerald-600/30']
                            ];
                        @endphp

                        @foreach($displayCategories as $name => $data)
                            @php 
                                $percentage = $data['units'] > 0 ? min(100, ($data['units'] / $data['max']) * 100) : 0; 
                            @endphp
                            <div class="flex items-center text-xs font-bold">
                                <span class="w-36 text-slate-600 truncate pr-2">{{ $name }}</span>
                                <div class="flex-1 bg-slate-100 rounded-md h-7 relative overflow-hidden">
                                    <div class="{{ $data['color'] }} h-full transition-all duration-500 rounded-md"
                                        style="width: {{ $percentage }}%;"></div>
                                </div>
                                <span
                                    class="w-16 text-right text-slate-900 font-black pl-2">{{ number_format($data['units']) }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- RIGHT SECTOR: Spans 1 Column for the KPI Summary Box Card Sidebar -->
            <div
                class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between text-sm font-semibold text-gray-600 shadow-sm min-h-[320px] w-full">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Summary</h2>

                <div class="space-y-4 flex-1 flex flex-col justify-around">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Products</span>
                        <span class="font-black text-gray-900 text-base">{{ number_format($totalProducts ?? 0) }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Low Stock Items</span>
                        <span class="font-black text-rose-600 text-base">{{ $lowStockItems ?? 0 }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Out of Stock</span>
                        <span class="font-black text-rose-700 text-base">{{ $outOfStock ?? 0 }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-1">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Stock Value</span>
                        <span class="font-black text-gray-900 text-base">{{ $stockValueDisplay ?? '₱0.00' }}</span>
                    </div>
                </div>
            </div>

        </div> <!-- Closes Top Section Grid Container -->


        <!-- ==================== LOWER SECTION: FULL-WIDTH ROW FOR REPORT LOGS ==================== -->
        <div class="w-full mt-6">
            <!-- 📋 SYSTEM REPORT LOG DATA LEDGER SHEET TABLE (NOW STRETCHED FULL-WIDTH) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 w-full">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Report Log</h2>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200/80">
                                <th class="py-3 px-4 pb-4">Report</th>
                                <th class="py-3 px-4 pb-4">Type</th>
                                <th class="py-3 px-4 pb-4">Generated</th>
                                <th class="py-3 px-4 pb-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-semibold text-gray-700 divide-y divide-gray-100">
                            @foreach($reportLogs as $log)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-3.5 px-4 text-gray-900 font-bold">{{ $log['report'] ?? $log['title'] }}</td>
                                    <td class="py-3.5 px-4 text-gray-500 font-medium">{{ $log['type'] }}</td>
                                    <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $log['generated'] }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="{{ route('reports.log.download', ['filename' => $log['report'] ?? $log['title']]) }}"
                                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition underline tracking-wide">
                                            Download
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div> <!-- Closes Lower Section Row Container -->

    </div> <!-- Closes Outer Layout Wrapper Box -->
@endsection