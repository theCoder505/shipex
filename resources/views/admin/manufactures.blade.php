@extends('layouts.admin.app')

@section('title', 'Manufacturers')

@section('style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
@endsection

@section('content')

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">All Manufacturers</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and view all manufacturer accounts.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 md:gap-6 mb-6">

        <!-- Total Manufacturers -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Manufacturers</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $manufacturers->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-industry text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Admin Verified -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Admin Verified</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 5)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 dark:text-green-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- User Verified -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">User Verified</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 1)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user-check text-blue-600 dark:text-blue-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Not Verified -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Not Verified</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 0)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Not Verified -->
        <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected</p>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mt-2">
                        {{ $manufacturers->where('status', 3)->count() }}</h3>
                </div>
                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fas fa-ban text-red-600 dark:text-red-400 text-xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Manufacturers Table -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="p-6 border-b border-gray-200 dark:border-gray-800">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Manufacturers List</h2>
        </div>
        <div class="overflow-x-auto">
            <table id="manufacturersTable" class="display responsive nowrap" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>UID</th>
                        <th>Email</th>
                        <th>Reviews</th>
                        <th>Industry</th>
                        <th>Status</th>
                        <th>Registered</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($manufacturers as $manufacturer)
                        <tr>
                            <td>{{ $manufacturer->id }}</td>
                            <td>{{ $manufacturer->manufacturer_uid }}</td>
                            <td>
                                <a class="text-blue-500"
                                    href="mailto:{{ $manufacturer->email }}">{{ $manufacturer->email }}</a>
                            </td>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="text-sm">
                                        <span
                                            class="font-semibold text-gray-900 dark:text-white">{{ $manufacturer->rating ?? '0.0' }}</span>
                                        <span
                                            class="text-xs text-gray-500 dark:text-gray-400">({{ $manufacturer->total_ratings ?? 0 }})</span>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $manufacturer->industry_category ?? 'N/A' }}</td>
                            <td>
                                @if ($manufacturer->status == 0)
                                    <span class="status-badge status-not-verified">Not Verified</span>
                                @elseif($manufacturer->status == 1)
                                    <span class="status-badge status-user-verified">User Verified</span>
                                @elseif($manufacturer->status == 3)
                                    <span class="status-badge status-not-verified">Application Rejected</span>
                                @elseif($manufacturer->status == 5)
                                    <span class="status-badge status-admin-verified">Application Approved</span>
                                @endif
                            </td>
                            <td>{{ $manufacturer->created_at->format('M d, Y') }}</td>
                            <td>
                                <button onclick="viewManufacturer({{ $manufacturer->id }})"
                                    class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors">
                                    View More
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="manufacturerModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white" id="modalTitle">Manufacturer Details</h2>
            </div>
            <div class="modal-body" id="modalBody">
                <!-- Content will be loaded here -->
            </div>
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
            $('#manufacturersTable').DataTable({
                responsive: true,
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                language: {
                    search: "Search manufacturers:",
                    lengthMenu: "Show _MENU_ manufacturers per page",
                    info: "Showing _START_ to _END_ of _TOTAL_ manufacturers",
                    infoEmpty: "No manufacturers found",
                    infoFiltered: "(filtered from _MAX_ total manufacturers)"
                }
            });
        });

        // Manufacturers data
        const manufacturersData = @json($manufacturers);

        // View manufacturer details
        function viewManufacturer(id) {
            const manufacturer = manufacturersData.find(m => m.id === id);
            if (!manufacturer) return;

            const modalBody = document.getElementById('modalBody');

            let statusBadge = '';
            if (manufacturer.status == 0) {
                statusBadge = '<span class="status-badge status-not-verified">Not Verified</span>';
            } else if (manufacturer.status == 1) {
                statusBadge = '<span class="status-badge status-user-verified">User Verified</span>';
            } else if (manufacturer.status == 3) {
                statusBadge = '<span class="status-badge status-not-verified">Application Rejected</span>';
            } else if (manufacturer.status == 5) {
                statusBadge = '<span class="status-badge status-admin-verified">Admin Verified</span>';
            }

            let subscriptionStatus = '';
            if (manufacturer.subscription == 0) {
                subscriptionStatus = '<span class="status-badge status-not-verified">Not Yet Subscribed</span>';
            } else {
                subscriptionStatus = '<span class="status-badge status-admin-verified">Subscribed</span>';
            }

            let subcriptionRecords = '';
            if (manufacturer.subscription != 0) {
                subcriptionRecords = `<div>
                                        <a href="/admin/subscription-records?manufacturer=${manufacturer.manufacturer_uid}" target="_blank"
                                            class="inline-flex items-center px-3 py-1 bg-pink-600 hover:bg-pink-700 text-white text-sm rounded-full shadow-sm transition-colors"
                                            title="View reviews by admin">
                                            <i class="fas fa-calendar-check mr-2"></i>
                                            Subscription Records
                                        </a>
                                    </div>`;
            }


            let showReviews = '';
            if (manufacturer.total_ratings > 0) {
                showReviews = `<div>
                                    <a href="/admin/manufacturers/${manufacturer.manufacturer_uid}/reviews" target="_blank"
                                        class="inline-flex items-center px-3 py-1 bg-teal-600 hover:bg-teal-700 text-white text-sm rounded-full shadow-sm transition-colors"
                                        title="View reviews by admin">
                                        <i class="fas fa-comments mr-2"></i>
                                        All Reviews
                                    </a>
                                </div>`;
            }

            // Products HTML
            let productsHtml = '<p class="text-gray-500 dark:text-gray-400">No products listed</p>';
            if (manufacturer.products && manufacturer.products.length > 0) {
                productsHtml = '<div class="image-grid">';
                manufacturer.products.forEach(product => {
                    productsHtml += `
                    <div class="image-item">
                        <img src="/${product.image}" alt="${product.name}" class="w-full h-32 object-cover rounded">
                        <p class="text-sm text-center mt-2 text-gray-900 dark:text-white">${product.name}</p>
                    </div>
                `;
                });
                productsHtml += '</div>';
            }

            // Certifications HTML
            let certificationsHtml = '<p class="text-gray-500 dark:text-gray-400">No certifications</p>';
            if (manufacturer.certifications && manufacturer.certifications.length > 0) {
                certificationsHtml = '<ul class="list-disc pl-5 space-y-2">';
                manufacturer.certifications.forEach(cert => {
                    certificationsHtml += `
                    <li class="text-gray-900 dark:text-white">
                        <strong>${cert.name}</strong>
                        <a href="/${cert.document}" target="_blank" class="text-blue-600 hover:underline ml-2">View Document</a>
                    </li>
                `;
                });
                certificationsHtml += '</ul>';
            }

            // Factory Pictures HTML
            let factoryPicsHtml = '<p class="text-gray-500 dark:text-gray-400">No factory pictures</p>';
            if (manufacturer.factory_pictures && manufacturer.factory_pictures.length > 0) {
                factoryPicsHtml = '<div class="image-grid">';
                manufacturer.factory_pictures.forEach(pic => {
                    factoryPicsHtml += `
                    <div class="image-item">
                        <img src="/${pic.image}" alt="${pic.title}" class="w-full h-32 object-cover rounded">
                        <p class="text-sm text-center mt-2 text-gray-900 dark:text-white">${pic.title}</p>
                    </div>
                `;
                });
                factoryPicsHtml += '</div>';
            }

            modalBody.innerHTML = `
            <!-- Status Change Section -->
            <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="flex flex-wrap justify-between gap-4">
                    <div class="flex flex-wrap gap-2">
                        <div>
                            ${statusBadge}
                        </div>
                        <div>
                            ${subscriptionStatus}
                        </div>
                        ${showReviews}
                        ${subcriptionRecords}
                    </div>
                </div>
            </div>

            <!-- Company Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-building text-blue-600"></i> Company Information
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Company Name (EN)</p>
                        <p class="info-value">${manufacturer.company_name_en || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Company Name (KO)</p>
                        <p class="info-value">${manufacturer.company_name_ko || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Company Address (EN)</p>
                        <p class="info-value">${manufacturer.company_address_en || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Company Address (KO)</p>
                        <p class="info-value">${manufacturer.company_address_ko || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Year Established</p>
                        <p class="info-value">${manufacturer.year_established || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Number of Employees</p>
                        <p class="info-value">${manufacturer.number_of_employees || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Website</p>
                        <p class="info-value"><a href="${manufacturer.website}" target="_blank" class="text-blue-600 hover:underline">${manufacturer.website || 'N/A'}</a></p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Language</p>
                        <p class="info-value">${manufacturer.language || 'N/A'}</p>
                    </div>
                </div>
                ${manufacturer.company_logo ? `
                                    <div class="mt-4">
                                        <p class="info-label mb-2">Company Logo</p>
                                        <img src="/${manufacturer.company_logo}" alt="Company Logo" class="w-32 h-32 object-contain border rounded">
                                    </div>
                                ` : ''}
                ${manufacturer.business_introduction ? `
                                    <div class="mt-4 info-item">
                                        <p class="info-label">Business Introduction</p>
                                        <p class="info-value">${manufacturer.business_introduction}</p>
                                    </div>
                                ` : ''}
            </div>

            <!-- Contact Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-user text-purple-600"></i> Contact Person
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Contact Name</p>
                        <p class="info-value">${manufacturer.contact_name || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Position</p>
                        <p class="info-value">${manufacturer.contact_position || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Email</p>
                        <p class="info-value"><a href="mailto:${manufacturer.contact_email}" class="text-blue-600 hover:underline">${manufacturer.contact_email || 'N/A'}</a></p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Phone</p>
                        <p class="info-value">${manufacturer.contact_phone || 'N/A'}</p>
                    </div>
                </div>
            </div>

            <!-- Business Profile -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-briefcase text-green-600"></i> Business Profile
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Business Type</p>
                        <p class="info-value">${manufacturer.business_type || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Industry Category</p>
                        <p class="info-value">${manufacturer.industry_category || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Registration Number</p>
                        <p class="info-value">${manufacturer.business_registration_number || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Export Experience</p>
                        <p class="info-value">${manufacturer.export_experience || 'N/A'}</p>
                    </div>
                    ${manufacturer.export_experience === 'yes' ? `
                                        <div class="info-item">
                                            <p class="info-label">Export Years</p>
                                            <p class="info-value">${manufacturer.export_years || 'N/A'} years</p>
                                        </div>
                                    ` : ''}
                </div>
                ${manufacturer.business_registration_license ? `
                                    <div class="mt-4">
                                        <p class="info-label mb-2">Business Registration License</p>
                                        <a href="/${manufacturer.business_registration_license}" target="_blank" class="text-blue-600 hover:underline">View Document</a>
                                    </div>
                                ` : ''}
            </div>

            <!-- Product Information -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-box text-orange-600"></i> Product Information
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Main Product Category</p>
                        <p class="info-value">${manufacturer.main_product_category || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Production Capacity</p>
                        <p class="info-value">${manufacturer.production_capacity || 'N/A'} ${manufacturer.production_capacity_unit || ''}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">MOQ</p>
                        <p class="info-value">${manufacturer.moq || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Has Patents</p>
                        <p class="info-value">${manufacturer.has_patents || 'N/A'}</p>
                    </div>
                </div>
                <div class="mt-4">
                    <p class="info-label mb-3">Products</p>
                    ${productsHtml}
                </div>
                ${manufacturer.catalogue ? `
                                    <div class="mt-4">
                                        <p class="info-label mb-2">Product Catalogue</p>
                                        <a href="/${manufacturer.catalogue}" target="_blank" class="text-blue-600 hover:underline">View Catalogue</a>
                                    </div>
                                ` : ''}
            </div>

            <!-- Certifications & Standards -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-certificate text-yellow-600"></i> Certifications & Standards
                </h3>
                <div class="info-grid mb-4">
                    <div class="info-item">
                        <p class="info-label">Has QMS</p>
                        <p class="info-value">${manufacturer.has_qms || 'N/A'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Factory Audit Available</p>
                        <p class="info-value">${manufacturer.factory_audit_available || 'N/A'}</p>
                    </div>
                    ${manufacturer.standards && manufacturer.standards.length > 0 ? `
                                        <div class="info-item">
                                            <p class="info-label">Standards</p>
                                            <p class="info-value">${manufacturer.standards.join(', ')}</p>
                                        </div>
                                    ` : ''}
                </div>
                <div class="mt-4">
                    <p class="info-label mb-3">Certifications</p>
                    ${certificationsHtml}
                </div>
            </div>

            <!-- Factory Pictures -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-images text-red-600"></i> Factory Pictures
                </h3>
                ${factoryPicsHtml}
            </div>

            <!-- Declaration -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-file-signature text-indigo-600"></i> Declaration
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <p class="info-label">Agreed to Terms</p>
                        <p class="info-value">${manufacturer.agree_terms ? 'Yes' : 'No'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Consent Background Check</p>
                        <p class="info-value">${manufacturer.consent_background_check ? 'Yes' : 'No'}</p>
                    </div>
                    <div class="info-item">
                        <p class="info-label">Digital Signature</p>
                        <p class="info-value">${manufacturer.digital_signature || 'N/A'}</p>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="info-grid">
                <div class="info-item">
                    <p class="info-label">Created At</p>
                    <p class="info-value">${new Date(manufacturer.created_at).toLocaleString()}</p>
                </div>
                <div class="info-item">
                    <p class="info-label">Last Updated</p>
                    <p class="info-value">${new Date(manufacturer.updated_at).toLocaleString()}</p>
                </div>
            </div>

            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 py-6 w-full lg:w-1/2">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <i class="fas fa-tasks text-blue-600"></i> Application Status Management
                </h3>
                <form method="POST" action="/admin/ussers/manufacturers/change-status" class="w-full">
                    @csrf
                    <input type="hidden" name="manufacturer_uid" value="${manufacturer.manufacturer_uid}">
                    <div class="gap-4 items-start sm:items-center">
                        <div class="w-full">
                            <label for="statusSelect" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Change Application Status
                            </label>
                            <select id="statusSelect" name="status" required
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="" disabled selected>Select new status</option>
                                <option value="1" ${manufacturer.status == 1 ? 'selected' : ''}>Keep User Verified</option>
                                <option value="5" ${manufacturer.status == 5 ? 'selected' : ''}>Approve Application</option>
                                <option value="3" ${manufacturer.status == 3 ? 'selected' : ''}>Reject Application</option>
                            </select>
                        </div>
                        <div class="w-full mt-4">
                            <label for="adminComment" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Review Summary
                            </label>
                            <textarea id="adminComment" name="admin_comment" rows="3"
                                class="w-full px-4 py-2.5 border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="Add your comment here">${manufacturer.admin_comment || ''}</textarea>
                        </div>
                        <div class="w-full">
                            <button type="submit"
                                class="mt-4 w-full block px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors duration-200 ease-in-out focus:ring-4 focus:ring-blue-500/50">
                                Update Status
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        `;

            document.getElementById('manufacturerModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('manufacturerModal').style.display = 'none';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('manufacturerModal');
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
@endsection
