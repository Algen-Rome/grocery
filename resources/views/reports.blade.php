@extends('layouts.blade-layout')

@section('content')
<div class="flex min-h-screen bg-slate-50">

    <aside class="w-64 border-r border-gray-200 p-6 flex flex-col space-y-2 bg-white">
        <div class="mb-8 flex items-center space-x-3">
            <div class="bg-emerald-600 rounded-full h-5 w-5"></div>
            <span class="text-lg font-black text-gray-900 tracking-wide">Grocery Admin</span>
        </div>
        <a href="{{ route('dashboard') }}"
            class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Dashboard</a>
        <a href="{{ route('sales.index') }}"
            class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Sales</a>
        <a href="#"
            class="bg-emerald-800 text-white px-4 py-3 rounded-xl font-medium text-sm transition block shadow-sm">Reports</a>
        <a href="#"
            class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Settings</a>
        <a href="#"
            class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Admin</a>
        <a href="#"
            class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Online
            Orders</a>
        <a href="#"
            class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Stock
            Alerts</a>

        <form action="{{ route('logout') }}" method="POST" class="w-full mt-auto">
            @csrf
            <button type="submit"
                class="w-full text-left text-red-500 hover:bg-red-50 font-medium px-4 py-3 rounded-xl text-sm block transition">Log
                Out</button>
        </form>
    </aside>


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
                <span class="text-[10px] font-bold text-gray-400">Sep 1 - Sep 14, 2026</span>
            </div>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div
                class="bg-gray-100/80 rounded-2xl p-6 border border-gray-200/50 flex flex-col justify-between lg:col-span-2">
                <div class="mb-4">
                    <h2 class="text-sm font-black text-gray-900 uppercase tracking-wider">Revenue by Month</h2>
                </div>

                <div class="relative w-full h-44">
                    <canvas id="revenueBarChart"></canvas>
                </div>
            </div>


            <div
                class="bg-gray-100/80 rounded-2xl p-6 border border-gray-200/50 flex flex-col justify-between text-sm font-semibold text-gray-600">
                <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Summary</h2>
                <div class="flex flex-col space-y-3.5 flex-1 justify-center">
                    <div class="flex justify-between items-center border-b border-gray-200/60 pb-2">
                        <span class="text-gray-500 font-bold">Total Revenue</span>
                        <span class="font-black text-gray-900">{{ $formattedRevenue }}</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-200/60 pb-2">
                        <span class="text-gray-500 font-bold">Growth</span>
                        <span class="font-black text-emerald-600">+18%</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-200/60 pb-2">
                        <span class="text-gray-500 font-bold">Best Month</span>
                        <span class="font-black text-gray-900">August</span>
                    </div>
                    <div class="flex justify-between items-center pb-1">
                        <span class="text-gray-500 font-bold">Avg Order</span>
                        <span class="font-black text-gray-900">₱375</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-wider mb-4">Report Log</h2>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                        <th class="py-3 px-4 pb-4">Report</th>
                        <th class="py-3 px-4 pb-4">Type</th>
                        <th class="py-3 px-4 pb-4">Generated</th>
                        <th class="py-3 px-4 pb-4 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-semibold text-gray-700">
                    <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 text-gray-900">August 2026 Sales Summary</td>
                        <td class="py-3 px-4 text-gray-400 font-medium">Sales</td>
                        <td class="py-3 px-4 text-gray-400 font-medium">Sep 1, 2026</td>
                        <td class="py-3 px-4 text-center">
                            <a href="#"
                                class="text-xs font-bold text-gray-400 hover:text-emerald-700 transition underline tracking-wide">Download</a>
                        </td>
                    </tr>
                    <tr class="border-b border-gray-100 hover:bg-slate-50/50 transition">
                        <td class="py-3 px-4 text-gray-900">Q3 Inventory Audit</td>
                        <td class="py-3 px-4 text-gray-400 font-medium">Inventory</td>
                        <td class="py-3 px-4 text-gray-400 font-medium">Aug 28, 2026</td>
                        <td class="py-3 px-4 text-center">
                            <a href="#"
                                class="text-xs font-bold text-gray-400 hover:text-emerald-700 transition underline tracking-wide">Download</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</div>


<script src="https://jsdelivr.net"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('revenueBarChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                datasets: [{
                    data:,
                    backgroundColor: function (context) {

                        return context.dataIndex === 4 ? '#064e3b' : '#a7f3d0';
                    },
                    borderRadius: 6,
                    borderSkipped: false,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        display: false,
                        grid: { display: false }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#94a3b8', font: { weight: 'bold', size: 11 }
                        }
                    }

                }
            }
        });
    });
    @endsection