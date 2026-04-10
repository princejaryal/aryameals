@extends('admin.layouts.app')

@section('title', 'Arya Meals - Customer Management')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('admin.dashboard') }}')" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Customer Management</h1>
                    <p class="text-sm text-purple-500 mt-1">Manage customer accounts and view order history</p>
                </div>
            </div>
        </section>

        <!-- Stats Cards -->
        <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Total Customers</p>
                        <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['total_customers']) }}</p>
                    </div>
                    <div class="h-12 w-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">New Today</p>
                        <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['new_customers_today']) }}</p>
                    </div>
                    <div class="h-12 w-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-user-plus text-green-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">This Month</p>
                        <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['new_customers_this_month']) }}</p>
                    </div>
                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar text-blue-600"></i>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-purple-600">Active</p>
                        <p class="text-2xl font-bold text-purple-900">{{ number_format($stats['active_customers']) }}</p>
                    </div>
                    <div class="h-12 w-12 bg-pink-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-pink-600"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Filters Section -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100 p-6">
            <form method="GET" action="{{ route('admin.customers') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-purple-700 mb-2">Search Customers</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                placeholder="Search by name, email, or ID..."
                                class="w-full px-4 py-2 pl-10 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-purple-400"></i>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Date From -->
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">From Date</label>
                        <input type="date" name="date_from" value="{{ request('date_from') }}" 
                            class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <!-- Date To -->
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">To Date</label>
                        <input type="date" name="date_to" value="{{ request('date_to') }}" 
                            class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" name="has_orders" value="1" {{ request('has_orders') ? 'checked' : '' }} 
                            class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-purple-300 rounded">
                        <label for="has_orders" class="ml-2 text-sm text-purple-700">Show customers with orders only</label>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                        <a href="{{ route('admin.customers') }}" class="px-4 py-2 border border-purple-300 text-purple-700 rounded-lg hover:bg-purple-50 transition-colors">
                            Clear Filters
                        </a>
                        <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Customers Table -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-purple-50 border-b border-purple-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Orders</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Total Spent</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Joined</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-purple-100">
                        @forelse($customers as $customer)
                            <tr class="hover:bg-purple-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 flex-shrink-0">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white font-semibold">
                                                {{ strtoupper(substr($customer->name, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-purple-900">{{ $customer->name }}</div>
                                            <div class="text-sm text-purple-500">ID: #{{ $customer->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-purple-900">{{ $customer->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-purple-900 font-medium">{{ $customer->orders_count ?? 0 }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-purple-900">
                                        ${{ number_format($customer->orders_sum_total_amount ?? 0, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-purple-900">{{ $customer->created_at->format('M d, Y') }}</div>
                                    <div class="text-xs text-purple-500">{{ $customer->created_at->diffForHumans() }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.customers.show', $customer->id) }}" 
                                            class="text-purple-600 hover:text-purple-900 font-medium">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer->id) }}" 
                                            class="text-blue-600 hover:text-blue-900 font-medium">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($customer->orders_count == 0)
                                            <form action="{{ route('admin.customers.destroy', $customer->id) }}" method="POST" 
                                                onsubmit="return confirm('Are you sure you want to delete this customer?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 font-medium">
                                                    <i class="fas fa-trash"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-purple-500">
                                        <i class="fas fa-users text-4xl mb-4"></i>
                                        <p class="text-lg font-medium">No customers found</p>
                                        <p class="text-sm">Try adjusting your search criteria</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($customers->hasPages())
                <div class="px-6 py-4 border-t border-purple-200">
                    {{ $customers->links() }}
                </div>
            @endif
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
