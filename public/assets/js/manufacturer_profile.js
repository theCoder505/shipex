document.addEventListener('DOMContentLoaded', function () {
    // File upload initialization
    initializeFileUploads();
    
    // Conditional field toggles
    setupConditionalFields();
    
    // Character counter
    setupCharacterCounters();
    
    // Add/remove dynamic items
    setupDynamicItems();
});

function initializeFileUploads() {
    document.querySelectorAll('.file-upload-area').forEach(area => {
        const input = area.querySelector('input[type="file"]');
        const preview = area.querySelector('.file-preview');
        const placeholder = area.querySelector('.upload-placeholder');
        const fileName = area.querySelector('.file-name');
        
        area.addEventListener('click', (e) => {
            if (e.target.tagName !== 'INPUT') {
                input.click();
            }
        });
        
        area.addEventListener('dragover', (e) => {
            e.preventDefault();
            area.classList.add('dragover');
        });
        
        area.addEventListener('dragleave', () => {
            area.classList.remove('dragover');
        });
        
        area.addEventListener('drop', (e) => {
            e.preventDefault();
            area.classList.remove('dragover');
            if (e.dataTransfer.files.length) {
                input.files = e.dataTransfer.files;
                handleFileSelect(e.dataTransfer.files[0]);
            }
        });
        
        input.addEventListener('change', () => {
            if (input.files.length) {
                handleFileSelect(input.files[0]);
            }
        });
        
        function handleFileSelect(file) {
            if (file) {
                // Update file name display
                if (fileName) {
                    const fileSizeMB = (file.size / (1024 * 1024)).toFixed(2);
                    fileName.textContent = `${file.name} (${fileSizeMB}MB)`;
                }
                
                // Show preview for images
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
        }
    });
}

function setupConditionalFields() {
    // Export experience toggle
    document.querySelectorAll('input[name="export_experience"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const exportYearsField = document.getElementById('export_years_field');
            if (exportYearsField) {
                if (this.value === 'yes') {
                    exportYearsField.classList.remove('hidden');
                    exportYearsField.querySelector('input')?.setAttribute('required', 'required');
                } else {
                    exportYearsField.classList.add('hidden');
                    exportYearsField.querySelector('input')?.removeAttribute('required');
                }
            }
        });
    });
    
    // Patents toggle
    document.querySelectorAll('input[name="has_patents"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const patentsContainer = document.getElementById('patents_container');
            const addPatentBtn = document.getElementById('add_patent_btn');
            
            if (this.value === 'yes') {
                patentsContainer?.classList.remove('hidden');
                addPatentBtn?.classList.remove('hidden');
            } else {
                patentsContainer?.classList.add('hidden');
                addPatentBtn?.classList.add('hidden');
            }
        });
    });
    
    // Trigger initial state
    document.querySelector('input[name="export_experience"]:checked')?.dispatchEvent(new Event('change'));
    document.querySelector('input[name="has_patents"]:checked')?.dispatchEvent(new Event('change'));
}

function setupCharacterCounters() {
    document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
        const countId = textarea.getAttribute('data-count-id') || textarea.id + '_count';
        const countElement = document.getElementById(countId);
        
        if (countElement) {
            textarea.addEventListener('input', function() {
                countElement.textContent = this.value.length;
            });
            // Initialize count
            countElement.textContent = textarea.value.length;
        }
    });
}

function setupDynamicItems() {
    // Product management
    document.getElementById('add_product_btn')?.addEventListener('click', function() {
        const container = document.getElementById('products_container');
        const count = container.querySelectorAll('.product-item').length;
        
        if (count >= 5) {
            alert('Maximum 5 products allowed');
            return;
        }
        
        const newProduct = createProductItem(count);
        container.insertAdjacentHTML('beforeend', newProduct);
        initializeFileUploads();
    });
    
    // Similar functions for certifications, patents, factory pictures
    // ...
}

function createProductItem(index) {
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

function removeItem(button, type) {
    const item = button.closest(`.${type}-item`);
    if (item && confirm('Are you sure you want to remove this item?')) {
        item.remove();
        // Renumber remaining items
        document.querySelectorAll(`.${type}-item`).forEach((item, index) => {
            const numberSpan = item.querySelector('.w-8.h-8');
            if (numberSpan) {
                numberSpan.textContent = index + 1;
            }
        });
    }
}