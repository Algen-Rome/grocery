@extends('layouts.blade-layout')

@section('content')
    <!-- Outer layout wrapper ensuring proper full-width rendering across your dashboard viewport -->
    <div class="w-full min-h-screen bg-slate-50 p-8 font-sans block">

        <!-- 📊 Dynamic Centralized Report Navigation Pills Ribbon Bar -->
        @include('layouts.report-nav')

        <!-- ==================== TOP SECTION: TWO-COLUMN ANCHOR SPLIT GRID ==================== -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mt-6">

            <!-- LEFT SECTOR: Spans 2 Columns for Revenue by Month Bar Graph Panel -->
            <div class="lg:col-span-2 w-full">
                <!-- 📊 REVENUE BY MONTH BAR GRAPH PANEL -->
                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between shadow-sm w-full min-h-[320px]">
                    <div class="mb-4">
                        <h2 class="text-xs font-black text-gray-900 uppercase tracking-wider">Revenue by Month</h2>
                    </div>

                    <div
                        class="h-48 flex items-end justify-between gap-4 bg-gray-50/50 p-4 rounded-xl border border-gray-100 mt-2">
                        @foreach($monthlyChartData as $chart)
                            <div class="flex flex-col items-center flex-1 h-full justify-end relative group">
                                <!-- Tooltip Hover Panel -->
                                <div
                                    class="absolute -top-8 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-sm z-10">
                                    ₱{{ number_format($chart['raw_sales'], 2) }}
                                </div>

                                <!-- Dynamic Height Chart Bar -->
                                <div class="w-full rounded-t transition-all duration-500 bg-emerald-600 group-hover:bg-emerald-500"
                                    style="height: {{ $chart['height'] }}%;"></div>

                                <span
                                    class="text-[10px] font-bold text-gray-400 mt-2 whitespace-nowrap uppercase tracking-wider">
                                    {{ $chart['label'] }}
                                </span>
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
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Revenue</span>
                        <span class="font-black text-gray-900 text-base">₱{{ number_format($totalRevenue ?? 0, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Growth</span>
                        <span class="font-black text-emerald-600 text-base">+{{ $growthPercent ?? 0 }}%</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Best Month</span>
                        <span class="font-black text-gray-900 text-base">{{ $bestMonth ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-1">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Avg Order</span>
                        <span class="font-black text-gray-900 text-base">₱{{ number_format($avgOrderValue ?? 0, 2) }}</span>
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
                                    <td class="py-3.5 px-4 text-gray-900 font-bold">{{ $log['title'] ?? $log['report'] }}</td>
                                    <td class="py-3.5 px-4 text-gray-500 font-medium">{{ $log['type'] }}</td>
                                    <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $log['generated'] }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="{{ route('reports.log.download', ['filename' => $log['title'] ?? $log['report']]) }}"
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