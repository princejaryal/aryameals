@extends('layouts.app')

@section('title', $menuItem->name . ' - ' . $menuItem->restaurant->name . ' - AryaMeals')

@section('content')
<!-- Breadcrumb Navigation -->
<nav aria-label="breadcrumb" class="bg-light py-3">
    <div class="container-fluid">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('restaurants') }}" class="text-decoration-none">Restaurants</a></li>
            <li class="breadcrumb-item"><a href="{{ route('restaurant.show', $menuItem->restaurant->id) }}" class="text-decoration-none">{{ $menuItem->restaurant->name }}</a></li>
            <li class="breadcrumb-item active">{{ $menuItem->name }}</li>
        </ol>
    </div>
</nav>

<!-- Product Detail Section -->
<section class="product-detail py-5">
    <div class="container-fluid">
        <div class="row">
            <!-- Product Images -->
            <div class="col-lg-6 mb-4">
                <div class="product-image-container">
                    <div class="main-image mb-3">
                        <img src="{{ $menuItem->image_url }}" alt="{{ $menuItem->name }}" class="img-fluid rounded shadow-sm" id="mainProductImage">
                    </div>
                    @if($menuItem->image)
                    <div class="image-thumbnails">
                        <img src="{{ $menuItem->image_url }}" alt="{{ $menuItem->name }}" class="thumbnail active" onclick="changeMainImage(this)">
                    </div>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <div class="product-info">
                    <!-- Restaurant Info -->
                    <div class="restaurant-info mb-3">
                        <a href="{{ route('restaurant.show', $menuItem->restaurant->id) }}" class="text-decoration-none">
                            <h6 class="text-muted mb-1">
                                <i class="fas fa-store me-1"></i> {{ $menuItem->restaurant->name }}
                            </h6>
                        </a>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star"></i> {{ number_format($menuItem->restaurant->rating, 1) }}
                            </span>
                            @if($menuItem->is_recommended)
                            <span class="badge bg-success">
                                <i class="fas fa-award"></i> Recommended
                            </span>
                            @endif
                        </div>
                    </div>

                    <!-- Product Title -->
                    <h1 class="product-title mb-3">{{ $menuItem->name }}</h1>

                    <!-- Product Badges -->
                    <div class="product-badges mb-3">
                        <span class="badge bg-info">{{ ucfirst($menuItem->category) }}</span>
                        @if($menuItem->spice_level)
                        <span class="badge bg-danger">{{ $menuItem->spice_level_badge }}</span>
                        @endif
                        @if($menuItem->is_available)
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle"></i> Available
                        </span>
                        @else
                        <span class="badge bg-secondary">
                            <i class="fas fa-times-circle"></i> Unavailable
                        </span>
                        @endif
                    </div>

                    <!-- Product Description -->
                    <div class="product-description mb-4">
                        <p class="text-muted">{{ $menuItem->description ?: 'No description available for this delicious item.' }}</p>
                    </div>

                    <!-- Additional Info -->
                    @if($menuItem->preparation_time || $menuItem->calories)
                    <div class="additional-info mb-4">
                        <div class="row g-3">
                            @if($menuItem->preparation_time)
                            <div class="col-6">
                                <div class="info-item d-flex align-items-center">
                                    <i class="fas fa-clock me-2" style="color: #ff6b35;"></i>
                                    <div>
                                        <small class="text-muted d-block">Prep Time</small>
                                        <span>{{ $menuItem->preparation_time }} mins</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($menuItem->calories)
                            <div class="col-6">
                                <div class="info-item d-flex align-items-center">
                                    <i class="fas fa-fire text-warning me-2"></i>
                                    <div>
                                        <small class="text-muted d-block">Calories</small>
                                        <span>{{ $menuItem->calories }} kcal</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Allergens Info -->
                    @if($menuItem->allergens)
                    <div class="allergens-info mb-4">
                        <div class="alert alert-warning d-flex align-items-center" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                <strong>Allergens:</strong> {{ $menuItem->allergens }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Order Section -->
                    @if($menuItem->is_available)
                    <div class="order-section mb-4">
                        <div class="row g-3 align-items-center">
                            @if($menuItem->has_single_price)
                            <div class="col-12">
                                <label class="form-label">Price</label>
                                <div class="price-display">
                                    <span class="price-value">₹{{ number_format($menuItem->price, 0) }}</span>
                                </div>
                            </div>
                            @else
                            <div class="col-6">
                                <label class="form-label">Portion Size</label>
                                <select class="form-select" id="portionSize">
                                    <option value="half" data-price="{{ $menuItem->half_plate_price }}">Half Plate - ₹{{ number_format($menuItem->half_plate_price, 0) }}</option>
                                    <option value="full" data-price="{{ $menuItem->full_plate_price }}" selected>Full Plate - ₹{{ number_format($menuItem->full_plate_price, 0) }}</option>
                                </select>
                            </div>
                            @endif
                            <div class="col-6">
                                <label class="form-label">Quantity</label>
                                <div class="input-group">
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(-1)">-</button>
                                    <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="99" onchange="updateQuantity(0)">
                                    <button class="btn btn-outline-secondary" onclick="updateQuantity(1)">+</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-grid gap-2 d-md-flex">
                                    <button class="btn btn-lg flex-fill" onclick="addToCart()" style="background-color: #ff8c2b;color:white">
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        This item is currently unavailable
                    </div>
                    @endif

                    <!-- Delivery Info -->
                    <div class="delivery-info">
                        <div class="d-flex justify-content-between align-items-center text-muted">
                            <span><i class="fas fa-truck me-1"></i> Delivery: ₹49</span>
                            <span><i class="fas fa-clock me-1"></i> 30-45 mins</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Product Details Tabs -->
<section class="product-tabs py-4 bg-light">
    <div class="container-fluid">
        <ul class="nav nav-tabs mb-4" id="productTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details" type="button" role="tab">
                    <i class="fas fa-info-circle me-2"></i>Details
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button" role="tab">
                    <i class="fas fa-star me-2"></i>Reviews
                </button>
            </li>
        </ul>
        
        <div class="tab-content" id="productTabsContent">
            <div class="tab-pane fade show active" id="details" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Product Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Category:</strong></td>
                                <td>{{ ucfirst($menuItem->category) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Spice Level:</strong></td>
                                <td>{{ $menuItem->spice_level_badge ?? 'Not specified' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Preparation Time:</strong></td>
                                <td>{{ $menuItem->preparation_time ?? 'Not specified' }} minutes</td>
                            </tr>
                            @if($menuItem->allergens)
                            <tr>
                                <td><strong>Allergens:</strong></td>
                                <td>{{ $menuItem->allergens }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Restaurant Information</h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Name:</strong></td>
                                <td>{{ $menuItem->restaurant->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Rating:</strong></td>
                                <td>
                                    <i class="fas fa-star text-warning"></i> {{ number_format($menuItem->restaurant->rating, 1) }}
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Category:</strong></td>
                                <td>{{ ucfirst($menuItem->restaurant->category) }}</td>
                            </tr>
                            <tr>
                                <td><strong>Address:</strong></td>
                                <td>{{ $menuItem->restaurant->address }}, {{ $menuItem->restaurant->city }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="tab-pane fade" id="reviews" role="tabpanel">
                <!-- Review Form -->
                <div class="review-form-section mb-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Write a Review</h5>
                        </div>
                        <div class="card-body">
                            <form id="reviewForm">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Your Name</label>
                                        <input type="text" class="form-control" name="customer_name" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Your Email</label>
                                        <input type="email" class="form-control" name="customer_email" required>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Rating</label>
                                        <div class="rating-input">
                                            @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" id="star{{ $i }}" name="rating" value="{{ $i }}" required>
                                            <label for="star{{ $i }}" class="star-label">
                                                <i class="far fa-star"></i>
                                            </label>
                                            @endfor
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label">Your Review</label>
                                        <textarea class="form-control" name="review_text" rows="4" placeholder="Tell us about your experience..." required></textarea>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-paper-plane me-2"></i>Submit Review
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Existing Reviews -->
                <div class="existing-reviews">
                    <h5 class="mb-4">Customer Reviews</h5>
                    @if($menuItem->approvedReviews->count() > 0)
                        <div class="reviews-list">
                            @foreach($menuItem->approvedReviews as $review)
                            <div class="review-card mb-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="mb-1">{{ $review->customer_name }}</h6>
                                        <div class="rating-stars">
                                            {!! $review->rating_stars !!}
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <p class="mb-0">{{ $review->review_text }}</p>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-star text-muted fa-3x mb-3"></i>
                            <p class="text-muted">No reviews yet. Be the first to review this item!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Items Section -->
@if($relatedItems->count() > 0)
<section class="related-items py-5">
    <div class="container-fluid">
        <div class="section-header text-center mb-5">
            <h2 class="section-title text-dark">More from {{ $menuItem->restaurant->name }}</h2>
            <p class="section-subtitle text-muted">Explore other delicious items from this restaurant</p>
        </div>
        
        <div class="menu-grid">
            @foreach($relatedItems as $item)
                <x-card-layout 
                    :item="[
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'price' => $item->price ?? $item->full_plate_price,
                        'half_plate_price' => $item->half_plate_price,
                        'full_plate_price' => $item->full_plate_price,
                        'image' => $item->image,
                        'restaurant' => $item->restaurant->name,
                        'has_single_price' => $item->price !== null
                    ]" 
                    :unit="$item->unit ?? null" 
                />
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Similar Items Section -->
@if($similarItems->count() > 0)
<section class="similar-items py-5 bg-light">
    <div class="container-fluid">
        <div class="section-header text-center mb-5">
            <h2 class="section-title text-dark">Similar {{ ucfirst($menuItem->category) }} Items</h2>
            <p class="section-subtitle text-muted">You might also like these items</p>
        </div>
        
        <div class="menu-grid">
            @foreach($similarItems as $item)
                <x-card-layout 
                    :item="[
                        'id' => $item->id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'price' => $item->price ?? $item->full_plate_price,
                        'half_plate_price' => $item->half_plate_price,
                        'full_plate_price' => $item->full_plate_price,
                        'image' => $item->image,
                        'restaurant' => $item->restaurant->name,
                        'has_single_price' => $item->price !== null
                    ]" 
                    :unit="$item->unit ?? null" 
                />
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/card-styles.css') }}">
<style>
/* PRODUCT DETAIL STYLES */
.product-detail {
    background: white;
}

/* GRID STYLES */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

.product-image-container {
    position: sticky;
    top: 100px;
}

.main-image img {
    width: 100%;
    height: 400px;
    object-fit: cover;
    border-radius: 12px;
}

.image-thumbnails {
    display: flex;
    gap: 10px;
}

.thumbnail {
    width: 80px;
    height: 80px;
    object-fit: cover;
    border-radius: 8px;
    cursor: pointer;
    border: 2px solid transparent;
    transition: all 0.3s ease;
}

.thumbnail:hover,
.thumbnail.active {
    border-color: var(--primary-orange);
    transform: scale(1.05);
}

.product-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.product-badges .badge {
    margin-right: 5px;
    font-size: 0.85rem;
}

.info-item {
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.info-item:hover {
    background: #e9ecef;
}

/* TAB STYLES */
.nav-tabs .nav-link {
    color: #6c757d;
    border: none;
    border-bottom: 3px solid transparent;
    background: none;
    font-weight: 600;
    padding: 12px 24px;
}

.nav-tabs .nav-link.active {
    color: var(--primary-orange);
    border-bottom-color: var(--primary-orange);
    background: none;
}

.nav-tabs .nav-link:hover {
    color: var(--primary-orange);
    border-bottom-color: rgba(255, 140, 43, 0.3);
}

.tab-content {
    background: white;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
}

/* REVIEW FORM STYLES */
.rating-input {
    display: flex;
    flex-direction: row-reverse;
    gap: 5px;
    font-size: 24px;
}

.rating-input input[type="radio"] {
    display: none;
}

.star-label {
    cursor: pointer;
    color: #ddd;
    transition: color 0.2s;
}

.star-label:hover,
.star-label:hover ~ .star-label {
    color: #ffc107;
}

.rating-input input[type="radio"]:checked ~ .star-label {
    color: #ffc107;
}

.review-card {
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
    border-left: 4px solid #ff8c2b;
}

.rating-stars {
    color: #ffc107;
    font-size: 14px;
}

/* RESPONSIVE */
@media(max-width:768px) {
    .product-title {
        font-size: 1.8rem;
    }
    
    .main-image img {
        height: 250px;
    }
    
    .product-image-container {
        position: static;
    }
    
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width:576px) {
    .product-title {
        font-size: 1.5rem;
    }
    
    .tab-content {
        padding: 20px;
    }
    
    .menu-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media(max-width:400px) {
    .menu-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Cart functionality
let currentPrice = {{ $menuItem->has_single_price ? $menuItem->price : $menuItem->full_plate_price }};
let currentQuantity = 1;
let hasSinglePrice = {{ $menuItem->has_single_price ? 'true' : 'false' }};

function updateQuantity(change) {
    const quantityInput = document.getElementById('quantity');
    let newQuantity = parseInt(quantityInput.value) + change;
    
    if (newQuantity >= 1 && newQuantity <= 99) {
        quantityInput.value = newQuantity;
        currentQuantity = newQuantity;
        updateTotalPrice();
    }
}

function updateTotalPrice() {
    const totalPrice = currentPrice * currentQuantity;
    document.getElementById('totalPrice').textContent = totalPrice.toFixed(0);
}

// Portion size change handler (only for dual price items)
if (!hasSinglePrice) {
    document.getElementById('portionSize').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        currentPrice = parseFloat(selectedOption.dataset.price);
        updateTotalPrice();
    });
}

// Add to cart function
function addToCart() {
    const menuItemId = {{ $menuItem->id }};
    const quantity = document.getElementById('quantity').value;
    const portionSize = hasSinglePrice ? 'full' : document.getElementById('portionSize').value;
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            menu_item_id: menuItemId,
            quantity: parseInt(quantity),
            portion_size: portionSize
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount(data.cart_count);
            // Reset quantity to 1
            document.getElementById('quantity').value = 1;
        } else {
            showNotification(data.message || 'Error adding to cart', 'error');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showNotification('Network error. Please try again.', 'error');
    });
}

function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.alert');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create new notification
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

// Update cart count in header
function updateCartCount(count) {
    const cartCountElement = document.getElementById('cartCount');
    if (cartCountElement) {
        cartCountElement.textContent = count;
        // Show animation
        cartCountElement.classList.add('animate-bounce');
        setTimeout(() => {
            cartCountElement.classList.remove('animate-bounce');
        }, 500);
    }
}

// Image gallery
function changeMainImage(thumbnail) {
    const mainImage = document.getElementById('mainProductImage');
    mainImage.src = thumbnail.src;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach(thumb => {
        thumb.classList.remove('active');
    });
    thumbnail.classList.add('active');
}

// Review form submission
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const menuItemId = {{ $menuItem->id }};
    
    fetch(`/item/${menuItemId}/review`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message);
            this.reset();
            // Reset rating stars
            document.querySelectorAll('.star-label').forEach(label => {
                label.style.color = '#ddd';
            });
        } else {
            showNotification('Error submitting review. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error submitting review. Please try again.', 'error');
    });
});
</script>
@endpush
