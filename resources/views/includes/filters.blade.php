<div id="filtersModal" class="modal-overlay">
    <div class="modal-content filter_content">
        <img src="/assets/images/cross.png" alt="Close" class="modal-close" onclick="closeFilterModal()">
        <div class="filter_text text-center py-4 text-lg lg:text-[32px] border-b border-gray-400">Filters</div>

        <div class="filter_details py-8">

            <div class="all_products overflow-y-auto max-h-[calc(100vh-300px)] px-6">
                <!-- Product Categories -->
                <div class="mb-8">
                    <div class="filter_title text-lg lg:text-[24px] text-[#46484d] font-medium mb-4">Product Categories
                    </div>

                    <div class="flex gap-4 flex-wrap">
                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="medical">
                            <img src="/assets/images/medical.png" alt="Medical"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Medical</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="automobile">
                            <img src="/assets/images/auto.png" alt="Automobile"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Automobile & Spare Parts</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="agrochemical">
                            <img src="/assets/images/agrochemical.png" alt="Agro-chemical"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Agro-chemical</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="technology">
                            <img src="/assets/images/tech.png" alt="Technology"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Technology</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="military">
                            <img src="/assets/images/military.png" alt="Military"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Military</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="cosmetics">
                            <img src="/assets/images/cosmetics.png" alt="Cosmetics"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Cosmetics</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="fashion">
                            <img src="/assets/images/fashion.png" alt="Fashion & Textiles"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Fashion & Textiles</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="secondhand">
                            <img src="/assets/images/second-hand.png" alt="Second-hand"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Second-hand</div>
                        </div>

                        <div class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter"
                            data-category="other">
                            <img src="/assets/images/other.png" alt="Other"
                                class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                            <div class="pr-4 text-base lg:text-[18px]">Other</div>
                        </div>
                    </div>
                </div>

                <!-- Business Types -->
                <div class="mb-8">
                    <div class="filter_title text-lg lg:text-[24px] text-[#46484d] font-medium mb-4">Business Types
                    </div>

                    <div class="flex gap-3 flex-wrap">
                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="business_type" value="manufacturer"
                                class="hidden business-type-checkbox">
                            <span class="text-base lg:text-[18px]">Manufacturer</span>
                        </label>

                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="business_type" value="oem"
                                class="hidden business-type-checkbox">
                            <span class="text-base lg:text-[18px]">OEM</span>
                        </label>

                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="business_type" value="odm"
                                class="hidden business-type-checkbox">
                            <span class="text-base lg:text-[18px]">ODM</span>
                        </label>

                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="business_type" value="exporter"
                                class="hidden business-type-checkbox">
                            <span class="text-base lg:text-[18px]">Exporter</span>
                        </label>
                    </div>
                </div>

                <!-- Certifications -->
                <div class="mb-8">
                    <div class="filter_title text-lg lg:text-[24px] text-[#46484d] font-medium mb-4">Certifications
                    </div>

                    <div class="flex gap-3 flex-wrap">
                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="certification" value="iso"
                                class="hidden certification-checkbox">
                            <span class="text-base lg:text-[18px]">ISO</span>
                        </label>

                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="certification" value="ce"
                                class="hidden certification-checkbox">
                            <span class="text-base lg:text-[18px]">CE</span>
                        </label>

                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="certification" value="rohs"
                                class="hidden certification-checkbox">
                            <span class="text-base lg:text-[18px]">RoHS</span>
                        </label>

                        <label
                            class="px-4 py-2 rounded-full border border-gray-400 cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors">
                            <input type="checkbox" name="certification" value="other"
                                class="hidden certification-checkbox">
                            <span class="text-base lg:text-[18px]">Other</span>
                        </label>
                    </div>
                </div>

                <!-- Location -->
                <div class="mb-8 lg:w-1/2">
                    <div class="filter_title text-lg lg:text-[24px] text-[#46484d] font-medium mb-4">Location</div>

                    <small class="text-[#46484d] mb-2 block">Country Selector</small>
                    <div class="relative">
                        <select name="country" id="countrySelector"
                            class="w-full px-4 py-3 border border-gray-400 rounded text-base lg:text-[18px] text-[#888] appearance-none bg-white cursor-pointer">
                            @include('includes.country_options')
                        </select>
                        <svg class="absolute right-4 top-6 -translate-y-1/2 w-4 h-4 pointer-events-none text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7">
                            </path>
                        </svg>
                    </div>
                </div>

                <!-- Minimum Order Quantity -->
                <div class="mb-8">
                    <div class="filter_title text-lg lg:text-[24px] text-[#46484d] font-medium mb-4">
                        Minimum Order Quantity (item quantity)
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-base text-[#888]">0</span>

                        <div class="flex-1 relative">
                            <input type="range" id="moqSlider" min="0" max="10000" value="0"
                                class="w-full h-2 bg-gray-300 rounded-lg appearance-none cursor-pointer slider">
                        </div>

                        <span class="text-base text-[#888]">10,000</span>

                        <input type="number" id="moqInput" min="0" max="10000" value="1000"
                            class="w-24 px-3 py-2 border border-gray-400 rounded text-base text-center">
                    </div>
                </div>

                <!-- Verified Only -->
                <div class="mb-8">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="toggle-switch">
                            <input type="checkbox" id="verifiedOnly" class="verified-checkbox">
                            <span class="toggle-slider"></span>
                        </div>
                        <span class="text-base lg:text-[18px] text-[#46484d]">Verified only</span>
                    </label>
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="flex items-center justify-between pt-4 border-t border-gray-400 px-6">
                <button type="button" onclick="clearFilters()"
                    class="text-[#003FB4] text-base font-medium hover:underline">
                    Clear filters
                </button>

                <button type="button" onclick="applyFilters()"
                    class="px-8 py-3 bg-[#003FB4] text-white rounded text-base font-medium hover:bg-[#002d85] transition-colors">
                    Search
                </button>
            </div>
        </div>
    </div>
</div>
