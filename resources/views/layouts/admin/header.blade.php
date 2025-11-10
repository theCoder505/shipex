<!-- Header -->
<header class="fixed top-0 left-0 right-0 z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-sm">
    <div class="flex items-center justify-between h-16 px-4 lg:px-6">
        
        <!-- Left Section: Hamburger + Logo -->
        <div class="flex items-center gap-4">
            <!-- Hamburger Menu Button -->
            <button id="menuToggle" type="button" class="inline-flex items-center justify-center p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors lg:hidden">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            <!-- Logo/Brand -->
            <a href="/admin/dashboard" class="flex items-center gap-2">
                <img src="{{ $website_icon ?? '/assets/images/logo.png' }}" alt="Logo" class="h-8 w-8 object-contain">
                <span class="text-xl font-bold text-gray-900 dark:text-white hidden sm:block">{{ $brandname ?? 'Admin' }}</span>
            </a>
        </div>

        <!-- Right Section: Search, Notifications, Profile -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Dark Mode Toggle -->
            <button id="darkModeToggle" type="button" class="p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                <svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"></path>
                </svg>
                <svg class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                </svg>
            </button>

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profileDropdown" type="button" class="flex items-center gap-2 p-1.5 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                    <img src="/assets/images/admin.jpg" alt="Profile" class="w-8 h-8 rounded-full object-cover">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 hidden sm:block">{{ Auth::user()->name ?? 'Admin' }}</span>
                    <svg class="w-4 h-4 text-gray-600 dark:text-gray-400 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileMenu" class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1">
                    <a href="/admin/account-settings" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                        <i class="fas fa-cog w-4"></i>
                        <span>Settings</span>
                    </a>
                    <hr class="my-1 border-gray-200 dark:border-gray-700">
                    <form method="POST" action="/admin/logout">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 text-left">
                            <i class="fas fa-sign-out-alt w-4"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</header>

<!-- Overlay for mobile menu -->
<div id="sidebarOverlay" class="hidden fixed inset-0 bg-[#00000035] bg-opacity-50 z-30 lg:hidden"></div>