<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Manufacturer;

class ManufacturerProfileController extends Controller
{
    /**
     * Show the specified step
     */
    public function showStep(Request $request, $step = 1)
    {
        $step = (int) $step;
        if ($step < 1 || $step > 6) {
            $step = 1;
        }

        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $profile_data = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();
        
        // If user tries to skip steps, redirect them to the next incomplete step
        $nextIncompleteStep = $this->getNextIncompleteStep($profile_data);
        if ($step > $nextIncompleteStep) {
            return redirect()->route('manufacturer.application.step', ['step' => $nextIncompleteStep]);
        }

        $contact_mail = config('app.contact_email', 'support@example.com');

        return view('surface.account.menufacturer_profile_complete', compact('profile_data', 'step', 'contact_mail'));
    }

    /**
     * Get the next incomplete step
     */
    private function getNextIncompleteStep($manufacturer)
    {
        // Check which steps are completed
        if (empty($manufacturer->name) || empty($manufacturer->company_name_en)) {
            return 1;
        }
        
        if (empty($manufacturer->business_type) || empty($manufacturer->business_registration_number)) {
            return 2;
        }
        
        if (empty($manufacturer->main_product_category) || empty($manufacturer->production_capacity)) {
            return 3;
        }
        
        if (empty($manufacturer->has_qms)) {
            return 4;
        }
        
        if (empty($manufacturer->agree_terms) || empty($manufacturer->digital_signature)) {
            return 5;
        }
        
        return 6; // All steps completed, show review
    }

    /**
     * Save step data
     */
    public function saveStep(Request $request, $step)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        $data = $request->except(['_token', '_method']);
        $nextStep = $request->input('next_step', $step + 1);
        $action = $request->input('action', 'next');

        // Validate step data
        $validationRules = $this->getValidationRules($step);
        $validator = Validator::make($data, $validationRules);

        if ($validator->fails()) {
            return redirect()->route('manufacturer.application.step', ['step' => $step])
                ->withErrors($validator)
                ->withInput();
        }

        // Process step data
        $processedData = $this->processStepData($step, $data, $manufacturer, $request);

        // Update manufacturer
        $manufacturer->update($processedData);

        // Determine where to redirect
        if ($action === 'previous') {
            $previousStep = max(1, $step - 1);
            return redirect()->route('manufacturer.application.step', ['step' => $previousStep])
                ->with('success', 'Changes saved successfully.');
        }

        // If it's step 5, go to review (step 6)
        if ($step == 5) {
            return redirect()->route('manufacturer.application.step', ['step' => 6])
                ->with('success', 'Step completed successfully.');
        }

