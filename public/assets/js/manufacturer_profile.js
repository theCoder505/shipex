document.addEventListener('DOMContentLoaded', function () {
    let currentStep = parseInt(document.querySelector('.current_step')?.value) || 1;
    const totalSteps = 6;
    const visibleSteps = 5;

    // File size limits (in bytes)
    const FILE_SIZE_LIMITS = {
        single_file: 10 * 1024 * 1024, // 10MB per file
        total_request: 250 * 1024 * 1024 // 250MB total
    };

    // Track file sizes for validation
    const fileSizes = {
        business_license: 0,
        company_logo: 0,
        catalogue: 0,
        products: {},
        certifications: {},
        factory_pictures: {},
        patents: {}
    };

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

    // File size validation functions
    function validateFileSize(file, inputName) {
        if (file.size > FILE_SIZE_LIMITS.single_file) {
            const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
            const limitMB = (FILE_SIZE_LIMITS.single_file / (1024 * 1024)).toFixed(2);
            return {
                valid: false,
                message: `File "${file.name}" is ${fileSizeMB}MB. Maximum file size is ${limitMB}MB.`
            };
        }
        return { valid: true };
    }

    function calculateTotalSize() {
        let totalSize = 0;
        
        // Single files
        totalSize += fileSizes.company_logo || 0;
        totalSize += fileSizes.business_license || 0;
        totalSize += fileSizes.catalogue || 0;
        
        // Array files
        Object.values(fileSizes.products).forEach(size => totalSize += size || 0);
        Object.values(fileSizes.certifications).forEach(size => totalSize += size || 0);
        Object.values(fileSizes.factory_pictures).forEach(size => totalSize += size || 0);
        Object.values(fileSizes.patents).forEach(size => totalSize += size || 0);
        
        return totalSize;
    }

    function validateTotalSize() {
        const totalSize = calculateTotalSize();
        if (totalSize > FILE_SIZE_LIMITS.total_request) {
            const totalSizeMB = (totalSize / (1024 * 1024)).toFixed(2);
            const limitMB = (FILE_SIZE_LIMITS.total_request / (1024 * 1024)).toFixed(2);
            return {
                valid: false,
                message: `Total upload size ${totalSizeMB}MB exceeds maximum limit of ${limitMB}MB. Please remove some files.`
            };
        }
        return { valid: true };
    }

    function showFileSizeError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'File Too Large',
                html: message,
                timer: 6000,
                showConfirmButton: true
            });
        } else {
            alert(message);
        }
    }

    function showTotalSizeError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Upload Limit Exceeded',
                html: message,
                timer: 8000,
                showConfirmButton: true
            });
        } else {
            alert(message);
        }
    }

    function updateFileSizeDisplay(input, file) {
        const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
        const fileNameElement = input.closest('.file-upload-area').querySelector('.file-name');
        if (fileNameElement) {
            // Add file size to display
            const originalText = fileNameElement.textContent;
            fileNameElement.innerHTML = `${file.name} <span class="text-xs ${file.size > FILE_SIZE_LIMITS.single_file ? 'text-red-600' : 'text-green-600'}">(${fileSizeMB}MB)</span>`;
        }
    }

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
        if (file) {
            // Validate file size
            const sizeValidation = validateFileSize(file, input.name);
            if (!sizeValidation.valid) {
                showFileSizeError(sizeValidation.message);
                input.value = ''; // Clear the input
                if (fileNameElement) fileNameElement.textContent = '';
                if (previewElement) previewElement.style.display = 'none';
                area.classList.remove('has-image');
                const placeholder = area.querySelector('.upload-placeholder');
                if (placeholder) placeholder.style.display = 'block';
                removeFileSize(input.name);
                return;
            }

            // Update file size tracking
            updateFileSizeTracking(input.name, file.size);

            // Validate total size
            const totalValidation = validateTotalSize();
            if (!totalValidation.valid) {
                showTotalSizeError(totalValidation.message);
                input.value = ''; // Clear the input
                if (fileNameElement) fileNameElement.textContent = '';
                if (previewElement) previewElement.style.display = 'none';
                area.classList.remove('has-image');
                const placeholder = area.querySelector('.upload-placeholder');
                if (placeholder) placeholder.style.display = 'block';
                removeFileSize(input.name);
                return;
            }

            // Update display with file size
            updateFileSizeDisplay(input, file);

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

    function updateFileSizeTracking(inputName, fileSize) {
        if (inputName === 'company_logo') {
            fileSizes.company_logo = fileSize;
        } else if (inputName === 'business_registration_license') {
            fileSizes.business_license = fileSize;
        } else if (inputName === 'catalogue') {
            fileSizes.catalogue = fileSize;
        } else if (inputName.startsWith('products[') && inputName.endsWith('[image]')) {
            const match = inputName.match(/products\[(\d+)\]\[image\]/);
            if (match) {
                fileSizes.products[match[1]] = fileSize;
            }
        } else if (inputName.startsWith('certifications[') && inputName.endsWith('[document]')) {
            const match = inputName.match(/certifications\[(\d+)\]\[document\]/);
            if (match) {
                fileSizes.certifications[match[1]] = fileSize;
            }
        } else if (inputName.startsWith('factory_pictures[') && inputName.endsWith('[image]')) {
            const match = inputName.match(/factory_pictures\[(\d+)\]\[image\]/);
            if (match) {
                fileSizes.factory_pictures[match[1]] = fileSize;
            }
        } else if (inputName.startsWith('patents[') && inputName.endsWith('[document]')) {
            const match = inputName.match(/patents\[(\d+)\]\[document\]/);
            if (match) {
                fileSizes.patents[match[1]] = fileSize;
            }
        }
    }

    function removeFileSize(inputName) {
        if (inputName === 'company_logo') {
            fileSizes.company_logo = 0;
        } else if (inputName === 'business_registration_license') {
            fileSizes.business_license = 0;
        } else if (inputName === 'catalogue') {
            fileSizes.catalogue = 0;
        } else if (inputName.startsWith('products[') && inputName.endsWith('[image]')) {
            const match = inputName.match(/products\[(\d+)\]\[image\]/);
            if (match) {
                delete fileSizes.products[match[1]];
            }
        } else if (inputName.startsWith('certifications[') && inputName.endsWith('[document]')) {
            const match = inputName.match(/certifications\[(\d+)\]\[document\]/);
            if (match) {
                delete fileSizes.certifications[match[1]];
            }
        } else if (inputName.startsWith('factory_pictures[') && inputName.endsWith('[image]')) {
            const match = inputName.match(/factory_pictures\[(\d+)\]\[image\]/);
            if (match) {
                delete fileSizes.factory_pictures[match[1]];
            }
        } else if (inputName.startsWith('patents[') && inputName.endsWith('[document]')) {
            const match = inputName.match(/patents\[(\d+)\]\[document\]/);
            if (match) {
                delete fileSizes.patents[match[1]];
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
                removeFileSize(`products[${index}][image]`);
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
                                <p class="text-xs text-gray-500 mt-1">(e.g. ISO, CE,ROHS, etc.)</p>
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
                removeFileSize(`certifications[${index}][document]`);
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
                removeFileSize(`factory_pictures[${index}][image]`);
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
                removeFileSize(`patents[${index}][document]`);
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

    // Form submission with final validation
    const profileForm = document.getElementById('profileForm');
    if (profileForm) {
        profileForm.addEventListener('submit', function (e) {
            // Final file size validation before submission
            const totalValidation = validateTotalSize();
            if (!totalValidation.valid) {
                e.preventDefault();
                showTotalSizeError(totalValidation.message);
                return;
            }

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


