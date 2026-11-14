@extends('layouts.blade-layout')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-3 flex justify-between items-center">
            <a href="{{ route('online-orders.index') }}" class="text-xs font-bold text-gray-700 hover:text-black">
                ← Back to Online Orders
            </a>
        </div>

        @if($errors->any())
            <div class="mb-4 bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-xl text-xs">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('online-orders.store') }}" id="order-form"
            class="bg-[#d9dbdb] rounded-3xl p-6 sm:p-8 shadow-sm space-y-6">
            @csrf

            <h2 class="text-center text-sm font-bold text-gray-900 tracking-wide uppercase">
                CUSTOMER ORDER FORM
            </h2>

            {{-- Customer Details Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-gray-800 mb-1.5">Full Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                        class="w-full border border-gray-500 bg-transparent rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-800 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full border border-gray-500 bg-transparent rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-800 mb-1.5">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                        class="w-full border border-gray-500 bg-transparent rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-800 mb-1.5">Complete Delivery Address</label>
                    <input type="text" name="delivery_address" value="{{ old('delivery_address') }}"
                        class="w-full border border-gray-500 bg-transparent rounded-xl px-3.5 py-2.5 text-xs text-gray-900 focus:outline-none focus:border-black">
                </div>
            </div>

            {{-- Order Type & Payment Method --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-1">
                <div>
                    <p class="text-xs font-bold text-gray-900 mb-2">Order Type</p>
                    <div class="flex items-center gap-6 text-xs text-gray-800">
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="order_type" value="Delivery" checked class="accent-black">
                            <span>Delivery</span>
                        </label>
                        <label class="inline-flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="order_type" value="Pick Up" class="accent-black">
                            <span>Pick Up</span>
                        </label>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold text-gray-900 mb-2">Payment Method</p>
                    <div class="space-y-1.5 text-xs text-gray-800">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment_method" value="Cash on Delivery" checked class="accent-black">
                            <span>Cash on Delivery</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment_method" value="Cash on Pick Up" class="accent-black">
                            <span>Cash on Pick Up</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="radio" name="payment_method" value="Gcash" class="accent-black">
                            <span>Gcash</span>
                        </label>
                    </div>
                </div>
            </div>
            {{-- Select Grocery Products --}}
            <div class="pt-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                    <p class="text-xs font-bold text-gray-900">Select grocery products</p>

                    <div class="flex items-center gap-2">
                        <select id="product-picker"
                            class="border border-gray-500 bg-white rounded-lg px-3 py-1.5 text-xs text-gray-800">
                            <option value="">-- Choose a product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}" data-stock="{{ $product->stock_count }}">
                                    {{ $product->name }} (₱{{ number_format($product->price, 0) }} • {{ $product->stock_count }}
                                    left)
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="addSelectedProduct()"
                            class="bg-[#1b5335] text-white text-xs font-bold px-3.5 py-1.5 rounded-lg hover:bg-[#143f28]">
                            + Add
                        </button>
                    </div>
                </div>

                <div id="selected-products-list" class="space-y-3"></div>
                <div id="hidden-inputs-container"></div>
            </div>

            {{-- Order Summary --}}
            <div class="border-t border-gray-400 pt-4">
                <p class="text-xs font-bold text-gray-900 mb-2">Order Summary</p>
                <div id="order-summary-items" class="space-y-1 text-xs text-gray-800">
                    <p class="text-gray-500 italic">No products added yet.</p>
                </div>

                <div
                    class="border-t border-gray-500 mt-3 pt-2 flex justify-between items-center text-xs font-bold text-gray-900">
                    <span>Subtotal</span>
                    <span id="order-subtotal">₱0</span>
                </div>
                <p class="text-[10px] text-gray-600 mt-1">Delivery fee (if applicable) will be confirmed by the store</p>
            </div>

            {{-- Place Order Button --}}
            <div class="pt-2">
                <button type="submit"
                    class="w-full border border-gray-600 hover:bg-gray-300 text-gray-900 font-semibold text-xs py-2.5 rounded-xl transition">
                    Place Order
                </button>
            </div>
        </form>
    </div>

    <script>
        let cart = [];

        function addSelectedProduct() {
            const select = document.getElementById('product-picker');
            const option = select.options[select.selectedIndex];
            if (!option || !option.value) return;

            const id = option.value;
            const name = option.dataset.name;
            const price = parseFloat(option.dataset.price);
            const maxStock = parseInt(option.dataset.stock, 10) || 1;

            const existing = cart.find(item => item.id === id);
            if (existing) {
                if (existing.quantity < maxStock) {
                    existing.quantity += 1;
                }
            } else {
                cart.push({ id, name, price, maxStock, quantity: 1 });
            }

            select.value = '';
            renderCart();
        }

        function updateQty(index, delta) {
            const nextQty = cart[index].quantity + delta;
            if (nextQty <= 0) {
                cart.splice(index, 1);
            } else if (nextQty <= cart[index].maxStock) {
                cart[index].quantity = nextQty;
            }
            renderCart();
        }

        function removeItem(index) {
            cart.splice(index, 1);
            renderCart();
        }

        function renderCart() {
            const listEl = document.getElementById('selected-products-list');
            const summaryEl = document.getElementById('order-summary-items');
            const hiddenEl = document.getElementById('hidden-inputs-container');
            const subtotalEl = document.getElementById('order-subtotal');

            listEl.innerHTML = '';
            summaryEl.innerHTML = '';
            hiddenEl.innerHTML = '';

            if (cart.length === 0) {
                summaryEl.innerHTML = '<p class="text-gray-500 italic">No products added yet.</p>';
                subtotalEl.textContent = '₱0';
                return;
            }

            let subtotal = 0;

            cart.forEach((item, index) => {
                const lineTotal = item.price * item.quantity;
                subtotal += lineTotal;

                listEl.innerHTML += `
                        <div class="flex items-center justify-between text-xs text-gray-900 py-1">
                            <div>
                                <p class="font-semibold">${item.name}</p>
                                <p class="text-[10px] text-gray-600">₱${item.price.toLocaleString()} each</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <span class="font-bold">₱${item.price.toLocaleString()}</span>
                                <div class="flex items-center gap-2">
                                    <button type="button" onclick="updateQty(${index}, -1)"
                                            class="w-5 h-5 rounded-full bg-black text-white flex items-center justify-center font-bold leading-none">−</button>
                                    <span class="w-4 text-center font-semibold">${item.quantity}</span>
                                    <button type="button" onclick="updateQty(${index}, 1)"
                                            class="w-5 h-5 rounded-full bg-black text-white flex items-center justify-center font-bold leading-none">+</button>
                                </div>
                                <button type="button" onclick="removeItem(${index})"
                                        class="text-red-500 hover:text-red-700 font-semibold">Delete</button>
                            </div>
                        </div>
                    `;

                summaryEl.innerHTML += `
                        <div class="flex justify-between">
                            <span>${item.name} ×${item.quantity}</span>
                            <span class="font-semibold">₱${lineTotal.toLocaleString()}</span>
                        </div>
                    `;

                hiddenEl.innerHTML += `
                        <input type="hidden" name="items[${index}][product_id]" value="${item.id}">
                        <input type="hidden" name="items[${index}][quantity]" value="${item.quantity}">
                    `;
            });

            subtotalEl.textContent = '₱' + subtotal.toLocaleString();
        }
    </script>
@endsection