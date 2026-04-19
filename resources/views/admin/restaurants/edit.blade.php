@extends('admin.layouts.app')

@section('title', 'Arya Meals - Edit Restaurant')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center space-x-4">
            <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Edit Restaurant</h1>
                <p class="text-sm text-purple-500 mt-1">Update restaurant information</p>
            </div>
        </section>

        <!-- Edit Restaurant Form -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <h2 class="text-lg font-bold text-purple-900">Restaurant Information</h2>
            </div>
            
            <form id="editRestaurantForm" enctype="multipart/form-data" class="p-6">
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
                        <label class="block text-sm font-medium text-purple-700 mb-2">Restaurant Name <span class="text-red-600">*</span></label>
                        <input type="text" name="name" value="{{ $restaurant->name }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Enter restaurant name">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Category <span class="text-red-600">*</span></label>
                        <select name="category"
                                class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Category</option>
                            <option value="indian" {{ $restaurant->category == 'indian' ? 'selected' : '' }}>Indian</option>
                            <option value="chinese" {{ $restaurant->category == 'chinese' ? 'selected' : '' }}>Chinese</option>
                            <option value="italian" {{ $restaurant->category == 'italian' ? 'selected' : '' }}>Italian</option>
                            <option value="mexican" {{ $restaurant->category == 'mexican' ? 'selected' : '' }}>Mexican</option>
                            <option value="thai" {{ $restaurant->category == 'thai' ? 'selected' : '' }}>Thai</option>
                            <option value="american" {{ $restaurant->category == 'american' ? 'selected' : '' }}>American</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Email <span class="text-red-600">*</span></label>
                        <input type="email" name="email" value="{{ $restaurant->email }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="restaurant@example.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Phone <span class="text-red-600">*</span></label>
                        <input type="tel" name="phone" value="{{ $restaurant->phone }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder=" 98765 43210">
                    </div>
                    
                    <!-- Address Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Address Information</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Address <span class="text-red-600">*</span></label>
                        <textarea name="address" rows="3" 
                                  class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Enter complete address">{{ $restaurant->address }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">City <span class="text-red-600">*</span></label>
                        <input type="text" name="city" value="{{ $restaurant->city }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Mumbai">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">State <span class="text-red-600">*</span></label>
                        <input type="text" name="state" value="{{ $restaurant->state }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="Maharashtra">
                    </div>
                    
                    <!-- Business Information -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Business Information</h3>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" min="1" max="5" step="0.5" value="{{ $restaurant->rating ?? 5 }}"
                               class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="5">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Description</label>
                        <textarea name="description" rows="4" 
                                  class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                  placeholder="Describe restaurant specialties, cuisine type, etc.">{{ $restaurant->description }}</textarea>
                    </div>
                    
                    <!-- Media -->
                    <div class="md:col-span-2 mt-6">
                        <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Restaurant Image</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <div class="border-2 border-dashed border-purple-300 rounded-lg p-8 text-center hover:border-purple-400 transition-colors">
                            @if($restaurant->image)
                                <div id="currentImage" class="mb-4">
                                    <img src="{{ asset('storage/restaurants/' . $restaurant->image) }}" alt="Current Image" class="mx-auto h-32 w-32 object-cover rounded-lg">
                                    <p class="text-sm text-purple-600 mt-2">Current Image</p>
                                </div>
                            @endif
                            <div id="uploadArea" class="mb-4">
                                <i class="fas fa-cloud-upload-alt text-4xl text-purple-400 mb-2"></i>
                                <p class="text-sm text-purple-600 font-medium">Click to upload or drag and drop</p>
                                <p class="text-xs text-purple-500 mt-1">PNG, JPG, JPEG, GIF, WebP, SVG, AVIF up to 10MB (Leave empty to keep current)</p>
                            </div>
                            <input type="file" name="image" accept="image/jpeg,image/jpg,image/png,image/gif,image/webp,image/svg+xml,image/avif" class="hidden" id="restaurantImage">
                            <button type="button" id="chooseFileBtn" onclick="document.getElementById('restaurantImage').click()" 
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
                                <input type="checkbox" name="is_active" {{ $restaurant->is_active ? 'checked' : '' }}
                                       class="w-4 h-4 text-purple-600 border-purple-200 rounded focus:ring-purple-500">
                                <span class="ml-3 text-sm text-purple-700">Make restaurant active</span>
                            </label>
                            <p class="text-xs text-purple-500">Active restaurants will be visible to customers</p>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Update Restaurant
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

        // AJAX form submission
        document.getElementById('editRestaurantForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Clear any existing field errors before submission
            clearFieldErrors();
            hideValidationErrors();
            
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Handle checkbox properly - include unchecked state
            const isActiveCheckbox = this.querySelector('input[name="is_active"]');
            if (isActiveCheckbox) {
                formData.set('is_active', isActiveCheckbox.checked ? '1' : '0');
            }
            
            // Show loading state
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating Restaurant...';
            
            const url = '{{ route("admin.restaurants.update", $restaurant->id) }}';
            
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
                    showSuccessMessage('Restaurant updated successfully!');
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
        });

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
        document.getElementById('restaurantImage').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if (file.size > 10 * 1024 * 1024) {
                    showValidationErrors({image: ['File size must be less than 10MB']});
                    this.value = '';
                    return;
                }
                
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
            document.getElementById('restaurantImage').value = '';
            
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
