@extends('app')

@section('content')
    <div class="flex min-h-screen bg-slate-50">
        <aside class="w-64 bg-gradient-to-r from-red-500 via-yellow-500 to-violet-500 border-r border-gray-200 p-6 flex flex-col space-y-2">
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
                <button type="submit" class="w-full text-left text-red-500 hover:bg-red-50 font-medium px-4 py-3 rounded-xl text-sm block transition">Logout</button>
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
        </main>
    </div>
@endsection