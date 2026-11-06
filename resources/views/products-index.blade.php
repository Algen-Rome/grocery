@extends('layouts.blade-layout')

@section('content')
    <div class="flex min-h-screen bg-slate-50">

        <!-- Main Working Canvas -->
        <main class="flex-1 p-8">

            <!-- 🌟 MOCKUP REALIGNMENT: Floats the button directly on the right margin below the staff name -->
            <div class="flex justify-end mb-6">
                <button type="button" 
                        onclick="toggleAddProductModal()" 
                        class="px-10 py-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-sm rounded-xl shadow-sm transition border-0 cursor-pointer tracking-wide">
                    +Add Products
                </button>
            </div>

            <!-- 🌟 2. Wrapped the entire creation card layout inside a beautiful, hidden floating modal layer -->
                        <!-- Floating Overlay Modal Window Layer for Adding Products -->
            <!-- Floating Overlay Modal Window Layer for Adding Products -->
            <div id="addProductModal" class="hidden fixed inset-0 bg-black/45 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden p-6 relative">
                    
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-wide">Add New Product</h3>
                        <button type="button" onclick="toggleAddProductModal()" class="text-gray-400 hover:text-gray-600 text-xs font-bold bg-transparent border-0 cursor-pointer">
                            Cancel
                        </button>
                    </div>

                    <!-- 🌟 Removed standard form tag to prevent Inertia from intercepting and breaking fields -->
                    <div class="space-y-4 m-0">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Product / Item Name</label>
                            <input type="text" id="store_name" required placeholder="e.g. Premium Jasmine Rice (5kg)" class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Retail Price (₱)</label>
                                <input type="number" id="store_price" step="0.01" min="0" required placeholder="0.00" class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Initial Stock Qty</label>
                                <input type="number" id="store_stock_count" min="0" required placeholder="e.g. 50" class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Low-Stock Alert Level</label>
                            <input type="number" id="store_reorder_level" min="0" value="10" required class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                            <p class="text-gray-400 text-[9px] mt-1 font-medium">You will get automatic item warnings when stock counts fall to or below this level.</p>
                        </div>

                        <!-- 🌟 Programmatic background submit execution -->
                        <button type="button" 
                                onclick="submitNewProductDirectly()"
                                class="w-full py-2.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-lg transition text-center block border-0 cursor-pointer text-xs uppercase tracking-wider shadow-sm">
                            Save Product to Inventory
                        </button>
                    </div>
                </div>
            </div>


            <!-- Filter Control Matrix Sheet Wrapper -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <select
                    class="bg-gray-200 border border-transparent rounded-xl p-3 text-xs font-bold text-gray-500 focus:outline-none focus:bg-white focus:border-gray-300">
                    <option>Category</option>
                </select>
                <select
                    class="bg-gray-200 border border-transparent rounded-xl p-3 text-xs font-bold text-gray-500 focus:outline-none focus:bg-white focus:border-gray-300">
                    <option>Stock Status</option>
                </select>
                <select
                    class="bg-gray-200 border border-transparent rounded-xl p-3 text-xs font-bold text-gray-500 focus:outline-none focus:bg-white focus:border-gray-300">
                    <option>Sort By</option>
                </select>
                <input type="text" placeholder="Search orders"
                    class="bg-gray-200 border border-transparent rounded-xl p-3 text-xs font-bold text-gray-500 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-300" />
            </div>

            <!-- KPI Summary Cards Grid Row Block -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-gray-200 p-6 rounded-3xl flex flex-col justify-center items-center text-center h-28 relative shadow-sm">
                    <p class="text-xs font-bold text-gray-500 tracking-wide uppercase">Total Products <span
                            class="text-[10px] text-emerald-600 font-extrabold ml-1">+3%</span></p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ number_format($totalProducts) }}</h3>
                </div>
                <div
                    class="bg-gray-200 p-6 rounded-3xl flex flex-col justify-center items-center text-center h-28 relative shadow-sm">
                    <p class="text-xs font-bold text-gray-500 tracking-wide uppercase">Low Stock Items <span
                            class="text-[10px] text-amber-600 font-extrabold ml-1">+5</span></p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $lowStockItems }}</h3>
                </div>
                <div
                    class="bg-gray-200 p-6 rounded-3xl flex flex-col justify-center items-center text-center h-28 relative shadow-sm">
                    <p class="text-xs font-bold text-gray-500 tracking-wide uppercase">Out of Stock <span
                            class="text-[10px] text-rose-600 font-extrabold ml-1">+2</span></p>
                    <h3 class="text-2xl font-black text-gray-900 mt-1">{{ $outOfStock }}</h3>
                </div>
            </div>

            <!-- Products Matrix Grid Table Section -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs font-semibold text-gray-700">
                        <thead>
                            <tr class="border-b border-gray-200 text-[10px] font-black text-gray-900 uppercase tracking-wider bg-gray-50/50">
                                <th class="py-3 px-4 font-black">PRODUCT</th>
                                <th class="py-3 px-4 font-black">CATEGORY</th>
                                <th class="py-3 px-4 font-black">PRICE</th>
                                <th class="py-3 px-4 font-black">STOCK</th>
                                <th class="py-3 px-4 font-black">STATUS</th>
                                <th class="py-3 px-4 font-black">LAST UPDATED</th>
                                <th class="py-3 px-4 font-black text-right">ACTION</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr class="border-b border-gray-100 hover:bg-slate-50/80 transition align-middle">
                                    <td class="py-4 px-4 font-bold text-gray-900">{{ $product->name }}</td>
                                    <td class="py-4 px-4 text-gray-500 font-medium">{{ $product->category ?? 'General' }}</td>
                                    <td class="py-4 px-4 font-bold text-gray-900">₱{{ number_format($product->price, 0) }}</td>
                                    <td class="py-4 px-4 text-gray-600 font-medium">{{ $product->stock_count }}</td>
                                    <td class="py-4 px-4">
                                        @if ($product->stock_count <= 0)
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wide uppercase bg-rose-100 text-rose-600 border border-rose-200/50">Out of Stock</span>
                                        @elseif ($product->stock_count <= ($product->reorder_level ?? 10))
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wide uppercase bg-amber-100 text-amber-600 border border-amber-200/50">Low Stock</span>
                                        @else
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black tracking-wide uppercase bg-emerald-100 text-emerald-600 border border-emerald-200/50">In Stock</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-4 text-gray-500 font-medium">
                                        {{ isset($product->updated_at) ? \Carbon\Carbon::parse($product->updated_at)->format('M d, Y') : \Carbon\Carbon::now()->format('M d, Y') }}
                                    </td>
                                    <td class="py-4 px-4 text-right whitespace-nowrap text-[11px] font-bold text-gray-400">
                                        <div class="flex items-center justify-end space-x-2">
                                            <!-- 🌟 EDIT BUTTON TRIGGER -->
                                            <button type="button" 
                                                    onclick="openEditModal({{ json_encode($product) }})" 
                                                    class="text-gray-500 hover:text-gray-900 bg-transparent border-0 p-0 m-0 font-bold text-[11px] cursor-pointer no-underline">
                                                Edit
                                            </button>
                                            <span>&bull;</span>
                                            <!-- 🌟 ADJUST STOCK TRIGGER -->
                                            <button type="button" 
                                                    onclick="openAdjustStockModal({{ json_encode($product) }})" 
                                                    class="text-gray-500 hover:text-gray-900 bg-transparent border-0 p-0 m-0 font-bold text-[11px] cursor-pointer no-underline">
                                                Adjust Stock
                                            </button>
                                            <span>&bull;</span>
                                            <a href="{{ route('products.directDelete', $product->id) }}"
                                               class="text-rose-500 hover:text-rose-700 transition no-underline"
                                               onclick="event.stopPropagation(); return confirm('Are you sure you want to permanently delete \'{{ $product->name }}\'? This cannot be undone.');">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 🌟 EDIT PRODUCT FLOATING OVERLAY MODAL -->
            <div id="editProductModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden p-6 relative">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-black text-gray-900 uppercase tracking-wide">Edit Product Details</h3>
                        <button type="button" onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-sm font-bold bg-transparent border-0 cursor-pointer">&mdash; Cancel</button>
                    </div>
                    <form id="editProductForm" method="POST" class="space-y-4 m-0" onsubmit="event.stopPropagation();">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Product Name</label>
                            <input type="text" id="edit_name" name="name" required class="w-full border border-gray-200 rounded-xl p-3 text-sm text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Price (₱)</label>
                                <input type="number" id="edit_price" name="price" step="0.01" min="0" required class="w-full border border-gray-200 rounded-xl p-3 text-sm text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Stock Qty</label>
                                <input type="number" id="edit_stock" name="stock_count" min="0" required class="w-full border border-gray-200 rounded-xl p-3 text-sm text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Low-Stock Alert Level</label>
                            <input type="number" id="edit_reorder" name="reorder_level" min="0" required class="w-full border border-gray-200 rounded-xl p-3 text-sm text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                        </div>
                        <button type="submit" onclick="event.stopPropagation();" class="w-full py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl transition text-center border-0 cursor-pointer shadow-md mt-4 text-xs uppercase tracking-wider">
                            Update Product Parameters
                        </button>
                    </form>
                </div>
            </div>

            <!-- 🌟 ADJUST STOCK LEVEL FLOATING OVERLAY MODAL -->
            <!-- 🌟 REDESIGNED QUICK STOCK ADJUST MODAL -->
