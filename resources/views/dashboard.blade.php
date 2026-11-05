@extends('layouts.blade-layout')

@section('content')
    <!-- KPI Summary Cards Grid Row Block -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sales</span>
            <h3 class="text-2xl font-black text-slate-800 mt-2">₱{{ number_format($totalSales ?? 0, 2) }}</h3>
            <p class="text-[11px] {{ $salesDiffColor ?? 'text-slate-400' }} font-bold mt-1">
                {{ $salesDiffText ?? 'No revenue changes' }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Orders Today</span>
            <h3 class="text-2xl font-black text-slate-800 mt-2">{{ $ordersToday ?? 0 }}</h3>
            <p class="text-[11px] text-rose-500 font-bold mt-1">{{ $ordersTrend ?? 'No checkouts' }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Products</span>
            <h3 class="text-2xl font-black text-slate-800 mt-2">{{ $totalProducts ?? 0 }}</h3>
            <p class="text-[11px] text-amber-500 font-bold mt-1">{{ $stockStatus ?? 'All in stock' }}</p>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col justify-between">
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Active Customers</span>
            <h3 class="text-2xl font-black text-slate-800 mt-2">{{ $activeCustomers ?? 0 }}</h3>
            <p class="text-[11px] text-rose-500 font-bold mt-1">{{ $customerTrend ?? '0 active sessions' }}</p>
        </div>
    </div>

    <!-- Analytics Graphs and Rankings row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Overview Chart -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-wide mb-1">Sales Overview</h2>
            <p class="text-xs text-slate-400 font-medium mb-6">Completed revenue totals for the last 7 days</p>

            <div class="flex items-end justify-between h-48 pt-4 px-2 bg-slate-50 rounded-xl border border-slate-100/50">
                @foreach($chartData ?? [] as $data)
                    <div class="flex flex-col items-center flex-1 group">
                        <div class="w-8 bg-emerald-500 hover:bg-emerald-600 rounded-t transition-all duration-300 relative"
                            style="height: {{ $data['height'] }}%">
                            <span
                                class="absolute -top-7 left-1/2 transform -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-1.5 rounded opacity-0 group-hover:opacity-100 transition whitespace-nowrap z-10 font-bold">₱{{ number_format($data['sales']) }}</span>
                        </div>
                        <span
                            class="text-[10px] text-slate-400 font-bold mt-3 uppercase tracking-wider">{{ $data['day_name'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Top Products Leaderboard -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <h2 class="text-sm font-black text-slate-800 uppercase tracking-wide mb-1">Top Products</h2>
            <p class="text-xs text-slate-400 font-medium mb-4">Store product inventory reference list</p>

            <div class="space-y-3">
                @forelse($topProducts ?? [] as $prod)
                    <div
                        class="flex justify-between items-center p-2.5 hover:bg-slate-50 rounded-xl transition border border-transparent hover:border-slate-100">
                        <span class="text-xs font-bold text-slate-700">{{ $prod->name }}</span>
                        <span class="text-xs font-black text-slate-900">₱{{ number_format($prod->price, 2) }}</span>
                    </div>
                @empty
                    <p class="text-xs font-bold text-slate-400 text-center py-12">No products found.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Activity Orders Logs -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <h2 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-4">Recent Orders</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs font-semibold text-slate-600">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4">Order ID</th>
                        <th class="py-3 px-4">Customer</th>
                        <th class="py-3 px-4">Total</th>
                        <th class="py-3 px-4">Status</th>
                        <th class="py-3 px-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders ?? [] as $order)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition">
                            <td class="py-4 px-4 font-bold text-slate-900">#{{ $order->id }}</td>
                            <td class="py-4 px-4 text-slate-500 font-medium">{{ $order->customer_name }}</td>
                            <td class="py-4 px-4 font-bold text-slate-900">₱{{ number_format($order->total_amount, 2) }}</td>
                            <td class="py-4 px-4">
                                <span
                                    class="px-2.5 py-1 rounded-full font-bold uppercase text-[9px] tracking-wide {{ $order->status === 'Completed' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-slate-400 font-medium">
                                {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 font-bold">No recent checkout logs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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