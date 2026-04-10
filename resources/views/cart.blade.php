@extends('layouts.app')

@section('title', 'Shopping Cart - AryaMeals')

@section('content')
<!-- Hero Section -->
<section class="hero-section bg-gradient-to-r from-orange-500 to-pink-500 py-8">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="text-white">
                    <h1 class="display-4 mb-3 fw-bold" style="font-family: 'Merienda', sans-serif">
                        <i class="fas fa-shopping-cart me-3"></i>Shopping Cart
                    </h1>
                    <p class="lead mb-0">Review your delicious selections before checkout</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cart Content -->
<section class="py-5 bg-light">
    <div class="container-fluid">
        <div class="row">
            <!-- Cart Items Section -->
            <div class="col-lg-8">
                @if($cartItems->count() > 0)
                    <!-- Cart Items Card -->
                    <div class="card border-0 shadow-lg mb-4">
                        <div class="card-header bg-white border-0 pt-4 pb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0 fw-bold">
                                    <i class="fas fa-shopping-bag text-orange-500 me-2"></i>
                                    Your Items
                                </h4>
                                <button class="btn btn-sm" onclick="clearCart()" style="border: 1px solid orange;color:orange">
                                    <i class="fas fa-trash-alt me-2"></i>Clear Cart
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="cart-items">
                                @foreach($cartItems as $item)
                                <div class="cart-item mb-4 p-3 rounded-lg border" data-id="{{ $item->id }}">
                                    <div class="row align-items-center">
                                        <!-- Item Image -->
                                        <div class="col-md-2">
                                            <div class="item-image">
                                                <img src="{{ asset('storage/menu-items/' . $item->menuItem->image) }}" 
                                                     alt="{{ $item->menuItem->name }}" 
                                                     class="rounded shadow-sm" 
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                            </div>
                                        </div>
                                        
                                        <!-- Item Details -->
                                        <div class="col-md-6">
                                            <div class="item-details">
                                                <h5 class="fw-bold mb-2 text-dark">{{ $item->menuItem->name }}</h5>
                                                <div class="d-flex align-items-center mb-2">
                                                    <span class="badge bg-warning text-dark me-2">
                                                        <i class="fas fa-store me-1"></i>{{ $item->menuItem->restaurant->name }}
                                                    </span>
                                                    <span class="badge bg-secondary text-white">
                                                        {{ $item->portion_display }}
                                                    </span>
                                                </div>
                                                <div class="text-muted small mb-2">
                                                    <span class="fw-bold text-danger">{{ $item->formatted_price }}</span>
                                                    per item
                                                </div>
                                                
                                                <!-- Quantity Controls -->
                                                <div class="quantity-controls mb-3">
                                                    <div class="input-group" style="width: 140px;">
                                                        <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity({{ $item->id }}, -1)">
                                                            <i class="fas fa-minus"></i>
                                                        </button>
                                                        <input type="number" class="form-control text-center fw-bold" value="{{ $item->quantity }}" 
                                                               min="1" max="99" id="quantity-{{ $item->id }}" readonly>
                                                        <button class="btn btn-outline-secondary btn-sm" onclick="updateQuantity({{ $item->id }}, 1)">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                
                                                @if($item->special_instructions)
                                                <div class="alert alert-info py-2 px-3 mb-2">
                                                    <small class="mb-0">
                                                        <i class="fas fa-sticky-note me-1"></i>
                                                        {{ $item->special_instructions }}
                                                    </small>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Item Total & Remove -->
                                        <div class="col-md-4">
                                            <div class="d-flex flex-column align-items-end justify-content-center h-100">
                                                <div class="item-actions text-end">
                                                    <div class="h5 fw-bold text-danger mb-2" id="total-{{ $item->id }}">
                                                        {{ $item->formatted_total_price }}
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="removeItem({{ $item->id }})">
                                                        <i class="fas fa-trash me-1"></i>Remove
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    
                @else
                    <!-- Empty Cart -->
                    <div class="card border-0 shadow-lg">
                        <div class="card-body text-center py-5">
                            <div class="empty-cart-icon mb-4">
                                <i class="fas fa-shopping-cart fa-5x text-muted"></i>
                            </div>
                            <h3 class="text-muted mb-3">Your cart is empty</h3>
                            <p class="text-muted mb-4">Looks like you haven't added any delicious items yet!</p>
                            <a href="{{ route('home') }}" class="btn btn-warning btn-lg">
                                <i class="fas fa-utensils me-2"></i>
                                Start Ordering
                            </a>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Order Summary Section -->
            <div class="col-lg-4">
                <div class="card border-0" style="position: sticky; top: 20px;">
                    <div class="card-header text-white border-0" style="background-color: #ff8c2b;">
                        <h5 class="mb-0 fw-bold">
                            <i class="fas fa-receipt me-2"></i>
                            Order Summary
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        @if($cartItems->count() > 0)
                            <!-- Price Breakdown -->
                            <div class="price-breakdown mb-4">
                                <!-- Subtotal -->
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="text-muted">
                                        <i class="fas fa-shopping-bag me-2"></i>
                                        Subtotal ({{ $itemCount }} items)
                                    </span>
                                    <span class="fw-bold" id="subtotal">₹{{ number_format($subtotal, 0) }}</span>
                                </div>
                                
                                <!-- Dynamic Platform Fees -->
                                @if(isset($feeData['fees']) && count($feeData['fees']) > 0)
                                    @foreach($feeData['fees'] as $fee)
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <span class="text-muted">
                                                <i class="fas fa-percentage me-2"></i>
                                                {{ $fee['display_label'] }}
                                            </span>
                                            <span class="fw-bold">₹{{ number_format($fee['amount'], 0) }}</span>
                                        </div>
                                    @endforeach
                                @endif
                                
                                <hr class="my-3">
                                
                                <!-- Total -->
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <h5 class="mb-0 text-dark">Total Amount</h5>
                                    <h4 class="mb-0 text-danger fw-bold" id="grandTotal">
                                        {{ isset($feeData['grand_total']) ? $feeData['grand_total'] : number_format($subtotal + 49, 0) }}
                                    </h4>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="action-buttons">
                                <a href="{{ route('checkout.index') }}" class="btn btn-lg btn-warning w-100">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Proceed to Checkout
                                </a>
                            </div>
                        @else
                            <!-- Empty Summary -->
                            <div class="text-center py-4">
                                <i class="fas fa-receipt fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Add items to cart to see order summary</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
