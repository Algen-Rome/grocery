@extends('layouts.blade-layout')

@section('content')
    <!-- Outer layout wrapper ensuring proper full-width rendering across your dashboard viewport -->
    <div class="w-full min-h-screen bg-slate-50 p-8 font-sans block">

        <!-- 📊 Dynamic Centralized Report Navigation Pills Ribbon Bar -->
        @include('layouts.report-nav')

        <!-- ==================== TOP SECTION: TWO-COLUMN ANCHOR SPLIT GRID ==================== -->
        <div class="w-full grid grid-cols-1 lg:grid-cols-3 gap-6 items-start mt-6">

            <!-- LEFT SECTOR: Spans 2 Columns for Customer Growth Graph Card -->
            <div class="lg:col-span-2 w-full">
                <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between shadow-sm w-full min-h-[320px]">
                    <div class="mb-4">
                        <h2 class="text-xs font-black text-gray-900 uppercase tracking-wider">New Customers by Month</h2>
                    </div>

                    <div class="flex items-end justify-between h-40 pt-4 px-4 bg-slate-50/50 rounded-xl border border-slate-100/50 mt-2">
                        @forelse($chartData as $data)
                            <div class="flex flex-col items-center flex-1 group">
                                <div class="w-8 bg-emerald-700 hover:bg-emerald-800 rounded-t transition-all duration-300 relative"
                                    style="height: {{ $data['height'] }}%">
                                    <span class="absolute -top-7 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-[9px] font-bold py-0.5 px-1.5 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        {{ $data['count'] }} clients
                                    </span>
                                </div>
                                <span class="text-[10px] text-gray-400 font-extrabold mt-3 uppercase tracking-wider">
                                    {{ $data['month'] }}
                                </span>
                            </div>
                        @empty
                            @foreach(['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov'] as $m)
                                <div class="flex flex-col items-center flex-1">
                                    <div class="w-8 bg-slate-200 rounded-t" style="height: 8%"></div>
                                    <span class="text-[10px] text-gray-400 font-extrabold mt-3 uppercase tracking-wider">{{ $m }}</span>
                                </div>
                            @endforeach
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- RIGHT SECTOR: Spans 1 Column for the KPI Summary Box Card Sidebar -->
            <div class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between text-sm font-semibold text-gray-600 shadow-sm min-h-[320px] w-full">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Summary</h2>

                <div class="space-y-4 flex-1 flex flex-col justify-around">
                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Total Customers</span>
                        <span class="font-black text-gray-900 text-base">{{ number_format($totalCustomers ?? 0) }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">New This Month</span>
                        <span class="font-black text-gray-900 text-base">+{{ $newThisMonth ?? 0 }}</span>
                    </div>

                    <div class="flex justify-between items-center border-b border-gray-100 pb-3">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Returning Rate</span>
                        <span class="font-black text-gray-900 text-base">64%</span>
                    </div>

                    <div class="flex justify-between items-center pb-1">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wider">Active Today</span>
                        <span class="font-black text-gray-900 text-base">{{ $activeToday ?? 0 }}</span>
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
                                        <!-- Active Download Routing Hook linked directly to the shared media utility engine -->
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
