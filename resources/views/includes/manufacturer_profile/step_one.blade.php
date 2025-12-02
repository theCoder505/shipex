<div class="step-content px-4 py-8 lg:px-8">
    <h2 class="text-3xl lg:text-[40px] mb-8">Company Information</h2>

    <form action="{{ route('manufacturer.application.step.save', ['step' => 1]) }}" method="POST"
        enctype="multipart/form-data" id="stepForm">
        @csrf
        @method('POST')

        <input type="hidden" name="next_step" value="2">
        <input type="hidden" name="action" id="formAction" value="next">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <h3 class="text-lg font-semibold mb-6">Company Details</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Your Name -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Your Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" required
                            value="{{ old('name', $profile_data->name ?? '') }}" placeholder="eg: Alfred Joseph"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name (English) -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Company name (English) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="company_name_en" required
                            value="{{ old('company_name_en', $profile_data->company_name_en ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name_en') border-red-500 @enderror">
                        @error('company_name_en')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Name (Korean) -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Company name (Korean)
                        </label>
                        <input type="text" name="company_name_ko"
                            value="{{ old('company_name_ko', $profile_data->company_name_ko ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_name_ko') border-red-500 @enderror">
                        @error('company_name_ko')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Address (English) -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Company address (English) <span class="text-red-500">*</span>
                        </label>
                        <textarea name="company_address_en" required rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_address_en') border-red-500 @enderror">{{ old('company_address_en', $profile_data->company_address_en ?? '') }}</textarea>
                        @error('company_address_en')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Address (Korean) -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Company address (Korean)
                        </label>
                        <textarea name="company_address_ko" rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_address_ko') border-red-500 @enderror">{{ old('company_address_ko', $profile_data->company_address_ko ?? '') }}</textarea>
                        @error('company_address_ko')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Company Google Location -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Company Google Location
                        </label>
                        <textarea name="company_google_location" rows="3"
                            placeholder="Add your company google map location. Place the iframe code here."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('company_google_location') border-red-500 @enderror">{{ old('company_google_location', $profile_data->company_google_location ?? '') }}</textarea>
                        @error('company_google_location')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Optional: Paste Google Maps iframe embed code</p>
                    </div>

                    <!-- Year Established -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Year established <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="year_established" required min="1900" max="{{ date('Y') }}"
                            value="{{ old('year_established', $profile_data->year_established ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('year_established') border-red-500 @enderror">
                        @error('year_established')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Number of Employees -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Number of Employees <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="number_of_employees" required min="1"
                            value="{{ old('number_of_employees', $profile_data->number_of_employees ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('number_of_employees') border-red-500 @enderror">
                        @error('number_of_employees')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Company Logo -->
                <div class="mb-6 mt-4">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Company Logo <span class="text-red-500">*</span>
                        </label>
                        <div class="file-upload-area {{ isset($profile_data->company_logo) && $profile_data->company_logo ? 'has-image' : '' }} @error('company_logo') border-red-500 @enderror"
                            data-upload="company_logo">
                            <input type="file" name="company_logo" accept="image/*" class="hidden" id="company_logo"
                                {{ empty($profile_data->company_logo) ? 'required' : '' }}>
                            @if (isset($profile_data->company_logo) && $profile_data->company_logo)
                                <img src="{{ asset($profile_data->company_logo) }}" class="file-preview"
                                    alt="Company logo preview">
                                <div class="upload-placeholder" style="display: none;">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-1">Drag & drop the file here or <span
                                            class="text-blue-600 underline cursor-pointer">select file</span>
                                    </p>
                                    <p class="text-xs text-gray-500 file-name"></p>
                                </div>
                            @else
                                <img src="" class="file-preview" alt="Company logo preview"
                                    style="display: none;">
                                <div class="upload-placeholder">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-600 mb-1">Drag & drop the file here or <span
                                            class="text-blue-600 underline cursor-pointer">select file</span>
                                    </p>
                                    <p class="text-xs text-gray-500 file-name"></p>
                                </div>
                            @endif
                        </div>
                        @if (isset($profile_data->company_logo) && $profile_data->company_logo)
                            <p class="text-xs text-gray-500 mt-2">Current file uploaded. Upload new file to replace.
                            </p>
                        @endif
                        @error('company_logo')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">Max file size: 5MB. Supported formats: JPG, JPEG, PNG</p>
                    </div>
                </div>

                <!-- Website -->
                <div class="mb-6">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Website
                        </label>
                        <input type="url" name="website" placeholder="https://"
                            value="{{ old('website', $profile_data->website ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('website') border-red-500 @enderror">
                        @error('website')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Business Introduction -->
                <div class="mb-6">
                    <div class="lg:w-1/2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Short business introduction <span class="text-red-500">*</span>
                        </label>
                        <textarea name="business_introduction" required rows="4" maxlength="400"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('business_introduction') border-red-500 @enderror"
                            oninput="updateCharCount(this, 'intro_count')">{{ old('business_introduction', $profile_data->business_introduction ?? '') }}</textarea>
                        <div class="text-right text-sm text-gray-500 mt-1">
                            <span
                                id="intro_count">{{ strlen(old('business_introduction', $profile_data->business_introduction ?? '')) }}</span>/400
                        </div>
                        @error('business_introduction')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <h3 class="text-lg font-semibold mb-6 mt-8">Contact Person</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Primary Contact Name -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Primary Contact Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="contact_name" required
                            value="{{ old('contact_name', $profile_data->contact_name ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_name') border-red-500 @enderror">
                        @error('contact_name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Position -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Position <span class="text-red-500">*</span>
                        </label>
                        <select name="contact_position" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_position') border-red-500 @enderror">
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
                        @error('contact_position')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Email Address -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Email address <span class="text-red-500">*</span>
                        </label>
                        <input type="email" name="contact_email" required
                            value="{{ old('contact_email', $profile_data->contact_email ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_email') border-red-500 @enderror">
                        @error('contact_email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" name="contact_phone" required placeholder="+82 000 000 000"
                            value="{{ old('contact_phone', $profile_data->contact_phone ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('contact_phone') border-red-500 @enderror">
                        <p class="text-xs text-gray-500 mt-1">Start with the country code (e.g. +82 000 000 000)</p>
                        @error('contact_phone')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="my-12 text-gray-400">

                <div class="flex justify-between items-center">
                    <div class="text-sm text-gray-500">
                        <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        Auto-saved when you click Next
                    </div>
                    <div class="flex gap-4">
                        @if ($step > 1)
                            <button type="button" onclick="submitForm('previous')"
                                class="prev-btn text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition border border-blue-600">
                                ← Previous
                            </button>
                        @endif
                        <button type="button" onclick="submitForm('next')"
                            class="next-btn bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                            Next →
                        </button>
                    </div>
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
                        <a href="mailto:{{ $contact_mail }}"
                            class="text-blue-600 hover:underline">{{ $contact_mail }}</a>
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function submitForm(action) {
        document.getElementById('formAction').value = action;
        document.getElementById('stepForm').submit();
    }

    function updateCharCount(textarea, countId) {
        const count = textarea.value.length;
        const countElement = document.getElementById(countId);
        if (countElement) {
            countElement.textContent = count;
        }
    }

    // File upload functionality
    document.addEventListener('DOMContentLoaded', function() {
        const fileUploadAreas = document.querySelectorAll('.file-upload-area');

        fileUploadAreas.forEach(area => {
            const input = area.querySelector('input[type="file"]');
            const preview = area.querySelector('.file-preview');
            const placeholder = area.querySelector('.upload-placeholder');
            const fileName = area.querySelector('.file-name');

            // Click to upload
            area.addEventListener('click', function(e) {
                if (!e.target.closest('.remove-btn')) {
                    input.click();
                }
            });

            // File input change
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

                    // Update file name display
                    if (fileName) {
                        fileName.textContent = `${file.name} (${fileSizeMB}MB)`;
                    }

                    // Preview for images
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            if (preview) {
                                preview.src = e.target.result;
                                preview.style.display = 'block';
                            }
                            if (placeholder) {
                                placeholder.style.display = 'none';
                            }
                            area.classList.add('has-image');
                        };
                        reader.readAsDataURL(file);
                    } else {
                        // For non-image files
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                        area.classList.add('has-image');
                    }
                }
            });

            // Drag and drop
            area.addEventListener('dragover', function(e) {
                e.preventDefault();
                area.classList.add('dragover');
            });

            area.addEventListener('dragleave', function() {
                area.classList.remove('dragover');
            });

            area.addEventListener('drop', function(e) {
                e.preventDefault();
                area.classList.remove('dragover');

                if (e.dataTransfer.files.length) {
                    input.files = e.dataTransfer.files;
                    input.dispatchEvent(new Event('change'));
                }
            });
        });
    });
</script>

<style>
    .file-upload-area {
        border: 2px dashed #d1d5db;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        min-height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .file-upload-area:hover {
        border-color: #3b82f6;
        background-color: #f8fafc;
    }

    .file-upload-area.dragover {
        border-color: #3b82f6;
        background-color: #eff6ff;
    }

    .file-upload-area.has-image {
        border-style: solid;
        padding: 0.5rem;
        min-height: auto;
    }

    .file-preview {
        max-width: 100%;
        max-height: 180px;
        object-fit: contain;
        border-radius: 0.375rem;
    }

    .upload-placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .file-upload-area.has-image .upload-placeholder {
        display: none;
    }

    .file-name {
        word-break: break-all;
    }

    .border-red-500 {
        border-color: #ef4444;
    }
</style>
