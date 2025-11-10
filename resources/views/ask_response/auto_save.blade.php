Controller code:

    public function completeProfileSetup(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $profile_data = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        $step = $request->query('step', 1);
        return view('surface.account.menufacturer_profile_complete', compact('profile_data', 'step'));
    }


    public function profileSetupDetails(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        // Get all request data
        $data = $request->except(['_token']);

        // Handle single file uploads
        $this->handleSingleFileUpload($request, $data, 'company_logo', 'company_logo/', $manufacturer);
        $this->handleSingleFileUpload($request, $data, 'business_registration_license', 'business_license/', $manufacturer);
        $this->handleSingleFileUpload($request, $data, 'catalogue', 'catalogue/', $manufacturer);

        // Handle products with images
        if ($request->has('products')) {
            $data['products'] = $this->handleProductsUpload($request, $manufacturer);
        }

        // Handle certifications with documents
        if ($request->has('certifications')) {
            $data['certifications'] = $this->handleCertificationsUpload($request, $manufacturer);
        }

        // Handle patents with documents
        if ($request->has('patents')) {
            $data['patents'] = $this->handlePatentsUpload($request, $manufacturer);
        }

        // Handle factory pictures with images
        if ($request->has('factory_pictures')) {
            $data['factory_pictures'] = $this->handleFactoryPicturesUpload($request, $manufacturer);
        }

        // Handle standards array
        $data['standards'] = $request->has('standards') ? $request->standards : [];

        // Handle boolean fields
        $data['agree_terms'] = $request->has('agree_terms');
        $data['consent_background_check'] = $request->has('consent_background_check');

        // Convert numeric fields to integers
        $data['year_established'] = isset($data['year_established']) ? intval($data['year_established']) : null;
        $data['number_of_employees'] = isset($data['number_of_employees']) ? intval($data['number_of_employees']) : null;
        $data['export_years'] = isset($data['export_years']) ? intval($data['export_years']) : null;
        $data['production_capacity'] = isset($data['production_capacity']) ? intval($data['production_capacity']) : null;
        $data['moq'] = isset($data['moq']) ? intval($data['moq']) : null;

        // Update manufacturer
        $manufacturer->update($data);

        return redirect('/manufacturer/application-successful')->with('success', 'Profile Updated Successfully!');
    }

    /**
     * Handle single file upload with old file preservation
     */
    private function handleSingleFileUpload(Request $request, &$data, $fieldName, $uploadPath, $manufacturer)
    {
        if ($request->hasFile($fieldName)) {
            // Delete old file if exists
            if ($manufacturer->$fieldName && file_exists(public_path($manufacturer->$fieldName))) {
                unlink(public_path($manufacturer->$fieldName));
            }

            $file = $request->file($fieldName);
            $extension = $file->getClientOriginalExtension();
            $filename = $fieldName . '_' . time() . '_' . rand(1000, 9999) . '.' . $extension;
            $file->move(public_path($uploadPath), $filename);
            $data[$fieldName] = $uploadPath . $filename;
        } else {
            // Keep old file if exists
            if ($manufacturer->$fieldName) {
                $data[$fieldName] = $manufacturer->$fieldName;
            }
        }
    }

    /**
     * Handle products upload with old data preservation
     */
    private function handleProductsUpload(Request $request, $manufacturer)
    {
        $oldProducts = $manufacturer->products ?? [];
        $products = [];

        foreach ($request->products as $index => $productData) {
            $product = [
                'name' => $productData['name'] ?? ''
            ];

            // Check if new image is uploaded
            if ($request->hasFile("products.{$index}.image")) {
                // Delete old image if exists
                if (isset($oldProducts[$index]['image']) && file_exists(public_path($oldProducts[$index]['image']))) {
                    unlink(public_path($oldProducts[$index]['image']));
                }

                $file = $request->file("products.{$index}.image");
                $extension = $file->getClientOriginalExtension();
                $filename = 'product_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'products/';
                $file->move(public_path($path), $filename);
                $product['image'] = $path . $filename;
            } else {
                // Keep old image if exists
                if (isset($oldProducts[$index]['image'])) {
                    $product['image'] = $oldProducts[$index]['image'];
                }
            }

            $products[] = $product;
        }

        return $products;
    }

    /**
     * Handle certifications upload with old data preservation
     */
    private function handleCertificationsUpload(Request $request, $manufacturer)
    {
        $oldCertifications = $manufacturer->certifications ?? [];
        $certifications = [];

        foreach ($request->certifications as $index => $certificationData) {
            $certification = [
                'name' => $certificationData['name'] ?? ''
            ];

            // Check if new document is uploaded
            if ($request->hasFile("certifications.{$index}.document")) {
                // Delete old document if exists
                if (isset($oldCertifications[$index]['document']) && file_exists(public_path($oldCertifications[$index]['document']))) {
                    unlink(public_path($oldCertifications[$index]['document']));
                }

                $file = $request->file("certifications.{$index}.document");
                $extension = $file->getClientOriginalExtension();
                $filename = 'certification_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'certifications/';
                $file->move(public_path($path), $filename);
                $certification['document'] = $path . $filename;
            } else {
                // Keep old document if exists
                if (isset($oldCertifications[$index]['document'])) {
                    $certification['document'] = $oldCertifications[$index]['document'];
                }
            }

            $certifications[] = $certification;
        }

        return $certifications;
    }

    /**
     * Handle patents upload with old data preservation
     */
    private function handlePatentsUpload(Request $request, $manufacturer)
    {
        $oldPatents = $manufacturer->patents ?? [];
        $patents = [];

        foreach ($request->patents as $index => $patentData) {
            $patent = [
                'description' => $patentData['description'] ?? ''
            ];

            // Check if new document is uploaded
            if ($request->hasFile("patents.{$index}.document")) {
                // Delete old document if exists
                if (isset($oldPatents[$index]['document']) && file_exists(public_path($oldPatents[$index]['document']))) {
                    unlink(public_path($oldPatents[$index]['document']));
                }

                $file = $request->file("patents.{$index}.document");
                $extension = $file->getClientOriginalExtension();
                $filename = 'patent_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'patents/';
                $file->move(public_path($path), $filename);
                $patent['document'] = $path . $filename;
            } else {
                // Keep old document if exists
                if (isset($oldPatents[$index]['document'])) {
                    $patent['document'] = $oldPatents[$index]['document'];
                }
            }

            $patents[] = $patent;
        }

        return $patents;
    }

    /**
     * Handle factory pictures upload with old data preservation
     */
    private function handleFactoryPicturesUpload(Request $request, $manufacturer)
    {
        $oldFactoryPictures = $manufacturer->factory_pictures ?? [];
        $factoryPictures = [];

        foreach ($request->factory_pictures as $index => $pictureData) {
            $picture = [
                'title' => $pictureData['title'] ?? ''
            ];

            // Check if new image is uploaded
            if ($request->hasFile("factory_pictures.{$index}.image")) {
                // Delete old image if exists
                if (isset($oldFactoryPictures[$index]['image']) && file_exists(public_path($oldFactoryPictures[$index]['image']))) {
                    unlink(public_path($oldFactoryPictures[$index]['image']));
                }

                $file = $request->file("factory_pictures.{$index}.image");
                $extension = $file->getClientOriginalExtension();
                $filename = 'factory_picture_' . time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                $path = 'factory_pictures/';
                $file->move(public_path($path), $filename);
                $picture['image'] = $path . $filename;
            } else {
                // Keep old image if exists
                if (isset($oldFactoryPictures[$index]['image'])) {
                    $picture['image'] = $oldFactoryPictures[$index]['image'];
                }
            }

            $factoryPictures[] = $picture;
        }

        return $factoryPictures;
    }


