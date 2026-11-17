@extends('layouts.blade-layout')

@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <!-- Main Working Canvas -->
        <main class="flex-1 p-0 lg:p-8">

            <!-- Header & Add Products CTA -->
            <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-3 mb-5 lg:mb-6">
                <h1 class="lg:hidden text-2xl font-extrabold text-slate-900">Products</h1>
                <div class="hidden lg:block"></div>
                <button type="button" onclick="toggleAddProductModal()"
                    class="w-full lg:w-auto px-10 py-3.5 lg:py-4 bg-[#165334] lg:bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition border-0 cursor-pointer tracking-wide text-center">
                    + Add Products
                </button>
            </div>

            <!-- Floating Overlay Modal Window Layer for Adding Products -->
            <div id="addProductModal"
                class="hidden fixed inset-0 bg-black/45 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden p-6 relative">
                    <div class="flex justify-between items-center mb-6">
                        <h5 class="text-sm font-black text-slate-800 uppercase tracking-wide">Add New Product</h5>
                        <button type="button" onclick="toggleAddProductModal()"
                            class="text-gray-400 hover:text-gray-600 text-xs font-bold bg-transparent border-0 cursor-pointer">
                            Cancel
                        </button>
                    </div>

                    <div class="space-y-4 e-0">
                        @csrf
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Product /
                                Item Name</label>
                            <input type="text" id="store_name" required placeholder="e.g. Premium Jasmine Rice (5kg)"
                                class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Retail
                                    Price (₱)</label>
                                <input type="number" id="store_price" step="0.01" min="0" required placeholder="0.00"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                            </div>
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Initial
                                    Stock Qty</label>
                                <input type="number" id="store_stock_count" min="0" required placeholder="e.g. 50"
                                    class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                            </div>
                        </div>

                        <div class="pb-4">
                            <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Low-Stock
                                Alert Level</label>
                            <input type="number" id="store_reorder_level" min="0" required
                                class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition">
                            <p class="text-gray-400 text-[10px] mt-1 font-medium">You will get automatic item warnings when
                                stock counts fall to or below this level.</p>
                        </div>

                        <button type="button" onclick="submitNewProductDirectly()"
                            class="w-full py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-lg transition text-center block border-0 cursor-pointer text-xs uppercase tracking-wider shadow-sm">
                            Save Product to Inventory
                        </button>
                    </div>
                </div>
            </div>
            <!-- Filter Control Matrix Grid Wrapper -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 lg:gap-4 mb-5 lg:mb-8">
                <select id="categoryFilter" onchange="filterProductsTable()"
                    class="bg-white lg:bg-gray-200 border border-slate-200 lg:border-transparent rounded-xl lg:rounded-md p-3 text-xs font-bold text-gray-800 focus:outline-none focus:bg-white focus:border-gray-300">
                    <option value="">Category</option>
                    @foreach($products->pluck('category')->filter()->unique() as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>

                <select id="stockStatusFilter" onchange="filterProductsTable()"
                    class="bg-white lg:bg-gray-200 border border-slate-200 lg:border-transparent rounded-xl lg:rounded-md p-3 text-xs font-bold text-gray-800 focus:outline-none focus:bg-white focus:border-gray-300">
                    <option value="">Stock Status</option>
                    <option value="In Stock">In Stock</option>
                    <option value="Low Stock">Low Stock</option>
                    <option value="Out of Stock">Out of Stock</option>
                </select>

                <select id="sortFilter" onchange="sortProductsTable()"
                    class="bg-white lg:bg-gray-200 border border-slate-200 lg:border-transparent rounded-xl lg:rounded-md p-3 text-xs font-bold text-gray-800 focus:outline-none focus:bg-white focus:border-gray-300">
                    <option value="">Sort By</option>
                    <option value="name-asc">Name (A-Z)</option>
                    <option value="price-asc">Price (Low-High)</option>
                    <option value="price-desc">Price (High-Low)</option>
                    <option value="stock-asc">Stock (Low-High)</option>
                </select>

                <input type="text" id="productSearchInput" oninput="filterProductsTable()" placeholder="Search orders"
                    class="bg-white lg:bg-gray-200 border border-slate-200 lg:border-transparent rounded-xl lg:rounded-md p-3 text-xs font-bold text-gray-800 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-300" />
            </div>

            <!-- KPI Summary Cards Grid Row Block -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 lg:gap-6 mb-6 lg:mb-8">
                <div
                    class="bg-white lg:bg-gray-200 px-4 py-3.5 lg:p-6 rounded-xl lg:rounded-3xl border border-slate-200 lg:border-transparent flex flex-row lg:flex-col justify-between lg:justify-center items-center lg:text-center lg:h-28 relative shadow-sm">
                    <p class="text-xs font-bold text-gray-600 lg:text-gray-500 lg:uppercase tracking-wide">
                        Total Products
                        <span class="hidden lg:inline text-[10px] text-emerald-600 font-extrabold ml-1">+3%</span>
                    </p>
                    <div class="flex items-baseline gap-1.5 lg:mt-1">
                        <h3 class="text-lg lg:text-2xl font-black text-gray-900">{{ number_format($totalProducts) }}</h3>
                        <span class="lg:hidden text-[10px] text-emerald-600 font-extrabold">+3%</span>
                    </div>
                </div>

                <div
                    class="bg-white lg:bg-gray-200 px-4 py-3.5 lg:p-6 rounded-xl lg:rounded-3xl border border-slate-200 lg:border-transparent flex flex-row lg:flex-col justify-between lg:justify-center items-center lg:text-center lg:h-28 relative shadow-sm">
                    <p class="text-xs font-bold text-gray-600 lg:text-gray-500 lg:uppercase tracking-wide">
                        Low Stock Items
                        <span class="hidden lg:inline text-[10px] text-amber-600 font-extrabold ml-1">+6</span>
                    </p>
                    <div class="flex items-baseline gap-1.5 lg:mt-1">
                        <h3 class="text-lg lg:text-2xl font-black text-gray-900">{{ $lowStockItems }}</h3>
                        <span class="lg:hidden text-[10px] text-amber-600 font-extrabold">+6</span>
                    </div>
                </div>

                <div
                    class="bg-white lg:bg-gray-200 px-4 py-3.5 lg:p-6 rounded-xl lg:rounded-3xl border border-slate-200 lg:border-transparent flex flex-row lg:flex-col justify-between lg:justify-center items-center lg:text-center lg:h-28 relative shadow-sm">
                    <p class="text-xs font-bold text-gray-600 lg:text-gray-500 lg:uppercase tracking-wide">
                        Out of Stock
                        <span class="hidden lg:inline text-[10px] text-rose-600 font-extrabold ml-1">+2</span>
                    </p>
                    <div class="flex items-baseline gap-1.5 lg:mt-1">
                        <h3 class="text-lg lg:text-2xl font-black text-gray-900">{{ $outOfStock }}</h3>
                        <span class="lg:hidden text-[10px] text-rose-600 font-extrabold">+2</span>
                    </div>
                </div>
            </div>
            <!-- Mobile Section Title -->
            <h2 class="lg:hidden text-sm font-extrabold text-slate-900 mb-3 tracking-wide">All Products</h2>

            <!-- Products Matrix Grid Table Section -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs font-semibold text-gray-700 block lg:table">
                    <thead class="hidden lg:table-header-group">
                        <tr
                            class="border-b border-gray-200 text-[10px] font-black text-gray-900 uppercase tracking-wider bg-gray-50/50">
                            <th class="py-3 px-4 font-black">PRODUCT</th>
                            <th class="py-3 px-4 font-black">CATEGORY</th>
                            <th class="py-3 px-4 font-black">PRICE</th>
                            <th class="py-3 px-4 font-black">STOCK</th>
                            <th class="py-3 px-4 font-black">STATUS</th>
                            <th class="py-3 px-4 font-black">LAST UPDATED</th>
                            <th class="py-3 px-4 font-black text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody" class="block lg:table-row-group space-y-3 lg:space-y-0">
                        @foreach ($products as $product)
                            @php
                                if ($product->stock_count <= 0) {
                                    $statusLabel = 'Out of Stock';
                                } elseif ($product->stock_count <= ($product->reorder_level ?? 10)) {
                                    $statusLabel = 'Low Stock';
                                } else {
                                    $statusLabel = 'In Stock';
                                }
                            @endphp

                            <tr class="product-row block lg:table-row bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 lg:border-b lg:border-gray-100 p-4 lg:p-0 shadow-sm lg:shadow-none hover:bg-slate-50/80 transition align-middle"
                                data-name="{{ strtolower($product->name) }}"
                                data-category="{{ $product->category ?? 'General' }}" data-status="{{ $statusLabel }}"
                                data-price="{{ $product->price }}" data-stock="{{ $product->stock_count }}">

                                <!-- Product Name (+ Mobile Category Subtitle) -->
                                <td
                                    class="block lg:table-cell lg:py-4 lg:px-4 font-bold text-gray-900 text-sm lg:text-xs pb-2 lg:pb-4">
                                    <div>{{ $product->name }}</div>
                                    <div class="lg:hidden text-[11px] font-medium text-gray-400 mt-0.5">
                                        {{ $product->category ?? 'General' }}</div>
                                </td>

                                <!-- Category (Desktop Only) -->
                                <td class="hidden lg:table-cell py-4 px-4 text-gray-500 font-medium">
                                    {{ $product->category ?? 'General' }}
                                </td>

                                <!-- Price -->
                                <td
                                    class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-4 font-bold text-gray-900">
                                    <span
                                        class="lg:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider">PRICE</span>
                                    <span>₱{{ number_format($product->price, 0) }}</span>
                                </td>

                                <!-- Stock -->
                                <td
                                    class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-4 text-gray-700 lg:text-gray-600 font-bold lg:font-medium">
                                    <span
                                        class="lg:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider">STOCK</span>
                                    <span>{{ $product->stock_count }}</span>
                                </td>

                                <!-- Status -->
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-4">
                                    <span
                                        class="lg:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider">STATUS</span>
                                    @if ($product->stock_count <= 0)
                                        <span
                                            class="lg:px-3 lg:py-1 lg:rounded-full text-xs lg:text-[10px] font-black tracking-wide lg:bg-rose-100 text-rose-600 lg:border lg:border-rose-200/50">Out
                                            of Stock</span>
                                    @elseif ($product->stock_count <= ($product->reorder_level ?? 10))
                                        <span
                                            class="lg:px-3 lg:py-1 lg:rounded-full text-xs lg:text-[10px] font-black tracking-wide lg:bg-amber-100 text-amber-600 lg:border lg:border-amber-200/50">Low
                                            Stock</span>
                                    @else
                                        <span
                                            class="lg:px-3 lg:py-1 lg:rounded-full text-xs lg:text-[10px] font-black tracking-wide lg:bg-emerald-100 text-emerald-700 lg:text-emerald-600 lg:border lg:border-emerald-200/50">In
                                            Stock</span>
                                    @endif
                                </td>

                                <!-- Last Updated -->
                                <td
                                    class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-4 text-gray-500 font-medium">
                                    <span class="lg:hidden text-[10px] font-bold text-gray-400 uppercase tracking-wider">LAST
                                        UPDATED</span>
                                    <span>{{ isset($product->updated_at) ? \Carbon\Carbon::parse($product->updated_at)->format('M d, Y') : \Carbon\Carbon::now()->format('M d, Y') }}</span>
                                </td>

                                <!-- Action -->
                                <td
                                    class="block lg:table-cell pt-2.5 lg:py-4 lg:px-4 text-right whitespace-nowrap text-[11px] font-bold text-gray-400">
                                    <div class="inline-flex items-center justify-end space-x-2">
                                        <button type="button" onclick="openEditModal({{ json_encode($product) }})"
                                            class="text-gray-500 hover:text-gray-900 bg-transparent border-0 p-0 m-0 font-bold text-[11px] cursor-pointer no-underline">
                                            Edit
                                        </button>
                                        <span>&bull;</span>
                                        <button type="button" onclick="openAdjustStockModal({{ json_encode($product) }})"
                                            class="text-gray-500 hover:text-gray-900 bg-transparent border-0 p-0 m-0 font-bold text-[11px] cursor-pointer no-underline">
                                            Adjust stock
                                        </button>
                                        <span class="hidden lg:inline">&bull;</span>
                                        <a href="{{ route('products.directDelete', $product->id) }}"
                                            class="hidden lg:inline text-rose-500 hover:text-rose-700 transition no-underline"
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

        </main>
    </div>
    <!-- EDIT PRODUCT FLOATING OVERLAY MODAL -->
    <div id="editProductModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden p-6 relative">
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-lg font-black text-gray-900 uppercase tracking-wide">Edit Product Details</h5>
                <button type="button" onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-600 text-sm font-bold bg-transparent border-0 cursor-pointer">&mdash;
                    Cancel</button>
            </div>

            <form id="editProductForm" method="POST" class="space-y-4 m-0" onsubmit="event.stopPropagation();">
                @csrf

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Product
                        Name</label>
                    <input type="text" id="edit_name" name="name" required
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Price
                            (₱)</label>
                        <input type="number" id="edit_price" name="price" step="0.01" min="0" required
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Stock
                            Qty</label>
                        <input type="number" id="edit_stock" name="stock_count" min="0" required
                            class="w-full border border-gray-200 rounded-xl p-3 text-xs text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-500 uppercase tracking-wider mb-1">Low-Stock Alert
                        Level</label>
                    <input type="number" id="edit_reorder" name="reorder_level" min="0" required
                        class="w-full border border-gray-200 rounded-xl p-3 text-xs text-black bg-gray-50 shadow-inner focus:outline-none focus:border-emerald-500">
                </div>

                <button type="submit" onclick="event.stopPropagation();"
                    class="w-full py-3.5 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl transition text-center border-0 cursor-pointer shadow-md mt-4 text-xs uppercase tracking-wider">
                    Update Product Parameters
                </button>
            </form>
        </div>
    </div>

    <!-- ADJUST STOCK LEVEL FLOATING OVERLAY MODAL -->
    <div id="adjustStockModal"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-sm overflow-hidden border border-gray-200">
            <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-sm font-bold text-gray-900 tracking-wide uppercase">Adjust Stock</h3>
                <button type="button" onclick="closeAdjustStockModal()"
                    class="text-gray-400 hover:text-gray-600 text-xs font-semibold bg-transparent border-0 cursor-pointer">
                    Cancel
                </button>
            </div>

            <form id="adjustStockForm" method="POST" class="p-5 space-y-4 m-0" onsubmit="event.stopPropagation();">
                @csrf

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 text-xs">
                    <p class="text-gray-500 font-medium">Product: <span id="adjust_product_title"
                            class="text-gray-900 font-bold">Product Name</span></p>
                    <p class="text-gray-500 font-medium mt-1">Current Stock: <span id="adjust_current_display"
                            class="text-emerald-600 font-bold">0</span> units</p>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Adjustment
                        Type</label>
                    <select name="adjustment_type" required
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-700 bg-white focus:outline-none focus:border-emerald-500 transition cursor-pointer">
                        <option value="add">Increase Product Stock</option>
                        <option value="subtract">Decrease Product Stock</option>
                    </select>
                </div>

                <div>
                    <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" required placeholder="e.g. 10"
                        class="w-full border border-gray-200 rounded-lg p-2.5 text-xs text-gray-800 bg-white focus:outline-none focus:border-emerald-500 transition placeholder-gray-300">
                </div>

                <button type="submit" onclick="event.stopPropagation();"
                    class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-lg transition text-xs text-center border-0 cursor-pointer shadow-sm">
                    Apply Adjustments
                </button>
            </form>
        </div>
    </div>
    <script>
        function toggleAddProductModal() {
            const modal = document.getElementById('addProductModal');
            modal.classList.toggle('hidden');
        }

        function openEditModal(product) {
            document.getElementById('edit_name').value = product.name;
            document.getElementById('edit_price').value = product.price;
            document.getElementById('edit_stock').value = product.stock_count;
            document.getElementById('edit_reorder').value = product.reorder_level || 10;

            document.getElementById('editProductForm').action = '/products/' + product.id + '/update';
            document.getElementById('editProductModal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('editProductModal').classList.add('hidden');
        }

        function openAdjustStockModal(product) {
            document.getElementById('adjust_product_title').innerText = product.name;
            document.getElementById('adjust_current_display').innerText = product.stock_count;

            document.getElementById('adjustStockForm').action = '/products/' + product.id + '/adjust-stock';
            document.getElementById('adjustStockModal').classList.remove('hidden');
        }

        function closeAdjustStockModal() {
            document.getElementById('adjustStockModal').classList.add('hidden');
        }

        function submitNewProductDirectly() {
            let nameVal = document.getElementById('store_name').value;
            let priceVal = document.getElementById('store_price').value;
            let stockVal = document.getElementById('store_stock_count').value;
            let reorderVal = document.getElementById('store_reorder_level').value;

            if (!nameVal || !priceVal || !stockVal || !reorderVal) {
                alert('Please fill out all product input fields completely.');
                return;
            }

            let formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', nameVal);
            formData.append('price', priceVal);
            formData.append('stock_count', stockVal);
            formData.append('reorder_level', reorderVal);

            fetch('/products', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
                .then(response => {
                    window.location.reload();
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to insert new inventory item entry securely.');
                });
        }

        function filterProductsTable() {
            const searchQuery = (document.getElementById('productSearchInput')?.value || '').toLowerCase().trim();
            const selectedCategory = document.getElementById('categoryFilter')?.value || '';
            const selectedStatus = document.getElementById('stockStatusFilter')?.value || '';

            const rows = document.querySelectorAll('.product-row');

            rows.forEach(row => {
                const name = row.getAttribute('data-name') || '';
                const category = row.getAttribute('data-category') || '';
                const status = row.getAttribute('data-status') || '';

                const matchesSearch = name.includes(searchQuery);
                const matchesCategory = !selectedCategory || category === selectedCategory;
                const matchesStatus = !selectedStatus || status === selectedStatus;

                row.style.display = (matchesSearch && matchesCategory && matchesStatus) ? '' : 'none';
            });
        }

        function sortProductsTable() {
            const sortBy = document.getElementById('sortFilter')?.value || '';
            const tbody = document.getElementById('productsTableBody');
            if (!tbody || !sortBy) return;

            const rows = Array.from(tbody.querySelectorAll('.product-row'));

            rows.sort((a, b) => {
                if (sortBy === 'name-asc') {
                    return (a.getAttribute('data-name') || '').localeCompare(b.getAttribute('data-name') || '');
                }
                if (sortBy === 'price-asc') {
                    return parseFloat(a.getAttribute('data-price')) - parseFloat(b.getAttribute('data-price'));
                }
                if (sortBy === 'price-desc') {
                    return parseFloat(b.getAttribute('data-price')) - parseFloat(a.getAttribute('data-price'));
                }
                if (sortBy === 'stock-asc') {
                    return parseInt(a.getAttribute('data-stock')) - parseInt(b.getAttribute('data-stock'));
                }
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
@endsection