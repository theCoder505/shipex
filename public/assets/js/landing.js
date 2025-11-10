var _token = $(".csrf_token").val();
var currentFilters = {
    search: '',
    product_type: 'all',
    categories: [],
    business_types: [],
    certifications: [],
    country: '',
    moq: 0,
    verified_only: false
};

// Track if filters modal has been opened and filters applied
var filtersInitialized = false;
var filtersApplied = false;

function openAccountModal() {
    document.getElementById('authModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAccountModal() {
    document.getElementById('authModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('authModal');
    modal.addEventListener('click', function (e) {
        if (e.target === modal) {
            closeAccountModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') {
            closeAccountModal();
        }
    });
});

function showSpecManufacturer(passedThis) {
    let dataID = $(passedThis).attr("data-id");
    $(".active_tab").addClass('homepage_tab').removeClass('active_tab');
    $(passedThis).addClass("active_tab");

    let search_input = ($(".serach_input").val()).trim();
    currentFilters.search = search_input;
    currentFilters.product_type = dataID;
    
    // If "All" tab is clicked, clear all filters
    if (dataID === 'all') {
        resetAllFilters();
    }
    
    applyFilters();
}

function searchMenufacturer() {
    let search_input = ($(".serach_input").val()).trim();
    currentFilters.search = search_input;
    applyFilters();
}

function filterMenufacturers() {
    document.getElementById('filtersModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
    filtersInitialized = true;
}

function closeFilterModal() {
    document.getElementById('filtersModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Sync slider and input with progress tracking
const moqSlider = document.getElementById('moqSlider');
const moqInput = document.getElementById('moqInput');

function updateSliderProgress() {
    if (moqSlider) {
        const value = moqSlider.value;
        const max = moqSlider.max;
        const progress = (value / max) * 100;
        moqSlider.style.setProperty('--slider-progress', `${progress}%`);
    }
}

if (moqSlider && moqInput) {
    // Initialize with default values (no filter applied)
    moqSlider.value = 0;
    moqInput.value = 1000;
    updateSliderProgress();

    moqSlider.addEventListener('input', function () {
        moqInput.value = this.value;
        updateSliderProgress();
    });

    moqInput.addEventListener('input', function () {
        let value = parseInt(this.value);
        if (value < 0) value = 0;
        if (value > 10000) value = 10000;
        this.value = value;
        moqSlider.value = value;
        updateSliderProgress();
    });
}

// Handle category filter selection
document.querySelectorAll('.category-filter').forEach(filter => {
    filter.addEventListener('click', function () {
        this.classList.toggle('active');
        // Don't apply filters immediately when clicking categories
    });
});

// Handle checkbox labels
document.querySelectorAll('.business-type-checkbox, .certification-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function () {
        const label = this.closest('label');
        if (this.checked) {
            label.classList.add('bg-[#003FB4]', 'text-white');
            label.classList.remove('text-[#121212]');
        } else {
            label.classList.remove('bg-[#003FB4]', 'text-white');
            label.classList.add('text-[#121212]');
        }
        // Don't apply filters immediately when checking boxes
    });
});

// Reset all filters to default state
function resetAllFilters() {
    // Reset current filters to defaults
    currentFilters = {
        search: '',
        product_type: 'all',
        categories: [],
        business_types: [],
        certifications: [],
        country: '',
        moq: 0,
        verified_only: false
    };
    
    // Reset UI state
    $(".serach_input").val('');
    $(".filter_span").html('( 0 )');
    
    // Reset flags
    filtersApplied = false;
    
    // Reset filter modal UI if it's open
    resetFilterModalUI();
}

// Reset filter modal UI elements
function resetFilterModalUI() {
    // Reset category filters
    document.querySelectorAll('.category-filter').forEach(el => el.classList.remove('active'));
    
    // Reset checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(el => {
        el.checked = false;
        el.closest('label')?.classList.remove('bg-[#003FB4]', 'text-white');
        el.closest('label')?.classList.add('text-[#121212]');
    });
    
    // Reset country selector
    document.getElementById('countrySelector').value = '';
    
    // Reset MOQ to default values (no filter)
    document.getElementById('moqSlider').value = 0;
    document.getElementById('moqInput').value = 1000;
    updateSliderProgress();
}

