@extends('admin.layouts.app')

@section('title', 'Arya Meals - Customer Orders')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('admin.customers.show', $customer->id) }}')" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Customer Orders</h1>
                    <p class="text-sm text-purple-500 mt-1">View all orders for {{ $customer->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.customers.show', $customer->id) }}" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-user"></i>
                    <span>Customer Profile</span>
                </a>
            </div>
        </section>

        <!-- Customer Summary Card -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white text-2xl font-bold">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-purple-900">{{ $customer->name }}</h2>
                        <p class="text-purple-600">{{ $customer->email }}</p>
                        <p class="text-sm text-purple-500">Customer ID: #{{ $customer->id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-purple-600">Total Orders</p>
                    <p class="text-3xl font-bold text-purple-900">{{ $orders->total() }}</p>
                    <p class="text-sm text-purple-500">Total Spent: ${{ number_format($customer->orders->sum('total_amount'), 2) }}</p>
                </div>
            </div>
        </section>

        <!-- Orders Table -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-purple-50 border-b border-purple-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Items</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-purple-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-purple-900">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-purple-900">
                                        @if($order->formatted_items)
                                            <div class="space-y-1">
                                                @foreach(array_slice($order->formatted_items, 0, 2) as $item)
                                                    <div class="text-xs">
                                                        <span class="font-medium">{{ $item['name'] ?? 'Item' }}</span>
                                                        <span class="text-purple-600"> x{{ $item['quantity'] ?? 1 }}</span>
                                                    </div>
                                                @endforeach
                                                @if(count($order->formatted_items) > 2)
                                                    <div class="text-xs text-purple-500">+{{ count($order->formatted_items) - 2 }} more items</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-purple-500">No items</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-purple-900">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status == 'preparing') bg-yellow-100 text-yellow-800
                                        @elseif($order->status == 'ready') bg-blue-100 text-blue-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->payment_status == 'paid') bg-green-100 text-green-800
                                        @elseif($order->payment_status == 'failed') bg-red-100 text-red-800
                                        @elseif($order->payment_status == 'refunded') bg-yellow-100 text-yellow-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-purple-900">
                                        <div>{{ $order->created_at->format('M d, Y') }}</div>
                                        <div class="text-xs text-purple-500">{{ $order->created_at->format('h:i A') }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" 
                                            class="text-purple-600 hover:text-purple-900 font-medium">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" 
                                            class="text-blue-600 hover:text-blue-900 font-medium">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="text-purple-500">
                                        <i class="fas fa-shopping-cart text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No orders found</p>
                                        <p class="text-sm">This customer hasn't placed any orders yet</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($orders->hasPages())
                <div class="px-6 py-4 border-t border-purple-200">
                    {{ $orders->links() }}
                </div>
            @endif
        </section>

        <!-- Order Summary Stats -->
        @if($orders->count() > 0)
            <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Average Order Value</p>
                            <p class="text-2xl font-bold text-purple-900">
                                ${{ number_format($customer->orders->avg('total_amount'), 2) }}
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calculator text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Total Orders</p>
                            <p class="text-2xl font-bold text-purple-900">{{ $orders->total() }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">First Order</p>
                            <p class="text-lg font-bold text-purple-900">
                                {{ $customer->orders->last()?->created_at->format('M d, Y') ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Last Order</p>
                            <p class="text-lg font-bold text-purple-900">
                                {{ $customer->orders->first()?->created_at->format('M d, Y') ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-pink-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-pink-600"></i>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </div>
@endsection

@section('scripts')
    <script>
        function goBack() {
            // Try to go back in history
            if (window.history.length > 1) {
                window.history.back();
            } else {
                // Fallback to customers index if no history
                window.location.href = '{{ route("admin.customers") }}';
            }
        }
        
        // Also add keyboard shortcut for back button
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                goBack();
            }
        });
    </script>
@endsection
