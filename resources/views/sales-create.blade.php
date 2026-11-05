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

                <!-- 1. Full-Width Search Product Dropdown Bar Container -->
                <!-- 1. Pure Input Search Container Layer (Top Layer) -->
                <div class="relative w-full mb-6">
                    <input type="text" id="product-search-input" placeholder="Search product..."
                        class="w-full rounded-lg bg-gray-100 border border-transparent px-6 py-3.5 text-sm font-semibold text-gray-700 placeholder-gray-400 focus:outline-none focus:bg-white focus:border-gray-200 transition shadow-inner" />

                    <!-- 2. Clean Floating Dynamic Results Panel Container (Sits perfectly under your search bar) -->
                    <div id="search-results-panel"
                        class="absolute left-0 right-0 top-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto hidden z-50">
                        <div class="p-2 space-y-1" id="search-results-rows">
                            <!-- Dynamic rows will inject here cleanly without visual code overlapping -->
                        </div>
                    </div>
                </div>

                <!-- Main Workspace Two-Column Split Layout Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                    <!-- Left Hand Column Container: Item List Table Segment -->
                    <div class="lg:col-span-2">
                        <h3 class="text-sm font-black text-gray-900 mb-3 tracking-wide">Item in this sale</h3>

                        <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                            <table class="w-full text-left border-collapse" id="pos-table">
                                <thead>
                                    <tr style="background-color: #D9D9D9;"
                                        class="bg-gray-100/80 text-xs font-bold text-gray-500 uppercase tracking-wider border-b border-gray-200">
                                        <th class="py-3.5 px-6">Item</th>
                                        <th class="py-3.5 px-6 text-center w-24">Qty</th>
                                        <th class="py-3.5 px-6 text-center">Price</th>
                                        <th class="py-3.5 px-6 text-center">Subtotal</th>
                                        <th class="py-3.5 px-6 text-center"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 font-semibold text-sm text-gray-700 bg-white"
                                    id="cart-rows">
                                    <!-- Empty by default, items will be added here dynamically from your dropdown menu -->
                                </tbody>
                            </table>
                        </div>

                        <!-- Bottom Inline Plus Text Trigger Selector -->
                        <button type="button" id="trigger-picker-focus"
                            class="text-xs font-bold text-emerald-700 hover:text-emerald-800 transition mt-4 flex items-center space-x-1">
                            <a href="{{ route('products.create') }}"
                                class="text-emerald hover:text-emerald-600 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">
                                + Add Product
                            </a>
                        </button>
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
                        const searchInput = document.getElementById('product-search-input');
                        const resultsPanel = document.getElementById('search-results-panel');
                        const resultsRows = document.getElementById('search-results-rows');
                        const cartRows = document.getElementById('cart-rows');
                        const receivedInput = document.getElementById('amount-received');

                        // Live inventory array mapping from database context
                        const productsList = [
                            @foreach($products as $prod)
                                { id: "{{ $prod->id }}", name: "{{ $prod->product_name ?? $prod->name }}", price: {{ $prod->price }} },
                            @endforeach
        ];

                        // 🧮 Core Calculator Engine
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

                            document.getElementById('summary-qty-count').innerText = totalItemsCount;
                            document.getElementById('summary-subtotal').innerText = '₱' + Math.round(runningTotalSum);
                            document.getElementById('summary-total').innerText = '₱' + Math.round(runningTotalSum);
                            document.getElementById('form-total-amount').value = runningTotalSum.toFixed(2);
                            document.getElementById('change-display').innerText = '₱' + Math.round(changeDue);
                        }

                        // 🔍 Search Bar Filter Matching 
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
                                        row.className = "flex justify-between items-center px-4 py-2.5 rounded-md hover:bg-gray-100 transition cursor-pointer text-sm font-semibold text-gray-700";
                                        row.innerHTML = `
                            <span>"${product.name}"</span>
                            <span class="text-gray-400">₱${Math.round(product.price)}</span>
                        `;

                                        row.addEventListener('click', function () {
                                            addProductToCart(product);
                                            resultsPanel.classList.add('hidden');
                                            searchInput.value = '';
                                        });

                                        resultsRows.appendChild(row);
                                    });
                                } else {
                                    resultsRows.innerHTML = '<p class="text-xs text-gray-400 p-3 text-center">No matching grocery items found.</p>';
                                    resultsPanel.classList.remove('hidden');
                                }
                            });
                        }

                        // Close floating query suggestions box on document clicks
                        document.addEventListener('click', function (e) {
                            if (resultsPanel && !searchInput.contains(e.target) && !resultsPanel.contains(e.target)) {
                                resultsPanel.classList.add('hidden');
                            }
                        });

                        // ➕ Add Item to Selection List
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

                            cartRows.insertAdjacentHTML('beforeend', newRowHtml);
                            calculateTotals();
                        }

                        // 🛠️ Event Delegation: Detects actions inside dynamically generated table rows cleanly
                        if (cartRows) {
                            // Handle changes in item quantity
                            cartRows.addEventListener('input', function (e) {
                                if (e.target.classList.contains('qty-input')) {
                                    calculateTotals();
                                }
                            });

                            // Handle clicking the remove button
                            cartRows.addEventListener('click', function (e) {
                                if (e.target.classList.contains('remove-btn')) {
                                    e.preventDefault();
                                    e.target.closest('tr').remove(); // Instantly purges the target row line layout block
                                    calculateTotals(); // Triggers calculations refresh automatically
                                }
                            });
                        }

                        if (receivedInput) {
                            receivedInput.addEventListener('input', calculateTotals);
                        }

                        // Execute absolute starting defaults calculations baseline context on launch
                        calculateTotals();
                    });
                </script>

            </form>
        </div>
    </div>
@endsection