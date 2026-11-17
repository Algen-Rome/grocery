@extends('layouts.blade-layout')

@section('content')
    <!-- Outer layout wrapper ensuring proper full-width rendering across your dashboard viewport -->
    <div class="w-full min-h-screen bg-slate-50 p-0 lg:p-8 font-sans block">

        <!-- Mobile Page Title -->
        <h1 class="lg:hidden text-2xl font-extrabold text-slate-900 mb-4">Reports</h1>

        <!-- 📊 Dynamic Centralized Report Navigation Pills Ribbon Bar -->
        @include('layouts.report-nav')

        <!-- ==================== TOP SECTION: TWO-COLUMN ANCHOR SPLIT GRID ==================== -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 items-start mt-5 lg:mt-6">

            <!-- LEFT SECTOR: Spans 2 Columns for Revenue by Month Bar Graph Panel -->
            <div class="lg:col-span-2 w-full">
                <!-- 📊 REVENUE BY MONTH BAR GRAPH PANEL -->
                <div
                    class="bg-white rounded-2xl p-5 lg:p-6 border border-slate-200 lg:border-gray-100 flex flex-col justify-between shadow-sm w-full lg:min-h-[320px]">
                    <div class="mb-4">
                        <h2
                            class="text-sm lg:text-xs font-extrabold lg:font-black text-slate-900 lg:text-gray-900 lg:uppercase tracking-wider">
                            Revenue by Month</h2>
                    </div>

                    <div
                        class="h-40 lg:h-48 flex items-end justify-between gap-2.5 lg:gap-4 lg:bg-gray-50/50 pt-4 px-1 lg:p-4 lg:rounded-xl lg:border lg:border-gray-100 mt-1 lg:mt-2">
                        @foreach($monthlyChartData as $chart)
                            <div class="flex flex-col items-center flex-1 h-full justify-end relative group">
                                <!-- Tooltip Hover Panel -->
                                <div
                                    class="absolute -top-8 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-sm z-10">
                                    ₱{{ number_format($chart['raw_sales'], 2) }}
                                </div>

                                <!-- Dynamic Height Chart Bar (Highlights Current/Last Bar in Dark Forest Green on Mobile) -->
                                <div class="w-full rounded-t transition-all duration-500 {{ $loop->last ? 'bg-[#165334] lg:bg-emerald-600' : 'bg-emerald-100 lg:bg-emerald-600' }} group-hover:bg-emerald-500"
                                    style="height: {{ $chart['height'] }}%;"></div>

                                <span
                                    class="hidden lg:block text-[10px] font-bold text-gray-400 mt-2 whitespace-nowrap uppercase tracking-wider">
                                    {{ $chart['label'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <!-- RIGHT SECTOR: Spans 1 Column for the KPI Summary Box Card Sidebar -->
            <div
                class="bg-white rounded-2xl p-5 lg:p-6 border border-slate-200 lg:border-gray-100 flex flex-col justify-between text-xs lg:text-sm font-semibold text-gray-600 shadow-sm lg:min-h-[320px] w-full">
                <h2
                    class="text-sm lg:text-xs font-extrabold lg:font-black text-slate-900 lg:text-gray-400 lg:uppercase tracking-wider mb-4">
                    Summary</h2>

                <div class="space-y-3.5 lg:space-y-4 flex-1 flex flex-col justify-around">
                    <div class="flex justify-between items-center lg:border-b lg:border-gray-100 lg:pb-3">
                        <span
                            class="text-slate-500 lg:text-gray-400 text-xs font-semibold lg:font-bold lg:uppercase tracking-wider">Total
                            Revenue</span>
                        <span
                            class="font-black text-slate-900 lg:text-gray-900 text-sm lg:text-base">₱{{ number_format($totalRevenue ?? 0, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center lg:border-b lg:border-gray-100 lg:pb-3">
                        <span
                            class="text-slate-500 lg:text-gray-400 text-xs font-semibold lg:font-bold lg:uppercase tracking-wider">Growth</span>
                        <span
                            class="font-black text-slate-900 lg:text-emerald-600 text-xs lg:text-base">+{{ $growthPercent ?? 0 }}%</span>
                    </div>

                    <div class="flex justify-between items-center lg:border-b lg:border-gray-100 lg:pb-3">
                        <span
                            class="text-slate-500 lg:text-gray-400 text-xs font-semibold lg:font-bold lg:uppercase tracking-wider">Best
                            Month</span>
                        <span
                            class="font-black text-slate-900 lg:text-gray-900 text-xs lg:text-base">{{ $bestMonth ?? 'N/A' }}</span>
                    </div>

                    <div class="flex justify-between items-center lg:pb-1">
                        <span
                            class="text-slate-500 lg:text-gray-400 text-xs font-semibold lg:font-bold lg:uppercase tracking-wider">Avg
                            Order</span>
                        <span
                            class="font-black text-slate-900 lg:text-gray-900 text-xs lg:text-base">₱{{ number_format($avgOrderValue ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>

        </div> <!-- Closes Top Section Grid Container -->
        <!-- ==================== LOWER SECTION: FULL-WIDTH ROW FOR REPORT LOGS ==================== -->
        <div class="w-full mt-6">
            <!-- Mobile Section Heading -->
            <h2 class="lg:hidden text-sm font-extrabold text-slate-900 mb-3 tracking-wide">Report Log</h2>

            <!-- 📋 SYSTEM REPORT LOG DATA LEDGER SHEET TABLE (CARDS ON MOBILE, FULL-WIDTH TABLE ON DESKTOP) -->
            <div class="lg:bg-white lg:p-6 lg:rounded-2xl lg:shadow-sm lg:border lg:border-gray-100 w-full">
                <h2 class="hidden lg:block text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Report Log</h2>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse block lg:table">
                        <thead class="hidden lg:table-header-group">
                            <tr
                                class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200/80">
                                <th class="py-3 px-4 pb-4">Report</th>
                                <th class="py-3 px-4 pb-4">Type</th>
                                <th class="py-3 px-4 pb-4">Generated</th>
                                <th class="py-3 px-4 pb-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody
                            class="block lg:table-row-group space-y-3 lg:space-y-0 text-xs lg:text-sm font-semibold text-gray-700 lg:divide-y lg:divide-gray-100">
                            @foreach($reportLogs as $log)
                                <tr
                                    class="block lg:table-row bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 p-4 lg:p-0 shadow-sm lg:shadow-none hover:bg-slate-50/50 transition duration-150">
                                    <td
                                        class="block lg:table-cell lg:py-3.5 lg:px-4 text-slate-900 lg:text-gray-900 font-extrabold lg:font-bold text-sm lg:text-sm pb-2.5 lg:pb-3.5">
                                        {{ $log['title'] ?? $log['report'] }}
                                    </td>
                                    <td
                                        class="flex justify-between items-center lg:table-cell py-1 lg:py-3.5 lg:px-4 text-slate-600 lg:text-gray-500 font-semibold lg:font-medium">
                                        <span
                                            class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">TYPE</span>
                                        <span>{{ $log['type'] }}</span>
                                    </td>
                                    <td
                                        class="flex justify-between items-center lg:table-cell py-1 lg:py-3.5 lg:px-4 text-slate-500 lg:text-gray-400 font-medium">
                                        <span
                                            class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">GENERATED</span>
                                        <span>{{ $log['generated'] }}</span>
                                    </td>
                                    <td class="block lg:table-cell pt-2.5 lg:py-3.5 lg:px-4 text-right lg:text-center">
                                        <a href="{{ route('reports.log.download', ['filename' => $log['title'] ?? $log['report']]) }}"
                                            class="text-[11px] lg:text-xs font-bold text-slate-500 lg:text-emerald-600 hover:text-emerald-700 transition no-underline lg:underline tracking-wide">
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