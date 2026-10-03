@extends('app')

@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <aside
            class="w-64 bg-gradient-to-r from-red-500 via-yellow-500 to-violet-500 border-r border-gray-200 p-6 flex flex-col space-y-2">
            <div class="mb-8 flex items-center space-x-3">
                <div class="bg-emerald-300 rounded-full h-5 w-5"></div>
                Grocery Admin
                <span class="text-lg font-bold text-lg"></span>
            </div>
            <a href="#"
                class="bg-emerald-800 text-white px-4 py-3 rounded-xl font-medium text-sm transition block shadow-sm">Dashboard</a>
            <a href="#"
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
                        <h3 class="text-3xl font-black text-gray-900 mt-2">₱128,450</h3>
                    </div>
                    <div class="text-xs font-bold text-emerald-600 mt-2 flex items-center">
                        ↑ +12.5% <span class="text-gray-400 font-normal ml-1">vs yesterday</span>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Orders Today</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">342</h3>
                    </div>
                    <div class="text-xs font-bold text-emerald-600 mt-2 flex items-center">
                        ↑ +8.2% <span class="text-gray-400 font-normal ml-1">new checkouts</span>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Products</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">1,205</h3>
                    </div>
                    <div class="text-xs font-bold text-amber-500 mt-2 flex items-center">
                        ⚠️ 12 Items <span class="text-gray-400 font-normal ml-1">low stock alerts</span>
                    </div>
                </div>
                <div
                    class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between transition hover:shadow-md h-32">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Active Customers</p>
                        <h3 class="text-3xl font-black text-gray-900 mt-2">89</h3>
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
                <div class="flex flex-col bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-lg font-black text-gray-900 mb-6">Top Products</h2>
                    <div class="flex flex-col gap-5">
                        <h3 class="flex justify-between items-center py-1"><span class="font-extrabold text-gray-800 text-sm">banana</span><span class="font-black text-gray-500 text-sm">59</span></h3>
                        <h3 class="flex justify-between items-center py-1"><span class="font-extrabold text-gray-800 text-sm">tomatoes</span><span class="font-black text-gray-500 text-sm">34</span></h3>
                        <h3 class="flex justify-between items-center py-1"><span class="font-extrabold text-gray-800 text-sm">freshmilk</span><span class="font-black text-gray-500 text-sm">28</span></h3>
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
                        <tr>
                            <td class="py-2 px-4 border-b">order_id</td>
                            <td class="py-2 px-4 border-b">customer_name</td>
                            <td class="py-2 px-4 border-b">total</td>
                            <td class="py-2 px-4 border-b"><span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full w-fit block ">Completed</span></td>
                            <td class="py-2 px-4 border-b">date</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
@endsection