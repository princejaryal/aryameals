@extends('admin.layouts.app')

@section('title', 'Arya Meals - Order Details')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center space-x-4">
            <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Order Details</h1>
                <p class="text-sm text-purple-500 mt-1">Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
                    <div class="p-6 border-b border-purple-100">
                        <h2 class="text-lg font-bold text-purple-900">Customer Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-purple-700">Name</label>
                                <p class="text-purple-900 font-medium">{{ $order->customer_name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-purple-700">Phone</label>
                                <p class="text-purple-900 font-medium">{{ $order->customer_phone }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <label class="text-sm font-medium text-purple-700">Address</label>
                                <p class="text-purple-900 font-medium">{{ $order->customer_address }}</p>
                            </div>
                        </div>
                        @if($order->order_notes)
                            <div>
                                <label class="text-sm font-medium text-purple-700">Order Notes</label>
                                <p class="text-purple-900">{{ $order->order_notes }}</p>
                            </div>
                        @endif
                    </div>
                </section>

                <!-- Order Items -->
                <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
                    <div class="p-6 border-b border-purple-100">
                        <h2 class="text-lg font-bold text-purple-900">Order Items</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($order->formatted_items as $item)
                                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                                    <div class="flex items-center space-x-4">
                                        @if($item['image'])
                                            <img src="{{ asset('storage/menu-items/' . $item['image']) }}" 
                                                 alt="{{ $item['name'] }}" 
                                                 class="w-16 h-16 rounded-lg object-cover">
                                        @else
                                            <div class="w-16 h-16 rounded-lg bg-purple-100 flex items-center justify-center">
                                                <i class="fas fa-utensils text-purple-600"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="font-medium text-purple-900">{{ $item['name'] }}</h4>
                                            <p class="text-sm text-purple-600">Quantity: {{ $item['quantity'] }}</p>
                                            @if($item['special_instructions'])
                                                <p class="text-xs text-purple-500 mt-1">Notes: {{ $item['special_instructions'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-bold text-purple-900">₹{{ number_format($item['price'], 0) }}</p>
                                        <p class="text-sm text-purple-600">x {{ $item['quantity'] }}</p>
                                        <p class="font-bold text-lg text-purple-900">₹{{ number_format($item['price'] * $item['quantity'], 0) }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <!-- Order Total -->
                        <div class="mt-6 pt-6 border-t border-purple-200">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-purple-900">Total Amount:</span>
                                <span class="text-xl font-bold text-purple-900">₹{{ number_format($order->total_amount, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <!-- Order Status & Actions -->
            <div class="space-y-6">
                <!-- Order Status -->
                <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
                    <div class="p-6 border-b border-purple-100">
                        <h2 class="text-lg font-bold text-purple-900">Order Status</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        
                        <div>
                            <label class="text-sm font-medium text-purple-700">Delivery Type</label>
                            <p class="text-purple-900 font-medium">{{ ucfirst($order->delivery_type ?? 'Standard') }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-purple-700">Payment Method</label>
                            <p class="text-purple-900 font-medium">{{ ucfirst($order->payment_method ?? 'Cash') }}</p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = '{{ route("admin.orders") }}';
        }

        function updateOrderStatus(orderId, newStatus) {
            if (confirm(`Are you sure you want to update order status to "${newStatus}"?`)) {
                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating order status');
                    }
                });
            }
        }

        function cancelOrder(orderId) {
            if (confirm('Are you sure you want to cancel this order?')) {
                fetch(`/admin/orders/${orderId}/cancel`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error cancelling order');
                    }
                });
            }
        }
    </script>
@endsection
