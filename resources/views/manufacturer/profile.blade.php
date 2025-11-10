@extends('layouts.surface.app')

@section('title', 'Your Profile Setup')

@section('style')
    <link rel="stylesheet" href="/assets/css/manufacturer_profile.css">
@endsection

@section('content')
    <div class="hero_section my-4 px-4 lg:px-8 max-w-[1600px] mx-auto">
        <div class="w-full flex gap-4 justify-between items-center">
            <div>
                <div class="text-[#46484D] text-xl lg:text-[40px]">Personal Space</div>
                <div class="text-[#46484D]">{{ $profile_data->name }}</div>
            </div>
            <div>
                @php $status = $profile_data->status ?? null; @endphp

                @if ($status == 1)
                    <button type="button" class="px-4 py-2 rounded-full border border-yellow-300 bg-yellow-50 text-yellow-800 text-sm"
                        disabled title="Your profile is pending admin approval">
                        Waiting Admin Approval
                    </button>
                @elseif ($status == 3)
                    <button type="button" class="px-4 py-2 rounded-full border border-red-300 bg-red-50 text-red-700 text-sm" disabled
                        title="Your application was rejected">
                        Rejected Application
                    </button>
                @elseif ($status == 5)
                    <button type="button" class="px-4 py-2 rounded-full border border-green-300 bg-green-50 text-green-800 text-sm"
                        disabled title="Your profile has been approved">
                        Approved Profile
                    </button>
                @else
                    <div class="text-[#46484D]">Verification Status: {{ $profile_data->status }}</div>
                @endif
            </div>
        </div>


        <div
            class="about_grid flex flex-wrap justify-between items-center gap-4 pt-3 px-4 lg:px-0 lg:border-b-2 border-[#E4E4E4] bg-white my-4">
            <div class="left_grid flex flex-wrap">
                <button type="button" onclick="personalTab(this)" data-id="Profile"
                    class="about_grid_item py-2 px-4 cursor-pointer @if ($page_type == 'profile') grid_active @endif">Profile</button>
                <button type="button" onclick="personalTab(this)" data-id="Settings"
                    class="about_grid_item py-2 px-4 cursor-pointer @if ($page_type == 'settings') grid_active @endif">Settings</button>
                <a href="/manufacturer/chats" class="about_grid_item py-2 px-4 cursor-pointer">My Chats</a>
            </div>
        </div>




        <div class="step-content py-4 active">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 personal_space @if ($page_type != 'profile') hidden @endif"
                id="Profile">
                <div class="lg:col-span-2">
                    <!-- Company Information Review -->
                    <div class="review-section">
                        <div class="review-header" data-toggle="company-info" onclick="toggleAccordion(this)">
                            <div class="flex gap-2 flex-wrap">
                                <h3 class="review-title">Company Information</h3>
                                <a class="edit-btn" href="/manufacturer/application?step=1">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
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
                                <div class="review-value">{{ $profile_data->company_name_en ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Company name (Korean):</div>
                                <div class="review-value">{{ $profile_data->company_name_ko ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Company address (English):</div>
                                <div class="review-value">{{ $profile_data->company_address_en ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Company address (Korean):</div>
                                <div class="review-value">{{ $profile_data->company_address_ko ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Year established:</div>
                                <div class="review-value">{{ $profile_data->year_established ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Business Registration Number:</div>
                                <div class="review-value">{{ $profile_data->business_registration_number ?? '-' }}</div>
                            </div>

                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Business Registration License:</div>
                                <div class="review-value">
                                    @if ($profile_data->business_registration_license)
                                        <img src="{{ asset($profile_data->business_registration_license) }}"
                                            class="file-preview-small max-w-[200px] rounded border border-gray-200"
                                            alt="Business License">
                                    @else
                                        <span>-</span>
                                    @endif
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Primary Contact Name:</div>
                                <div class="review-value">{{ $profile_data->contact_name ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Email address:</div>
                                <div class="review-value">{{ $profile_data->contact_email ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Position:</div>
                                <div class="review-value">{{ $profile_data->contact_position ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Phone Number:</div>
                                <div class="review-value">{{ $profile_data->contact_phone ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Business Profile Review -->
                    <div class="review-section collapsed">
                        <div class="review-header" data-toggle="business-profile" onclick="toggleAccordion(this)">
                            <div class="flex gap-2 flex-wrap">
                                <h3 class="review-title">Business Profile</h3>
                                <a class="edit-btn" href="/manufacturer/application?step=2">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
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
                                <div class="review-value">{{ $profile_data->business_type ?? '-' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Industry Categories:</div>
                                <div class="review-value">{{ $profile_data->industry_category ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Information Review -->
                    <div class="review-section collapsed">
                        <div class="review-header" data-toggle="product-info" onclick="toggleAccordion(this)">
                            <div class="flex gap-2 flex-wrap">
                                <h3 class="review-title">Product Information</h3>
                                <a class="edit-btn" href="/manufacturer/application?step=3">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
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
                                <div class="review-value capitalize">{{ $profile_data->main_product_category ?? '-' }}
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                                <div class="review-label">Key Products:</div>
                                <div class="review-value">
                                    @if ($profile_data->products && count($profile_data->products) > 0)
                                        <ul class="review-list space-y-3">
                                            @foreach ($profile_data->products as $product)
                                                <li
                                                    class="flex justify-between items-center py-2 border-b border-gray-100">
                                                    <span
                                                        class="font-medium">{{ $product['name'] ?? 'Unnamed Product' }}</span>
                                                    @if (isset($product['image']))
                                                        <img src="{{ asset($product['image']) }}"
                                                            class="file-preview-small max-w-[100px] h-auto rounded border border-gray-200"
                                                            alt="{{ $product['name'] ?? 'Product' }}">
                                                    @else
                                                        <span class="text-xs text-gray-500">No image</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <ul class="review-list">
                                            <li class="no-data">No products added</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Trust & Verification Review -->
                    <div class="review-section collapsed">
                        <div class="review-header" data-toggle="trust-verification" onclick="toggleAccordion(this)">
                            <div class="flex gap-2 flex-wrap">
                                <h3 class="review-title">Trust & Verifications</h3>
                                <a class="edit-btn" href="/manufacturer/application?step=4">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
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
                                    @if ($profile_data->certifications && count($profile_data->certifications) > 0)
                                        <ul class="review-list space-y-3">
                                            @foreach ($profile_data->certifications as $certification)
                                                <li
                                                    class="flex justify-between items-center py-2 border-b border-gray-100">
                                                    <span
                                                        class="font-medium">{{ $certification['name'] ?? 'Unnamed Certification' }}</span>
                                                    @if (isset($certification['document']))
                                                        <img src="{{ asset($certification['document']) }}"
                                                            class="file-preview-small max-w-[100px] h-auto rounded border border-gray-200"
                                                            alt="{{ $certification['name'] ?? 'Certification' }}">
                                                    @else
                                                        <span class="text-xs text-gray-500">No document</span>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <ul class="review-list">
                                            <li class="no-data">No certifications added</li>
                                        </ul>
                                    @endif
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                                <div class="review-label">Factory Pictures:</div>
                                <div class="review-value">
                                    @if ($profile_data->factory_pictures && count($profile_data->factory_pictures) > 0)
                                        <div class="review-image-grid grid grid-cols-2 md:grid-cols-3 gap-4">
                                            @foreach ($profile_data->factory_pictures as $picture)
                                                <div class="review-image-item">
                                                    @if (isset($picture['image']))
                                                        <img src="{{ asset($picture['image']) }}"
                                                            class="w-full h-48 object-cover rounded border border-gray-200"
                                                            alt="{{ $picture['title'] ?? 'Factory Picture' }}">
                                                        <div class="review-image-title text-sm text-gray-600 mt-2">
                                                            {{ $picture['title'] ?? 'Untitled' }}
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="review-image-grid">
                                            <div class="no-data">No factory pictures added</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Declaration Review -->
                    <div class="review-section collapsed">
                        <div class="review-header" data-toggle="declaration" onclick="toggleAccordion(this)">
                            <div class="flex gap-2 flex-wrap">
                                <h3 class="review-title">Declaration</h3>
                                <a class="edit-btn" href="/manufacturer/application?step=5">
                                    <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                        </path>
                                    </svg>
                                    Edit
                                </a>
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
                                <div class="review-value">{{ $profile_data->agree_terms ? 'Yes' : 'No' }}</div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Consent to Background Check:</div>
                                <div class="review-value">{{ $profile_data->consent_background_check ? 'Yes' : 'No' }}
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-2 w-full">
                                <div class="review-label">Digital Signature:</div>
                                <div class="review-value">{{ $profile_data->digital_signature ?? '-' }}</div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-12 text-gray-400">
                </div>

                <div>
                    <div class="sticky top-24">
                        <a href="/manufacturers/{{ $profile_data->company_name_en }}/{{ $profile_data->manufacturer_uid }}"
                            class="float-right hover:text-white bg-blue-50 border border-[#003fb4] text-[#003fb4] flex items-center gap-2 rounded-lg px-4 py-3 hover:bg-[#003FB4] text-center transition-all duration-200">
                            See public profile <i class="fas fa-external-link-alt"></i>
                        </a>
                    </div>
                </div>
            </div>





            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 personal_space @if ($page_type != 'settings') hidden @endif"
                id="Settings">
                <div class="lg:col-span-2 border rounded-lg border-[#BCBCBC] p-6 max-w-[800px]">
                    <div class="flex justify-between gap-4 py-6 border-b border-[#BCBCBC]">
                        <div class="left">
                            <p class="text-xs">Email</p>
                            <p class="text text-[#121212]">{{ $profile_data->email }}</p>
                        </div>
                        <div class="right">
                            <button class="edit-btn" onclick="editEmailModal(this)">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-6 border-b border-[#BCBCBC]">
                        <div class="left">
                            <p class="text-xs">Password</p>
                            <p class="text-lg text-[#121212]">*****************</p>
                        </div>
                        <div class="right">
                            <button class="edit-btn" onclick="editPwdModal(this)">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-6 border-b border-[#BCBCBC]">
                        <div class="left">
                            <p class="text-xs">Default language</p>
                            <p class="text-lg text-[#121212] capitalize">{{ $profile_data->language }}</p>
                        </div>
                        <div class="right">
                            <button class="edit-btn" onclick="editLangModal(this)">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                    </path>
                                </svg>
                                Edit
                            </button>
                        </div>
                    </div>
                    <div class="flex justify-between gap-4 py-6 border-b border-[#BCBCBC]">
                        <button
                            class="border rounded-lg flex items-center gap-4 border-[#D01007] text-[#D01007] hover:text-white hover:bg-[#D01007] bg-red-50 px-4 py-2"
                            onclick="deleteAccountModal(this)">
                            <i class="fa fa-trash"></i>
                            <span>Delete account</span>
                        </button>
                    </div>
                </div>

                <div>
                    <div class="help-box sticky top-24">
                        <div class="w-24 h-24 from-blue-400 to-blue-600 rounded-xl flex items-center mb-4 justify-left">
                            <img src="/assets/images/question_img.png" alt="" class="w-full">
                        </div>
                        <h4 class="text-lg font-semibold mb-2">Need to edit other information?</h4>
                        <a href="/manufacturer/application" class="text-sm text-gray-600 underline">Edit your
                            profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div id="editEmailAddress" class="modal-overlay">
        <div class="modal-content filter_content">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeeditEmailAddress()">
            <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-[#BCBCBC]">
                Editing your email address
            </div>

            <form action="/manufacturer/change-email-address" method="post">
                @csrf

                <div class="py-10 px-6">
                    <div class="text-xs mb-2">Email</div>
                    <input type="email" class="rounded px-4 py-2 border border-[#BCBCBC]" name="email_addr"
                        placeholder="example@domain.com" required>
                </div>

                <div class="links grid lg:flex justify-end items-center gap-4 lg:gap-8 border-t border-[#BCBCBC] p-4">
                    <button class="text_primary text-center px-4 py-2 font-semibold" onclick="closeeditEmailAddress()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#003FB4] text-white rounded-lg text-base font-medium hover:bg-[#002d85] transition-colors">
                        Confirm email change
                    </button>
                </div>
            </form>
        </div>
    </div>





    <div id="EditPassChange" class="modal-overlay">
        <div class="modal-content filter_content">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeEditPassChange()">
            <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-[#BCBCBC]">
                Editing your account password
            </div>

            <form action="/manufacturer/change-account-password" method="post">
                @csrf

                <div class="py-10 px-6">
                    <div class="mb-1 relative max-w-[400px]">
                        <label for="password" class="text-sm text-gray-700 mb-2 block">Password</label>
                        <input type="password" id="password" name="password" placeholder="********"
                            class="border w-full border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                            required="" value="">
                        <span class="absolute right-3 top-[38px] cursor-pointer text-gray-500"
                            onclick="passwordToggle(this)">
                            <i class="fa fa-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="links grid lg:flex justify-end items-center gap-4 lg:gap-8 border-t border-[#BCBCBC] p-4">
                    <button class="text_primary text-center px-4 py-2 font-semibold" onclick="closeEditPassChange()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#003FB4] text-white rounded-lg text-base font-medium hover:bg-[#002d85] transition-colors">
                        Confirm new password
                    </button>
                </div>
            </form>
        </div>
    </div>




    <div id="EditLangChange" class="modal-overlay">
        <div class="modal-content filter_content">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeEditLangChange()">
            <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-[#BCBCBC]">
                Editing your default language
            </div>

            <form action="/manufacturer/change-language-selection" method="post">
                @csrf

                <div class="py-10 px-6 grid gap-6">
                    <div class="rounded-lg p-4 flex gap-2 bg-[#DEEFFF] lg:w-[400px]">
                        <i class="fa fa-info-circle text-[#0B45B9]"></i>
                        <p class="text-[#0B45B9]">
                            This information will allow AI to automatically translate conversations into your default
                            language. It won't change the language of the interface.
                        </p>
                    </div>

                    <div class="mb-1">
                        <label for="language" class="text-sm text-gray-700 mb-2 block">Language</label>
                        <select id="language" name="language"
                            class="lg:w-[400px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                            required>
                            <option value="korean">Korean</option>
                            <option value="english">English</option>
                            <option value="spanish">Spanish</option>
                            <option value="french">French</option>
                            <option value="german">German</option>
                            <option value="italian">Italian</option>
                            <option value="portuguese">Portuguese</option>
                            <option value="russian">Russian</option>
                            <option value="japanese">Japanese</option>
                            <option value="chinese">Chinese (Simplified)</option>
                            <option value="chinese-traditional">Chinese (Traditional)</option>
                            <option value="arabic">Arabic</option>
                            <option value="hindi">Hindi</option>
                            <option value="bengali">Bengali</option>
                            <option value="dutch">Dutch</option>
                            <option value="turkish">Turkish</option>
                            <option value="polish">Polish</option>
                            <option value="vietnamese">Vietnamese</option>
                            <option value="thai">Thai</option>
                            <option value="indonesian">Indonesian</option>
                        </select>
                    </div>
                </div>

                <div class="links grid lg:flex justify-end items-center gap-4 lg:gap-8 border-t border-[#BCBCBC] p-4">
                    <button type="button" class="text-[#003FB4] text-center px-4 py-2 font-semibold"
                        onclick="closeEditLangChange()">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-8 py-3 bg-[#003FB4] text-white rounded-lg text-base font-medium hover:bg-[#002d85] transition-colors">
                        Confirm language change
                    </button>
                </div>
            </form>
        </div>
    </div>




    <div id="deleteAccountModal" class="modal-overlay">
        <div class="modal-conten create_modal">
            <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeDeleteAccountModal()">
            <img src="/assets/images/log-out.png" alt="User" class="w-24 h-24 rounded-lg block mx-auto">
            <div class="popup_text text-xl lg:text-[40px] my-6 text-center">
                Are you sure you want to </br> delete your account?
            </div>

            <p class="mb-8 text-[#46484D]">
                You won’t be able to access your profile or information again once you delete your account. If you have
                question, please reach out to <a href="mailto:{{ $contact_mail }}">{{ $contact_mail }}</a>
            </p>

            <div class="links grid lg:flex justify-center items-center gap-4 lg:gap-8">
                <button class="text_primary text-center px-4 py-2 font-semibold"
                    onclick="closeDeleteAccountModal()">Cancel</button>
                <form action="/manufacturer/delete-account" method="post">
                    @csrf
                    <button type="submit"
                        class="bg-[#FEE0DE] rounded-lg px-4 py-3 cursor-pointer text-[#D01007] hover:text-white hover:bg-[#D01007]">
                        Delete account
                    </button>
                </form>
            </div>
        </div>
    </div>


    </div>
@endsection

@section('scripts')
    <script>
        function toggleAccordion(reviewSection) {
            $(reviewSection).parent().toggleClass("collapsed");
        }


        function personalTab(passedThis) {
            $(".about_grid_item").removeClass("grid_active");
            $(passedThis).addClass("grid_active");
            let dataID = $(passedThis).attr("data-id");
            $(".personal_space").addClass("hidden");
            $("#" + dataID).removeClass("hidden");
        }



        function editEmailModal() {
            document.getElementById('editEmailAddress').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeeditEmailAddress() {
            document.getElementById('editEmailAddress').style.display = 'none';
            document.body.style.overflow = 'auto';
        }


        function editPwdModal() {
            document.getElementById('EditPassChange').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }


        function closeEditPassChange() {
            document.getElementById('EditPassChange').style.display = 'none';
            document.body.style.overflow = 'auto';
        }


        function editLangModal() {
            document.getElementById('EditLangChange').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }


        function closeEditLangChange() {
            document.getElementById('EditLangChange').style.display = 'none';
            document.body.style.overflow = 'auto';
        }


        function deleteAccountModal() {
            document.getElementById('deleteAccountModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteAccountModal() {
            document.getElementById('deleteAccountModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function passwordToggle(el) {
            const input = el.parentElement.querySelector('input');
            const icon = el.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
@endsection
