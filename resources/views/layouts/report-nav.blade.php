@php
    $currentRoute = Route::currentRouteName();
@endphp

<!-- ==================== CENTRALIZED REPORT NAVIGATION LINK BAR ==================== -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 w-full">
    <!-- ==================== DYNAMIC CENTRALIZED REPORT NAVIGATION PILLS RIBBON BAR ==================== -->
    <div class="flex items-center space-x-3 w-full">

        <!-- Sales Report (Muted / Unselected Style Example) -->
        <a href="{{ route('reports.index') }}"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition {{ Route::currentRouteName() === 'reports.index' ? 'bg-emerald-800 text-white' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' }}">
            Sales Report
        </a>

        <!-- Inventory Report (Muted / Unselected Base Style) -->
        <a href="{{ route('reports.inventory') }}"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition {{ Route::currentRouteName() === 'reports.inventory' ? 'bg-emerald-800 text-white' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' }}">
            Inventory Report
        </a>

        <!-- Customer Report (Active / Selected High-Contrast Dark Green Style) -->
        <a href="{{ route('reports.customer') }}"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition {{ Route::currentRouteName() === 'reports.customer' ? 'bg-emerald-800 text-white' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' }}">
            Customer Report
        </a>

        <!-- Financial Report (UPDATED: Restructured wrapper classes to exactly match unselected pills) -->
        <a href="{{ route('reports.financial') }}"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition {{ Route::currentRouteName() === 'reports.financial' ? 'bg-emerald-800 text-white' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' }}">
            Financial Report
        </a>

    </div>


    <!-- Action Date Ribbon Layout Summary Section Container -->
    <!-- 📊 Find this block in layouts/report-nav.blade.php and change your button to an active anchor link route structure: -->
    <div class="flex items-center gap-3 self-end sm:self-auto">
        <span class="px-4 py-2.5 bg-slate-200 text-slate-700 font-bold rounded-lg text-xs shadow-inner">Sep 1 - Sep 14,
            2026</span>

        <!-- 🌟 LINK FIXED: Triggers the backend export controller generation engine script on click! -->
        <a href="{{ route('reports.inventory.pdf') }}"
            class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-black text-xs rounded-lg transition tracking-wide uppercase shadow-sm inline-block text-center no-underline">
            Export PDF
        </a>
    </div>
</div>