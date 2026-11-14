@extends('layouts.blade-layout')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Action Buttons Row --}}
        <div class="flex justify-end gap-3">
            <a href="{{ route('online-orders.create') }}"
                class="bg-[#d9dbdb] hover:bg-gray-300 text-gray-900 font-bold text-xs px-5 py-3 rounded-lg transition shadow-sm">
                + Customer Order Form
            </a>
            <a href="{{ route('online-orders.export') }}"
                class="bg-[#1b5335] hover:bg-[#143f28] text-white font-bold text-xs px-6 py-3 rounded-lg transition shadow-sm">
                Export Orders
            </a>
        </div>

        {{-- Filter Bar Row --}}
        <form method="GET" action="{{ route('online-orders.index') }}"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <select name="date_range" onchange="this.form.submit()"
                class="bg-[#d9dbdb] text-gray-800 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
                <option value="">Date Range</option>
                <option value="today" {{ request('date_range') === 'today' ? 'selected' : '' }}>Today</option>
                <option value="week" {{ request('date_range') === 'week' ? 'selected' : '' }}>This Week</option>
                <option value="month" {{ request('date_range') === 'month' ? 'selected' : '' }}>This Month</option>
            </select>

            <select name="fulfillment" onchange="this.form.submit()"
                class="bg-[#d9dbdb] text-gray-800 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
                <option value="All">Fulfillment</option>
                <option value="Delivery" {{ request('fulfillment') === 'Delivery' ? 'selected' : '' }}>Delivery</option>
                <option value="Pick Up" {{ request('fulfillment') === 'Pick Up' ? 'selected' : '' }}>Pick Up</option>
            </select>

            <select name="status" onchange="this.form.submit()"
                class="bg-[#d9dbdb] text-gray-800 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
                <option value="All">Status</option>
                <option value="New" {{ request('status') === 'New' ? 'selected' : '' }}>New</option>
                <option value="Preparing" {{ request('status') === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                <option value="Delivered" {{ request('status') === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="Cancelled" {{ request('status') === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search orders"
                class="bg-[#d9dbdb] text-gray-800 placeholder-gray-700 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
        </form>

        {{-- 3 Summary KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#d9dbdb] rounded-3xl py-6 px-6 text-center">
                <p class="text-xs font-medium text-gray-800 mb-3">Online Orders Today</p>
                <p class="text-xl font-bold text-gray-900">{{ $ordersTodayCount }}</p>
            </div>

            <div class="bg-[#d9dbdb] rounded-3xl py-6 px-6 text-center">
                <p class="text-xs font-medium text-gray-800 mb-3">Pending Fulfillment</p>
                <p class="text-xl font-bold text-gray-900">{{ $pendingFulfillmentCount }}</p>
            </div>

            <div class="bg-[#d9dbdb] rounded-3xl py-6 px-6 text-center">
                <p class="text-xs font-medium text-gray-800 mb-3">Online Revenue</p>
                <p class="text-xl font-bold text-gray-900">₱{{ number_format($onlineRevenue, 0) }}</p>
            </div>
        </div>

        {{-- Incoming Online Orders Table --}}
        <div>
            <h2 class="text-sm font-bold text-gray-900 mb-3">Incoming Online Orders</h2>

            <div class="bg-white border border-gray-300 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#d9dbdb] text-gray-900 text-xs font-bold">
                            <th class="py-3.5 px-4">ORDER ID</th>
                            <th class="py-3.5 px-4">CUSTOMER</th>
                            <th class="py-3.5 px-4">TYPE</th>
                            <th class="py-3.5 px-4">TOTAL</th>
                            <th class="py-3.5 px-4 text-center">STATUS</th>
                            <th class="py-3.5 px-4">DATE</th>
                            <th class="py-3.5 px-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs text-gray-800">
                        @forelse($orders as $order)
                            @php
                                $statusLabel = $order->status === 'Pending' ? 'New' : $order->status;
                                $badgeClass = match ($statusLabel) {
                                    'Preparing' => 'bg-[#bfe3ce] text-[#1b5335]',
                                    'New' => 'bg-[#f4e4cd] text-[#b47b38]',
                                    'Delivered' => 'bg-[#bfe3ce] text-[#1b5335]',
                                    'Cancelled' => 'bg-[#d4d4d4] text-white',
                                    default => 'bg-gray-200 text-gray-700',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-3.5 px-4 font-medium">
                                    #ON-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="py-3.5 px-4">{{ $order->full_name }}</td>
                                <td class="py-3.5 px-4">{{ $order->order_type }}</td>
                                <td class="py-3.5 px-4">₱{{ number_format($order->subtotal, 0) }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span
                                        class="inline-block min-w-[90px] py-1 px-3 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <a href="{{ route('online-orders.show', $order) }}"
                                        class="text-gray-700 hover:text-black font-medium">
                                        View
                                    </a>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                    No online orders found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- Order Details & Status Update Modal --}}
    @foreach($orders as $order)
        <div id="order-modal-{{ $order->id }}" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 space-y-4 shadow-xl">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-sm font-bold text-gray-900">
                        Order #ON-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </h3>
                    <button type="button" onclick="closeOrderModal({{ $order->id }})"
                        class="text-gray-500 hover:text-gray-800 font-bold">✕</button>
                </div>

                <div class="text-xs space-y-1.5 text-gray-700">
                    <p><span class="font-bold">Customer:</span> {{ $order->full_name }}</p>
                    <p><span class="font-bold">Contact:</span> {{ $order->contact_number }}</p>
                    <p><span class="font-bold">Type:</span> {{ $order->order_type }}</p>
                    @if($order->delivery_address)
                        <p><span class="font-bold">Address:</span> {{ $order->delivery_address }}</p>
                    @endif
                    <p><span class="font-bold">Payment:</span> {{ $order->payment_method }}</p>
                </div>

                <div class="border-t pt-3">
                    <p class="text-xs font-bold text-gray-900 mb-2">Items</p>
                    <div class="space-y-1 text-xs text-gray-700 max-h-36 overflow-y-auto">
                        @foreach($order->items as $item)
                            <div class="flex justify-between">
                                <span>{{ $item->product_name }} x{{ $item->quantity }}</span>
                                <span>₱{{ number_format($item->line_total, 2) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="flex justify-between font-bold text-xs text-gray-900 border-t mt-2 pt-2">
                        <span>Total</span>
                        <span>₱{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('online-orders.status', $order) }}"
                    class="border-t pt-3 flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="flex-1 bg-[#d9dbdb] text-xs font-semibold rounded-lg px-3 py-2">
                        <option value="New" {{ in_array($order->status, ['New', 'Pending']) ? 'selected' : '' }}>New</option>
                        <option value="Preparing" {{ $order->status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    <button type="submit" class="bg-[#1b5335] text-white text-xs font-bold px-4 py-2 rounded-lg">
                        Update
                    </button>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        function openOrderModal(id) {
            const modal = document.getElementById('order-modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeOrderModal(id) {
            const modal = document.getElementById('order-modal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
@endsection