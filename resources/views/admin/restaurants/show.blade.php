@extends('admin.layouts.app')

@section('title', 'Arya Meals - Restaurant Details')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Restaurant Details</h1>
                    <p class="text-sm text-purple-500 mt-1">View restaurant information and menu items</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <button onclick="editRestaurant({{ $restaurant->id }})" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                    <i class="fas fa-edit mr-2"></i>
                    Edit Restaurant
                </button>
            </div>
        </section>

        <!-- Restaurant Info Card -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <h2 class="text-lg font-bold text-purple-900">Restaurant Information</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Restaurant Image -->
                    <div class="md:col-span-1">
                        @if($restaurant->image)
                            <img src="{{ asset('storage/restaurants/' . $restaurant->image) }}" alt="{{ $restaurant->name }}" class="w-full h-48 object-cover rounded-lg shadow-md">
                        @else
                            <div class="w-full h-48 bg-purple-100 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-store text-purple-400 text-4xl"></i>
                            </div>
                        @endif
                        
                        <!-- Status Badge -->
                        <div class="mt-4 text-center">
                            @if($restaurant->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                    <i class="fas fa-times-circle mr-1"></i>
                                    Inactive
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Restaurant Details -->
                    <div class="md:col-span-2 space-y-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-lg font-semibold text-purple-900 mb-3">{{ $restaurant->name }}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-purple-600 font-medium">Category</p>
                                    <p class="text-purple-900 capitalize">{{ $restaurant->category }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-purple-600 font-medium">Rating</p>
                                    <div class="flex">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= floor($restaurant->rating))
                                                <i class="fas fa-star text-sm" style="color: gold;"></i>
                                            @elseif($i - 0.5 <= $restaurant->rating)
                                                <i class="fas fa-star-half-alt text-sm" style="color: gold;"></i>
                                            @else
                                                <i class="far fa-star text-sm text-yellow-400"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Contact Info -->
                        <div>
                            <h4 class="text-md font-semibold text-purple-800 mb-2">Contact Information</h4>
                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <i class="fas fa-envelope text-purple-500 w-5"></i>
                                    <span class="ml-3 text-purple-900">{{ $restaurant->email }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone text-purple-500 w-5"></i>
                                    <span class="ml-3 text-purple-900">{{ $restaurant->phone }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        <div>
                            <h4 class="text-md font-semibold text-purple-800 mb-2">Address</h4>
                            <div class="space-y-2">
                                <div class="flex items-start">
                                    <i class="fas fa-map-marker-alt text-purple-500 w-5 mt-1"></i>
                                    <div class="ml-3">
                                        <p class="text-purple-900">{{ $restaurant->address }}</p>
                                        <p class="text-purple-900">{{ $restaurant->city }}, {{ $restaurant->state }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Description -->
                        @if($restaurant->description)
                        <div>
                            <h4 class="text-md font-semibold text-purple-800 mb-2">Description</h4>
                            <p class="text-purple-900">{{ $restaurant->description }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu Items Section -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-purple-900">Menu Items</h2>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-purple-600">
                        Total: {{ $restaurant->menuItems->count() }} items
                    </span>
                    <button onclick="addMenuItem({{ $restaurant->id }})" class="px-3 py-1 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                        <i class="fas fa-plus mr-1"></i>
                        Add Item
                    </button>
                </div>
            </div>
            
            <div class="p-6">
                @if($restaurant->menuItems->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($restaurant->menuItems as $item)
                            <div class="border border-purple-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-start space-x-3">
                                    @if($item->image)
                                        <img src="{{ asset('storage/menu-items/' . $item->image) }}" alt="{{ $item->name }}" class="w-16 h-16 rounded-lg object-cover">
                                    @else
                                        <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-utensils text-purple-600"></i>
                                        </div>
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-purple-900">{{ $item->name }}</h4>
                                        <p class="text-sm text-purple-600">{{ $item->category }}</p>
                                        <div class="flex items-center justify-between mt-2">
                                            <span class="text-purple-900 font-medium">₹{{ number_format($item->full_plate_price, 0) }}</span>
                                            @if($item->is_available)
                                                <span class="text-xs text-green-600">Available</span>
                                            @else
                                                <span class="text-xs text-red-600">Unavailable</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i class="fas fa-utensils text-4xl text-purple-300 mb-4"></i>
                        <p class="text-purple-500">No menu items added yet</p>
                        <button onclick="addMenuItem({{ $restaurant->id }})" class="mt-4 px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                            <i class="fas fa-plus mr-2"></i>
                            Add First Menu Item
                        </button>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <script>
        function goBack() {
            window.location.href = '{{ route("admin.restaurants") }}';
        }

        function editRestaurant(id) {
            window.location.href = `/admin/restaurants/${id}/edit`;
        }

        function addMenuItem(restaurantId) {
            window.location.href = `/admin/menu/create?restaurant_id=${restaurantId}`;
        }
    </script>
@endsection
