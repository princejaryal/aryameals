@props([
    'item' => null,
    'unit' => null
])

@php
    $isRestaurant = isset($item['is_restaurant']) && $item['is_restaurant'];
    
    // Get delivery fee from platform_fees table
    $deliveryFee = \DB::table('platform_fees')
        ->where('fee_type', 'delivery')
        ->where('is_active', true)
        ->value('fee_amount') ?? 49;
    
    // Determine image path based on item type
    if ($isRestaurant) {
        $imagePath = $item['image'] ?? null;
        $imageUrl = $imagePath ? asset('storage/restaurants/' . $imagePath) : asset('images/default-restaurant.jpg');
        $route = route('restaurant.show', $item['id']);
    } else {
        $imagePath = $item['image'] ?? null;
        $imageUrl = $imagePath ? asset('storage/menu-items/' . $imagePath) : asset('images/default-food.jpg');
        $route = route('item.detail', $item['id']);
    }
@endphp

<div class="default-card" onclick="window.location.href='{{ $route }}'" style="cursor: pointer;">
    <div class="card-img">
        <img src="{{ $imageUrl }}" alt="{{ $item['name'] }}">
    </div>
    
    <div class="card-content">
        <div class="card-header">
            <img src="{{ $imageUrl }}" 
                alt="{{ $item['name'] }}" 
                class="card-img-small">

            <div class="card-info">
                <h3 class="card-title">{{ $item['name'] }}</h3>
                <p class="card-restaurant">
                    <i class="fas fa-store"></i> {{ $item['restaurant'] }}
                </p>
            </div>

            <!-- PRICE RIGHT SIDE (HIDE FOR RESTAURANTS) -->
            @if(!$isRestaurant)
            <div class="card-price">
                ₹<span id="price-{{ $item['id'] }}">
                    {{ number_format($item['price'], 2) }}
                </span>
            </div>
            @else
            <!-- RATING FOR RESTAURANTS -->
            @if(isset($item['rating']) && $item['rating'])
            <div class="card-rating">
                <i class="fas fa-star"></i> {{ number_format($item['rating'], 1) }}
            </div>
            @endif
            @endif
        </div>
        
        <p class="card-desc">{{ $item['description'] }}</p>
        
        @if($unit && !$isRestaurant)
        <div class="card-unit" id="unit-{{ $item['id'] }}">
            <i class="fas fa-weight"></i> {{ $unit }}
        </div>
        @endif
        
        @if(!$isRestaurant)
        <div class="card-delivery">
            <i class="fas fa-truck"></i> Delivery: ₹{{ number_format($deliveryFee, 0) }}
        </div>
        @endif
        
        <div class="card-footer">
            @if(!$isRestaurant)
            <div class="card-price-section">
                <div class="card-quantity">
                    <button class="qty-btn qty-minus" onclick="event.stopPropagation(); updateQuantity({{ $item['id'] }}, -1, {{ $item['price'] }}, '{{ $unit ?? '' }}')">−</button>
                    <input type="number" id="qty-{{ $item['id'] }}" class="qty-input" value="1" min="1" max="99" onchange="event.stopPropagation(); updateQuantity({{ $item['id'] }}, 0, {{ $item['price'] }}, '{{ $unit ?? '' }}')">
                    <button class="qty-btn qty-plus" onclick="event.stopPropagation(); updateQuantity({{ $item['id'] }}, 1, {{ $item['price'] }}, '{{ $unit ?? '' }}')">+</button>
                </div>
            </div>
            <div class="card-action">
                <button class="card-btn" onclick="addToCartFromCard({{ $item['id'] }}, '{{ $item['name'] }}', document.getElementById('qty-{{ $item['id'] }}').value, {{ $item['price'] }}, '{{ $item['restaurant'] }}', '{{ $item['image'] }}', '{{ $unit ?? '' }}')">
                    + Add
                </button>
            </div>
            @else
            <div class="card-action w-100">
                <button class="card-btn w-100" onclick="event.stopPropagation(); window.location.href='{{ $route }}'">
                    View Menu
                </button>
            </div>
            @endif
        </div>
    </div>
</div>
