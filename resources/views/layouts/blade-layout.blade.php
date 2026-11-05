<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Grocery Admin Dashboard</title>
    <!-- Include your Tailwind CSS or stylesheets here -->
    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://tailwindcss.com"></script>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased">

    <div class="flex min-h-screen">

        <!-- 🌟 RESTORED SIDEBAR PANEL: Stays perfectly anchored on the left margin across every single page -->
        <aside class="w-64 border-r border-slate-200 p-6 flex flex-col space-y-2 bg-white fixed h-full z-40">
            <div class="mb-8 flex items-center space-x-3">
                <div class="bg-emerald-600 rounded-full h-5 w-5"></div>
                <span class="text-lg font-black text-slate-900 tracking-wide">Grocery Admin</span>
            </div>

            <a href="{{ route('dashboard') }}"
                class="hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block {{ Request::is('dashboard') ? 'text-emerald-700 bg-emerald-50/60 font-bold' : 'text-slate-500' }}">Dashboard</a>

            <a href="{{ route('sales.index') }}"
                class="hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block {{ Request::is('sales*') ? 'text-emerald-700 bg-emerald-50/60 font-bold' : 'text-slate-500' }}">Sales</a>

            <a href="{{ route('products.index') }}"
                class="hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block {{ Request::is('products*') ? 'text-emerald-700 bg-emerald-50/60 font-bold' : 'text-slate-500' }}">Products</a>

            <a href="{{ route('reports.index') }}"
                class="hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block {{ Request::is('reports*') ? 'text-emerald-700 bg-emerald-50/60 font-bold' : 'text-slate-500' }}">Reports</a>

            <a href="#"
                class="text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Settings</a>
            <a href="#"
                class="text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Admin</a>
            <a href="#"
                class="text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Online
                Orders</a>
            <a href="#"
                class="text-slate-500 hover:text-slate-900 hover:bg-slate-100 px-4 py-3 rounded-xl font-medium text-sm transition block">Stock
                Alerts</a>

            <!-- Logout System Button Action Block -->
            <form action="{{ route('logout') }}" method="POST" class="w-full mt-auto mb-0">
                @csrf
                <button type="submit"
                    class="w-full text-left text-red-500 hover:bg-red-50 font-bold px-4 py-3 rounded-xl text-sm block transition border-0 cursor-pointer">
                    Log Out
                </button>
            </form>
        </aside>

        <!-- 🌟 CANVAS CONTENT CONTAINER: Shifted right by 16rem (ml-64) to leave room for the fixed sidebar -->
        <div class="flex-1 flex flex-col ml-64 min-h-screen">

            <!-- MASTER SHARED HEADER RIBBON VIEW COMPONENT -->
            <header
                class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center sticky top-0 z-30">
                <input type="text" placeholder="Search..."
                    class="rounded-full bg-slate-100 border border-transparent px-6 py-2.5 w-80 text-sm font-semibold focus:outline-none focus:bg-white focus:border-emerald-500 transition shadow-inner placeholder-slate-400" />
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 bg-slate-300 rounded-full border border-slate-100"></div>
                    <span class="text-sm font-bold text-slate-700">Ana R.</span>
                </div>
            </header>

            <!-- DYNAMIC TAB CANVAS SLOT -->
            <main class="p-8 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

</body>

</html>