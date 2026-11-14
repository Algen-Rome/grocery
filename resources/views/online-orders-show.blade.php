@extends('layouts.blade-layout')

@section('content')
    @php
        $statusLabel = $order->status === 'Pending' ? 'New' : $order->status;
        $badgeClass = match ($statusLabel) {
            'Preparing' => 'bg-[#bfe3ce] text-[#1b5335]',
            'New' => 'bg-[#f4e4cd] text-[#b47b38]',
            'Delivered' => 'bg-[#bfe3ce] text-[#1b5335]',
            'Cancelled' => 'bg-[#d4d4d4] text-white',
            default => 'bg-gray-200 text-gray-700',
        };
        $deliveryFee = $order->order_type === 'Delivery' ? 50 : 0;
        $totalAmount = $order->subtotal + $deliveryFee;
    @endphp

    <div class="max-w-3xl mx-auto">
        <div class="mb-3 flex justify-between items-center">
            <a href="{{ route('online-orders.index') }}" class="text-xs font-bold text-gray-700 hover:text-black">
                ← Back to Online Orders
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-[#d9dbdb] rounded-3xl p-6 sm:p-8 shadow-sm text-xs text-gray-900">
            {{-- Section 1: Order Details --}}
            <div class="flex justify-between items-start pb-4 border-b border-gray-400">
                <div>
                    <h2 class="text-sm font-bold text-gray-900">Order Details</h2>
                    <p class="text-xs font-semibold text-gray-800 mt-1">
                        ORDER ID: #ON-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <span
                    class="inline-block min-w-[90px] text-center py-1 px-3 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- Section 2: Customer Information --}}
            <div class="py-4 border-b border-gray-400 space-y-2">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Customer Information</h3>

                <div class="flex justify-between">
                    <span class="text-gray-800">Customer Name</span>
                    <span class="font-bold text-gray-900">{{ $order->full_name }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-800">Contact Number</span>
                    <span class="font-bold text-gray-900">{{ $order->contact_number }}</span>
                </div>

                @if($order->delivery_address)
                    <div class="pt-1">
                        <p class="text-gray-800">Delivery Address</p>
                        <p class="font-bold text-gray-900 mt-0.5">{{ $order->delivery_address }}</p>
                    </div>
                @endif
            </div>

            {{-- Section 3: Order Information --}}
            <div class="py-4 border-b border-gray-400 space-y-2">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Order Information</h3>

                <div class="flex justify-between">
                    <span class="text-gray-800">Order Type</span>
                    <span class="font-bold text-gray-900">{{ $order->order_type }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-800">Order Date</span>
                    <span class="font-bold text-gray-900">{{ $order->created_at->format('F d, Y') }}</span>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-800">Payment Method</span>
                    <span class="font-bold text-gray-900">{{ $order->payment_method }}</span>
                </div>
            </div>
            {{-- Section 4: Ordered Products --}}
            <div class="py-4 border-b border-gray-400 space-y-2">
                <h3 class="text-sm font-bold text-gray-900 mb-2">Ordered Products</h3>

                @foreach($order->items as $item)
                    <div class="flex justify-between items-start py-0.5">
                        <div>
                            <p class="font-medium text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-[11px] text-gray-700">{{ $item->quantity }}x</p>
                        </div>
                        <span class="font-bold text-gray-900">₱{{ number_format($item->line_total, 0) }}</span>
                    </div>
                @endforeach

                <div class="pt-3 space-y-1.5">
                    <div class="flex justify-between">
                        <span class="text-gray-800">Subtotal</span>
                        <span class="font-bold text-gray-900">₱{{ number_format($order->subtotal, 0) }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-800">Delivery Fee</span>
                        <span class="font-bold text-gray-900">₱{{ number_format($deliveryFee, 0) }}</span>
                    </div>

                    <div class="flex justify-between pt-1 text-sm">
                        <span class="font-bold text-gray-900">Total Amount</span>
                        <span class="font-bold text-gray-900">₱{{ number_format($totalAmount, 0) }}</span>
                    </div>
                </div>
            </div>

            {{-- Section 5: Order Management --}}
            <form method="POST" action="{{ route('online-orders.status', $order) }}" class="pt-4 space-y-3">
                @csrf
                @method('PATCH')

                <div>
                    <h3 class="text-sm font-bold text-gray-900">Order Management</h3>
                    <p class="text-[11px] text-gray-700 mt-0.5">Update Order Status</p>
                </div>

                <select name="status"
                    class="w-full border border-gray-500 bg-transparent rounded-xl px-4 py-2.5 text-center text-xs font-bold text-gray-900 focus:outline-none focus:border-black">
                    <option value="New" {{ in_array($order->status, ['New', 'Pending']) ? 'selected' : '' }}>New</option>
                    <option value="Preparing" {{ $order->status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                    <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>

                <div class="flex justify-between items-center text-[11px] text-gray-700 pt-1">
                    <span>Current Status: {{ $statusLabel }}</span>
                    <span class="inline-block min-w-[90px] text-center py-1 px-3 rounded-full font-bold {{ $badgeClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="pt-2 flex justify-center">
                    <button type="submit"
                        class="w-full sm:w-64 border border-gray-600 hover:bg-gray-300 text-gray-900 font-bold text-xs py-2.5 rounded-xl transition">
                        Update Status
                    </button>
                </div>
            </form>
            {{-- Delete Order Action --}}
            <form method="POST" action="{{ route('online-orders.destroy', $order) }}"
                onsubmit="return confirm('Are you sure you want to permanently delete this order?');"
                class="pt-2 flex justify-center">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full sm:w-64 border border-red-400 text-red-700 hover:bg-red-100 font-bold text-xs py-2.5 rounded-xl transition">
                    Delete Order
                </button>
            </form>

        </div>
    </div>
@endsection