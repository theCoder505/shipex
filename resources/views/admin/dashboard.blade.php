@extends('layouts.admin.app')

@section('title', 'Dashboard')

@section('style')
    <style>
        .stat-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection

@section('content')

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Welcome back, {{ $admin->name }}!</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Here's what's happening with your platform today.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 md:gap-6 mb-6">

        <!-- 🏭 Total Wholesalers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Wholesalers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $wholesalers->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-people-carry text-indigo-600 dark:text-indigo-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- ✅ Verified Wholesalers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Verified Wholesalers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $wholesalers->where('status', 1)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- ⚠️ Not Verified Wholesalers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Not Verified Wholesalers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $wholesalers->where('status', 0)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-times text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- 🚫 Restricted Wholesalers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Restricted Wholesalers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $wholesalers->where('status', 3)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-slash text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- 🏢 Total Manufacturers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Manufacturers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $manufacturers->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-cyan-100 dark:bg-cyan-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-cogs text-cyan-600 dark:text-cyan-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- 🧾 Admin Verified Manufacturers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Admin Verified Manufacturers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 5)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- 👤 User Verified Manufacturers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">User Verified Manufacturers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 1)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- ⛔ Not Verified Manufacturers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Not Verified Manufacturers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 0)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-orange-600 dark:text-orange-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- 🚫 Rejected Manufacturers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected Manufacturers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 3)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ban text-rose-600 dark:text-rose-400 text-xl"></i>
                </div>
            </div>
        </div>

    </div>


    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h2>
            <div class="space-y-3">
                <a href="/admin/users/manufacturers"
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-industry text-blue-600 dark:text-blue-400"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Manufacturers</span>
                </a>
                <a href="/admin/users/wholesalers"
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-truck-loading text-purple-600 dark:text-purple-400"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Wholesalers</span>
                </a>
                <a href="/admin/reports"
                    class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                    <div class="w-10 h-10 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-alt text-green-600 dark:text-green-400"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Generate Report</span>
                </a>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Admin Info</h2>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <i class="fas fa-user text-gray-400 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Name</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-envelope text-gray-400 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-clock text-gray-400 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Last Activity</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($admin->last_activity)->diffForHumans() }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-laptop text-gray-400 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Device</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->last_login_device }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-globe text-gray-400 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Browser</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->last_login_browser }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-map-marker-alt text-gray-400 w-5"></i>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Location</p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">IP: {{ $admin->last_login_ip }}
                        </p>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->last_login_location }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(".dashboard").addClass("active_tab");
    </script>
@endsection
