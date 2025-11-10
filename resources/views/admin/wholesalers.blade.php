@extends('layouts.admin.app')

@section('title', 'Wholesalers')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Category Badges */
        .category-badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            margin: 0.125rem;
            font-size: 0.75rem;
            border-radius: 0.25rem;
            background-color: #e5e7eb;
            color: #374151;
        }
        .dark .category-badge {
            background-color: #374151;
            color: #e5e7eb;
        }

        /* Status Badges */
        .status-active {
            color: #10b981;
            font-weight: 600;
        }
        .status-inactive {
            color: #ef4444;
            font-weight: 600;
        }
        .status-restricted {
            color: #f59e0b;
            font-weight: 600;
        }
        .status-unverified {
            color: #6b7280;
            font-weight: 600;
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

        /* Action Buttons */
        .action-btn {
            display: inline-block;
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
            border: none;
        }
        .btn-restrict {
            background-color: #ef4444;
            color: white;
        }
        .btn-restrict:hover {
            background-color: #dc2626;
        }
        .btn-unrestrict {
            background-color: #10b981;
            color: white;
        }
        .btn-unrestrict:hover {
            background-color: #059669;
        }

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
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 10% auto;
            padding: 30px;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
        }
        .dark .modal-content {
            background-color: #1f2937;
            color: #f9fafb;
        }
        .close {
            color: #9ca3af;
            float: right;
            font-size: 28px;
            font-weight: bold;
            line-height: 20px;
            cursor: pointer;
        }
        .close:hover,
        .close:focus {
            color: #374151;
        }
        .dark .close:hover,
        .dark .close:focus {
            color: #e5e7eb;
        }

        /* Form Styles */
        .form-group {
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        .dark .form-label {
            color: #e5e7eb;
        }
        .form-control {
            width: 100%;
            padding: 0.625rem;
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background-color: white;
            color: #111827;
        }
        .dark .form-control {
            background-color: #374151;
            color: #f9fafb;
            border-color: #4b5563;
        }
        .form-control:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Button Styles */
        .btn-submit {
            background-color: #3b82f6;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-submit:hover {
            background-color: #2563eb;
        }
        .btn-cancel {
            background-color: #6b7280;
            color: white;
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            border: none;
            font-weight: 500;
            cursor: pointer;
            margin-left: 0.5rem;
            transition: all 0.2s;
        }
        .btn-cancel:hover {
            background-color: #4b5563;
        }

        /* Alert Styles */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
        }
        .alert-success {
            background-color: #d1fae5;
            color: #065f46;
            border: 1px solid #6ee7b7;
        }
        .dark .alert-success {
            background-color: #064e3b;
            color: #d1fae5;
            border-color: #047857;
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
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">All Wholesalers</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and view all wholesaler accounts. You can restrict or unrestrict wholesalers as needed.</p>
    </div>

    <!-- Wholesalers Table Card -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Wholesalers List</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="wholesalersTable" class="display responsive nowrap w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>UID</th>
                            <th>Company Name</th>
                            <th>Email</th>
                            <th>Business Type</th>
                            <th>Industry Focus</th>
                            <th>Country</th>
                            <th>Categories</th>
                            <th>Language</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wholesalers as $key => $wholesaler)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $wholesaler->wholesaler_uid }}</td>
                            <td>{{ $wholesaler->company_name ?? 'N/A' }}</td>
                            <td>{{ $wholesaler->email }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $wholesaler->business_type)) }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $wholesaler->industry_focus)) }}</td>
                            <td>{{ strtoupper($wholesaler->country) }}</td>
                            <td>
                                @if(is_array($wholesaler->category))
                                    @foreach($wholesaler->category as $cat)
                                        <span class="category-badge">{{ ucfirst($cat) }}</span>
                                    @endforeach
                                @else
                                    @php
                                        $categories = json_decode($wholesaler->category, true);
                                    @endphp
                                    @if($categories)
                                        @foreach($categories as $cat)
                                            <span class="category-badge">{{ ucfirst($cat) }}</span>
                                        @endforeach
                                    @endif
                                @endif
                            </td>
                            <td>{{ ucfirst($wholesaler->language) }}</td>
                            <td>
                                @if($wholesaler->status == '1')
                                    <span class="status-active">Active & Verified</span>
                                @elseif($wholesaler->status == '3')
                                    <span class="status-restricted">Restricted</span>
                                @elseif($wholesaler->status == '0')
                                    <span class="status-unverified">Not Verified</span>
                                @else
                                    <span class="status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($wholesaler->created_at)->format('M d, Y') }}</td>
                            <td>
                                @if($wholesaler->status == '3')
                                    <button class="action-btn btn-unrestrict" onclick="openRestrictionModal('{{ $wholesaler->id }}', '{{ $wholesaler->wholesaler_uid }}', '{{ $wholesaler->email }}', 'unrestrict')">
                                        Unrestrict
                                    </button>
                                @elseif($wholesaler->status == '1')
                                    <button class="action-btn btn-restrict" onclick="openRestrictionModal('{{ $wholesaler->id }}', '{{ $wholesaler->wholesaler_uid }}', '{{ $wholesaler->email }}', 'restrict')">
                                        Restrict
                                    </button>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500 text-sm">Not Verified</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Restriction Modal -->
    <div id="restrictionModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeRestrictionModal()">&times;</span>
            <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-white" id="modalTitle">Restrict Wholesaler</h2>
            
            <form id="restrictionForm" method="POST" action="{{ url('/admin/wholesaler-toggle-restriction') }}">
                @csrf
                <input type="hidden" name="wholesaler_id" id="wholesaler_id">
                <input type="hidden" name="wholesaler_uid" id="wholesaler_uid">
                <input type="hidden" name="action_type" id="action_type">
                
                <div class="form-group">
                    <label class="form-label">Wholesaler UID</label>
                    <input type="text" class="form-control" id="display_uid" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" id="display_email" readonly>
                </div>
                
                <div class="form-group">
                    <label class="form-label" for="admin_comment">Admin Comment</label>
                    <textarea class="form-control" id="admin_comment" name="admin_comment" rows="4" placeholder="Enter reason for this action..."></textarea>
                </div>
                
                <div class="mt-6">
                    <button type="submit" class="btn-submit">Confirm</button>
                    <button type="button" class="btn-cancel" onclick="closeRestrictionModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function() {
            // Mark active tab
            $(".wholesalers").addClass("active_tab");

            // Initialize DataTable
            $('#wholesalersTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[0, 'desc']],
                language: {
                    search: "Search wholesalers:",
                    lengthMenu: "Show _MENU_ wholesalers per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ wholesalers",
                    infoEmpty: "No wholesalers found",
                    infoFiltered: "(filtered from _MAX_ total wholesalers)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [
                    { orderable: false, targets: [7, 11] } // Disable sorting on Categories and Actions columns
                ]
            });

            // Auto-hide success alert after 5 seconds
            setTimeout(function() {
                $('#successAlert').fadeOut('slow');
            }, 5000);
        });

        function openRestrictionModal(id, uid, email, action) {
            document.getElementById('wholesaler_id').value = id;
            document.getElementById('wholesaler_uid').value = uid;
            document.getElementById('display_uid').value = uid;
            document.getElementById('display_email').value = email;
            document.getElementById('action_type').value = action;
            
            if (action === 'restrict') {
                document.getElementById('modalTitle').textContent = 'Restrict Wholesaler';
                document.getElementById('admin_comment').placeholder = 'Enter reason for restricting this wholesaler...';
            } else {
                document.getElementById('modalTitle').textContent = 'Unrestrict Wholesaler';
                document.getElementById('admin_comment').placeholder = 'Enter reason for unrestricting this wholesaler...';
            }
            
            document.getElementById('restrictionModal').style.display = 'block';
        }

        function closeRestrictionModal() {
            document.getElementById('restrictionModal').style.display = 'none';
            document.getElementById('restrictionForm').reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('restrictionModal');
            if (event.target == modal) {
                closeRestrictionModal();
            }
        }
    </script>
@endsection