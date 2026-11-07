@php
    $currentRoute = Route::currentRouteName();
@endphp

<!-- ==================== CENTRALIZED REPORT NAVIGATION LINK BAR ==================== -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 w-full">
    <!-- Sub-Report Dynamic Tab Switcher Grid Matrix Layout -->
    <div class="flex flex-wrap gap-2.5">

        <!-- 🌟 FIXED: Point this cleanly to your reports handler route index instead of sales index! -->
        <a href="{{ route('reports.index') }}"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition {{ Route::currentRouteName() === 'reports.index' ? 'bg-emerald-800 text-white' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' }}">
            Sales Report
        </a>

        <a href="{{ route('reports.inventory') }}"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition {{ Route::currentRouteName() === 'reports.inventory' ? 'bg-emerald-800 text-white' : 'bg-slate-300 text-slate-700 hover:bg-slate-400' }}">
            Inventory Report
        </a>

        <a href="#"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition bg-slate-300 text-slate-700 hover:bg-slate-400">
            Customer Report
        </a>

        <a href="#"
            class="px-5 py-2.5 font-bold rounded-full text-xs shadow-sm transition bg-slate-300 text-slate-700 hover:bg-slate-400">
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