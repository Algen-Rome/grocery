@extends('layouts.blade-layout')


@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <aside class="w-64 border-r border-gray-200 p-6 flex flex-col space-y-2">
            <div class="mb-8 flex items-center space-x-3">
                <div class="bg-emerald-600 rounded-full h-5 w-5"></div>
                Grocery Admin
                <span class="text-lg font-bold text-lg"></span>
            </div>
            <a href="{{ route('dashboard') }}"
                class="bg-emerald-800 text-white px-4 py-3 rounded-xl font-medium text-sm transition block shadow-sm">Dashboard</a>
            <a href="{{ route('sales.index') }}"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Sales</a>
            <a href="{{ route('reports.index') }}"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Reports</a>
            <a href="   "
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Settings</a>
            <a href="#"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Admin</a>
            <a href="#"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Online
                Orders</a>
            <a href="#"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Stock
                Alerts</a>
            <form action="{{ route('logout') }}" method="POST" class="w-full mt-auto">
                @csrf
                <button type="submit"
                    class="w-full text-left text-red-500 hover:bg-red-50 font-medium px-4 py-3 rounded-xl text-sm block transition">Logout</button>
            </form>
        </aside>
        <main class="flex-1 p-8">
            <div class="flex justify-between items-center gap-10">
                <h1 class="text-2xl font-black tracking-wide text-gray-900">DASHBOARD</h1>
                <input type="text" placeholder="Search" ~
                    class="rounded-full bg-gray-300 px-6 py-2.5 w-[40rem] border border-transparent focus:outline-none focus:bg-white focus:border-emerald-500 transition" />
                <div class="flex ml-auto bg-gray-400 rounded-full h-10 w-10 p-0"></div>
                Admin Name
                <span class="text-lg font-extrabold"></span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-6">
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Sales</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">₱{{ number_format($totalSales, 2) }}</h3>
                    </div>
                    <div class="text-xs font-bold text-emerald-600 mt-2 flex items-center">
                        <p class="text-xs {{ $salesDiffColor }} font-bold mt-2">{{ $salesDiffText }}</p>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orders Today</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">{{ $ordersToday }}</h3>
                    </div>
                    <div
                        class="text-xs font-bold mt-2 flex items-center {{ str_contains($ordersTrend, '-') || str_contains($ordersTrend, 'No') ? 'text-rose-600' : 'text-emerald-600' }}">
                        <span>
                            {{ str_contains($ordersTrend, '-') ? '↓' : (str_contains($ordersTrend, 'No') ? '' : '↑') }}
                            {{ $ordersTrend }}
                        </span>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Products</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">{{ number_format($totalProducts) }}</h3>
                    </div>
                    <div class="text-xs font-bold text-amber-500 mt-2 flex items-center">
                        @if ($lowStockCount <= 0)
                            ⚠️ {{ $lowStockCount }} {{ str_contains('item', $lowStockCount) }} <span
                                class="text-gray-400 font-normal ml-1">low stock alerts</span>
                        @else
                            <span class="text-emerald-400 font-bold ml-1">✅ All products in stock</span>
                        @endif
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Customers</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">{{ $activeCustomers }}</h3>
                    </div>
                    <div
                        class="text-xs font-bold mt-2 flex items-center {{ str_contains($customerTrend, '-') || str_contains($customerTrend, '0 active') ? 'text-rose-600' : 'text-emerald-600' }}">
                        <span>
                            {{ str_contains($customerTrend, '-') ? '↓' : (str_contains($customerTrend, '0 active') ? '' : '↑') }}
                            {{ $customerTrend }}
                        </span>
                    </div>
                </div>

            </div>
            <div class="flex flex-col lg:flex-row gap-6 mt-6">

                <div
                    class="w-full lg:w-2/3 bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 mb-1">Sales Overview</h3>
                        <p class="text-xs text-gray-400 mb-4">Completed revenue totals for the last 7 days</p>
                    </div>

                    <div class="h-64 flex items-end justify-between gap-3 bg-gray-50 p-6 rounded-lg border border-gray-100">
                        @foreach($chartData as $data)
                            <div class="flex flex-col items-center flex-1 h-full justify-end group relative">
                                <div
                                    class="absolute -top-8 bg-gray-800 text-white text-[10px] px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200 pointer-events-none whitespace-nowrap shadow-sm z-10">
                                    ₱{{ number_format($data['sales'], 2) }}
                                </div>

                                <div class="w-full rounded-t transition-all duration-500 {{ $data['day_name'] === date('D') ? 'bg-emerald-600' : 'bg-emerald-200 group-hover:bg-emerald-300' }}"
                                    style="height: {{ $data['height'] }}%;">
                                </div>

                                <span
                                    class="text-[11px] font-medium text-gray-400 mt-2 uppercase tracking-wider">{{ $data['day_name'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div
                    class="w-full lg:w-1/3 bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h3 class="text-sm font-bold text-gray-800 mb-1">Top Products</h3>
                        <p class="text-xs text-gray-400 mb-4">Store product inventory reference list</p>
                    </div>

                    <div class="space-y-4 h-full flex flex-col justify-center">
                        @forelse($topProducts as $product)
                            <div class="flex justify-between items-center border-b border-gray-50 pb-2 last:border-none">
                                <span
                                    class="text-sm font-medium text-gray-700">{{ $product->product_name ?? ($product->name ?? 'Unnamed Item') }}</span>
                                <span
                                    class="text-sm font-bold text-emerald-600">₱{{ number_format($product->price ?? 0, 2) }}</span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 text-center py-4">No products found.</p>
                        @endforelse
                    </div>
                </div>

            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mt-6">
                <h2 class="text-lg font-black text-gray-900 mb-6">Recent Orders</h2>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b font-bold text-gray-900">Order ID</th>
                            <th class="py-2 px-4 border-b font-bold text-gray-900">Customer</th>
                            <th class="py-2 px-4 border-b font-bold text-gray-900">Total</th>
                            <th class="py-2 px-4 border-b font-bold text-gray-900">Status</th>
                            <th class="py-2 px-4 border-b font-bold text-gray-900">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-2 px-4 border-b">#{{ $order->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $order->customer_name }}</td>
                                <td class="py-2 px-4 border-b">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td class="py-2 px-4 border-b">
                                <td class="py-2 px-4 border-b">
                                    @if(strtolower($order->status) === 'completed')
                                        <span
                                            class="bg-emerald-100 text-emerald-800 text-xs font-black px-3 py-1 rounded-full w-fit block uppercase tracking-wide">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @else
                                        <span
                                            class="bg-amber-100 text-amber-800 text-xs font-black px-3 py-1 rounded-full w-fit block uppercase tracking-wide">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="py-2 px-4 border-b text-sm font-medium text-gray-400">
                                    {{ $order->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-sm font-semibold text-gray-50/50">No recent orders
                                    registered in the system today.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </main>
    </div>
    <script src="https://jsdelivr.net"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('salesChart').getContext('2d');
            new Chart(ctx, {
                type: 'line', data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Weekly Revenue',
                        data: [12000, 19000, 15000, 25000, 22000, 30000, 28450],
                        borderColor: '#065f46',
                        backgroundColor: 'rgba(6, 95, 70, 0.05)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
@endsection