<!-- 🌟 SIMPLIFIED QUICK STOCK ADJUST MODAL -->
<div id="adjustStockModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-sm overflow-hidden border border-gray-100">
        
        <!-- Header Section -->
        <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-900 tracking-wide uppercase">Adjust Stock</h3>
            <button type="button" onclick="closeAdjustStockModal()" class="text-gray-400 hover:text-gray-600 text-xs font-semibold bg-transparent border-0 cursor-pointer">
                Cancel
            </button>
        </div>

        <!-- Submission Form Layout -->
        <form id="adjustStockForm" method="POST" class="p-5 space-y-4 m-0" onsubmit="event.stopPropagation();">
            @csrf
            
            <!-- Active Target Info Box -->
            <div class="bg-gray-50 border border-gray-100 rounded-lg p-3 text-xs">
                <p class="text-gray-500 font-medium">Product: <span id="adjust_product_title" class="text-gray-900 font-bold">Product Name</span></p>
                <p class="text-gray-500 font-medium mt-1">Current Stock: <span id="adjust_current_display" class="text-emerald-600 font-bold">0</span> units</p>
            </div>

            <!-- Action Dropdown Block -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Adjustment</label>
                <select name="adjustment_type" required class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-700 bg-white focus:outline-none focus:border-emerald-500 transition cursor-pointer">
                    <option value="add">Increase Product Stock</option>
                    <option value="subtract">Decrease Product Stock</option>
                </select>
            </div>

            <!-- Quantity Input Field Block -->
            <div>
                <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Quantity</label>
                <input type="number" name="quantity" min="1" required placeholder="e.g. 10" class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition placeholder-gray-300">
            </div>

            <!-- Action Buttons Row Strip -->
            <button type="submit" onclick="event.stopPropagation();" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg transition text-center border-0 cursor-pointer shadow-sm">
                Apply Adjustments
            </button>
        </form>
    </div>
