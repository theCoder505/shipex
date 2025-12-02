<div class="step-content px-4 py-8 lg:px-8">
    <h2 class="text-3xl lg:text-[40px] mb-8">Review & Submit</h2>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- Company Information Review -->
            <div class="review-section collapsed">
                <div class="review-header" onclick="toggleAccordion(this)">
                    <div class="flex items-center gap-3">
                        <h3 class="review-title">Company Information</h3>
                        <button type="button" class="edit-btn"
                            onclick="window.location.href='{{ route('manufacturer.application.step', ['step' => 1]) }}'">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Your Name:</div>
                            <div class="review-value" id="review-name">
                                {{ $profile_data->name ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Company name (English):</div>
                            <div class="review-value" id="review-company-name-en">
                                {{ $profile_data->company_name_en ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Company name (Korean):</div>
                            <div class="review-value" id="review-company-name-ko">
                                {{ $profile_data->company_name_ko ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Company address (English):</div>
                            <div class="review-value" id="review-company-address-en">
                                {{ $profile_data->company_address_en ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Company address (Korean):</div>
                            <div class="review-value" id="review-company-address-ko">
                                {{ $profile_data->company_address_ko ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Year established:</div>
                            <div class="review-value" id="review-year-established">
                                {{ $profile_data->year_established ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Number of Employees:</div>
                            <div class="review-value" id="review-number-of-employees">
                                {{ $profile_data->number_of_employees ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Website:</div>
                            <div class="review-value" id="review-website">
                                {{ $profile_data->website ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Business Introduction:</div>
                            <div class="review-value" id="review-business-introduction">
                                {{ Str::limit($profile_data->business_introduction ?? 'Not provided', 100) }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Company Logo:</div>
                            <div class="review-value">
                                @if ($profile_data->company_logo)
                                    <img src="{{ asset($profile_data->company_logo) }}" class="file-preview-small"
                                        alt="Company Logo">
                                @else
                                    <span class="text-gray-500">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Primary Contact Name:</div>
                            <div class="review-value" id="review-contact-name">
                                {{ $profile_data->contact_name ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Position:</div>
                            <div class="review-value" id="review-contact-position">
                                {{ $profile_data->contact_position ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Email Address:</div>
                            <div class="review-value" id="review-contact-email">
                                {{ $profile_data->contact_email ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Phone Number:</div>
                            <div class="review-value" id="review-contact-phone">
                                {{ $profile_data->contact_phone ?? 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Profile Review -->
            <div class="review-section collapsed">
                <div class="review-header" onclick="toggleAccordion(this)">
                    <div class="flex items-center gap-3">
                        <h3 class="review-title">Business Profile</h3>
                        <button type="button" class="edit-btn"
                            onclick="window.location.href='{{ route('manufacturer.application.step', ['step' => 2]) }}'">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Business Type:</div>
                            <div class="review-value" id="review-business-type">
                                {{ $profile_data->business_type ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Industry Category:</div>
                            <div class="review-value" id="review-industry-category">
                                {{ $profile_data->industry_category ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Business Registration Number:</div>
                            <div class="review-value" id="review-business-registration-number">
                                {{ $profile_data->business_registration_number ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Business Registration License:</div>
                            <div class="review-value">
                                @if ($profile_data->business_registration_license)
                                    <img src="{{ asset($profile_data->business_registration_license) }}"
                                        class="file-preview-small" alt="Business License">
                                @else
                                    <span class="text-gray-500">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Export Experience:</div>
                            <div class="review-value" id="review-export-experience">
                                @if ($profile_data->export_experience == 'yes')
                                    Yes ({{ $profile_data->export_years ?? '0' }} years)
                                @else
                                    No
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Information Review -->
            <div class="review-section collapsed">
                <div class="review-header" onclick="toggleAccordion(this)">
                    <div class="flex items-center gap-3">
                        <h3 class="review-title">Product Information</h3>
                        <button type="button" class="edit-btn"
                            onclick="window.location.href='{{ route('manufacturer.application.step', ['step' => 3]) }}'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Main Product Category:</div>
                            <div class="review-value capitalize" id="review-main-product-category">
                                {{ $profile_data->main_product_category ?? 'Not provided' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                            <div class="review-label">Key Products:</div>
                            <div class="review-value">
                                @if ($profile_data->products && count($profile_data->products) > 0)
                                    <ul class="review-list" id="review-products">
                                        @foreach ($profile_data->products as $product)
                                            <li
                                                class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span
                                                    class="font-medium">{{ $product['name'] ?? 'Unnamed Product' }}</span>
                                                @if (isset($product['image']))
                                                    <img src="{{ asset($product['image']) }}"
                                                        class="file-preview-small"
                                                        alt="{{ $product['name'] ?? 'Product' }}">
                                                @else
                                                    <span class="text-xs text-gray-500">No image</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <li class="no-data">No products added</li>
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Production Capacity:</div>
                            <div class="review-value" id="review-production-capacity">
                                {{ $profile_data->production_capacity ?? '0' }}
                                {{ $profile_data->production_capacity_unit ?? '' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Minimum Order Quantity (MOQ):</div>
                            <div class="review-value" id="review-moq">
                                {{ $profile_data->moq ?? '0' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                            <div class="review-label">Certifications:</div>
                            <div class="review-value">
                                <ul class="review-list" id="review-certifications">
                                    @if ($profile_data->certifications && count($profile_data->certifications) > 0)
                                        @foreach ($profile_data->certifications as $certification)
                                            <li
                                                class="flex justify-between items-center py-2 border-b border-gray-100">
                                                <span
                                                    class="font-medium">{{ $certification['name'] ?? 'Unnamed Certification' }}</span>
                                                @if (isset($certification['document']))
                                                    <img src="{{ asset($certification['document']) }}"
                                                        class="file-preview-small"
                                                        alt="{{ $certification['name'] ?? 'Certification' }}">
                                                @else
                                                    <span class="text-xs text-gray-500">No document</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    @else
                                        <li class="no-data">No certifications added</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Patents & Proprietary Technology:</div>
                            <div class="review-value" id="review-has-patents">
                                {{ $profile_data->has_patents == 'yes' ? 'Yes' : 'No' }}
                            </div>
                        </div>
                        @if ($profile_data->has_patents == 'yes' && $profile_data->patents && count($profile_data->patents) > 0)
                            <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                                <div class="review-label">Patent Details:</div>
                                <div class="review-value">
                                    <ul class="review-list" id="review-patents">
                                        @foreach ($profile_data->patents as $index => $patent)
                                            <li class="p-3 bg-gray-50 rounded-lg">
                                                <div class="font-medium text-gray-900 mb-2">Patent {{ $index + 1 }}
                                                </div>
                                                <p class="text-sm text-gray-700 mb-2">
                                                    {{ $patent['description'] ?? 'No description' }}</p>
                                                @if (isset($patent['document']))
                                                    @if (Str::endsWith($patent['document'], ['.jpg', '.jpeg', '.png']))
                                                        <img src="{{ asset($patent['document']) }}"
                                                            class="file-preview-small" alt="Patent Document">
                                                    @else
                                                        <div class="flex items-center gap-2 text-blue-600">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                                </path>
                                                            </svg>
                                                            <span class="text-sm">Document attached</span>
                                                        </div>
                                                    @endif
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Trust & Verification Review -->
            <div class="review-section collapsed">
                <div class="review-header" onclick="toggleAccordion(this)">
                    <div class="flex items-center gap-3">
                        <h3 class="review-title">Trust & Verification</h3>
                        <button type="button" class="edit-btn"
                            onclick="window.location.href='{{ route('manufacturer.application.step', ['step' => 4]) }}'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Quality Management System:</div>
                            <div class="review-value" id="review-has-qms">
                                {{ $profile_data->has_qms == 'yes' ? 'Yes' : 'No' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Factory Audit Available:</div>
                            <div class="review-value" id="review-factory-audit-available">
                                {{ $profile_data->factory_audit_available == 'yes' ? 'Yes' : ($profile_data->factory_audit_available == 'no' ? 'No' : 'Not specified') }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Compliance Standards:</div>
                            <div class="review-value" id="review-standards">
                                @if ($profile_data->standards && count($profile_data->standards) > 0)
                                    {{ implode(', ', $profile_data->standards) }}
                                @else
                                    Not specified
                                @endif
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                            <div class="review-label">Factory Pictures:</div>
                            <div class="review-value">
                                <div class="review-image-grid" id="review-factory-pictures">
                                    @if ($profile_data->factory_pictures && count($profile_data->factory_pictures) > 0)
                                        @foreach ($profile_data->factory_pictures as $picture)
                                            <div class="review-image-item">
                                                @if (isset($picture['image']))
                                                    <img src="{{ asset($picture['image']) }}"
                                                        class="w-full h-48 object-cover rounded border border-gray-200"
                                                        alt="{{ $picture['title'] ?? 'Factory Picture' }}">
                                                    <div class="review-image-title">
                                                        {{ $picture['title'] ?? 'Untitled' }}
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="no-data">No factory pictures added</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Product Catalogue:</div>
                            <div class="review-value">
                                @if ($profile_data->catalogue)
                                    <div class="flex items-center gap-2 text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                            </path>
                                        </svg>
                                        <span class="text-sm">Catalogue attached</span>
                                    </div>
                                @else
                                    <span class="text-gray-500">Not uploaded</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Declaration Review -->
            <div class="review-section collapsed">
                <div class="review-header" onclick="toggleAccordion(this)">
                    <div class="flex items-center gap-3">
                        <h3 class="review-title">Declaration</h3>
                        <button type="button" class="edit-btn"
                            onclick="window.location.href='{{ route('manufacturer.application.step', ['step' => 5]) }}'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content">
                    <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Agreed to Terms of Service:</div>
                            <div class="review-value" id="review-agree-terms">
                                {{ $profile_data->agree_terms ? 'Yes' : 'No' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Consent to Background Check:</div>
                            <div class="review-value" id="review-consent-background-check">
                                {{ $profile_data->consent_background_check ? 'Yes' : 'No' }}
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-2 w-full">
                            <div class="review-label">Digital Signature:</div>
                            <div class="review-value" id="review-digital-signature">
                                {{ $profile_data->digital_signature ?? 'Not provided' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-12 text-gray-400">

            <!-- Final Submission Section -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <h4 class="text-lg font-semibold text-blue-800 mb-3">Ready to Submit?</h4>
                <p class="text-blue-700 mb-4">
                    Please review all your information above. Once submitted, your application will be sent for review.
                    You will be notified by email when your application is approved.
                </p>
                <div class="flex items-start">
                    <input type="checkbox" id="confirm_accuracy" required class="mt-1 mr-3 h-5 w-5 text-blue-600">
                    <label for="confirm_accuracy" class="text-blue-800">
                        I confirm that all information provided is accurate and complete. I understand that
                        providing false information may result in rejection of my application.
                    </label>
                </div>
            </div>

            <form action="{{ route('manufacturer.application.final.submit') }}" method="POST" id="finalSubmitForm">
                @csrf
                @method('POST')
            </form>
        </div>

        <div>
            <div class="help-box sticky top-24">
                <div class="w-24 h-24 from-blue-400 to-blue-600 rounded-xl flex items-center mb-4 justify-left">
                    <img src="/assets/images/question_img.png" alt="" class="w-full">
                </div>
                <h4 class="text-lg font-semibold mb-2">Final Review</h4>
                <p class="text-sm text-gray-600 mb-3">
                    Please carefully review all information before submitting.
                    You can edit any section by clicking the "Edit" button.
                </p>
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> After submission, your application will be reviewed within 3-5 business
                        days.
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid lg:flex justify-center gap-4 items-center relative mt-8">
        <a href="/manufacturer/application/step/5"
            class="prev-btn text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition border border-blue-600">
            ← Previous
        </a>
        <button type="button" onclick="submitFinalForm()"
            class="bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            Submit Application →
        </button>
    </div>
</div>

<script>
    function toggleAccordion(header) {
        $(header).parent().toggleClass('collapsed');
    }

    function submitFinalForm() {
        const confirmCheckbox = document.getElementById('confirm_accuracy');
        if (!confirmCheckbox.checked) {
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: "Please confirm that all information is accurate before submitting.",
                timer: 4000,
                showConfirmButton: true
            });
            confirmCheckbox.focus();
            return;
        }

        // Show loading state
        const submitBtn = document.querySelector('button[onclick="submitFinalForm()"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Submitting...
        `;
        submitBtn.disabled = true;

        // Submit the form
        document.getElementById('finalSubmitForm').submit();
    }

    // Prevent edit button clicks from toggling accordion
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });
    });
</script>
