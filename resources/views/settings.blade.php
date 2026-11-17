@extends('layouts.blade-layout')

@section('content')
    <div class="w-full min-h-screen bg-slate-50 p-0 lg:p-8 font-sans">

        <!-- Mobile Page Title -->
        <h1 class="lg:hidden text-2xl font-extrabold text-slate-900 mb-4">Settings</h1>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-xs font-bold px-4 py-3 rounded-xl">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-5 lg:gap-8 items-start">

            <!-- Left / Top Navigation Sidebar -->
            <div
                class="w-full lg:w-56 grid grid-cols-2 lg:flex lg:flex-col gap-2.5 lg:gap-0 lg:bg-[#f3f4f4] rounded-2xl lg:p-4 lg:space-y-2 shrink-0">
                <button type="button" onclick="switchTab('store-info')" id="tab-btn-store-info"
                    style="background-color: #165334; color: #ffffff;"
                    class="settings-tab-btn w-full text-left px-4 py-3 lg:py-2.5 rounded-xl text-xs font-bold shadow-sm lg:shadow-none transition cursor-pointer">
                    Store Info
                </button>


                <button type="button" onclick="switchTab('payment')" id="tab-btn-payment"
                    class="settings-tab-btn w-full text-left px-4 py-3 lg:py-2.5 rounded-xl text-xs font-bold bg-white lg:bg-transparent text-slate-700 lg:text-gray-600 border border-slate-200 lg:border-0 hover:bg-gray-100 lg:hover:bg-gray-200/60 transition cursor-pointer">
                    Payment
                </button>

                <button type="button" onclick="switchTab('notifications')" id="tab-btn-notifications"
                    class="settings-tab-btn w-full text-left px-4 py-3 lg:py-2.5 rounded-xl text-xs font-bold bg-white lg:bg-transparent text-slate-700 lg:text-gray-600 border border-slate-200 lg:border-0 hover:bg-gray-100 lg:hover:bg-gray-200/60 transition cursor-pointer">
                    Notifications
                </button>

                <button type="button" onclick="switchTab('security')" id="tab-btn-security"
                    class="settings-tab-btn w-full text-left px-4 py-3 lg:py-2.5 rounded-xl text-xs font-bold bg-white lg:bg-transparent text-slate-700 lg:text-gray-600 border border-slate-200 lg:border-0 hover:bg-gray-100 lg:hover:bg-gray-200/60 transition cursor-pointer">
                    Security
                </button>

                <button type="button" onclick="switchTab('supplier-info')" id="tab-btn-supplier-info"
                    class="settings-tab-btn col-span-2 lg:col-span-1 w-full text-left px-4 py-3 lg:py-2.5 rounded-xl text-xs font-bold bg-white lg:bg-transparent text-slate-700 lg:text-gray-600 border border-slate-200 lg:border-0 hover:bg-gray-100 lg:hover:bg-gray-200/60 transition cursor-pointer">
                    Supplier Info
                </button>
            </div>

            <!-- Right / Main Form Panels -->
            <div class="flex-1 w-full max-w-3xl">

                <form action="{{ route('settings.update') }}" method="POST">
                    @csrf
                    <!-- TAB 1: STORE INFORMATION -->
                    <div id="tab-panel-store-info"
                        class="settings-tab-panel bg-white lg:bg-[#f3f4f4] border border-slate-200 lg:border-gray-300 rounded-2xl p-5 lg:p-8 shadow-sm lg:shadow-none">
                        <h2 class="text-base lg:text-lg font-extrabold text-gray-900 mb-5 lg:mb-6">Store Information</h2>

                        <div class="space-y-4 lg:space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Store Name</label>
                                    <input type="text" name="store_name"
                                        value="{{ old('store_name', $setting->store_name) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Contact Number</label>
                                    <input type="text" name="contact_number"
                                        value="{{ old('contact_number', $setting->contact_number) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-800 mb-2">Store Address</label>
                                <input type="text" name="store_address"
                                    value="{{ old('store_address', $setting->store_address) }}"
                                    class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Currency</label>
                                    <input type="text" name="currency" value="{{ old('currency', $setting->currency) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Timezone</label>
                                    <input type="text" name="timezone" value="{{ old('timezone', $setting->timezone) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6 pt-2">
                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Email Notifications</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="email_notifications" value="1" class="sr-only peer"
                                            @checked($setting->email_notifications)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">SMS Notifications</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="sms_notifications" value="1" class="sr-only peer"
                                            @checked($setting->sms_notifications)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- TAB 2: PAYMENT SETTINGS -->
                    <div id="tab-panel-payment"
                        class="settings-tab-panel hidden bg-white lg:bg-[#f3f4f4] border border-slate-200 lg:border-gray-300 rounded-2xl p-5 lg:p-8 shadow-sm lg:shadow-none">
                        <h2 class="text-base lg:text-lg font-extrabold text-gray-900 mb-5 lg:mb-6">Payment Settings</h2>

                        <div class="mb-7">
                            <h3 class="text-xs font-extrabold text-gray-800 mb-4">Accepted Payment Methods</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-10">
                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Cash on Delivery</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="cod_enabled" value="1" class="sr-only peer"
                                            @checked($setting->cod_enabled)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">GCash/Maya</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="gcash_enabled" value="1" class="sr-only peer"
                                            @checked($setting->gcash_enabled)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Credit / Debit Card</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="card_enabled" value="1" class="sr-only peer"
                                            @checked($setting->card_enabled)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Maya</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="maya_enabled" value="1" class="sr-only peer"
                                            @checked($setting->maya_enabled)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4 lg:space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Payment Gateway</label>
                                    <input type="text" name="payment_gateway"
                                        value="{{ old('payment_gateway', $setting->payment_gateway) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">VAT Rate (%)</label>
                                    <input type="text" name="vat_rate" value="{{ old('vat_rate', $setting->vat_rate) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">GCash Number</label>
                                    <input type="text" name="gcash_number" placeholder="09XX XXX XXXX"
                                        value="{{ old('gcash_number', $setting->gcash_number) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Receipt Footer Message</label>
                                    <input type="text" name="receipt_footer"
                                        value="{{ old('receipt_footer', $setting->receipt_footer) }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- TAB 3: NOTIFICATION PREFERENCES -->
                    <div id="tab-panel-notifications"
                        class="settings-tab-panel hidden bg-white lg:bg-[#f3f4f4] border border-slate-200 lg:border-gray-300 rounded-2xl p-5 lg:p-8 shadow-sm lg:shadow-none">
                        <h2 class="text-base lg:text-lg font-extrabold text-gray-900 mb-5 lg:mb-6">Notification Preferences
                        </h2>

                        <div class="mb-6">
                            <h3 class="text-xs font-extrabold text-gray-800 mb-4">Store Alerts</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-5 gap-x-10">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800">Low Stock Alerts</span>
                                        <span class="block text-[10px] text-gray-400 font-semibold mt-0.5">Alert for every
                                            new order</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="low_stock_alerts" value="1" class="sr-only peer"
                                            @checked($setting->low_stock_alerts)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800">Refund Requests</span>
                                        <span class="block text-[10px] text-gray-400 font-semibold mt-0.5">Alert for every
                                            new order</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="refund_request_alerts" value="1" class="sr-only peer"
                                            @checked($setting->refund_request_alerts)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800">New Order Alerts</span>
                                        <span class="block text-[10px] text-gray-400 font-semibold mt-0.5">Item falls below
                                            reorder point</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="new_order_alerts" value="1" class="sr-only peer"
                                            @checked($setting->new_order_alerts)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800">Daily Sales Summary</span>
                                        <span class="block text-[10px] text-gray-400 font-semibold mt-0.5">Alert for every
                                            new order</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="daily_sales_summary" value="1" class="sr-only peer"
                                            @checked($setting->daily_sales_summary)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800">Payment Received</span>
                                        <span class="block text-[10px] text-gray-400 font-semibold mt-0.5">Confirm
                                            successful payment</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="payment_received_alerts" value="1" class="sr-only peer"
                                            @checked($setting->payment_received_alerts)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800">Weekly Report Email</span>
                                        <span class="block text-[10px] text-gray-400 font-semibold mt-0.5">Alert for every
                                            new order</span>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="weekly_report_email" value="1" class="sr-only peer"
                                            @checked($setting->weekly_report_email)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="pt-5 border-t border-gray-200">
                            <h3 class="text-xs font-extrabold text-gray-800 mb-4">Notify Me Via</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-xs font-bold text-gray-700 whitespace-nowrap">Email</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="notify_via_email" value="1" class="sr-only peer"
                                            @checked($setting->email_notifications)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-xs font-bold text-gray-700 whitespace-nowrap">SMS</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="notify_via_sms" value="1" class="sr-only peer"
                                            @checked($setting->sms_notifications)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-xs font-bold text-gray-700 whitespace-nowrap">In-App</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="in_app_notifications" value="1" class="sr-only peer"
                                            @checked($setting->in_app_notifications)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- TAB 4: SECURITY SETTINGS -->
                    <div id="tab-panel-security"
                        class="settings-tab-panel hidden bg-white lg:bg-[#f3f4f4] border border-slate-200 lg:border-gray-300 rounded-2xl p-5 lg:p-8 shadow-sm lg:shadow-none">
                        <h2 class="text-base lg:text-lg font-extrabold text-gray-900 mb-5 lg:mb-6">Security Settings</h2>

                        <div class="space-y-4 lg:space-y-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Email Address</label>
                                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Current Password</label>
                                    <input type="password" name="current_password" placeholder="••••••••"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">New Password</label>
                                    <input type="password" name="new_password" placeholder="••••••••"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Session Timeout</label>
                                    <input type="text" name="session_timeout"
                                        value="{{ old('session_timeout', $setting->session_timeout ?? '30 minutes') }}"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" placeholder="••••••••"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-10 pt-2">
                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Two-Factor Authentication</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="two_factor_enabled" value="1" class="sr-only peer"
                                            @checked($setting->two_factor_enabled)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Login Alert Emails</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="login_alert_emails" value="1" class="sr-only peer"
                                            @checked($setting->login_alert_emails)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Auto Logout When Idle</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="auto_logout_idle" value="1" class="sr-only peer"
                                            @checked($setting->auto_logout_idle)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>

                                <div class="flex items-center justify-between lg:pr-4">
                                    <span class="text-xs font-bold text-gray-800">Allow Multiple Devices</span>
                                    <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                        <input type="checkbox" name="allow_multiple_devices" value="1" class="sr-only peer"
                                            @checked($setting->allow_multiple_devices)>
                                        <div
                                            class="w-12 h-6 bg-[#d1d5db] rounded-full peer peer-checked:bg-[#1b5335] transition-colors after:content-[''] after:absolute after:top-0.5 after:left-0.5 after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:after:translate-x-3">
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div id="login-history-box"
                                class="hidden mt-6 bg-white border border-gray-300 rounded-xl overflow-hidden">
                                <div
                                    class="px-4 py-3 bg-gray-100 border-b border-gray-300 flex items-center justify-between">
                                    <span class="text-xs font-extrabold text-gray-800">Recent Login Activity</span>
                                    <span class="text-[11px] font-semibold text-gray-500">Last 10 sessions</span>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr
                                                class="border-b border-gray-200 text-[11px] font-extrabold text-gray-600 uppercase">
                                                <th class="py-2.5 px-4">Date & Time</th>
                                                <th class="py-2.5 px-4">IP Address</th>
                                                <th class="py-2.5 px-4">Browser / Device</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100 text-xs font-semibold text-gray-700">
                                            @forelse($loginHistories ?? [] as $history)
                                                <tr>
                                                    <td class="py-2.5 px-4 whitespace-nowrap">
                                                        {{ $history->logged_in_at->format('M d, Y h:i A') }}
                                                    </td>
                                                    <td class="py-2.5 px-4 whitespace-nowrap">
                                                        {{ $history->ip_address ?? '127.0.0.1' }}
                                                    </td>
                                                    <td class="py-2.5 px-4 truncate max-w-xs" title="{{ $history->device }}">
                                                        {{ \Illuminate\Support\Str::limit($history->device, 45) }}
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="py-6 px-4 text-center text-gray-400">
                                                        No login history recorded yet. Log out and sign back in to test.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3 mt-5 lg:mt-6">
                        <div>
                            <button type="button" id="view-login-history-link" onclick="toggleLoginHistory()"
                                class="hidden text-xs font-extrabold text-[#1b5335] hover:underline lg:pl-2 bg-transparent border-0 cursor-pointer">
                                View Login History
                            </button>
                        </div>

                        <button type="submit" id="settings-submit-btn"
                            class="w-full lg:w-auto bg-[#165334] lg:bg-[#1b5335] hover:bg-[#143f28] text-white font-bold text-xs px-8 py-3.5 lg:py-3 rounded-xl shadow-sm transition whitespace-nowrap text-center border-0 cursor-pointer">
                            Save Changes
                        </button>
                    </div>
                </form>
                <!-- TAB 5: SUPPLIER INFORMATION -->
                <div id="tab-panel-supplier-info" class="settings-tab-panel hidden space-y-6">
                    <div
                        class="bg-white lg:bg-[#f3f4f4] border border-slate-200 lg:border-gray-300 rounded-2xl p-5 lg:p-8 shadow-sm lg:shadow-none">
                        <h2 class="text-base lg:text-lg font-extrabold text-gray-900 mb-5 lg:mb-6">Supplier Information</h2>

                        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4 lg:space-y-5">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Supplier Name</label>
                                    <input type="text" name="supplier_name" required
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Contact Number</label>
                                    <input type="text" name="contact_number" required
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-800 mb-2">Email Address</label>
                                <input type="email" name="email"
                                    class="w-full lg:w-5/6 bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Address</label>
                                    <input type="text" name="address"
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-800 mb-2">Product Supplied</label>
                                    <input type="text" name="product_supplied" required
                                        class="w-full bg-slate-100 lg:bg-[#d9dbdb] border border-transparent lg:border-gray-500 rounded-xl px-4 py-3 lg:py-2.5 text-sm font-semibold text-gray-900 focus:outline-none focus:ring-2 focus:ring-[#1b5335]">
                                </div>
                            </div>

                            <div class="flex justify-center pt-3">
                                <button type="submit"
                                    class="w-full lg:w-auto bg-[#165334] lg:bg-[#1b5335] hover:bg-[#143f28] text-white font-bold text-xs px-8 py-3.5 lg:py-3 rounded-xl shadow-sm transition border-0 cursor-pointer">
                                    Save Supplier
                                </button>
                            </div>
                        </form>
                    </div>

                    <div
                        class="lg:bg-white lg:border lg:border-gray-200 rounded-2xl lg:rounded-xl overflow-hidden shadow-xs">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse block lg:table">
                                <thead class="hidden lg:table-header-group">
                                    <tr class="bg-[#d9dbdb] text-xs font-extrabold text-gray-900">
                                        <th class="py-3.5 px-5">Supplier name</th>
                                        <th class="py-3.5 px-5">Contact Number</th>
                                        <th class="py-3.5 px-5">E-mail</th>
                                        <th class="py-3.5 px-5">Address</th>
                                        <th class="py-3.5 px-5">Product Supplied</th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="block lg:table-row-group space-y-3 lg:space-y-0 lg:divide-y lg:divide-gray-200 text-xs font-semibold text-gray-800">
                                    @forelse($suppliers ?? [] as $supplier)
                                        <tr
                                            class="block lg:table-row bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 p-4 lg:p-0 shadow-sm lg:shadow-none hover:bg-gray-50 transition">
                                            <td
                                                class="block lg:table-cell lg:py-4 lg:px-5 font-extrabold lg:font-semibold text-sm lg:text-xs text-slate-900 pb-2 lg:pb-4">
                                                {{ $supplier->supplier_name }}
                                            </td>
                                            <td
                                                class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-5 whitespace-nowrap">
                                                <span
                                                    class="lg:hidden text-[10px] font-bold text-slate-400 uppercase">Contact</span>
                                                <span>{{ $supplier->contact_number }}</span>
                                            </td>
                                            <td class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-5">
                                                <span
                                                    class="lg:hidden text-[10px] font-bold text-slate-400 uppercase">Email</span>
                                                <span>{{ $supplier->email ?? '-' }}</span>
                                            </td>
                                            <td
                                                class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-5 lg:max-w-[180px]">
                                                <span
                                                    class="lg:hidden text-[10px] font-bold text-slate-400 uppercase">Address</span>
                                                <span>{{ $supplier->address ?? '-' }}</span>
                                            </td>
                                            <td class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-5">
                                                <span
                                                    class="lg:hidden text-[10px] font-bold text-slate-400 uppercase">Product</span>
                                                <span>{{ $supplier->product_supplied }}</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr class="block lg:table-row bg-white rounded-2xl border border-slate-200 lg:border-0">
                                            <td colspan="5" class="block lg:table-cell py-8 px-5 text-center text-gray-400">
                                                No suppliers added yet. Fill out the form above and click Save Supplier.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            const panels = document.querySelectorAll('.settings-tab-panel');
            for (let i = 0; i < panels.length; i++) {
                panels[i].classList.add('hidden');
            }

            const buttons = document.querySelectorAll('.settings-tab-btn');
            for (let i = 0; i < buttons.length; i++) {
                buttons[i].style.backgroundColor = '';
                buttons[i].style.color = '';
                buttons[i].classList.remove('bg-[#165334]', 'bg-emerald-900', 'lg:bg-[#cce5d4]', 'text-white', 'lg:text-[#1b5335]', 'shadow-sm');
                buttons[i].classList.add('bg-white', 'lg:bg-transparent', 'text-slate-700', 'lg:text-gray-600', 'border', 'border-slate-200', 'lg:border-0');
            }

            const targetPanel = document.getElementById('tab-panel-' + tabName);
            if (targetPanel) {
                targetPanel.classList.remove('hidden');
            }

            const activeBtn = document.getElementById('tab-btn-' + tabName);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'lg:bg-transparent', 'text-slate-700', 'lg:text-gray-600', 'border', 'border-slate-200', 'lg:border-0');
                activeBtn.classList.add('lg:bg-[#cce5d4]', 'lg:text-[#1b5335]', 'shadow-sm');
                if (window.innerWidth < 1024) {
                    activeBtn.style.backgroundColor = '#165334';
                    activeBtn.style.color = '#ffffff';
                }
            }



            const historyLink = document.getElementById('view-login-history-link');
            const submitBtn = document.getElementById('settings-submit-btn');

            if (tabName === 'supplier-info') {
                if (historyLink) historyLink.classList.add('hidden');
                if (submitBtn) submitBtn.classList.add('hidden');
            } else if (tabName === 'security') {
                if (historyLink) historyLink.classList.remove('hidden');
                if (submitBtn) {
                    submitBtn.classList.remove('hidden');
                    submitBtn.textContent = 'Update Security';
                }
            } else {
                if (historyLink) historyLink.classList.add('hidden');
                if (submitBtn) {
                    submitBtn.classList.remove('hidden');
                    submitBtn.textContent = 'Save Changes';
                }
            }
        }

        function toggleLoginHistory() {
            const box = document.getElementById('login-history-box');
            if (box) {
                box.classList.toggle('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);
            const activeTab = params.get('tab');
            if (activeTab) {
                switchTab(activeTab);
            }
        });
    </script>
@endsection