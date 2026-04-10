@extends('admin.layouts.app')

@section('title', 'Arya Meals - Edit Menu Item')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center space-x-4">
            <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Edit Menu Item</h1>
                <p class="text-sm text-purple-500 mt-1">Update menu item information</p>
            </div>
        </section>

        <!-- Edit Menu Item Form -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <h2 class="text-lg font-bold text-purple-900">Menu Item Information</h2>
            </div>
            
            <form id="editMenuItemForm" enctype="multipart/form-data" class="p-6">
                @csrf
                @method('PUT')
                
                <!-- Validation Errors Container -->
                <div id="validationErrors" class="hidden mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg shadow-md validation-error">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-triangle text-red-400 text-lg"></i>
                        </div>
                        <div class="ml-3 flex-1">
                            <div class="mt-2">
                                <ul id="errorList" class="list-disc list-inside text-sm text-red-700 space-y-1">
                                </ul>
                            </div>
                        </div>
                        <div class="ml-auto pl-3">
                            <div class="-mx-1.5 -my-1.5">
                                <button type="button" onclick="hideValidationErrors()" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 transition-colors">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Basic Information -->
                    <div class="md:col-span-2">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Basic Information</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Restaurant <span class="text-red-600">*</span></label>
                        <select name="restaurant_id"
                                class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Restaurant</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}" {{ $menuItem->restaurant_id == $restaurant->id ? 'selected' : '' }}>
                                    {{ $restaurant->name }} ({{ $restaurant->city }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Item Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" value="{{ $menuItem->name }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Enter menu item name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Category <span class="text-red-600">*</span></label>
                        <input type="text" name="category" id="categoryInput" value="{{ $menuItem->category }}" oninput="togglePricing()"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Enter category (e.g., starters, main_course, biryani, chinese, desserts, beverages)">
                    </div>
                    
                    <!-- Pricing -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Pricing</h3>
                    </div>
                    
                    <!-- Price Type Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Price Type <span class="text-red-600">*</span></label>
                        <div class="flex gap-6">
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="price_type" value="dual" 
                                       @if($menuItem->price == 0 || $menuItem->price == null) checked @endif
                                       class="w-4 h-4 text-purple-600 border-purple-200 focus:ring-purple-500">
                                <span class="ml-2 text-sm text-purple-700">Half/Full Plate Pricing</span>
                            </label>
                            <label class="flex items-center cursor-pointer">
                                <input type="radio" name="price_type" value="single"
                                       @if($menuItem->price > 0) checked @endif
                                       class="w-4 h-4 text-purple-600 border-purple-200 focus:ring-purple-500">
                                <span class="ml-2 text-sm text-purple-700">Single Price</span>
                            </label>
                        </div>
                        <p class="text-xs text-purple-500 mt-1">Choose pricing type: Half/Full for food items, Single price for beverages/groceries</p>
                    </div>
                    
                    <!-- Half/Full Plate Pricing -->
                    <div id="halfFullPricing" class="grid grid-cols-1 md:grid-cols-2 gap-6 @if($menuItem->price > 0) hidden @endif">
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Half Plate Price</label>
                            <input type="number" name="half_plate_price" step="0.01" min="0" value="{{ $menuItem->half_plate_price }}"
                                   class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Full Plate Price</label>
                            <input type="number" name="full_plate_price" step="0.01" min="0" value="{{ $menuItem->full_plate_price }}"
                                   class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00">
                        </div>
                    </div>
                    
                    <!-- Single Price -->
                    <div id="singlePricing" class="@if($menuItem->price == 0 || $menuItem->price == null) hidden @endif">
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Price</label>
                            <input type="number" name="single_price" step="0.01" min="0" value="{{ $menuItem->price }}"
                                   class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="0.00">
                            <p class="text-xs text-purple-500 mt-1">For single price items like beverages, groceries, etc.</p>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Description</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Describe the menu item, ingredients, etc.">{{ $menuItem->description }}</textarea>
                    </div>
                    
                    <!-- Media -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Menu Item Image</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="border-2 border-dashed border-purple-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                            @if($menuItem->image)
                                <div id="currentImage" class="mb-4">
                                    <img src="{{ asset('storage/menu-items/' . $menuItem->image) }}" alt="Current Image" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                    <p class="text-sm text-purple-600 mt-2">Current Image</p>
                                </div>
                            @endif
                            <div id="uploadArea" class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-purple-400 mb-2"></i>
                                <p class="text-sm text-purple-600 font-medium">Click to upload or drag and drop</p>
                                <p class="text-xs text-purple-500 mt-1">PNG, JPG, JPEG, GIF, WebP, SVG, AVIF up to 10MB (Leave empty to keep current)</p>
                            </div>
                            <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml,image/avif" class="hidden" id="menuItemImage">
                            <button type="button" id="chooseFileBtn" onclick="document.getElementById('menuItemImage').click()" 
                                    class="px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Choose New File
                            </button>
                            <div id="imagePreview" class="mt-4 hidden">
                                <img src="" alt="Preview" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                <p class="text-sm text-purple-600 mt-2" id="imageName"></p>
                                <button type="button" onclick="removeImage()" class="mt-2 px-3 py-1 bg-red-100 text-red-600 text-sm rounded-lg hover:bg-red-200 transition-colors">
                                    <i class="fas fa-times mr-1"></i>
                                    Remove Image
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Settings -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Settings</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_available" {{ $menuItem->is_available ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 border-purple-200 rounded focus:ring-purple-500">
                                <span class="ml-3 text-sm text-purple-700">Make item available</span>
                            </label>
                            <p class="text-xs text-purple-500">Available items will be visible to customers</p>
                        </div>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_recommended" {{ $menuItem->is_recommended ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 border-purple-200 rounded focus:ring-purple-500">
                                <span class="ml-3 text-sm text-purple-700">Mark as recommended item</span>
                            </label>
                            <p class="text-xs text-purple-500">Recommended items will be highlighted to customers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Update Menu Item
                    </button>
                    <button type="button" onclick="goBack()" class="flex-1 px-6 py-3 bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-lg font-medium hover:from-slate-200 hover:to-slate-300 transition-all hover:scale-105 transform">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </section>
    </div>

    <script>
        function goBack() {
            window.location.href = '{{ route("admin.menu") }}';
        }

        function togglePricing() {
            const category = document.getElementById('categoryInput').value.toLowerCase();
            const halfFullPricing = document.getElementById('halfFullPricing');
            const singlePricing = document.getElementById('singlePricing');
            
            if (category === 'beverages') {
                halfFullPricing.classList.add('hidden');
                singlePricing.classList.remove('hidden');
            } else {
                halfFullPricing.classList.remove('hidden');
                singlePricing.classList.add('hidden');
            }
        }

        // Initialize pricing display on page load
        document.addEventListener('DOMContentLoaded', function() {
            togglePricing();
        });

        // AJAX form submission with validation
        document.getElementById('editMenuItemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear any existing field errors before submission
            clearFieldErrors();
            hideValidationErrors();
            
            // Get required fields based on category
            const category = document.getElementById('categoryInput').value.toLowerCase();
            let requiredFields = ['restaurant_id', 'name', 'category'];
            
            if (category === 'beverages') {
                requiredFields.push('single_price');
            } else {
                requiredFields.push('half_plate_price', 'full_plate_price');
            }
            const errors = {};
            let hasErrors = false;
            
            // Check each required field
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && !field.value.trim()) {
                    const fieldLabel = getFieldLabel(fieldName);
                    errors[fieldName] = [`${fieldLabel} is required`];
                    hasErrors = true;
                }
            });
            
            // If there are errors, show them with animation
            if (hasErrors) {
                showValidationErrors(errors);
                return;
            }
            
            // If no errors, proceed with form submission
            submitForm(this);
        });

        function getFieldLabel(fieldName) {
            const labels = {
                'restaurant_id': 'Restaurant',
                'name': 'Item Name',
                'category': 'Category',
                'half_plate_price': 'Half Plate Price',
                'full_plate_price': 'Full Plate Price',
                'single_price': 'Price'
            };
            return labels[fieldName] || fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
        }

        function submitForm(form) {
            // Clear any existing field errors before submission
            clearFieldErrors();
            hideValidationErrors();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Handle pricing based on category
            const category = document.getElementById('categoryInput').value.toLowerCase();
            if (category === 'beverages') {
                // For beverages, set single_price to both half and full plate prices
                const singlePrice = formData.get('single_price');
                formData.set('half_plate_price', singlePrice);
                formData.set('full_plate_price', singlePrice);
                formData.delete('single_price');
            }
            
            // Handle checkboxes properly
            const availableCheckbox = form.querySelector('input[name="is_available"]');
            const recommendedCheckbox = form.querySelector('input[name="is_recommended"]');
            
            if (availableCheckbox) {
                formData.set('is_available', availableCheckbox.checked ? '1' : '0');
            }
            if (recommendedCheckbox) {
                formData.set('is_recommended', recommendedCheckbox.checked ? '1' : '0');
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating Menu Item...';
            
            const url = '{{ route("admin.menu.update", $menuItem->id) }}';
            
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccessMessage('Menu item updated successfully!');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.menu") }}';
                    }, 2000);
                } else {
                    showValidationErrors(data.errors || {});
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
                showErrorMessage('Something went wrong. Please try again.');
            });
        }

        function showValidationErrors(errors) {
            const errorContainer = document.getElementById('validationErrors');
            const errorList = document.getElementById('errorList');
            
            clearFieldErrors();
            
            if (Object.keys(errors).length > 0) {
                errorList.innerHTML = '';
                
                Object.entries(errors).forEach(([field, messages]) => {
                    const fieldErrors = Array.isArray(messages) ? messages : [messages];
                    fieldErrors.forEach(message => {
                        const li = document.createElement('li');
                        li.className = 'error-message';
                        li.textContent = message;
                        errorList.appendChild(li);
                    });
                    
                    highlightFieldError(field);
                });
                
                errorContainer.classList.remove('hidden');
                errorContainer.classList.remove('hide');
                
                setTimeout(() => {
                    errorContainer.scrollIntoView({ 
                        behavior: 'smooth', 
                        block: 'center' 
                    });
                }, 100);
            } else {
                hideValidationErrors();
            }
        }

        function hideValidationErrors() {
            const errorContainer = document.getElementById('validationErrors');
            errorContainer.classList.add('hide');
            
            setTimeout(() => {
                errorContainer.classList.add('hidden');
                errorContainer.classList.remove('hide');
            }, 300);
        }

        function clearFieldErrors() {
            document.querySelectorAll('.field-error').forEach(element => {
                element.classList.remove('field-error');
            });
            
            document.querySelectorAll('.field-error-message').forEach(element => {
                element.remove();
            });
        }

        function highlightFieldError(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('field-error');
                
                field.addEventListener('input', function() {
                    this.classList.remove('field-error');
                }, { once: true });
                
                if (document.querySelectorAll('.field-error').length === 1) {
                    setTimeout(() => {
                        field.focus();
                        field.scrollIntoView({ 
                            behavior: 'smooth', 
                            block: 'center' 
                        });
                    }, 200);
                }
            }
        }

        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'toast-notification fixed top-4 right-4 bg-green-100 border-l-4 border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50';
            successDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" onclick="this.parentElement.parentElement.classList.add('hide')" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(successDiv);
            
            setTimeout(() => {
                successDiv.classList.add('hide');
                setTimeout(() => {
                    successDiv.remove();
                }, 300);
            }, 5000);
        }

        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'toast-notification fixed top-4 right-4 bg-red-100 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-lg shadow-lg z-50';
            errorDiv.innerHTML = `
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-red-400 text-lg"></i>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium">${message}</p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button type="button" onclick="this.parentElement.parentElement.classList.add('hide')" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
            document.body.appendChild(errorDiv);
            
            setTimeout(() => {
                errorDiv.classList.add('hide');
                setTimeout(() => {
                    errorDiv.remove();
                }, 300);
            }, 5000);
        }

        // Image upload preview
        document.getElementById('menuItemImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size
                if (file.size > 10 * 1024 * 1024) {
                    showValidationErrors({image: ['File size must be less than 10MB']});
                    this.value = '';
                    return;
                }
                
                // Check file type
                if (!file.type.match('image.*')) {
                    showValidationErrors({image: ['Please select an image file (JPEG, PNG, JPG, GIF, WebP, SVG, AVIF)']});
                    this.value = '';
                    return;
                }
                
                // Hide current image and upload area, show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    const currentImage = document.getElementById('currentImage');
                    if (currentImage) {
                        currentImage.classList.add('hidden');
                    }
                    document.getElementById('uploadArea').classList.add('hidden');
                    document.getElementById('chooseFileBtn').classList.add('hidden');
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.querySelector('#imagePreview img').src = e.target.result;
                    document.getElementById('imageName').textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        });

        function removeImage() {
            document.getElementById('menuItemImage').value = '';
            
            const currentImage = document.getElementById('currentImage');
            if (currentImage) {
                currentImage.classList.remove('hidden');
            }
            document.getElementById('uploadArea').classList.remove('hidden');
            document.getElementById('chooseFileBtn').classList.remove('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.querySelector('#imagePreview img').src = '';
            document.getElementById('imageName').textContent = '';
        }

        // Price type selection handler
        document.addEventListener('DOMContentLoaded', function() {
            const priceTypeRadios = document.querySelectorAll('input[name="price_type"]');
            const halfFullPricing = document.getElementById('halfFullPricing');
            const singlePricing = document.getElementById('singlePricing');

            priceTypeRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'dual') {
                        halfFullPricing.classList.remove('hidden');
                        singlePricing.classList.add('hidden');
                    } else {
                        halfFullPricing.classList.add('hidden');
                        singlePricing.classList.remove('hidden');
                    }
                });
            });
        });

        // Clear validation errors when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                const errorContainer = document.getElementById('validationErrors');
                if (!errorContainer.classList.contains('hidden')) {
                    if (this.classList.contains('field-error')) {
                        this.classList.remove('field-error');
                    }
                    
                    const remainingErrors = document.querySelectorAll('.field-error');
                    if (remainingErrors.length === 0) {
                        hideValidationErrors();
                    }
                }
            });
        });
    </script>
@endsection
