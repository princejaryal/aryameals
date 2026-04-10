@extends('admin.layouts.app')

@section('title', 'Create Platform Fee - AryaMeals Admin')

@section('content')
<div class="p-6 space-y-8">
    <!-- Header Section -->
    <section class="flex items-center justify-between">
        <div class="flex items-center space-x-4">
            <button onclick="window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('admin.dashboard') }}')" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Create Platform Fee</h1>
                <p class="text-sm text-purple-500 mt-1">Add a new platform fee, tax, or delivery charge</p>
            </div>
        </div>
        <div class="flex items-center space-x-4">
            <a href="{{ route('admin.platform-fees.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors flex items-center space-x-2">
                <i class="fas fa-list"></i>
                <span>View All Fees</span>
            </a>
        </div>
    </section>

    <!-- Create Form -->
    <section class="bg-white rounded-xl shadow-sm border border-purple-100">
        <div class="p-6">
            <form action="{{ route('admin.platform-fees.store') }}" method="POST" class="space-y-6" id="platformFeeForm" novalidate>
                @csrf
                
                <!-- Fee Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-tag mr-2"></i>Fee Type
                        </label>
                        <select name="fee_type" data-required="true" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Fee Type</option>
                            <option value="delivery">Delivery Fee</option>
                            <option value="service">Service Fee</option>
                            <option value="tax">Tax</option>
                            <option value="convenience">Convenience Fee</option>
                            <option value="packaging">Packaging Fee</option>
                        </select>
                        @error('fee_type')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Fee Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-signature mr-2"></i>Fee Name
                        </label>
                        <input type="text" name="fee_name" data-required="true" maxlength="255" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="e.g., Standard Delivery, Service Charge, GST">
                        @error('fee_name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Calculation Type -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-calculator mr-2"></i>Calculation Type
                        </label>
                        <select name="fee_type_calculation" data-required="true" id="calculationType" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Calculation Type</option>
                            <option value="fixed">Fixed Amount</option>
                            <option value="percentage">Percentage</option>
                        </select>
                        @error('fee_type_calculation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sort Order -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-sort mr-2"></i>Sort Order
                        </label>
                        <input type="number" name="sort_order" min="0" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="0 (displayed first)">
                        @error('sort_order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Amount Fields -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fixed Amount -->
                    <div id="fixedAmountField">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-rupee-sign mr-2"></i>Fixed Amount (₹)
                        </label>
                        <input type="number" name="fee_amount" data-required="true" min="0" step="0.01" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="0.00">
                        @error('fee_amount')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Percentage -->
                    <div id="percentageField" class="hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-percent mr-2"></i>Percentage (%)
                        </label>
                        <input type="number" name="fee_percentage" data-required="true" min="0" max="100" step="0.01" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                               placeholder="0.00">
                        @error('fee_percentage')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-align-left mr-2"></i>Description
                    </label>
                    <textarea name="description" rows="4" maxlength="1000" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                              placeholder="Describe what this fee is for and how it's calculated..."></textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center space-x-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" 
                               class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                        <span class="text-sm font-medium text-gray-700">
                            <i class="fas fa-toggle-on mr-2"></i>Active
                        </span>
                    </label>
                    <p class="mt-1 text-xs text-gray-500">Enable this fee to be applied to orders</p>
                    @error('is_active')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.platform-fees.index') }}" 
                       class="px-6 py-3 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors flex items-center space-x-2">
                        <i class="fas fa-times"></i>
                        <span>Cancel</span>
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                        <i class="fas fa-save"></i>
                        <span>Create Fee</span>
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
// Form validation and field highlighting
document.getElementById('platformFeeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Clear previous highlights
    clearHighlights();
    
    // Get all required fields
    const requiredFields = document.querySelectorAll('[data-required="true"]');
    let isValid = true;
    let firstInvalidField = null;
    
    // Validate each required field
    requiredFields.forEach(field => {
        const value = field.value.trim();
        const isEmpty = !value || value === '';
        
        if (isEmpty) {
            isValid = false;
            highlightField(field);
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
        } else {
            removeHighlight(field);
        }
    });
    
    // If form is valid, submit it
    if (isValid) {
        this.submit();
    } else {
        // Scroll to first invalid field
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ 
                behavior: 'smooth', 
                block: 'center' 
            });
            firstInvalidField.focus();
        }
        
    }
});

// Toggle between fixed amount and percentage fields
document.getElementById('calculationType').addEventListener('change', function() {
    const fixedAmountField = document.getElementById('fixedAmountField');
    const percentageField = document.getElementById('percentageField');
    const fixedInput = fixedAmountField.querySelector('input');
    const percentageInput = percentageField.querySelector('input');
    
    if (this.value === 'fixed') {
        fixedAmountField.classList.remove('hidden');
        percentageField.classList.add('hidden');
        fixedInput.setAttribute('data-required', 'true');
        percentageInput.removeAttribute('data-required');
    } else if (this.value === 'percentage') {
        fixedAmountField.classList.add('hidden');
        percentageField.classList.remove('hidden');
        fixedInput.removeAttribute('data-required');
        percentageInput.setAttribute('data-required', 'true');
    } else {
        fixedAmountField.classList.add('hidden');
        percentageField.classList.add('hidden');
        fixedInput.removeAttribute('data-required');
        percentageInput.removeAttribute('data-required');
    }
});

// Helper functions
function highlightField(field) {
    field.classList.add('border-red-500', 'bg-red-50');
    field.classList.remove('border-gray-300');
    
    // Find or create error message container
    let errorContainer = field.parentNode.querySelector('.field-error');
    if (!errorContainer) {
        errorContainer = document.createElement('div');
        errorContainer.className = 'field-error mt-2 text-sm text-red-600 font-medium';
        field.parentNode.appendChild(errorContainer);
    }
    errorContainer.textContent = 'This field is required.';
}

function removeHighlight(field) {
    field.classList.remove('border-red-500', 'bg-red-50');
    field.classList.add('border-gray-300');
    
    // Remove error message
    const errorContainer = field.parentNode.querySelector('.field-error');
    if (errorContainer) {
        errorContainer.remove();
    }
}

function clearHighlights() {
    const highlightedFields = document.querySelectorAll('.border-red-500');
    highlightedFields.forEach(field => {
        removeHighlight(field);
    });
    
    // Remove any existing error messages
    const errorMessages = document.querySelectorAll('.field-error');
    errorMessages.forEach(msg => msg.remove());
    
    // Remove any validation error notifications
    const notifications = document.querySelectorAll('.validation-error');
    notifications.forEach(notif => notif.remove());
}

function showValidationError(message) {
    // Remove existing validation errors
    const existingNotifications = document.querySelectorAll('.validation-error');
    existingNotifications.forEach(notif => notif.remove());
    
    // Create new error notification
    const notification = document.createElement('div');
    notification.className = 'validation-error fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2';
    notification.innerHTML = `
        <i class="fas fa-exclamation-triangle"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

// Remove highlights when user starts typing
document.querySelectorAll('[data-required="true"]').forEach(field => {
    field.addEventListener('input', function() {
        if (this.value.trim()) {
            removeHighlight(this);
        }
    });
    
    field.addEventListener('change', function() {
        if (this.value.trim()) {
            removeHighlight(this);
        }
    });
});

// Initialize form state
document.addEventListener('DOMContentLoaded', function() {
    const calculationType = document.getElementById('calculationType');
    if (calculationType && calculationType.value) {
        calculationType.dispatchEvent(new Event('change'));
    }
});
</script>
@endpush
