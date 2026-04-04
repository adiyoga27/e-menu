<!-- Sidebar Overlay (Mobile) -->
<div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-20 transition-opacity bg-black bg-opacity-50 md:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar component -->
<div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl transition-transform duration-300 transform md:translate-x-0 md:static md:inset-auto md:w-64 flex flex-col">
    <!-- Logo -->
    <div class="flex items-center justify-center h-20 border-b border-gray-100">
        <a href="{{ route('dashboard') }}" class="flex items-center">
            <x-application-logo class="block h-10 w-auto fill-current text-indigo-600" />
            <span class="ml-2 font-bold text-xl text-gray-800">Admin Panel</span>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        @php
            $linkClass = "flex items-center px-4 py-3 text-sm font-medium rounded-lg transition-colors";
            $activeClass = "bg-indigo-50 text-indigo-700";
            $inactiveClass = "text-gray-600 hover:bg-gray-100 hover:text-gray-900";
        @endphp

        <a href="{{ route('dashboard') }}" class="{{ $linkClass }} {{ request()->routeIs('dashboard') ? $activeClass : $inactiveClass }}">
            {{ __('Dashboard') }}
        </a>
        <a href="{{ route('admin.categories.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.categories.*') ? $activeClass : $inactiveClass }}">
            Kategori
        </a>
        <a href="{{ route('admin.menus.index') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.menus.*') ? $activeClass : $inactiveClass }}">
            Menu
        </a>
        <a href="{{ route('admin.orders.index') }}" class="{{ $linkClass }} {{ (request()->routeIs('admin.orders.index') || request()->routeIs('admin.orders.show')) ? $activeClass : $inactiveClass }}">
            Pesanan
        </a>
        <a href="{{ route('admin.orders.report') }}" class="{{ $linkClass }} {{ request()->routeIs('admin.orders.report') ? $activeClass : $inactiveClass }}">
            Laporan
        </a>
    </nav>

    <!-- Profile & Settings -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex flex-col bg-gray-50 rounded-xl p-4">
            <div class="font-bold text-sm text-gray-800 truncate">{{ Auth::user()->name }}</div>
            <div class="text-xs text-gray-500 mt-1 truncate">{{ Auth::user()->email }}</div>
            
            <hr class="my-3 border-gray-200">
            
            <a href="{{ route('profile.edit') }}" class="text-sm py-1.5 text-gray-600 hover:text-indigo-600 transition-colors">
                {{ __('Profile') }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="w-full mt-1">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="text-sm py-1.5 text-red-500 hover:text-red-700 block transition-colors">
                    {{ __('Log Out') }}
                </a>
            </form>
        </div>
    </div>
</div>
