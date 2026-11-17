@extends('layouts.blade-layout')

@section('content')
    <div class="space-y-5 lg:space-y-6">
        <!-- Mobile Page Title -->
        <h1 class="lg:hidden text-2xl font-extrabold text-slate-900">Stock Alert</h1>

        @if(session('success'))
            <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded-xl text-xs font-semibold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Top Export Button Row --}}
        <div class="flex justify-end">
            <a href="{{ route('stock-alert.export') }}"
                style="background-color: #165334; color: #ffffff;"
                class="w-full lg:w-auto hover:opacity-95 font-bold text-xs px-6 py-3.5 lg:py-3 rounded-xl lg:rounded-lg transition shadow-sm text-center">
                Export Orders
            </a>
        </div>

        {{-- Filter Bar Row (2x2 Grid on Mobile, 4-Col Grid on Desktop) --}}
        <form method="GET" action="{{ route('stock-alert.index') }}"
            class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 lg:gap-4">
            <select name="category" onchange="this.form.submit()"
                class="bg-white lg:bg-[#d9dbdb] border border-slate-200 lg:border-0 text-slate-700 lg:text-gray-800 text-xs font-semibold lg:font-medium rounded-xl lg:rounded-lg px-3.5 lg:px-4 py-3 focus:outline-none">
                <option value="All">Category</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>

            <select name="alert_level" onchange="this.form.submit()"
                class="bg-white lg:bg-[#d9dbdb] border border-slate-200 lg:border-0 text-slate-700 lg:text-gray-800 text-xs font-semibold lg:font-medium rounded-xl lg:rounded-lg px-3.5 lg:px-4 py-3 focus:outline-none">
                <option value="All">Alert Level</option>
                <option value="Out of Stock" {{ request('alert_level') === 'Out of Stock' ? 'selected' : '' }}>Out of Stock</option>
                <option value="Critical" {{ request('alert_level') === 'Critical' ? 'selected' : '' }}>Critical</option>
                <option value="Low" {{ request('alert_level') === 'Low' ? 'selected' : '' }}>Low</option>
            </select>

            <select name="supplier" onchange="this.form.submit()"
                class="bg-white lg:bg-[#d9dbdb] border border-slate-200 lg:border-0 text-slate-700 lg:text-gray-800 text-xs font-semibold lg:font-medium rounded-xl lg:rounded-lg px-3.5 lg:px-4 py-3 focus:outline-none">
                <option value="All">Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier }}" {{ request('supplier') === $supplier ? 'selected' : '' }}>
                        {{ $supplier }}
                    </option>
                @endforeach
            </select>

            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search products"
                class="bg-white lg:bg-[#d9dbdb] border border-slate-200 lg:border-0 text-slate-700 lg:text-gray-800 placeholder-slate-400 lg:placeholder-gray-700 text-xs font-semibold lg:font-medium rounded-xl lg:rounded-lg px-3.5 lg:px-4 py-3 focus:outline-none">
        </form>
        {{-- 3 Summary KPI Cards (Single Card with Rows on Mobile, 3 Cards on Desktop) --}}
        <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 p-5 lg:p-0 shadow-sm lg:shadow-none grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6">
            <div class="flex lg:block justify-between items-center border-b border-slate-100 lg:border-0 pb-3.5 lg:pb-6 lg:bg-[#d9dbdb] lg:rounded-xl lg:py-6 lg:px-6 lg:text-center">
                <p class="text-xs font-semibold lg:font-medium text-slate-500 lg:text-gray-800 lg:mb-3">Low Stock Items</p>
                <p class="text-sm lg:text-xl font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">{{ $lowStockCount }}</p>
            </div>

            <div class="flex lg:block justify-between items-center border-b border-slate-100 lg:border-0 pb-3.5 lg:pb-6 lg:bg-[#d9dbdb] lg:rounded-xl lg:py-6 lg:px-6 lg:text-center">
                <p class="text-xs font-semibold lg:font-medium text-slate-500 lg:text-gray-800 lg:mb-3">Out of Stock</p>
                <p class="text-sm lg:text-xl font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">{{ $outOfStockCount }}</p>
            </div>

            <div class="flex lg:block justify-between items-center lg:bg-[#d9dbdb] lg:rounded-xl lg:py-6 lg:px-6 lg:text-center">
                <p class="text-xs font-semibold lg:font-medium text-slate-500 lg:text-gray-800 lg:mb-3">Pending Restocks</p>
                <p class="text-sm lg:text-xl font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">{{ $pendingRestocksCount }}</p>
            </div>
        </div>
        {{-- Products Needing Attention Table / Mobile Cards --}}
        <div>
            <h2 class="text-sm font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-3">Products Needing Attention</h2>

            <div class="lg:bg-white lg:border lg:border-gray-300 overflow-x-auto">
                <table class="w-full text-left border-collapse block lg:table">
                    <thead class="hidden lg:table-header-group">
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
                    <tbody class="block lg:table-row-group space-y-3 lg:space-y-0 lg:divide-y lg:divide-gray-200 text-xs text-gray-800">
                        @forelse($products as $product)
                            @php
                                $badgeClass = match ($product->computed_alert) {
                                    'Out of Stock' => 'bg-[#f4d7d7] text-[#c05656]',
                                    'Critical' => 'bg-[#f4e4cd] text-[#b47b38]',
                                    'Low' => 'bg-[#d4d4d4] text-white',
                                    default => 'bg-gray-200 text-gray-700',
                                };
                            @endphp
                            <tr class="block lg:table-row bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 p-4 lg:p-0 shadow-sm lg:shadow-none hover:bg-gray-50 transition">
                                <td class="block lg:table-cell lg:py-3.5 lg:px-4 font-extrabold lg:font-medium text-sm lg:text-xs text-slate-900">
                                    {{ $product->computed_sku }}
                                </td>
                                <td class="block lg:table-cell pt-0.5 pb-3 lg:py-3.5 lg:px-4 text-[11px] lg:text-xs text-slate-600 lg:text-gray-800">
                                    {{ $product->name }}
                                </td>
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-3.5 lg:px-4 lg:text-center">
                                    <span class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">STOCK</span>
                                    <span class="font-bold lg:font-medium text-slate-900">{{ $product->computed_stock }}</span>
                                </td>
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-3.5 lg:px-4 lg:text-center">
                                    <span class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">MIN LEVEL</span>
                                    <span class="font-bold lg:font-normal text-slate-900">{{ $product->computed_min }}</span>
                                </td>
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-3.5 lg:px-4 lg:text-center">
                                    <span class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">ALERT</span>
                                    <span class="font-extrabold lg:font-bold text-slate-900 lg:inline-block lg:min-w-[100px] lg:py-1 lg:px-3 lg:rounded-full lg:text-[11px] {{ $badgeClass }}">
                                        {{ $product->computed_alert }}
                                    </span>
                                </td>
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-3.5 lg:px-4">
                                    <span class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">SUPPLIER</span>
                                    <span class="font-semibold lg:font-normal text-slate-700">{{ $product->computed_supplier }}</span>
                                </td>
                                <td class="block lg:table-cell pt-2.5 lg:py-3.5 lg:px-4 text-right">
                                    <button type="button" onclick="openReorderModal({{ $product->id }})"
                                        class="text-[11px] lg:text-xs text-slate-500 lg:text-gray-700 hover:text-black font-bold lg:font-medium cursor-pointer">
                                        Reorder
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr class="block lg:table-row bg-white rounded-2xl border border-slate-200 lg:border-0">
                                <td colspan="7" class="block lg:table-cell py-8 px-4 text-center text-gray-500">
                                    All products are sufficiently stocked!
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
                            class="text-gray-500 hover:text-gray-800 font-bold">X</button>
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
                                style="background-color: #165334; color: #ffffff;"
                                class="hover:opacity-95 text-xs font-bold px-4 py-2 rounded-lg">
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
    </div>
@endsection
