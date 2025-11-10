@extends('layouts.admin.app')

@section('title', 'Coupon codes')

@section('style')
    <style>
        .coupon-type-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
        }

        .coupon-type-fixed {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .coupon-type-fixed.dark {
            background-color: #1e3a8a;
            color: #dbeafe;
        }

        .coupon-type-percentage {
            background-color: #dcfce7;
            color: #166534;
        }

        .coupon-type-percentage.dark {
            background-color: #14532d;
            color: #dcfce7;
        }

        .calculated-value {
            background-color: #f0f9ff;
            border-color: #0ea5e9;
        }

        .dark .calculated-value {
            background-color: #1e3a8a;
            border-color: #3b82f6;
            color: #dbeafe;
        }
    </style>
@endsection

@section('content')

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">Coupon Codes</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">
            Manage your discount coupons and promotional codes.
            <i class="fas fa-info-circle cursor-pointer" onclick="toggleInfo()"></i>
        </p>

        <p
            class="text-gray-600 dark:text-gray-300 bg-white dark:bg-slate-900 border rounded-lg p-4 max-w-[600px] absolute mt-4 coupon_desc hidden text-sm">
            Percentage discounts calculate based on package price, while fixed discounts subtract the same amount from any
            package.<br>
            Examples: <br>
            • Monthly $15: 20% = $12 • Half-yearly $80: $15 off = $65 • Yearly $150: 25% = $112.50<br>
            • Universal fixed discount: $5 off any package ($15→$10, $80→$75, $150→$145)
            <br>
        </p>
    </div>

    <!-- Add New Coupon Code Button -->
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">All Coupon Codes</h2>
        <button type="button" id="openAddModalBtn"
            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
            Add New Coupon
        </button>
    </div>

    <!-- Coupon Codes Table -->
    <div class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Coupon Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Discount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($coupon_codes as $coupon)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $coupon->coupon_code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($coupon->type === 'fixed')
                                    <span class="coupon-type-badge coupon-type-fixed dark:coupon-type-fixed">Fixed Amount</span>
                                @else
                                    <span class="coupon-type-badge coupon-type-percentage dark:coupon-type-percentage">Percentage</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @if ($coupon->type === 'fixed')
                                        ${{ number_format($coupon->discount_amount, 2) }}
                                    @else
                                        {{ $coupon->discount_percentage }}%
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button type="button" data-coupon-id="{{ $coupon->id }}"
                                    data-coupon-code="{{ $coupon->coupon_code }}"
                                    data-monthly_fee_amount="{{ $coupon->monthly_fee_amount }}"
                                    data-half_yearly_fee_amount="{{ $coupon->half_yearly_fee_amount }}"
                                    data-yearly_fee_amount="{{ $coupon->yearly_fee_amount }}"
                                    data-type="{{ $coupon->type }}" data-discount-amount="{{ $coupon->discount_amount }}"
                                    data-discount-percentage="{{ $coupon->discount_percentage }}"
                                    class="edit-coupon-btn text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-4">
                                    Edit
                                </button>
                                <button type="button" data-coupon-id="{{ $coupon->id }}"
                                    data-coupon-code="{{ $coupon->coupon_code }}"
                                    class="delete-coupon-btn text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No coupon codes found. Create your first coupon code!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Coupon Modal -->
    <div id="addModal" class="fixed inset-0 bg-[#00000035] overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 md:top-10 mx-auto p-6 w-full max-w-6xl shadow-2xl rounded-lg bg-white dark:bg-gray-800 mb-10">
            <div class="max-h-[85vh] overflow-y-auto pr-2">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Add New Coupon Code</h3>
                <p class="text-sm font-medium text-gray-700 mb-6 dark:text-gray-300">
                    Original charges: <span class="font-bold">Monthly: ${{ $monthly_fee_amount }}</span>, 
                    <span class="font-bold">Half-yearly: ${{ $half_yearly_fee_amount }}</span>, 
                    <span class="font-bold">Yearly: ${{ $yearly_fee_amount }}</span>
                </p>
                <form action="/admin/coupon-code/create" method="POST">
                    @csrf
                    
                    <!-- Hidden fields for calculated prices -->
                    <input type="hidden" name="monthly_fee_amount" id="monthly_fee_amount" value="{{ $monthly_fee_amount }}">
                    <input type="hidden" name="half_yearly_fee_amount" id="half_yearly_fee_amount" value="{{ $half_yearly_fee_amount }}">
                    <input type="hidden" name="yearly_fee_amount" id="yearly_fee_amount" value="{{ $yearly_fee_amount }}">
                    
                    <!-- Coupon Code -->
                    <div class="mb-6">
                        <label for="coupon_code"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Coupon Code *</label>
                        <input type="text" name="coupon_code" id="coupon_code" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Discount Configuration -->
                    <div class="bg-blue-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Discount Configuration</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="type"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Discount Type *</label>
                                <select name="type" id="type" required
                                    class="discount-type-select w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                    data-form-type="add">
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>

                            <div id="addFixedAmountField" class="discount-field add-field">
                                <label for="discount_amount"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Discount Amount ($)</label>
                                <input type="number" name="discount_amount" id="discount_amount" step="0.01" min="0"
                                    class="discount-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                    data-form-type="add">
                            </div>

                            <div id="addPercentageField" class="discount-field add-field hidden">
                                <label for="discount_percentage"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Discount Percentage (%)</label>
                                <input type="number" name="discount_percentage" id="discount_percentage" step="0.01"
                                    min="0" max="100"
                                    class="discount-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                    data-form-type="add">
                            </div>
                        </div>

                        <!-- Display calculated prices -->
                        <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Calculated Prices:</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Monthly:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-2" id="display_monthly">$0.00</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Half-yearly:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-2" id="display_half_yearly">$0.00</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Yearly:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-2" id="display_yearly">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" id="closeAddModalBtn"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200 shadow-lg">
                            Create Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Coupon Modal -->
    <div id="editModal" class="fixed inset-0 bg-[#00000035] overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-4 md:top-10 mx-auto p-6 w-full max-w-6xl shadow-2xl rounded-lg bg-white dark:bg-gray-800 mb-10">
            <div class="max-h-[85vh] overflow-y-auto pr-2">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Edit Coupon Code</h3>
                <p class="text-sm font-medium text-gray-700 mb-6 dark:text-gray-300">
                    Original charges: <span class="font-bold">Monthly: ${{ $monthly_fee_amount }}</span>, 
                    <span class="font-bold">Half-yearly: ${{ $half_yearly_fee_amount }}</span>, 
                    <span class="font-bold">Yearly: ${{ $yearly_fee_amount }}</span>
                </p>
                <form action="/admin/coupon-code/update" method="POST">
                    @csrf
                    <input type="hidden" name="coupon_id" id="edit_coupon_id">
                    
                    <!-- Hidden fields for calculated prices -->
                    <input type="hidden" name="monthly_fee_amount" id="edit_monthly_fee_amount">
                    <input type="hidden" name="half_yearly_fee_amount" id="edit_half_yearly_fee_amount">
                    <input type="hidden" name="yearly_fee_amount" id="edit_yearly_fee_amount">
                    
                    <!-- Coupon Code -->
                    <div class="mb-6">
                        <label for="edit_coupon_code"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Coupon Code *</label>
                        <input type="text" name="coupon_code" id="edit_coupon_code" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>

                    <!-- Discount Configuration -->
                    <div class="bg-blue-50 dark:bg-gray-700 p-6 rounded-lg mb-6">
                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Discount Configuration</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="edit_type"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Discount Type *</label>
                                <select name="type" id="edit_type" required
                                    class="discount-type-select w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                    data-form-type="edit">
                                    <option value="fixed">Fixed Amount</option>
                                    <option value="percentage">Percentage</option>
                                </select>
                            </div>

                            <div id="editFixedAmountField" class="discount-field edit-field">
                                <label for="edit_discount_amount"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Discount Amount ($)</label>
                                <input type="number" name="discount_amount" id="edit_discount_amount" step="0.01" min="0"
                                    class="discount-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                    data-form-type="edit">
                            </div>

                            <div id="editPercentageField" class="discount-field edit-field hidden">
                                <label for="edit_discount_percentage"
                                    class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Discount Percentage (%)</label>
                                <input type="number" name="discount_percentage" id="edit_discount_percentage" step="0.01"
                                    min="0" max="100"
                                    class="discount-input w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-600 dark:text-white"
                                    data-form-type="edit">
                            </div>
                        </div>

                        <!-- Display calculated prices -->
                        <div class="mt-6 p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                            <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Calculated Prices:</h5>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Monthly:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-2" id="edit_display_monthly">$0.00</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Half-yearly:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-2" id="edit_display_half_yearly">$0.00</span>
                                </div>
                                <div>
                                    <span class="text-gray-600 dark:text-gray-400">Yearly:</span>
                                    <span class="font-bold text-gray-900 dark:text-white ml-2" id="edit_display_yearly">$0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="button" id="closeEditModalBtn"
                            class="px-6 py-3 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-lg transition-colors duration-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors duration-200 shadow-lg">
                            Update Coupon
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-[#e7000b35] overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800 dark:border-gray-700">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-900/30">
                    <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mt-3">Delete Coupon Code</h3>
                <div class="mt-2 px-4 py-3">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Are you sure you want to delete coupon code "<span id="deleteCouponCode" class="font-semibold"></span>"? This action cannot be undone.
                    </p>
                </div>
                <div class="flex justify-center space-x-3 mt-6">
                    <button type="button" id="closeDeleteModalBtn"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 hover:bg-gray-300 rounded-md transition-colors duration-200 dark:bg-gray-600 dark:text-gray-300 dark:hover:bg-gray-500">
                        Cancel
                    </button>
                    <form id="deleteForm" action="/admin/coupon-code/delete" method="POST" class="inline">
                        @csrf
                        <input type="hidden" name="coupon_id" id="delete_coupon_id">
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-md transition-colors duration-200">
                            Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        // Original prices from server
        const originalPrices = {
            monthly: {{ $monthly_fee_amount }},
            half_yearly: {{ $half_yearly_fee_amount }},
            yearly: {{ $yearly_fee_amount }}
        };

        $(document).ready(function() {
            // Initialize tabs
            $(".settings_tab").click();
            $(".coupon_codes").addClass("active_tab");

            // Initialize discount fields for add modal
            toggleDiscountFields('add');
            calculatePrices('add');

            // Modal event handlers
            $('#openAddModalBtn').click(openAddModal);
            $('#closeAddModalBtn').click(closeAddModal);
            $('#closeEditModalBtn').click(closeEditModal);
            $('#closeDeleteModalBtn').click(closeDeleteModal);

            // Edit coupon buttons
            $(document).on('click', '.edit-coupon-btn', function() {
                const couponId = $(this).data('coupon-id');
                const couponCode = $(this).data('coupon-code');
                const monthly_fee_amount = $(this).data('monthly_fee_amount');
                const half_yearly_fee_amount = $(this).data('half_yearly_fee_amount');
                const yearly_fee_amount = $(this).data('yearly_fee_amount');
                const type = $(this).data('type');
                const discountAmount = $(this).data('discount-amount');
                const discountPercentage = $(this).data('discount-percentage');
                
                openEditModal(couponId, couponCode, monthly_fee_amount, half_yearly_fee_amount, yearly_fee_amount, type, discountAmount, discountPercentage);
            });

            // Delete coupon buttons
            $(document).on('click', '.delete-coupon-btn', function() {
                const couponId = $(this).data('coupon-id');
                const couponCode = $(this).data('coupon-code');
                openDeleteModal(couponId, couponCode);
            });

            // Discount type change handlers
            $(document).on('change', '.discount-type-select', function() {
                const formType = $(this).data('form-type');
                toggleDiscountFields(formType);
                calculatePrices(formType);
            });

            // Discount input change handlers
            $(document).on('input', '.discount-input', function() {
                const formType = $(this).data('form-type');
                calculatePrices(formType);
            });

            // Close modals when clicking outside
            $(window).click(function(event) {
                if ($(event.target).is('#addModal')) {
                    closeAddModal();
                }
                if ($(event.target).is('#editModal')) {
                    closeEditModal();
                }
                if ($(event.target).is('#deleteModal')) {
                    closeDeleteModal();
                }
            });
        });

        // Calculate prices based on discount
        function calculatePrices(formType) {
            const prefix = formType === 'add' ? '' : 'edit_';
            const type = $(`#${prefix}type`).val();
            
            let monthlyPrice = originalPrices.monthly;
            let halfYearlyPrice = originalPrices.half_yearly;
            let yearlyPrice = originalPrices.yearly;

            if (type === 'fixed') {
                const discountAmount = parseFloat($(`#${prefix}discount_amount`).val()) || 0;
                
                monthlyPrice = Math.max(0, originalPrices.monthly - discountAmount);
                halfYearlyPrice = Math.max(0, originalPrices.half_yearly - discountAmount);
                yearlyPrice = Math.max(0, originalPrices.yearly - discountAmount);
            } else if (type === 'percentage') {
                const discountPercentage = parseFloat($(`#${prefix}discount_percentage`).val()) || 0;
                const discountMultiplier = (100 - discountPercentage) / 100;
                
                monthlyPrice = originalPrices.monthly * discountMultiplier;
                halfYearlyPrice = originalPrices.half_yearly * discountMultiplier;
                yearlyPrice = originalPrices.yearly * discountMultiplier;
            }

            // Update the hidden input fields
            $(`#${prefix}monthly_fee_amount`).val(monthlyPrice.toFixed(2));
            $(`#${prefix}half_yearly_fee_amount`).val(halfYearlyPrice.toFixed(2));
            $(`#${prefix}yearly_fee_amount`).val(yearlyPrice.toFixed(2));

            // Update the display fields
            $(`#${prefix}display_monthly`).text('$' + monthlyPrice.toFixed(2));
            $(`#${prefix}display_half_yearly`).text('$' + halfYearlyPrice.toFixed(2));
            $(`#${prefix}display_yearly`).text('$' + yearlyPrice.toFixed(2));
        }

        // Modal functions
        function openAddModal() {
            // Reset form and show appropriate fields
            $('#addModal form')[0].reset();
            toggleDiscountFields('add');
            calculatePrices('add');
            $('#addModal').removeClass('hidden');
        }

        function closeAddModal() {
            $('#addModal').addClass('hidden');
        }

        function openEditModal(id, code, monthly_fee_amount, half_yearly_fee_amount, yearly_fee_amount, type, amount, percentage) {
            $('#edit_coupon_id').val(id);
            $('#edit_coupon_code').val(code);
            $('#edit_type').val(type);
            $('#edit_discount_amount').val(amount);
            $('#edit_discount_percentage').val(percentage);

            // Show/hide appropriate fields based on type
            toggleDiscountFields('edit');
            calculatePrices('edit');

            $('#editModal').removeClass('hidden');
        }

        function closeEditModal() {
            $('#editModal').addClass('hidden');
        }

        function openDeleteModal(id, code) {
            $('#delete_coupon_id').val(id);
            $('#deleteCouponCode').text(code);
            $('#deleteModal').removeClass('hidden');
        }

        function closeDeleteModal() {
            $('#deleteModal').addClass('hidden');
        }

        function toggleDiscountFields(formType) {
            const prefix = formType === 'add' ? '' : 'edit_';
            const type = $(`#${prefix}type`).val();
            const fixedField = $(`#${formType === 'add' ? 'add' : 'edit'}FixedAmountField`);
            const percentageField = $(`#${formType === 'add' ? 'add' : 'edit'}PercentageField`);

            // Hide all fields first
            $(`.${formType === 'add' ? 'add' : 'edit'}-field`).addClass('hidden');

            if (type === 'fixed') {
                fixedField.removeClass('hidden');
                // Make fixed amount required, percentage not required
                $(`#${prefix}discount_amount`).prop('required', true);
                $(`#${prefix}discount_percentage`).prop('required', false).val('');
            } else {
                percentageField.removeClass('hidden');
                // Make percentage required, fixed amount not required
                $(`#${prefix}discount_amount`).prop('required', false).val('');
                $(`#${prefix}discount_percentage`).prop('required', true);
            }
        }

        function toggleInfo() {
            $(".coupon_desc").toggleClass("hidden");
        }
    </script>
@endsection