</div>
        </main>
    </div>

    <!-- 🌟 JavaScript Modal Window Component Controllers -->
    <script>
    function toggleAddProductModal() {
        const modal = document.getElementById('addProductModal');
        modal.classList.toggle('hidden');
    }

    /* Edit Modal View Controller Functions */
    function openEditModal(product) {
        document.getElementById('edit_name').value = product.name;
        document.getElementById('edit_price').value = product.price;
        document.getElementById('edit_stock').value = product.stock_count;
        document.getElementById('edit_reorder').value = product.reorder_level || 10;
        
        // Dynamically point the form action URL straight onto your backend route layout parameters
        document.getElementById('editProductForm').action = '/products/' + product.id + '/update';
        document.getElementById('editProductModal').classList.remove('hidden');
    }
    
    function closeEditModal() {
        document.getElementById('editProductModal').classList.add('hidden');
    }

    /* Stock Adjustment Modal Controller Functions */
    function openAdjustStockModal(product) {
        document.getElementById('adjust_product_title').innerText = product.name;
        document.getElementById('adjust_current_display').innerText = product.stock_count;
        
        // Dynamically point the stock adjustment form URL straight onto your backend route layout parameters
        document.getElementById('adjustStockForm').action = '/products/' + product.id + '/adjust-stock';
        document.getElementById('adjustStockModal').classList.remove('hidden');
    }
    
    function closeAdjustStockModal() {
        document.getElementById('adjustStockModal').classList.add('hidden');
    }

    function submitNewProductDirectly() {
    // 1. Gather all inputs programmatically bypassing rendering wrappers
    let nameVal = document.getElementById('store_name').value;
    let priceVal = document.getElementById('store_price').value;
    let stockVal = document.getElementById('store_stock_count').value;
    let reorderVal = document.getElementById('store_reorder_level').value;

    if (!nameVal || !priceVal || !stockVal || !reorderVal) {
        alert('Please fill out all product input fields completely.');
        return;
    }

    // 2. Prepare structured Form Payload Data
    let formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('name', nameVal);
    formData.append('price', priceVal);
    formData.append('stock_count', stockVal);
    formData.append('reorder_level', reorderVal);

    // 3. Dispatch background Fetch Request directly to backend bypassing Inertia listeners
    fetch('/products', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Force complete page update window refresh upon insertion success state
        window.location.reload();
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to insert new inventory item entry securely.');
    });
}
    </script>
@endsection