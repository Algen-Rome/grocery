@extends('layouts.blade-layout')

@section('content')
    <div class="class=min-h-screen bg-white p-8 font-sans">
        <div class="mb-4 flex justify-between items-center">
            <a href="{{ route('sales.index') }}" class="text-xs font-bold hover:text-emerald-200 transition">+ Back to
                Sales</a>
        </div>

        <!-- Backend Form Submission Point -->
        <form action="{{ route('sales.store') }}" method="POST" id="sales-form">
            @csrf
            <input type="hidden" name="total_amount" id="form-total-amount" value="0.00" />

            <!-- Main Workspace Two-Column Split Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                <!-- Left Hand Column Container: Item List Table Segment -->
                <div class="lg:col-span-2">
                    <h2 class="text-sm font-black text-gray-900 mb-3 tracking-wide">Item in this sale</h2>

                    <div
                        class="bg-white p-6 border border-gray-200 rounded-lg overflow-hidden shadow-sm w-full min-h-[200px] flex flex-col justify-between">
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
                                <tr id="emptyCartRow">
                                    <td colspan="5" class="py-6 text-slate-400 font-medium text-center">No items added to
                                        this transaction record yet.</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- INTERACTIVE SELECTION ROW LINK -->
                        <div class="mt-auto pt-4 border-t border-gray-100">
                            <button type="button" onclick="showProductSelectionRow()"
                                class="text-emerald-600 hover:text-emerald-700 bg-transparent border-0 p-0 font-bold text-xs cursor-pointer flex items-center space-x-1">
                                <span>+ Add Product</span>
                            </button>
                        </div>
                    </div>

                    <!-- HIDDEN DYNAMIC ROW SELECTOR PANEL TEMPLATE -->
                    <div id="productSelectorWrapper"
                        class="hidden mt-4 p-5 bg-slate-50 rounded-xl border border-slate-200/60 w-full relative">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">Search
                            Store Product Item</label>
                        <div class="flex items-center space-x-4 w-full relative">
                            <div class="flex-1 relative">
                                <input type="text" id="posProductSearchInput"
                                    placeholder="Type a product name to search instantly..."
                                    oninput="handleLiveProductSearch(this.value)"
                                    onfocus="handleLiveProductSearch(this.value)"
                                    class="w-full bg-white border border-slate-200 rounded-lg p-3 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500 shadow-inner">

                                <!-- FLOATING RESULTS DROPDOWN PANEL -->
                                <div id="customDropdownMenu"
                                    class="hidden absolute left-0 right-0 top-full mt-1 bg-white border border-slate-200 shadow-xl rounded-xl max-h-60 overflow-y-auto z-50 divide-y divide-slate-100">
                                    @if(isset($products) && count($products) > 0)
                                        @foreach($products as $product)
                                            <div class="product-search-item px-4 py-3 hover:bg-slate-50 cursor-pointer flex justify-between items-center transition"
                                                data-name="{{ strtolower($product->name) }}"
                                                data-display-name="{{ $product->name }}" data-id="{{ $product->id }}"
                                                data-price="{{ $product->price }}" data-stock="{{ $product->stock_count }}"
                                                onclick="selectProductFromCustomMenu(this)">
                                                <div>
                                                    <p class="text-xs font-bold text-slate-900 mb-0.5">{{ $product->name }}</p>
                                                    <p class="text-[10px] text-slate-400 font-semibold">Available Inventory:
                                                        {{ $product->stock_count }} units</p>
                                                </div>
                                                <span
                                                    class="text-xs font-black text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div id="noResultsFoundMessage"
                                    class="hidden p-4 text-center text-xs font-hold text-slate-400">
                                    No matching products found in stock registry.
                                </div>
                            </div>
                            <button type="button" onclick="addCurrentlySelectedProduct()"
                                class="px-5 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black text-xs rounded-lg transition border-0 cursor-pointer shadow-sm uppercase tracking-wider whitespace-nowrap">Add
                                To Sale</button>
                        </div>
                    </div>
                </div>

                <!-- Right Hand Column Container: Order Summary Panel -->
                <div class="p-6 rounded-2xl flex flex-col justify-between shadow-sm border border-transparent bg-slate-50">
                    <div>
                        <h2 class="text-sm font-black text-gray-900 uppercase tracking-wider mb-4">Order Summary</h2>

                        <!-- Customer Input Row -->
                        <div class="flex justify-between items-center text-xs font-semibold mb-4">
                            <span class="text-gray-400">Customer</span>
                            <input type="text" name="customer_name" value="Maria Santos" required
                                class="bg-transparent border-b border-transparent hover:border-gray-400 focus:border-emerald-600 focus:outline-none text-right font-bold text-gray-800 py-0.5 transition w-32" />
                        </div>

                        <div class="space-y-3 border-b border-gray-200 pb-3.5 mb-4 text-sm font-semibold text-slate-700">
                            <div class="flex justify-between">
                                <span class="text-gray-400">Items</span>
                                <span id="summary-qty-count" class="text-gray-900 font-black">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-400">Subtotal</span>
                                <span id="summary-subtotal" class="text-gray-900 font-black">₱0.00</span>
                            </div>
                        </div>

                        <div
                            class="flex justify-between text-sm font-black text-gray-900 border-b border-gray-200 pb-3.5 mb-5">
                            <span>Total</span>
                            <span id="summary-total" class="text-base font-black text-emerald-600">₱0.00</span>
                        </div>

                        <!-- Payment Input Fields Sections -->
                        <div class="space-y-4">
                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">Payment
                                    Method</label>
                                <select name="payment_method"
                                    class="w-full rounded-xl bg-white border border-slate-200 p-3 text-sm font-bold text-gray-700 focus:outline-none shadow-sm">
                                    <option value="Cash">Cash</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Maya">Maya</option>
                                    <option value="Credit Card">Credit Card</option>
                                </select>
                            </div>

                            <div>
                                <label
                                    class="block text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1.5">Amount
                                    Received</label>
                                <input type="number" value="500" min="0" step="1" id="amount-received"
                                    oninput="calculateLiveChange()"
                                    class="w-full rounded-xl bg-white border border-slate-200 p-3 text-sm font-black text-gray-800 focus:outline-none shadow-sm" />
                            </div>

                            <div class="flex justify-between items-center pt-2 text-sm font-semibold text-gray-700">
                                <span class="text-gray-400">Change</span>
                                <span id="change-display" class="text-base font-black text-gray-900">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-black text-sm py-3.5 rounded-xl shadow-sm mt-8 block border-0 cursor-pointer text-center transition">
                        Complete Sale
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- ==================== JAVASCRIPT POS CALCULATION ENGINE ==================== -->
    <script>
        let activeSelectedProduct = null;

        function showProductSelectionRow() {
            const wrapper = document.getElementById('productSelectorWrapper');
            if (wrapper) wrapper.classList.remove('hidden');
            const input = document.getElementById('posProductSearchInput');
            if (input) input.focus();
        }

        function handleLiveProductSearch(query) {
            const menu = document.getElementById('customDropdownMenu');
            const items = document.querySelectorAll('.product-search-item');
            const noResults = document.getElementById('noResultsFoundMessage');
            const cleanQuery = query.toLowerCase().trim();

            if (cleanQuery === '') {
                if (menu) menu.classList.add('hidden');
                return;
            }

            if (menu) menu.classList.remove('hidden');
            let matches = 0;

            items.forEach(item => {
                const productName = item.getAttribute('data-name');
                if (productName && productName.includes(cleanQuery)) {
                    item.classList.remove('hidden');
                    item.classList.add('flex');
                    matches++;
                } else {
                    item.classList.add('hidden');
                    item.classList.remove('flex');
                }
            });

            if (noResults) noResults.classList.toggle('hidden', matches > 0);
        }

        function selectProductFromCustomMenu(element) {
            const input = document.getElementById('posProductSearchInput');
            const menu = document.getElementById('customDropdownMenu');

            if (input) input.value = element.getAttribute('data-display-name');

            activeSelectedProduct = {
                id: element.getAttribute('data-id'),
                name: element.getAttribute('data-display-name'),
                price: parseFloat(element.getAttribute('data-price'))
            };

            if (menu) menu.classList.add('hidden');
        }

        function addCurrentlySelectedProduct() {
            const input = document.getElementById('posProductSearchInput');
            if (!activeSelectedProduct || (input && input.value.trim() === '')) {
                alert('Please select a valid product first.');
                return;
            }

            const itemId = activeSelectedProduct.id;
            const itemName = activeSelectedProduct.name;
            const itemPrice = activeSelectedProduct.price;

            const emptyRow = document.getElementById('emptyCartRow');
            if (emptyRow) emptyRow.remove();

            const tbody = document.getElementById('cartTableBody');
            if (!tbody) return;

            const existingRow = document.getElementById('cart-item-' + itemId);
            if (existingRow) {
                const qtyInput = existingRow.querySelector('input[type="number"]');
                if (qtyInput) {
                    qtyInput.value = parseInt(qtyInput.value) + 1;
                    updateRowSubtotal(itemId, itemPrice);
                }
                cleanupPOSSearchState();
                return;
            }

            const row = document.createElement('tr');
            row.id = 'cart-item-' + itemId;
            row.className = 'border-b border-slate-100 align-middle';
            row.innerHTML = `
            <td class="py-4 px-4 font-bold text-slate-900">${itemName}</td>
            <td class="py-4 px-4">
                <input type="number" value="1" min="1" oninput="updateRowSubtotal(${itemId}, ${itemPrice})" class="w-16 border border-slate-200 rounded-md p-1 text-center text-xs font-bold text-slate-800 focus:outline-none">
            </td>
            <td class="py-4 px-4 text-slate-500 font-medium">₱${itemPrice.toFixed(2)}</td>
            <td class="py-4 px-4 font-bold text-slate-900 cart-row-subtotal-value" id="subtotal-${itemId}">₱${itemPrice.toFixed(2)}</td>
            <td class="py-4 px-4 text-right">
                <button type="button" onclick="this.closest('tr').remove(); calculateCartTotals();" class="text-rose-500 hover:text-rose-700 font-bold bg-transparent border-0 cursor-pointer">Remove</button>
            </td>
        `;

            tbody.appendChild(row);
            cleanupPOSSearchState();
            calculateCartTotals();
        }

        function cleanupPOSSearchState() {
            const input = document.getElementById('posProductSearchInput');
            if (input) input.value = "";
            const wrapper = document.getElementById('productSelectorWrapper');
            if (wrapper) wrapper.classList.add('hidden');
            activeSelectedProduct = null;
        }

        function updateRowSubtotal(id, price) {
            const row = document.getElementById('cart-item-' + id);
            if (!row) return;

            const qtyInput = row.querySelector('input[type="number"]');
            const qty = parseInt(qtyInput ? qtyInput.value : 1) || 1;
            const subtotal = qty * price;

            const subtotalCell = document.getElementById('subtotal-' + id);
            if (subtotalCell) {
                subtotalCell.innerText = '₱' + subtotal.toFixed(2);
            }
            calculateCartTotals();
        }

        function calculateCartTotals() {
            let itemsCount = 0;
            let orderSubtotal = 0;

            const quantityFields = document.querySelectorAll('#cartTableBody tr input[type="number"]');
            quantityFields.forEach(input => {
                itemsCount += parseInt(input.value) || 0;

                const parentRow = input.closest('tr');
                if (parentRow) {
                    const subtotalTextContainer = parentRow.querySelector('.cart-row-subtotal-value');
                    if (subtotalTextContainer) {
                        const numericCleanString = subtotalTextContainer.innerText.replace('₱', '').replace(/,/g, '').trim();
                        orderSubtotal += parseFloat(numericCleanString) || 0;
                    }
                }
            });

            const itemsField = document.getElementById('summary-qty-count');
            const subtotalField = document.getElementById('summary-subtotal');
            const totalField = document.getElementById('summary-total');

            if (itemsField) itemsField.innerText = itemsCount;
            if (subtotalField) subtotalField.innerText = '₱' + orderSubtotal.toFixed(2);
            if (totalField) totalField.innerText = '₱' + orderSubtotal.toFixed(2);

            const totalHiddenField = document.getElementById('form-total-amount');
            if (totalHiddenField) totalHiddenField.value = orderSubtotal.toFixed(2);

            calculateLiveChange(orderSubtotal);
        }

        function calculateLiveChange(forcedSubtotal = null) {
            let totalAmount = 0;

            if (forcedSubtotal !== null) {
                totalAmount = forcedSubtotal;
            } else {
                const totalElement = document.getElementById('summary-total');
                const totalText = totalElement ? totalElement.innerText : '₱0.00';
                totalAmount = parseFloat(totalText.replace('₱', '').replace(/,/g, '')) || 0;
            }

            const amountReceivedInput = document.getElementById('amount-received');
            const amountReceived = parseFloat(amountReceivedInput ? amountReceivedInput.value : 0) || 0;

            const changeDisplayField = document.getElementById('change-display');
            if (changeDisplayField) {
                if (amountReceived >= totalAmount && totalAmount > 0) {
                    changeDisplayField.innerText = '₱' + (amountReceived - totalAmount).toFixed(2);
                } else {
                    changeDisplayField.innerText = '₱0.00';
                }
            }
        }

        // Close suggestion menu if clicking away
        document.addEventListener('click', function (event) {
            const searchContainer = document.getElementById('posProductSearchInput');
            const customMenu = document.getElementById('customDropdownMenu');
            if (searchContainer && !searchContainer.contains(event.target) && customMenu) {
                customMenu.classList.add('hidden');
            }
        });
    </script>
@endsection