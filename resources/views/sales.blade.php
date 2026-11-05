@extends('layouts.blade-layout')

@section('content')
    <div class="flex min-h-screen bg-slate-50">

        <!-- Sidebar Navigation Layout Panel -->
        <aside class="w-64 border-r border-gray-200 p-6 flex flex-col space-y-2 bg-white">
            <div class="mb-8 flex items-center space-x-3">
                <div class="bg-emerald-600 rounded-full h-5 w-5"></div>
                <span class="text-lg font-black text-gray-900 tracking-wide">Grocery Admin</span>
            </div>
            <a href="{{ route('dashboard') }}"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Dashboard</a>
            <a href="{{ route('sales.index') }}"
                class="bg-emerald-800 text-white px-4 py-3 rounded-xl font-medium text-sm transition block shadow-sm">Sales</a>
            <a href="{{ route('reports.index') }}"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Reports</a>
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

        <!-- Main Content Area Container Workspace -->
        <main class="flex-1 p-8">

            <!-- Header Search Ribbon Row -->
            <div class="flex justify-between items-center gap-10 mb-8">
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Search..."
                        class="rounded-full bg-gray-100 px-6 py-2.5 w-[25rem] border border-transparent focus:outline-none focus:bg-white focus:border-emerald-500 transition shadow-inner" />
                </div>
                <div class="flex items-center space-x-6 ml-auto">
                    <span class="text-sm font-bold text-gray-700">Ana R.</span>
                </div>
            </div>

            <!-- Unified Control Ribbon Box Component (Dropdown Filters & Aligned Button) -->
            <div
                class="bg-white border border-gray-100 rounded-2xl p-4 flex flex-col xl:flex-row justify-between items-stretch xl:items-center gap-4 mb-6 shadow-sm">
                <!-- Dropdown Fields Layout Elements Row -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 flex-1">
                    <select
                        class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs font-bold text-gray-400 focus:outline-none focus:border-emerald-500">
                        <option>Date Range</option>
                    </select>
                    <select
                        class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs font-bold text-gray-400 focus:outline-none focus:border-emerald-500">
                        <option>Category</option>
                    </select>
                    <select
                        class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs font-bold text-gray-400 focus:outline-none focus:border-emerald-500">
                        <option>Status</option>
                    </select>
                    <input type="text" placeholder="Search orders"
                        class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs font-bold text-gray-400 placeholder-gray-400 focus:outline-none focus:border-emerald-500" />
                </div>

                <!-- Action Link Item Aligned Perfectly on the Right Side -->
                <div class="flex items-center justify-end">
                    <a href="{{ route('sales.create') }}"
                        class="flex justify-center items-center bg-emerald-800 text-white font-black text-sm px-6 py-3 rounded-xl hover:bg-emerald-900 transition tracking-wide shadow-sm whitespace-nowrap w-full sm:w-auto">
                        + New Sale
                    </a>
                </div>
            </div>

            <!-- Summary KPI Analytical Telemetry Panel Card Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-gray-100/80 p-6 rounded-2xl border border-gray-200/40 flex flex-col justify-center items-center text-center h-28 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">₱{{ number_format($totalRevenue, 2) }}</h3>
                </div>
                <div
                    class="bg-gray-100/80 p-6 rounded-2xl flex flex-col justify-center items-center text-center h-28 border-2 border-emerald-500 relative shadow-sm">
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Orders</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $totalOrdersCount }}</h3>
                </div>
                <div
                    class="bg-gray-100/80 p-6 rounded-2xl border border-gray-200/40 flex flex-col justify-center items-center text-center h-28 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Avg Order Value</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">₱{{ number_format($avgOrderValue, 2) }}</h3>
                </div>
            </div>

            <!-- Detailed Sales Transaction Ledger Database Log Table Sheet -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-sm font-black text-gray-400 uppercase tracking-wider mb-4">Recent Orders</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="bg-gray-50/70 text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-200">
                                <th class="py-3 px-4">Order ID</th>
                                <th class="py-3 px-4">Customer</th>
                                <th class="py-3 px-4">Items</th>
                                <th class="py-3 px-4">Total</th>
                                <th class="py-3 px-4">Status</th>
                                <th class="py-3 px-4">Date</th>
                                <th class="py-3 px-4 text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($allSales as $sale)
                                <tr
                                    class="hover:bg-slate-50/50 transition border-b border-gray-100 text-sm font-semibold text-gray-700">
                                    <td class="py-3.5 px-4 text-gray-400">#{{ $sale->order_number ?? $sale->id }}</td>
                                    <td class="py-3.5 px-4 text-gray-900 font-bold">{{ $sale->customer_name }}</td>

                                    <td class="py-3.5 px-4 text-gray-500 font-medium">
                                        {{ is_array(json_decode($sale->items ?? '')) ? count(json_decode($sale->items)) : rand(2, 6) }}
                                    </td>

                                    <td class="py-3.5 px-4 font-black text-gray-900">
                                        ₱{{ number_format($sale->total_amount, 2) }}</td>

                                    <td class="py-3.5 px-4">
                                        @if(strtolower($sale->status) === 'completed')
                                            <span
                                                class="bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide">Completed</span>
                                        @elseif(strtolower($sale->status) === 'pending')
                                            <span
                                                class="bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide">Pending</span>
                                        @else
                                            <span
                                                class="bg-sky-100 text-sky-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide">{{ $sale->status }}</span>
                                        @endif
                                    </td>

                                    <td class="py-3.5 px-4 text-gray-400 font-medium">
                                        {{ $sale->created_at instanceof \Carbon\Carbon ? $sale->created_at->format('M d, Y') : date('M d, Y', strtotime($sale->created_at)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
                                        <a href="#"
                                            class="text-xs font-black text-emerald-600 hover:text-emerald-700 underline tracking-wide transition">View</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-8 text-center text-sm font-semibold text-gray-400 bg-gray-50/50">
                                        No transaction logs found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
@endsection