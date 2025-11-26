<div class="step-content px-4 py-8 lg:px-8 {{ $step == 2 ? 'active' : '' }}" data-step="2">
    <h2 class="text-3xl lg:text-[40px] mb-8">Business Profile</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Business Types <span class="text-gray-500">*</span>
                    </label>
                    <select name="business_type" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="Manufacturer, OEM, ODM, Exporter" {{ old('business_type', $profile_data->business_type ?? '') == 'Manufacturer, OEM, ODM, Exporter' ? 'selected' : '' }}>Manufacturer, OEM, ODM, Exporter</option>
                        <option value="Manufacturer" {{ old('business_type', $profile_data->business_type ?? '') == 'Manufacturer' ? 'selected' : '' }}>Manufacturer</option>
                        <option value="OEM" {{ old('business_type', $profile_data->business_type ?? '') == 'OEM' ? 'selected' : '' }}>OEM</option>
                        <option value="ODM" {{ old('business_type', $profile_data->business_type ?? '') == 'ODM' ? 'selected' : '' }}>ODM</option>
                        <option value="Exporter" {{ old('business_type', $profile_data->business_type ?? '') == 'Exporter' ? 'selected' : '' }}>Exporter</option>
                        <option value="Refurbished" {{ old('business_type', $profile_data->business_type ?? '') == 'Refurbished' ? 'selected' : '' }}>Refurbished</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Industry Categories <span class="text-gray-500">*</span>
                    </label>
                    <select name="industry_category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">Select category</option>
                        <option value="Electronics" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                        <option value="Textiles" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Textiles' ? 'selected' : '' }}>Textiles</option>
                        <option value="Machinery" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Machinery' ? 'selected' : '' }}>Machinery</option>
                        <option value="Chemicals" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Chemicals' ? 'selected' : '' }}>Chemicals</option>
                        <option value="Food & Beverage" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Food & Beverage' ? 'selected' : '' }}>Food & Beverage</option>
                        <option value="Automotive" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Automotive' ? 'selected' : '' }}>Automotive</option>
                        <option value="Other" {{ old('industry_category', $profile_data->industry_category ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="mb-6 col-span-2">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Business Registration Number <span class="text-gray-500">*</span>
                        </label>
                        <input type="text" name="business_registration_number" required value="{{ old('business_registration_number', $profile_data->business_registration_number ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mb-6 col-span-2">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Business Registration License <span class="text-gray-500">*</span>
                            <svg class="inline w-4 h-4 text-gray-400 cursor-help" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" title="Upload your business registration document">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </label>
                        <div class="file-upload-area {{ $profile_data->business_registration_license ? 'has-image' : '' }}" data-upload="business_license">
                            <input type="file" name="business_registration_license" accept=".pdf,.jpg,.jpeg,.png"
                                {{ $profile_data->business_registration_license ? '' : 'required' }} class="hidden" id="business_license">
                            <img src="{{ $profile_data->business_registration_license ? asset($profile_data->business_registration_license) : '' }}" 
                                class="file-preview" alt="Business license preview" style="{{ $profile_data->business_registration_license ? '' : 'display: none;' }}">
                            <div class="upload-placeholder" style="{{ $profile_data->business_registration_license ? 'display: none;' : '' }}">
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
                        @if($profile_data->business_registration_license)
                            <p class="text-xs text-gray-500 mt-2">Current file uploaded. Upload new file to replace.</p>
                        @endif
                    </div>
                </div>

                <div class="mb-6 col-span-2">
                    <div class="lg:w-1/2">
                        <label class="block text-sm font-medium text-gray-700 mb-3">
                            Do you have Export Experience? <span class="text-gray-500">*</span>
                        </label>
                        <div class="space-y-3">
                            <label class="flex items-center">
                                <input type="radio" name="export_experience" value="yes" required
                                    {{ old('export_experience', $profile_data->export_experience ?? '') == 'yes' ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">Yes, I have already exported in the past</span>
                            </label>
                            <label class="flex items-center">
                                <input type="radio" name="export_experience" value="no" required
                                    {{ old('export_experience', $profile_data->export_experience ?? '') == 'no' ? 'checked' : '' }}
                                    class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                                <span class="ml-3 text-sm text-gray-700">No, I have never exported my products</span>
                            </label>
                        </div>

                        <div class="mb-6 col-span-2 {{ ($profile_data->export_experience ?? '') == 'yes' ? '' : 'hidden' }} mt-8" id="export_years_field">
                            <label class="block text-sm text-gray-700 mb-2">
                                How many years of Export Experience do you have? <span class="text-gray-500">*</span>
                            </label>
                            <input type="number" name="export_years" min="1" value="{{ old('export_years', $profile_data->export_years ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
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

    <div class="grid lg:flex justify-center gap-4 items-center relative">
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
        <button type="button"
            class="next-btn bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
            Next →
        </button>
    </div>
</div>