Route:
Route::get('/manufacturer/application', [MenufacturerCredentialsController::class, 'completeProfileSetup'])->middleware('manufacturer');
Route::post('/manufacturer/complete-application', [MenufacturerCredentialsController::class, 'profileSetupDetails'])->middleware('manufacturer');



menufacturer_profile_complete.blade.php codebase below:
@extends('layouts.surface.app')

@section('title', 'Complete your manufacturer profile')

@section('style')
    <link rel="stylesheet" href="/assets/css/manufacturer_profile.css">
@endsection

@section('content')
    <section class="main mx-auto px-4 lg:px-8 max-w-[1600px] py-8">
        <input type="hidden" class="current_step" value="{{ $step }}">
        <div class="steps pb-3">
            <div class="step_one step_line @if ($step == 1) active_step @endif"></div>
            <div class="step_two step_line @if ($step == 2) active_step @endif"></div>
            <div class="step_three step_line @if ($step == 3) active_step @endif"></div>
            <div class="step_four step_line @if ($step == 4) active_step @endif"></div>
            <div class="step_five step_line @if ($step == 5) active_step @endif"></div>
        </div>
        <div class="px-4 pb-6 lg:px-8">
            <div class="hidden lg:block step-indicator">
                <div class="step-progress" id="stepProgress" style="width: 0%;"></div>
                <div class="grid grid-cols-5 gap-4 relative">
                    <!-- Step 1 -->
                    <div class="step-item @if ($step == 1) active @endif" data-step="1">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 1</div>
                        <div class="step-title mt-2 text-sm font-medium">Company Information</div>
                    </div>

                    <!-- Step 2 -->
                    <div class="step-item @if ($step == 2) active @endif" data-step="2">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 2</div>
                        <div class="step-title mt-2 text-sm font-medium">Business Profile</div>
                    </div>

                    <!-- Step 3 -->
                    <div class="step-item @if ($step == 3) active @endif" data-step="3">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 3</div>
                        <div class="step-title mt-2 text-sm font-medium">Product Information</div>
                    </div>

                    <!-- Step 4 -->
                    <div class="step-item @if ($step == 4) active @endif" data-step="4">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 4</div>
                        <div class="step-title mt-2 text-sm font-medium">Trust & Verifications</div>
                    </div>

                    <!-- Step 5 -->
                    <div class="step-item @if ($step == 5) active @endif" data-step="5">
                        <div class="step-label uppercase text-xs font-semibold mb-2">STEP 5</div>
                        <div class="step-title mt-2 text-sm font-medium">Declaration</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="mobileStepIndicator"
            class="step_of_step uppercase text-[#05660C] font-semibold block lg:hidden mt-[-0.75rem] px-4">
            STEP 1 Out of 5
        </div>

        <div class="bg-white rounded-lg shadow-sm">
            <!-- Form -->
            <form id="profileForm" action="/manufacturer/complete-application" method="POST"
                enctype="multipart/form-data">
                @csrf

                <!-- Step 1: Company Information -->
                @include('includes.manufacturer_profile.step_one')

                <!-- Step 2: Business Profile -->
                @include('includes.manufacturer_profile.step_two')


                <!-- Step 3: Product Information -->
                @include('includes.manufacturer_profile.step_three')


                <!-- Step 4: Trust & Verifications -->
                @include('includes.manufacturer_profile.step_four')


                <!-- Step 5: Declaration -->
                @include('includes.manufacturer_profile.step_five')


                <!-- Step 6: Review & Submit -->
                @include('includes.manufacturer_profile.step_six')

            </form>
        </div>
    </section>
@endsection


@section('scripts')
    <script>
        function toggleAccordion(reviewSection) {
            $(reviewSection).toggleClass("collapsed");
        }
    </script>
    <script src="/assets/js/manufacturer_profile.js"></script>
@endsection






step_one.blade.php code below:
<div class="step-content px-4 py-8 lg:px-8 {{ $step == 1 ? 'active' : '' }}" data-step="1">
    <h2 class="text-3xl lg:text-[40px] mb-8">Company Information</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <h3 class="text-lg font-semibold mb-6">Company Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                <div class="text-sm text-gray-500">
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


