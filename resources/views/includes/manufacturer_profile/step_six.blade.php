<div class="step-content px-4 py-8 lg:px-8 {{ $step == 6 ? 'active' : '' }}" data-step="6">
    <h2 class="text-3xl lg:text-[40px] mb-8">Review</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- Company Information Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="company-info" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Company Information</h3>
                        <button type="button" class="edit-btn" data-step="1">
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
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company name (English):</div>
                        <div class="review-value" id="review-company-name-en">
                            {{ $profile_data->company_name_en ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company name (Korean):</div>
                        <div class="review-value" id="review-company-name-ko">
                            {{ $profile_data->company_name_ko ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company address (English):</div>
                        <div class="review-value" id="review-company-address-en">
                            {{ $profile_data->company_address_en ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company address (Korean):</div>
                        <div class="review-value" id="review-company-address-ko">
                            {{ $profile_data->company_address_ko ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Year established:</div>
                        <div class="review-value" id="review-year-established">
                            {{ $profile_data->year_established ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Business Registration Number:</div>
                        <div class="review-value" id="review-business-registration-number">
                            {{ $profile_data->business_registration_number ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Business Registration License:</div>
                        <div class="review-value">
                            @if ($profile_data->business_registration_license)
                                <img id="review-business-license"
                                    class="file-preview-small max-w-[100px] rounded border border-gray-200"
                                    src="{{ asset( $profile_data->business_registration_license) }}"
                                    alt="Business License">
                            @else
                                <img id="review-business-license"
                                    class="file-preview-small max-w-[100px] rounded border border-gray-200"
                                    src="" alt="Business License" >
                            @endif
                            <span
                                id="review-business-license-text">{{ $profile_data->business_registration_license ? '' : '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Primary Contact Name:</div>
                        <div class="review-value" id="review-contact-name">{{ $profile_data->contact_name ?? '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Email address:</div>
                        <div class="review-value" id="review-contact-email">{{ $profile_data->contact_email ?? '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Position:</div>
                        <div class="review-value" id="review-contact-position">
                            {{ $profile_data->contact_position ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Phone Number:</div>
                        <div class="review-value" id="review-contact-phone">{{ $profile_data->contact_phone ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Profile Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="business-profile" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Business Profile</h3>
                        <button type="button" class="edit-btn" data-step="2">
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
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Business Types:</div>
                        <div class="review-value" id="review-business-type">{{ $profile_data->business_type ?? '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Industry Categories:</div>
                        <div class="review-value" id="review-industry-category">
                            {{ $profile_data->industry_category ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Product Information Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="product-info" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Product Information</h3>
                        <button type="button" class="edit-btn" data-step="3">
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
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Main Product Categories:</div>
                        <div class="review-value capitalize" id="review-main-product-category">
                            {{ $profile_data->main_product_category ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                        <div class="review-label">Key Products:</div>
                        <div class="review-value">
                            <ul class="review-list space-y-3" id="review-products">
                                @if ($profile_data->products && count($profile_data->products) > 0)
                                    @foreach ($profile_data->products as $product)
                                        <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span
                                                class="font-medium">{{ $product['name'] ?? 'Unnamed Product' }}</span>
                                            @if (isset($product['image']))
                                                <img src="{{ asset( $product['image']) }}"
                                                    class="file-preview-small max-w-[100px] h-auto rounded border border-gray-200"
                                                    alt="{{ $product['name'] ?? 'Product' }}">
                                            @else
                                                <span class="text-xs text-gray-500">No image</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li class="no-data">No products added</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust & Verification Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="trust-verification" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Trust & Verifications</h3>
                        <button type="button" class="edit-btn" data-step="4">
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
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                        <div class="review-label">Certifications:</div>
                        <div class="review-value">
                            <ul class="review-list space-y-3" id="review-certifications">
                                @if ($profile_data->certifications && count($profile_data->certifications) > 0)
                                    @foreach ($profile_data->certifications as $certification)
                                        <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span
                                                class="font-medium">{{ $certification['name'] ?? 'Unnamed Certification' }}</span>
                                            @if (isset($certification['document']))
                                                <img src="{{ asset( $certification['document']) }}"
                                                    class="file-preview-small max-w-[100px] h-auto rounded border border-gray-200"
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
                    <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                        <div class="review-label">Factory Pictures:</div>
                        <div class="review-value">
                            <div class="review-image-grid grid grid-cols-2 md:grid-cols-3 gap-4"
                                id="review-factory-pictures">
                                @if ($profile_data->factory_pictures && count($profile_data->factory_pictures) > 0)
                                    @foreach ($profile_data->factory_pictures as $picture)
                                        <div class="review-image-item">
                                            @if (isset($picture['image']))
                                                <img src="{{ asset( $picture['image']) }}"
                                                    class="w-full h-48 object-cover rounded border border-gray-200"
                                                    alt="{{ $picture['title'] ?? 'Factory Picture' }}">
                                                <div class="review-image-title text-sm text-gray-600 mt-2">
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
                </div>
            </div>

            <!-- Declaration Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="declaration" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Declaration</h3>
                        <button type="button" class="edit-btn" data-step="5">
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
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Agreed to Terms:</div>
                        <div class="review-value" id="review-agree-terms">
                            {{ $profile_data->agree_terms ? 'Yes' : 'No' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Consent to Background Check:</div>
                        <div class="review-value" id="review-consent-background-check">
                            {{ $profile_data->consent_background_check ? 'Yes' : 'No' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Digital Signature:</div>
                        <div class="review-value" id="review-digital-signature">
                            {{ $profile_data->digital_signature ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <hr class="my-12 text-gray-400">
        </div>

        <div>
            <div class="help-box sticky top-24">
                <div class="w-24 h-24 from-blue-400 to-blue-600 rounded-xl flex items-center mb-4 justify-left">
                    <img src="/assets/images/question_img.png" alt="" class="w-full">
                </div>
                <h4 class="text-lg font-semibold mb-2">Need help filling this out?</h4>
                <p class="text-sm text-gray-600">
                    Feel free to reach out to
                    <a href="mailto:{{ $contact_mail }}">{{ $contact_mail }}</a>
                </p>
            </div>
        </div>
    </div>

    <div class="grid lg:flex justify-center gap-4 items-center relative mt-8">
        <div class="text-sm text-gray-500 lg:absolute left-0 hidden">
            <svg class="inline w-4 h-4 mr-1 animate-spin hidden" id="saving_indicator" fill="none"
                stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                </path>
            </svg>
            <span id="save_text">Auto-saved</span>
        </div>
        <button type="button"
            class="prev-btn text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition border border-blue-600">
            ← Previous
        </button>
        <button type="submit"
            class="bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
            Confirm & apply →
        </button>
    </div>
</div>
