<!-- Sidebar -->
<aside id="sidebar"
    class="fixed top-16 left-0 z-30 w-64 h-[calc(100vh-4rem)] bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800 transition-transform duration-300 -translate-x-full lg:translate-x-0 overflow-y-auto">

    <nav class="p-4 space-y-2">

        <!-- Dashboard -->
        <a href="/admin/dashboard"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition-colors dashboard sidebar_tab">
            <i class="fas fa-home w-5"></i>
            <span class="font-medium">Dashboard</span>
        </a>


        <a href="/admin/ussers/manufacturers"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition-colors manufacturers sidebar_tab">
            <i class="fas fa-industry w-4"></i>
            <span class="font-medium">Manufacturers</span>
        </a>


        <a href="/admin/ussers/wholesalers"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition-colors wholesalers sidebar_tab">
            <i class="fas fa-truck-loading w-4"></i>
            <span class="font-medium">Wholesalers</span>
        </a>


        <!-- Analytics -->
        <a href="/admin/frequently-asked-questions"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition-colors sidebar_tab">
            <i class="fas fa-question-circle w-5"></i>
            <span class="font-medium">FAQs</span>
        </a>

        <!-- Reports -->
        <a href="/admin/subscription-records"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition-colors subscriptions sidebar_tab">
            <i class="fas fa-calendar-check w-5"></i>
            <span class="font-medium">Subscriptions</span>
        </a>

        <!-- Settings Section -->
        <div class="space-y-1">
            <button type="button"
                class="menu-toggle flex items-center justify-between w-full px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors settings_tab">
                <div class="flex items-center gap-3">
                    <i class="fas fa-cog w-5"></i>
                    <span class="font-medium">Settings</span>
                </div>
                <svg class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div class="submenu hidden pl-4 space-y-1">
                <a href="/admin/settings/general"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors general sidebar_tab">
                    <i class="fas fa-wrench w-4"></i>
                    <span>General</span>
                </a>
                <a href="/admin/settings/coupon-codes"
                    class="flex items-center gap-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors coupon_codes sidebar_tab">
                    <i class="fas fa-gift"></i>
                    <span>Coupon Codes</span>
                </a>
            </div>
        </div>

        <!-- Divider -->
        {{-- <hr class="border-gray-200 dark:border-gray-800"> --}}

        <!-- Help & Support -->
        {{-- <a href="/admin/support"
            class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-gray-800 rounded-lg transition-colors sidebar_tab">
            <i class="fas fa-question-circle w-5"></i>
            <span class="font-medium">Help & Support</span>
        </a> --}}

    </nav>

    <!-- Footer Info -->
    <div class="p-4 mt-auto border-t border-gray-200 dark:border-gray-800">
        <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
            <p>© {{ date('Y') }} {{ $brandname ?? 'Admin' }}</p>
            <p class="mt-1">Version 1.0.0</p>
        </div>
    </div>

</aside>
