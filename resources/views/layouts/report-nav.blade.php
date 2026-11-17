@php
    $currentRoute = Route::currentRouteName();
@endphp

<!-- ==================== CENTRALIZED REPORT NAVIGATION LINK BAR ==================== -->
<div class="flex flex-col lg:flex-row justify-between items-stretch lg:items-center gap-4 mb-6 lg:mb-8 w-full">

    <!-- ==================== DYNAMIC CENTRALIZED REPORT NAVIGATION PILLS RIBBON BAR ==================== -->
    <div class="grid grid-cols-2 lg:flex lg:items-center gap-2.5 lg:gap-3 w-full lg:w-auto">

        <!-- Sales Report -->
        <a href="{{ route('reports.index') }}"
            class="px-4 lg:px-5 py-3 lg:py-2.5 font-bold rounded-xl lg:rounded-full text-xs shadow-sm transition text-left lg:text-center no-underline {{ $currentRoute === 'reports.index' ? 'bg-[#165334] lg:bg-emerald-800 text-white' : 'bg-white lg:bg-slate-300 text-slate-700 border border-slate-200 lg:border-0 hover:bg-slate-100 lg:hover:bg-slate-400' }}">
            Sales Report
        </a>

        <!-- Inventory Report -->
        <a href="{{ route('reports.inventory') }}"
            class="px-4 lg:px-5 py-3 lg:py-2.5 font-bold rounded-xl lg:rounded-full text-xs shadow-sm transition text-left lg:text-center no-underline {{ $currentRoute === 'reports.inventory' ? 'bg-[#165334] lg:bg-emerald-800 text-white' : 'bg-white lg:bg-slate-300 text-slate-700 border border-slate-200 lg:border-0 hover:bg-slate-100 lg:hover:bg-slate-400' }}">
            Inventory Report
        </a>

        <!-- Customer Report -->
        <a href="{{ route('reports.customer') }}"
            class="px-4 lg:px-5 py-3 lg:py-2.5 font-bold rounded-xl lg:rounded-full text-xs shadow-sm transition text-left lg:text-center no-underline {{ $currentRoute === 'reports.customer' ? 'bg-[#165334] lg:bg-emerald-800 text-white' : 'bg-white lg:bg-slate-300 text-slate-700 border border-slate-200 lg:border-0 hover:bg-slate-100 lg:hover:bg-slate-400' }}">
            Customer Report
        </a>

        <!-- Financial Report -->
        <a href="{{ route('reports.financial') }}"
            class="px-4 lg:px-5 py-3 lg:py-2.5 font-bold rounded-xl lg:rounded-full text-xs shadow-sm transition text-left lg:text-center no-underline {{ $currentRoute === 'reports.financial' ? 'bg-[#165334] lg:bg-emerald-800 text-white' : 'bg-white lg:bg-slate-300 text-slate-700 border border-slate-200 lg:border-0 hover:bg-slate-100 lg:hover:bg-slate-400' }}">
            Financial Report
        </a>

    </div>

    <!-- Action Date Ribbon & Export PDF Button -->
    <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-3 w-full lg:w-auto">
        <span
            class="text-slate-500 lg:text-slate-700 lg:px-4 lg:py-2.5 lg:bg-slate-200 font-semibold lg:font-bold lg:rounded-lg text-xs lg:shadow-inner">
            Sep 1 – Sep 14, 2026
        </span>

        <a href="{{ route('reports.inventory.pdf') }}"
            class="w-full lg:w-auto px-5 py-3.5 lg:py-2.5 bg-[#165334] lg:bg-emerald-800 hover:bg-emerald-900 text-white font-bold lg:font-black text-xs rounded-xl lg:rounded-lg transition tracking-wide lg:uppercase shadow-sm inline-block text-center no-underline whitespace-nowrap">
            Export PDF
        </a>
    </div>
</div>