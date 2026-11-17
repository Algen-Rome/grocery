@extends('layouts.blade-layout')

@section('content')
    <div class="min-h-screen bg-slate-50 lg:bg-white p-0 lg:p-8 font-sans">

        <!-- Top Back Navigation & Mobile Title -->
        <div class="mb-4 space-y-2">
            <h1 class="lg:hidden text-2xl font-extrabold text-slate-900">New sale</h1>
            <a href="{{ route('sales.index') }}"
                class="inline-flex items-center text-xs font-bold text-emerald-800 hover:text-emerald-600 transition">
                ← Back to sales
            </a>
        </div>

        <!-- Backend Form Submission Point -->
        <form action="{{ route('sales.store') }}" method="POST" id="sales-form">
            @csrf
            <input type="hidden" name="total_amount" id="form-total-amount" value="0.00" />

            <!-- Mobile Quick Search Bar (Syncs with Product Selector) -->
            <div class="lg:hidden mb-4">
                <input type="text" placeholder="Search product..." onfocus="showProductSelectionRow()"
                    oninput="showProductSelectionRow(); document.getElementById('posProductSearchInput').value = this.value; handleLiveProductSearch(this.value);"
                    class="w-full rounded-xl bg-white border border-slate-200 px-4 py-3 text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:border-[#165334]" />
            </div>

            <!-- Main Workspace Two-Column Split Layout Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">

                <!-- Left Hand Column Container: Item List -->
                <div class="lg:col-span-2">
                    <h2 class="text-sm font-black text-gray-900 mb-3 tracking-wide">Item in this sale</h2>

                    <div
                        class="lg:bg-white lg:p-6 lg:border lg:border-gray-200 lg:rounded-lg lg:shadow-sm w-full lg:min-h-[280px] flex flex-col justify-between">
                        <table class="w-full text-left border-collapse text-xs font-semibold text-slate-700 block lg:table">
                            <thead class="hidden lg:table-header-group">
                                <tr
                                    class="border-b border-slate-200 text-[10px] font-black text-slate-400 uppercase tracking-wider bg-slate-50/50">
                                    <th class="py-3 px-4">ITEM</th>
                                    <th class="py-3 px-4">QTY</th>
                                    <th class="py-3 px-4">PRICE</th>
                                    <th class="py-3 px-4">SUBTOTAL</th>
                                    <th class="py-3 px-4 text-right">ACTION</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody" class="block lg:table-row-group space-y-3 lg:space-y-0">
                                <tr id="emptyCartRow"
                                    class="block lg:table-row bg-white rounded-2xl border border-slate-200 lg:border-0">
                                    <td colspan="5" class="block lg:table-cell py-6 text-slate-400 font-medium text-center">
                                        No items added to this transaction record yet.
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- INTERACTIVE SELECTION ROW LINK -->
                        <div class="mt-3 lg:mt-auto pt-3 lg:pt-4 lg:border-t lg:border-gray-100">
                            <button type="button" onclick="showProductSelectionRow()"
                                class="text-[#165334] hover:text-emerald-700 bg-transparent border-0 p-0 font-bold text-xs cursor-pointer flex items-center space-x-1">
                                <span>+ Add another item</span>
                            </button>
                        </div>
                    </div>


                    <!-- DYNAMIC ROW SELECTOR PANEL -->
                    <div id="productSelectorWrapper"
                        class="hidden mt-4 p-4 lg:p-5 bg-white lg:bg-slate-50 rounded-2xl lg:rounded-xl border border-slate-200 w-full relative shadow-sm lg:shadow-none">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-wider mb-2">
                            Search Store Product Item
                        </label>
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full relative">
                            <div class="flex-1 relative">
                                <input type="text" id="posProductSearchInput"
                                    placeholder="Type a product name to search instantly..."
                                    oninput="handleLiveProductSearch(this.value)"
                                    onfocus="handleLiveProductSearch(this.value)"
                                    class="w-full bg-slate-50 lg:bg-white border border-slate-200 rounded-xl lg:rounded-lg p-3 text-xs font-semibold text-slate-700 focus:outline-none focus:border-emerald-500 shadow-inner">

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
                                                        {{ $product->stock_count }} units
                                                    </p>
                                                </div>
                                                <span
                                                    class="text-xs font-black text-emerald-700">₱{{ number_format($product->price, 2) }}</span>
                                            </div>
                                        @endforeach
                                    @endif
                                    <div id="noResultsFoundMessage"
                                        class="hidden p-4 text-center text-xs font-bold text-slate-400">
                                        No matching products found in stock registry.
                                    </div>
                                </div>
                            </div>

                            <button type="button" onclick="addCurrentlySelectedProduct()"
                                class="px-5 py-3.5 bg-[#165334] hover:bg-emerald-800 text-white font-black text-xs rounded-xl lg:rounded-lg transition border-0 cursor-pointer shadow-sm uppercase tracking-wider whitespace-nowrap">
                                Add to Sale
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Right Hand Column Container: Order Summary Panel -->
                <div
                    class="bg-white lg:bg-slate-50 p-5 lg:p-6 rounded-2xl border border-slate-200 lg:border-transparent flex flex-col justify-between shadow-sm lg:min-h-[380px]">
                    <div class="space-y-4">
                        <h2
                            class="text-base lg:text-sm font-extrabold lg:font-black text-gray-900 lg:uppercase tracking-wide">
                            Order Summary
                        </h2>

                        <!-- Customer Input -->
                        <div
                            class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-1.5 text-xs font-semibold">
                            <span class="text-slate-600 lg:text-gray-400 font-bold">Customer Name (Optional)</span>
                            <input type="text" name="customer_name" value="Maria Santos" required
                                class="bg-slate-100 lg:bg-transparent border border-transparent lg:border-gray-200 hover:border-gray-400 focus:border-emerald-600 focus:outline-none text-left lg:text-right font-bold text-gray-900 rounded-xl lg:rounded-none px-3.5 py-2.5 lg:px-1 lg:py-0.5 transition w-full lg:w-48" />
                        </div>

                        <!-- Live Metrics Summary Readouts -->
                        <div
                            class="space-y-3 border-b border-gray-200 pb-3.5 text-xs lg:text-sm font-semibold text-slate-700">
                            <div class="flex justify-between">
                                <span class="text-slate-500 lg:text-gray-400">Items</span>
                                <span id="summary-qty-count" class="text-gray-900 font-black">0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500 lg:text-gray-400">Subtotal</span>
                                <span id="summary-subtotal" class="text-gray-900 font-black">₱0.00</span>
                            </div>
                        </div>

                        <div class="flex justify-between text-sm font-black text-gray-900 pb-1">
                            <span>Total</span>
                            <span id="summary-total" class="text-base font-black text-gray-900">₱0.00</span>
                        </div>

                        <!-- Payment Method Dropdown -->
                        <div class="space-y-1.5 text-xs font-semibold">
                            <label class="block text-slate-600 font-bold">Payment Method</label>
                            <select name="payment_method"
                                class="w-full bg-slate-100 border border-transparent rounded-xl px-3.5 py-2.5 text-xs font-bold text-gray-900 focus:outline-none focus:border-emerald-600">
                                <option value="Cash">Cash</option>
                                <option value="GCash">GCash</option>
                                <option value="Card">Card</option>
                            </select>
                        </div>

                        <div
                            class="space-y-3 lg:bg-white lg:p-4 lg:rounded-xl lg:border lg:border-gray-100 lg:shadow-inner">
                            <div
                                class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-1.5 text-xs font-bold text-gray-700">
                                <span>Amount Received</span>
                                <input type="number" step="0.01" id="amount-received" oninput="calculateLiveChange()"
                                    placeholder="0.00"
                                    class="w-full lg:w-28 bg-slate-100 lg:bg-gray-50 border border-transparent lg:border-gray-200 rounded-xl lg:rounded-md px-3.5 py-2.5 lg:p-1.5 text-left lg:text-right font-black text-gray-900 focus:outline-none focus:border-emerald-500" />
                            </div>
                            <div class="flex justify-between items-center text-xs font-bold text-gray-700 pt-1">
                                <span>Change</span>
                                <span id="change-display" class="font-black text-emerald-700 text-sm">₱0.00</span>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" id="submit-sale-btn"
                            class="w-full bg-[#165334] hover:bg-emerald-800 text-white font-black text-xs lg:uppercase tracking-wider py-3.5 lg:py-4 rounded-xl transition-all shadow-md transform active:scale-[0.98] cursor-pointer text-center border-0">
                            Complete Sale
                        </button>
                    </div>
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
            const clearQuery = query.toLowerCase().trim();

            if (clearQuery === '') {
                if (menu) menu.classList.add('hidden');
                return;
            }

            if (menu) menu.classList.remove('hidden');
            let matches = 0;

            items.forEach(item => {
                const productName = item.getAttribute('data-name');
                if (productName && productName.includes(clearQuery)) {
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
                const qtyInput = existingRow.querySelector('.cart-qty-field');
                if (qtyInput) {
                    qtyInput.value = parseInt(qtyInput.value) + 1;
                    const hiddenQty = existingRow.querySelector('.hidden-qty-field');
                    if (hiddenQty) hiddenQty.value = qtyInput.value;
                }
                updateRowSubtotal(itemId, itemPrice);
                cleanupPOSSearchState();
                return;
            }

            const row = document.createElement('tr');
            row.id = 'cart-item-' + itemId;
            row.className = 'block lg:table-row bg-white rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 lg:border-b lg:border-slate-100 p-4 lg:p-0 space-y-2 lg:space-y-0 shadow-sm lg:shadow-none align-middle';

            row.innerHTML = `
                <td class="block lg:table-cell lg:py-4 lg:px-4 font-bold text-slate-900 text-sm lg:text-xs pb-1 lg:pb-4">
                    ${itemName}
                    <input type="hidden" name="product_id[]" value="${itemId}">
                </td>
                <td class="flex justify-between items-center lg:table-cell lg:py-4 lg:px-4">
                    <span class="lg:hidden font-bold text-slate-400 uppercase text-[10px]">QTY</span>
                    <input type="number" value="1" min="1"
                        oninput="syncHiddenQty(${itemId}, this.value); updateRowSubtotal(${itemId}, ${itemPrice})"
                        class="w-16 border border-slate-200 rounded-md p-1 text-right lg:text-center text-xs font-bold text-slate-800 focus:outline-none cart-qty-field">
                    <input type="hidden" name="quantity[]" value="1" id="hidden-qty-${itemId}" class="hidden-qty-field">
                </td>
                <td class="flex justify-between items-center lg:table-cell lg:py-4 lg:px-4 text-slate-600 lg:text-slate-500 font-medium">
                    <span class="lg:hidden font-bold text-slate-400 uppercase text-[10px]">PRICE</span>
                    <span>₱${itemPrice.toFixed(2)}</span>
                </td>
                <td class="flex justify-between items-center lg:table-cell lg:py-4 lg:px-4 font-bold text-slate-900">
                    <span class="lg:hidden font-bold text-slate-400 uppercase text-[10px]">SUBTOTAL</span>
                    <span class="cart-row-subtotal-value" id="subtotal-${itemId}">₱${itemPrice.toFixed(2)}</span>
                </td>
                <td class="block lg:table-cell lg:py-4 lg:px-4 text-right pt-1 lg:pt-4">
                    <button type="button" onclick="this.closest('tr').remove(); calculateCartTotals();"
                        class="text-rose-500 hover:text-rose-700 font-bold text-xs bg-transparent border-0 cursor-pointer">Remove</button>
                </td>
            `;

            tbody.appendChild(row);
            cleanupPOSSearchState();
            calculateCartTotals();
        }
        function syncHiddenQty(id, val) {
            const hiddenInput = document.getElementById('hidden-qty-' + id);
            if (hiddenInput) {
                hiddenInput.value = parseInt(val) || 1;
            }
        }

        function cleanupPOSSearchState() {
            const input = document.getElementById('posProductSearchInput');
            if (input) input.value = '';
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
            const submitBtn = document.getElementById('submit-sale-btn');

            if (changeDisplayField) {
                if (totalAmount === 0) {
                    changeDisplayField.innerText = '₱0.00';
                    changeDisplayField.className = 'text-base font-black text-gray-900';
                    if (submitBtn) submitBtn.disabled = true;
                } else if (amountReceived < totalAmount) {
                    const shortAmount = totalAmount - amountReceived;
                    changeDisplayField.innerText = 'Short: ₱' + shortAmount.toFixed(2);
                    changeDisplayField.className = 'text-sm font-black text-rose-600';
                    if (submitBtn) submitBtn.disabled = true;
                } else {
                    changeDisplayField.innerText = '₱' + (amountReceived - totalAmount).toFixed(2);
                    changeDisplayField.className = 'text-base font-black text-emerald-700';
                    if (submitBtn) submitBtn.disabled = false;
                }
            }
        }

        document.addEventListener('click', function (event) {
            const searchContainer = document.getElementById('posProductSearchInput');
            const customMenu = document.getElementById('customDropdownMenu');
            if (searchContainer && !searchContainer.contains(event.target) && customMenu && !customMenu.contains(event.target)) {
                customMenu.classList.add('hidden');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const salesForm = document.getElementById('sales-form');
            if (salesForm) {
                salesForm.addEventListener('submit', function (event) {
                    const totalElement = document.getElementById('summary-total');
                    const totalText = totalElement ? totalElement.innerText : '₱0.00';
                    const totalAmount = parseFloat(totalText.replace('₱', '').replace(/,/g, '')) || 0;

                    const amountReceivedInput = document.getElementById('amount-received');
                    const amountReceived = parseFloat(amountReceivedInput ? amountReceivedInput.value : 0) || 0;

                    if (totalAmount <= 0) {
                        event.preventDefault();
                        alert('Cannot complete sale: There are no items in the checkout cart.');
                        return false;
                    }

                    if (amountReceived < totalAmount) {
                        event.preventDefault();
                        alert('Cannot complete sale: The amount received is less than the order total.');
                        return false;
                    }
                });
            }
        });
    </script>
@endsection