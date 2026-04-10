@extends('admin.layouts.app')

@section('title', 'Arya Meals - Add Restaurant')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center space-x-4">
            <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Add New Restaurant</h1>
                <p class="text-sm text-purple-500 mt-1">Register a new restaurant in the system</p>
            </div>
        </section>

        <!-- Add Restaurant Form -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <h2 class="text-lg font-bold text-purple-900">Restaurant Information</h2>
            </div>
            
            <form id="addRestaurantForm" enctype="multipart/form-data" class="p-6">
                @csrf
                
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
                        <label class="block text-sm font-medium text-purple-700 mb-2">Restaurant Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Enter restaurant name">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Category <span class="text-red-600">*</span></label>
                        <select name="category" class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Category</option>
                            <option value="indian">Indian</option>
                            <option value="chinese">Chinese</option>
                            <option value="italian">Italian</option>
                            <option value="mexican">Mexican</option>
                            <option value="thai">Thai</option>
                            <option value="american">American</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" 
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="restaurant@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Phone <span class="text-red-600">*</span></label>
                        <input type="tel" name="phone" 
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="+91 98765 43210">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Address Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Address Information</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Address <span class="text-red-600">*</span></label>
                        <textarea name="address" rows="3" 
                                  class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Enter complete address"></textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">City <span class="text-red-600">*</span></label>
                        <input type="text" name="city" 
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Mumbai">
                        @error('city')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">State <span class="text-red-600">*</span></label>
                        <input type="text" name="state" 
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Maharashtra">
                        @error('state')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Business Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Business Information</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" min="1" max="5" step="0.5" value="5"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="5">
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Describe restaurant specialties, cuisine type, etc."></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Media -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Restaurant Image <span class="text-red-600">*</span></h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="border-2 border-dashed border-purple-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                            <div id="uploadArea" class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-purple-400 mb-2"></i>
                                <p class="text-sm text-purple-600 font-medium">Click to upload or drag and drop</p>
                                <p class="text-xs text-purple-500 mt-1">PNG, JPG, JPEG, GIF, WebP, SVG, AVIF up to 10MB</p>
                            </div>
                            <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml,image/avif" class="hidden" id="restaurantImage">
                            <button type="button" id="chooseFileBtn" onclick="document.getElementById('restaurantImage').click()" 
                                    class="px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors">
                                <i class="fas fa-upload mr-2"></i>
                                Choose File
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
                    @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Settings -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Settings</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="space-y-4">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="is_active" checked 
                                       class="w-4 h-4 text-purple-600 border-purple-200 rounded focus:ring-purple-500">
                                <span class="ml-3 text-sm text-purple-700">Make restaurant active immediately</span>
                            </label>
                            <p class="text-xs text-purple-500">Active restaurants will be visible to customers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit" id="submitRestaurantBtn" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Save Restaurant
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
            window.location.href = '{{ route("admin.restaurants") }}';
        }

        // Real-time validation on form submission
        document.getElementById('addRestaurantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear previous errors
            clearFieldErrors();
            hideValidationErrors();
            
            // Get required fields
            const requiredFields = ['name', 'category', 'email', 'phone', 'address', 'city', 'state'];
            const errors = {};
            let hasErrors = false;
            
            // Check each required field
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && !field.value.trim()) {
                    const fieldLabel = getFieldLabel(fieldName);
                    errors[fieldName] = [`${fieldLabel} field is required.`];
                    hasErrors = true;
                }
            });
            
            // Check image field
            const imageField = document.querySelector('[name="image"]');
            if (imageField && !imageField.files.length) {
                errors['image'] = ['The image field is required.'];
                hasErrors = true;
            }
            
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
                'name': 'Restaurant Name',
                'category': 'Category',
                'email': 'Email',
                'phone': 'Phone',
                'address': 'Address',
                'city': 'City',
                'state': 'State'
            };
            return labels[fieldName] || fieldName.charAt(0).toUpperCase() + fieldName.slice(1);
        }

        function submitForm(form) {
            // Clear any existing field errors before submission
            clearFieldErrors();
            hideValidationErrors();
            
            const formData = new FormData(form);
            const submitButton = form.querySelector('button[type="submit"]') || 
                                 document.getElementById('submitRestaurantBtn') ||
                                 form.querySelector('.btn[type="submit"]');
            
            if (!submitButton) {
                console.error('Submit button not found');
                alert('Error: Submit button not found');
                return;
            }
            
            const originalText = submitButton.innerHTML;
            
            // Handle checkbox properly - include unchecked state
            const isActiveCheckbox = form.querySelector('input[name="is_active"]');
            if (isActiveCheckbox) {
                formData.set('is_active', isActiveCheckbox.checked ? '1' : '0');
            }
            
            // Show loading statep
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Adding Restaurant...';
            
            fetch('{{ route("admin.restaurants.store") }}', {
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
                    showSuccessMessage('Restaurant added successfully!');
                    setTimeout(() => {
                        window.location.href = '{{ route("admin.restaurants") }}';
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
            
            // Clear previous field errors
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
                    
                    // Highlight field with error
                    highlightFieldError(field);
                });
                
                // Show error container with animation
                errorContainer.classList.remove('hidden');
                errorContainer.classList.remove('hide');
                
                // Scroll to error container smoothly
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
            // Remove error styling from all fields
            document.querySelectorAll('.field-error').forEach(element => {
                element.classList.remove('field-error');
            });
            
            // Remove individual field error messages
            document.querySelectorAll('.field-error-message').forEach(element => {
                element.remove();
            });
        }

        function highlightFieldError(fieldName) {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.classList.add('field-error');
                
                // Remove error class when user starts typing
                field.addEventListener('input', function() {
                    this.classList.remove('field-error');
                }, { once: true });
                
                // Focus on the first error field
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
            
            // Auto remove after 5 seconds
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
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                errorDiv.classList.add('hide');
                setTimeout(() => {
                    errorDiv.remove();
                }, 300);
            }, 5000);
        }

        // Image upload preview
        document.getElementById('restaurantImage').addEventListener('change', function(e) {
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
                
                // Show preview and hide upload area
                const reader = new FileReader();
                reader.onload = function(e) {
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
            document.getElementById('restaurantImage').value = '';
            document.getElementById('uploadArea').classList.remove('hidden');
            document.getElementById('chooseFileBtn').classList.remove('hidden');
            document.getElementById('imagePreview').classList.add('hidden');
            document.querySelector('#imagePreview img').src = '';
            document.getElementById('imageName').textContent = '';
        }

        // Clear validation errors when user starts typing
        document.querySelectorAll('input, select, textarea').forEach(element => {
            element.addEventListener('input', function() {
                const errorContainer = document.getElementById('validationErrors');
                if (!errorContainer.classList.contains('hidden')) {
                    // Check if this field has errors
                    if (this.classList.contains('field-error')) {
                        this.classList.remove('field-error');
                    }
                    
                    // Check if all errors are cleared
                    const remainingErrors = document.querySelectorAll('.field-error');
                    if (remainingErrors.length === 0) {
                        hideValidationErrors();
                    }
                }
            });
        });
    </script>
@endsection
