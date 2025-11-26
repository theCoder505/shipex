@extends('layouts.surface.app')

@section('title', $spec_manufacturer->company_name_en . ' profile')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">
    <link rel="stylesheet" href="/assets/css/index_style.css">

    <style>
        .modal-close:hover {
            transform: rotate(90deg);
        }

        .star-rating {
            display: flex;
            gap: 8px;
        }

        .star-icon {
            transition: all 0.2s ease;
        }

        .star-icon:hover {
            transform: scale(1.1);
        }

        .error-message {
            font-size: 14px;
            color: #DC2626;
        }

        #reviewText {
            transition: border-color 0.2s;
        }

        #reviewText:focus {
            border-color: #003FB4;
            box-shadow: 0 0 0 3px rgba(0, 63, 180, 0.1);
        }

        #reviewText.error {
            border-color: #DC2626;
        }

        #submitReviewBtn {
            transition: all 0.3s ease;
            min-width: 140px;
        }

        @media (max-width: 768px) {
            .modal-content {
                max-width: 95%;
                margin: 20px;
            }

            .star-icon {
                font-size: 28px !important;
            }

            .filter_text {
                font-size: 24px;
            }
        }

        .product_images_wrapper {
            position: relative;
            padding: 0 20px;
        }

        .product_images_container {
            overflow: hidden;
            position: relative;
        }

        .product_images_track {
            display: flex;
            gap: 1rem;
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .product_item_wrapper {
            flex-shrink: 0;
            height: 280px;
        }

        .product_item {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .carousel_nav {
            cursor: pointer;
            border: none;
            background: transparent;
            transition: all 0.3s ease;
        }

        .carousel_nav:disabled {
            opacity: 0.3;
            cursor: not-allowed;
            pointer-events: none;
        }

        .carousel_nav:not(:disabled):hover div {
            background-color: #f9fafb;
            transform: scale(1.05);
        }

        .carousel_nav div {
            transition: all 0.3s ease;
        }

        @media (max-width: 1023px) {
            .product_item_wrapper {
                width: 100%;
                height: 240px;
            }

            .product_images_wrapper {
                padding: 0 10px;
            }
        }

        @media (min-width: 1024px) {
            .product_item_wrapper {
                width: calc(33.333% - 10.67px);
                height: 320px;
            }

            .product_images_wrapper {
                padding: 0 30px;
            }
        }

        .product_images_track.animating {
            transition: transform 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        #googleLocation {
            height: 400px;
            border-radius: 8px;
            overflow: hidden;
        }

        .about_grid_item {
            transition: all 0.3s ease;
        }

        .about_grid_item.grid_active {
            color: #003FB4;
            border-bottom: 2px solid #003FB4;
        }
    </style>
@endsection

@section('content')
    <div class="hero_section my-4 px-4 lg:px-8 max-w-[1600px] mx-auto">
        <div class="flex gap-4 items-center">
            <a href="/manufacturers" class="text-[#46484D] dark:text-gray-300">All manufacturers</a>
            <i class="fa fa-chevron-right text-[#46484D] dark:text-gray-300"></i>
            <a href="/manufacturers/{{ $spec_manufacturer->company_name_en }}/{{ $manufacturer_uid }}"
                class="text-[#46484D] dark:text-gray-300">
                {{ $spec_manufacturer->company_name_en }}
            </a>
        </div>

        <div class="grid lg:flex justify-between gap-4 items-center mt-6">
            <div class="grid lg:flex gap-6 items-center">
                @if ($spec_manufacturer->company_logo)
                    <img src="{{ asset($spec_manufacturer->company_logo) }}" alt="{{ $spec_manufacturer->company_name_en }}"
                        class="rounded-full w-18 h-18 object-cover">
                @else
                    <img src="/assets/images/menufacturer.png" alt="{{ $spec_manufacturer->company_name_en }}"
                        class="rounded-full w-18 h-18">
                @endif

                <div class="grid gap-4">
                    <div class="flex gap-4 flex-wrap items-center">
                        <div class="text-[#46484D] dark:text-gray-100 text-xl lg:text-[40px]">
                            {{ $spec_manufacturer->company_name_en }}</div>
                        @if ($spec_manufacturer->status == '5')
                            <span
                                class="flex gap-2 rounded-full items-center px-3 py-1 bg-green-100 dark:bg-green-900 text-[#05660c] dark:text-green-300 text-xs">
                                <div class="">
                                    <img src="/assets/images/guard.png" alt="" class="h-full">
                                </div>
                                <span>Verified</span>
                            </span>
                        @endif
                    </div>

                    <div class="flex gap-4 flex-wrap">
                        <div class="flex gap-2 items-center">
                            <div class="">
                                <img src="/assets/images/map-pin.png" alt="" class="h-4">
                            </div>
                            <div class="text-[#46484D] dark:text-gray-300">{{ $spec_manufacturer->company_address_en }}
                            </div>
                        </div>
                        @if ($spec_manufacturer->year_established)
                            <div class="flex gap-2 items-center">
                                <div class="">
                                    <img src="/assets/images/calander.png" alt="" class="h-4">
                                </div>
                                <div class="text-[#46484D] dark:text-gray-300">Founded in
                                    {{ $spec_manufacturer->year_established }}</div>
                            </div>
                        @endif
                        <div class="flex gap-2 items-center">
                            <div class="">
                                <img src="/assets/images/star.png" alt="" class="h-4">
                            </div>
                            <div class="text-[#46484D] dark:text-gray-300">
                                {{ number_format($reviews->avg('rating'), 1) }} ({{ $reviews->count() }} reviews)
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex gap-4 items-center">
                @if ($spec_manufacturer->status != 5 || $spec_manufacturer->subscription != 1)
                    <div class="flex gap-2 items-center">
                        <div class="h-2 w-2 rounded-full bg-[#7e7e7e]"></div>
                        <div class="text-[#7e7e7e] dark:text-gray-400">Profile Offline</div>
                    </div>
                @else
                    <div class="flex gap-2 items-center">
                        <div class="h-2 w-2 rounded-full bg-[#05660C]"></div>
                        <div class="text-[#05660C] dark:text-green-400">Online now</div>
                    </div>
                @endif

                @if (Auth::guard('wholesaler')->check())
                    <a href="/wholesaler/chats/{{ $manufacturer_uid }}"
                        class="block text-white hover:bg-[#003FB4] rounded-lg px-4 py-3 bg-[#003FB4] dark:bg-blue-600 dark:hover:bg-blue-700 text-center font-semibold transition-all duration-200">
                        Chat Now
                    </a>
                    {{-- <button data-manufacturer="{{ $manufacturer_uid }}" onclick="showChatPopup(this)"
                        class="block text-white hover:bg-[#003FB4] rounded-lg px-4 py-3 bg-[#003FB4] dark:bg-blue-600 dark:hover:bg-blue-700 text-center font-semibold transition-all duration-200">
                        Chat Now
                    </button> --}}
                @elseif(Auth::guard('manufacturer')->check() && $manufacturer_uid == Auth::guard('manufacturer')->user()->manufacturer_uid)
                    <a href="/manufacturer/set-up-manufacturer-profile"
                        class="block hover:text-white bg-blue-50 dark:bg-blue-900 border border-[#003fb4] dark:border-blue-600 text-[#003fb4] dark:text-blue-300 rounded-lg px-4 py-3 hover:bg-[#003FB4] dark:hover:bg-blue-700 text-center font-semibold transition-all duration-200">
                        <i class="fas fa-pencil-alt"></i> Edit Profile
                    </a>
                @endif
            </div>
        </div>

        <hr class="my-8 bg-[#121212] dark:bg-gray-700 text-[#121212] dark:text-gray-700">

        <div class="grid lg:flex justify-between gap-4">
            <div class="products_line grid lg:flex gap-4">
                <p class="text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-3 py-1.5">Production:</p>
                <div class="related_tags flex gap-2 flex-wrap">
                    <div
                        class="bg-[#f6f6f6] dark:bg-gray-700 text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-3 py-1.5 capitalize">
                        {{ $spec_manufacturer->main_product_category }}
                    </div>
                    <div
                        class="bg-[#f6f6f6] dark:bg-gray-700 text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-3 py-1.5 capitalize">
                        {{ $spec_manufacturer->industry_category }}
                    </div>
                    <div
                        class="bg-[#f6f6f6] dark:bg-gray-700 text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-3 py-1.5 capitalize">
                        {{ $spec_manufacturer->business_type }}
                    </div>
                </div>
            </div>

            <a class="border border-[#46484d] dark:border-gray-500 text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-3 py-1 hover:bg-[#003FB4] hover:text-white dark:hover:bg-blue-600 block text-center"
                href="/new-products">
                New Products
            </a>
        </div>

        <div class="product_images_wrapper relative w-full my-12">
            <div class="product_images_container overflow-hidden">
                <div class="product_images_track flex gap-4 transition-transform duration-500 ease-in-out">
                    @if ($spec_manufacturer->factory_pictures && count($spec_manufacturer->factory_pictures) > 0)
                        @foreach ($spec_manufacturer->factory_pictures as $picture)
                            <div class="product_item_wrapper flex-shrink-0 w-full lg:w-[calc(33.333%-10.67px)]">
                                <img src="{{ asset($picture['image']) }}" alt="{{ $picture['title'] ?? '' }}"
                                    class="w-full h-full rounded-lg product_item object-cover">
                            </div>
                        @endforeach
                    @else
                        <div class="product_item_wrapper flex-shrink-0 w-full lg:w-[calc(33.333%-10.67px)]">
                            <img src="/assets/images/menufacturer_camera.png" alt=""
                                class="w-full h-full rounded-lg product_item object-cover">
                        </div>
                        <div class="product_item_wrapper flex-shrink-0 w-full lg:w-[calc(33.333%-10.67px)]">
                            <img src="/assets/images/menufacturer_company.jpeg" alt=""
                                class="w-full h-full rounded-lg product_item object-cover">
                        </div>
                        <div class="product_item_wrapper flex-shrink-0 w-full lg:w-[calc(33.333%-10.67px)]">
                            <img src="/assets/images/menufacturer_three.jpg" alt=""
                                class="w-full h-full rounded-lg product_item object-cover">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Navigation Arrows -->
            <button
                class="carousel_nav carousel_prev absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 lg:-translate-x-6 z-10 hidden opacity-0 transition-opacity duration-300">
                <div
                    class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white dark:bg-gray-700 shadow-lg flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </div>
            </button>

            <button
                class="carousel_nav carousel_next absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 lg:translate-x-6 z-10 opacity-100 transition-opacity duration-300">
                <div
                    class="w-12 h-12 lg:w-14 lg:h-14 rounded-full bg-white dark:bg-gray-700 shadow-lg flex items-center justify-center hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors">
                    <svg class="w-6 h-6 lg:w-7 lg:h-7 text-gray-700 dark:text-gray-300" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </div>
            </button>
        </div>

        {{-- About/Products/Certification/Reviews Tabs --}}
        <div class="about_grid flex flex-wrap justify-between items-center gap-4 pt-3 px-4 lg:px-0 lg:border-b-2 border-[#E4E4E4] dark:border-gray-700 sticky top-[73.5px] left-0 bg-white dark:bg-gray-900 z-20"
            id="stickyNav">
            <div class="left_grid flex flex-wrap">
                <div class="about_grid_item py-2 px-4 cursor-pointer grid_active dark:text-gray-300" data-id="About"
                    onclick="showSpecTab(this)">About</div>
                <div class="about_grid_item py-2 px-4 cursor-pointer dark:text-gray-300" data-id="Products"
                    onclick="showSpecTab(this)">Products</div>
                <div class="about_grid_item py-2 px-4 cursor-pointer dark:text-gray-300" data-id="Certifications"
                    onclick="showSpecTab(this)">Certifications</div>
                <div class="about_grid_item py-2 px-4 cursor-pointer dark:text-gray-300" data-id="Reviews"
                    onclick="showSpecTab(this)">Reviews</div>
            </div>

            @if (Auth::guard('wholesaler')->check())
                <a href="/wholesaler/chats/{{ $manufacturer_uid }}"
                    class="right_grid text-[#003FB4] dark:text-blue-400 block hover:underline">
                    Chat Now ↓
                </a>
                {{-- <button data-manufacturer="{{ $manufacturer_uid }}" onclick="showChatPopup(this)"
                    class="right_grid text-[#003FB4] dark:text-blue-400 block hover:underline">
                    Chat Now ↓
                </button> --}}
            @endif
        </div>

        <div class="grid_item_details grid grid-cols-1 lg:grid-cols-2 gap-4 py-12 border-b-2 border-[#e4e4e4] dark:border-gray-700"
            id="About">
            <div class="left_grid">
                <h3 class="text-xl text-[#46484D] dark:text-gray-100 font-medium">About</h3>
                <p class="my-6 text-[#46484D] dark:text-gray-300">
                    {{ $spec_manufacturer->business_introduction ?? 'No business introduction provided yet.' }}
                </p>
                <div class="flex flex-wrap gap-2">
                    @if ($spec_manufacturer->business_type)
                        <span
                            class="border rounded-full text-[#46484D] dark:text-gray-300 border-[#46484D] dark:border-gray-500 px-3 py-1 text-sm">{{ $spec_manufacturer->business_type }}</span>
                    @endif
                    @if ($spec_manufacturer->export_experience == 'yes')
                        <span
                            class="border rounded-full text-[#46484D] dark:text-gray-300 border-[#46484D] dark:border-gray-500 px-3 py-1 text-sm">Exporter</span>
                    @endif
                </div>

                <ul class="my-6 text-[#46484D] dark:text-gray-300 spec_ul">
                    @if ($spec_manufacturer->production_capacity)
                        <li>
                            <span class="font-medium">Production Capacity: </span>
                            <span class="text-sm">{{ $spec_manufacturer->production_capacity }}
                                {{ $spec_manufacturer->production_capacity_unit }}</span>
                        </li>
                    @endif
                    @if ($spec_manufacturer->moq)
                        <li>
                            <span class="font-medium">Minimum Order Quantity: </span>
                            <span class="text-sm">{{ $spec_manufacturer->moq }}</span>
                        </li>
                    @endif
                    @if ($spec_manufacturer->number_of_employees)
                        <li>
                            <span class="font-medium">Number of Employees: </span>
                            <span class="text-sm">{{ $spec_manufacturer->number_of_employees }}</span>
                        </li>
                    @endif
                    @if ($spec_manufacturer->website)
                        <li>
                            <span class="font-medium">Website: </span>
                            <a href="{{ $spec_manufacturer->website }}" target="_blank"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:underline">{{ $spec_manufacturer->website }}</a>
                        </li>
                    @endif
                </ul>

                <div class="language_section">
                    <div class="flex gap-2 items-center flex-wrap">
                        <div class="flex gap-2 items-center">
                            <i class="fas fa-globe h-4"></i>
                            <span class="text-[#46484D] dark:text-gray-300 font-medium">Languages:</span>
                        </div>
                        @if ($spec_manufacturer->language)
                            @php
                                $languages = explode(',', $spec_manufacturer->language);
                                $languages = array_map('trim', $languages); // Remove whitespace
                                $languages = array_map('ucfirst', $languages); // Capitalize first letter
                            @endphp
                            @foreach ($languages as $language)
                                <span
                                    class="bg-[#f6f6f6] dark:bg-gray-700 text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-3 py-1">
                                    {{ $language }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-[#46484D] dark:text-gray-300 text-sm">No languages listed</span>
                        @endif
                    </div>
                </div>

                @if (Auth::guard('wholesaler')->check())
                    <div class="mt-12">
                        <a href="/wholesaler/chats/{{ $manufacturer_uid }}"
                            class="text-white hover:bg-[#003FB4] rounded-lg px-4 py-3 bg-[#003FB4] dark:bg-blue-600 dark:hover:bg-blue-700 text-center font-semibold transition-all duration-200">
                            Chat now
                        </a>
                        {{-- <button data-manufacturer="{{ $manufacturer_uid }}" onclick="showChatPopup(this)"
                            class="text-white hover:bg-[#003FB4] rounded-lg px-4 py-3 bg-[#003FB4] dark:bg-blue-600 dark:hover:bg-blue-700 text-center font-semibold transition-all duration-200">
                            Chat now
                        </button> --}}
                    </div>
                @endif
            </div>

            <div class="right_grid w-full overflow-auto mt-6 lg:mt-0">
                <div class="w-full" id="googleLocation" style="height: 400px; border-radius: 8px; overflow: hidden;">
                    {!! $spec_manufacturer->company_google_location !!}
                </div>
            </div>
        </div>

        <div class="py-12 pt-32 lg:pt-18 border-b-2 border-[#e4e4e4]" id="Products">
            <h3 class="text-xl text-[#46484D] font-medium">Products</h3>

            <div class="relative rounded-lg border-gray-400 border flex items-center overflow-hidden mt-8 w-full lg:w-1/2">
                <img src="/assets/images/search.png" alt="Search" class="px-4 py-3 cursor-pointer"
                    onclick="searchProducts(this)">
                <input type="text" class="border-none outline-none w-full py-2 serach_input" name="search_query"
                    placeholder="Search for products...">
            </div>

            @include('includes.products')
        </div>

        <div class="py-12 pt-32 lg:pt-18 border-b-2 border-[#e4e4e4] dark:border-gray-700" id="Certifications">
            <h3 class="text-xl text-[#46484D] dark:text-gray-100 font-medium">Certifications</h3>

            <div class="my-12 grid grid-cols-1 lg:grid-cols-5 gap-4">
                @if ($spec_manufacturer->certifications && count($spec_manufacturer->certifications) > 0)
                    @foreach ($spec_manufacturer->certifications as $key => $certificate)
                        <div class="grid_item flex gap-4 items-center">
                            <img src="/assets/images/iso.png" alt="" class="rounded-full h-[80px] w-[80px]">
                            <div class="">
                                <h3 class="text-[#121212] dark:text-gray-100">{{ $certificate['name'] }}</h3>
                                <a href="{{ asset($certificate['document']) }}" target="_blank"
                                    class="underline text-blue-600 dark:text-blue-400">
                                    See certification
                                </a>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-span-5 text-center py-12">
                        <p class="text-gray-500 dark:text-gray-400">No certifications available</p>
                    </div>
                @endif
            </div>

            @if ($spec_manufacturer->standards && count($spec_manufacturer->standards) > 0)
                <div class="mt-8">
                    <h4 class="text-lg font-medium text-[#46484D] dark:text-gray-200 mb-4">Standards Compliance</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach ($spec_manufacturer->standards as $standard)
                            <span
                                class="bg-[#f6f6f6] dark:bg-gray-700 text-[#46484d] dark:text-gray-300 rounded-full font-normal text-sm px-4 py-2">
                                {{ $standard }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>


        @include('includes.reviews')


        <div class="py-12">
            <h3 class="text-xl text-[#46484d] dark:text-gray-100 font-medium mb-8">
                Other manufacturer(s) you might be interested in
            </h3>
            @include('includes.manufacturer')
        </div>

        <div id="reviewModal" class="modal-overlay">
            <div class="modal-content filter_content bg-white dark:bg-gray-800">
                <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeReviewModal()">
                <div
                    class="filter_text text-center py-4 text-lg lg:text-[32px] border-b-2 border-[#BCBCBC] dark:border-gray-700 dark:text-gray-100">
                    Leave a review
                </div>

                <form action="/wholesaler/review-manufacturer" method="post" id="reviewForm">
                    @csrf
                    <input type="hidden" name="manufacturer_uid" value="{{ $manufacturer_uid }}">
                    <input type="hidden" name="rating" id="ratingValue" value="0">

                    <div class="py-8">
                        <div class="all_products overflow-y-auto max-h-[calc(100vh-300px)] px-6">
                            <!-- Rating Section -->
                            <div class="mb-6">
                                <label class="block text-[#46484D] dark:text-gray-200 text-base font-medium mb-3">
                                    Please rate your interaction with {{ $spec_manufacturer->company_name_en }}*
                                </label>
                                <div class="flex gap-2 star-rating" id="starRating">
                                    <i class="fas fa-star star-icon text-4xl cursor-pointer text-gray-300 dark:text-gray-600"
                                        data-rating="1"></i>
                                    <i class="fas fa-star star-icon text-4xl cursor-pointer text-gray-300 dark:text-gray-600"
                                        data-rating="2"></i>
                                    <i class="fas fa-star star-icon text-4xl cursor-pointer text-gray-300 dark:text-gray-600"
                                        data-rating="3"></i>
                                    <i class="fas fa-star star-icon text-4xl cursor-pointer text-gray-300 dark:text-gray-600"
                                        data-rating="4"></i>
                                    <i class="fas fa-star star-icon text-4xl cursor-pointer text-gray-300 dark:text-gray-600"
                                        data-rating="5"></i>
                                </div>
                                <div class="error-message hidden mt-2 flex items-center gap-1 text-red-600 dark:text-red-400 text-sm"
                                    id="ratingError">
                                    <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>This field is mandatory</span>
                                </div>
                            </div>

                            <!-- Review Text Section -->
                            <div class="mb-6">
                                <label class="block text-[#46484D] dark:text-gray-200 text-base font-medium mb-3">
                                    Please write about your experience with {{ $spec_manufacturer->company_name_en }}*
                                </label>
                                <div class="relative">
                                    <textarea name="review_text" id="reviewText" rows="6" maxlength="400"
                                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-3 text-[#46484D] dark:text-gray-200 dark:bg-gray-700 text-base focus:outline-none focus:border-[#003FB4] dark:focus:border-blue-500 resize-none"
                                        placeholder="Your review"></textarea>
                                    <div class="flex justify-between items-center mt-2">
                                        <div class="error-message hidden flex items-center gap-1 text-red-600 dark:text-red-400 text-sm"
                                            id="reviewError">
                                            <svg class="w-4 h-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <span>This field is mandatory</span>
                                        </div>
                                        <span class="text-sm text-gray-500 dark:text-gray-400" id="charCount">0/400</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="flex items-center justify-end gap-6 pt-4 border-t-2 border-[#BCBCBC] dark:border-gray-700 px-6">
                            <button type="button" onclick="closeReviewModal()"
                                class="text-[#003FB4] dark:text-blue-400 text-base font-medium hover:underline px-4 py-2">
                                Cancel
                            </button>

                            <button type="submit" id="submitReviewBtn"
                                class="px-8 py-3 rounded text-base font-medium transition-colors text-[#707171] bg-[#E4E4E4] dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed"
                                disabled>
                                Submit review
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @include('includes.chats.chat')

    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.serach_input').on('keyup', function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    searchProducts(this);
                } else {
                    // Optional: Add debounce for real-time search
                    clearTimeout($(this).data('timeout'));
                    $(this).data('timeout', setTimeout(() => {
                        searchProducts(this);
                    }, 300));
                }
            });

            // Handle search icon click
            $('img[src="/assets/images/search.png"]').on('click', function() {
                searchProducts(this);
            });



            $('.owl_items').owlCarousel({
                items: 1,
                loop: true,
                nav: true,
                dots: true,
                autoplay: false,
                autoplayTimeout: 5000,
                autoplayHoverPause: true,
                navText: ['', ''],
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 1
                    },
                    1000: {
                        items: 1
                    }
                }
            });
        });

        function filterMenufacturers() {
            window.location.href = '/';
        }

        function showFull(passedThis) {
            let activeItem = $(passedThis).closest(".relative").find(".owl-item.active img");
            let imgSrc = activeItem.attr("src");

            if (!imgSrc) {
                imgSrc = $(passedThis).closest(".relative").find(".owl-carousel img").first().attr("src");
            }

            document.getElementById("modalImage").src = imgSrc;
            document.getElementById("imageModal").classList.remove("hidden");
        }

        function closePopup() {
            document.getElementById("imageModal").classList.add("hidden");
        }

        document.getElementById("imageModal")?.addEventListener("click", function(e) {
            if (e.target === this) closePopup();
        });

        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") closePopup();
        });

        function writeReview() {
            document.getElementById('reviewModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeReviewModal() {
            document.getElementById('reviewModal').style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('reviewModal');
            modal.addEventListener('click', function(e) {
                if (e.target === modal) closeReviewModal();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'flex') {
                    closeReviewModal();
                }
            });
        });

        $(document).ready(function() {
            let selectedRating = 0;

            $('.star-icon').on('click', function() {
                selectedRating = parseInt($(this).data('rating'));
                $('#ratingValue').val(selectedRating);
                updateStars(selectedRating);
                $('#ratingError').addClass('hidden');
                validateForm();
            });

            $('.star-icon').on('mouseenter', function() {
                const hoverRating = parseInt($(this).data('rating'));
                updateStars(hoverRating, true);
            });

            $('#starRating').on('mouseleave', function() {
                updateStars(selectedRating);
            });

            function updateStars(rating, isHover = false) {
                $('.star-icon').each(function() {
                    const starRating = parseInt($(this).data('rating'));
                    if (starRating <= rating) {
                        $(this).removeClass('text-gray-300 dark:text-gray-600').addClass('text-[#FDB022]');
                    } else {
                        $(this).removeClass('text-[#FDB022]').addClass('text-gray-300 dark:text-gray-600');
                    }
                });
            }

            $('#reviewText').on('input', function() {
                const length = $(this).val().length;
                $('#charCount').text(length + '/400');
                $('#reviewError').addClass('hidden');
                validateForm();
            });

            function validateForm() {
                const hasRating = selectedRating > 0;
                const hasReview = $('#reviewText').val().trim().length > 0;
                const submitBtn = $('#submitReviewBtn');

                if (hasRating && hasReview) {
                    submitBtn.removeClass(
                        'text-[#707171] bg-[#E4E4E4] dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed');
                    submitBtn.addClass(
                        'text-white bg-[#003FB4] dark:bg-blue-600 hover:bg-[#002d85] dark:hover:bg-blue-700 cursor-pointer'
                    );
                    submitBtn.prop('disabled', false);
                } else {
                    submitBtn.removeClass(
                        'text-white bg-[#003FB4] dark:bg-blue-600 hover:bg-[#002d85] dark:hover:bg-blue-700 cursor-pointer'
                    );
                    submitBtn.addClass(
                        'text-[#707171] bg-[#E4E4E4] dark:bg-gray-700 dark:text-gray-500 cursor-not-allowed');
                    submitBtn.prop('disabled', true);
                }
            }

            $('#reviewForm').on('submit', function(e) {
                e.preventDefault();

                const hasRating = selectedRating > 0;
                const hasReview = $('#reviewText').val().trim().length > 0;

                $('#ratingError').addClass('hidden');
                $('#reviewError').addClass('hidden');

                let isValid = true;
                if (!hasRating) {
                    $('#ratingError').removeClass('hidden');
                    isValid = false;
                }
                if (!hasReview) {
                    $('#reviewError').removeClass('hidden');
                    isValid = false;
                }

                if (isValid) {
                    this.submit();
                }
            });

            window.writeReview = function() {
                selectedRating = 0;
                $('#ratingValue').val(0);
                $('#reviewText').val('');
                $('#charCount').text('0/400');
                updateStars(0);
                $('#ratingError').addClass('hidden');
                $('#reviewError').addClass('hidden');
                validateForm();

                document.getElementById('reviewModal').style.display = 'flex';
                document.body.style.overflow = 'hidden';
            };

            window.closeReviewModal = function() {
                document.getElementById('reviewModal').style.display = 'none';
                document.body.style.overflow = 'auto';
            };
        });

        $(document).ready(function() {
            const $track = $('.product_images_track');
            const $items = $('.product_item_wrapper');
            const $prevBtn = $('.carousel_prev');
            const $nextBtn = $('.carousel_next');

            let currentIndex = 0;
            let itemsPerView = 1;
            let totalItems = $items.length;
            let isAnimating = false;

            function updateItemsPerView() {
                if ($(window).width() >= 1024) {
                    itemsPerView = 3;
                } else {
                    itemsPerView = 1;
                }
            }

            function getTotalSlides() {
                return Math.ceil(totalItems - itemsPerView + 1);
            }

            function updateCarousel(animate = true) {
                if (animate) {
                    $track.addClass('animating');
                } else {
                    $track.removeClass('animating');
                }

                let translateX = 0;

                if ($(window).width() >= 1024) {
                    const itemWidth = $items.eq(0).outerWidth();
                    const gap = 16;
                    translateX = -(currentIndex * (itemWidth + gap));
                } else {
                    translateX = -(currentIndex * 100);
                }

                if ($(window).width() >= 1024) {
                    $track.css('transform', `translateX(${translateX}px)`);
                } else {
                    $track.css('transform', `translateX(${translateX}%)`);
                }

                updateNavigationButtons();

                setTimeout(() => {
                    $track.removeClass('animating');
                }, 500);
            }

            function updateNavigationButtons() {
                const maxIndex = getTotalSlides() - 1;

                if (currentIndex === 0) {
                    $prevBtn.removeClass('opacity-100').addClass('opacity-0 hidden');
                } else {
                    $prevBtn.removeClass('opacity-0 hidden').addClass('opacity-100');
                }

                if (currentIndex >= maxIndex) {
                    $nextBtn.removeClass('opacity-100').addClass('opacity-0');
                    setTimeout(() => {
                        $nextBtn.addClass('hidden');
                    }, 300);
                } else {
                    $nextBtn.removeClass('opacity-0 hidden').addClass('opacity-100');
                }
            }

            function nextSlide() {
                if (isAnimating) return;

                const maxIndex = getTotalSlides() - 1;
                if (currentIndex < maxIndex) {
                    isAnimating = true;
                    currentIndex++;
                    updateCarousel();
                    setTimeout(() => {
                        isAnimating = false;
                    }, 500);
                }
            }

            function prevSlide() {
                if (isAnimating) return;

                if (currentIndex > 0) {
                    isAnimating = true;
                    currentIndex--;
                    updateCarousel();
                    setTimeout(() => {
                        isAnimating = false;
                    }, 500);
                }
            }

            $nextBtn.on('click', nextSlide);
            $prevBtn.on('click', prevSlide);

            let resizeTimer;
            $(window).on('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    const oldItemsPerView = itemsPerView;
                    updateItemsPerView();

                    if (oldItemsPerView !== itemsPerView) {
                        const maxIndex = getTotalSlides() - 1;
                        if (currentIndex > maxIndex) {
                            currentIndex = maxIndex;
                        }
                    }

                    updateCarousel(false);
                }, 250);
            });

            updateItemsPerView();
            updateCarousel(false);

            let touchStartX = 0;
            let touchEndX = 0;

            $track.on('touchstart', function(e) {
                touchStartX = e.originalEvent.touches[0].clientX;
            });

            $track.on('touchmove', function(e) {
                touchEndX = e.originalEvent.touches[0].clientX;
            });

            $track.on('touchend', function() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            });

            $(document).on('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });
        });

        function showSpecTab(element) {
            $('.about_grid_item').removeClass('grid_active');
            $(element).addClass('grid_active');

            const targetId = $(element).data('id');
            const targetSection = $('#' + targetId);

            if (targetSection.length) {
                $('html, body').animate({
                    scrollTop: targetSection.offset().top - 100
                }, 600, 'swing');
            }
        }


        // Fixed searchProducts function
        function searchProducts(element) {
            // Get the search input - handle both click on icon and input keyup
            const searchInput = $(element).hasClass('serach_input') ?
                $(element) :
                $(element).siblings('.serach_input');

            const searchQuery = searchInput.val() ? searchInput.val().toLowerCase().trim() : '';

            let visibleCount = 0;

            // Iterate through all product items
            $('.product-item').each(function() {
                const productName = $(this).data('product-name');
                const isAskAboutSection = productName === 'ask-about-product';

                // Always show the "Ask about product" section
                if (isAskAboutSection) {
                    $(this).removeClass('hidden');
                    return; // Continue to next iteration
                }

                if (searchQuery === '') {
                    // If search is empty, show all products
                    $(this).removeClass('hidden');
                    visibleCount++;
                } else {
                    // If searching, show only matching products
                    if (productName && productName.includes(searchQuery)) {
                        $(this).removeClass('hidden');
                        visibleCount++;
                    } else {
                        $(this).addClass('hidden');
                    }
                }
            });

            // Show/hide empty message based on visible products
            updateEmptyMessage(searchQuery, visibleCount);
        }

        function updateEmptyMessage(searchQuery, visibleCount) {
            const emptyMessage = $('.empty-products-message');

            if (visibleCount === 0 && !searchQuery) {
                // No products at all (initial empty state)
                if (emptyMessage.length === 0) {
                    $('.all_products').prepend(`
                <div class="col-span-3 p-4 rounded-lg bg-[#F6F6F6] dark:bg-gray-700 mx-4 lg:w-[680px] lg:mx-auto empty-products-message">
                    <img src="/assets/images/empty_box.png" alt="No products" class="w-32 rounded block mx-auto">
                    <h3 class="text-xl my-4 text-40px text-center dark:text-gray-100">
                        No products found
                    </h3>
                    <p class="text-[16px] text-gray-500 dark:text-gray-400 mb-2 text-center">
                        No products have been added yet!
                    </p>
                </div>
            `);
                }
            } else if (visibleCount === 0 && searchQuery) {
                // No products found for search query
                if (emptyMessage.length === 0) {
                    $('.all_products').prepend(`
                <div class="col-span-3 p-4 rounded-lg bg-[#F6F6F6] dark:bg-gray-700 mx-4 lg:w-[680px] lg:mx-auto empty-products-message">
                    <img src="/assets/images/empty_box.png" alt="No products" class="w-32 rounded block mx-auto">
                    <h3 class="text-xl my-4 text-40px text-center dark:text-gray-100">
                        No products found
                    </h3>
                    <p class="text-[16px] text-gray-500 dark:text-gray-400 mb-2 text-center">
                        No products match your search criteria.
                    </p>
                </div>
            `);
                } else {
                    emptyMessage.find('p').text('No products match your search criteria.');
                }
            } else {
                // Products are visible, remove empty message
                emptyMessage.remove();
            }
        }
    </script>

    <script src="/assets/js/chat.js"></script>
@endsection
