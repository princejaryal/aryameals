@extends('admin.layouts.app')

@section('title', 'Arya Meals - Edit Customer')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('admin.customers') }}')" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Edit Customer</h1>
                    <p class="text-sm text-purple-500 mt-1">Update customer information</p>
                </div>
            </div>
        </section>

        <!-- Customer Information Form -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100">
            <div class="p-6">
                <form action="{{ route('admin.customers.update', $customer->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Customer Profile -->
                    <div class="text-center mb-8">
                        <div class="h-24 w-24 mx-auto rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white text-3xl font-bold mb-4">
                            {{ strtoupper(substr($customer->name, 0, 1)) }}
                        </div>
                        <h2 class="text-xl font-bold text-purple-900 mb-2">{{ $customer->name }}</h2>
                        <p class="text-purple-600">Customer ID: #{{ $customer->id }}</p>
                        <p class="text-sm text-purple-500">Member since: {{ $customer->created_at->format('M d, Y') }}</p>
                    </div>

                    <!-- Basic Information -->
                    <div class="border-b border-purple-100 pb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Basic Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Full Name</label>
                                <input type="text" name="name" value="{{ $customer->name }}" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Email Address</label>
                                <input type="email" name="email" value="{{ $customer->email }}" required
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Password Change -->
                    <div class="border-b border-purple-100 pb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Password Change</h3>
                        <p class="text-sm text-purple-600 mb-4">Leave blank to keep current password</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">New Password</label>
                                <input type="password" name="password" 
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-purple-700 mb-2">Confirm Password</label>
                                <input type="password" name="password_confirmation" 
                                    class="w-full px-4 py-2 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Customer Statistics -->
                    <div class="border-b border-purple-100 pb-6">
                        <h3 class="text-lg font-semibold text-purple-900 mb-4">Customer Statistics</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-purple-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-purple-600">Total Orders</p>
                                <p class="text-2xl font-bold text-purple-900">{{ $customer->orders->count() }}</p>
                            </div>
                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-green-600">Total Spent</p>
                                <p class="text-2xl font-bold text-green-900">${{ number_format($customer->orders->sum('total_amount'), 2) }}</p>
                            </div>
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm font-medium text-blue-600">Last Order</p>
                                <p class="text-lg font-bold text-blue-900">
                                    @if($customer->orders->count() > 0)
                                        {{ $customer->orders->first()->created_at->format('M d, Y') }}
                                    @else
                                        No orders
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Orders Preview -->
                    @if($customer->orders->count() > 0)
                        <div class="pb-6">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4">Recent Orders</h3>
                            <div class="space-y-2">
                                @foreach($customer->orders->take(3) as $order)
                                    <div class="flex justify-between items-center bg-purple-50 p-3 rounded-lg">
                                        <div>
                                            <span class="font-medium text-purple-900">Order #{{ $order->id }}</span>
                                            <span class="text-purple-600 ml-2">{{ $order->restaurant->name ?? 'N/A' }}</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="font-semibold text-purple-900">${{ number_format($order->total_amount, 2) }}</span>
                                            <span class="text-xs text-purple-600 ml-2">{{ $order->created_at->format('M d, Y') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @if($customer->orders->count() > 3)
                                <div class="text-center mt-3">
                                    <a href="{{ route('admin.customers.orders', $customer->id) }}" 
                                        class="text-purple-600 hover:text-purple-900 font-medium text-sm">
                                        View all {{ $customer->orders->count() }} orders →
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6">
                        <button type="button" onclick="window.location.href = '{{ route('admin.customers') }}'" 
                            class="px-6 py-2 border border-purple-300 text-purple-700 rounded-lg hover:bg-purple-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-6 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg hover:from-purple-700 hover:to-pink-700 transition-all">
                            Update Customer
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
    <script>
        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const password = document.querySelector('input[name="password"]').value;
            const passwordConfirm = document.querySelector('input[name="password_confirmation"]').value;
            
            if (password && password !== passwordConfirm) {
                e.preventDefault();
                alert('Password and password confirmation do not match.');
                return false;
            }
            
            if (password && password.length < 8) {
                e.preventDefault();
                alert('Password must be at least 8 characters long.');
                return false;
            }
        });

        // Show confirmation if password is being changed
        document.querySelector('input[name="password"]').addEventListener('input', function() {
            if (this.value.length > 0) {
                const confirmField = document.querySelector('input[name="password_confirmation"]');
                confirmField.setAttribute('required', 'required');
            } else {
                const confirmField = document.querySelector('input[name="password_confirmation"]');
                confirmField.removeAttribute('required');
            }
        });
    </script>
@endsection
