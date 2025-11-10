@extends('layouts.admin.app')

@section('title', 'General Settings, Manage Website Basic Information')

@section('style')
    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <style>
        .preview-img {
            max-width: 120px;
            max-height: 120px;
        }

        label {
            font-weight: 600;
        }

        /* Quill Editor Styling */
        .quill-editor {
            background-color: white;
            border-radius: 0.5rem;
        }

        .dark .quill-editor {
            background-color: #1f2937;
        }

        .ql-toolbar {
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
            border: 1px solid #d1d5db;
        }

        .dark .ql-toolbar {
            border-color: #4b5563;
            background-color: #374151;
        }

        .ql-container {
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            border: 1px solid #d1d5db;
            min-height: 200px;
            font-size: 14px;
        }

        .dark .ql-container {
            border-color: #4b5563;
            background-color: #1f2937;
            color: white;
        }

        .dark .ql-editor {
            color: white;
        }

        .dark .ql-stroke {
            stroke: #9ca3af;
        }

        .dark .ql-fill {
            fill: #9ca3af;
        }

        .dark .ql-picker-label {
            color: #9ca3af;
        }

        .ql-editor.ql-blank::before {
            color: #9ca3af;
            font-style: normal;
        }
    </style>
@endsection

@section('content')

    <!-- Welcome Section -->
    <div class="mb-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white">General Settings</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage website basic information from here</p>
    </div>

    <!-- Settings Form -->
    <form id="generalSettingsForm"
        class="bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-200 dark:border-gray-800 p-6"
        action="/admin/update-website-settings" enctype="multipart/form-data" method="POST">
        @csrf

        <!-- Brand Information Section -->
        <div class="mb-8">
            <h2
                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-800">
                Brand Information
            </h2>

            <!-- Logo Uploads Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                <!-- Brand Logo -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Brand Logo</label>
                    <div class="relative flex flex-col items-center justify-center w-full h-48 p-4 text-center transition border-2 border-gray-300 border-dashed rounded-lg cursor-pointer group dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400"
                        onclick="document.getElementById('brandlogo').click();">
                        <input id="brandlogo" name="brandlogo" type="file" accept="image/*"
                            onchange="previewImage(this, 'brandlogoPreview', 'brandlogoUploadIcon')" class="hidden" />

                        <!-- Image Preview -->
                        @if (!empty($settings->brandlogo))
                            <img id="brandlogoPreview" src="{{ asset('storage/' . $settings->brandlogo) }}"
                                class="object-contain w-24 h-24 mb-2 rounded-md preview-img" alt="Brand Logo Preview" />
                        @else
                            <img id="brandlogoPreview" src="https://via.placeholder.com/120x120?text=Brand+Logo"
                                class="hidden object-contain w-24 h-24 mb-2 rounded-md preview-img"
                                alt="Brand Logo Preview" />
                        @endif

                        <!-- Upload Icon -->
                        <div id="brandlogoUploadIcon"
                            class="{{ !empty($settings->brandlogo) ? 'hidden' : '' }} flex flex-col items-center justify-center text-gray-400 group-hover:text-blue-500 dark:text-gray-500 dark:group-hover:text-blue-400">
                            <i class="text-3xl fas fa-cloud-upload-alt"></i>
                            <p class="mt-1 text-sm">Click or drag file to upload</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">SVG, PNG, JPG, or GIF (max 2MB)</p>
                        </div>
                    </div>
                </div>

                <!-- Brand Icon (Favicon) -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Brand Icon
                        (Favicon)</label>
                    <div class="relative flex flex-col items-center justify-center w-full h-48 p-4 text-center transition border-2 border-gray-300 border-dashed rounded-lg cursor-pointer group dark:border-gray-600 hover:border-blue-500 dark:hover:border-blue-400"
                        onclick="document.getElementById('website_icon').click();">
                        <input id="website_icon" name="website_icon" type="file" accept="image/*"
                            onchange="previewImage(this, 'website_iconPreview', 'website_iconUploadIcon')" class="hidden" />

                        <!-- Image Preview -->
                        @if (!empty($settings->website_icon))
                            <img id="website_iconPreview" src="{{ asset('storage/' . $settings->website_icon) }}"
                                class="object-contain w-24 h-24 mb-2 rounded-md preview-img" alt="Brand Icon Preview" />
                        @else
                            <img id="website_iconPreview" src="https://via.placeholder.com/120x120?text=Brand+Icon"
                                class="hidden object-contain w-24 h-24 mb-2 rounded-md preview-img"
                                alt="Brand Icon Preview" />
                        @endif

                        <!-- Upload Icon -->
                        <div id="website_iconUploadIcon"
                            class="{{ !empty($settings->website_icon) ? 'hidden' : '' }} flex flex-col items-center justify-center text-gray-400 group-hover:text-blue-500 dark:text-gray-500 dark:group-hover:text-blue-400">
                            <i class="text-3xl fas fa-cloud-upload-alt"></i>
                            <p class="mt-1 text-sm">Click or drag file to upload</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">ICO, PNG (max 1MB)</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Brand Name & Currency -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="brandname" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Brand Name
                    </label>
                    <input type="text" id="brandname" name="brandname" value="{{ $settings->brandname ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Your Brand Name" />
                </div>
            </div>
        </div>

        <!-- Stripe Payment Settings -->
        <div class="mb-8">
            <h2
                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-800">
                Stripe Payment Settings
            </h2>

            <div class="col-span-2 grid grid-cols-1 lg:grid-cols-3 gap-4">
                <div>
                    <label for="PAYPAL_CLIENT_ID" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Paypal Client ID
                    </label>
                    <input type="text" id="PAYPAL_CLIENT_ID" name="PAYPAL_CLIENT_ID"
                        value="{{ $settings->PAYPAL_CLIENT_ID ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Client ID Here..." />
                </div>

                <div>
                    <label for="PAYPAL_SECRET" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Paypal Secret Key
                    </label>
                    <input type="text" id="PAYPAL_SECRET" name="PAYPAL_SECRET"
                        value="{{ $settings->PAYPAL_SECRET ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Secret Key Here..." />
                </div>

                <div>
                    <label for="PAYPAL_MODE" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Paypal Mode
                    </label>
                    <select id="PAYPAL_MODE" name="PAYPAL_MODE"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white">
                        @php $mode = old('PAYPAL_MODE', $settings->PAYPAL_MODE ?? 'sandbox'); @endphp
                        <option value="sandbox" {{ $mode == 'sandbox' ? 'selected' : '' }}>Sandbox</option>
                        <option value="live" {{ $mode == 'live' ? 'selected' : '' }}>Live</option>
                    </select>
                </div>


                <div>
                    <label for="monthly_fee_amount" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Original Monthly Amount
                    </label>
                    <input type="number" min="1" id="monthly_fee_amount" name="monthly_fee_amount"
                        value="{{ $settings->monthly_fee_amount ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Subscription Amount" />
                </div>


                <div>
                    <label for="half_yearly_fee_amount"
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Original Half Yearly Amount
                    </label>
                    <input type="number" id="half_yearly_fee_amount" name="half_yearly_fee_amount" min="1"
                        value="{{ $settings->half_yearly_fee_amount ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Subscription Amount" />
                </div>

                <div>
                    <label for="yearly_fee_amount"
                        class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Original Yearly Amount
                    </label>
                    <input type="number" min="1" id="yearly_fee_amount" name="yearly_fee_amount"
                        value="{{ $settings->yearly_fee_amount ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Subscription Amount" />
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="mb-8">
            <h2
                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-800">
                Contact Information
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="open_dys" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Open Days
                    </label>
                    <input type="text" id="open_dys" name="open_dys" value="{{ $settings->open_dys ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="Monday - Friday" />
                </div>

                <div>
                    <label for="open_time" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Open Time
                    </label>
                    <input type="text" id="open_time" name="open_time" value="{{ $settings->open_time ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="9:00 AM - 5:00 PM" />
                </div>

                <div>
                    <label for="contact_mail" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contact Email
                    </label>
                    <input type="email" id="contact_mail" name="contact_mail"
                        value="{{ $settings->contact_mail ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="contact@example.com" />
                </div>

                <div class="">
                    <label for="contact_phone" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        Contact Phone
                    </label>
                    <input type="text" id="contact_phone" name="contact_phone"
                        value="{{ $settings->contact_phone ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="+1234567890" />
                </div>
            </div>
        </div>

        <!-- Social Media Links -->
        <div class="mb-8">
            <h2
                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-800">
                Social Media Links
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="fb_url" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fab fa-facebook text-blue-600 mr-2"></i>Facebook URL
                    </label>
                    <input type="url" id="fb_url" name="fb_url" value="{{ $settings->fb_url ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="https://facebook.com/yourpage" />
                </div>

                <div>
                    <label for="twitter_url" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fab fa-twitter text-sky-500 mr-2"></i>Twitter URL
                    </label>
                    <input type="url" id="twitter_url" name="twitter_url"
                        value="{{ $settings->twitter_url ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="https://twitter.com/yourpage" />
                </div>

                <div>
                    <label for="instagram_url" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fab fa-instagram text-pink-600 mr-2"></i>Instagram URL
                    </label>
                    <input type="url" id="instagram_url" name="instagram_url"
                        value="{{ $settings->instagram_url ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="https://instagram.com/yourpage" />
                </div>

                <div>
                    <label for="linkedin_url" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                        <i class="fab fa-linkedin text-blue-700 mr-2"></i>LinkedIn URL
                    </label>
                    <input type="url" id="linkedin_url" name="linkedin_url"
                        value="{{ $settings->linkedin_url ?? '' }}"
                        class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                        placeholder="https://linkedin.com/company/yourpage" />
                </div>
            </div>
        </div>

        <!-- Brand Description -->
        <div class="mb-8 grid grid-cols-1 gap-6 lg:grid-cols-2">
            <h2
                class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-800 col-span-2">
                Brand Description
            </h2>

            <div>
                <label for="business_registration_number"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Business registration No
                </label>
                <input type="text" id="business_registration_number" name="business_registration_number"
                    value="{{ $settings->business_registration_number ?? '' }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Your Brand Name" />
            </div>

            <div>
                <label for="business_address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Address
                </label>
                <input type="text" id="business_address" name="business_address"
                    value="{{ $settings->business_address ?? '' }}"
                    class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Your Brand Name" />
            </div>

            <div>
                <label for="short_desc_about_brand"
                    class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    Short Description About Brand
                </label>
                <textarea id="short_desc_about_brand" name="short_desc_about_brand" rows="4"
                    class="w-full px-4 py-2 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:bg-gray-800 dark:text-white"
                    placeholder="Write a short description about your brand...">{{ $settings->short_desc_about_brand ?? '' }}</textarea>
            </div>
        </div>

        <!-- Terms & Conditions with Rich Text Editor -->
        <div class="mt-6">
            <label for="terms_conditions" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                Terms & Conditions Content
            </label>
            <div id="terms_conditions_editor" class="quill-editor"></div>
            <input type="hidden" name="terms_conditions" id="terms_conditions">
        </div>

        <!-- Privacy Policy with Rich Text Editor -->
        <div class="mt-6">
            <label for="privacy_policy" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                Privacy Policy Content
            </label>
            <div id="privacy_policy_editor" class="quill-editor"></div>
            <input type="hidden" name="privacy_policy" id="privacy_policy">
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end mt-6">
            <button type="submit"
                class="px-6 py-3 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-colors save_btn">
                <i class="fas fa-save mr-2"></i>Save Changes
            </button>
        </div>
    </form>

