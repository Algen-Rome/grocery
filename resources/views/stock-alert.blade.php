@extends('layouts.blade-layout')

@section('content')
    <div class="space-y-6">
        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Export Button Row --}}
        <div class="flex justify-end">
            <a href="{{ route('stock-alert.export') }}"
                class="bg-[#1b5335] hover:bg-[#143f28] text-white font-bold text-xs px-6 py-3 rounded-lg transition shadow-sm">
                Export Orders
            </a>
        </div>

        {{-- Filter Bar Row (Category, Alert Level, Supplier, Search products) --}}
        <form method="GET" action="{{ route('stock-alert.index') }}"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <select name="category" onchange="this.form.submit()"
                class="bg-[#d9dbdb] text-gray-800 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
                <option value="All">Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <select name="alert_level" onchange="this.form.submit()"
                class="bg-[#d9dbdb] text-gray-800 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
                <option value="All">Alert Level</option>
                <option value="Out of Stock" {{ request('alert_level') === 'Out of Stock' ? 'selected' : '' }}>Out of Stock
                </option>
                <option value="Critical" {{ request('alert_level') === 'Critical' ? 'selected' : '' }}>Critical</option>
                <option value="Low" {{ request('alert_level') === 'Low' ? 'selected' : '' }}>Low</option>
            </select>

            <select name="supplier" onchange="this.form.submit()"
                class="bg-[#d9dbdb] text-gray-800 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
                <option value="All">Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier }}" {{ request('supplier') === $supplier ? 'selected' : '' }}>
                        {{ $supplier }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products"
                class="bg-[#d9dbdb] text-gray-800 placeholder-gray-700 text-xs font-medium rounded-lg px-4 py-3 focus:outline-none">
        </form>

        {{-- 3 Summary KPI Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#d9dbdb] rounded-3xl py-6 px-6 text-center">
                <p class="text-xs font-medium text-gray-800 mb-3">Low Stock Items</p>
                <p class="text-xl font-bold text-gray-900">{{ $lowStockCount }}</p>
            </div>

            <div class="bg-[#d9dbdb] rounded-3xl py-6 px-6 text-center">
                <p class="text-xs font-medium text-gray-800 mb-3">Out of Stock</p>
                <p class="text-xl font-bold text-gray-900">{{ $outOfStockCount }}</p>
            </div>

            <div class="bg-[#d9dbdb] rounded-3xl py-6 px-6 text-center">
                <p class="text-xs font-medium text-gray-800 mb-3">Pending Restocks</p>
                <p class="text-xl font-bold text-gray-900">{{ $pendingRestocksCount }}</p>
            </div>
        </div>

        {{-- Products Needing Attention Table --}}
        <div>
            <h2 class="text-sm font-bold text-gray-900 mb-3">Products Needing Attention</h2>

            <div class="bg-white border border-gray-300 overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#d9dbdb] text-gray-900 text-xs font-bold">
                            <th class="py-3.5 px-4">SKU</th>
                            <th class="py-3.5 px-4">PRODUCT</th>
                            <th class="py-3.5 px-4 text-center">STOCK</th>
                            <th class="py-3.5 px-4 text-center">MIN LEVEL</th>
                            <th class="py-3.5 px-4 text-center">ALERT</th>
                            <th class="py-3.5 px-4">SUPPLIER</th>
                            <th class="py-3.5 px-4"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs text-gray-800">
                        @forelse($products as $product)
                            @php
                                $badgeClass = match ($product->computed_alert) {
                                    'Out of Stock' => 'bg-[#f4d7d7] text-[#c05656]',
                                    'Critical' => 'bg-[#f4e4cd] text-[#b47b38]',
                                    'Low' => 'bg-[#d4d4d4] text-white',
                                    default => 'bg-gray-200 text-gray-700',
                                };
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="py-3.5 px-4 font-medium">{{ $product->computed_sku }}</td>
                                <td class="py-3.5 px-4">{{ $product->name }}</td>
                                <td class="py-3.5 px-4 text-center font-medium">{{ $product->computed_stock }}</td>
                                <td class="py-3.5 px-4 text-center">{{ $product->computed_min }}</td>
                                <td class="py-3.5 px-4 text-center">
                                    <span
                                        class="inline-block min-w-[100px] py-1 px-3 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                        {{ $product->computed_alert }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">{{ $product->computed_supplier }}</td>
                                <td class="py-3.5 px-4 text-right">
                                    <button type="button" onclick="openReorderModal({{ $product->id }})"
                                        class="text-gray-700 hover:text-black font-medium">
                                        Reorder
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 px-4 text-center text-gray-500">
                                    All products are sufficiently stocked!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Reorder Modal --}}
    @foreach($products as $product)
        <div id="reorder-modal-{{ $product->id }}"
            class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-2xl max-w-sm w-full p-6 space-y-4 shadow-xl">
                <div class="flex justify-between items-center border-b pb-3">
                    <h3 class="text-sm font-bold text-gray-900">Reorder {{ $product->name }}</h3>
                    <button type="button" onclick="closeReorderModal({{ $product->id }})"
                        class="text-gray-500 hover:text-gray-800 font-bold">✕</button>
                </div>

                <div class="text-xs space-y-1 text-gray-700">
                    <p><span class="font-bold">SKU:</span> {{ $product->computed_sku }}</p>
                    <p><span class="font-bold">Supplier:</span> {{ $product->computed_supplier }}</p>
                    <p><span class="font-bold">Current Stock:</span> {{ $product->computed_stock }} (Min:
                        {{ $product->computed_min }})</p>
                </div>

                <form method="POST" action="{{ route('stock-alert.reorder', $product) }}" class="space-y-3 pt-1">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1">Restock Quantity</label>
                        <input type="number" name="restock_qty"
                            value="{{ max(10, $product->computed_min - $product->computed_stock) }}" min="1" required
                            class="w-full bg-[#d9dbdb] rounded-lg px-3 py-2 text-xs font-semibold text-gray-900 focus:outline-none">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="closeReorderModal({{ $product->id }})"
                            class="px-4 py-2 text-xs font-semibold text-gray-600 hover:text-gray-900">
                            Cancel
                        </button>
                        <button type="submit"
                            class="bg-[#1b5335] hover:bg-[#143f28] text-white text-xs font-bold px-4 py-2 rounded-lg">
                            Confirm Reorder
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        function openReorderModal(id) {
            const modal = document.getElementById('reorder-modal-' + id);
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeReorderModal(id) {
            const modal = document.getElementById('reorder-modal-' + id);
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }
    </script>
@endsection