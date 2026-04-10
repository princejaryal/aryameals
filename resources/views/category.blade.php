@extends('layouts.app')

@section('title', $categoryInfo['name'] . ' - AryaMeals')

@section('content')

<!-- HERO -->
<section class="category-hero">
    <div class="container text-center">
        <h1 class="hero-title">{{ $categoryInfo['name'] }}</h1>
        <p class="hero-subtitle">{{ $categoryInfo['description'] }}</p>
        <div class="hero-nav">
            <a href="{{ route('home') }}" class="hero-link">Home</a>
            <span class="hero-separator">/</span>
            <span class="hero-current">{{ $categoryInfo['name'] }}</span>
        </div>
    </div>
</section>

<!-- MENU -->
<section class="menu-section">
    <div class="container-fluid">
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
        @forelse($menuItems as $item)
            <x-card-layout :item="$item" :unit="$item['unit'] ?? null" />
        @empty
            <div class="empty">
                <h3>No Items Found</h3>
                <p>Try adjusting your filters</p>
                <a href="{{ route('home') }}">Go Back</a>
            </div>
        @endforelse
        </div>
    </div>
</section>


@endsection


@push('styles')
<link rel="stylesheet" href="{{ asset('css/card-styles.css') }}">
<style>
/* HERO */
.category-hero {
    background: linear-gradient(135deg,#ff8c2b,#ff6b35);
    padding: 134px 0 80px 0;
    color: white;
}

.hero-title {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 15px;
}

.hero-subtitle {
    opacity: 0.9;
    margin-bottom: 25px;
    font-size: 1.1rem;
}

.hero-nav {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    font-size: 1rem;
}

.hero-link {
    color: white;
    text-decoration: none;
    opacity: 0.8;
    transition: opacity 0.3s;
}

.hero-link:hover {
    opacity: 1;
}

.hero-separator {
    opacity: 0.6;
}

.hero-current {
    opacity: 1;
    font-weight: 600;
}

/* SECTION */
.menu-section {
    background: #f5f6fa;
    padding: 60px 0;
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
}

/* RESPONSIVE */
@media(max-width:1200px){
    .menu-grid { grid-template-columns: repeat(3,1fr); }
}

@media(max-width:768px){
    .menu-grid { grid-template-columns: repeat(3,1fr); }
    .category-hero { padding: 134px 0 80px 0; }
    .hero-title { font-size: 2.5rem; }
}

@media(max-width:576px){
    .menu-grid { grid-template-columns: repeat(2,1fr); }
    .category-hero { padding: 134px 0 80px 0; }
    .hero-title { font-size: 2rem; }
    .hero-subtitle { font-size: 1rem; }
}

@media(max-width:400px){
    .menu-grid { grid-template-columns: 1fr; }
    .hero-title { font-size: 1.8rem; }
}
</style>
@push('scripts')
<script src="{{ asset('js/card-scripts.js') }}"></script>
<script>
// Pass category data to card scripts
window.categoryItems = [
    @foreach($menuItems as $item)
    {
        id: {{ $item['id'] }},
        price: {{ $item['price'] }},
        name: '{{ $item['name'] }}',
        unit: '{{ $item['unit'] ?? '' }}',
        category: '{{ $item['category'] ?? 'food' }}',
        description: '{{ $item['description'] }}',
        image: '{{ $item['image'] }}'
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
                            <i class="fas fa-store"></i> {{ $categoryInfo['name'] }}
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
                        <button class="card-btn" onclick="addToCart(${item.id}, '${item.name}', document.getElementById('qty-${item.id}').value, ${item.price}, '{{ $categoryInfo['name'] }}', '${item.image}', '${item.unit || ''}')">
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