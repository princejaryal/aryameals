@extends('layouts.app')

@section('title', 'Search Results - AryaMeals')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/card-styles.css') }}">
<style>
.menu-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 25px;
}

@media (max-width: 1200px) {
    .menu-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 768px) {
    .menu-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
}

@media (max-width: 576px) {
    .menu-grid {
        grid-template-columns: 1fr;
        gap: 15px;
    }
}
</style>
@endpush

@section('content')
<div class="container-fluid py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #ff6b35;">
                <i class="fas fa-search me-2"></i>Search Results
            </h2>
            <p class="text-muted mb-4">Results for "<strong>{{ $query }}</strong>"</p>

            @if($restaurants->count() > 0 || $menuItems->count() > 0)
                <!-- Restaurants Section -->
                @if($restaurants->count() > 0)
                    <h4 class="fw-bold mb-3">Restaurants</h4>
                    <div class="menu-grid mb-5">
                        @foreach($restaurants as $restaurant)
                            @php
                                $restaurantItem = [
                                    'id' => $restaurant->id,
                                    'name' => $restaurant->name,
                                    'description' => $restaurant->city . ' - ' . ucfirst($restaurant->category),
                                    'price' => 0,
                                    'image' => $restaurant->image,
                                    'restaurant' => $restaurant->name,
                                    'rating' => $restaurant->rating,
                                    'is_restaurant' => true
                                ];
                            @endphp
                            <x-card-layout :item="$restaurantItem" />
                        @endforeach
                    </div>
                @endif

                <!-- Food Items Section -->
                @if($menuItems->count() > 0)
                    <h4 class="fw-bold mb-3">Food Items</h4>
                    <div class="menu-grid">
                        @foreach($menuItems as $item)
                            @php
                                $foodItem = [
                                    'id' => $item->id,
                                    'name' => $item->name,
                                    'description' => Str::limit($item->description, 50),
                                    'price' => $item->price ?? $item->full_plate_price,
                                    'image' => $item->image,
                                    'restaurant' => $item->restaurant->name,
                                    'rating' => null,
                                    'category' => $item->category,
                                    'half_plate_price' => $item->half_plate_price,
                                    'full_plate_price' => $item->full_plate_price,
                                    'has_single_price' => $item->price !== null
                                ];
                            @endphp
                            <x-card-layout :item="$foodItem" :unit="$item->unit ?? null" />
                        @endforeach
                    </div>
                @endif
            @else
                <div class="text-center py-5">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No results found</h4>
                    <p class="text-muted">Try different keywords</p>
                    <a href="{{ route('home') }}" class="btn btn-warning">
                        <i class="fas fa-home me-1"></i> Go Home
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/card-scripts.js') }}"></script>
<script>
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
