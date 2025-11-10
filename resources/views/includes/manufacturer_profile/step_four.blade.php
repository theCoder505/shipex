<div class="step-content px-4 py-8 lg:px-8 {{ $step == 4 ? 'active' : '' }}" data-step="4">
    <h2 class="text-3xl lg:text-[40px] mb-8">Trust & Verification</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Do you have a Quality Management System? <span class="text-gray-500">*</span>
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
            </div>

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
            </div>

            <div class="mb-8">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Select the standards you comply with
                </label>
                <div class="space-y-3">
                    @php
                        $standards = $profile_data->standards ?? [];
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
                        <input type="checkbox" name="standards[]" value="RoHS"
                            {{ in_array('RoHS', $standards) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-700">RoHS</span>
                    </label>
                    <label class="flex items-center">
                        <input type="checkbox" name="standards[]" value="Other"
                            {{ in_array('Other', $standards) ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-700">Other</span>
                    </label>
                </div>
            </div>

            <h3 class="text-lg font-semibold mb-6">Factory pictures (max. 5)</h3>
            <div id="factory_pictures_container" class="lg:w-1/2">
                @if ($profile_data->factory_pictures && count($profile_data->factory_pictures) > 0)
                    @foreach ($profile_data->factory_pictures as $index => $picture)
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
                                                    Picture Title <span class="text-gray-500">*</span>
                                                </label>
                                                <button type="button"
                                                    class="remove-factory-picture text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                                            </div>
                                        @else
                                            <label class="block text-sm text-gray-700 mb-2">
                                                Picture Title <span class="text-gray-500">*</span>
                                            </label>
                                        @endif
                                        <input type="text" name="factory_pictures[{{ $index }}][title]"
                                            required value="{{ $picture['title'] ?? '' }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Factory Picture Upload <span class="text-gray-500">*</span>
                                        </label>
                                        <div class="file-upload-area {{ isset($picture['image']) ? 'has-image' : '' }}"
                                            data-upload="factory_pic_{{ $index }}">
                                            <input type="file" name="factory_pictures[{{ $index }}][image]"
                                                accept="image/*" {{ isset($picture['image']) ? '' : 'required' }}
                                                class="hidden">
                                            <img src="{{ isset($picture['image']) ? asset($picture['image']) : '' }}"
                                                class="file-preview" alt="Factory picture preview"
                                                style="{{ isset($picture['image']) ? '' : 'display: none;' }}">
                                            <div class="upload-placeholder"
                                                style="{{ isset($picture['image']) ? 'display: none;' : '' }}">
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
                                        </div>
                                        @if (isset($picture['image']))
                                            <p class="text-xs text-gray-500 mt-2">Current image uploaded. Upload new to
                                                replace.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="factory-picture-item mb-4">
                        <div class="flex gap-4 justify-between">
                            <div class="flex items-start justify-between mb-3">
                                <span
                                    class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">1</span>
                            </div>
                            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Picture Title <span class="text-gray-500">*</span>
                                    </label>
                                    <input type="text" name="factory_pictures[0][title]" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Factory Picture Upload <span class="text-gray-500">*</span>
                                    </label>
                                    <div class="file-upload-area" data-upload="factory_pic_0">
                                        <input type="file" name="factory_pictures[0][image]" accept="image/*"
                                            required class="hidden">
                                        <img src="" class="file-preview" alt="Factory picture preview">
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
                    </div>
                @endif
            </div>
            <button type="button" id="add_factory_picture_btn"
                class="text-blue-600 font-medium text-sm hover:text-blue-700">
                + Add Picture
            </button>

            <hr class="my-12 text-gray-400">

            <h3 class="text-lg font-semibold mb-6 mt-8">Catalogue</h3>
            <div class="mb-6 lg:w-1/2">
                <label class="block text-sm text-gray-700 mb-2">
                    Please upload your Product Catalogue or Brochure
                </label>
                <div class="file-upload-area {{ $profile_data->catalogue ? 'has-image' : '' }}"
                    data-upload="catalogue">
                    <input type="file" name="catalogue" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden"
                        id="catalogue">
                    <img src="{{ $profile_data->catalogue ? asset($profile_data->catalogue) : '' }}"
                        class="file-preview" alt="Catalogue preview"
                        style="{{ $profile_data->catalogue ? '' : 'display: none;' }}">
                    <div class="upload-placeholder" style="{{ $profile_data->catalogue ? 'display: none;' : '' }}">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                            </path>
                        </svg>
                        <p class="text-sm text-gray-600 mb-1">Drag & drop the file here or <span
                                class="text-blue-600 underline cursor-pointer">select file</span></p>
                        <p class="text-xs text-gray-500">Accepted files: word, pdf, jpg, png</p>
                        <p class="text-xs text-gray-500 file-name mt-1"></p>
                    </div>
                </div>
                @if ($profile_data->catalogue)
                    <p class="text-xs text-gray-500 mt-2">Current catalogue uploaded. Upload new to replace.</p>
                @endif
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
        <div class="text-sm text-gray-500 lg:absolute left-0">
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
