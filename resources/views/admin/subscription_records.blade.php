@extends('layouts.admin.app')

@section('title', 'Subscription Records')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Status Badges */
        .status-active {
            color: #10b981;
            font-weight: 600;
        }
        .status-canceled {
            color: #ef4444;
            font-weight: 600;
        }
        .status-pending {
            color: #f59e0b;
            font-weight: 600;
        }
        .status-incomplete {
            color: #6b7280;
            font-weight: 600;
        }

        /* Package Type Badges */
        .package-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            margin: 0.125rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            background-color: #e5e7eb;
            color: #374151;
            text-transform: capitalize;
        }
        .dark .package-badge {
            background-color: #374151;
            color: #e5e7eb;
        }

        /* Payment Method Badges */
        .payment-method-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            background-color: #3b82f6;
            color: white;
        }

        /* DataTable Dark Mode Styles */
        .dark table.dataTable {
            color: #f9fafb;
        }
        .dark table.dataTable thead th {
            color: #f9fafb;
            background-color: #1f2937;
            border-bottom-color: #374151;
        }
        .dark table.dataTable tbody td {
            color: #e5e7eb;
            border-bottom-color: #374151;
        }
        .dark table.dataTable tbody tr {
            background-color: #111827;
        }
        .dark table.dataTable tbody tr:hover {
            background-color: #1f2937;
        }
        .dark .dataTables_wrapper .dataTables_length,
        .dark .dataTables_wrapper .dataTables_filter,
        .dark .dataTables_wrapper .dataTables_info,
        .dark .dataTables_wrapper .dataTables_paginate {
            color: #e5e7eb;
        }
        .dark .dataTables_wrapper .dataTables_filter input,
        .dark .dataTables_wrapper .dataTables_length select {
            background-color: #374151;
            color: #f9fafb;
            border: 1px solid #4b5563;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            color: #e5e7eb !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #3b82f6 !important;
            color: white !important;
            border-color: #3b82f6 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #374151 !important;
            color: white !important;
            border-color: #4b5563 !important;
        }

        /* Table Hover Effects */
        table.dataTable tbody tr {
            cursor: pointer;
        }
        table.dataTable tbody tr:hover {
            background-color: #f9fafb;
        }

        /* Amount Styles */
        .amount {
            font-weight: 600;
            color: #059669;
        }
        .dark .amount {
            color: #10b981;
        }

        /* Date Styles */
        .date-cell {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .dark .date-cell {
            color: #9ca3af;
        }

        /* No Data Styles */
        .no-data {
            text-align: center;
            color: #6b7280;
            font-style: italic;
            padding: 1rem;
        }
        .dark .no-data {
            color: #9ca3af;
        }
    </style>
@endsection

@section('content')

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Subscription Records</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">View all the subscription records done by the manufacturers.</p>
    </div>

    <!-- Subscription Records Table Card -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Subscription History</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="subscriptionsTable" class="display responsive nowrap w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Manufacturer UID</th>
                            <th>Billing Name</th>
                            <th>Billing Email</th>
                            <th>Package Type</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Payment Method</th>
                            <th>Payment Date</th>
                            <th>Coupon Code</th>
                            <th>Stripe Subscription ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($records as $key => $record)
                        <tr>
                            <td>{{ $record->id }}</td>
                            <td>{{ $record->manufacturer_uid }}</td>
                            <td>{{ $record->billing_name }}</td>
                            <td>{{ $record->billing_email }}</td>
                            <td>
                                <span class="package-badge">{{ $record->package_type }}</span>
                            </td>
                            <td>
                                <span class="amount">{{ $record->currency }} {{ $record->amount }}</span>
                            </td>
                            <td>
                                @if($record->payment_status == 'active')
                                    <span class="status-active">Active</span>
                                @elseif($record->payment_status == 'canceled')
                                    <span class="status-canceled">Canceled</span>
                                @elseif($record->payment_status == 'pending')
                                    <span class="status-pending">Pending</span>
                                @else
                                    <span class="status-incomplete">Incomplete</span>
                                @endif
                            </td>
                            <td>
                                <span class="payment-method-badge">{{ ucfirst($record->payment_method) }}</span>
                            </td>
                            <td>
                                <span class="date-cell">
                                    {{ \Carbon\Carbon::parse($record->payment_date)->format('M d, Y H:i') }}
                                </span>
                            </td>
                            <td>
                                @if($record->coupon_code)
                                    <span class="package-badge">{{ $record->coupon_code }}</span>
                                @else
                                    <span class="no-data">No coupon</span>
                                @endif
                            </td>
                            <td>
                                @if($record->stripe_subscription_id)
                                    <span class="text-xs font-mono text-gray-600 dark:text-gray-400">
                                        {{ $record->stripe_subscription_id }}
                                    </span>
                                @else
                                    <span class="no-data">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // Mark active tab
            $(".subscriptions").addClass("active_tab");

            // Get URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const manufacturerParam = urlParams.get('manufacturer');

            // Initialize DataTable
            const table = $('#subscriptionsTable').DataTable({
                responsive: true,
                pageLength: 9,
                order: [[0, 'desc']],
                language: {
                    search: "Search subscriptions:",
                    lengthMenu: "Show _MENU_ records per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ records",
                    infoEmpty: "No subscription records found",
                    infoFiltered: "(filtered from _MAX_ total records)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { 
                        responsivePriority: 1, 
                        targets: [0, 2, 3, 5, 6] // ID, Name, Email, Amount, Status
                    },
                    { 
                        responsivePriority: 2, 
                        targets: [1, 4, 7, 8] // UID, Package, Method, Date
                    },
                    { 
                        responsivePriority: 3, 
                        targets: [9, 10] // Coupon, Stripe ID
                    }
                ],
                initComplete: function() {
                    // If manufacturer parameter exists, apply it to the search field
                    if (manufacturerParam) {
                        this.api().search(manufacturerParam).draw();
                        
                        // Also set the search input value for better UX
                        const searchInput = $('.dataTables_filter input');
                        searchInput.val(manufacturerParam);
                        
                        // Trigger input event to update the search
                        searchInput.trigger('input');
                    }
                }
            });

            // Auto-hide success alert after 5 seconds
            setTimeout(function() {
                const successAlert = document.getElementById('successAlert');
                if (successAlert) {
                    successAlert.fadeOut('slow');
                }
            }, 5000);
        });
    </script>
@endsection