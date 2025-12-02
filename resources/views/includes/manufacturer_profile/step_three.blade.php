<div class="step-content px-4 py-8 lg:px-8">
    <h2 class="text-3xl lg:text-[40px] mb-8">Product & Capability Information</h2>

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

    <form action="{{ route('manufacturer.application.step.save', ['step' => 3]) }}" method="POST"
        enctype="multipart/form-data" id="stepForm">
        @csrf
        @method('POST')

        <input type="hidden" name="next_step" value="4">
        <input type="hidden" name="action" id="formAction" value="next">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2">
                <!-- Main Product Category -->
                <div class="mb-6 lg:w-1/2">
                    <label class="block text-sm text-gray-700 mb-2">
                        Main Product Categories <span class="text-red-500">*</span>
                    </label>
                    <select name="main_product_category" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('main_product_category') border-red-500 @enderror">
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
                    @error('main_product_category')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="my-12 text-gray-400">

                <!-- Key Products -->
                <div class="mb-8 lg:w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Your Key Products (max. 5)
                    </label>
                    <div id="products_container">
                        @php
                            $products = old('products', $profile_data->products ?? []);
                            if (empty($products)) {
                                $products = [['name' => '', 'image' => '']];
                            }
                        @endphp

                        @foreach ($products as $index => $product)
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
                                                    Product Name <span class="text-red-500">*</span>
                                                </label>
                                                <button type="button"
                                                    class="remove-product text-red-600 hover:text-red-700 text-sm font-medium"
                                                    onclick="removeItem(this, 'product')">Remove</button>
                                            </div>
                                        @else
                                            <label class="block text-sm text-gray-700 mb-2">
                                                Product Name <span class="text-red-500">*</span>
                                            </label>
                                        @endif
                                        <input type="text" name="products[{{ $index }}][name]" required
                                            value="{{ old("products.$index.name", $product['name'] ?? '') }}"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error("products.$index.name") border-red-500 @enderror">
                                        @error("products.$index.name")
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Product Image <span class="text-red-500">*</span>
                                        </label>
                                        <div class="file-upload-area {{ isset($product['image']) && $product['image'] ? 'has-image' : '' }} @error("products.$index.image") border-red-500 @enderror"
                                            data-upload="product_{{ $index }}">
                                            <input type="file" name="products[{{ $index }}][image]"
                                                accept="image/*" class="hidden"
                                                {{ $index == 0 && empty($product['image']) ? 'required' : '' }}>
                                            @if (isset($product['image']) && $product['image'])
                                                <img src="{{ asset($product['image']) }}" class="file-preview"
                                                    alt="Product image preview">
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
                                                <img src="" class="file-preview" alt="Product image preview"
                                                    style="display: none;">
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
                                        @if (isset($product['image']) && $product['image'])
                                            <p class="text-xs text-gray-500 mt-2">Current image uploaded. Upload new to
                                                replace.</p>
                                        @endif
                                        @error("products.$index.image")
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" id="add_product_btn"
                        class="text-blue-600 font-medium text-sm hover:text-blue-700 mt-2">
                        + Add Product
                    </button>
                    @error('products')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <hr class="my-12 text-gray-400">

                <!-- Capacity -->
                <h3 class="text-lg font-semibold mb-6">Capacity</h3>
                <div class="mb-6 lg:w-1/2">
                    <div class="mb-6">
                        <label class="block text-sm text-gray-700 mb-2">
                            Production Capacity <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            <div>
                                <input type="number" name="production_capacity" required
                                    value="{{ old('production_capacity', $profile_data->production_capacity ?? '') }}"
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full @error('production_capacity') border-red-500 @enderror">
                                @error('production_capacity')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <select name="production_capacity_unit" required
                                    class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent block w-full @error('production_capacity_unit') border-red-500 @enderror">
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
                                @error('production_capacity_unit')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Enter a number and select the unit</p>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Minimum Order Quantity (MOQ) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="moq" required
                            value="{{ old('moq', $profile_data->moq ?? '') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('moq') border-red-500 @enderror">
                        @error('moq')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <hr class="my-12 text-gray-400">

                <!-- Certifications -->
                <h3 class="text-lg font-semibold mb-6">Certifications</h3>
                <div id="certifications_container" class="mb-4 lg:w-1/2">
                    @php
                        $certifications = old('certifications', $profile_data->certifications ?? []);
                        if (empty($certifications)) {
                            $certifications = [['name' => '', 'document' => '']];
                        }
                    @endphp

                    @foreach ($certifications as $index => $certification)
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
                                                Certification Name <span class="text-red-500">*</span>
                                            </label>
                                            <button type="button"
                                                class="remove-certification text-red-600 hover:text-red-700 text-sm font-medium"
                                                onclick="removeItem(this, 'certification')">Remove</button>
                                        </div>
                                    @else
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Certification Name <span class="text-red-500">*</span>
                                        </label>
                                    @endif
                                    <input type="text" name="certifications[{{ $index }}][name]" required
                                        value="{{ old("certifications.$index.name", $certification['name'] ?? '') }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error("certifications.$index.name") border-red-500 @enderror">
                                    @error("certifications.$index.name")
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Certification Document <span class="text-red-500">*</span>
                                    </label>
                                    <div class="file-upload-area {{ isset($certification['document']) && $certification['document'] ? 'has-image' : '' }} @error("certifications.$index.document") border-red-500 @enderror"
                                        data-upload="cert_{{ $index }}">
                                        <input type="file" name="certifications[{{ $index }}][document]"
                                            accept=".pdf,.jpg,.jpeg,.png" class="hidden"
                                            {{ $index == 0 && empty($certification['document']) ? 'required' : '' }}>
                                        @if (isset($certification['document']) && $certification['document'])
                                            <img src="{{ asset($certification['document']) }}" class="file-preview"
                                                alt="Certification document preview">
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
                                                        file</span>
                                                </p>
                                                <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE,ROHS, etc.)</p>
                                                <p class="text-xs text-gray-500 file-name"></p>
                                            </div>
                                        @else
                                            <img src="" class="file-preview"
                                                alt="Certification document preview" style="display: none;">
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
                                                        file</span></p>
                                                <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE,ROHS, etc.)</p>
                                                <p class="text-xs text-gray-500 file-name"></p>
                                            </div>
                                        @endif
                                    </div>
                                    @if (isset($certification['document']) && $certification['document'])
                                        <p class="text-xs text-gray-500 mt-2">Current document uploaded. Upload new to
                                            replace.</p>
                                    @endif
                                    @error("certifications.$index.document")
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add_certification_btn"
                    class="text-blue-600 font-medium text-sm hover:text-blue-700 mb-8">
                    + Add Certification
                </button>
                @error('certifications')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <!-- Patents -->
                <h3 class="text-lg font-semibold mb-6 mt-8">Patents & Proprietary Technology</h3>
                <div class="mb-6 lg:w-1/2">
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        Do you have any patents or proprietary technology? <span class="text-red-500">*</span>
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
                    @error('has_patents')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div id="patents_container"
                    class="{{ old('has_patents', $profile_data->has_patents ?? '') == 'yes' ? '' : 'hidden' }} lg:w-1/2">
                    @php
                        $patents = old('patents', $profile_data->patents ?? []);
                        if (empty($patents)) {
                            $patents = [['description' => '', 'document' => '']];
                        }
                    @endphp

                    @foreach ($patents as $index => $patent)
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
                                                Please upload your patents and relevant certificates
                                            </label>
                                            <button type="button"
                                                class="remove-patent text-red-600 hover:text-red-700 text-sm font-medium"
                                                onclick="removeItem(this, 'patent')">Remove</button>
                                        </div>
                                    @else
                                        <label class="block text-sm text-gray-700 mb-2">
                                            Please upload your patents and relevant certificates
                                        </label>
                                    @endif
                                    <div class="file-upload-area {{ isset($patent['document']) && $patent['document'] ? 'has-image' : '' }} @error("patents.$index.document") border-red-500 @enderror"
                                        data-upload="patent_{{ $index }}">
                                        <input type="file" name="patents[{{ $index }}][document]"
                                            accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                                        @if (isset($patent['document']) && $patent['document'])
                                            <img src="{{ asset($patent['document']) }}" class="file-preview"
                                                alt="Patent document preview">
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
                                                        file</span>
                                                </p>
                                                <p class="text-xs text-gray-500 file-name mt-1"></p>
                                            </div>
                                        @else
                                            <img src="" class="file-preview" alt="Patent document preview"
                                                style="display: none;">
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
                                    @if (isset($patent['document']) && $patent['document'])
                                        <p class="text-xs text-gray-500 mt-2">Current document uploaded. Upload new to
                                            replace.</p>
                                    @endif
                                    @error("patents.$index.document")
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-700 mb-2">
                                        Patent or Certification Description <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="patents[{{ $index }}][description]" rows="3"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error("patents.$index.description") border-red-500 @enderror">{{ old("patents.$index.description", $patent['description'] ?? '') }}</textarea>
                                    @error("patents.$index.description")
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button type="button" id="add_patent_btn"
                    class="text-blue-600 font-medium text-sm hover:text-blue-700 {{ old('has_patents', $profile_data->has_patents ?? '') == 'yes' ? '' : 'hidden' }}">
                    + Add Proprietary Technology
                </button>
                @error('patents')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

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
                <a href="/manufacturer/application/step/2"
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
        // Patents toggle
        const hasPatentsRadios = document.querySelectorAll('input[name="has_patents"]');
        hasPatentsRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const patentsContainer = document.getElementById('patents_container');
                const addPatentBtn = document.getElementById('add_patent_btn');
                const patentInputs = patentsContainer.querySelectorAll(
                    'input[required], textarea[required]');

                if (this.value === 'yes') {
                    patentsContainer.classList.remove('hidden');
                    addPatentBtn.classList.remove('hidden');
                    patentInputs.forEach(input => input.setAttribute('required', 'required'));
                } else {
                    patentsContainer.classList.add('hidden');
                    addPatentBtn.classList.add('hidden');
                    patentInputs.forEach(input => input.removeAttribute('required'));
                }
            });
        });

        // Add Product
        document.getElementById('add_product_btn')?.addEventListener('click', function() {
            const container = document.getElementById('products_container');
            const count = container.querySelectorAll('.product-item').length;

            if (count >= 5) {
                alert('Maximum 5 products allowed');
                return;
            }

            const newProduct = createProductHTML(count);
            container.insertAdjacentHTML('beforeend', newProduct);
            initializeFileUploads();
        });

        // Add Certification
        document.getElementById('add_certification_btn')?.addEventListener('click', function() {
            const container = document.getElementById('certifications_container');
            const newCertification = createCertificationHTML(container.querySelectorAll(
                '.certification-item').length);
            container.insertAdjacentHTML('beforeend', newCertification);
            initializeFileUploads();
        });

        // Add Patent
        document.getElementById('add_patent_btn')?.addEventListener('click', function() {
            const container = document.getElementById('patents_container');
            const newPatent = createPatentHTML(container.querySelectorAll('.patents-item').length);
            container.insertAdjacentHTML('beforeend', newPatent);
            initializeFileUploads();
        });

        // Trigger initial state
        const checkedPatentsRadio = document.querySelector('input[name="has_patents"]:checked');
        if (checkedPatentsRadio) {
            checkedPatentsRadio.dispatchEvent(new Event('change'));
        }
    });

    function createProductHTML(index) {
        return `
        <div class="product-item mb-4 flex gap-2">
            <div class="flex items-start justify-between mb-3">
                <span class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">${index + 1}</span>
            </div>
            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                <div class="mb-4">
                    <div class="flex justify-between gap-4 mb-2">
                        <label class="block text-sm text-gray-700 mb-2">
                            Product Name <span class="text-red-500">*</span>
                        </label>
                        <button type="button" class="remove-product text-red-600 hover:text-red-700 text-sm font-medium" onclick="removeItem(this, 'product')">Remove</button>
                    </div>
                    <input type="text" name="products[${index}][name]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Product Image <span class="text-red-500">*</span>
                    </label>
                    <div class="file-upload-area" data-upload="product_${index}">
                        <input type="file" name="products[${index}][image]" accept="image/*" required class="hidden">
                        <img src="" class="file-preview" alt="Product image preview" style="display: none;">
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
        </div>`;
    }

    function createCertificationHTML(index) {
        return `
        <div class="certification-item mb-4 flex gap-2">
            <div class="flex items-start justify-between mb-3">
                <span class="w-8 h-8 border border-gray-300 rounded-full flex items-center justify-center text-sm font-medium">${index + 1}</span>
            </div>
            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                <div class="mb-4">
                    <div class="flex justify-between gap-4 mb-2">
                        <label class="block text-sm text-gray-700">
                            Certification Name <span class="text-red-500">*</span>
                        </label>
                        <button type="button" class="remove-certification text-red-600 hover:text-red-700 text-sm font-medium" onclick="removeItem(this, 'certification')">Remove</button>
                    </div>
                    <input type="text" name="certifications[${index}][name]" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Certification Document <span class="text-red-500">*</span>
                    </label>
                    <div class="file-upload-area" data-upload="cert_${index}">
                        <input type="file" name="certifications[${index}][document]" accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                        <img src="" class="file-preview" alt="Certification document preview" style="display: none;">
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
        </div>`;
    }

    function createPatentHTML(index) {
        return `
        <div class="patents-item mb-4 flex gap-2">
            <div class="flex items-start justify-between mb-3">
                <span class="w-8 h-8 border border-gray-400 rounded-full flex items-center justify-center text-sm font-medium">${index + 1}</span>
            </div>
            <div class="border-l border-gray-300 rounded py-4 pl-4 w-full">
                <div class="mb-4">
                    <div class="flex gap-2 justify-between">
                        <label class="block text-sm text-gray-700 mb-2">
                            Please upload your patents and relevant certificates
                        </label>
                        <button type="button" class="remove-patent text-red-600 hover:text-red-700 text-sm font-medium" onclick="removeItem(this, 'patent')">Remove</button>
                    </div>
                    <div class="file-upload-area" data-upload="patent_${index}">
                        <input type="file" name="patents[${index}][document]" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                        <img src="" class="file-preview" alt="Patent document preview" style="display: none;">
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
                <div>
                    <label class="block text-sm text-gray-700 mb-2">
                        Patent or Certification Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="patents[${index}][description]" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
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