// Clear filters function - reset to initial state
function clearFilters() {
    resetAllFilters();
    
    // Also reset active tab to "All"
    $(".active_tab").addClass('homepage_tab').removeClass('active_tab');
    $(".all").addClass("active_tab");
    
    // Apply the cleared filters (show all manufacturers)
    applyFilters();
}

function applyFilters() {
    showLoading();
    
    // Only get filter values from UI if filters modal has been initialized
    if (filtersInitialized) {
        const filters = {
            categories: Array.from(document.querySelectorAll('.category-filter.active')).map(el => el.dataset.category),
            businessTypes: Array.from(document.querySelectorAll('.business-type-checkbox:checked')).map(el => el.value),
            certifications: Array.from(document.querySelectorAll('.certification-checkbox:checked')).map(el => el.value),
            country: document.getElementById('countrySelector').value,
            moq: document.getElementById('moqInput').value,
            verifiedOnly: document.getElementById('verifiedOnly').checked
        };

        // Update current filters
        currentFilters.categories = filters.categories;
        currentFilters.business_types = filters.businessTypes;
        currentFilters.certifications = filters.certifications;
        currentFilters.country = filters.country;
        currentFilters.moq = parseInt(filters.moq) || 0;
        currentFilters.verified_only = filters.verifiedOnly;

        // Mark that filters have been applied
        filtersApplied = true;
    }

    // Calculate active filter count (only count non-default values)
    let activeFilterCount = 0;
    if (currentFilters.categories.length > 0) activeFilterCount++;
    if (currentFilters.business_types.length > 0) activeFilterCount++;
    if (currentFilters.certifications.length > 0) activeFilterCount++;
    if (currentFilters.country) activeFilterCount++;
    if (currentFilters.moq > 0) activeFilterCount++;
    if (currentFilters.verified_only) activeFilterCount++;
    if (currentFilters.search) activeFilterCount++;
    if (currentFilters.product_type !== 'all') activeFilterCount++;

    $(".filter_span").html('( ' + activeFilterCount + ' )');
    closeFilterModal();

    // Send AJAX request to filter manufacturers
    $.ajax({
        url: '/filter-manufacturers',
        type: 'POST',
        data: {
            _token: _token,
            ...currentFilters
        },
        success: function(response) {
            hideLoading();
            if (response.success) {
                updateManufacturersDisplay(response.manufacturers);
            } else {
                console.error('Filter error:', response.message);
                filterManufacturersClientSide();
            }
        },
        error: function(xhr) {
            hideLoading();
            console.error('Filter error:', xhr);
            filterManufacturersClientSide();
        }
    });
}

function updateManufacturersDisplay(manufacturers) {
    const container = $('.all_menufacturers');
    const emptyResults = $('.empty_results');
    
    if (manufacturers.length === 0) {
        container.addClass('hidden');
        emptyResults.removeClass('hidden');
    } else {
        container.removeClass('hidden');
        emptyResults.addClass('hidden');
        
        // Clear existing content
        container.html('');
        
        // Create manufacturer HTML for each item
        manufacturers.forEach(manufacturer => {
            const manufacturerHtml = createManufacturerHtml(manufacturer);
            container.append(manufacturerHtml);
        });
        
        // Reinitialize Owl Carousel for new items
        initializeCarousels();
    }
}

