@extends('layouts.blade-layout')

@section('content')
<div class="min-h-screen bg-slate-50 p-8 flex justify-center items-center">
    <div class="bg-white border border-gray-100 rounded-2xl p-8 max-w-lg w-full shadow-sm">
        
        <!-- Header Ribbon -->
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-black text-gray-900 tracking-wide">Add New Product</h2>
            <a href="{{ route('dashboard') }}" class="text-xs font-bold text-gray-400 hover:text-gray-600 transition">← Cancel</a>
        </div>

        <!-- Validation Error Logger -->
        @if ($errors->any())
            <div class="mb-4 p-3 bg-rose-50 text-rose-600 text-xs font-bold rounded-xl space-y-1">
                @foreach ($errors->all() as $error)
                    <p>• {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Creation Form Block -->
        <form action="{{ route('products.store') }}" method="POST" class="space-y-5">
            @csrf
            
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Product / Item Name</label>
                <input type="text" name="product_name" value="{{ old('product_name') }}" required placeholder="e.g. Premium Jasmine Rice (5kg)"
                    class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm font-semibold focus:outline-none focus:bg-white focus:border-emerald-500 transition" />
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Retail Price (₱)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0.00"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm font-semibold focus:outline-none focus:bg-white focus:border-emerald-500 transition" />
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Initial Stock Qty</label>
                    <input type="number" name="stock_count" value="{{ old('stock_count') }}" required placeholder="e.g. 50"
                        class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm font-semibold focus:outline-none focus:bg-white focus:border-emerald-500 transition" />
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Low-Stock Alert Level (Reorder Threshold)</label>
                <input type="number" name="reorder_level" value="{{ old('reorder_level', 10) }}" required placeholder="Triggers warning notification at this number or below"
                    class="w-full rounded-xl bg-gray-50 border border-gray-200 px-4 py-3 text-sm font-semibold focus:outline-none focus:bg-white focus:border-emerald-500 transition" />
                <p class="text-[10px] text-gray-400 font-medium mt-1.5">You will get automatic item warnings when stock counts fall to or below this level.</p>
            </div>

            <button type="submit" 
                class="w-full bg-emerald-800 text-white font-black text-sm py-4 rounded-xl hover:bg-emerald-900 transition tracking-wide shadow-sm mt-4 block text-center">
                Save Product to Inventory
            </button>
        </form>
    </div>
</div>
@endsection