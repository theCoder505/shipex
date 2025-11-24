<div class="step-content px-4 py-8 lg:px-8 {{ $step == 3 ? 'active' : '' }}" data-step="3">
    <h2 class="text-3xl lg:text-[40px] mb-8">Product & Capability Information</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="mb-6 lg:w-1/2">
                <label class="block text-sm text-gray-700 mb-2">
                    Main Product Categories <span class="text-gray-500">*</span>
                </label>
                <select name="main_product_category" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Select category</option>
                    <option value="medical"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'medical' ? 'selected' : '' }}>
                        Medical
                    </option>

                    <option value="automobile"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'automobile' ? 'selected' : '' }}>
                        Automobile
                    </option>

                    <option value="agrochemical"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'agrochemical' ? 'selected' : '' }}>
                        Agrochemical
                    </option>

                    <option value="technology"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'technology' ? 'selected' : '' }}>
                        Technology
                    </option>

                    <option value="military"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'military' ? 'selected' : '' }}>
                        Military
                    </option>

                    <option value="cosmetics"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'cosmetics' ? 'selected' : '' }}>
                        Cosmetics
                    </option>

                    <option value="fashion"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'fashion' ? 'selected' : '' }}>
                        Fashion
                    </option>
                        
                    <option value="secondhand"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'secondhand' ? 'selected' : '' }}>
                        Secondhand
                    </option>
                        
                    <option value="Other"
                        {{ old('main_product_category', $profile_data->main_product_category ?? '') == 'Other' ? 'selected' : '' }}>
                        Other
                    </option>
                </select>
            </div>

            <hr class="my-12 text-gray-400">

            <div class="mb-8 lg:w-1/2">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Your Key Products (max. 5)
                </label>
                <div id="products_container">
                    @if ($profile_data->products && count($profile_data->products) > 0)
                        @foreach ($profile_data->products as $index => $product)
                            <div class="product-item mb-4 flex gap-2">
                                <div class="flex items-start justify-between mb-3">
                                    <span
                                        class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">{{ $index + 1 }}</span>
                                </div>
                                <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                                    <div class="mb-4">
                                        @if ($index > 0)
                                            <div class="flex justify-between gap-4 mb-2">
                                                <label class="block text-sm text-gray-700 mb-2">
                                                    Product Name <span class="text-gray-500">*</span>
                                                </label>
                                                <button type="button"
                                                    class="remove-product text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                                            </div>
                                        @else
                                            <label class="block text-sm text-gray-700 mb-2">
                                                Product Name <span class="text-gray-500">*</span>
                                            </label>
                                        @endif
                                        <input type="text" name="products[{{ $index }}][name]" required
                                            value="{{ $product['name'] ?? '' }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Product Image <span class="text-gray-500">*</span>
                                        </label>
                                        <div class="file-upload-area {{ isset($product['image']) ? 'has-image' : '' }}"
                                            data-upload="product_{{ $index }}">
                                            <input type="file" name="products[{{ $index }}][image]"
                                                accept="image/*" {{ isset($product['image']) ? '' : 'required' }}
                                                class="hidden">
                                            <img src="{{ isset($product['image']) ? asset($product['image']) : '' }}"
                                                class="file-preview" alt="Product image preview"
                                                style="{{ isset($product['image']) ? '' : 'display: none;' }}">
                                            <div class="upload-placeholder"
                                                style="{{ isset($product['image']) ? 'display: none;' : '' }}">
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
                                        @if (isset($product['image']))
                                            <p class="text-xs text-gray-500 mt-2">Current image uploaded. Upload new to
                                                replace.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="product-item mb-4 flex gap-2">
                            <div class="flex items-start justify-between mb-3">
                                <span
                                    class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">1</span>
                            </div>
                            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                                <div class="mb-4">
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Product Name <span class="text-gray-500">*</span>
                                    </label>
                                    <input type="text" name="products[0][name]" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Product Image <span class="text-gray-500">*</span>
                                    </label>
                                    <div class="file-upload-area" data-upload="product_0">
                                        <input type="file" name="products[0][image]" accept="image/*" required
                                            class="hidden">
                                        <img src="" class="file-preview" alt="Product image preview">
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
                    @endif
                </div>
                <button type="button" id="add_product_btn"
                    class="text-blue-600 font-medium text-sm hover:text-blue-700">
                    + Add Product
                </button>
            </div>

            <hr class="my-12 text-gray-400">

            <h3 class="text-lg font-semibold mb-6">Capacity</h3>
            <div class="mb-6 lg:w-1/2">
                <div class="mb-6">
                    <label class="block text-sm text-gray-700 mb-2">
                        Production Capacity <span class="text-gray-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                        <div>
                            <input type="number" name="production_capacity" required
                                value="{{ old('production_capacity', $profile_data->production_capacity ?? '') }}"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full">
                        </div>
                        <div>
                            <select name="production_capacity_unit" required
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent block w-full">
                                <option value="pcs/month"
                                    {{ old('production_capacity_unit', $profile_data->production_capacity_unit ?? '') == 'pcs/month' ? 'selected' : '' }}>
                                    pcs/month</option>
                                <option value="pcs/year"
                                    {{ old('production_capacity_unit', $profile_data->production_capacity_unit ?? '') == 'pcs/year' ? 'selected' : '' }}>
                                    pcs/year</option>
                                <option value="tons/month"
                                    {{ old('production_capacity_unit', $profile_data->production_capacity_unit ?? '') == 'tons/month' ? 'selected' : '' }}>
                                    tons/month</option>
                                <option value="tons/year"
                                    {{ old('production_capacity_unit', $profile_data->production_capacity_unit ?? '') == 'tons/year' ? 'selected' : '' }}>
                                    tons/year</option>
                            </select>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Enter a number and select the unit</p>
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Minimum Order Quantity (MOQ) <span class="text-gray-500">*</span>
                    </label>
                    <input type="number" name="moq" required value="{{ old('moq', $profile_data->moq ?? '') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>

            <hr class="my-12 text-gray-400">

            <h3 class="text-lg font-semibold mb-6">Certifications</h3>
            <div id="certifications_container" class="mb-4 lg:w-1/2">
                @if ($profile_data->certifications && count($profile_data->certifications) > 0)
                    @foreach ($profile_data->certifications as $index => $certification)
                        <div class="certification-item mb-4 flex gap-2">
                            <div class="flex items-start justify-between mb-3">
                                <span
                                    class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center text-sm font-medium">{{ $index + 1 }}</span>
                            </div>
                            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                                <div class="mb-4">
                                    @if ($index > 0)
                                        <div class="flex justify-between gap-4 mb-2">
                                            <label class="block text-sm text-gray-700">
                                                Certification Name <span class="text-gray-500">*</span>
                                            </label>
                                            <button type="button"
                                                class="remove-certification text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                                        </div>
                                    @else
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Certification Name <span class="text-gray-500">*</span>
                                        </label>
                                    @endif
                                    <input type="text" name="certifications[{{ $index }}][name]" required
                                        value="{{ $certification['name'] ?? '' }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Certification Document <span class="text-gray-500">*</span>
                                    </label>
                                    <div class="file-upload-area {{ isset($certification['document']) ? 'has-image' : '' }}"
                                        data-upload="cert_{{ $index }}">
                                        <input type="file" name="certifications[{{ $index }}][document]"
                                            accept=".pdf,.jpg,.jpeg,.png"
                                            {{ isset($certification['document']) ? '' : 'required' }} class="hidden">
                                        <img src="{{ isset($certification['document']) ? asset($certification['document']) : '' }}"
                                            class="file-preview" alt="Certification document preview"
                                            style="{{ isset($certification['document']) ? '' : 'display: none;' }}">
                                        <div class="upload-placeholder"
                                            style="{{ isset($certification['document']) ? 'display: none;' : '' }}">
                                            <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                                </path>
                                            </svg>
                                            <p class="text-sm text-gray-600">Drag & drop the file here or <span
                                                    class="text-blue-600 underline cursor-pointer">select file</span>
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE,ROHS, etc.)</p>
                                            <p class="text-xs text-gray-500 file-name"></p>
                                        </div>
                                    </div>
                                    @if (isset($certification['document']))
                                        <p class="text-xs text-gray-500 mt-2">Current document uploaded. Upload new to
                                            replace.</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="certification-item mb-4 flex gap-2">
                        <div class="flex items-start justify-between mb-3">
                            <span
                                class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center text-sm font-medium">1</span>
                        </div>
                        <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                            <div class="mb-4">
                                <label class="block text-sm text-gray-700 mb-2">
                                    Certification Name <span class="text-gray-500">*</span>
                                </label>
                                <input type="text" name="certifications[0][name]" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">
                                    Certification Document <span class="text-gray-500">*</span>
                                </label>
                                <div class="file-upload-area" data-upload="cert_0">
                                    <input type="file" name="certifications[0][document]"
                                        accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                                    <img src="" class="file-preview" alt="Certification document preview">
                                    <div class="upload-placeholder">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Drag & drop the file here or <span
                                                class="text-blue-600 underline cursor-pointer">select file</span></p>
                                        <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE,ROHS, etc.)</p>
                                        <p class="text-xs text-gray-500 file-name"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" id="add_certification_btn"
                class="text-blue-600 font-medium text-sm hover:text-blue-700">
                + Add Certification
            </button>

            <h3 class="text-lg font-semibold mb-6 mt-8">Patents & Proprietary Technology</h3>
            <div class="mb-6 lg:w-1/2">
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    Do you have any patents or proprietary technology? <span class="text-gray-500">*</span>
                </label>
                <div class="space-y-3">
                    <label class="flex items-center">
                        <input type="radio" name="has_patents" value="yes" required
                            {{ old('has_patents', $profile_data->has_patents ?? '') == 'yes' ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-700">Yes</span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" name="has_patents" value="no" required
                            {{ old('has_patents', $profile_data->has_patents ?? '') == 'no' ? 'checked' : '' }}
                            class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500">
                        <span class="ml-3 text-sm text-gray-700">No</span>
                    </label>
                </div>
            </div>

            <div id="patents_container"
                class="{{ ($profile_data->has_patents ?? '') == 'yes' ? '' : 'hidden' }} lg:w-1/2">
                @if ($profile_data->patents && count($profile_data->patents) > 0)
                    @foreach ($profile_data->patents as $index => $patent)
                        <div class="patents-item mb-4 flex gap-2">
                            <div class="flex items-start justify-between mb-3">
                                <span
                                    class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">{{ $index + 1 }}</span>
                            </div>
                            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                                <div class="mb-4">
                                    @if ($index > 0)
                                        <div class="flex gap-2 justify-between">
                                            <label class="block text-sm text-gray-700 mb-2">
                                                Please upload your patents and relevant certificates <span
                                                    class="text-gray-500">*</span>
                                            </label>
                                            <button type="button"
                                                class="remove-patent text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                                        </div>
                                    @else
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Please upload your patents and relevant certificates <span
                                                class="text-gray-500">*</span>
                                        </label>
                                    @endif
                                    <div class="file-upload-area {{ isset($patent['document']) ? 'has-image' : '' }}"
                                        data-upload="patent_{{ $index }}">
                                        <input type="file" name="patents[{{ $index }}][document]"
                                            accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                        <img src="{{ isset($patent['document']) ? asset($patent['document']) : '' }}"
                                            class="file-preview" alt="Patent document preview"
                                            style="{{ isset($patent['document']) ? '' : 'display: none;' }}">
                                        <div class="upload-placeholder"
                                            style="{{ isset($patent['document']) ? 'display: none;' : '' }}">
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
                                    @if (isset($patent['document']))
                                        <p class="text-xs text-gray-500 mt-2">Current document uploaded. Upload new to
                                            replace.</p>
                                    @endif
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Patent or Certification Description <span class="text-gray-500">*</span>
                                    </label>
                                    <textarea name="patents[{{ $index }}][description]" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ $patent['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="patents-item mb-4 flex gap-2">
                        <div class="flex items-start justify-between mb-3">
                            <span
                                class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">1</span>
                        </div>
                        <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                            <div class="mb-4">
                                <label class="block text-sm text-gray-700 mb-2">
                                    Please upload your patents and relevant certificates <span
                                        class="text-gray-500">*</span>
                                </label>
                                <div class="file-upload-area" data-upload="patent_0">
                                    <input type="file" name="patents[0][document]" accept=".pdf,.jpg,.jpeg,.png"
                                        class="hidden">
                                    <img src="" class="file-preview" alt="Patent document preview">
                                    <div class="upload-placeholder">
                                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-600">Drag & drop the file here or <span
                                                class="text-blue-600 underline cursor-pointer">select file</span></p>
                                        <p class="text-xs text-gray-500 file-name mt-1"></p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm text-gray-700 mb-2">
                                    Patent or Certification Description <span class="text-gray-500">*</span>
                                </label>
                                <textarea name="patents[0][description]" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" id="add_patent_btn"
                class="text-blue-600 font-medium text-sm hover:text-blue-700 {{ ($profile_data->has_patents ?? '') == 'yes' ? '' : 'hidden' }}">
                + Add Proprietary Technology
            </button>

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
