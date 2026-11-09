@extends('layouts.blade-layout')

@section('content')
    <!-- Outer layout wrapper matching your master inventory viewport layout constraints -->
    <div class="w-full min-h-screen bg-slate-50 p-8 font-sans block">

        <!-- 📊 Dynamic Centralized Report Navigation Pills Ribbon Bar -->
        @include('layouts.report-nav')

        <!-- ==================== TOP SECTION: TWO-COLUMN ANCHOR SPLIT GRID (EXACT MATCH TO INVENTORY) ==================== -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mt-6">

            <!-- LEFT SECTOR: Spans 2 Columns for the Revenue vs Expenses Dual Bar Card Panel -->
            <div class="lg:col-span-2 w-full">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between shadow-sm w-full min-h-[320px]">
                    <div class="mb-4">
                        <h2 class="text-xs font-black text-gray-900 uppercase tracking-wider">Revenue vs Expenses</h2>
                    </div>

                    <div class="flex items-end justify-between h-40 pt-4 px-4 bg-slate-50/50 rounded-xl border border-slate-100/50 mt-2">
                        @forelse($chartData as $data)
                            <div class="flex flex-col items-center flex-1 group px-1">
                                <div class="flex items-end space-x-1.5 h-full w-full justify-center">
                                    <!-- Revenue Light Green Colored Tall Bar -->
                                    <div class="w-4 bg-emerald-200 hover:bg-emerald-300 rounded-t transition-all relative"
                                         style="height: {{ max($data['rev_height'], 12) }}%">
                                        <span class="absolute -top-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[9px] font-bold py-0.5 px-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Rev: ₱{{ number_format($data['rev_value']) }}</span>
                                    </div>
                                    <!-- Expense Deep Charcoal Contrast Grey Bar -->
                                    <div class="w-4 bg-gray-400 hover:bg-gray-500 rounded-t transition-all relative"
                                         style="height: {{ max($data['exp_height'], 8) }}%">
                                        <span class="absolute -top-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[9px] font-bold py-0.5 px-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Exp: ₱{{ number_format($data['exp_value']) }}</span>
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-400 font-extrabold mt-3 uppercase tracking-wider">{{ $data['month'] }}</span>
                            </div>
                        @empty
                            @foreach(['Jun', 'Jul', 'Aug', 'Sep'] as $m)
                                <div class="flex flex-col items-center flex-1">
                                    <div class="flex items-end space-x-1.5 h-full justify-center">
                                        <div class="w-4 bg-slate-200 rounded-t" style="height: 12%"></div>
                                        <div class="w-4 bg-slate-100 rounded-t" style="height: 8%"></div>
                                    </div>
                                    <span class="text-[10px] text-gray-400 font-extrabold mt-3 uppercase tracking-wider">{{ $m }}</span>
                                </div>
                            @endforeach
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- RIGHT SECTOR: Spans 1 Column for the KPI Financial Summary Box Card Sidebar -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between text-sm font-semibold text-gray-600 shadow-sm min-h-[320px] w-full">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Summary</h2>

                <div class="space-y-4 flex-1 flex flex-col justify-around">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Revenue</span>
                        <span class="font-black text-gray-900 text-base">₱{{ $totalRevenue > 1000 ? number_format($totalRevenue / 1000, 0) . 'K' : number_format($totalRevenue, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Expenses</span>
                        <span class="font-black text-gray-900 text-base">₱{{ $totalExpenses > 1000 ? number_format($totalExpenses / 1000, 0) . 'K' : number_format($totalExpenses, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Net Profit</span>
                        <span class="font-black text-emerald-600 text-base">₱{{ $netProfit > 1000 ? number_format($netProfit / 1000, 0) . 'K' : number_format($netProfit, 2) }}</span>
                    </div>

                    <div class="flex justify-between items-center pb-1">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Profit Margin</span>
                        <span class="font-black text-gray-900 text-base">{{ number_format($profitMargin, 0) }}%</span>
                    </div>
                </div>
            </div>

        </div> <!-- Closes Top Section Grid Container -->

        <!-- ==================== LOWER SECTION: FULL-WIDTH ROW FOR REPORT LOGS ==================== -->
        <div class="w-full mt-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 w-full">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Report Log</h2>
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200/80">
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
                                    <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $log['generated'] ?? $log['date'] }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="#" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition underline tracking-wide">
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
