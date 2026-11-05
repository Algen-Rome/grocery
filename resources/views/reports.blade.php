@extends('layouts.blade-layout')

@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <main class="flex-1 p-8">

            <div class="flex justify-between items-center gap-10 mb-6">
                <h1 class="text-2xl font-black tracking-wide text-gray-900">REPORTS</h1>
                <input type="text" placeholder="Search..."
                    class="rounded-full bg-gray-100 px-6 py-2.5 w-[25rem] border border-transparent focus:outline-none focus:bg-white focus:border-emerald-500 transition shadow-inner" />
                <div class="flex items-center space-x-3 ml-auto">
                    <span class="text-sm font-bold text-gray-700">Ana R.</span>
                </div>
            </div>

            <div
                class="bg-white border border-gray-100 rounded-2xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 shadow-sm">
                <div class="flex flex-wrap gap-2">
                    <button
                        class="bg-emerald-800 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-sm transition">Sales
                        Report</button>
                    <button
                        class="bg-gray-100 text-gray-500 hover:bg-gray-200 text-xs font-bold px-4 py-2.5 rounded-xl transition">Inventory
                        Report</button>
                    <button
                        class="bg-gray-100 text-gray-500 hover:bg-gray-200 text-xs font-bold px-4 py-2.5 rounded-xl transition">Customer
                        Report</button>
                    <button
                        class="bg-gray-100 text-gray-500 hover:bg-gray-200 text-xs font-bold px-4 py-2.5 rounded-xl transition">Financial
                        Report</button>
                </div>
                <div class="flex flex-col items-end gap-1.5 w-full sm:w-auto">
                    <button
                        class="bg-emerald-800 text-white font-black text-xs px-5 py-2.5 rounded-xl hover:bg-emerald-900 transition tracking-wide shadow-sm w-full sm:w-auto">Export
                        PDF</button>
                    <span class="text-[10px] font-bold text-gray-400">{{ now()->startOfMonth()->format('M j') }} -
                        {{ now()->format('M j, Y') }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between lg:col-span-2 shadow-sm">
                    <div class="mb-4">
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-wider">Revenue by Month</h2>
                    </div>

                    <div
                        class="h-48 flex items-end justify-between gap-4 bg-gray-50 p-4 rounded-xl border border-gray-100 mt-2">
                        @foreach($monthlyChartData as $chart)
                            <div class="flex flex-col items-center flex-1 h-full justify-end group relative">
                                <div
                                    class="absolute -top-8 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-sm z-10">
                                    ₱{{ number_format($chart['raw_sales'], 2) }}
                                </div>

                                <div class="w-full rounded-t transition-all duration-500 bg-emerald-600 group-hover:bg-emerald-500"
                                    style="height: {{ $chart['height'] }}%;">
                                </div>

                                <span
                                    class="text-[10px] font-bold text-gray-400 mt-2 whitespace-nowrap uppercase tracking-wider">{{ $chart['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="bg-white rounded-2xl p-6 border border-gray-100 flex flex-col justify-between text-sm font-semibold text-gray-600 shadow-sm">
                    <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Summary</h2>
                    <div class="flex flex-col space-y-3.5 flex-1 justify-center">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2.5">
                            <span class="text-gray-400 font-bold uppercase text-xs tracking-wider">Total Revenue</span>
                            <span class="font-black text-gray-900 text-base">₱{{ number_format($totalRevenue, 2) }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2.5">
                            <span class="text-gray-400 font-bold uppercase text-xs tracking-wider">Growth</span>
                            <span class="font-black text-emerald-600 text-base">{{ $growthText }}</span>
                        </div>
                        <div class="flex justify-between items-center border-b border-gray-100 pb-2.5">
                            <span class="text-gray-400 font-bold uppercase text-xs tracking-wider">Best Month</span>
                            <span class="font-black text-gray-900 text-base">{{ $bestMonth }}</span>
                        </div>
                        <div class="flex justify-between items-center pb-1">
                            <span class="text-gray-400 font-bold uppercase text-xs tracking-wider">Avg Order</span>
                            <span class="font-black text-gray-900 text-base">₱{{ number_format($avgOrderValue, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Report Log</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                                <th class="py-3 px-4 pb-4">Report</th>
                                <th class="py-3 px-4 pb-4">Type</th>
                                <th class="py-3 px-4 pb-4">Generated</th>
                                <th class="py-3 px-4 pb-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm font-semibold text-gray-700 divide-y divide-gray-100">
                            @foreach($reportLogs as $log)
                                <tr class="hover:bg-slate-50/50 transition duration-150">
                                    <td class="py-3.5 px-4 text-gray-900 font-bold">{{ $log['title'] }}</td>
                                    <td class="py-3.5 px-4 text-gray-500 font-medium">{{ $log['type'] }}</td>
                                    <td class="py-3.5 px-4 text-gray-400 font-medium">{{ $log['generated'] }}</td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="#"
                                            class="text-xs font-bold text-emerald-600 hover:text-emerald-700 transition underline tracking-wide">Download</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
@endsection