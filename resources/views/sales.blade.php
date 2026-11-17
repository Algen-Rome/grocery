@extends('layouts.blade-layout')

@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <!-- Main Content Area Container Workspace -->
        <main class="flex-1 p-0 lg:p-8">

            <!-- MOBILE TOP CONTROLS (Matches Sales Mobile Mockup) -->
            <div class="lg:hidden space-y-4 mb-6">
                <h1 class="text-2xl font-extrabold text-slate-900">Sales</h1>

                <!-- Full-Width +New Sale Button -->
                <a href="{{ route('sales.create') }}"
                    class="w-full flex justify-center items-center bg-[#165334] hover:bg-[#114229] text-white font-bold text-sm py-3 rounded-xl shadow-sm transition">
                    +New Sale
                </a>

                <!-- 2x2 Filter Grid -->
                <div class="grid grid-cols-2 gap-3">
                    <select
                        class="bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-600 focus:outline-none focus:border-[#165334]">
                        <option>Date Range</option>
                    </select>
                    <select
                        class="bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-600 focus:outline-none focus:border-[#165334]">
                        <option>Category</option>
                    </select>
                    <select
                        class="bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-600 focus:outline-none focus:border-[#165334]">
                        <option>Status</option>
                    </select>
                    <input type="text" placeholder="Search orders"
                        class="bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:border-[#165334]" />
                </div>

                <!-- 3 Horizontal KPI Cards -->
                <div class="space-y-2.5">
                    <div
                        class="bg-white rounded-2xl border border-slate-200 px-4 py-3.5 flex items-center justify-between shadow-sm">
                        <span class="text-xs font-medium text-slate-600">Total Revenue</span>
                        <span
                            class="text-base font-extrabold text-slate-900">₱{{ number_format($totalRevenue ?? 0, 0) }}</span>
                    </div>
                    <div
                        class="bg-white rounded-2xl border border-slate-200 px-4 py-3.5 flex items-center justify-between shadow-sm">
                        <span class="text-xs font-medium text-slate-600">Total Orders</span>
                        <span class="text-base font-extrabold text-slate-900">{{ $totalOrdersCount ?? 0 }}</span>
                    </div>
                    <div
                        class="bg-white rounded-2xl border border-slate-200 px-4 py-3.5 flex items-center justify-between shadow-sm">
                        <span class="text-xs font-medium text-slate-600">Avg order Value</span>
                        <span
                            class="text-base font-extrabold text-slate-900">₱{{ number_format($avgOrderValue ?? 0, 0) }}</span>
                    </div>
                </div>
            </div>

            <!-- DESKTOP UNIFIED CONTROL RIBBON (Hidden on Mobile) -->
            <div
                class="hidden lg:flex bg-white border border-gray-100 rounded-2xl p-4 flex-col xl:flex-row justify-between items-stretch xl:items-center gap-4 mb-6 shadow-sm">
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

                <div class="flex items-center justify-end">
                    <a href="{{ route('sales.create') }}"
                        class="flex justify-center items-center bg-emerald-800 text-white font-black text-sm px-6 py-3 rounded-xl hover:bg-emerald-900 transition tracking-wide shadow-sm whitespace-nowrap w-full sm:w-auto">
                        + New Sale
                    </a>
                </div>
            </div>

            <!-- DESKTOP KPI PANEL CARD GRID (Hidden on Mobile) -->
            <div class="hidden lg:grid md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-gray-100/80 p-6 rounded-2xl border border-gray-200/40 flex flex-col justify-center items-center text-center h-28 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Revenue</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">₱{{ number_format($totalRevenue ?? 0, 2) }}</h3>
                </div>

                <div
                    class="bg-gray-100/80 p-6 rounded-2xl flex flex-col justify-center items-center text-center h-28 border-2 border-emerald-500 relative shadow-sm">
                    <p class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Total Orders</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $totalOrdersCount ?? 0 }}</h3>
                </div>

                <div
                    class="bg-gray-100/80 p-6 rounded-2xl border border-gray-200/40 flex flex-col justify-center items-center text-center h-28 shadow-sm">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Avg Order Value</p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">₱{{ number_format($avgOrderValue ?? 0, 2) }}</h3>
                </div>
            </div>


            <!-- RECENT ORDERS SECTION -->
            <div class="lg:bg-white lg:p-6 lg:rounded-2xl lg:shadow-sm lg:border lg:border-gray-100">
                <h2
                    class="text-base lg:text-sm font-extrabold lg:font-black text-slate-900 lg:text-gray-400 lg:uppercase tracking-wider mb-3 lg:mb-4">
                    Recent Orders
                </h2>

                <!-- Mobile Stacked Order Cards (Matches Mockup) -->
                <div class="lg:hidden space-y-3">
                    @forelse($orders as $sale)
                        <div class="bg-white rounded-2xl border border-slate-200 p-4 space-y-2 text-xs shadow-sm">
                            <p class="font-bold text-slate-900 text-sm">{{ $sale->order_number ?? '#' . $sale->id }}</p>
                            <p class="font-medium text-slate-700 pb-1">{{ $sale->customer_name }}</p>

                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-400 uppercase text-[10px]">ITEMS</span>
                                <span
                                    class="font-semibold text-slate-800">{{ is_array($sale->items) ? count($sale->items) : '0' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-400 uppercase text-[10px]">TOTAL</span>
                                <span class="font-semibold text-slate-800">₱{{ number_format($sale->total_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-400 uppercase text-[10px]">STATUS</span>
                                <span
                                    class="font-extrabold text-slate-900">{{ ucfirst(strtolower($sale->status ?? 'Pending')) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-slate-400 uppercase text-[10px]">DATE</span>
                                <span class="text-slate-600">
                                    {{ $sale->created_at ? \Carbon\Carbon::parse($sale->created_at)->format('M d, Y') : 'N/A' }}
                                </span>
                            </div>

                            <div class="pt-2 flex justify-end items-center gap-4 border-t border-slate-100 mt-2">
                                <a href="{{ route('sales.show', $sale->id) }}"
                                    class="text-emerald-700 hover:text-emerald-900 font-bold text-xs">
                                    View
                                </a>
                                <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this order?');"
                                    class="inline m-0 p-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-500 hover:text-red-700 font-bold text-xs bg-transparent border-0 p-0 cursor-pointer">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p
                            class="text-xs text-slate-400 text-center py-6 bg-white rounded-2xl border border-slate-200 font-bold">
                            No sales records found.
                        </p>
                    @endforelse
                </div>

                <!-- Desktop Sales Table (Hidden on Mobile) -->
                <div class="hidden lg:block overflow-x-auto">
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
                            @foreach($orders as $sale)
                                <tr class="hover:bg-slate-50/50 transition text-sm font-semibold text-gray-700">
                                    <td class="py-3.5 px-4 text-gray-400">
                                        {{ $sale->order_number ?? '#' . $sale->id }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-900 font-bold">
                                        {{ $sale->customer_name }}
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-500 font-medium">
                                        {{ is_array($sale->items) ? count($sale->items) : '0' }}
                                    </td>
                                    <td class="py-3.5 px-4 font-black text-gray-900">
                                        ₱{{ number_format($sale->total_amount, 2) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-sm">
                                        @if(strtolower($sale->status ?? 'pending') === 'completed' || strtolower($sale->status ?? '') === 'paid')
                                            <span
                                                class="inline-block bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide min-w-[5.5rem] text-center">
                                                {{ $sale->status }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-block bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide min-w-[5.5rem] text-center">
                                                {{ $sale->status ?? 'Pending' }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-3.5 px-4 text-gray-500 font-medium">
                                        {{ $sale->created_at ? \Carbon\Carbon::parse($sale->created_at)->format('M d, Y g:i A') : 'N/A' }}
                                    </td>
                                    <td class="py-3.5 px-4 text-sm">
                                        <div class="flex items-center gap-3">
                                            <a href="{{ route('sales.show', $sale->id) }}"
                                                class="text-emerald-600 hover:text-emerald-700 transition">
                                                View
                                            </a>
                                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this order?');"
                                                class="inline m-0 p-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 hover:text-red-600 transition font-bold cursor-pointer bg-transparent border-0 p-0">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 🌟 Floating Order Details Overlay Modal Component Section Layer -->
            <div id="order-details-modal"
                class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm hidden items-center justify-center z-50 transition duration-150 p-4">
                <div
                    class="bg-white border border-gray-100 w-full max-w-lg rounded-2xl p-6 shadow-xl transform scale-95 transition-all duration-200">

                    <!-- Header Info Row -->
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h3 class="text-base font-black text-gray-900 tracking-wide">Order Receipt <span
                                    id="modal-order-id" class="text-emerald-700">#00</span></h3>
                            <p id="modal-order-date" class="text-[11px] font-bold text-gray-400 mt-0.5">Nov 05, 2026</p>
                        </div>
                        <button type="button" id="close-modal-btn"
                            class="text-gray-400 hover:text-gray-600 font-bold text-sm bg-gray-50 hover:bg-gray-100 p-2 rounded-xl transition">✕
                            Close</button>
                    </div>

                    <!-- Customer & Order Context Telemetry Details Sheet -->
                    <div
                        class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-4 space-y-2 text-xs font-semibold text-gray-600">
                        <div class="flex justify-between">
                            <span class="text-gray-400">Cashier Store Admin:</span>
                            <span class="text-gray-800 font-bold">Ana R.</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-400">Customer Name Target:</span>
                            <span id="modal-customer" class="text-gray-900 font-black">Maria Santos</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-400">Transaction Status:</span>
                            <span id="modal-status"
                                class="bg-emerald-100 text-emerald-800 text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wide">COMPLETED</span>
                        </div>
                    </div>

                    <!-- Purchased Product Matrix Spreadsheet Table Section -->
                    <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-wider mb-2">Basket Breakdowns</h4>
                    <div class="border border-gray-100 rounded-xl overflow-hidden mb-4 max-h-40 overflow-y-auto">
                        <table class="w-full text-left text-xs font-semibold text-gray-700 border-collapse">
                            <thead>
                                <tr
                                    class="bg-gray-50 text-[10px] text-gray-400 font-bold uppercase tracking-wider border-b border-gray-100">
                                    <th class="py-2.5 px-4">Product Name</th>
                                    <th class="py-2.5 px-4 text-center">Qty</th>
                                    <th class="py-2.5 px-4 text-right">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="modal-items-rows" class="divide-y divide-gray-50 bg-white">
                                <!-- Dynamic product data loops rows map inside here dynamically -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Grand Total Summary Banner Board Container -->
                    <div
                        class="flex justify-between items-center pt-3 border-t border-gray-100 font-black text-sm text-gray-900">
                        <span class="text-gray-400 text-xs font-bold uppercase tracking-wide">Grand Total Collected</span>
                        <span id="modal-total-amt" class="text-lg text-gray-900 font-black">₱0.00</span>
                    </div>

                </div>
            </div>

        </main>
@endsection

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('order-details-modal');
            const closeBtn = document.getElementById('close-modal-btn');
            const itemsRows = document.getElementById('modal-items-rows');

            // Attach ajax loaders to every row line item view action button
            document.querySelectorAll('.trigger-view-modal-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const orderId = this.getAttribute('data-id');

                    // Trigger secure background async fetch query lookup metrics
                    fetch(`/sales/${orderId}/json`)
                        .then(res => res.json())
                        .then(data => {
                            if (data.error) return alert(data.error);

                            // Map string variables cleanly onto DOM receipt elements variables
                            document.getElementById('modal-order-id').innerText = '#' + data.order_id;
                            document.getElementById('modal-order-date').innerText = data.date;
                            document.getElementById('modal-customer').innerText = data.customer_name;
                            document.getElementById('modal-total-amt').innerText = '₱' + data.total_amount;

                            // Render status badges with proper coloring layout matching contextual parameters
                            const statusEl = document.getElementById('modal-status');
                            statusEl.innerText = data.status.toUpperCase();

                            // 🌟 Inside your fetch().then(data => { ... }) loop block, replace 'const statusEl...' with this:
                            statusEl.className =
                                'text-[10px] font-black px-2.5 py-0.5 rounded-full uppercase tracking-wide text-center';

                            const normalizedStatus = data.status.toLowerCase();
                            if (normalizedStatus === 'completed' || normalizedStatus === 'paid') {
                                statusEl.classList.add('bg-emerald-100', 'text-emerald-800');
                            } else if (normalizedStatus === 'pending') {
                                statusEl.classList.add('bg-amber-100', 'text-amber-800');
                            } else if (normalizedStatus === 'refunded') {
                                statusEl.classList.add('bg-gray-200', 'text-gray-600');
                            } else {
                                statusEl.classList.add('bg-sky-100', 'text-sky-800');
                            }

                            itemsRows.innerHTML = ''; // wipe loading structures clean

                            // Loop through the purchases list
                            data.items.forEach(item => {
                                const tr = document.createElement('tr');
                                tr.className = "hover:bg-slate-50/40 transition font-medium text-gray-700";
                                tr.innerHTML = `
                                <td class="py-2.5 px-4 font-bold text-gray-900">${item.name}</td>
                                <td class="py-2.5 px-4 text-center font-bold text-gray-400">${item.qty}</td>
                                <td class="py-2.5 px-4 text-right font-black text-gray-900">₱${parseFloat(item.price * item.qty).toFixed(2)}</td>
                            `;
                                itemsRows.appendChild(tr);
                            });

                            // Transition active state visibility layer classes on screen
                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        })
                        .catch(() => alert('Could not parse transaction telemetry logs details.'));
                });
            });

            // Close buttons behaviors triggers handlers
            if (closeBtn) {
                closeBtn.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            }

            window.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            });
        });
    </script>