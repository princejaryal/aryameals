@extends('layouts.app')

@section('title', $restaurant->name . ' - AryaMeals')

@section('content')
<!-- Restaurant Hero Section -->
<section class="restaurant-hero" style="background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.4)), url('{{ $restaurant->image_url }}') center/cover;">
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-6">
                <div class="restaurant-hero-content">
                    <div class="restaurant-badge mb-3">
                        <h1 class="restaurant-title">{{ $restaurant->name }}</h1>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star"></i> {{ number_format($restaurant->rating, 1) }}
                        </span>
                        <span class="badge bg-info ms-2">{{ ucfirst($restaurant->category) }}</span>
                    </div>
                    
                    <p class="restaurant-description">{{ $restaurant->description }}</p>
                    
                    <div class="restaurant-info">
                        <div class="info-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $restaurant->address }}, {{ $restaurant->city }}, {{ $restaurant->state }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-phone"></i>
                            <span>{{ $restaurant->phone }}</span>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-envelope"></i>
                            <span>{{ $restaurant->email }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Menu Section -->
<section class="menu-section">
    <div class="container-fluid">
        <div class="section-header text-center mb-5">
            <h2 class="section-title text-dark">Our Menu</h2>
            <p class="section-subtitle text-dark">Choose from our delicious selection</p>
        </div>
        
        <!-- Filter Section -->
        <div class="filter-section mb-4">
            <div class="row">
                <div class="col-lg-10 col-md-10 mb-3">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-search me-2"></i>Search Items
                        </label>
                        <input type="text" id="searchInput" class="filter-input" placeholder="Search menu items...">
                    </div>
                </div>
                <div class="col-lg-2 col-md-2 mb-3">
                    <div class="filter-group">
                        <label class="filter-label">
                            <i class="fas fa-sort me-2"></i>Sort By
                        </label>
                        <select id="sortFilter" class="filter-select">
                            <option value="name">Name</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="recommended">Recommended</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="menu-grid" id="menuItemsGrid">
        @forelse($restaurant->availableMenuItems as $item)
            <x-card-layout 
                :item="[
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'price' => $item->has_single_price ? $item->price : $item->full_plate_price,
                    'half_plate_price' => $item->half_plate_price,
                    'full_plate_price' => $item->full_plate_price,
                    'image' => $item->image,
                    'restaurant' => $restaurant->name,
                    'has_single_price' => $item->has_single_price ?? false
                ]" 
                :unit="$item->unit ?? null" 
            />
        @empty
            <div class="empty">
                <h3>No Menu Items Available</h3>
                <p>Check back later for delicious options!</p>
                <a href="{{ route('home') }}">Go Back</a>
            </div>
        @endforelse
        </div>
    </div>
</section>

<!-- Order Section -->
<section class="order-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2>Ready to order?</h2>
                <p>Place your order now and enjoy delicious food from {{ $restaurant->name }} delivered right to your doorstep.</p>
                <div class="order-features">
                    <div class="feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Fast delivery in Chamba</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Hygienic packaging</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-check-circle text-success"></i>
                        <span>Fresh ingredients</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <button class="arya-btn btn-lg" onclick="scrollToMenu()">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Order Now
                </button>
                <a href="tel:{{ $restaurant->phone }}" class="btn btn-lg ms-3" style="border-color:#ff8c2b;color:#ff8c2b">
                    <i class="fas fa-phone me-2"></i>
                    Call Restaurant
                </a>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/card-styles.css') }}">
<style>
/* RESTAURANT HERO */
.restaurant-hero {
    min-height: 100vh;
    display: flex;
    align-items: center;
    position: relative;
}

.restaurant-hero-content {
    color: white;
    z-index: 2;
}

.restaurant-title {
    font-size: 3rem;
    font-weight: 700;
    margin-bottom: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    font-family: "Merienda", sans-serif;
}

.restaurant-description {
    font-size: 1.2rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.restaurant-info {
    margin-bottom: 2rem;
}

.info-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.info-item i {
    width: 30px;
    margin-right: 15px;
    color: var(--primary-orange);
}


/* MENU SECTION */
.menu-section {
    background: #f5f6fa;
    padding: 60px 0;
}

.section-subtitle {
    color: var(--gray-600);
    font-size: 1.1rem;
}

/* FILTER SECTION */
.filter-section {
    border-radius: 16px;
    margin-bottom: 30px;
}

.filter-group {
    margin-bottom: 0;
}

.filter-label {
    display: flex;
    align-items: center;
    font-weight: 600;
    color: var(--gray-700);
    margin-bottom: 8px;
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-input {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.95rem;
    transition: all 0.3s ease;
    background: #f9fafb;
}

.filter-input:focus {
    outline: none;
    border-color: var(--primary-orange);
    background: white;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
}

.filter-select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 10px;
    font-size: 0.95rem;
    background: #f9fafb;
    cursor: pointer;
    transition: all 0.3s ease;
}

.filter-select:focus {
    outline: none;
    border-color: var(--primary-orange);
    background: white;
    box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1);
}

.form-check {
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-check-input {
    width: 18px;
    height: 18px;
    accent-color: var(--primary-orange);
    cursor: pointer;
}

.form-check-label {
    font-size: 0.9rem;
    color: var(--gray-700);
    cursor: pointer;
    margin: 0;
}

/* BUTTON STYLES */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 12px 24px;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    cursor: pointer;
    border: 2px solid transparent;
}

/* GRID */
.menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

/* EMPTY */
.empty {
    text-align: center;
    grid-column: span 4;
    padding: 40px;
    background: white;
    border-radius: 16px;
}

.empty h3 {
    color: #2c3e50;
    margin-bottom: 15px;
}

