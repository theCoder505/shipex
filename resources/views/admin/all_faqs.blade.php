@extends('layouts.admin.app')

@section('title', 'All FAQs')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <style>
        /* Action Buttons */
        .action-btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            border-radius: 0.375rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            margin: 0 0.125rem;
        }

        .btn-edit {
            background-color: #3b82f6;
            color: white;
        }

        .btn-edit:hover {
            background-color: #2563eb;
        }

        .btn-delete {
            background-color: #ef4444;
            color: white;
        }

        .btn-delete:hover {
            background-color: #dc2626;
        }

        .btn-save {
            background-color: #10b981;
            color: white;
        }

        .btn-save:hover {
            background-color: #059669;
        }

        .btn-cancel {
            background-color: #6b7280;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #4b5563;
        }

        .btn-create {
            background-color: #10b981;
            color: white;
            padding: 0.625rem 1.25rem;
            font-size: 0.9375rem;
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-create:hover {
            background-color: #059669;
        }

        /* Form Styles */
        .form-input,
        .form-textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
        }

        .dark .form-input,
        .dark .form-textarea {
            background-color: #374151;
            color: #f9fafb;
            border-color: #4b5563;
        }

        .form-textarea {
            min-height: 80px;
            resize: vertical;
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

        /* Table Styles */
        table.dataTable tbody tr {
            cursor: default;
        }

        table.dataTable tbody tr:hover {
            background-color: #f9fafb;
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

        /* Create Form Card */
        .create-form-card {
            background-color: #f9fafb;
            border: 2px dashed #d1d5db;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .dark .create-form-card {
            background-color: #1f2937;
            border-color: #4b5563;
        }

        /* Edit Mode Row */
        tr.editing-row {
            background-color: #fef3c7 !important;
        }

        .dark tr.editing-row {
            background-color: #78350f !important;
        }

        /* Question Column */
        .question-text {
            font-weight: 600;
            color: #1f2937;
        }

        .dark .question-text {
            color: #f9fafb;
        }

        /* Answer Column */
        .answer-text {
            color: #4b5563;
            line-height: 1.5;
        }

        .dark .answer-text {
            color: #d1d5db;
        }
    </style>
@endsection

@section('content')

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
        </div>
    @endif

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Frequently Asked Questions</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage all FAQs - Create, edit, and delete questions and answers.
        </p>
    </div>

    <!-- Create New FAQ Form -->
    <div class="create-form-card">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Create New FAQ</h3>
        <form action="{{ url('/admin/frequently-asked-questions/create') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question</label>
                    <input type="text" name="question" class="form-input" placeholder="Enter your question here..."
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Answer</label>
                    <textarea name="answer" class="form-textarea" placeholder="Enter the answer here..." required></textarea>
                </div>
            </div>
            <button type="submit" class="btn-create">Create FAQ</button>
        </form>
    </div>

    <!-- FAQs Table Card -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All FAQs</h2>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table id="faqsTable" class="display responsive nowrap w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Question</th>
                            <th>Answer</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $record)
                            <tr data-id="{{ $record->id }}">
                                <form action="/admin/frequently-asked-questions/update" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $record->id }}">
                                    <td>{{ $record->id }}</td>
                                    <td>
                                        <div class="edit-mode">
                                            <input type="text" class="form-input question-input" name="question"
                                                value="{{ $record->question }}">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="edit-mode">
                                            <textarea class="form-textarea answer-input" name="answer">{{ $record->answer }}</textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="view-mode">
                                            <button type="submit" class="action-btn btn-edit edit-btn">Update</button>
                                            <button type="button" class="action-btn btn-delete delete-btn"
                                                onclick="deleteRow({{ $record->id }})">Delete</button>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Hidden Forms for Update and Delete -->
    <form id="updateForm" action="{{ url('/admin/frequently-asked-questions/update') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="id" id="update_id">
        <input type="hidden" name="question" id="update_question">
        <input type="hidden" name="answer" id="update_answer">
    </form>

    <form id="deleteForm" action="{{ url('/admin/frequently-asked-questions/delete') }}" method="POST"
        style="display: none;">
        @csrf
        <input type="hidden" name="id" id="delete_id">
    </form>

@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        let table;

        $(document).ready(function() {
            // Initialize DataTable
            table = $('#faqsTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Search FAQs:",
                    lengthMenu: "Show _MENU_ FAQs per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ FAQs",
                    infoEmpty: "No FAQs found",
                    infoFiltered: "(filtered from _MAX_ total FAQs)",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                },
                columnDefs: [{
                        responsivePriority: 1,
                        targets: [1, 3]
                    },
                    {
                        responsivePriority: 2,
                        targets: [0, 2]
                    }
                ]
            });

            // Auto-hide success alert after 5 seconds
            setTimeout(function() {
                $('#successAlert').fadeOut('slow');
            }, 5000);
        });


        function deleteRow(id) {
            if (confirm('Are you sure you want to delete this FAQ?')) {
                $('#delete_id').val(id);
                $('#deleteForm').submit();
            }
        }
    </script>
@endsection