/* Custom gradient backgrounds */
.bg-gradient-to-r {
    background: linear-gradient(135deg, #ff8c2b, #ff6b35) !important;
}

/* Custom orange button */
.btn-warning {
    background: linear-gradient(135deg, #ff8c2b, #ff6b35) !important;
    border: none;
    color: white !important;
    transition: all 0.3s ease;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #ff6b35, #ff5722) !important;
    color: white !important;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 140, 43, 0.3);
}

/* Quantity controls styling */
.quantity-controls .input-group .btn {
    border-color: #ff8c2b;
    color: #ff8c2b;
}

.quantity-controls .input-group .btn:hover {
    background-color: #ff8c2b;
    color: white;
}

/* Card hover effects */
.card {
    transition: all 0.3s ease;
}

/* Sticky positioning */
.sticky-top {
    position: sticky;
    top: 20px;
    z-index: 10;
}

@media (max-width: 768px) {
    .sticky-top {
        position: relative;
        top: 0;
    }
    .item-image img{
        width:100% !important;
    }
   
}

@media (max-width: 768px) {
    .cart-item .row {
        flex-direction: column;
    }

    .cart-item .col-md-4 {
        width: 100%;
        margin-top: -50px;
    }
    .h5{
        margin:-155px -89px 0 0px ;
    }
    .item-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .item-actions .btn {
        margin-left: auto; /* pushes button to right */
    }
}

/* Badge improvements */
.badge {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

/* Alert improvements */
.alert {
    border: none;
    border-radius: 0.5rem;
}

/* Hide scrollbar for shopping cart on wide screens */
@media (min-width: 992px) {
    .cart-items {
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none; /* IE and Edge */
    }
    
    .cart-items::-webkit-scrollbar {
        display: none; /* Chrome, Safari and Opera */
    }
    
    .cart-items::-webkit-scrollbar-track {
        display: none;
    }
    
    .cart-items::-webkit-scrollbar-thumb {
        display: none;
    }
}
</style>
@endpush

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Update cart item quantity
function updateQuantity(itemId, change) {
    const input = document.getElementById(`quantity-${itemId}`);
    let newQuantity = parseInt(input.value) + change;
    
    if (newQuantity >= 1 && newQuantity <= 99) {
        // Show loading state
        const btn = event.target.closest('button');
        const originalContent = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        btn.disabled = true;
        
        fetch(`/cart/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                input.value = newQuantity;
                document.getElementById(`total-${itemId}`).textContent = data.total_price;
                updateOrderSummary(data.cart_total, data.cart_count, data.fees, data.grand_total);
                
                // Animate the total price
                const totalElement = document.getElementById(`total-${itemId}`);
                totalElement.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    totalElement.style.transform = 'scale(1)';
                }, 200);
            } else {
                showNotification('Error updating cart', 'error');
            }
        })
        .catch(error => {
            showNotification('Error updating cart', 'error');
        })
        .finally(() => {
            // Restore button state
            btn.innerHTML = originalContent;
            btn.disabled = false;
        });
    }
}

// Remove item from cart
function removeItem(itemId) {
    Swal.fire({
        title: 'Remove Item?',
        text: "Are you sure you want to remove this item from your cart?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff8c2b',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, remove it!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            const itemElement = document.querySelector(`[data-id="${itemId}"]`);
            
            // Add removing animation
            itemElement.classList.add('removing');
            
            fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    setTimeout(() => {
                        itemElement.remove();
                        updateOrderSummary(data.cart_total, data.cart_count, data.fees, data.grand_total);
                        showNotification(data.message, 'success');
                        
                        // Reload if cart is empty
                        if (data.cart_count == 0) {
                            setTimeout(() => location.reload(), 1000);
                        }
                    }, 300);
                } else {
                    itemElement.classList.remove('removing');
                    showNotification('Error removing item', 'error');
                }
            })
            .catch(error => {
                itemElement.classList.remove('removing');
                showNotification('Error removing item', 'error');
            });
        }
    });
}

// Clear entire cart
function clearCart() {
    Swal.fire({
        title: 'Clear Cart?',
        text: "Are you sure you want to clear your entire cart? This action cannot be undone.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, clear cart!',
        cancelButtonText: 'Cancel'
    }).then((result) => {
        if (result.isConfirmed) {
            // Add loading state to clear button
            const clearBtn = document.querySelector('button[onclick="clearCart()"]');
            const originalContent = clearBtn.innerHTML;
            clearBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Clearing...';
            clearBtn.disabled = true;
            
            fetch('/cart', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification(data.message, 'success');
                    
                    // Animate all cart items removal
                    const items = document.querySelectorAll('.cart-item');
                    items.forEach((item, index) => {
                        setTimeout(() => {
                            item.classList.add('removing');
                        }, index * 100);
                    });
                    
                    setTimeout(() => location.reload(), items.length * 100 + 500);
                } else {
                    showNotification('Error clearing cart', 'error');
                }
            })
            .catch(error => {
                showNotification('Error clearing cart', 'error');
            })
            .finally(() => {
                // Restore button state
                clearBtn.innerHTML = originalContent;
                clearBtn.disabled = false;
            });
        }
    });
}

// Update order summary
function updateOrderSummary(cartTotal, cartCount, fees = [], grandTotal = null) {
    const subtotal = parseFloat(cartTotal.replace('₹', '').replace(',', ''));
    const deliveryFee = 49;
    
    // Animate the update
    const subtotalElement = document.getElementById('subtotal');
    const grandTotalElement = document.getElementById('grandTotal');
    const itemCountElement = document.getElementById('cartItemCount');
    
    subtotalElement.style.transform = 'scale(1.05)';
    grandTotalElement.style.transform = 'scale(1.05)';
    
    setTimeout(() => {
        subtotalElement.textContent = cartTotal;
        
        // Update fees display
        updateFeesDisplay(fees);
        
        // Update grand total
        if (grandTotal) {
            grandTotalElement.textContent = grandTotal;
        } else {
            // Fallback calculation if no grand total provided
            const calculatedTotal = subtotal + deliveryFee;
            grandTotalElement.textContent = '₹' + calculatedTotal.toFixed(0);
        }
        
        itemCountElement.textContent = cartCount;
        
        subtotalElement.style.transform = 'scale(1)';
        grandTotalElement.style.transform = 'scale(1)';
    }, 200);
}

// Update fees display dynamically
function updateFeesDisplay(fees) {
    const priceBreakdown = document.querySelector('.price-breakdown');
    const deliveryFeeSection = priceBreakdown.querySelector('hr');
    
    // Remove existing fee sections (keep subtotal and delivery)
    const existingFees = priceBreakdown.querySelectorAll('.dynamic-fee');
    existingFees.forEach(fee => fee.remove());
    
    // Add dynamic fees if any
    if (fees && fees.length > 0) {
        fees.forEach((fee, index) => {
            const feeDiv = document.createElement('div');
            feeDiv.className = 'd-flex justify-content-between align-items-center mb-3 dynamic-fee';
            feeDiv.innerHTML = `
                <span class="text-gray-600">
                    <i class="fas fa-percentage me-2"></i>
                    ${fee.display_label}
                </span>
                <span class="fw-bold">₹${fee.amount}</span>
            `;
            
            // Insert before the delivery fee section
            priceBreakdown.insertBefore(feeDiv, deliveryFeeSection);
        });
    }
}


// Highlight empty fields
function highlightEmptyFields() {
    const fields = ['customerName', 'customerEmail', 'customerPhone', 'deliveryAddress'];
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            field.focus();
            
            // Remove highlight after user starts typing
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                }
            }, { once: true });
        }
    });
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed top-0 end-0 m-3 shadow-lg`;
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
        notification.style.transition = 'all 0.3s ease';
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Add input validation feedback
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('input[required], textarea[required]');
    inputs.forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value.trim()) {
                this.classList.remove('is-invalid');
                this.classList.add('is-valid');
            } else {
                this.classList.remove('is-valid');
                this.classList.add('is-invalid');
            }
        });
        
        // Remove validation on focus
        input.addEventListener('focus', function() {
            this.classList.remove('is-invalid', 'is-valid');
        });
    });
    
    // Add hover effects to cart items
    const cartItems = document.querySelectorAll('.cart-item');
    cartItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 4px 12px rgba(0,0,0,0.1)';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
});
</script>
@endpush
