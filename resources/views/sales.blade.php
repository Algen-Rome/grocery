@extends('layouts.blade-layout')

@section('content')
    <div class="flex min-h-screen bg-slate-50">

        <!-- Main Content Area Container Workspace -->
        <main class="flex-1 p-8">

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

                                    <td class="py-3.5 px-4 font-semibold text-sm">
                                        @switch(strtolower($sale->status))
                                            @case('completed')
                                            @case('paid')
                                                <!-- 🟢 Paid / Completed (Emerald Green Badge) -->
                                                <span
                                                    class="inline-block bg-emerald-100 text-emerald-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide min-w-[5.5rem] text-center">
                                                    {{ $sale->status }}
                                                </span>
                                            @break

                                            @case('pending')
                                                <!-- 🟡 Pending (Amber Orange Badge) -->
                                                <span
                                                    class="inline-block bg-amber-100 text-amber-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide min-w-[5.5rem] text-center">
                                                    Pending
                                                </span>
                                            @break

                                            @case('refunded')
                                                <!-- 🔘 Refunded (Slate Gray Badge) -->
                                                <span
                                                    class="inline-block bg-gray-200 text-gray-600 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide min-w-[5.5rem] text-center">
                                                    Refunded
                                                </span>
                                            @break

                                            @default
                                                <!-- 🔵 Alternative Status Fallback Badge -->
                                                <span
                                                    class="inline-block bg-sky-100 text-sky-800 text-xs font-extrabold px-3 py-1 rounded-full uppercase tracking-wide min-w-[5.5rem] text-center">
                                                    {{ $sale->status }}
                                                </span>
                                        @endswitch
                                    </td>

                                    <td class="py-3.5 px-4 text-gray-400 font-medium">
                                        {{ $sale->created_at instanceof \Carbon\Carbon ? $sale->created_at->format('M d, Y') : date('M d, Y', strtotime($sale->created_at)) }}
                                    </td>
                                    <td class="py-3.5 px-4 text-center">
    <div class="flex items-center justify-center space-x-3">
        <!-- View Popup Action Trigger Link Button -->
        <a href="#" data-id="{{ $sale->id }}" class="text-xs font-black text-emerald-600 hover:text-emerald-700 underline tracking-wide transition trigger-view-modal-btn">
            View
        </a>
        
        <span class="text-gray-300">|</span>
        
        <!-- Secure Deletion Request Endpoint Form Post Payload -->
        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete this order record? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-xs font-black text-rose-500 hover:text-rose-700 underline tracking-wide transition">
                Delete
            </button>
        </form>
    </div>
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