@extends('layouts.admin.app')

@section('title', 'Package Settings')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <style>
        .package-section {
            margin-bottom: 2rem;
        }

        .service-item {
            transition: all 0.3s ease;
        }

        .service-item:hover {
            background-color: rgba(59, 130, 246, 0.05);
        }
    </style>
@endsection

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Package Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage service plans properly from here</p>
    </div>

    <div class="grid gap-4 grid-cols-1 md:grid-cols-2">
        <!-- Starter Package Section -->
        <div
            class="package-section bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-rocket text-teal-500 mr-2"></i>Starter Package
                </h2>
            </div>

            <div class="space-y-3">
                @foreach ($services->where('package_of', 'starter') as $service)
                    <div
                        class="service-item flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center flex-1">
                            <div class="flex-1">
                                <input type="text" value="{{ $service->service_name }}"
                                    class="service-name-input bg-transparent border-none text-gray-900 dark:text-white focus:ring-0 w-full"
                                    data-id="{{ $service->id }}" readonly>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer availability-toggle"
                                    data-id="{{ $service->id }}" {{ $service->service_available ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Available</span>
                            </label>
                            <button type="button"
                                class="edit-btn text-teal-600 hover:text-teal-700 dark:text-teal-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button"
                                class="save-btn hidden text-green-600 hover:text-green-700 dark:text-green-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button"
                                class="cancel-btn hidden text-gray-600 hover:text-gray-700 dark:text-gray-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                            <button type="button" class="delete-btn text-red-600 hover:text-red-700 dark:text-red-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Service for Starter -->
                <div
                    class="flex items-center gap-2 p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg border-2 border-dashed border-teal-300 dark:border-teal-700">
                    <input type="text" placeholder="Add new service for Starter package..."
                        class="new-service-input flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        data-package="starter">
                    <button type="button"
                        class="add-service-btn px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors"
                        data-package="starter">
                        <i class="fas fa-plus mr-2"></i>Add Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Premium Package Section -->
        <div
            class="package-section bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-star text-teal-500 mr-2"></i>Premium Package
                </h2>
            </div>

            <div class="space-y-3">
                @foreach ($services->where('package_of', 'premium') as $service)
                    <div
                        class="service-item flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center flex-1">
                            <div class="flex-1">
                                <input type="text" value="{{ $service->service_name }}"
                                    class="service-name-input bg-transparent border-none text-gray-900 dark:text-white focus:ring-0 w-full"
                                    data-id="{{ $service->id }}" readonly>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer availability-toggle"
                                    data-id="{{ $service->id }}" {{ $service->service_available ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Available</span>
                            </label>
                            <button type="button"
                                class="edit-btn text-teal-600 hover:text-teal-700 dark:text-teal-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button"
                                class="save-btn hidden text-green-600 hover:text-green-700 dark:text-green-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button"
                                class="cancel-btn hidden text-gray-600 hover:text-gray-700 dark:text-gray-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                            <button type="button" class="delete-btn text-red-600 hover:text-red-700 dark:text-red-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Service for Premium -->
                <div
                    class="flex items-center gap-2 p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg border-2 border-dashed border-teal-300 dark:border-teal-700">
                    <input type="text" placeholder="Add new service for Premium package..."
                        class="new-service-input flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        data-package="premium">
                    <button type="button"
                        class="add-service-btn px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors"
                        data-package="premium">
                        <i class="fas fa-plus mr-2"></i>Add Service
                    </button>
                </div>
            </div>
        </div>

        <!-- Ultimate Package Section -->
        <div
            class="package-section bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">
                    <i class="fas fa-crown text-teal-500 mr-2"></i>Ultimate Package
                </h2>
            </div>

            <div class="space-y-3">
                @foreach ($services->where('package_of', 'ultimate') as $service)
                    <div
                        class="service-item flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="flex items-center flex-1">
                            <div class="flex-1">
                                <input type="text" value="{{ $service->service_name }}"
                                    class="service-name-input bg-transparent border-none text-gray-900 dark:text-white focus:ring-0 w-full"
                                    data-id="{{ $service->id }}" readonly>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" class="sr-only peer availability-toggle"
                                    data-id="{{ $service->id }}" {{ $service->service_available ? 'checked' : '' }}>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-teal-300 dark:peer-focus:ring-teal-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-teal-600">
                                </div>
                                <span class="ml-3 text-sm font-medium text-gray-900 dark:text-gray-300">Available</span>
                            </label>
                            <button type="button"
                                class="edit-btn text-teal-600 hover:text-teal-700 dark:text-teal-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button type="button"
                                class="save-btn hidden text-green-600 hover:text-green-700 dark:text-green-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-check"></i>
                            </button>
                            <button type="button"
                                class="cancel-btn hidden text-gray-600 hover:text-gray-700 dark:text-gray-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                            <button type="button" class="delete-btn text-red-600 hover:text-red-700 dark:text-red-500"
                                data-id="{{ $service->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Service for Ultimate -->
                <div
                    class="flex items-center gap-2 p-4 bg-teal-50 dark:bg-teal-900/20 rounded-lg border-2 border-dashed border-teal-300 dark:border-teal-700">
                    <input type="text" placeholder="Add new service for Ultimate package..."
                        class="new-service-input flex-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white rounded-lg px-4 py-2 focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                        data-package="ultimate">
                    <button type="button"
                        class="add-service-btn px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 transition-colors"
                        data-package="ultimate">
                        <i class="fas fa-plus mr-2"></i>Add Service
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function() {
            $(".settings_tab").click();
            $(".package_settings").addClass("active_tab");
        });

        // Add New Service
        $('.add-service-btn').click(function() {
            const packageType = $(this).data('package');
            const input = $(`.new-service-input[data-package="${packageType}"]`);
            const serviceName = input.val().trim();

            if (!serviceName) {
                toastr.error('Please enter a service name');
                return;
            }

            $.ajax({
                url: '/admin/settings/subscription-packages/create',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    package_of: packageType,
                    service_name: serviceName,
                    service_available: 1
                },
                success: function(response) {
                    toastr.success('Service added successfully!');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    toastr.error('Failed to add service');
                }
            });
        });

        // Edit Service
        $('.edit-btn').click(function() {
            const id = $(this).data('id');
            const input = $(`.service-name-input[data-id="${id}"]`);

            input.removeAttr('readonly').focus();
            input.addClass('border border-teal-500 px-2 py-1 rounded');

            $(this).addClass('hidden');
            $(`.save-btn[data-id="${id}"]`).removeClass('hidden');
            $(`.cancel-btn[data-id="${id}"]`).removeClass('hidden');
            $(`.delete-btn[data-id="${id}"]`).addClass('hidden');
        });

        // Save Service
        $('.save-btn').click(function() {
            const id = $(this).data('id');
            const input = $(`.service-name-input[data-id="${id}"]`);
            const serviceName = input.val().trim();

            if (!serviceName) {
                toastr.error('Service name cannot be empty');
                return;
            }

            $.ajax({
                url: '/admin/settings/subscription-packages/update',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    service_name: serviceName
                },
                success: function(response) {
                    toastr.success('Service updated successfully!');
                    input.attr('readonly', true);
                    input.removeClass('border border-teal-500 px-2 py-1 rounded');

                    $(`.edit-btn[data-id="${id}"]`).removeClass('hidden');
                    $(`.save-btn[data-id="${id}"]`).addClass('hidden');
                    $(`.cancel-btn[data-id="${id}"]`).addClass('hidden');
                    $(`.delete-btn[data-id="${id}"]`).removeClass('hidden');
                },
                error: function(xhr) {
                    toastr.error('Failed to update service');
                }
            });
        });

        // Cancel Edit
        $('.cancel-btn').click(function() {
            const id = $(this).data('id');
            location.reload();
        });

        // Delete Service
        $('.delete-btn').click(function() {
            const id = $(this).data('id');

            if (!confirm('Are you sure you want to delete this service?')) {
                return;
            }

            $.ajax({
                url: '/admin/settings/subscription-packages/delete',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function(response) {
                    toastr.success('Service removed successfully');
                    setTimeout(() => location.reload(), 1000);
                },
                error: function(xhr) {
                    toastr.error('Failed to delete service');
                }
            });
        });

        // Toggle Availability
        $('.availability-toggle').change(function() {
            const id = $(this).data('id');
            const isAvailable = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: '/admin/settings/subscription-packages/update',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id,
                    service_available: isAvailable
                },
                success: function(response) {
                    toastr.success('Availability updated successfully!');
                },
                error: function(xhr) {
                    toastr.error('Failed to update availability');
                    location.reload();
                }
            });
        });

        // Handle Enter key for new service inputs
        $('.new-service-input').keypress(function(e) {
            if (e.which === 13) {
                const packageType = $(this).data('package');
                $(`.add-service-btn[data-package="${packageType}"]`).click();
            }
        });
    </script>
@endsection
