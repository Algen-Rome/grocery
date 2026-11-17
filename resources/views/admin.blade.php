@extends('layouts.blade-layout')

@section('content')
    <div class="w-full min-h-screen bg-slate-50 p-0 lg:p-8 font-sans">

        <!-- Mobile Page Title -->
        <h1 class="lg:hidden text-2xl font-extrabold text-slate-900 mb-4">Admin</h1>

        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs font-bold px-4 py-3 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 text-xs font-bold px-4 py-3 rounded-xl">
                {{ $errors->first() }}
            </div>
        @endif

        <!-- Top Action Button (Full-Width on Mobile, Right-Aligned on Desktop) -->
        <div class="flex justify-end mb-5 lg:mb-6">
            <button type="button" onclick="openAddModal()"
                style="background-color: #165334; color: #ffffff;"
                class="w-full lg:w-auto hover:opacity-95 font-bold text-xs px-6 py-3.5 lg:py-2.5 rounded-xl lg:rounded-lg shadow-xs transition text-center cursor-pointer">
                +Add User
            </button>
        </div>

        <!-- KPI Summary Card (Single Card with Rows on Mobile, 3 Cards on Desktop) -->
        <div class="bg-white lg:bg-transparent rounded-2xl border border-slate-200 lg:border-0 p-5 lg:p-0 shadow-sm lg:shadow-none grid grid-cols-1 lg:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <div class="flex lg:block justify-between items-center border-b border-slate-100 lg:border-0 pb-3.5 lg:pb-6 lg:bg-[#e3e5e5] lg:rounded-xl lg:py-6 lg:px-6 lg:text-center">
                <p class="text-xs font-semibold lg:font-bold text-slate-500 lg:text-gray-800 lg:mb-2">Total Users</p>
                <p class="text-sm lg:text-2xl font-extrabold text-slate-900 lg:text-gray-900">{{ number_format($totalUsers) }}</p>
            </div>

            <div class="flex lg:block justify-between items-center border-b border-slate-100 lg:border-0 pb-3.5 lg:pb-6 lg:bg-[#e3e5e5] lg:rounded-xl lg:py-6 lg:px-6 lg:text-center">
                <p class="text-xs font-semibold lg:font-bold text-slate-500 lg:text-gray-800 lg:mb-2">Admins &amp; Staff</p>
                <p class="text-sm lg:text-2xl font-extrabold text-slate-900 lg:text-gray-900">{{ number_format($adminsAndStaff) }}</p>
            </div>

            <div class="flex lg:block justify-between items-center lg:bg-[#e3e5e5] lg:rounded-xl lg:py-6 lg:px-6 lg:text-center">
                <p class="text-xs font-semibold lg:font-bold text-slate-500 lg:text-gray-800 lg:mb-2">Active Sessions</p>
                <p class="text-sm lg:text-2xl font-extrabold text-slate-900 lg:text-gray-900">{{ number_format($activeSessions) }}</p>
            </div>
        </div>
        <!-- Filter Tabs (Clean Inline Links on Mobile, Pill Buttons on Desktop) -->
        <div class="flex flex-wrap items-center justify-between lg:justify-start gap-2 lg:gap-3 mb-4 lg:mb-6 px-1 lg:px-0">
            <a href="{{ route('admin.index', ['role' => 'all']) }}"
                class="px-2 py-1 lg:px-5 lg:py-2 rounded-lg text-xs transition {{ $roleFilter === 'all' ? 'font-extrabold text-slate-900 lg:bg-[#1b5335] lg:text-white' : 'font-semibold lg:font-bold text-slate-500 lg:bg-[#f3f4f4] lg:border lg:border-gray-300 lg:text-gray-700 lg:hover:bg-gray-200' }}">
                All Users
            </a>

            <a href="{{ route('admin.index', ['role' => 'Admin']) }}"
                class="px-2 py-1 lg:px-5 lg:py-2 rounded-lg text-xs transition {{ $roleFilter === 'Admin' ? 'font-extrabold text-slate-900 lg:bg-[#1b5335] lg:text-white' : 'font-semibold lg:font-bold text-slate-500 lg:bg-[#f3f4f4] lg:border lg:border-gray-300 lg:text-gray-700 lg:hover:bg-gray-200' }}">
                Admin
            </a>

            <a href="{{ route('admin.index', ['role' => 'Staff']) }}"
                class="px-2 py-1 lg:px-5 lg:py-2 rounded-lg text-xs transition {{ $roleFilter === 'Staff' ? 'font-extrabold text-slate-900 lg:bg-[#1b5335] lg:text-white' : 'font-semibold lg:font-bold text-slate-500 lg:bg-[#f3f4f4] lg:border lg:border-gray-300 lg:text-gray-700 lg:hover:bg-gray-200' }}">
                Staff
            </a>

            <a href="{{ route('admin.index', ['role' => 'Customer']) }}"
                class="px-2 py-1 lg:px-5 lg:py-2 rounded-lg text-xs transition {{ $roleFilter === 'Customer' ? 'font-extrabold text-slate-900 lg:bg-[#1b5335] lg:text-white' : 'font-semibold lg:font-bold text-slate-500 lg:bg-[#f3f4f4] lg:border lg:border-gray-300 lg:text-gray-700 lg:hover:bg-gray-200' }}">
                Customers
            </a>
        </div>
        <!-- Users List (Stacked Cards on Mobile, Data Table on Desktop) -->
        <div class="lg:bg-white lg:border lg:border-gray-200 rounded-2xl lg:rounded-xl overflow-hidden lg:shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse block lg:table">
                    <thead class="hidden lg:table-header-group">
                        <tr class="bg-[#d9dbdb] text-xs font-extrabold text-gray-900 uppercase">
                            <th class="py-3.5 px-6">NAME</th>
                            <th class="py-3.5 px-6">EMAIL</th>
                            <th class="py-3.5 px-6">ROLE</th>
                            <th class="py-3.5 px-6 text-center">STATUS</th>
                            <th class="py-3.5 px-6 text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="block lg:table-row-group space-y-3 lg:space-y-0 lg:divide-y lg:divide-gray-200 text-xs font-semibold text-gray-800">
                        @forelse($users as $user)
                            <tr class="block lg:table-row bg-white lg:bg-transparent rounded-2xl lg:rounded-none border border-slate-200 lg:border-0 p-4 lg:p-0 shadow-sm lg:shadow-none hover:bg-gray-50 transition">
                                <td class="block lg:table-cell lg:py-4 lg:px-6 font-extrabold lg:font-semibold text-sm lg:text-xs text-slate-900 whitespace-nowrap">
                                    {{ $user->name }}
                                </td>
                                <td class="block lg:table-cell pt-0.5 pb-3 lg:py-4 lg:px-6 text-[11px] lg:text-xs text-slate-500 lg:text-gray-800 whitespace-nowrap">
                                    {{ $user->email }}
                                </td>
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-6 whitespace-nowrap">
                                    <span class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">ROLE</span>
                                    <span class="font-bold lg:font-semibold text-slate-900 lg:text-gray-800">₱{{ number_format($user->spend_amount ?? 0, 0) }}</span>
                                </td>
                                <td class="flex justify-between items-center lg:table-cell py-1 lg:py-4 lg:px-6 lg:text-center whitespace-nowrap">
                                    <span class="lg:hidden text-[10px] font-bold text-slate-400 uppercase tracking-wider">STATUS</span>
                                    @if($user->role === 'Admin')
                                        <span class="font-extrabold lg:font-bold text-slate-900 lg:inline-block lg:w-24 lg:py-1.5 lg:rounded-full lg:text-[11px] lg:bg-[#b2dec2] lg:text-[#1b5335]">
                                            Admin
                                        </span>
                                    @elseif($user->role === 'Staff')
                                        <span class="font-extrabold lg:font-bold text-slate-900 lg:inline-block lg:w-24 lg:py-1.5 lg:rounded-full lg:text-[11px] lg:bg-[#f9ead2] lg:text-[#8a6d3b]">
                                            Staff
                                        </span>
                                    @else
                                        <span class="font-extrabold lg:font-bold text-slate-900 lg:inline-block lg:w-24 lg:py-1.5 lg:rounded-full lg:text-[11px] lg:bg-[#c8cccc] lg:text-white">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="block lg:table-cell pt-2.5 lg:py-4 lg:px-6 text-right lg:text-center whitespace-nowrap">
                                    <button type="button"
                                        onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', '{{ $user->spend_amount ?? 0 }}')"
                                        class="text-[11px] lg:text-xs text-slate-500 lg:text-gray-700 hover:text-[#1b5335] font-bold cursor-pointer">
                                        Edit
                                    </button>
                                    <span class="text-slate-400 lg:text-gray-400 text-[11px] lg:text-xs">&middot;</span>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Remove this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-[11px] lg:text-xs text-slate-500 lg:text-gray-700 hover:text-red-600 font-bold cursor-pointer">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr class="block lg:table-row bg-white rounded-2xl border border-slate-200 lg:border-0">
                                <td colspan="5" class="block lg:table-cell py-8 px-6 text-center text-gray-400">
                                    No users found for this filter. Click +Add User to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
    <!-- Add New User Modal -->
    <div id="add-user-modal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-extrabold text-gray-900">Add New User</h3>
                <button type="button" onclick="closeAddModal()"
                    class="text-gray-400 hover:text-gray-700 font-bold text-lg">&times;</button>
            </div>

            <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">Full Name</label>
                    <input type="text" name="name" required
                        class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">Email Address</label>
                    <input type="email" name="email" required
                        class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1">Role / Status</label>
                        <select name="role"
                            class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                            <option value="Customer">Customer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1">Amount (₱)</label>
                        <input type="number" step="0.01" name="spend_amount" value="0"
                            class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">Password</label>
                    <input type="password" name="password" required minlength="6"
                        class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-xl">
                        Cancel
                    </button>
                    <button type="submit"
                        style="background-color: #165334; color: #ffffff;"
                        class="hover:opacity-95 text-xs font-bold px-6 py-2 rounded-xl">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit User Modal -->
    <div id="edit-user-modal" class="hidden fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-xl">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-extrabold text-gray-900">Edit User</h3>
                <button type="button" onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-700 font-bold text-lg">&times;</button>
            </div>

            <form id="edit-user-form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">Full Name</label>
                    <input type="text" id="edit-name" name="name" required
                        class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-800 mb-1">Email Address</label>
                    <input type="email" id="edit-email" name="email" required
                        class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1">Role / Status</label>
                        <select id="edit-role" name="role"
                            class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                            <option value="Admin">Admin</option>
                            <option value="Staff">Staff</option>
                            <option value="Customer">Customer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1">Amount (₱)</label>
                        <input type="number" step="0.01" id="edit-spend" name="spend_amount"
                            class="w-full bg-[#e3e5e5] border border-gray-400 rounded-xl px-3.5 py-2 text-sm font-semibold text-gray-900">
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-gray-100 rounded-xl">
                        Cancel
                    </button>
                    <button type="submit"
                        style="background-color: #165334; color: #ffffff;"
                        class="hover:opacity-95 text-xs font-bold px-6 py-2 rounded-xl">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function openAddModal() {
            document.getElementById('add-user-modal').classList.remove('hidden');
        }

        function closeAddModal() {
            document.getElementById('add-user-modal').classList.add('hidden');
        }

        function openEditModal(id, name, email, role, spend) {
            document.getElementById('edit-user-form').action = '/admin/users/' + id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role').value = role;
            document.getElementById('edit-spend').value = spend;
            document.getElementById('edit-user-modal').classList.remove('hidden');
        }

        function closeEditModal() {
            document.getElementById('edit-user-modal').classList.add('hidden');
        }
    </script>
@endsection