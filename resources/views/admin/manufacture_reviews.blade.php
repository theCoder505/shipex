@extends('layouts.admin.app')

@section('title', 'Manufacturer Reviews')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 2% auto;
            padding: 0;
            border-radius: 8px;
            width: 90%;
            max-width: 1000px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dark .modal-content {
            background-color: #1f2937;
        }

        .modal-header {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dark .modal-header {
            border-bottom-color: #374151;
        }

        .modal-body {
            padding: 20px;
            max-height: calc(100vh - 200px);
            overflow-y: auto;
        }

        .close {
            color: #9ca3af;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.2s;
        }

        .close:hover {
            color: #ef4444;
        }

        /* Info Grid */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .info-item {
            padding: 0.75rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
        }

        .dark .info-item {
            background-color: #374151;
        }

        .info-label {
            font-size: 0.875rem;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 0.25rem;
        }

        .dark .info-label {
            color: #9ca3af;
        }

        .info-value {
            font-size: 0.875rem;
            color: #111827;
            font-weight: 500;
        }

        .dark .info-value {
            color: #f3f4f6;
        }

        /* Rating Stars */
        .rating-stars {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .star {
            color: #fbbf24;
            font-size: 1.25rem;
        }

        .star.empty {
            color: #d1d5db;
        }

        .dark .star.empty {
            color: #4b5563;
        }

        /* DataTables Dark Mode */
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #d1d5db;
        }

        .dark .dataTables_wrapper .dataTables_filter input,
        .dark .dataTables_wrapper .dataTables_length select {
            background-color: #374151;
            color: #f3f4f6;
            border: 1px solid #4b5563;
        }

        .dark table.dataTable thead th,
        .dark table.dataTable thead td {
            border-bottom: 1px solid #374151;
            color: #f3f4f6;
        }

        .dark table.dataTable tbody tr {
            background-color: #1f2937;
        }

        .dark table.dataTable tbody tr:hover {
            background-color: #374151;
        }

        .dark table.dataTable tbody td {
            border-bottom: 1px solid #374151;
            color: #d1d5db;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #d1d5db !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #374151;
            color: #f3f4f6 !important;
        }

        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6;
            color: #fff !important;
        }
    </style>
@endsection

@section('content')

    <!-- Welcome Section -->
    <div class="mb-6">
        <div class="flex items-center gap-3 mb-2">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">
                Reviews Of "{{ $manufacturer->company_name_en }}"
            </h1>
        </div>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and view all reviews of this manufacturer</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-6">
        <!-- Total Reviews -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Reviews</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $reviews->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comments text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Average Rating -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Average Rating</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $reviews->avg('rating') ? number_format($reviews->avg('rating'), 1) : '0.0' }}
                        <span class="text-sm text-yellow-500">★</span>
                    </h3>
                </div>
                <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-yellow-600 dark:text-yellow-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- 5 Star Reviews -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">5 Star Reviews</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $reviews->where('rating', 5)->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-thumbs-up text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Low Ratings (1-2 stars) -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Low Ratings</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $reviews->whereIn('rating', [1, 2])->count() }}
                    </h3>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Reviews List</h2>
        </div>
        <div class="overflow-x-auto">
            <table id="reviewsTable" class="display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reviewer</th>
                        <th>Rating</th>
                        <th>Review</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reviews as $key => $review)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900 dark:text-white">
                                        {{ $review->wholesaler_uid ?? 'Anonymous' }}
                                    </span>
                                    @forelse ($wholesalers as $wholesaler)
                                        @if ($wholesaler->wholesaler_uid === $review->wholesaler_uid)
                                            <a href="mailto:{{ $wholesaler->email }}"
                                                class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                                {{ $wholesaler->email }}
                                            </a>
                                        @endif
                                    @empty
                                    @endforelse
                                </div>
                            </td>
                            <td>
                                <div class="flex items-center gap-1">
                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $review->rating }}</span>
                                    <div class="rating-stars">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <span class="star {{ $i <= $review->rating ? '' : 'empty' }}">★</span>
                                        @endfor
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="max-w-xs">
                                    <div class="text-sm text-gray-900 dark:text-gray-300 text-wrap">
                                        {{ $review->review_text }}
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $review->created_at->format('M d, Y') }}
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="text-black dark:text-white mb-2 text-sm font-semibold">
                                    Status: {{ $review->status == 0 ? 'Hidden' : 'Showing' }}
                                </div>
                                <div class="gir grid-cols-2 gap-2">
                                    @if ($review->status == 0)
                                        <a href="/admin/reviews/{{ $review->id }}/show"
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-white bg-teal-500 hover:bg-teal-600 rounded-md transition-colors">
                                            <i class="fas fa-eye mr-1"></i> Show
                                        </a>
                                    @else
                                        <a href="/admin/reviews/{{ $review->id }}/hide"
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-white bg-pink-500 hover:bg-pink-600 rounded-md transition-colors">
                                            <i class="fas fa-eye-slash mr-1"></i> Hide
                                        </a>
                                    @endif

                                    <form action="/admin/reviews/delete/{{ $review->id }}" method="POST" class="inline"
                                        onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center px-3 py-1.5 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-md transition-colors">
                                            <i class="fas fa-trash mr-1"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        // Mark active tab
        $(document).ready(function() {
            $(".manufacturers").addClass("active_tab");
        });

        // Initialize DataTable
        $(document).ready(function() {
            $('#reviewsTable').DataTable({
                responsive: true,
                pageLength: 9,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Search reviews:",
                    lengthMenu: "Show _MENU_ reviews per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ reviews",
                    infoEmpty: "No reviews found",
                    infoFiltered: "(filtered from _MAX_ total reviews)"
                }
            });
        });
    </script>
@endsection
