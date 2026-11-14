@extends('layouts.blade-layout')

@section('content')
    <div class="w-full min-h-screen bg-slate-50 p-8 font-sans">

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

        <div class="flex justify-end mb-6">
            <button type="button" onclick="openAddModal()"
                class="bg-[#1b5335] hover:bg-[#143f28] text-white font-bold text-xs px-6 py-2.5 rounded-lg shadow-xs transition">
                +Add User
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-[#e3e5e5] rounded-xl py-6 px-6 text-center">
                <p class="text-xs font-bold text-gray-800 mb-2">Total Users</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ number_format($totalUsers) }}</p>
            </div>

            <div class="bg-[#e3e5e5] rounded-xl py-6 px-6 text-center">
                <p class="text-xs font-bold text-gray-800 mb-2">Admins &amp; Staff</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ number_format($adminsAndStaff) }}</p>
            </div>

            <div class="bg-[#e3e5e5] rounded-xl py-6 px-6 text-center">
                <p class="text-xs font-bold text-gray-800 mb-2">Active Sessions</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ number_format($activeSessions) }}</p>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-3 mb-6">
            <a href="{{ route('admin.index', ['role' => 'all']) }}"
                class="px-5 py-2 rounded-lg text-xs font-bold transition {{ $roleFilter === 'all' ? 'bg-[#1b5335] text-white' : 'bg-[#f3f4f4] border border-gray-300 text-gray-700 hover:bg-gray-200' }}">
                All Users
            </a>

            <a href="{{ route('admin.index', ['role' => 'Admin']) }}"
                class="px-5 py-2 rounded-lg text-xs font-bold transition {{ $roleFilter === 'Admin' ? 'bg-[#1b5335] text-white' : 'bg-[#f3f4f4] border border-gray-300 text-gray-700 hover:bg-gray-200' }}">
                Admin
            </a>

            <a href="{{ route('admin.index', ['role' => 'Staff']) }}"
                class="px-5 py-2 rounded-lg text-xs font-bold transition {{ $roleFilter === 'Staff' ? 'bg-[#1b5335] text-white' : 'bg-[#f3f4f4] border border-gray-300 text-gray-700 hover:bg-gray-200' }}">
                Staff
            </a>

            <a href="{{ route('admin.index', ['role' => 'Customer']) }}"
                class="px-5 py-2 rounded-lg text-xs font-bold transition {{ $roleFilter === 'Customer' ? 'bg-[#1b5335] text-white' : 'bg-[#f3f4f4] border border-gray-300 text-gray-700 hover:bg-gray-200' }}">
                Customers
            </a>
        </div>

        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-xs">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#d9dbdb] text-xs font-extrabold text-gray-900 uppercase">
                            <th class="py-3.5 px-6">NAME</th>
                            <th class="py-3.5 px-6">EMAIL</th>
                            <th class="py-3.5 px-6">ROLE</th>
                            <th class="py-3.5 px-6 text-center">STATUS</th>
                            <th class="py-3.5 px-6 text-center">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 text-xs font-semibold text-gray-800">
                        @forelse($users as $user)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="py-4 px-6 whitespace-nowrap">{{ $user->name }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">{{ $user->email }}</td>
                                <td class="py-4 px-6 whitespace-nowrap">₱{{ number_format($user->spend_amount ?? 0, 0) }}</td>
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    @if($user->role === 'Admin')
                                        <span
                                            class="inline-block w-24 py-1.5 rounded-full text-[11px] font-bold bg-[#b2dec2] text-[#1b5335]">
                                            Admin
                                        </span>
                                    @elseif($user->role === 'Staff')
                                        <span
                                            class="inline-block w-24 py-1.5 rounded-full text-[11px] font-bold bg-[#f9ead2] text-[#8a6d3b]">
                                            Staff
                                        </span>
                                    @else
                                        <span
                                            class="inline-block w-24 py-1.5 rounded-full text-[11px] font-bold bg-[#c8cccc] text-white">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center whitespace-nowrap">
                                    <button type="button"
                                        onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $user->role }}', '{{ $user->spend_amount ?? 0 }}')"
                                        class="text-gray-700 hover:text-[#1b5335] font-bold">
                                        Edit
                                    </button>
                                    <span class="text-gray-400">-</span>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Remove this user?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-700 hover:text-red-600 font-bold">
                                            Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 px-6 text-center text-gray-400">
                                    No users found for this filter. Click +Add User to create one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
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
                        class="bg-[#1b5335] hover:bg-[#143f28] text-white text-xs font-bold px-6 py-2 rounded-xl">
                        Save User
                    </button>
                </div>
            </form>
        </div>
    </div>

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
                        class="bg-[#1b5335] hover:bg-[#143f28] text-white text-xs font-bold px-6 py-2 rounded-xl">
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