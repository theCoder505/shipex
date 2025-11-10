@extends('layouts.surface.app')

@section('title', 'Create an account as a wholesaler')

@section('style')
    <style>
        .error-message {
            color: #dc2626;
            font-size: 0.875rem;
            margin-top: 0.25rem;
            display: none;
        }

        .input-error {
            border-color: #dc2626 !important;
            box-shadow: 0 0 0 1px #dc2626 !important;
        }

        .input-success {
            border-color: #16a34a !important;
        }

        .enabled_acc_btn:disabled {
            background-color: #9ca3af !important;
            cursor: not-allowed;
        }

        .category-filter:has(input:checked) {
            background-color: #003FB4 !important;
            color: #fff !important;
        }

        .category-filter.bg-\[\#003FB4\] {
            background-color: #003FB4 !important;
            color: #fff !important;
        }
    </style>
@endsection

@section('content')
    <div class="progress_bar col-span-2 grid grid-cols-3 overflow-hidden">
        <div class="almost bar_filled"></div>
        <div class="company_information bar_unfilled"></div>
        <div class="category_selection bar_unfilled"></div>
    </div>


    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px]">
        <section class="min-h-screen flex items-center justify-center bg-white px-4">
            <div class="px-2 lg:px-8 w-full my-10 grid grid-cols-1 md:grid-cols-2 gap-8">

                <!-- Image for mobile -->
                <div class="block md:hidden">
                    <img src="/assets/images/boxes.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>


                <form method="POST" action="/wholesaler/complete-profile-setup" class="all_forms">
                    @csrf
                    <div class="flex flex-col md:px-9" id="almost">
                        <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                            Almost there!
                        </h2>


                        <div class="max-w-[400px] mt-4">
                            <div class="mb-4">
                                <label for="company_name" class="text-xs text-gray-700 mb-2 block">
                                    Company name *
                                </label>
                                <input type="text" id="company_name" name="company_name"
                                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                    value="{{ old('company_name', $wholesaler->company_name ?? '') }}" required>
                                <div id="company_nameError" class="error-message"></div>
                            </div>

                            <div class="flex gap-2 justify-end">
                                <button type="button" id="nextBtn" onclick="next()"
                                    class="enabled_acc_btn mt-4 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg px-6"
                                    disabled>
                                    Next →
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-col md:px-9 hidden" id="company_information">
                        <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                            Tell us more about <span
                                class="comp_name">{{ $wholesaler->company_name ?? '[company name]' }}</span>
                        </h2>


                        <div class="mt-4">
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">
                                <div class="">
                                    <label for="businessType" class="text-xs text-gray-700 mb-2 block">
                                        Business Type *
                                    </label>
                                    <input type="text" placeholder="Enter your primary business focus"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                        required id="businessType" name="businessType"
                                        value="{{ $wholesaler->business_type }}">
                                    <div id="businessTypeError" class="error-message"></div>
                                </div>


                                <div class="">
                                    <label for="industryFocus" class="text-xs text-gray-700 mb-2 block">
                                        Industry Focus *
                                    </label>
                                    <input type="text" placeholder="Enter your primary industry focus"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                        required id="industryFocus" name="industryFocus"
                                        value="{{ $wholesaler->industry_focus }}">
                                    <div id="industryFocusError" class="error-message"></div>
                                </div>


                                <div class="">
                                    <label for="country" class="text-xs text-gray-700 mb-2 block">
                                        Country *
                                    </label>
                                    <select id="country" name="country"
                                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-1 focus:ring-[#003FB4]"
                                        required>
                                        @include('includes.country_options')
                                    </select>
                                    <div id="countryError" class="error-message"></div>
                                </div>
                            </div>


                            <div class="flex gap-2 justify-end">
                                <button type="button" id="previousBtn" onclick="previous()"
                                    class="mt-4 font-normal text-[#003FB4] hover:bg-[#002F86] hover:text-white rounded-lg px-4 py-2">
                                    ← Previous
                                </button>

                                <button type="button" id="nextBtn" onclick="next()"
                                    class="enabled_acc_btn mt-4 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg px-6"
                                    disabled>
                                    Next →
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="flex flex-col md:px-9 hidden" id="category_selection">
                        <h2 class="text-xl lg:text-[40px] text-[#121212] mb-1">
                            Please select your preferred product categories
                        </h2>


                        <div class="mt-8">
                            <div class="flex gap-4 flex-wrap">
                                @php
                                    $selectedCategories = old('category', $wholesaler->category ?? []);
                                    if (!is_array($selectedCategories)) {
                                        $selectedCategories = [];
                                    }
                                @endphp

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('medical', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/medical.png" alt="Medical"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Medical</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="medical"
                                        {{ in_array('medical', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('automobile', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/auto.png" alt="Automobile"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Automobile & Spare Parts</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="automobile"
                                        {{ in_array('automobile', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('agrochemical', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/agrochemical.png" alt="Agro-chemical"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Agro-chemical</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="agrochemical"
                                        {{ in_array('agrochemical', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('technology', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/tech.png" alt="Technology"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Technology</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="technology"
                                        {{ in_array('technology', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('military', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/military.png" alt="Military"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Military</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="military"
                                        {{ in_array('military', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('cosmetics', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/cosmetics.png" alt="Cosmetics"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Cosmetics</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="cosmetics"
                                        {{ in_array('cosmetics', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('fashion', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/fashion.png" alt="Fashion & Textiles"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Fashion & Textiles</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="fashion"
                                        {{ in_array('fashion', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('secondhand', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/second-hand.png" alt="Second-hand"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Second-hand</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="secondhand"
                                        {{ in_array('secondhand', $selectedCategories) ? 'checked' : '' }}>
                                </label>

                                <label
                                    class="p-3 rounded border border-gray-400 flex gap-2 items-center cursor-pointer hover:bg-[#003FB4] text-[#121212] hover:text-white transition-colors category-filter {{ in_array('other', $selectedCategories) ? 'bg-[#003FB4] text-white' : '' }}"
                                    onclick="chooseCategory(this)">
                                    <img src="/assets/images/other.png" alt="Other"
                                        class="w-[32px] h-[32px] lg:w-[40px] lg:h-[40px]">
                                    <div class="pr-4 text-base lg:text-[18px]">Other</div>
                                    <input type="checkbox" name="category[]" class="hidden" value="other"
                                        {{ in_array('other', $selectedCategories) ? 'checked' : '' }}>
                                </label>
                            </div>


                            <div class="flex gap-2 justify-end">
                                <button type="button" id="previousBtn" onclick="previous()"
                                    class="mt-4 font-normal text-[#003FB4] hover:bg-[#002F86] hover:text-white rounded-lg px-4 py-2">
                                    ← Previous
                                </button>

                                <button type="submit" id="submitBtn"
                                    class="enabled_acc_btn mt-4 font-normal bg-[#003FB4] hover:bg-[#002F86] text-white py-2 rounded-lg px-6"
                                    disabled>
                                    Submit →
                                </button>
                            </div>
                        </div>
                    </div>
                </form>


                <!-- Right Image -->
                <div class="hidden md:block">
                    <img src="/assets/images/wholesaler_boxes.png" alt="SHIPEX"
                        class="w-full h-full object-cover rounded-lg shadow-md">
                </div>
            </div>
        </section>
    </section>
@endsection

@section('scripts')
    <script>
        let currentStep = 1;
        const totalSteps = 3;
        const preselectedCountry = "{{ old('country', $wholesaler->country ?? '') }}";

        function updateProgressBar() {
            document.querySelectorAll('.progress_bar > div').forEach(div => {
                div.classList.remove('bar_filled', 'bar_unfilled');
            });

            for (let i = 1; i <= totalSteps; i++) {
                const barElement = document.querySelector(`.progress_bar > div:nth-child(${i})`);
                if (i < currentStep) {
                    barElement.classList.add('bar_filled');
                } else if (i === currentStep) {
                    barElement.classList.add('bar_filled');
                } else {
                    barElement.classList.add('bar_unfilled');
                }
            }
        }

        function validateStep(step) {
            let isValid = true;
            document.querySelectorAll('.error-message').forEach(error => {
                error.style.display = 'none';
            });
            document.querySelectorAll('.input-error').forEach(input => {
                input.classList.remove('input-error');
            });

            switch (step) {
                case 1:
                    const companyName = document.getElementById('company_name');
                    if (!companyName.value.trim()) {
                        showError(companyName, 'company_nameError', 'Company name is required');
                        isValid = false;
                    } else {
                        document.querySelectorAll('.comp_name').forEach(el => {
                            el.textContent = companyName.value;
                        });
                    }
                    break;

                case 2:
                    const businessType = document.getElementById('businessType');
                    const industryFocus = document.getElementById('industryFocus');
                    const country = document.getElementById('country');

                    if (!businessType.value) {
                        showError(businessType, 'businessTypeError', 'Business type is required');
                        isValid = false;
                    }

                    if (!industryFocus.value) {
                        showError(industryFocus, 'industryFocusError', 'Industry focus is required');
                        isValid = false;
                    }

                    if (!country.value) {
                        showError(country, 'countryError', 'Country is required');
                        isValid = false;
                    }
                    break;

                case 3:
                    const categories = document.querySelectorAll('input[name="category[]"]:checked');
                    if (categories.length === 0) {
                        alert('Please select at least one product category');
                        isValid = false;
                    }
                    break;
            }

            return isValid;
        }

        function showError(inputElement, errorId, message) {
            const errorElement = document.getElementById(errorId);
            errorElement.textContent = message;
            errorElement.style.display = 'block';
            inputElement.classList.add('input-error');
        }

        function updateNextButtonState() {
            const nextButtons = document.querySelectorAll('#nextBtn, #submitBtn');

            let allFilled = true;

            switch (currentStep) {
                case 1:
                    allFilled = document.getElementById('company_name').value.trim() !== '';
                    break;
                case 2:
                    allFilled = document.getElementById('businessType').value !== '' &&
                        document.getElementById('industryFocus').value !== '' &&
                        document.getElementById('country').value !== '';
                    break;
                case 3:
                    const categories = document.querySelectorAll('input[name="category[]"]:checked');
                    allFilled = categories.length > 0;
                    break;
            }

            nextButtons.forEach(button => {
                if (button.id === 'submitBtn' && currentStep === 3) {
                    button.disabled = !allFilled;
                } else if (button.id === 'nextBtn' && currentStep < 3) {
                    button.disabled = !allFilled;
                }
            });
        }

        function next() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    document.getElementById(getStepId(currentStep)).classList.add('hidden');
                    currentStep++;
                    document.getElementById(getStepId(currentStep)).classList.remove('hidden');

                    updateProgressBar();
                    updateNextButtonState();
                } else if (currentStep === totalSteps) {
                    document.querySelector('form').submit();
                }
            }
        }

        function previous() {
            if (currentStep > 1) {
                document.getElementById(getStepId(currentStep)).classList.add('hidden');

                currentStep--;
                document.getElementById(getStepId(currentStep)).classList.remove('hidden');

                updateProgressBar();
                updateNextButtonState();
            }
        }

        function getStepId(step) {
            switch (step) {
                case 1:
                    return 'almost';
                case 2:
                    return 'company_information';
                case 3:
                    return 'category_selection';
                default:
                    return '';
            }
        }

        function chooseCategory(element) {
            const checkbox = element.querySelector('input[type="checkbox"]');
            checkbox.checked = !checkbox.checked;

            // Toggle the appropriate classes
            if (checkbox.checked) {
                element.classList.add('bg-[#003FB4]', 'text-white');
                element.classList.remove('text-[#121212]');
            } else {
                element.classList.remove('bg-[#003FB4]', 'text-white');
                element.classList.add('text-[#121212]');
            }

            updateNextButtonState();
        }

        function setCountryValue() {
            if (preselectedCountry) {
                const countrySelect = document.getElementById('country');
                if (countrySelect) {
                    countrySelect.value = preselectedCountry;
                }
            }
        }

        function initializeCategoryFilters() {
            document.querySelectorAll('.category-filter').forEach(label => {
                const checkbox = label.querySelector('input[type="checkbox"]');
                // Ensure the visual state matches the checkbox state on page load
                if (checkbox && checkbox.checked) {
                    label.classList.add('bg-[#003FB4]', 'text-white');
                    label.classList.remove('text-[#121212]');
                } else {
                    label.classList.remove('bg-[#003FB4]', 'text-white');
                    label.classList.add('text-[#121212]');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('company_name').addEventListener('input', function() {
                document.querySelectorAll('.comp_name').forEach(el => {
                    el.textContent = this.value || '[company name]';
                });
                updateNextButtonState();
            });

            document.getElementById('businessType').addEventListener('change', updateNextButtonState);
            document.getElementById('industryFocus').addEventListener('change', updateNextButtonState);
            document.getElementById('country').addEventListener('change', updateNextButtonState);

            // Remove the duplicate event listeners for category filters
            // since we're using onclick in the HTML

            setCountryValue();
            initializeCategoryFilters();
            updateProgressBar();
            updateNextButtonState();
        });
    </script>
@endsection