// createManufacturerHtml function remains the same as your previous version
function createManufacturerHtml(manufacturer) {
    const factoryPictures = manufacturer.factory_pictures || [];
    const hasFactoryPictures = factoryPictures.length > 0 && factoryPictures.some(pic => pic.image);
    
    const standards = manufacturer.standards || [];
    
    const isVerified = manufacturer.status == 5 && manufacturer.subscription == 1;
    const isNewProduct = manufacturer.year_established >= (new Date().getFullYear() - 2);
    
    // Build carousel HTML
    let carouselHtml = '';
    if (hasFactoryPictures) {
        factoryPictures.forEach((picture, index) => {
            if (picture.image) {
                carouselHtml += `
                    <div class="item">
                        <img src="${picture.image}" alt="${picture.title || 'Factory Picture'}" class="w-full h-[200px] object-cover rounded-lg">
                    </div>
                `;
            }
        });
    }
    
    // Add default images if no factory pictures
    if (!hasFactoryPictures) {
        carouselHtml += `
            <div class="item">
                <img src="/assets/images/menufacturer_camera.png" alt="Default Factory Image" class="w-full h-[200px] object-cover rounded-lg">
            </div>
            <div class="item">
                <img src="/assets/images/menufacturer_company.jpeg" alt="Default Company Image" class="w-full h-[200px] object-cover rounded-lg">
            </div>
            <div class="item">
                <img src="/assets/images/menufacturer_three.jpg" alt="Default Manufacturing Image" class="w-full h-[200px] object-cover rounded-lg">
            </div>
        `;
    }
    
    // Build tags HTML
    let tagsHtml = '';
    const tags = [];
    
    if (manufacturer.industry_category) tags.push(manufacturer.industry_category);
    if (manufacturer.main_product_category) tags.push(manufacturer.main_product_category);
    if (manufacturer.business_type) tags.push(manufacturer.business_type);
    
    // Add up to 3 tags
    tags.slice(0, 3).forEach(tag => {
        tagsHtml += `<div class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">${tag}</div>`;
    });
    
    // Add "+X" tag if there are more than 3 tags
    if (tags.length > 3) {
        tagsHtml += `<div class="bg-[#f6f6f6] text-[#46484d] rounded-full font-normal text-sm px-3 py-1.5 border border-gray-300">+${tags.length - 3}</div>`;
    }
    
    // Build badges HTML
    let badgesHtml = '';
    if (isVerified) {
        badgesHtml += `<span class="flex gap-2 rounded-full items-center px-3 py-1 bg-green-100 text-[#05660c] text-xs absolute top-4 right-4 z-10">
            <img src="/assets/images/guard.png" alt="Verified" class="h-4">
            <span>Verified</span>
        </span>`;
    }
    if (isNewProduct) {
        badgesHtml += `<span class="flex gap-2 rounded-full items-center px-3 py-1 bg-blue-100 text-[#003FB4] text-xs absolute top-4 left-4 z-10">
            <span>New</span>
        </span>`;
    }
    
    const companyNameSlug = manufacturer.company_name_en ? manufacturer.company_name_en.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '') : 'company';
    
    return `
        <div class="item border border-gray-400 rounded-lg p-4 manufacturer-item" 
             data-categories="${(manufacturer.industry_category + ',' + manufacturer.main_product_category).toLowerCase()}"
             data-business-type="${(manufacturer.business_type || '').toLowerCase()}"
             data-standards="${standards.map(s => s.toLowerCase()).join(',')}"
             data-moq="${manufacturer.moq || 0}"
             data-verified="${isVerified}"
             data-country="${(manufacturer.company_address_en || '').toLowerCase()}"
             data-search-text="${(manufacturer.company_name_en + ' ' + (manufacturer.company_name_ko || '') + ' ' + (manufacturer.business_introduction || '') + ' ' + (manufacturer.industry_category || '') + ' ' + (manufacturer.main_product_category || '')).toLowerCase()}"
             data-product-type="${isNewProduct ? 'new' : 'old'}">
            
            <div class="relative">
                <div class="owl-carousel owl-theme owl_items">
                    ${carouselHtml}
                </div>
                ${badgesHtml}
            </div>

            <div class="flex gap-4 mt-4">
                ${manufacturer.company_logo ? 
                    `<img src="${manufacturer.company_logo}" alt="${manufacturer.company_name_en || 'Company Logo'}" class="w-[64px] h-[64px] rounded-full object-cover border border-gray-300">` :
                    `<img src="/assets/images/menufacturer.png" alt="Default Company Logo" class="w-[64px] h-[64px] rounded-full border border-gray-300">`
                }
                <div class="user_details flex-1">
                    <a href="/manufacturers/${companyNameSlug}/${manufacturer.manufacturer_uid}" class="font-medium text-xl lg:text-[24px] text-gray-900 hover:text-[#003FB4] transition-colors duration-200 block">
                        ${manufacturer.company_name_en || 'Manufacturer Name'}
                    </a>
                    <p class="text-gray-600 flex items-center gap-1 font-normal mt-2">
                        <img src="/assets/images/map-pin.png" alt="Location" class="h-4">
                        <span class="truncate">${manufacturer.company_address_en || 'Location not specified'}</span>
                    </p>
                    <p class="text-gray-600 flex items-center gap-1 font-normal mt-2">
                        <img src="/assets/images/star.png" alt="Rating" class="h-4 w-4">
                        <span>${parseFloat(manufacturer.rating).toFixed(1)} (${manufacturer.total_ratings} reviews)</span>
                    </p>
                </div>
            </div>

            <div class="related_tags flex gap-2 flex-wrap mt-4 mb-6">
                ${tagsHtml}
            </div>

            <a class="border border-[#46484d] text-[#46484d] rounded-full font-normal text-sm px-3 py-1 inline-block hover:bg-[#003FB4] hover:border-[#003FB4] hover:text-white"
                href="/manufacturers/${companyNameSlug}/${manufacturer.manufacturer_uid}">
                View Products
            </a>
        </div>
    `;
}

