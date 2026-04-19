@extends('admin.layouts.app')

@section('title', 'Arya Meals - Customer Details')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('admin.customers') }}')" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Customer Details</h1>
                    <p class="text-sm text-purple-500 mt-1">View customer information and order history</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-edit"></i>
                    <span>Edit Customer</span>
                </a>
                <a href="{{ route('admin.customers.orders', $customer->id) }}" 
                    class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span>View Orders</span>
                </a>
            </div>
        </section>

        <!-- Customer Info Section -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Customer Profile Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="text-center">
                        <div class="h-24 w-24 mx-auto rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-purple-900 mb-2">{{ $customer->name }}</h2>
                        <p class="text-purple-600 mb-4">{{ $customer->email }}</p>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-purple-500">Member Since:</span>
                                <span class="font-medium text-purple-900">{{ $customer->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-purple-500">Account Age:</span>
                                <span class="font-medium text-purple-900">{{ $customer->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Total Orders</p>
                            <p class="text-3xl font-bold text-purple-900">{{ $stats['total_orders'] }}</p>
                        </div>
                        <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shopping-bag text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Total Spent</p>
                            <p class="text-3xl font-bold text-purple-900">${{ number_format($stats['total_spent'], 2) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Avg Order Value</p>
                            <p class="text-3xl font-bold text-purple-900">${{ number_format($stats['average_order_value'], 2) }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600">Last Order</p>
                            <p class="text-lg font-bold text-purple-900">
                                @if($stats['last_order'])
                                    {{ $stats['last_order']->created_at->format('M d, Y') }}
                                @else
                                    No orders
                                @endif
                            </p>
                        </div>
                        <div class="h-12 w-12 bg-pink-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-pink-600"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        
        <!-- Recent Orders -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100">
            <div class="p-6 border-b border-purple-100">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-purple-900">Recent Orders</h3>
                    <a href="{{ route('admin.customers.orders', $customer->id) }}" 
                        class="text-purple-600 hover:text-purple-900 font-medium">
                        View All Orders
                    </a>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-purple-50 border-b border-purple-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Restaurant</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-purple-100">
                        @forelse($customer->orders->take(5) as $order)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-purple-900">#{{ $order->id }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-purple-900">{{ $order->restaurant->name ?? 'N/A' }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-semibold text-purple-900">${{ number_format($order->total_amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($order->status == 'delivered') bg-green-100 text-green-800
                                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                                        @elseif($order->status == 'preparing') bg-yellow-100 text-yellow-800
                                        @else bg-purple-100 text-purple-800
                                        @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-purple-900">{{ $order->created_at->format('M d, Y') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('admin.orders.show', $order->id) }}" 
                                        class="text-purple-600 hover:text-purple-900 font-medium">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
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
        </section>
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