@endsection

@section('scripts')
    <!-- Quill JS -->
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

    <script>
        $(function() {
            $(".settings_tab").click();
        });

        $(".general").addClass("active_tab");

        // Initialize Quill Editors
        let termsEditor, privacyEditor;

        $(document).ready(function() {
            // Quill toolbar configuration
            const toolbarOptions = [
                [{
                    'header': [1, 2, 3, 4, 5, 6, false]
                }],
                [{
                    'font': []
                }],
                [{
                    'size': ['small', false, 'large', 'huge']
                }],
                ['bold', 'italic', 'underline', 'strike'],
                [{
                    'color': []
                }, {
                    'background': []
                }],
                [{
                    'script': 'sub'
                }, {
                    'script': 'super'
                }],
                [{
                    'list': 'ordered'
                }, {
                    'list': 'bullet'
                }],
                [{
                    'indent': '-1'
                }, {
                    'indent': '+1'
                }],
                [{
                    'align': []
                }],
                ['blockquote', 'code-block'],
                ['link', 'image', 'video'],
                ['clean']
            ];

            // Initialize Terms & Conditions Editor
            termsEditor = new Quill('#terms_conditions_editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Write your terms and conditions here...'
            });

            // Initialize Privacy Policy Editor
            privacyEditor = new Quill('#privacy_policy_editor', {
                theme: 'snow',
                modules: {
                    toolbar: toolbarOptions
                },
                placeholder: 'Write your privacy policy here...'
            });

            // Set existing content
            const termsContent = {!! json_encode($settings->terms_conditions ?? '') !!};
            const privacyContent = {!! json_encode($settings->privacy_policy ?? '') !!};

            if (termsContent) {
                termsEditor.root.innerHTML = termsContent;
            }

            if (privacyContent) {
                privacyEditor.root.innerHTML = privacyContent;
            }
        });

        // Image preview function
        function previewImage(input, previewId, uploadIconId) {
            const preview = document.getElementById(previewId);
            const uploadIcon = document.getElementById(uploadIconId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    uploadIcon.classList.add('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        // Make function globally available
        window.previewImage = previewImage;

        // Form submission handling with AJAX
        $(document).ready(function() {
            $('#generalSettingsForm').on('submit', function(e) {
                e.preventDefault();

                // Get content from Quill editors
                const termsHTML = termsEditor.root.innerHTML;
                const privacyHTML = privacyEditor.root.innerHTML;

                // Set hidden input values
                $('#terms_conditions').val(termsHTML);
                $('#privacy_policy').val(privacyHTML);

                const saveBtn = $('.save_btn');
                const originalText = saveBtn.html();
                saveBtn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Saving...');
                saveBtn.prop('disabled', true);

                const formData = new FormData(this);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        saveBtn.html('<i class="fas fa-check mr-2"></i>Saved!');

                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Website settings updated successfully!',
                                timer: 2000,
                                showConfirmButton: false
                            });

                            // Reload page after 2 seconds to show updated images
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
                        }
                    },
                    error: function(xhr) {
                        saveBtn.html(originalText);
                        saveBtn.prop('disabled', false);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            let errorMessage = '';

                            $.each(errors, function(key, value) {
                                errorMessage += value[0] + '<br>';
                            });

                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Error!',
                                html: errorMessage,
                                confirmButtonColor: '#3b82f6'
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Something went wrong. Please try again.',
                                confirmButtonColor: '#3b82f6'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
