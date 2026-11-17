@extends('layouts.blade-layout')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-3 flex justify-between items-center">
            <a href="{{ route('online-orders.index') }}" class="text-xs font-bold text-gray-700 hover:text-black">
                ← Back to Online Orders
            </a>
        </div>

        <h1 class="lg:hidden text-2xl font-extrabold text-slate-900 mb-4">Customer order form</h1>

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
            class="lg:bg-[#d9dbdb] lg:rounded-3xl lg:p-8 lg:shadow-sm space-y-4 lg:space-y-6">
            @csrf

            <h2 class="hidden lg:block text-center text-sm font-bold text-gray-900 tracking-wide uppercase">
                CUSTOMER ORDER FORM
            </h2>

            {{-- Customer Details Grid --}}
            <div
                class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 p-5 lg:p-0 shadow-sm lg:shadow-none grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold lg:font-semibold text-slate-800 lg:text-gray-800 mb-1.5">Full
                        Name</label>
                    <input type="text" name="full_name" value="{{ old('full_name') }}" required
                        class="w-full bg-slate-100 lg:bg-transparent border border-transparent lg:border-gray-500 rounded-xl px-3.5 py-3 lg:py-2.5 text-xs font-semibold text-gray-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold lg:font-semibold text-slate-800 lg:text-gray-800 mb-1.5">Email
                        Address</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full bg-slate-100 lg:bg-transparent border border-transparent lg:border-gray-500 rounded-xl px-3.5 py-3 lg:py-2.5 text-xs font-semibold text-gray-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold lg:font-semibold text-slate-800 lg:text-gray-800 mb-1.5">Contact
                        Number</label>
                    <input type="text" name="contact_number" value="{{ old('contact_number') }}" required
                        class="w-full bg-slate-100 lg:bg-transparent border border-transparent lg:border-gray-500 rounded-xl px-3.5 py-3 lg:py-2.5 text-xs font-semibold text-gray-900 focus:outline-none focus:border-black">
                </div>

                <div>
                    <label class="block text-xs font-bold lg:font-semibold text-slate-800 lg:text-gray-800 mb-1.5">Complete
                        Delivery Address</label>
                    <input type="text" name="delivery_address" value="{{ old('delivery_address') }}"
                        class="w-full bg-slate-100 lg:bg-transparent border border-transparent lg:border-gray-500 rounded-xl px-3.5 py-3 lg:py-2.5 text-xs font-semibold text-gray-900 focus:outline-none focus:border-black">
                </div>
            </div>
            {{-- Order Type & Payment Method --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 pt-1">
                <div
                    class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 p-5 lg:p-0 shadow-sm lg:shadow-none">
                    <p class="text-xs font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-3 lg:mb-2">Order Type
                    </p>
                    <div
                        class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-6 text-xs font-semibold lg:font-normal text-slate-800 lg:text-gray-800">
                        <label class="inline-flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="order_type" value="Delivery" checked
                                class="accent-[#165334] lg:accent-black w-4 h-4 lg:w-auto lg:h-auto">
                            <span>Delivery</span>
                        </label>
                        <label class="inline-flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="order_type" value="Pick Up"
                                class="accent-[#165334] lg:accent-black w-4 h-4 lg:w-auto lg:h-auto">
                            <span>Pick Up</span>
                        </label>
                    </div>
                </div>

                <div
                    class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 p-5 lg:p-0 shadow-sm lg:shadow-none">
                    <p class="text-xs font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-3 lg:mb-2">Payment
                        Method</p>
                    <div
                        class="space-y-3 lg:space-y-1.5 text-xs font-semibold lg:font-normal text-slate-800 lg:text-gray-800">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="payment_method" value="Cash on Delivery" checked
                                class="accent-[#165334] lg:accent-black w-4 h-4 lg:w-auto lg:h-auto">
                            <span>Cash on Delivery</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="payment_method" value="Cash on Pick Up"
                                class="accent-[#165334] lg:accent-black w-4 h-4 lg:w-auto lg:h-auto">
                            <span>Cash on Pick Up</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="radio" name="payment_method" value="Gcash"
                                class="accent-[#165334] lg:accent-black w-4 h-4 lg:w-auto lg:h-auto">
                            <span>Gcash</span>
                        </label>
                    </div>
                </div>
            </div>
            {{-- Select Grocery Products --}}
            <div class="pt-1">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-2.5 mb-3">
                    <p class="text-sm lg:text-xs font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">Select grocery
                        products</p>

                    <div class="flex items-center gap-2">
                        <select id="product-picker"
                            class="flex-1 lg:flex-initial border border-slate-200 lg:border-gray-500 bg-white rounded-xl lg:rounded-lg px-3 py-2.5 lg:py-1.5 text-xs text-gray-800">
                            <option value="">-- Choose a product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-name="{{ $product->name }}"
                                    data-price="{{ $product->price }}" data-stock="{{ $product->stock_count }}">
                                    {{ $product->name }} (₱{{ number_format($product->price, 0) }}) •
                                    {{ $product->stock_count }} left)
                                </option>
                            @endforeach
                        </select>
                        <button type="button" onclick="addSelectedProduct()"
                            style="background-color: #165334; color: #ffffff;"
                            class="hover:opacity-95 text-xs font-bold px-4 lg:px-3.5 py-2.5 lg:py-1.5 rounded-xl lg:rounded-lg shrink-0 cursor-pointer">
                            + Add
                        </button>
                    </div>
                </div>

                <div id="selected-products-list" class="space-y-3"></div>
                <div id="hidden-inputs-container"></div>
            </div>

            {{-- Order Summary --}}
            <div
                class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 lg:border-t lg:border-gray-400 p-5 lg:p-0 lg:pt-4 shadow-sm lg:shadow-none">
                <p class="text-sm lg:text-xs font-extrabold lg:font-bold text-slate-900 lg:text-gray-900 mb-3 lg:mb-2">Order
                    Summary</p>
                <div id="order-summary-items" class="space-y-2 lg:space-y-1 text-xs text-gray-800">
                    <p class="text-gray-500 italic">No products added yet.</p>
                </div>

                <div>
                    <div
                        class="border-t border-slate-100 lg:border-gray-500 mt-3 pt-2.5 lg:pt-2 flex justify-between items-center text-xs font-extrabold lg:font-bold text-slate-900 lg:text-gray-900">
                        <span>Subtotal</span>
                        <span id="order-subtotal">₱0</span>
                    </div>
                    <p class="text-[10px] text-slate-400 lg:text-gray-600 mt-1.5 lg:mt-1">Delivery fee (if applicable) will
                        be confirmed by the store</p>
                </div>
            </div>

            {{-- Place Order Button --}}
            <div class="pt-1 lg:pt-2">
                <button type="submit" style="background-color: #165334; color: #ffffff;"
                    class="w-full hover:opacity-95 font-bold lg:font-semibold text-xs py-3.5 lg:py-2.5 rounded-xl transition cursor-pointer shadow-sm">
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
                        <div class="bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 p-4 lg:p-0 lg:py-1 shadow-sm lg:shadow-none flex flex-col lg:flex-row lg:items-center justify-between gap-3 text-xs text-gray-900">
                            <div class="flex justify-between items-start lg:block">
                                <div>
                                    <p class="font-extrabold lg:font-bold text-sm lg:text-xs text-slate-900">${item.name}</p>
                                    <p class="text-[10px] text-slate-400 lg:text-gray-600 mt-0.5">₱${item.price.toLocaleString()} each</p>
                                </div>
                                <span class="lg:hidden font-extrabold text-xs text-slate-900">₱${lineTotal.toLocaleString()}</span>
                            </div>
                            <div class="flex items-center justify-between lg:justify-end gap-4">
                                <span class="hidden lg:inline font-bold">₱${lineTotal.toLocaleString()}</span>
                                <div class="flex items-center gap-3 lg:gap-2">
                                    <button type="button" onclick="updateQty(${index}, -1)"
                                        style="background-color: #165334; color: #ffffff;"
                                        class="w-12 h-7 lg:w-5 lg:h-5 rounded-lg lg:rounded-full text-xs font-bold flex items-center justify-center leading-none cursor-pointer">-</button>
                                    <span class="w-6 lg:w-4 text-center font-extrabold lg:font-semibold">${item.quantity}</span>
                                    <button type="button" onclick="updateQty(${index}, 1)"
                                        style="background-color: #165334; color: #ffffff;"
                                        class="w-12 h-7 lg:w-5 lg:h-5 rounded-lg lg:rounded-full text-xs font-bold flex items-center justify-center leading-none cursor-pointer">+</button>
                                </div>
                                <button type="button" onclick="removeItem(${index})"
                                    class="text-red-600 hover:text-red-700 font-bold text-[11px] cursor-pointer">Delete</button>
                            </div>
                        </div>
                    `;

                summaryEl.innerHTML += `
                        <div class="flex justify-between items-center">
                            <span class="font-semibold text-slate-700">${item.name}</span>
                            <div class="flex items-center gap-6">
                                <span class="text-slate-500">x${item.quantity}</span>
                                <span class="font-bold text-slate-900">₱${lineTotal.toLocaleString()}</span>
                            </div>
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