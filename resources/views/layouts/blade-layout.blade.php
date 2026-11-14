<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Grocery Admin Dashboard</title>
    @if(app()->environment('local'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-50 min-h-screen text-slate-800 antialiased">

    <div class="flex min-h-screen">

        <!-- SIDEBAR PANEL -->
        <aside class="w-64 border-r border-slate-200 p-6 flex flex-col space-y-2 bg-white fixed h-full z-40">
            <div class="mb-8 flex items-center space-x-3">
                <div class="bg-emerald-600 rounded-full h-5 w-5"></div>
                <span class="text-lg font-black text-slate-900 tracking-wide">Grocery Admin</span>
            </div>

            <a href="{{ route('dashboard') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('dashboard*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Dashboard
            </a>

            <a href="{{ route('sales.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('sales*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Sales
            </a>

            <a href="{{ route('products.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('products*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Products
            </a>

            <a href="{{ route('reports.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('reports*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Reports
            </a>

            <a href="{{ route('settings.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('settings*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Settings
            </a>

            <a href="{{ route('admin.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('admin*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Admin
            </a>

            <a href="{{ route('online-orders.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('online-orders*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Online Orders
            </a>

            <a href="{{ route('stock-alert.index') }}"
                class="px-4 py-3 rounded-xl text-sm transition block {{ Request::is('stock-alert*') ? 'bg-emerald-900 text-white font-bold' : 'text-slate-500 font-medium hover:bg-emerald-900 hover:text-white' }}">
                Stock Alert
            </a>

            <!-- Logout System Button -->
            <form action="{{ route('logout') }}" method="POST" class="w-full mt-auto mb-0">
                @csrf
                <button type="submit"
                    class="w-full text-left text-red-500 hover:bg-red-50 font-bold px-4 py-3 rounded-xl text-sm block transition border-0 cursor-pointer">
                    Log Out
                </button>
            </form>
        </aside>

        <!-- CANVAS CONTENT CONTAINER -->
        <div class="flex-1 flex flex-col ml-64 min-h-screen">

            <!-- HEADER RIBBON -->
            <header
                class="bg-white border-b border-slate-100 px-8 py-5 flex justify-between items-center sticky top-0 z-30">
                <input type="text" placeholder="Search..."
                    class="rounded-full bg-slate-100 border border-transparent px-6 py-2.5 w-80 text-sm font-semibold focus:outline-none focus:bg-white focus:border-emerald-500 transition shadow-inner placeholder-slate-400" />

                @auth
                    @php
                        $user = auth()->user();
                        $avatarPath = $user->profile_photo_path
                            ?? $user->avatar
                            ?? $user->image
                            ?? $user->profile_image
                            ?? null;
                        $avatarUrl = $avatarPath
                            ? (str_starts_with($avatarPath, 'http') ? $avatarPath : asset('storage/' . ltrim($avatarPath, '/')))
                            : null;
                    @endphp

                    <button type="button" onclick="openProfileModal()"
                        class="flex items-center gap-3 hover:bg-slate-100 px-3 py-1.5 rounded-full transition cursor-pointer">
                        @if($avatarUrl)
                            <img src="{{ $avatarUrl }}" alt="{{ $user->name }}"
                                class="w-12 h-12 rounded-full object-cover border border-gray-300 shadow-sm">
                        @else
                            <div
                                class="w-12 h-12 rounded-full bg-[#1b5335] text-white flex items-center justify-center text-lg font-bold uppercase shadow-sm">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif

                        <span class="text-xs font-semibold text-gray-800">
                            {{ $user->name }}
                        </span>
                    </button>
                @endauth
            </header>

            <!-- DYNAMIC TAB CANVAS SLOT -->
            <main class="p-8 flex-1">
                @yield('content')
            </main>
        </div>
    </div>

    @auth
        <!-- Profile Photo & Name Modal -->
        <div id="profile-modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50 p-4">
            <div class="bg-white rounded-3xl max-w-md w-full p-6 space-y-5 shadow-xl">
                <div class="flex justify-between items-center border-b border-gray-200 pb-3">
                    <h3 class="text-sm font-bold text-gray-900">Edit Profile</h3>
                    <button type="button" onclick="closeProfileModal()"
                        class="text-gray-500 hover:text-black font-bold">✕</button>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div class="flex items-center gap-4">
                        <img id="profile-preview-img" src="{{ $avatarUrl ?? '' }}" alt="Preview"
                            class="w-16 h-16 rounded-full object-cover border border-gray-300 {{ $avatarUrl ? '' : 'hidden' }}">

                        <div id="profile-preview-initial"
                            class="w-16 h-16 rounded-full bg-[#1b5335] text-white flex items-center justify-center text-lg font-bold uppercase {{ $avatarUrl ? 'hidden' : '' }}">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-800 mb-1">Change Photo</label>
                            <input type="file" name="photo" accept="image/*" onchange="previewProfilePhoto(event)"
                                class="text-xs text-gray-700 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#1b5335] file:text-white hover:file:bg-[#143f28]">
                            <p class="text-[10px] text-gray-500 mt-1">JPG, PNG, or WEBP (Max 2MB)</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-800 mb-1">Display Name</label>
                        <input type="text" name="name" value="{{ auth()->user()->name }}" required
                            class="w-full border border-gray-300 rounded-xl px-3.5 py-2 text-xs text-gray-900 focus:outline-none focus:border-emerald-700">
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" onclick="closeProfileModal()"
                            class="px-4 py-2 rounded-xl text-xs font-bold text-gray-700 bg-gray-200 hover:bg-gray-300">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-5 py-2 rounded-xl text-xs font-bold text-white bg-[#1b5335] hover:bg-[#143f28]">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            function openProfileModal() {
                const modal = document.getElementById('profile-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            function closeProfileModal() {
                const modal = document.getElementById('profile-modal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            function previewProfilePhoto(event) {
                const file = event.target.files[0];
                if (!file) return;

                const img = document.getElementById('profile-preview-img');
                const initial = document.getElementById('profile-preview-initial');

                img.src = URL.createObjectURL(file);
                img.classList.remove('hidden');
                if (initial) initial.classList.add('hidden');
            }
        </script>
    @endauth


</body>

</html>