.empty a {
    background: #667eea;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    display: inline-block;
    text-decoration: none;
}

.order-features {
    margin-top: 2rem;
}

.feature-item {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
    font-size: 1.1rem;
}

.feature-item i {
    margin-right: 15px;
    font-size: 1.2rem;
}

/* RESPONSIVE */
@media(max-width:1200px){
    .menu-grid { grid-template-columns: repeat(3,1fr); }
}

@media(max-width:768px){
    .menu-grid { grid-template-columns: repeat(2,1fr); }
    .restaurant-title { font-size: 2rem; }
}

@media(max-width:576px){
    .menu-grid { grid-template-columns: repeat(2,1fr); }
}

@media(max-width:400px){
    .menu-grid { grid-template-columns: 1fr; }
    .restaurant-title { font-size: 1.8rem; }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/card-scripts.js') }}"></script>
<script>
// Pass restaurant menu data to card scripts
window.categoryItems = [
    @foreach($restaurant->availableMenuItems as $item)
    {
        id: {{ $item->id }},
        price: {{ $item->has_single_price ? $item->price : $item->full_plate_price }},
        half_plate_price: {{ $item->half_plate_price }},
        full_plate_price: {{ $item->full_plate_price }},
        name: '{{ $item->name }}',
        unit: '{{ $item->unit ?? '' }}',
        category: '{{ $item->category }}',
        spice_level: '{{ $item->spice_level ?? 'mild' }}',
        description: '{{ $item->description }}',
        image: '{{ $item->image }}',
        is_recommended: {{ $item->is_recommended ? 'true' : 'false' }},
        has_single_price: {{ $item->has_single_price ? 'true' : 'false' }}
    }@unless($loop->last),@endunless
    @endforeach
];

// Filter functionality
let allItems = [...window.categoryItems];

function filterMenuItems() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const sortFilter = document.getElementById('sortFilter').value;
    
    let filteredItems = allItems.filter(item => {
        // Search filter
        const matchesSearch = item.name.toLowerCase().includes(searchTerm) || 
                           item.description.toLowerCase().includes(searchTerm);
        
        return matchesSearch;
    });
    
    // Sort items
    switch(sortFilter) {
        case 'price-low':
            filteredItems.sort((a, b) => a.price - b.price);
            break;
        case 'price-high':
            filteredItems.sort((a, b) => b.price - a.price);
            break;
        case 'recommended':
            filteredItems.sort((a, b) => b.is_recommended - a.is_recommended);
            break;
        default:
            filteredItems.sort((a, b) => a.name.localeCompare(b.name));
    }
    
    // Update menu grid
    updateMenuGrid(filteredItems);
}

function updateMenuGrid(items) {
    const grid = document.getElementById('menuItemsGrid');
    
    if (items.length === 0) {
        grid.innerHTML = `
            <div class="empty">
                <h3>No Items Found</h3>
                <p>Try adjusting your filters</p>
                <a href="{{ route('home') }}">Go Back</a>
            </div>
        `;
        return;
    }
    
    grid.innerHTML = items.map(item => `
        <div class="default-card">
            <div class="card-img">
                <img src="/storage/menu-items/${item.image}" alt="${item.name}">
            </div>
            <div class="card-content">
                <div class="card-header">
                    <img src="/storage/menu-items/${item.image}" alt="${item.name}" class="card-img-small">
                    <div class="card-info">
                        <h3 class="card-title">${item.name}</h3>
                        <p class="card-restaurant">
                            <i class="fas fa-store"></i> {{ $restaurant->name }}
                        </p>
                    </div>
                    <div class="card-price">
                        ₹<span id="price-${item.id}">${item.price.toFixed(2)}</span>
                    </div>
                </div>
                <p class="card-desc">${item.description}</p>
                ${item.unit ? `
                <div class="card-unit" id="unit-${item.id}">
                    <i class="fas fa-weight"></i> ${item.unit}
                </div>` : ''}
                <div class="card-delivery">
                    <i class="fas fa-truck"></i> Delivery: ₹49
                </div>
                <div class="card-footer">
                    <div class="card-price-section">
                        <div class="card-quantity">
                            <button class="qty-btn qty-minus" onclick="updateQuantity(${item.id}, -1, ${item.price}, '${item.unit || ''}')">−</button>
                            <input type="number" id="qty-${item.id}" class="qty-input" value="1" min="1" max="99" onchange="updateQuantity(${item.id}, 0, ${item.price}, '${item.unit || ''}')">
                            <button class="qty-btn qty-plus" onclick="updateQuantity(${item.id}, 1, ${item.price}, '${item.unit || ''}')">+</button>
                        </div>
                    </div>
                    <div class="card-action">
                        <button class="card-btn" onclick="addToCart(${item.id}, '${item.name}', document.getElementById('qty-${item.id}').value, ${item.price}, '{{ $restaurant->name }}', '${item.image}', '${item.unit || ''}')">
                            + Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('searchInput').addEventListener('input', filterMenuItems);
    document.getElementById('sortFilter').addEventListener('change', filterMenuItems);
});

// Proper notification function
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
    
    // Add to page
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

// Cart modal functions
function openCart() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('cartOverlay');
    
    if (modal && overlay) {
        modal.classList.add('show');
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeCart() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('cartOverlay');
    
    if (modal && overlay) {
        modal.classList.remove('show');
        overlay.classList.remove('show');
        document.body.style.overflow = 'auto';
    }
}

// Add cart icon click handler
document.addEventListener('DOMContentLoaded', function() {
    // Add cart open functionality to cart icon
    const cartIcon = document.querySelector('.cart');
    if (cartIcon) {
        cartIcon.addEventListener('click', openCart);
    }
    
    // Close cart on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeCart();
        }
    });
});
</script>
@endpush

