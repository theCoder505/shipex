<div class="step-content px-4 py-8 lg:px-8">
    <h2 class="text-3xl lg:text-[40px] mb-8">Trust & Verification</h2>

    @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('manufacturer.application.step.save', ['step' => 4]) }}" method="POST"
        enctype="multipart/form-data" id="stepForm">
        @csrf
        @method('POST')

        <input type="hidden" name="next_step" value="5">
        <input type="hidden" name="action" id="formAction" value="next">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <!-- Quality Management System -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Do you have a Quality Management System? <span class="text-red-500">*</span>
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="has_qms" value="yes" required
                                {{ old('has_qms', $profile_data->has_qms ?? '') == 'yes' ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Yes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="has_qms" value="no" required
                                {{ old('has_qms', $profile_data->has_qms ?? '') == 'no' ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">No</span>
                        </label>
                    </div>
                    @error('has_qms')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Factory Audit Available -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Is Factory Audit available?
                    </label>
                    <div class="space-y-3">
                        <label class="flex items-center">
                            <input type="radio" name="factory_audit_available" value="yes"
                                {{ old('factory_audit_available', $profile_data->factory_audit_available ?? '') == 'yes' ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Yes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="factory_audit_available" value="no"
                                {{ old('factory_audit_available', $profile_data->factory_audit_available ?? '') == 'no' ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">No</span>
                        </label>
                    </div>
                    @error('factory_audit_available')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Standards Compliance -->
                <div class="mb-8">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Select the standards you comply with
                    </label>
                    <div class="space-y-3">
                        @php
                            $standards = old('standards', $profile_data->standards ?? []);
                        @endphp
                        <label class="flex items-center">
                            <input type="checkbox" name="standards[]" value="EU"
                                {{ in_array('EU', $standards) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">EU</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="standards[]" value="US"
                                {{ in_array('US', $standards) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">US</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="standards[]" value="ROHS"
                                {{ in_array('ROHS', $standards) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">ROHS</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="standards[]" value="Other"
                                {{ in_array('Other', $standards) ? 'checked' : '' }}
                                class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-3 text-sm text-gray-700">Other</span>
                        </label>
                    </div>
                    @error('standards')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Factory Pictures -->
                <h3 class="text-lg font-semibold mb-6">Factory pictures (max. 5)</h3>
                <div id="factory_pictures_container" class="lg:w-1/2">
                    @php
                        $factory_pictures = old('factory_pictures', $profile_data->factory_pictures ?? []);
                        if (empty($factory_pictures)) {
                            $factory_pictures = [['title' => '', 'image' => '']];
                        }
                    @endphp

                    @foreach ($factory_pictures as $index => $picture)
                        <div class="factory-picture-item mb-4">
                            <div class="flex gap-4 justify-between">
                                <div class="flex items-start justify-between mb-3">
                                    <span
                                        class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">{{ $index + 1 }}</span>
                                </div>
                                <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                                    <div class="mb-4">
                                        @if ($index > 0)
                                            <div class="flex justify-between gap-4 mb-2">
                                                <label class="block text-sm text-gray-700 mb-2">
                                                    Picture Title <span class="text-red-500">*</span>
                                                </label>
                                                <button type="button"
                                                    class="remove-factory-picture text-red-600 hover:text-red-700 text-sm font-medium"
                                                    onclick="removeItem(this, 'factory-picture')">Remove</button>
                                            </div>
                                        @else
                                            <label class="block text-sm text-gray-700 mb-2">
                                                Picture Title <span class="text-red-500">*</span>
                                            </label>
                                        @endif
                                        <input type="text" name="factory_pictures[{{ $index }}][title]"
                                            required
                                            value="{{ old("factory_pictures.$index.title", $picture['title'] ?? '') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error("factory_pictures.$index.title") border-red-500 @enderror">
                                        @error("factory_pictures.$index.title")
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Factory Picture Upload <span class="text-red-500">*</span>
                                        </label>
                                        <div class="file-upload-area {{ isset($picture['image']) && $picture['image'] ? 'has-image' : '' }} @error("factory_pictures.$index.image") border-red-500 @enderror"
                                            data-upload="factory_pic_{{ $index }}">
                                            <input type="file" name="factory_pictures[{{ $index }}][image]"
                                                accept="image/*" class="hidden"
                                                {{ $index == 0 && empty($picture['image']) ? 'required' : '' }}>
                                            @if (isset($picture['image']) && $picture['image'])
                                                <img src="{{ asset($picture['image']) }}" class="file-preview"
                                                    alt="Factory picture preview">
                                                <div class="upload-placeholder" style="display: none;">
                                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                        </path>
                                                    </svg>
                                                    <p class="text-sm text-gray-600">Drag & drop the file here or <span
                                                            class="text-blue-600 underline cursor-pointer">select
                                                            file</span></p>
                                                    <p class="text-xs text-gray-500 file-name mt-1"></p>
                                                </div>
                                            @else
                                                <img src="" class="file-preview"
                                                    alt="Factory picture preview" style="display: none;">
                                                <div class="upload-placeholder">
                                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                        </path>
                                                    </svg>
                                                    <p class="text-sm text-gray-600">Drag & drop the file here or <span
                                                            class="text-blue-600 underline cursor-pointer">select
                                                            file</span>
                                                    </p>
                                                    <p class="text-xs text-gray-500 file-name mt-1"></p>
                                                </div>
                                            @endif
                                        </div>
                                        @if (isset($picture['image']) && $picture['image'])
                                            <p class="text-xs text-gray-500 mt-2">Current image uploaded. Upload new to
                                                replace.</p>
                                        @endif
                                        @error("factory_pictures.$index.image")
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add_factory_picture_btn"
                    class="text-blue-600 font-medium text-sm hover:text-blue-700">
                    + Add Picture
                </button>
                @error('factory_pictures')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <hr class="my-12 text-gray-400">

                <!-- Catalogue -->
                <h3 class="text-lg font-semibold mb-6 mt-8">Catalogue</h3>
                <div class="mb-6 lg:w-1/2">
                    <label class="block text-sm text-gray-700 mb-2">
                        Please upload your Product Catalogue or Brochure
                    </label>
                    <div class="file-upload-area {{ isset($profile_data->catalogue) && $profile_data->catalogue ? 'has-image' : '' }} @error('catalogue') border-red-500 @enderror"
                        data-upload="catalogue">
                        <input type="file" name="catalogue" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                            class="hidden" id="catalogue">
                        @if (isset($profile_data->catalogue) && $profile_data->catalogue)
                            @if (Str::endsWith($profile_data->catalogue, ['.jpg', '.jpeg', '.png']))
                                <img src="{{ asset($profile_data->catalogue) }}" class="file-preview"
                                    alt="Catalogue preview">
                            @else
                                <div class="file-document-preview p-4 bg-gray-100 rounded-lg">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                        </path>
                                    </svg>
                                    <p class="text-sm text-gray-600 text-center">Catalogue uploaded</p>
                                </div>
                            @endif
                            <div class="upload-placeholder" style="display: none;">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-600 mb-1">Drag & drop the file here or <span
                                        class="text-blue-600 underline cursor-pointer">select file</span></p>
                                <p class="text-xs text-gray-500">Accepted files: word, pdf, jpg, png</p>
                                <p class="text-xs text-gray-500 file-name mt-1"></p>
                            </div>
                        @else
                            <img src="" class="file-preview" alt="Catalogue preview" style="display: none;">
                            <div class="upload-placeholder">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-600 mb-1">Drag & drop the file here or <span
                                        class="text-blue-600 underline cursor-pointer">select file</span></p>
                                <p class="text-xs text-gray-500">Accepted files: word, pdf, jpg, png</p>
                                <p class="text-xs text-gray-500 file-name mt-1"></p>
                            </div>
                        @endif
                    </div>
                    @if (isset($profile_data->catalogue) && $profile_data->catalogue)
                        <p class="text-xs text-gray-500 mt-2">Current catalogue uploaded. Upload new to replace.</p>
                    @endif
                    @error('catalogue')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
                        <a href="mailto:{{ $contact_mail }}"
                            class="text-blue-600 hover:underline">{{ $contact_mail }}</a>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid lg:flex justify-center gap-4 items-center relative">
            <div class="text-sm text-gray-500 lg:absolute left-0">
                <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                    </path>
                </svg>
                Auto-saved when you click Next
            </div>
            <div class="flex gap-4">
                <a href="/manufacturer/application/step/3"
                    class="prev-btn text-blue-600 px-6 py-3 rounded-lg font-medium hover:bg-blue-50 transition border border-blue-600">
                    ← Previous
                </a>
                <button type="button" onclick="submitForm('next')"
                    class="next-btn bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
                    Next →
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    function submitForm(action) {
        document.getElementById('formAction').value = action;
        document.getElementById('stepForm').submit();
    }

    function removeItem(button, type) {
        const item = button.closest(`.${type}-item`);
        if (item && confirm('Are you sure you want to remove this item?')) {
            item.remove();
            renumberItems(`.${type}-item`);
        }
    }

    function renumberItems(selector) {
        document.querySelectorAll(selector).forEach((item, index) => {
            const numberSpan = item.querySelector('.w-8.h-8');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Add Factory Picture
        document.getElementById('add_factory_picture_btn')?.addEventListener('click', function() {
            const container = document.getElementById('factory_pictures_container');
            const count = container.querySelectorAll('.factory-picture-item').length;

            if (count >= 5) {
                alert('Maximum 5 factory pictures allowed');
                return;
            }

            const newPicture = createFactoryPictureHTML(count);
            container.insertAdjacentHTML('beforeend', newPicture);
            initializeFileUploads();
        });
    });

    function createFactoryPictureHTML(index) {
        return `
        <div class="factory-picture-item mb-4">
            <div class="flex gap-4 justify-between">
                <div class="flex items-start justify-between mb-3">
                    <span class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">${index + 1}</span>
                </div>
                <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                    <div class="mb-4">
                        <div class="flex justify-between gap-4 mb-2">
                            <label class="block text-sm text-gray-700 mb-2">
                                Picture Title <span class="text-red-500">*</span>
                            </label>
                            <button type="button" class="remove-factory-picture text-red-600 hover:text-red-700 text-sm font-medium" onclick="removeItem(this, 'factory-picture')">Remove</button>
                        </div>
                        <input type="text" name="factory_pictures[${index}][title]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Factory Picture Upload <span class="text-red-500">*</span>
                        </label>
                        <div class="file-upload-area" data-upload="factory_pic_${index}">
                            <input type="file" name="factory_pictures[${index}][image]" accept="image/*" required class="hidden">
                            <img src="" class="file-preview" alt="Factory picture preview" style="display: none;">
                            <div class="upload-placeholder">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                    </path>
                                </svg>
                                <p class="text-sm text-gray-600">Drag & drop the file here or <span
                                        class="text-blue-600 underline cursor-pointer">select file</span>
                                </p>
                                <p class="text-xs text-gray-500 file-name mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }

    function initializeFileUploads() {
        const fileUploadAreas = document.querySelectorAll('.file-upload-area');

        fileUploadAreas.forEach(area => {
            const input = area.querySelector('input[type="file"]');
            const preview = area.querySelector('.file-preview');
            const placeholder = area.querySelector('.upload-placeholder');
            const fileName = area.querySelector('.file-name');

            if (area.hasAttribute('data-initialized')) return;
            area.setAttribute('data-initialized', 'true');

            area.addEventListener('click', function(e) {
                if (!e.target.closest('.remove-btn')) {
                    input.click();
                }
            });

            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const file = this.files[0];
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);

                    if (fileName) {
                        fileName.textContent = `${file.name} (${fileSizeMB}MB)`;
                    }

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
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                        area.classList.add('has-image');
                    }
                }
            });

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
    }

    // Initialize file uploads on page load
    initializeFileUploads();
</script>