        // Go to next step
        return redirect()->route('manufacturer.application.step', ['step' => $nextStep])
            ->with('success', 'Step completed successfully.');
    }

    /**
     * Get validation rules for each step
     */
    private function getValidationRules($step)
    {
        $rules = [];

        switch ($step) {
            case 1: // Company Information
                $rules = [
                    'name' => 'required|string|max:255',
                    'company_name_en' => 'required|string|max:255',
                    'company_name_ko' => 'nullable|string|max:255',
                    'company_address_en' => 'required|string|max:500',
                    'company_address_ko' => 'nullable|string|max:500',
                    'year_established' => 'required|integer|min:1900|max:' . date('Y'),
                    'number_of_employees' => 'required|integer|min:1',
                    'company_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
                    'website' => 'nullable|url|max:255',
                    'business_introduction' => 'required|string|max:400',
                    'contact_name' => 'required|string|max:255',
                    'contact_position' => 'required|string|in:CEO,Manager,Director,Sales Representative,Other',
                    'contact_email' => 'required|email|max:255',
                    'contact_phone' => 'required|string|max:20',
                ];
                break;

            case 2: // Business Profile
                $rules = [
                    'business_type' => 'required|string|in:"Manufacturer, OEM, ODM, Exporter", Manufacturer, OEM, ODM, Exporter,Manufacturer,OEM,ODM,Exporter,Refurbished',
                    'industry_category' => 'required|string|in:Electronics,Textiles,Machinery,Chemicals,Food & Beverage,Automotive,Other',
                    'business_registration_number' => 'required|string|max:100',
                    'business_registration_license' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'export_experience' => 'required|in:yes,no',
                    'export_years' => 'required_if:export_experience,yes|nullable|integer|min:1',
                ];
                break;

            case 3: // Product Information
                $rules = [
                    'main_product_category' => 'required|string|in:medical,automobile,agrochemical,technology,military,cosmetics,fashion,secondhand,Other',
                    'products.*.name' => 'required|string|max:255',
                    'products.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
                    'production_capacity' => 'required|integer|min:1',
                    'production_capacity_unit' => 'required|string|in:pcs/month,pcs/year,tons/month,tons/year',
                    'moq' => 'required|integer|min:1',
                    'certifications.*.name' => 'required|string|max:255',
                    'certifications.*.document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                    'has_patents' => 'required|in:yes,no',
                    'patents.*.description' => 'required_if:has_patents,yes|nullable|string|max:500',
                    'patents.*.document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
                ];
                break;

            case 4: // Trust & Verification
                $rules = [
                    'has_qms' => 'required|in:yes,no',
                    'factory_audit_available' => 'nullable|in:yes,no',
                    'standards' => 'nullable|array',
                    'standards.*' => 'string|in:EU,US,ROHS,Other',
                    'factory_pictures.*.title' => 'required|string|max:255',
                    'factory_pictures.*.image' => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
                    'catalogue' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
                ];
                break;

            case 5: // Declaration
                $rules = [
                    'agree_terms' => 'required|accepted',
                    'consent_background_check' => 'required|accepted',
                    'digital_signature' => 'required|string|max:255',
                ];
                break;
        }

        return $rules;
    }

    /**
     * Process step data before saving
     */
    private function processStepData($step, $data, $manufacturer, $request)
    {
        $processedData = [];

        switch ($step) {
            case 1:
                // Single file uploads
                if ($request->hasFile('company_logo')) {
                    $this->handleSingleFileUpload($request, $processedData, 'company_logo', 'company_logo/', $manufacturer);
                } else {
                    $processedData['company_logo'] = $manufacturer->company_logo ?? null;
                }
                
                // Copy other fields
                $fields = ['name', 'company_name_en', 'company_name_ko', 'company_address_en', 
                         'company_address_ko', 'year_established', 'number_of_employees',
                         'website', 'business_introduction', 'contact_name', 'contact_position',
                         'contact_email', 'contact_phone', 'company_google_location'];
                
                foreach ($fields as $field) {
                    if (isset($data[$field])) {
                        $processedData[$field] = $data[$field];
                    }
                }
                break;

            case 2:
                // Business license file
                if ($request->hasFile('business_registration_license')) {
                    $this->handleSingleFileUpload($request, $processedData, 'business_registration_license', 'business_license/', $manufacturer);
                } else {
                    $processedData['business_registration_license'] = $manufacturer->business_registration_license ?? null;
                }
                
                // Copy other fields
                $fields = ['business_type', 'industry_category', 'business_registration_number',
                         'export_experience', 'export_years'];
                
                foreach ($fields as $field) {
                    if (isset($data[$field])) {
                        $processedData[$field] = $data[$field];
                    }
                }
                break;

            case 3:
                // Handle products
                if ($request->has('products')) {
                    $processedData['products'] = $this->handleProductsUpload($request, $manufacturer);
                } else {
                    $processedData['products'] = $manufacturer->products ?? [];
                }
                
                // Handle certifications
                if ($request->has('certifications')) {
                    $processedData['certifications'] = $this->handleCertificationsUpload($request, $manufacturer);
                } else {
                    $processedData['certifications'] = $manufacturer->certifications ?? [];
                }
                
                // Handle patents
                if ($request->has('patents') && isset($data['has_patents']) && $data['has_patents'] == 'yes') {
                    $processedData['patents'] = $this->handlePatentsUpload($request, $manufacturer);
                } else {
                    $processedData['patents'] = [];
                }
                
                // Copy other fields
                $fields = ['main_product_category', 'production_capacity', 'production_capacity_unit',
                         'moq', 'has_patents'];
                
                foreach ($fields as $field) {
                    if (isset($data[$field])) {
                        $processedData[$field] = $data[$field];
                    }
                }
                break;

            case 4:
                // Handle factory pictures
                if ($request->has('factory_pictures')) {
                    $processedData['factory_pictures'] = $this->handleFactoryPicturesUpload($request, $manufacturer);
                } else {
                    $processedData['factory_pictures'] = $manufacturer->factory_pictures ?? [];
                }
                
                // Handle catalogue
                if ($request->hasFile('catalogue')) {
                    $this->handleSingleFileUpload($request, $processedData, 'catalogue', 'catalogue/', $manufacturer);
                } else {
                    $processedData['catalogue'] = $manufacturer->catalogue ?? null;
                }
                
                // Copy other fields
                $fields = ['has_qms', 'factory_audit_available'];
                
                foreach ($fields as $field) {
                    if (isset($data[$field])) {
                        $processedData[$field] = $data[$field];
                    }
                }
                
                // Handle standards array
                $processedData['standards'] = $request->has('standards') ? $request->standards : [];
                break;

            case 5:
                // Handle boolean fields
                $processedData['agree_terms'] = $request->has('agree_terms');
                $processedData['consent_background_check'] = $request->has('consent_background_check');
                $processedData['digital_signature'] = $data['digital_signature'] ?? null;
                break;
        }

        return $processedData;
    }

    /**
     * Handle single file upload
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
        }
    }

    /**
     * Handle products upload
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
     * Handle certifications upload
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
     * Handle patents upload
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
     * Handle factory pictures upload
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

    /**
     * Final submission
     */
    public function finalSubmit(Request $request)
    {
        $manufacturer_uid = Auth::guard('manufacturer')->user()->manufacturer_uid;
        $manufacturer = Manufacturer::where('manufacturer_uid', $manufacturer_uid)->first();

        // Mark as completed
        $manufacturer->update([
            'profile_completed' => true,
            'profile_completed_at' => now(),
        ]);

        // Send notification email if needed
        // $this->sendNotificationEmail($manufacturer);

        return redirect()->route('manufacturer.application.successful')
            ->with('success', 'Your application has been submitted successfully!');
    }

    /**
     * Application successful page
     */
    public function applicationSuccessful()
    {
        return view('surface.account.manufacturer_app_success');
    }
}