step_two.blade.php:
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



step_three.blade.php:
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
                                            <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE, RoHS, etc.)</p>
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
                                        <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE, RoHS, etc.)</p>
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




step_four.blade.php:
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



step_five.blade.php:
<div class="step-content px-4 py-8 lg:px-8 {{ $step == 5 ? 'active' : '' }}" data-step="5">
    <h2 class="text-3xl lg:text-[40px] font-bold mb-8">Declaration</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <div class="space-y-4 mb-8">
                <label class="flex items-start">
                    <input type="checkbox" name="agree_terms" required
                        {{ old('agree_terms', $profile_data->agree_terms ?? false) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                    <span class="ml-3 text-sm text-gray-700">
                        I agree to the <a href="/terms-of-use" target="_blank" class="text-blue-600 underline">Terms of Service</a> <span class="text-gray-500">*</span>
                    </span>
                </label>
                <label class="flex items-start">
                    <input type="checkbox" name="consent_background_check" required
                        {{ old('consent_background_check', $profile_data->consent_background_check ?? false) ? 'checked' : '' }}
                        class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500 mt-1">
                    <span class="ml-3 text-sm text-gray-700">
                        I consent to going through a background check <span class="text-gray-500">*</span>
                    </span>
                </label>
            </div>

            <div class="bg-orange-50 border-l-4 border-orange-400 p-4 mb-8">
                <div class="flex">
                    <svg class="w-6 h-6 text-orange-400 flex-shrink-0" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                        </path>
                    </svg>
                    <div class="ml-3">
                        <p class="text-sm text-orange-800">
                            By typing your full name below, you are providing us with your digital
                            signature, which is legally binding just like a handwritten signature.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-8 lg:w-1/2">
                <label class="block text-sm text-gray-700 mb-2">
                    Digital Signature <span class="text-gray-500">*</span>
                </label>
                <input type="text" name="digital_signature" required value="{{ old('digital_signature', $profile_data->digital_signature ?? '') }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-2">Please enter your full legal name</p>
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




step_six.blade.php:
<div class="step-content px-4 py-8 lg:px-8 {{ $step == 6 ? 'active' : '' }}" data-step="6">
    <h2 class="text-3xl lg:text-[40px] mb-8">Review</h2>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2">
            <!-- Company Information Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="company-info" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Company Information</h3>
                        <button type="button" class="edit-btn" data-step="1">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company name (English):</div>
                        <div class="review-value" id="review-company-name-en">
                            {{ $profile_data->company_name_en ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company name (Korean):</div>
                        <div class="review-value" id="review-company-name-ko">
                            {{ $profile_data->company_name_ko ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company address (English):</div>
                        <div class="review-value" id="review-company-address-en">
                            {{ $profile_data->company_address_en ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Company address (Korean):</div>
                        <div class="review-value" id="review-company-address-ko">
                            {{ $profile_data->company_address_ko ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Year established:</div>
                        <div class="review-value" id="review-year-established">
                            {{ $profile_data->year_established ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Business Registration Number:</div>
                        <div class="review-value" id="review-business-registration-number">
                            {{ $profile_data->business_registration_number ?? '-' }}</div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Business Registration License:</div>
                        <div class="review-value">
                            @if ($profile_data->business_registration_license)
                                <img id="review-business-license"
                                    class="file-preview-small max-w-[100px] rounded border border-gray-200"
                                    src="{{ asset( $profile_data->business_registration_license) }}"
                                    alt="Business License">
                            @else
                                <img id="review-business-license"
                                    class="file-preview-small max-w-[100px] rounded border border-gray-200"
                                    src="" alt="Business License" >
                            @endif
                            <span
                                id="review-business-license-text">{{ $profile_data->business_registration_license ? '' : '-' }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Primary Contact Name:</div>
                        <div class="review-value" id="review-contact-name">{{ $profile_data->contact_name ?? '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Email address:</div>
                        <div class="review-value" id="review-contact-email">{{ $profile_data->contact_email ?? '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Position:</div>
                        <div class="review-value" id="review-contact-position">
                            {{ $profile_data->contact_position ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Phone Number:</div>
                        <div class="review-value" id="review-contact-phone">{{ $profile_data->contact_phone ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Business Profile Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="business-profile" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Business Profile</h3>
                        <button type="button" class="edit-btn" data-step="2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>

                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Business Types:</div>
                        <div class="review-value" id="review-business-type">{{ $profile_data->business_type ?? '-' }}
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Industry Categories:</div>
                        <div class="review-value" id="review-industry-category">
                            {{ $profile_data->industry_category ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Product Information Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="product-info" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Product Information</h3>
                        <button type="button" class="edit-btn" data-step="3">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Main Product Categories:</div>
                        <div class="review-value capitalize" id="review-main-product-category">
                            {{ $profile_data->main_product_category ?? '-' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                        <div class="review-label">Key Products:</div>
                        <div class="review-value">
                            <ul class="review-list space-y-3" id="review-products">
                                @if ($profile_data->products && count($profile_data->products) > 0)
                                    @foreach ($profile_data->products as $product)
                                        <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span
                                                class="font-medium">{{ $product['name'] ?? 'Unnamed Product' }}</span>
                                            @if (isset($product['image']))
                                                <img src="{{ asset( $product['image']) }}"
                                                    class="file-preview-small max-w-[100px] h-auto rounded border border-gray-200"
                                                    alt="{{ $product['name'] ?? 'Product' }}">
                                            @else
                                                <span class="text-xs text-gray-500">No image</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li class="no-data">No products added</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trust & Verification Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="trust-verification" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Trust & Verifications</h3>
                        <button type="button" class="edit-btn" data-step="4">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                        <div class="review-label">Certifications:</div>
                        <div class="review-value">
                            <ul class="review-list space-y-3" id="review-certifications">
                                @if ($profile_data->certifications && count($profile_data->certifications) > 0)
                                    @foreach ($profile_data->certifications as $certification)
                                        <li class="flex justify-between items-center py-2 border-b border-gray-100">
                                            <span
                                                class="font-medium">{{ $certification['name'] ?? 'Unnamed Certification' }}</span>
                                            @if (isset($certification['document']))
                                                <img src="{{ asset( $certification['document']) }}"
                                                    class="file-preview-small max-w-[100px] h-auto rounded border border-gray-200"
                                                    alt="{{ $certification['name'] ?? 'Certification' }}">
                                            @else
                                                <span class="text-xs text-gray-500">No document</span>
                                            @endif
                                        </li>
                                    @endforeach
                                @else
                                    <li class="no-data">No certifications added</li>
                                @endif
                            </ul>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full lg:col-span-2">
                        <div class="review-label">Factory Pictures:</div>
                        <div class="review-value">
                            <div class="review-image-grid grid grid-cols-2 md:grid-cols-3 gap-4"
                                id="review-factory-pictures">
                                @if ($profile_data->factory_pictures && count($profile_data->factory_pictures) > 0)
                                    @foreach ($profile_data->factory_pictures as $picture)
                                        <div class="review-image-item">
                                            @if (isset($picture['image']))
                                                <img src="{{ asset( $picture['image']) }}"
                                                    class="w-full h-48 object-cover rounded border border-gray-200"
                                                    alt="{{ $picture['title'] ?? 'Factory Picture' }}">
                                                <div class="review-image-title text-sm text-gray-600 mt-2">
                                                    {{ $picture['title'] ?? 'Untitled' }}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @else
                                    <div class="no-data">No factory pictures added</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Declaration Review -->
            <div class="review-section collapsed">
                <div class="review-header" data-toggle="declaration" onclick="toggleAccordion(this)">
                    <div class="flex gap-2 flex-wrap">
                        <h3 class="review-title">Declaration</h3>
                        <button type="button" class="edit-btn" data-step="5">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                </path>
                            </svg>
                            Edit
                        </button>
                    </div>
                    <div class="review-header-actions">
                        <button type="button" class="toggle-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="review-content grid grid-cols-1 gap-4 lg:grid-cols-2">
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Agreed to Terms:</div>
                        <div class="review-value" id="review-agree-terms">
                            {{ $profile_data->agree_terms ? 'Yes' : 'No' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Consent to Background Check:</div>
                        <div class="review-value" id="review-consent-background-check">
                            {{ $profile_data->consent_background_check ? 'Yes' : 'No' }}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-2 w-full">
                        <div class="review-label">Digital Signature:</div>
                        <div class="review-value" id="review-digital-signature">
                            {{ $profile_data->digital_signature ?? '-' }}</div>
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

    <div class="grid lg:flex justify-center gap-4 items-center relative mt-8">
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
        <button type="submit"
            class="bg-[#003FB4] text-white px-8 py-3 rounded-lg font-medium hover:bg-blue-700 transition">
            Confirm & apply →
        </button>
    </div>
</div>



assets/js/manufacturer_profile.js below:
document.addEventListener('DOMContentLoaded', function () {
    let currentStep = parseInt(document.querySelector('.current_step')?.value) || 1;
    const totalSteps = 6;
    const visibleSteps = 5;

    // Dynamic counters - initialize based on existing items
    let productCount = document.querySelectorAll('.product-item').length || 1;
    let certificationCount = document.querySelectorAll('.certification-item').length || 1;
    let patentCount = document.querySelectorAll('.patents-item').length || 1;
    let factoryPictureCount = document.querySelectorAll('.factory-picture-item').length || 1;

    // Store file previews for review step
    const filePreviews = {
        business_license: null,
        products: {},
        certifications: {},
        factory_pictures: {},
        patents: {}
    };

    // Initialize file previews from existing data
    function initializeExistingPreviews() {
        // Business License
        const businessLicenseImg = document.querySelector('[data-upload="business_license"] .file-preview');
        if (businessLicenseImg && businessLicenseImg.src && businessLicenseImg.style.display !== 'none') {
            filePreviews.business_license = businessLicenseImg.src;
        }

        // Products
        document.querySelectorAll('.product-item').forEach((item, index) => {
            const preview = item.querySelector('.file-preview');
            if (preview && preview.src && preview.style.display !== 'none') {
                filePreviews.products[index] = preview.src;
            }
        });

        // Certifications
        document.querySelectorAll('.certification-item').forEach((item, index) => {
            const preview = item.querySelector('.file-preview');
            if (preview && preview.src && preview.style.display !== 'none') {
                filePreviews.certifications[index] = preview.src;
            }
        });

        // Factory Pictures
        document.querySelectorAll('.factory-picture-item').forEach((item, index) => {
            const preview = item.querySelector('.file-preview');
            if (preview && preview.src && preview.style.display !== 'none') {
                filePreviews.factory_pictures[index] = preview.src;
            }
        });

        // Patents
        document.querySelectorAll('.patents-item').forEach((item, index) => {
            const preview = item.querySelector('.file-preview');
            if (preview && preview.src && preview.style.display !== 'none') {
                filePreviews.patents[index] = preview.src;
            }
        });
    }

    function updateStepProgress() {
        const visualStep = parseInt(currentStep) === 6 ? 5 : parseInt(currentStep);
        const progress = ((visualStep - 1) / (visibleSteps - 1)) * 100;
        const stepProgressBar = document.getElementById('stepProgress');
        if (stepProgressBar) {
            stepProgressBar.style.width = progress + '%';
        }

        // Update step lines
        document.querySelectorAll('.step_line').forEach((line, index) => {
            const stepNum = index + 1;
            line.classList.remove('active_step', 'completed_step');

            if (stepNum < visualStep) {
                line.classList.add('completed_step');
            } else if (stepNum === visualStep) {
                line.classList.add('active_step');
            }

            if (currentStep === 6 && stepNum === 5) {
                line.classList.add('completed_step');
            }
        });

        // Update step items
        document.querySelectorAll('.step-item').forEach((item, index) => {
            const stepNum = index + 1;
            item.classList.remove('active', 'completed_step');

            if (stepNum < visualStep) {
                item.classList.add('completed_step');
            } else if (stepNum === visualStep) {
                item.classList.add('active');
            }

            if (currentStep === 6 && stepNum === 5) {
                item.classList.add('completed_step');
            }
        });
    }

    function updateMobileStepIndicator() {
        const mobileIndicator = document.getElementById('mobileStepIndicator');
        if (mobileIndicator) {
            const visualStep = currentStep === 6 ? 5 : currentStep;
            mobileIndicator.textContent = `STEP ${visualStep} Out of 5`;
        }
    }

    function showStep(step) {
        document.querySelectorAll('.step-content').forEach(content => {
            content.classList.remove('active');
        });

        const stepContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (stepContent) {
            stepContent.classList.add('active');
        }

        currentStep = parseInt(step);
        updateStepProgress();

        if (step === 6) {
            updateReviewContent();
            setTimeout(initializeAccordions, 100);
        }

        updateMobileStepIndicator();

        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    function initializeAccordions() {
        document.querySelectorAll('.review-section').forEach(section => {
            if (!section.classList.contains('collapsed')) {
                section.classList.add('collapsed');
            }
        });

        document.querySelectorAll('.review-header').forEach(header => {
            const newHeader = header.cloneNode(true);
            header.parentNode.replaceChild(newHeader, header);
        });

        document.querySelectorAll('.review-header').forEach(header => {
            header.addEventListener('click', function (e) {
                if (e.target.closest('.edit-btn')) {
                    return;
                }
                const reviewSection = this.closest('.review-section');
                if (reviewSection) {
                    reviewSection.classList.toggle('collapsed');
                }
            });
        });
    }

    function getFieldValue(selector, isCheckbox = false) {
        const field = document.querySelector(selector);
        if (!field) return '-';

        if (isCheckbox) {
            return field.checked ? 'Yes' : 'No';
        }

        return field.value || '-';
    }

    function updateReviewContent() {
        // Company Information
        const reviewFields = {
            'review-company-name-en': 'input[name="company_name_en"]',
            'review-company-name-ko': 'input[name="company_name_ko"]',
            'review-company-address-en': 'textarea[name="company_address_en"]',
            'review-company-address-ko': 'textarea[name="company_address_ko"]',
            'review-year-established': 'input[name="year_established"]',
            'review-business-registration-number': 'input[name="business_registration_number"]',
            'review-contact-name': 'input[name="contact_name"]',
            'review-contact-email': 'input[name="contact_email"]',
            'review-contact-position': 'select[name="contact_position"]',
            'review-contact-phone': 'input[name="contact_phone"]',
            'review-business-type': 'select[name="business_type"]',
            'review-industry-category': 'select[name="industry_category"]',
            'review-main-product-category': 'select[name="main_product_category"]'
        };

        Object.keys(reviewFields).forEach(reviewId => {
            const element = document.getElementById(reviewId);
            if (element) {
                element.textContent = getFieldValue(reviewFields[reviewId]);
            }
        });

        // Business License
        updateBusinessLicenseReview();

        // Products
        updateProductsReview();

        // Certifications
        updateCertificationsReview();

        // Factory Pictures
        updateFactoryPicturesReview();

        // Declaration
        updateDeclarationReview();
    }

    function updateBusinessLicenseReview() {
        const businessLicensePreview = document.getElementById('review-business-license');
        const businessLicenseText = document.getElementById('review-business-license-text');

        if (businessLicensePreview && businessLicenseText) {
            if (filePreviews.business_license) {
                businessLicensePreview.src = filePreviews.business_license;
                businessLicensePreview.style.display = 'block';
                businessLicenseText.textContent = '';
            } else {
                businessLicensePreview.style.display = 'none';
                businessLicenseText.textContent = '-';
            }
        }
    }

    function updateProductsReview() {
        const productsContainer = document.getElementById('review-products');
        if (!productsContainer) return;

        productsContainer.innerHTML = '';

        const productItems = document.querySelectorAll('.product-item');
        let hasProducts = false;

        productItems.forEach((item, index) => {
            const productNameInput = item.querySelector('input[name^="products"][name$="[name]"]');
            const productName = productNameInput ? productNameInput.value : '';

            if (productName) {
                hasProducts = true;
                const li = document.createElement('li');
                li.className = 'flex justify-between items-center py-2 border-b border-gray-100';
                li.innerHTML = `
                    <span class="font-medium">${productName}</span>
                    ${filePreviews.products[index] ?
                        `<img src="${filePreviews.products[index]}" class="file-preview-small max-w-[100px] rounded border border-gray-200" alt="${productName}">` :
                        '<span class="text-xs text-gray-500">No image</span>'
                    }
                `;
                productsContainer.appendChild(li);
            }
        });

        if (!hasProducts) {
            productsContainer.innerHTML = '<li class="no-data">No products added</li>';
        }
    }

    function updateCertificationsReview() {
        const certificationsContainer = document.getElementById('review-certifications');
        if (!certificationsContainer) return;

        certificationsContainer.innerHTML = '';

        const certificationItems = document.querySelectorAll('.certification-item');
        let hasCertifications = false;

        certificationItems.forEach((item, index) => {
            const certNameInput = item.querySelector('input[name^="certifications"][name$="[name]"]');
            const certName = certNameInput ? certNameInput.value : '';

            if (certName) {
                hasCertifications = true;
                const li = document.createElement('li');
                li.className = 'flex justify-between items-center py-2 border-b border-gray-100';
                li.innerHTML = `
                    <span class="font-medium">${certName}</span>
                    ${filePreviews.certifications[index] ?
                        `<img src="${filePreviews.certifications[index]}" class="file-preview-small max-w-[100px] rounded border border-gray-200" alt="${certName}">` :
                        '<span class="text-xs text-gray-500">No document</span>'
                    }
                `;
                certificationsContainer.appendChild(li);
            }
        });

        if (!hasCertifications) {
            certificationsContainer.innerHTML = '<li class="no-data">No certifications added</li>';
        }
    }

    function updateFactoryPicturesReview() {
        const factoryPicturesContainer = document.getElementById('review-factory-pictures');
        if (!factoryPicturesContainer) return;

        factoryPicturesContainer.innerHTML = '';

        const factoryPictureItems = document.querySelectorAll('.factory-picture-item');
        let hasPictures = false;

        factoryPictureItems.forEach((item, index) => {
            const pictureTitleInput = item.querySelector('input[name^="factory_pictures"][name$="[title]"]');
            const pictureTitle = pictureTitleInput ? pictureTitleInput.value : '';

            if (pictureTitle && filePreviews.factory_pictures[index]) {
                hasPictures = true;
                const div = document.createElement('div');
                div.className = 'review-image-item';
                div.innerHTML = `
                    <img src="${filePreviews.factory_pictures[index]}" class="w-full h-48 object-cover rounded border border-gray-200" alt="${pictureTitle}">
                    <div class="review-image-title text-sm text-gray-600 mt-2">${pictureTitle}</div>
                `;
                factoryPicturesContainer.appendChild(div);
            }
        });

        if (!hasPictures) {
            factoryPicturesContainer.innerHTML = '<div class="no-data">No factory pictures added</div>';
        }
    }

    function updateDeclarationReview() {
        const agreeTermsElement = document.getElementById('review-agree-terms');
        if (agreeTermsElement) {
            agreeTermsElement.textContent = getFieldValue('input[name="agree_terms"]', true);
        }

        const consentBackgroundElement = document.getElementById('review-consent-background-check');
        if (consentBackgroundElement) {
            consentBackgroundElement.textContent = getFieldValue('input[name="consent_background_check"]', true);
        }

        const digitalSignatureElement = document.getElementById('review-digital-signature');
        if (digitalSignatureElement) {
            digitalSignatureElement.textContent = getFieldValue('input[name="digital_signature"]');
        }
    }

    function initializeFileUploads() {
        document.querySelectorAll('.file-upload-area').forEach(area => {
            const input = area.querySelector('input[type="file"]');
            const fileName = area.querySelector('.file-name');
            const preview = area.querySelector('.file-preview');

            if (!input) return;

            // Remove existing listeners by cloning
            const newArea = area.cloneNode(true);
            area.parentNode.replaceChild(newArea, area);

            // Re-get elements from new area
            const newInput = newArea.querySelector('input[type="file"]');
            const newFileName = newArea.querySelector('.file-name');
            const newPreview = newArea.querySelector('.file-preview');

            newArea.addEventListener('click', () => newInput.click());

            newArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                newArea.classList.add('dragover');
            });

            newArea.addEventListener('dragleave', () => {
                newArea.classList.remove('dragover');
            });

            newArea.addEventListener('drop', (e) => {
                e.preventDefault();
                newArea.classList.remove('dragover');
                if (e.dataTransfer.files.length) {
                    newInput.files = e.dataTransfer.files;
                    handleFileSelect(newInput.files[0], newInput, newFileName, newPreview, newArea);
                }
            });

            newInput.addEventListener('change', () => {
                if (newInput.files.length) {
                    handleFileSelect(newInput.files[0], newInput, newFileName, newPreview, newArea);
                }
            });
        });
    }

    function handleFileSelect(file, input, fileNameElement, previewElement, area) {
        if (file && fileNameElement) {
            fileNameElement.textContent = file.name;

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    if (previewElement) {
                        previewElement.src = e.target.result;
                        previewElement.style.display = 'block';
                        area.classList.add('has-image');
                        const placeholder = area.querySelector('.upload-placeholder');
                        if (placeholder) {
                            placeholder.style.display = 'none';
                        }
                    }
                    storeFilePreview(input, e.target.result);
                };
                reader.readAsDataURL(file);
            } else {
                area.classList.remove('has-image');
                storeFilePreview(input, null);
            }
        }
    }

    function storeFilePreview(input, dataUrl) {
        const name = input.getAttribute('name');
        if (!name) return;

        if (name === 'business_registration_license') {
            filePreviews.business_license = dataUrl;
        } else if (name.startsWith('products[') && name.endsWith('[image]')) {
            const match = name.match(/products\[(\d+)\]\[image\]/);
            if (match) {
                filePreviews.products[match[1]] = dataUrl;
            }
        } else if (name.startsWith('certifications[') && name.endsWith('[document]')) {
            const match = name.match(/certifications\[(\d+)\]\[document\]/);
            if (match) {
                filePreviews.certifications[match[1]] = dataUrl;
            }
        } else if (name.startsWith('factory_pictures[') && name.endsWith('[image]')) {
            const match = name.match(/factory_pictures\[(\d+)\]\[image\]/);
            if (match) {
                filePreviews.factory_pictures[match[1]] = dataUrl;
            }
        } else if (name.startsWith('patents[') && name.endsWith('[document]')) {
            const match = name.match(/patents\[(\d+)\]\[document\]/);
            if (match) {
                filePreviews.patents[match[1]] = dataUrl;
            }
        }
    }

    function validateStep(stepElement) {
        const requiredFields = stepElement.querySelectorAll('[required]');
        let isValid = true;
        const invalidFields = [];

        requiredFields.forEach(field => {
            let fieldValid = false;

            if (field.type === 'radio') {
                const radioGroup = stepElement.querySelectorAll(`input[name="${field.name}"]`);
                fieldValid = Array.from(radioGroup).some(radio => radio.checked);
            } else if (field.type === 'checkbox') {
                fieldValid = field.checked;
            } else {
                fieldValid = field.value.trim() !== '';
            }

            if (!fieldValid) {
                isValid = false;
                field.classList.add('border-red-500');
                invalidFields.push(field);
            } else {
                field.classList.remove('border-red-500');
            }
        });

        return { isValid, invalidFields };
    }

    // Next button handlers
    document.querySelectorAll('.next-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            const currentStepElement = document.querySelector(`.step-content[data-step="${currentStep}"]`);
            if (!currentStepElement) return;

            const validation = validateStep(currentStepElement);

            if (validation.isValid && parseInt(currentStep) < totalSteps) {
                showStep(parseInt(currentStep) + 1);
            } else if (!validation.isValid) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Missing Fields',
                        text: 'Please fill in all required fields',
                        timer: 4000,
                        showConfirmButton: true
                    });
                } else {
                    alert('Please fill in all required fields');
                }

                // Scroll to first invalid field
                if (validation.invalidFields.length > 0) {
                    validation.invalidFields[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }
        });
    });

    // Previous button handlers
    document.querySelectorAll('.prev-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            if (parseInt(currentStep) > 1) {
                showStep(parseInt(currentStep) - 1);
            }
        });
    });

    // Edit button handlers for review step
    document.addEventListener('click', function (e) {
        if (e.target.closest('.edit-btn')) {
            const editBtn = e.target.closest('.edit-btn');
            const stepToEdit = parseInt(editBtn.getAttribute('data-step'));
            if (!isNaN(stepToEdit)) {
                showStep(stepToEdit);
            }
        }
    });

    // Character count for textarea
    window.updateCharCount = function (textarea, countId) {
        const count = textarea.value.length;
        const countElement = document.getElementById(countId);
        if (countElement) {
            countElement.textContent = count;
        }
    };

    // Export experience toggle
    const exportExperienceRadios = document.querySelectorAll('input[name="export_experience"]');
    exportExperienceRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            const exportYearsField = document.getElementById('export_years_field');
            const exportYearsInput = exportYearsField ? exportYearsField.querySelector('input') : null;

            if (exportYearsField) {
                if (this.value === 'yes') {
                    exportYearsField.classList.remove('hidden');
                    if (exportYearsInput) exportYearsInput.setAttribute('required', 'required');
                } else {
                    exportYearsField.classList.add('hidden');
                    if (exportYearsInput) {
                        exportYearsInput.removeAttribute('required');
                        exportYearsInput.value = '';
                    }
                }
            }
        });
    });

    // Patents toggle
    const hasPatentsRadios = document.querySelectorAll('input[name="has_patents"]');
    hasPatentsRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            const patentsContainer = document.getElementById('patents_container');
            const addPatentBtn = document.getElementById('add_patent_btn');

            if (patentsContainer && addPatentBtn) {
                const patentInputs = patentsContainer.querySelectorAll('input, textarea');

                if (this.value === 'yes') {
                    patentsContainer.classList.remove('hidden');
                    addPatentBtn.classList.remove('hidden');
                    patentInputs.forEach(input => {
                        if (input.type === 'file' || input.tagName === 'TEXTAREA') {
                            input.setAttribute('required', 'required');
                        }
                    });
                } else {
                    patentsContainer.classList.add('hidden');
                    addPatentBtn.classList.add('hidden');
                    patentInputs.forEach(input => {
                        input.removeAttribute('required');
                        input.value = '';
                    });
                }
            }
        });
    });

    // Add Product
    const addProductBtn = document.getElementById('add_product_btn');
    if (addProductBtn) {
        addProductBtn.addEventListener('click', function () {
            if (productCount >= 5) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maximum Reached',
                        text: 'You can add maximum 5 products',
                        timer: 4000,
                        showConfirmButton: true
                    });
                } else {
                    alert('You can add maximum 5 products');
                }
                return;
            }

            const container = document.getElementById('products_container');
            if (!container) return;

            const newProduct = createProductHTML(productCount);
            container.insertAdjacentHTML('beforeend', newProduct);
            productCount++;
            initializeFileUploads();
        });
    }

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
                                Product Name <span class="text-gray-500">*</span>
                            </label>
                            <button type="button" class="remove-product text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                        </div>
                        <input type="text" name="products[${index}][name]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Product Image <span class="text-gray-500">*</span>
                        </label>
                        <div class="file-upload-area" data-upload="product_${index}">
                            <input type="file" name="products[${index}][image]" accept="image/*" required class="hidden">
                            <img src="" class="file-preview" alt="Product image preview" style="display: none;">
                            <div class="upload-placeholder">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600">Drag & drop the file here or <span class="text-blue-600 underline cursor-pointer">select file</span></p>
                                <p class="text-xs text-gray-500 file-name mt-1"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    // Remove Product
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-product')) {
            const productItem = e.target.closest('.product-item');
            if (productItem) {
                const index = Array.from(document.querySelectorAll('.product-item')).indexOf(productItem);
                delete filePreviews.products[index];
                productItem.remove();
                productCount = Math.max(1, productCount - 1);
                renumberItems('.product-item');
            }
        }
    });

    // Add Certification
    const addCertificationBtn = document.getElementById('add_certification_btn');
    if (addCertificationBtn) {
        addCertificationBtn.addEventListener('click', function () {
            const container = document.getElementById('certifications_container');
            if (!container) return;

            const newCertification = createCertificationHTML(certificationCount);
            container.insertAdjacentHTML('beforeend', newCertification);
            certificationCount++;
            initializeFileUploads();
        });
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
                                Certification Name <span class="text-gray-500">*</span>
                            </label>
                            <button type="button" class="remove-certification text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                        </div>
                        <input type="text" name="certifications[${index}][name]" required
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Certification Document <span class="text-gray-500">*</span>
                        </label>
                        <div class="file-upload-area" data-upload="cert_${index}">
                            <input type="file" name="certifications[${index}][document]" accept=".pdf,.jpg,.jpeg,.png" required class="hidden">
                            <img src="" class="file-preview" alt="Certification document preview" style="display: none;">
                            <div class="upload-placeholder">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600">Drag & drop the file here or <span class="text-blue-600 underline cursor-pointer">select file</span></p>
                                <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE, RoHS, etc.)</p>
                                <p class="text-xs text-gray-500 file-name"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    // Remove Certification
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-certification')) {
            const certItem = e.target.closest('.certification-item');
            if (certItem) {
                const index = Array.from(document.querySelectorAll('.certification-item')).indexOf(certItem);
                delete filePreviews.certifications[index];
                certItem.remove();
                certificationCount = Math.max(1, certificationCount - 1);
                renumberItems('.certification-item');
            }
        }
    });

    // Add Factory Picture
    const addFactoryPictureBtn = document.getElementById('add_factory_picture_btn');
    if (addFactoryPictureBtn) {
        addFactoryPictureBtn.addEventListener('click', function () {
            if (factoryPictureCount >= 5) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Maximum Reached',
                        text: 'You can add maximum 5 factory pictures',
                        timer: 4000,
                        showConfirmButton: true
                    });
                } else {
                    alert('You can add maximum 5 factory pictures');
                }
                return;
            }

            const container = document.getElementById('factory_pictures_container');
            if (!container) return;

            const newPicture = createFactoryPictureHTML(factoryPictureCount);
            container.insertAdjacentHTML('beforeend', newPicture);
            factoryPictureCount++;
            initializeFileUploads();
        });
    }

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
                                    Picture Title <span class="text-gray-500">*</span>
                                </label>
                                <button type="button" class="remove-factory-picture text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                            </div>
                            <input type="text" name="factory_pictures[${index}][title]" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-700 mb-2">
                                Factory Picture Upload <span class="text-gray-500">*</span>
                            </label>
                            <div class="file-upload-area" data-upload="factory_pic_${index}">
                                <input type="file" name="factory_pictures[${index}][image]" accept="image/*" required class="hidden">
                                <img src="" class="file-preview" alt="Factory picture preview" style="display: none;">
                                <div class="upload-placeholder">
                                    <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Drag & drop the file here or <span class="text-blue-600 underline cursor-pointer">select file</span></p>
                                    <p class="text-xs text-gray-500 file-name mt-1"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
    }

    // Remove Factory Picture
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-factory-picture')) {
            const pictureItem = e.target.closest('.factory-picture-item');
            if (pictureItem) {
                const index = Array.from(document.querySelectorAll('.factory-picture-item')).indexOf(pictureItem);
                delete filePreviews.factory_pictures[index];
                pictureItem.remove();
                factoryPictureCount = Math.max(1, factoryPictureCount - 1);
                renumberItems('.factory-picture-item');
            }
        }
    });

    // Add Patent
    const addPatentBtn = document.getElementById('add_patent_btn');
    if (addPatentBtn) {
        addPatentBtn.addEventListener('click', function () {
            const container = document.getElementById('patents_container');
            if (!container) return;

            const newPatent = createPatentHTML(patentCount);
            container.insertAdjacentHTML('beforeend', newPatent);
            patentCount++;
            initializeFileUploads();
        });
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
                                Please upload your patents and relevant certificates <span class="text-gray-500">*</span>
                            </label>
                            <button type="button" class="remove-patent text-red-600 hover:text-red-700 text-sm font-medium">Remove</button>
                        </div>
                        <div class="file-upload-area" data-upload="patent_${index}">
                            <input type="file" name="patents[${index}][document]" accept=".pdf,.jpg,.jpeg,.png" class="hidden">
                            <img src="" class="file-preview" alt="Patent document preview" style="display: none;">
                            <div class="upload-placeholder">
                                <svg class="w-10 h-10 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600">Drag & drop the file here or <span class="text-blue-600 underline cursor-pointer">select file</span></p>
                                <p class="text-xs text-gray-500 file-name mt-1"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700 mb-2">
                            Patent or Certification Description <span class="text-gray-500">*</span>
                        </label>
                        <textarea name="patents[${index}][description]" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"></textarea>
                    </div>
                </div>
            </div>`;
    }

    // Remove Patent
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-patent')) {
            const patentItem = e.target.closest('.patents-item');
            if (patentItem) {
                const index = Array.from(document.querySelectorAll('.patents-item')).indexOf(patentItem);
                delete filePreviews.patents[index];
                patentItem.remove();
                patentCount = Math.max(1, patentCount - 1);
                renumberItems('.patents-item');
            }
        }
    });

    // Renumber items after deletion
    function renumberItems(selector) {
        document.querySelectorAll(selector).forEach((item, index) => {
            const numberSpan = item.querySelector('.w-8.h-8');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }

    // Form submission
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.classList.add('btn-loading');
                submitBtn.innerHTML = '<span class="loading-spinner"></span> Submitting...';
                submitBtn.disabled = true;
            }
        });
    }


    // Initialize everything
    initializeExistingPreviews();
    updateStepProgress();
    updateMobileStepIndicator();
    initializeFileUploads();

    // Trigger initial state for conditional fields
    const checkedExportRadio = document.querySelector('input[name="export_experience"]:checked');
    if (checkedExportRadio) {
        checkedExportRadio.dispatchEvent(new Event('change'));
    }

    const checkedPatentsRadio = document.querySelector('input[name="has_patents"]:checked');
    if (checkedPatentsRadio) {
        checkedPatentsRadio.dispatchEvent(new Event('change'));
    }
});




Now here see, the form is submitting and showing data all in proper way.
No code, just give me an idea in a very short paragraph, that how i can implement auto submit feature in it?
What hould be the idea for auto submit?