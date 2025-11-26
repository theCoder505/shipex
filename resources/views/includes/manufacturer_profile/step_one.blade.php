<div class="step-content px-4 py-8 lg:px-8 {{ $step == 1 ? 'active' : '' }}" data-step="1">
    <h2 class="text-3xl lg:text-[40px] mb-8">Company Information</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <h3 class="text-lg font-semibold mb-6">Company Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Your Name <span class="text-gray-500">*</span>
                    </label>
                    <input type="text" name="name" required
                        value="{{ old('name', $profile_data->name ?? '') }}" placeholder="eg: Alfred Joseph"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Company name (English) <span class="text-gray-500">*</span>
                    </label>
                    <input type="text" name="company_name_en" required
                        value="{{ old('company_name_en', $profile_data->company_name_en ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Company name (Korean)
                    </label>
                    <input type="text" name="company_name_ko"
                        value="{{ old('company_name_ko', $profile_data->company_name_ko ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Company address (English) <span class="text-gray-500">*</span>
                    </label>
                    <textarea name="company_address_en" required rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('company_address_en', $profile_data->company_address_en ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Company address (Korean)
                    </label>
                    <textarea name="company_address_ko" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('company_address_ko', $profile_data->company_address_ko ?? '') }}</textarea>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Company Google Location
                    </label>
                    <textarea name="company_google_location" rows="3" placeholder="Add your company google map location. Place the iframe code here."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('company_google_location', $profile_data->company_google_location ?? '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Year established <span class="text-gray-500">*</span>
                    </label>
                    <input type="number" name="year_established" required min="1900" max="2025"
                        value="{{ old('year_established', $profile_data->year_established ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Number of Employees <span class="text-gray-500">*</span>
                    </label>
                    <input type="number" name="number_of_employees" required min="1"
                        value="{{ old('number_of_employees', $profile_data->number_of_employees ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6 mt-4">
                <div class="mb-6 col-span-2">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Company Logo <span class="text-gray-500">*</span>
                        </label>
                        <div class="file-upload-area {{ $profile_data->company_logo ? 'has-image' : '' }}"
                            data-upload="company_logo">
                            <input type="file" name="company_logo" accept="image/*"
                                {{ $profile_data->company_logo ? '' : 'required' }} class="hidden" id="company_logo">
                            <img src="{{ $profile_data->company_logo ? asset($profile_data->company_logo) : '' }}"
                                class="file-preview" alt="Company logo preview"
                                style="{{ $profile_data->company_logo ? '' : 'display: none;' }}">
                            <div class="upload-placeholder"
                                style="{{ $profile_data->company_logo ? 'display: none;' : '' }}">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-600 mb-1">Drag & drop the file here or <span
                                        class="text-blue-600 underline cursor-pointer">select file</span>
                                </p>
                                <p class="text-xs text-gray-500 file-name"></p>
                            </div>
                        </div>
                        @if ($profile_data->company_logo)
                            <p class="text-xs text-gray-500 mt-2">Current file uploaded. Upload new file to replace.</p>
                        @endif
                    </div>
                </div>

                <div class="mb-6 col-span-2">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Website
                        </label>
                        <input type="url" name="website" placeholder="https://"
                            value="{{ old('website', $profile_data->website ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mb-6 col-span-2">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Short business introduction <span class="text-gray-500">*</span>
                        </label>
                        <textarea name="business_introduction" required rows="4" maxlength="400"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            oninput="updateCharCount(this, 'intro_count')">{{ old('business_introduction', $profile_data->business_introduction ?? '') }}</textarea>
                        <div class="text-right text-sm text-gray-500 mt-1">
                            <span id="intro_count">{{ strlen($profile_data->business_introduction ?? '') }}</span>/400
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-6 mt-8">Contact Person</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Primary Contact Name <span class="text-gray-500">*</span>
                    </label>
                    <input type="text" name="contact_name" required
                        value="{{ old('contact_name', $profile_data->contact_name ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Position <span class="text-gray-500">*</span>
                    </label>
                    <select name="contact_position" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select position</option>
                        <option value="CEO"
                            {{ old('contact_position', $profile_data->contact_position ?? '') == 'CEO' ? 'selected' : '' }}>
                            CEO</option>
                        <option value="Manager"
                            {{ old('contact_position', $profile_data->contact_position ?? '') == 'Manager' ? 'selected' : '' }}>
                            Manager</option>
                        <option value="Director"
                            {{ old('contact_position', $profile_data->contact_position ?? '') == 'Director' ? 'selected' : '' }}>
                            Director</option>
                        <option value="Sales Representative"
                            {{ old('contact_position', $profile_data->contact_position ?? '') == 'Sales Representative' ? 'selected' : '' }}>
                            Sales Representative</option>
                        <option value="Other"
                            {{ old('contact_position', $profile_data->contact_position ?? '') == 'Other' ? 'selected' : '' }}>
                            Other</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Email address <span class="text-gray-500">*</span>
                    </label>
                    <input type="email" name="contact_email" required
                        value="{{ old('contact_email', $profile_data->contact_email ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Phone Number <span class="text-gray-500">*</span>
                    </label>
                    <input type="tel" name="contact_phone" required placeholder="+82 000 000 000"
                        value="{{ old('contact_phone', $profile_data->contact_phone ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Start with the country code (e.g. +82 000 000 000)</p>
                </div>
            </div>

            <hr class="my-12 text-gray-400">

            <div class="flex justify-between items-center">
                <div class="text-sm text-gray-500 hidden">
                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                    Auto-saved
                </div>
                <button type="button"
                    class="next-btn bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Next →
                </button>
            </div>
        </div>

        <div>
            <div class="help-box sticky top-24 lg:mt-20">
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
</div>
