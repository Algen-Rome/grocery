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

        <h1 class="lg:hidden text-2xl font-extrabold text-slate-900 mb-4">Order details</h1>

        @if(session('success'))
            <div class="mb-4 bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <div class="lg:bg-[#d9dbdb] lg:rounded-3xl lg:p-8 lg:shadow-sm text-xs text-gray-900 space-y-4 lg:space-y-0">
            {{-- Section 1: Order Details --}}
            <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 lg:border-b lg:border-gray-400 p-5 lg:p-0 lg:pb-4 shadow-sm lg:shadow-none flex flex-col lg:flex-row lg:justify-between lg:items-start gap-3">
                <div>
                    <h2 class="text-sm font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">Order Details</h2>
                    <p class="text-[11px] lg:text-xs font-semibold text-slate-500 lg:text-gray-800 mt-1">
                        ORDER ID: #ON-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>

                <span
                    class="w-full lg:w-auto lg:inline-block lg:min-w-[90px] text-left lg:text-center py-2.5 lg:py-1 px-3.5 lg:px-3 rounded-xl lg:rounded-full text-xs lg:text-[11px] font-bold {{ $badgeClass }}">
                    {{ $statusLabel }}
                </span>
            </div>

            {{-- Section 2: Customer Information --}}
            <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 lg:border-b lg:border-gray-400 p-5 lg:p-0 lg:py-4 shadow-sm lg:shadow-none space-y-2.5 lg:space-y-2">
                <h3 class="text-sm font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-2">Customer Information</h3>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Customer Name</span>
                    <span class="font-bold text-slate-900 lg:text-gray-900">{{ $order->full_name }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Contact Number</span>
                    <span class="font-bold text-slate-900 lg:text-gray-900">{{ $order->contact_number }}</span>
                </div>

                @if($order->delivery_address)
                    <div class="pt-1">
                        <p class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Delivery Address</p>
                        <p class="font-bold text-slate-900 lg:text-gray-900 mt-1 lg:mt-0.5">{{ $order->delivery_address }}</p>
                    </div>
                @endif
            </div>
            {{-- Section 3: Order Information --}}
            <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 lg:border-b lg:border-gray-400 p-5 lg:p-0 lg:py-4 shadow-sm lg:shadow-none space-y-2.5 lg:space-y-2">
                <h3 class="text-sm font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-2">Order Information</h3>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Order Type</span>
                    <span class="font-bold text-slate-900 lg:text-gray-900">{{ $order->order_type }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Order Date</span>
                    <span class="font-bold text-slate-900 lg:text-gray-900">{{ $order->created_at->format('F d, Y') }}</span>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Payment Method</span>
                    <span class="font-bold text-slate-900 lg:text-gray-900">{{ $order->payment_method }}</span>
                </div>
            </div>

            {{-- Section 4: Ordered Products --}}
            <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 lg:border-b lg:border-gray-400 p-5 lg:p-0 lg:py-4 shadow-sm lg:shadow-none space-y-2.5 lg:space-y-2">
                <h3 class="text-sm font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-2">Ordered Products</h3>

                @foreach($order->items as $item)
                    <div class="flex justify-between items-start py-1 lg:py-0.5">
                        <div>
                            <p class="font-semibold lg:font-medium text-slate-700 lg:text-gray-900">{{ $item->product_name }}</p>
                            <p class="text-[11px] text-slate-400 lg:text-gray-700">{{ $item->quantity }}x</p>
                        </div>
                        <span class="font-bold text-slate-900 lg:text-gray-900">₱{{ number_format($item->line_total, 0) }}</span>
                    </div>
                @endforeach

                <div class="pt-2 lg:pt-3 space-y-2 lg:space-y-1.5 border-t border-slate-100 lg:border-0">
                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Subtotal</span>
                        <span class="font-bold text-slate-900 lg:text-gray-900">₱{{ number_format($order->subtotal, 0) }}</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-slate-500 lg:text-gray-800 font-semibold lg:font-normal">Delivery Fee</span>
                        <span class="font-bold text-slate-900 lg:text-gray-900">₱{{ number_format($deliveryFee, 0) }}</span>
                    </div>

                    <div class="flex justify-between items-center pt-1 text-xs lg:text-sm">
                        <span class="font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">Total Amount</span>
                        <span class="font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">₱{{ number_format($totalAmount, 0) }}</span>
                    </div>
                </div>
            </div>
            {{-- Section 5: Order Management --}}
            <form method="POST" action="{{ route('online-orders.status', $order) }}" class="lg:pt-4 space-y-4 lg:space-y-3">
                @csrf
                @method('PATCH')

                <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 p-5 lg:p-0 shadow-sm lg:shadow-none space-y-3">
                    <div>
                        <h3 class="text-sm font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">Order Management</h3>
                        <p class="text-[11px] text-slate-500 lg:text-gray-700 mt-0.5">Update Order Status</p>
                    </div>

                    <select name="status"
                        class="w-full bg-slate-100 lg:bg-transparent border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-left lg:text-center text-xs font-bold text-gray-900 focus:outline-none focus:border-black">
                        <option value="New" {{ in_array($order->status, ['New', 'Pending']) ? 'selected' : '' }}>New</option>
                        <option value="Preparing" {{ $order->status === 'Preparing' ? 'selected' : '' }}>Preparing</option>
                        <option value="Delivered" {{ $order->status === 'Delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="Cancelled" {{ $order->status === 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-2 text-[11px] text-slate-500 lg:text-gray-700 pt-1">
                        <span>Current Status: {{ $statusLabel }}</span>
                        <span class="w-full lg:w-auto lg:inline-block lg:min-w-[90px] text-left lg:text-center py-2.5 lg:py-1 px-3.5 lg:px-3 rounded-xl lg:rounded-full text-xs lg:text-[11px] font-bold {{ $badgeClass }}">
                            {{ $statusLabel }}
                        </span>
                    </div>
                </div>

                <div class="pt-1 lg:pt-2 flex justify-center">
                    <button type="submit"
                        style="background-color: #165334; color: #ffffff;"
                        class="w-full lg:w-64 hover:opacity-95 font-bold text-xs py-3.5 lg:py-2.5 rounded-xl transition cursor-pointer shadow-sm">
                        Update Status
                    </button>
                </div>
            </form>

            {{-- Delete Order Action --}}
            <form method="POST" action="{{ route('online-orders.destroy', $order) }}"
                onsubmit="return confirm('Are you sure you want to permanently delete this order?');"
                class="pt-1 lg:pt-2 flex justify-center">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full lg:w-64 bg-white lg:bg-transparent border border-red-400 text-red-700 hover:bg-red-100 font-bold text-xs py-3.5 lg:py-2.5 rounded-xl transition cursor-pointer">
                    Delete Order
                </button>
            </form>
        </div>
    </div>
@endsection
