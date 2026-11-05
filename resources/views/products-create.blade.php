@extends('layouts.blade-layout')

@section('content')
    <div class="flex justify-center items-center min-h-[70vh]">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 w-full max-w-md p-6">

            <div class="flex justify-between items-center mb-6">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-wide">Add New Product</h3>
                <a href="{{ route('products.index') }}"
                    class="text-gray-400 hover:text-slate-600 text-xs font-bold no-underline">
                    &larr; Back to Catalog
                </a>
            </div>

            <!-- Hardened Submit Form Formatted with standard POST parameters cleanly -->
            <form action="{{ route('products.store') }}" method="POST" class="space-y-4 m-0"
                onsubmit="event.stopPropagation();">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Product / Item
                        Name</label>
                    <input type="text" name="name" required placeholder="e.g. Premium Jasmine Rice (5kg)"
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Retail Price
                            (₱)</label>
                        <input type="number" name="price" step="0.01" min="0" required placeholder="0.00"
                            class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Initial Stock
                            Qty</label>
                        <input type="number" name="stock_count" min="0" required placeholder="e.g. 50"
                            class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Low-Stock Alert
                        Level</label>
                    <input type="number" name="reorder_level" min="0" value="10" required
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                    <p class="text-gray-400 text-[9px] mt-1 font-medium">You will get automatic item warnings when stock
                        counts fall to or below this level.</p>
                </div>

                <button type="submit" onclick="event.stopPropagation();"
                    class="w-full py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-lg transition text-center block border-0 cursor-pointer text-xs uppercase tracking-wider shadow-sm">
                    Save Product to Inventory
                </button>
            </form>
        </div>
    </div>
@endsection