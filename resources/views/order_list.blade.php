@extends('layouts.app')

@section('title', 'My Orders - AryaMeals')

@section('content')
<div class="container py-5 mt-5">
    <div class="row">
        <div class="col-12">
            <h2 class="fw-bold mb-4" style="color: #ff6b35;">
                <i class="fas fa-clipboard-list me-2"></i>My Orders
            </h2>

            @if($orders->count() > 0)
                @foreach($orders as $order)
                    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
                        <div class="row g-0">
                            <!-- Left Side: Image -->
                            <div class="col-md-3 d-flex">
                                @php
                                    $firstItem = $order->orderItems->first();
                                    $imageUrl = $firstItem && $firstItem->menuItem ? $firstItem->menuItem->image_url : asset('images/default-food.jpg');
                                @endphp
                                <div class="position-relative w-100" style="min-height: 220px;">
                                    <img src="{{ $imageUrl }}" 
                                         class="w-100 h-100 position-absolute" 
                                         style="object-fit: cover; top: 0; left: 0;"
                                         alt="Food Image">
                                </div>
                            </div>
                            
                            <!-- Right Side: Order Details -->
                            <div class="col-md-9">
                                <div class="card-body">
                                    @php
                                        $firstItem = $order->orderItems->first();
                                        $itemName = $firstItem && $firstItem->menuItem ? $firstItem->menuItem->name : 'Item';
                                        $restaurantName = $firstItem && $firstItem->menuItem && $firstItem->menuItem->restaurant ? $firstItem->menuItem->restaurant->name : 'Restaurant';
                                    @endphp
                                    
                                    <!-- Item Name & Restaurant -->
                                    <div class="mb-2">
                                        <h4 class="fw-bold mb-1" style="color: #2c3e50;">{{ $itemName }}</h4>
                                        <p class="mb-0 text-muted">
                                            <i class="fas fa-store me-1" style="color: #ff6b35;"></i>
                                            {{ $restaurantName }}
                                        </p>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div>
                                            <p class="text-muted mb-0 small">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                {{ $order->created_at->format('M d, Y h:i A') }}
                                            </p>
                                        </div>
                                        <div class="text-end">
                                            <h4 class="fw-bold mb-0" style="color: #ff6b35;">
                                                ₹{{ number_format($order->total_amount, 0) }}
                                            </h4>
                                            <small class="text-muted">{{ $order->orderItems->count() }} item(s)</small>
                                        </div>
                                    </div>
                                    
                                    <!-- Additional Items (if more than 1) -->
                                    @if($order->orderItems->count() > 1)
                                        <div class="mb-3">
                                            <small class="text-muted">
                                                <i class="fas fa-plus-circle me-1"></i>
                                                Also includes: 
                                                @foreach($order->orderItems->skip(1)->take(2) as $item)
                                                    {{ $item->menuItem->name ?? 'Item' }}{{ !$loop->last ? ',' : '' }}
                                                @endforeach
                                                @if($order->orderItems->count() > 3)
                                                    +{{ $order->orderItems->count() - 3 }} more
                                                @endif
                                            </small>
                                        </div>
                                    @endif
                                    
                                    <!-- View Details Button -->
                                    <button class="btn btn-sm" 
                                            style="border: 1px solid #ff6b35; color: #ff6b35; background: white;"
                                            onclick="toggleOrderDetails({{ $order->id }})">
                                        <i class="fas fa-eye me-1"></i> View Details
                                        <i class="fas fa-chevron-down ms-1" id="icon-{{ $order->id }}"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Expandable Details Section -->
                        <div id="orderDetails{{ $order->id }}" class="d-none border-top bg-light">
                            <div class="card-body">
                                <div class="row">
                                    <!-- All Items -->
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3" style="color: #ff6b35;">
                                            <i class="fas fa-receipt me-2"></i>Order Items
                                        </h6>
                                        @foreach($order->orderItems as $item)
                                            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                                <div class="d-flex align-items-center">
                                                    @if($item->menuItem)
                                                        <img src="{{ $item->menuItem->image_url }}" 
                                                             class="rounded me-3" 
                                                             style="width: 50px; height: 50px; object-fit: cover;"
                                                             alt="Item">
                                                    @else
                                                        <div class="bg-secondary rounded me-3 d-flex align-items-center justify-content-center"
                                                             style="width: 50px; height: 50px;">
                                                            <i class="fas fa-utensils text-white"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <span class="fw-bold d-block">{{ $item->menuItem->name ?? 'Item' }}</span>
                                                        <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                                    </div>
                                                </div>
                                                <div class="text-end">
                                                    <span class="fw-bold d-block">₹{{ number_format($item->price * $item->quantity, 0) }}</span>
                                                    <small class="text-muted">₹{{ number_format($item->price, 0) }}/each</small>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <div class="mt-3 pt-3 border-top">
                                            <!-- Fee Breakdown -->
                                            @php
                                                $itemsSubtotal = $order->orderItems->sum(function($item) {
                                                    return $item->price * $item->quantity;
                                                });
                                                $deliveryFee = 49;
                                                $platformFees = $order->total_amount - $itemsSubtotal - $deliveryFee;
                                            @endphp
                                            
                                            <div class="bg-white rounded p-3 mb-3">
                                                <h6 class="fw-bold mb-2" style="color: #ff6b35;">
                                                    <i class="fas fa-calculator me-1"></i> Bill Breakdown
                                                </h6>
                                                <div class="d-flex justify-content-between py-1">
                                                    <span class="text-muted">Items Subtotal</span>
                                                    <span>₹{{ number_format($itemsSubtotal, 0) }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between py-1">
                                                    <span class="text-muted">
                                                        <i class="fas fa-motorcycle me-1"></i>Delivery Fee
                                                    </span>
                                                    <span>₹{{ number_format($deliveryFee, 0) }}</span>
                                                </div>
                                                @if($platformFees > 0)
                                                    <div class="d-flex justify-content-between py-1">
                                                        <span class="text-muted">
                                                            <i class="fas fa-cog me-1"></i>Platform Fee
                                                        </span>
                                                        <span>₹{{ number_format($platformFees, 0) }}</span>
                                                    </div>
                                                @endif
                                                <hr class="my-2">
                                                <div class="d-flex justify-content-between">
                                                    <span class="fw-bold">Total Amount</span>
                                                    <span class="fw-bold fs-5" style="color: #ff6b35;">₹{{ number_format($order->total_amount, 0) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delivery Details -->
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-3" style="color: #ff6b35;">
                                            <i class="fas fa-truck me-2"></i>Delivery Details
                                        </h6>
                                        <div class="card border-0 mb-3">
                                            <div class="card-body bg-white rounded">
                                                <p class="mb-2">
                                                    <i class="fas fa-user text-muted me-2"></i>
                                                    <strong>{{ $order->customer_name }}</strong>
                                                </p>
                                                <p class="mb-2">
                                                    <i class="fas fa-phone text-muted me-2"></i>
                                                    {{ $order->customer_phone }}
                                                </p>
                                                <p class="mb-0">
                                                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                                                    {{ $order->customer_address }}
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <h6 class="fw-bold mb-2" style="color: #ff6b35;">
                                            <i class="fas fa-info-circle me-2"></i>Order Info
                                        </h6>
                                        <div class="d-flex justify-content-between py-2">
                                            <span class="text-muted">Ordered On</span>
                                            <span>{{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="text-center py-5">
                    <i class="fas fa-shopping-bag fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No orders found</h4>
                    <a href="{{ route('restaurants') }}" class="btn btn-warning px-4">
                        <i class="fas fa-store me-1"></i> Browse Restaurants
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function toggleOrderDetails(orderId) {
    const detailsDiv = document.getElementById('orderDetails' + orderId);
    const icon = document.getElementById('icon-' + orderId);
    
    if (detailsDiv.classList.contains('d-none')) {
        detailsDiv.classList.remove('d-none');
        icon.classList.remove('fa-chevron-down');
        icon.classList.add('fa-chevron-up');
    } else {
        detailsDiv.classList.add('d-none');
        icon.classList.remove('fa-chevron-up');
        icon.classList.add('fa-chevron-down');
    }
}
</script>
@endsection
