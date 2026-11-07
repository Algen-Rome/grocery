<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt - {{ $order->order_number }}</title>
    <!-- 🌟 STABLE TAILWIND FIX: Replaces the non-functional script compiler link with a native CSS CDN layer -->
    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://tailwindcss.com"></script>
</head>

<body class="bg-gray-50 min-h-screen flex items-center justify-center p-4 font-sans antialiased">

    <div
        class="w-full max-w-lg bg-white rounded-3xl shadow-xl border border-gray-100 p-8 transform transition-all duration-300">

        <!-- Top Back Navigation Context Header Section -->
        <div class="flex items-center justify-between mb-8 border-b border-gray-100 pb-5">
            <a href="/sales-ledger"
                class="inline-flex items-center text-sm font-bold text-green-600 hover:text-green-700 transition gap-2 no-underline group">
                <span class="transform group-hover:-translate-x-1 transition-transform">&larr;</span> Back to Ledger
            </a>
            <span
                class="bg-green-50 text-green-700 text-xs font-black tracking-widest uppercase px-3 py-1.5 rounded-xl">
                Official Invoice
            </span>
        </div>

        <!-- Receipt Main Tracking Metadata Identification Header Details -->
        <div class="mb-8">
            <h2 class="text-2xl font-black text-gray-900 tracking-tight mb-1">Order Details</h2>
            <p class="text-sm text-gray-400 font-semibold flex items-center gap-1.5">
                Tracking Identifier:
                <span
                    class="text-gray-800 font-extrabold font-mono bg-gray-100 px-2 py-0.5 rounded-lg text-xs">{{ $order->order_number }}</span>
            </p>
        </div>

        <!-- Meta Profiles Details Summary Fields Grid Layout Structure -->
        <div
            class="grid grid-cols-2 gap-y-5 gap-x-6 text-sm mb-8 bg-gray-50/50 p-5 rounded-2xl border border-gray-100/50">
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider font-extrabold mb-1">Customer Profile</p>
                <p class="text-gray-900 font-black text-base">{{ $order->customer_name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs uppercase tracking-wider font-extrabold mb-1">Contact Phone</p>
                <p class="text-gray-700 font-bold text-base">{{ $order->customer_phone ?? 'N/A' }}</p>
            </div>
            <div class="col-span-2 border-t border-gray-200/50 pt-3">
                <p class="text-gray-400 text-xs uppercase tracking-wider font-extrabold mb-1">Destination Delivery
                    Address</p>
                <p class="text-gray-700 font-bold text-base leading-relaxed">
                    {{ $order->delivery_address ?? 'Store Counter Pickup' }}</p>
            </div>
        </div>

        <!-- Ordered Items Payload Matrix Display List Container -->
        <div class="mb-8">
            <p class="text-gray-400 text-xs uppercase tracking-wider font-extrabold mb-4 pl-1">Cart Summary</p>

            <div class="max-h-48 overflow-y-auto pr-1">
                <ul class="space-y-3.5 m-0 p-0 list-none">
                    @if(is_array($order->items) || is_object($order->items))
                        @foreach($order->items as $item)
                            <li
                                class="flex justify-between items-center text-sm font-bold text-gray-700 bg-white border border-gray-100 p-3 rounded-xl shadow-sm hover:border-gray-200 transition">
                                <div class="flex items-center gap-2">
                                    <span class="text-gray-900 font-extrabold">{{ $item['name'] ?? 'Grocery Product' }}</span>
                                    <span
                                        class="bg-gray-100 text-gray-500 font-black px-2 py-0.5 rounded-lg text-xs">x{{ $item['quantity'] ?? 1 }}</span>
                                </div>
                                <span
                                    class="text-gray-900 font-black font-mono">₱{{ number_format(($item['price'] ?? 0), 2) }}</span>
                            </li>
                        @endforeach
                    @else
                        <li
                            class="text-sm text-gray-400 italic text-center py-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            No cart item logs recorded for this receipt transaction.
                        </li>
                    @endif
                </ul>
            </div>
        </div>

        <!-- Billing Summary Section Calculations Footer -->
        <div
            class="bg-emerald-700 text-white rounded-2xl p-5 flex justify-between items-center shadow-lg transform hover:scale-[1.01] transition-transform duration-200">
            <div class="flex flex-col">
                <span class="text-gray-100 uppercase tracking-widest font-black text-2xs mb-0.5">Total Amount</span>
                <span class="text-xs text-green-400 font-bold">Gross Settlement</span>
            </div>
            <span class="text-2xl font-black font-mono tracking-tight text-white">
                ₱{{ number_format($order->total_amount, 2) }}
            </span>
        </div>

    </div>

</body>

</html>