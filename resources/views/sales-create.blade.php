@extends('layouts.blade-layout')

@section('content')
    <div class="min-h-screen bg-white p-8 font-sans">
        <div class="max-w-7xl mx-auto">

            <!-- Top Header Navigation Return Link -->
            <div class="mb-4 flex justify-between items-center">
                <a href="{{ route('sales.index') }}" class="text-xs font-bold hover:text-emerald-200 transition">← Back to
                    Sales</a>
            </div>

            <!-- Backend Form Submission Point -->
            <form action="{{ route('sales.store') }}" method="POST" id="sales-form">
                @csrf

                <!-- 🌟 Updated Search Block with High Layer Z-Index Layout Isolation Wrapper -->



                <!-- Main Workspace Two-Column Split Layout Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    <!-- Left Hand Column Container: Item List Table Segment -->
                    <div class="lg:col-span-2">
                        <h2 class="text-sm font-black text-gray-900 mb-3 tracking-wide">Item in this sale</h2>

                        <div
                            class="bg-white p-6 border border-gray-200 rounded-lg overflow-hidden shadow-sm w-full min-h-[200px] flex flex-col justify-between">
                            <!-- Items in this Sale Grid Table Wrapper -->
                            <table class="w-full text-left border-collapse text-xs font-semibold text-slate-700">
                                <thead>
                                    <tr
                                        class="border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                        <th class="py-3 px-4">ITEM</th>
                                        <th class="py-3 px-4">QTY</th>
                                        <th class="py-3 px-4">PRICE</th>
                                        <th class="py-3 px-4">SUBTOTAL</th>
                                        <th class="py-3 px-4 text-right">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTableBody">
                                    <!-- Dynamic row items appear here via selection script -->
                                    <tr id="emptyCartRow">
                                        <td colspan="5" class="py-6 text-slate-400 font-medium">No items added to this
                                            transaction record yet.</td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- 🌟 INTERACTIVE SELECTION ROW LINK -->
                            <div class="mt-auto pt-4 border-t border-gray-100">
                                <button type="button" onclick="showProductSelectionRow()"
                                    class="text-emerald-600 hover:text-emerald-700 bg-transparent border-0 p-0 font-bold text-xs cursor-pointer flex items-center space-x-1">
                                    <span>+ Add Product</span>
                                </button>
                            </div>

                            <!-- 🌟 HIDDEN DYNAMIC ROW SELECTOR PANEL TEMPLATE -->
                            <!-- 🌟 FULL-WIDTH SELECTION ROW CONTAINER (Matches the table grid perfectly) -->
                            <!-- 🔍 FULL-WIDTH SEARCHABLE SELECTION CONTAINER -->
                            <!-- 🔍 BRAND NEW STYLED SEARCH CONTAINER -->
                            <div id="productSelectorWrapper"
                                class="hidden mt-4 p-5 bg-slate-50 rounded-xl border border-slate-200/60 w-full relative">
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Search
                                    Store Product Item</label>
                                <div class="flex items-center space-x-4 w-full relative">

                                    <!-- Interactive Custom Search Bar -->
                                    <div class="flex-1 relative">
                                        <input type="text" id="posProductSearchInput"
                                            placeholder="Type a product name to search instantly..."
                                            class="w-full bg-white border border-slate-200 rounded-lg p-3.5 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500 shadow-inner"
                                            oninput="handleLiveProductSearch(this.value)"
                                            onfocus="handleLiveProductSearch(this.value)">

                                        <!-- 🌟 STYLED FLOATING RESULTS DROPDOWN PANEL -->
                                        <div id="customDropdownMenu"
                                            class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 shadow-xl rounded-xl max-h-60 overflow-y-auto z-50 divide-y divide-slate-100">
                                            @foreach($products ?? [] as $product)
                                                <div class="product-search-item px-4 py-3 hover:bg-slate-50 cursor-pointer flex justify-between items-center transition"
                                                    data-name="{{ strtolower($product->name) }}"
                                                    data-display-name="{{ $product->name }}" data-id="{{ $product->id }}"
                                                    data-price="{{ $product->price }}" data-stock="{{ $product->stock_count }}"
                                                    onclick="selectProductFromCustomMenu(this)">
                                                    <div>
                                                        <p class="text-xs font-bold text-slate-900 mb-0.5">{{ $product->name }}
                                                        </p>
                                                        <p class="text-[10px] text-slate-400 font-semibold">Available Inventory:
                                                            {{ $product->stock_count }} units
                                                        </p>
                                                    </div>
                                                    <span
                                                        class="text-xs font-black text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                                </div>
                                            @endforeach
                                            <div id="noResultsFoundMessage"
                                                class="hidden p-4 text-center text-xs font-bold text-slate-400">
                                                No matching products found in stock registry.
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Standard Row Add Action Button -->
                                    <button type="button" onclick="addCurrentlySelectedProduct()"
                                        class="px-6 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-lg transition border-0 cursor-pointer shadow-sm uppercase tracking-wider whitespace-nowrap">
                                        Add to Sale
                                    </button>
                                </div>
                            </div>
                            <!-- 🌟 CARTS OPERATIONS CONTROLLERS SCRIPTS -->
                            <script>
                                let activeSelectedProduct = null;

                                // 1. Show the parent searchable input field wrapper component container
                                function showProductSelectionRow() {
                                    document.getElementById('productSelectorWrapper').classList.remove('hidden');
                                    document.getElementById('posProductSearchInput').focus();
                                }

                                // 2. Interactive live text string typing filter handler 
                                function handleLiveProductSearch(query) {
                                    const menu = document.getElementById('customDropdownMenu');
                                    const items = document.querySelectorAll('.product-search-item');
                                    const noResults = document.getElementById('noResultsFoundMessage');
                                    const cleanQuery = query.toLowerCase().trim();

                                    if (cleanQuery === '') {
                                        menu.classList.add('hidden');
                                        return;
                                    }

                                    menu.classList.remove('hidden');
                                    let dynamicMatches = 0;

                                    items.forEach(item => {
                                        const productName = item.getAttribute('data-name');
                                        if (productName.includes(cleanQuery)) {
                                            item.classList.remove('hidden');
                                            item.classList.add('flex');
                                            dynamicMatches++;
                                        } else {
                                            item.classList.add('hidden');
                                            item.classList.remove('flex');
                                        }
                                    });

                                    if (dynamicMatches === 0) {
                                        noResults.classList.remove('hidden');
                                    } else {
                                        noResults.classList.add('hidden');
                                    }
                                }

                                // 3. Selection handler when clicking a custom item card inside the floating results menu
                                function selectProductFromCustomMenu(element) {
                                    const input = document.getElementById('posProductSearchInput');
                                    const menu = document.getElementById('customDropdownMenu');

                                    input.value = element.getAttribute('data-display-name');

                                    // Cache the precise parameters programmatically for the append step
                                    activeSelectedProduct = {
                                        id: element.getAttribute('data-id'),
                                        name: element.getAttribute('data-display-name'),
                                        price: parseFloat(element.getAttribute('data-price'))
                                    };

                                    menu.classList.add('hidden');
                                }

                                // 4. Safe table appending button click execution trigger method
                                function addCurrentlySelectedProduct() {
                                    const input = document.getElementById('posProductSearchInput');

                                    if (!activeSelectedProduct || input.value.trim() === '') {
                                        alert('Please search and click a valid product from your floating dropdown results first.');
                                        return;
                                    }

                                    const itemId = activeSelectedProduct.id;
                                    const itemName = activeSelectedProduct.name;
                                    const itemPrice = activeSelectedProduct.price;

                                    // Flush your initial empty card placeholder row
                                    const emptyRow = document.getElementById('emptyCartRow');
                                    if (emptyRow) emptyRow.remove();

                                    const tbody = document.getElementById('cartTableBody');
                                    const existingRow = document.getElementById('cart-item-' + itemId);

                                    // Instead of splitting rows, catch duplicate IDs and increment quantity values
                                    if (existingRow) {
                                        const qtyInput = document.getElementById('qty-' + itemId);
                                        qtyInput.value = parseInt(qtyInput.value) + 1;
                                        updateRowSubtotal(itemId, itemPrice);
                                        cleanupPOSSearchState();
                                        return;
                                    }

                                    // Append beautiful table columns seamlessly
                                    const row = document.createElement('tr');
                                    row.id = 'cart-item-' + itemId;
                                    row.className = 'border-b border-slate-100 align-middle';
                                    row.innerHTML = `
            <td class="py-4 px-4 font-bold text-slate-900">${itemName}</td>
            <td class="py-4 px-4">
                <input type="number" id="qty-${itemId}" value="1" min="1" onchange="updateRowSubtotal(${itemId}, ${itemPrice})" class="w-16 border border-slate-200 rounded-md p-1 text-center text-xs font-bold text-slate-800">
            </td>
            <td class="py-4 px-4 text-slate-500 font-medium">₱${itemPrice.toFixed(2)}</td>
            <td class="py-4 px-4 font-bold text-slate-900" id="subtotal-${itemId}">₱${itemPrice.toFixed(2)}</td>
            <td class="py-4 px-4 text-right">
                <button type="button" onclick="document.getElementById('cart-item-${itemId}').remove(); calculateCartTotals();" class="text-rose-500 hover:text-rose-700 font-bold bg-transparent border-0 cursor-pointer">Remove</button>
            </td>
        `;

                                    tbody.appendChild(row);
                                    cleanupPOSSearchState();
                                    calculateCartTotals();
                                }

                                // 5. Clean layout container states back to normal after additions
                                function cleanupPOSSearchState() {
                                    document.getElementById('posProductSearchInput').value = "";
                                    document.getElementById('productSelectorWrapper').classList.add('hidden');
                                    document.getElementById('customDropdownMenu').classList.add('hidden');
                                    activeSelectedProduct = null;
                                }

                                // 6. Keep intact: Existing math multiplier row subtotal engines
                                function updateRowSubtotal(id, price) {
                                    const qty = parseInt(document.getElementById('qty-' + id).value) || 1;
                                    const subtotal = qty * price;
                                    document.getElementById('subtotal-' + id).innerText = '₱' + subtotal.toFixed(2);
                                    calculateCartTotals();
                                }

                                // 7. Keep intact: Live Order Summary summary total updater engines
                                function calculateCartTotals() {
                                    let itemsCount = 0;
                                    let orderSubtotal = 0;

                                    document.querySelectorAll('[id^="subtotal-"]').forEach(el => {
                                        const val = parseFloat(el.innerText.replace('₱', '')) || 0;
                                        orderSubtotal += val;
                                    });

                                    document.querySelectorAll('[id^="qty-"]').forEach(el => {
                                        itemsCount += parseInt(el.value) || 0;
                                    });

                                    if (document.getElementById('summary_items_count')) document.getElementById('summary_items_count').innerText = itemsCount;
                                    if (document.getElementById('summary_subtotal')) document.getElementById('summary_subtotal').innerText = '₱' + orderSubtotal.toFixed(2);
                                    if (document.getElementById('summary_total')) document.getElementById('summary_total').innerText = '₱' + orderSubtotal.toFixed(2);
                                }

                                // 8. Global window event listener to close the floating dropdown automatically if user clicks away
                                document.addEventListener('click', function (event) {
                                    const searchContainer = document.getElementById('posProductSearchInput');
                                    const customMenu = document.getElementById('customDropdownMenu');
                                    if (searchContainer && !searchContainer.contains(event.target) && customMenu) {
                                        customMenu.classList.add('hidden');
                                    }
                                });
                            </script>
                        </div>
                    </div>

                    <!-- Right Hand Column Container: Order Summary Panel -->
                    <div class="p-6 rounded-2xl flex flex-col justify-between shadow-sm border border-transparent"
                        style="background-color: #D9D9D9;">
                        <div>
                            <h2 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4">Order Summary</h2>

                            <!-- Customer Input Row -->
                            <div class="flex justify-between items-center text-sm font-semibold mb-4">
                                <span class="text-gray-600">Customer</span>
                                <input type="text" name="customer_name" value="Maria Santos" required
                                    class="bg-transparent border-b border-transparent hover:border-gray-400 focus:border-emerald-600 focus:outline-none text-right font-bold text-gray-800 py-0.5 transition w-32" />
                            </div>

                            <div class="space-y-3 border-b border-gray-400 pb-3.5 mb-4 text-sm font-semibold text-gray-700">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Items</span>
                                    <!-- Dynamic Target Count -->
                                    <span id="summary-qty-count" class="text-gray-900 font-black">0</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal</span>
                                    <!-- Dynamic Target Subtotal -->
                                    <span id="summary-subtotal" class="text-gray-900 font-black">₱0</span>
                                </div>
                            </div>

                            <div
                                class="flex justify-between text-sm font-black text-gray-900 border-b border-gray-400 pb-3.5 mb-5">
                                <span>Total</span>
                                <!-- Dynamic Target Total -->
                                <span id="summary-total" class="text-base font-black">₱0</span>
                            </div>

                            <!-- Payment Input Fields Sections -->
                            <div class="space-y-4">
                                <div>
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Payment
                                        Method</label>
                                    <select
                                        class="w-full rounded-xl bg-white border border-transparent p-3 text-sm font-bold text-gray-700 focus:outline-none shadow-sm">
                                        <option>Cash</option>
                                        <option>GCash</option>
                                        <option>Maya</option>
                                        <option>Credit Card</option>
                                    </select>
                                </div>

                                <div>
                                    <label
                                        class="block text-xs font-bold uppercase tracking-wider text-gray-500 mb-1.5">Amount
                                        Received</label>
                                    <!-- Initialized with 0 instead of 500 for a fresh checkout state -->
                                    <input type="number" value="0" min="0" step="1" id="amount-received"
                                        class="w-full rounded-xl bg-white border border-transparent p-3 text-sm font-black text-gray-800 focus:outline-none shadow-sm" />
                                </div>

                                <div class="flex justify-between items-center pt-2 text-sm font-semibold text-gray-700">
                                    <span class="text-gray-600">Change</span>
                                    <!-- Dynamic Target Change -->
                                    <span id="change-display" class="text-base font-black text-gray-900">₱0</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="total_amount" id="form-total-amount" value="0.00" />

                        <!-- Complete Sale Submit Action -->
                        <button type="submit"
                            class="w-full bg-emerald-800 text-white font-black text-sm py-3.5 rounded-xl hover:bg-emerald-900 transition tracking-wide shadow-sm mt-8 block text-center">
                            Complete Sale
                        </button>
                    </div>

                </div>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        // 1. Core DOM Element Bindings
                        const searchInput = document.getElementById('product-search-input');
                        const resultsPanel = document.getElementById('search-results-panel');
                        const resultsRows = document.getElementById('search-results-rows');
                        const cartRows = document.getElementById('cart-rows');
                        const receivedInput = document.getElementById('amount-received');

                        // 2. Database Inventory Synchronization Loop Array
                        const productsList = [
                            @foreach($products as $prod)
                                { id: "{{ $prod->id }}", name: "{{ $prod->product_name ?? $prod->name }}", price: {{ $prod->price }} },
                            @endforeach
                                ];

                        // 3. 🧮 Core Calculator Engine Function
                        function calculateTotals() {
                            let totalItemsCount = 0;
                            let runningTotalSum = 0;

                            const rows = document.querySelectorAll('.cart-item');
                            rows.forEach(row => {
                                const price = parseFloat(row.getAttribute('data-price')) || 0;
                                const qtyInput = row.querySelector('.qty-input');
                                const qty = qtyInput ? (parseInt(qtyInput.value) || 0) : 0;
                                const subtotal = price * qty;

                                totalItemsCount += qty;
                                runningTotalSum += subtotal;

                                const subtotalDisplay = row.querySelector('.subtotal-display');
                                if (subtotalDisplay) {
                                    subtotalDisplay.innerText = '₱' + Math.round(subtotal);
                                }
                            });

                            const receivedAmt = parseFloat(receivedInput.value) || 0;
                            const changeDue = Math.max(0, receivedAmt - runningTotalSum);

                            const qtyCountEl = document.getElementById('summary-qty-count');
                            const subtotalEl = document.getElementById('summary-subtotal');
                            const totalEl = document.getElementById('summary-total');
                            const formAmtEl = document.getElementById('form-total-amount');
                            const changeEl = document.getElementById('change-display');

                            if (qtyCountEl) qtyCountEl.innerText = totalItemsCount;
                            if (subtotalEl) subtotalEl.innerText = '₱' + Math.round(runningTotalSum);
                            if (totalEl) totalEl.innerText = '₱' + Math.round(runningTotalSum);
                            if (formAmtEl) formAmtEl.value = runningTotalSum.toFixed(2);
                            if (changeEl) changeEl.innerText = '₱' + Math.round(changeDue);
                        }

                        // 4. ➕ Add Item to Selection List
                        function addProductToCart(product) {
                            document.querySelectorAll('.cart-item').forEach(r => r.classList.remove('border-2', 'border-sky-500'));

                            const newRowHtml = `
                                        <tr class="cart-item border-2 border-sky-500" data-price="${product.price}">
                                            <td class="py-4 px-6 text-gray-900">${product.name}</td>
                                            <td class="py-4 px-6 text-center"><input type="number" value="1" min="1" class="w-14 bg-gray-50 border border-gray-200 rounded p-1 text-center font-bold qty-input"></td>
                                            <td class="py-4 px-6 text-center text-gray-400">₱${Math.round(product.price)}</td>
                                            <td class="py-4 px-6 text-center text-gray-900 font-bold subtotal-display">₱${Math.round(product.price)}</td>
                                            <td class="py-4 px-6 text-center"><button type="button" class="text-xs text-rose-400 hover:text-rose-600 font-medium remove-btn">Remove</button></td>
                                        </tr>`;

                            if (cartRows) {
                                cartRows.insertAdjacentHTML('beforeend', newRowHtml);
                                calculateTotals();
                            }
                        }

                        // 5. 🔍 Input Event Listener (Filter Suggestions Menu Panel)
                        if (searchInput) {
                            searchInput.addEventListener('input', function () {
                                const query = this.value.toLowerCase().trim();
                                resultsRows.innerHTML = '';

                                if (query.length === 0) {
                                    resultsPanel.classList.add('hidden');
                                    return;
                                }

                                const matches = productsList.filter(p => p.name.toLowerCase().includes(query));

                                if (matches.length > 0) {
                                    resultsPanel.classList.remove('hidden');
                                    matches.forEach(product => {
                                        const row = document.createElement('div');
                                        row.className = "flex justify-between items-center px-6 py-3 rounded-xl hover:bg-slate-50 transition cursor-pointer text-sm font-semibold text-gray-700 group";

                                        row.innerHTML = `
                                                    <span>"${product.name}"</span>
                                                    <div class="flex items-center space-x-4">
                                                        <span class="text-gray-400 font-bold">₱${Math.round(product.price)}</span>
                                                        <button type="button" class="text-xs font-black text-rose-500 hover:text-rose-700 transition opacity-80 hover:opacity-100 px-2 py-1 rounded-lg remove-inventory-item-btn" data-id="${product.id}">
                                                            Remove
                                                        </button>
                                                    </div>
                                                `;

                                        // Add product on click
                                        row.addEventListener('click', function (e) {
                                            if (e.target.classList.contains('remove-inventory-item-btn')) return;
                                            addProductToCart(product);
                                            resultsPanel.classList.add('hidden');
                                            searchInput.value = '';
                                        });

                                        // Handle product database permanent deletion hook trigger
                                        const removeButton = row.querySelector('.remove-inventory-item-btn');
                                        removeButton.addEventListener('click', function (e) {
                                            e.stopPropagation();
                                            const productId = this.getAttribute('data-id');
                                            if (confirm(`Are you sure you want to remove "${product.name}" from your store records permanently?`)) {
                                                const deletionForm = document.createElement('form');
                                                deletionForm.action = `/products/${productId}`;
                                                deletionForm.method = 'POST';
                                                deletionForm.className = 'hidden';

                                                deletionForm.innerHTML = `
                                                            <input type="hidden" name="_token" value="${document.querySelector('input[name="_token"]').value}">
                                                            <input type="hidden" name="_method" value="DELETE">
                                                        `;

                                                document.body.appendChild(deletionForm);
                                                deletionForm.submit();
                                            }
                                        });

                                        resultsRows.appendChild(row);
                                    });
                                } else {
                                    resultsRows.innerHTML = '<p class="text-xs text-gray-400 p-3 text-center">No matching grocery items found.</p>';
                                    resultsPanel.classList.remove('hidden');
                                }
                            });

                            // 6. ⌨️ Keydown Event Listener (Catches Enter key natively to block redirects)
                            searchInput.addEventListener('keydown', function (e) {
                                if (e.key === 'Enter' || e.keyCode === 13) {
                                    e.preventDefault();
                                    const firstResultRow = resultsRows.querySelector('div.cursor-pointer');
                                    if (firstResultRow) {
                                        firstResultRow.click();
                                    }
                                    return false;
                                }
                            });
                        }

                        // 7. 🚪 Global Document Clicks Handler (Closes suggestions panel when clicking outside)
                        document.addEventListener('click', function (e) {
                            if (resultsPanel && !searchInput.contains(e.target) && !resultsPanel.contains(e.target)) {
                                resultsPanel.classList.add('hidden');
                            }
                        });

                        // 8. 🛠️ Table List Row Actions Event Delegation Handler Hooks
                        if (cartRows) {
                            cartRows.addEventListener('input', function (e) {
                                if (e.target.classList.contains('qty-input')) calculateTotals();
                            });

                            cartRows.addEventListener('click', function (e) {
                                if (e.target.classList.contains('remove-btn')) {
                                    e.preventDefault();
                                    e.target.closest('tr').remove();
                                    calculateTotals();
                                }
                            });
                        }

                        if (receivedInput) {
                            receivedInput.addEventListener('input', calculateTotals);
                        }

                        // 9. Initializing Starting Mathematics Baseline Setup States
                        calculateTotals();
                    });
                </script>
            </form>
        </div>
    </div>
@endsection