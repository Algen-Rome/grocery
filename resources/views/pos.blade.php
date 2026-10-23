@extends('app')

@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <aside class="w-64 border-r border-gray-200 p-6 flex flex-col space-y-2">
            <div class="mb-8 flex items-center space-x-3">
                <div class="bg-emerald-300 rounded-full h-5 w-5"></div>
                Grocery Admin
                <span class="text-lg font-bold text-lg"></span>
            </div>
            <a href="#"
                class="bg-emerald-800 text-white px-4 py-3 rounded-xl font-medium text-sm transition block shadow-sm">Dashboard</a>
            <a href="{{ route('sales.index') }}"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Sales</a>
            <a href="#"
                class="text-gray-500 hover:text-gray-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Reports</a>
            <a href="#"
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
                <input type="text" placeholder="Search"
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
                        ↑ +12.5% <span class="text-gray-400 font-normal ml-1">vs yesterday</span>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orders Today</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">{{ $ordersToday }}</h3>
                    </div>
                    <div class="text-xs font-bold text-emerald-600 mt-2 flex items-center">
                        ↑ +8.2% <span class="text-gray-400 font-normal ml-1">new checkouts</span>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Products</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">{{ number_format($totalProducts) }}</h3>
                    </div>
                    <div class="text-xs font-bold text-amber-500 mt-2 flex items-center">
                        @if ($lowStockCount > 0)
                            ⚠️ {{ $lowStockCount }} {{ str::plural('item', $lowStockCount) }} <span
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
                    <div class="text-xs font-bold text-emerald-600 mt-2 flex items-center">
                        ↑ +4.1% <span class="text-gray-400 font-normal ml-1">active sessions</span>
                    </div>
                </div>

            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="bg-white rounded-2xl lg:col-span-2 p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-black text-gray-900 mb-6">Sales Overview</h2>
                </div>
                <canvas id="salesChart" class="w-full h-48"></canvas>
                <div class="flex flex-col bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-black text-gray-900 mb-6">Top Products</h2>
                    <div class="flex flex-col gap-5">
                        @forelse($topProducts as $item)
                            <h3 class="flex justify-between items-center py-1"><span
                                    class="font-extrabold text-gray-800 text-sm">{{ $item->product_name }}</span><span
                                    class="font-black text-gray-500 text-sm">{{ $item->total_sold ?? 0}}</span></h3>
                        @empty
                            <p class="text-gray-500">No top products found.</p>
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