<div class="col-span-3 p-4 rounded-lg bg-[#F6F6F6] mx-4 lg:w-[680px] lg:mx-auto empty_results hidden">
    <img src="/assets/images/empty_box.png" alt="" class="w-32 rounded block mx-auto">
    <h3 class="text-xl my-4 text-40px text-center">
        No results
    </h3>
    <p class="text-[16px] text-gray-500 mb-2 text-center">
        Please edit your filters
    </p>
    <p class="text-[16px] text-[#003FB4] mb-2 text-center cursor-pointer" onclick="filterMenufacturers(this)">
        Edit Filters
    </p>
</div>

<div class="max-w-[1600px] mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-center all_menufacturers">
        @forelse ($manufacturers as $manufacturer)
            <div class="item border border-gray-400 rounded-lg p-4 manufacturer-item"
                data-categories="{{ strtolower($manufacturer->industry_category . ',' . $manufacturer->main_product_category) }}"
                data-business-type="{{ strtolower($manufacturer->business_type ?? '') }}"
                data-standards="{{ isset($manufacturer->standards) ? strtolower(implode(',', $manufacturer->standards)) : '' }}"
                data-moq="{{ $manufacturer->moq ?? 0 }}"
                data-verified="{{ $manufacturer->status == 5 && $manufacturer->subscription == 1 ? 'true' : 'false' }}"
                data-country="{{ strtolower($manufacturer->company_address_en ?? '') }}"
                data-search-text="{{ strtolower(($manufacturer->company_name_en ?? '') . ' ' . ($manufacturer->company_name_ko ?? '') . ' ' . ($manufacturer->business_introduction ?? '') . ' ' . ($manufacturer->industry_category ?? '') . ' ' . ($manufacturer->main_product_category ?? '')) }}"
                data-product-type="{{ $manufacturer->year_established >= date('Y') - 2 ? 'new' : 'old' }}">

                <!-- Carousel Section -->
                <div class="relative">
                    <div class="owl-carousel owl-theme owl_items">
                        @if (isset($manufacturer->factory_pictures) && count($manufacturer->factory_pictures) > 0)
                            @foreach ($manufacturer->factory_pictures as $picture)
                                @if (!empty($picture['image']))
                                    <div class="item">
                                        <img src="{{ asset($picture['image']) }}"
                                            alt="{{ $picture['title'] ?? 'Factory Picture' }}"
                                            class="w-full h-[200px] object-cover rounded-lg">
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        <!-- Default images if no factory pictures or if all are empty -->
                        @if (
                            !isset($manufacturer->factory_pictures) ||
                                count($manufacturer->factory_pictures) === 0 ||
                                (isset($manufacturer->factory_pictures) &&
                                    !collect($manufacturer->factory_pictures)->pluck('image')->filter()->count()))
                            <div class="item">
                                <img src="/assets/images/menufacturer_camera.png" alt="Default Factory Image"
                                    class="w-full h-[200px] object-cover rounded-lg">
                            </div>
                            <div class="item">
                                <img src="/assets/images/menufacturer_company.jpeg" alt="Default Company Image"
                                    class="w-full h-[200px] object-cover rounded-lg">
                            </div>
                            <div class="item">
                                <img src="/assets/images/menufacturer_three.jpg" alt="Default Manufacturing Image"
                                    class="w-full h-[200px] object-cover rounded-lg">
                            </div>
                        @endif
                    </div>

                    <!-- Verified Badge -->
                    @if ($manufacturer->status == 5 && $manufacturer->subscription == 1)
                        <span
                            class="flex gap-2 rounded-full items-center px-3 py-1 bg-green-100 text-[#05660c] text-xs absolute top-4 right-4 z-10">
                            <img src="/assets/images/guard.png" alt="Verified" class="h-4">
                            <span>Verified</span>
                        </span>
                    @endif

                    <!-- New Product Badge -->
                    @if ($manufacturer->year_established >= date('Y') - 2)
                        <span
                            class="flex gap-2 rounded-full items-center px-3 py-1 bg-blue-100 text-[#003FB4] text-xs absolute top-4 left-4 z-10">
                            <span>New</span>
                        </span>
                    @endif
                </div>

                <!-- Company Info Section -->
                <div class="flex gap-4 mt-4">
                    @if ($manufacturer->company_logo)
                        <img src="{{ asset($manufacturer->company_logo) }}"
                            alt="{{ $manufacturer->company_name_en ?? 'Company Logo' }}"
                            class="w-[64px] h-[64px] rounded-full object-cover border border-gray-300">
                    @else
                        <img src="/assets/images/menufacturer.png" alt="Default Company Logo"
                            class="w-[64px] h-[64px] rounded-full border border-gray-300">
                    @endif
                    <div class="user_details flex-1">
                        <a href="/manufacturers/{{ Str::slug($manufacturer->company_name_en ?? 'company') }}/{{ $manufacturer->manufacturer_uid }}"
                            class="font-medium text-xl lg:text-[24px] text-gray-900 hover:text-[#003FB4] transition-colors duration-200 block">
                            {{ $manufacturer->company_name_en ?? 'Manufacturer Name' }}
                        </a>
                        <p class="text-gray-600 flex items-center gap-1 font-normal mt-2">
                            <img src="/assets/images/map-pin.png" alt="Location" class="h-4">
                            <span
                                class="truncate">{{ $manufacturer->company_address_en ?? 'Location not specified' }}</span>
                        </p>
                        <p class="text-gray-600 flex items-center gap-1 font-normal mt-2">
                            <img src="/assets/images/star.png" alt="Rating" class="h-4 w-4">
                            <span>{{ number_format($manufacturer->rating, 1) }} ({{ $manufacturer->total_ratings }}
                                reviews)</span>
                        </p>
                    </div>
                </div>

                <!-- Tags Section -->
                <div class="related_tags flex gap-2 flex-wrap mt-4 mb-6">
                    @if ($manufacturer->industry_category)
                        <div
                            class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">
                            {{ $manufacturer->industry_category }}
                        </div>
                    @endif

                    @if ($manufacturer->main_product_category)
                        <div
                            class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">
                            {{ $manufacturer->main_product_category }}
                        </div>
                    @endif

                    @if ($manufacturer->business_type)
                        <div
                            class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">
                            {{ $manufacturer->business_type }}
                        </div>
                    @endif

                    @php
                        $tagsCount = 0;
                        if ($manufacturer->industry_category) {
                            $tagsCount++;
                        }
                        if ($manufacturer->main_product_category) {
                            $tagsCount++;
                        }
                        if ($manufacturer->business_type) {
                            $tagsCount++;
                        }

                        // Additional tags from standards
                        $additionalTags = [];
                        if (isset($manufacturer->standards) && is_array($manufacturer->standards)) {
                            $additionalTags = array_slice($manufacturer->standards, 0, 3 - $tagsCount);
                        }
                    @endphp

                    @foreach ($additionalTags as $tag)
                        <div
                            class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">
                            {{ $tag }}
                        </div>
                        @php $tagsCount++; @endphp
                    @endforeach

                    @if ($tagsCount < 3)
                        <div
                            class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">
                            +{{ 3 - $tagsCount }}
                        </div>
                    @endif
                </div>

                <!-- Action Button -->
                <a class="border border-[#46484d] text-[#46484d] rounded-full font-normal text-sm px-3 py-1 inline-block hover:bg-[#003FB4] hover:border-[#003FB4] hover:text-white"
                    href="/manufacturers/{{ $manufacturer->company_name_en }}/{{ $manufacturer->manufacturer_uid }}#Products">
                    View Products
                </a>

                <!-- Additional Info (Hidden but used for filtering) -->
                <div class="hidden manufacturer-meta">
                    <div class="export-experience">{{ $manufacturer->export_experience ?? 'no' }}</div>
                    <div class="export-years">{{ $manufacturer->export_years ?? 0 }}</div>
                    <div class="production-capacity">{{ $manufacturer->production_capacity ?? 0 }}</div>
                    <div class="has-qms">{{ $manufacturer->has_qms ?? 'no' }}</div>
                    <div class="factory-audit">{{ $manufacturer->factory_audit_available ?? 'no' }}</div>
                </div>
            </div>
        @empty
            <div class="col-span-3 p-4 rounded-lg bg-[#F6F6F6] mx-4 lg:w-[680px] lg:mx-auto empty_results">
                <img src="/assets/images/empty_box.png" alt="No results" class="w-32 rounded block mx-auto">
                <h3 class="text-xl my-4 text-40px text-center text-gray-900">
                    No manufacturers found
                </h3>
                <p class="text-[16px] text-gray-500 mb-2 text-center">
                    @if (request()->hasAny(['search', 'categories', 'business_types', 'certifications', 'country', 'moq', 'verified_only']))
                        Please adjust your filters to see more results
                    @else
                        No manufacturers are currently registered
                    @endif
                </p>
                <p class="text-[16px] text-[#003FB4] mb-2 text-center cursor-pointer hover:underline"
                    onclick="filterMenufacturers(this)">
                    Edit Filters
                </p>
            </div>
        @endforelse
    </div>

    <!-- Load More Section (if needed) -->
    @if ($manufacturers->count() >= 9)
        <div class="text-center mt-8">
            <button onclick="loadMoreMenufacturer()"
                class="px-8 py-3 bg-[#003FB4] text-white rounded-full font-medium text-base hover:bg-[#002d85] transition-colors duration-200 inline-flex items-center gap-2">
                <span>Load More Manufacturers</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
        </div>
    @endif
</div>

<!-- Loading Spinner -->
<div id="loadingSpinner" class="hidden fixed inset-0 bg-white bg-opacity-80 z-50 flex items-center justify-center">
    <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-[#003FB4]"></div>
</div>


@if ($show_type == 'limited')
    <div class="mx-auto flex items-center justify-center mt-12">
        <a href="/manufacturers"
            class="bg-blue-100 text-blue-800 border-blue-800 border rounded-lg px-4 py-2 hover:bg-blue-800 hover:text-white">View
            All Manufacturer</a>
    </div>
@endif
