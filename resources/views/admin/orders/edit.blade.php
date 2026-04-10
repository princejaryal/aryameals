@extends('admin.layouts.app')

@section('title', 'Arya Meals - Edit Order')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Edit Order #{{ $order->id }}</h1>
                    <p class="text-sm text-purple-500 mt-1">Update order details and status</p>
                </div>
            </div>
        </section>

        <!-- Order Details Form -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100">
            <div class="p-6">
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Customer Information -->
                    <div class="border-b border-purple-100 pb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Customer Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Customer Name</label>
                                <input type="text" name="customer_name" value="{{ $order->customer_name }}" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Phone Number</label>
                                <input type="text" name="customer_phone" value="{{ $order->customer_phone }}" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-purple-700 mb-2">Delivery Address</label>
                                <textarea name="customer_address" rows="2"
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ $order->customer_address ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Order Status -->
                    <div class="border-b border-purple-100 pb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Order Status</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Order Status</label>
                                <select name="status" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                    <option value="preparing" {{ $order->status == 'preparing' ? 'selected' : '' }}>Preparing</option>
                                    <option value="ready" {{ $order->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Payment Status</label>
                                <select name="payment_status" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                    <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Delivery Type</label>
                                <select name="delivery_type" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="pickup" {{ $order->delivery_type == 'pickup' ? 'selected' : '' }}>Pickup</option>
                                    <option value="delivery" {{ $order->delivery_type == 'delivery' ? 'selected' : '' }}>Delivery</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Payment Method</label>
                                <select name="payment_method" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="cash" {{ $order->payment_method == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="online" {{ $order->payment_method == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="card" {{ $order->payment_method == 'card' ? 'selected' : '' }}>Card</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items (Read-only) -->
                    <div class="border-b border-purple-100 pb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Order Items</h3>
                        <div class="bg-purple-50 rounded-lg p-4">
                            @if($order->formatted_items)
                                <div class="space-y-2">
                                    @foreach($order->formatted_items as $item)
                                        <div class="flex justify-between items-center bg-white p-3 rounded-lg">
                                            <div>
                                                <span class="font-medium text-purple-900">{{ $item['name'] ?? 'Item' }}</span>
                                                <span class="text-purple-600 ml-2">x{{ $item['quantity'] ?? 1 }}</span>
                                            </div>
                                            <span class="font-semibold text-purple-900">${{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 pt-4 border-t border-purple-200">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-semibold text-purple-900">Total Amount:</span>
                                        <span class="text-xl font-bold text-purple-900">${{ number_format($order->total_amount, 2) }}</span>
                                    </div>
                                </div>
                            @else
                                <p class="text-purple-600">No items found</p>
                            @endif
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Order Notes</label>
                        <textarea name="order_notes" rows="3" 
                            class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ $order->order_notes ?? '' }}</textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <button type="button" onclick="goBack()" 
                            class="px-6 py-2 border border-purple-300 text-purple-700 rounded-lg hover:bg-purple-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all">
                            Update Order
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        function goBack() {
            window.history.back();
        }

        // Auto-save functionality (optional)
        let autoSaveTimer;
        const form = document.querySelector('form');
        
        form.addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // You can implement auto-save here if needed
                console.log('Auto-save triggered');
            }, 5000);
        });

        // Show confirmation before leaving if form is dirty
        let formDirty = false;
        form.addEventListener('change', function() {
            formDirty = true;
        });

        window.addEventListener('beforeunload', function(e) {
            if (formDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });
    </script>
@endsection