function initializeCarousels() {
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
            0: { items: 1 },
            600: { items: 1 },
            1000: { items: 1 }
        }
    });
}

function filterManufacturersClientSide() {
    const items = document.querySelectorAll('.manufacturer-item');
    let visibleCount = 0;

    items.forEach(item => {
        let shouldShow = true;

        // Filter by search
        if (currentFilters.search) {
            const searchText = item.dataset.searchText || '';
            if (!searchText.includes(currentFilters.search.toLowerCase())) {
                shouldShow = false;
            }
        }

        // Filter by product type
        if (currentFilters.product_type !== 'all') {
            const productType = item.dataset.productType || '';
            if (currentFilters.product_type === 'new' && productType !== 'new') {
                shouldShow = false;
            } else if (currentFilters.product_type === 'refurbished') {
                const categories = item.dataset.categories || '';
                const businessType = item.dataset.businessType || '';
                if (!categories.includes('refurbished') && !businessType.includes('refurbished')) {
                    shouldShow = false;
                }
            }
        }

        // Filter by categories
        if (currentFilters.categories.length > 0) {
            const itemCategories = item.dataset.categories || '';
            const hasMatchingCategory = currentFilters.categories.some(cat => 
                itemCategories.includes(cat.toLowerCase())
            );
            if (!hasMatchingCategory) shouldShow = false;
        }

        // Filter by business type
        if (currentFilters.business_types.length > 0) {
            const businessType = item.dataset.businessType || '';
            const hasMatchingBusinessType = currentFilters.business_types.some(type => 
                businessType.includes(type.toLowerCase())
            );
            if (!hasMatchingBusinessType) shouldShow = false;
        }

        // Filter by certifications/standards
        if (currentFilters.certifications.length > 0) {
            const standards = item.dataset.standards || '';
            const hasMatchingCert = currentFilters.certifications.some(cert => 
                standards.includes(cert.toLowerCase())
            );
            if (!hasMatchingCert) shouldShow = false;
        }

        // Filter by MOQ
        if (currentFilters.moq > 0) {
            const moq = parseInt(item.dataset.moq) || 0;
            if (moq > currentFilters.moq) shouldShow = false;
        }

        // Filter by verified status
        if (currentFilters.verified_only) {
            const isVerified = item.dataset.verified === 'true';
            if (!isVerified) shouldShow = false;
        }

        // Filter by country
        if (currentFilters.country) {
            const country = item.dataset.country || '';
            if (!country.includes(currentFilters.country.toLowerCase())) shouldShow = false;
        }

        // Show/hide item
        item.style.display = shouldShow ? 'block' : 'none';
        if (shouldShow) visibleCount++;
    });

    // Show empty state if no items visible
    const emptyResults = document.querySelector('.empty_results');
    const allManufacturers = document.querySelector('.all_menufacturers');
    
    if (visibleCount === 0) {
        allManufacturers.classList.add('hidden');
        emptyResults.classList.remove('hidden');
    } else {
        allManufacturers.classList.remove('hidden');
        emptyResults.classList.add('hidden');
    }
}

function showLoading() {
    if (!$('#loadingSpinner').length) {
        $('body').append(`
            <div id="loadingSpinner" class="fixed inset-0 bg-white bg-opacity-80 z-50 flex items-center justify-center">
                <div class="animate-spin rounded-full h-16 w-16 border-b-2 border-[#003FB4]"></div>
            </div>
        `);
    }
    $('#loadingSpinner').removeClass('hidden');
}

function hideLoading() {
    $('#loadingSpinner').addClass('hidden');
}

function loadMoreMenufacturer() {
    alert("All manufacturers loaded");
}

// Initialize carousels on page load
$(document).ready(function() {
    initializeCarousels();
});