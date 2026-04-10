@extends('admin.layouts.app')

@section('title', 'Arya Meals - Menu Management')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="space-y-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                        <i class="fas fa-arrow-left"></i>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Menu Management</h1>
                        <p class="text-sm text-purple-500 mt-1">Manage restaurant menu items</p>
                    </div>
                </div>
                <button onclick="window.location.href='{{ route('admin.menu.create') }}'" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                    <i class="fas fa-plus mr-2"></i>
                    Add Menu Item
                </button>
            </div>
            
            <!-- Filters Section -->
            <div class="flex items-center space-x-4 flex-wrap gap-2">
                <!-- Restaurant Filter -->
                <div class="relative">
                    <select id="restaurantFilter" onchange="filterByRestaurant(this.value)" 
                            class="appearance-none bg-white border border-purple-200 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Restaurants</option>
                        @foreach($restaurants ?? [] as $rest)
                            <option value="{{ $rest->id }}" {{ ($restaurant?->id ?? request('restaurant_id')) == $rest->id ? 'selected' : '' }}>
                                {{ $rest->name }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-purple-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="relative">
                    <select id="categoryFilter" onchange="filterByCategory(this.value)" 
                            class="appearance-none bg-white border border-purple-200 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ ucfirst($category) }}
                            </option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-purple-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <!-- Availability Filter -->
                <div class="relative">
                    <select id="availabilityFilter" onchange="filterByAvailability(this.value)" 
                            class="appearance-none bg-white border border-purple-200 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <option value="">All Items</option>
                        <option value="available" {{ request('availability') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="unavailable" {{ request('availability') == 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-purple-600">
                        <i class="fas fa-chevron-down text-xs"></i>
                    </div>
                </div>

                <!-- Clear Filters Button -->
                <button onclick="clearFilters()" class="px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 text-sm font-medium">
                    Clear Filters
                </button>
            </div>
        </section>

        <!-- Restaurant Info (if filtered) -->
        @if($restaurant)
        <section class="bg-white rounded-xl shadow-lg border border-purple-200 p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-store text-purple-600"></i>
                    <div>
                        <h3 class="font-semibold text-purple-900">{{ $restaurant->name }}</h3>
                        <p class="text-sm text-purple-600">{{ $restaurant->category }} • {{ $restaurant->city }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.menu') }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>
                    Clear Filter
                </a>
            </div>
        </section>
        @endif

        <!-- Menu Items Table -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <h2 class="text-lg font-bold text-purple-900">Menu Items</h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Restaurant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Prices</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-100">
                        @forelse($menuItems as $item)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-3">
                                    @if($item->image)
                                        <img src="{{ asset('storage/menu-items/' . $item->image) }}?v={{ time() }}" alt="{{ $item->name }}" class="w-10 h-10 rounded-lg object-cover">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-utensils text-purple-600"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-purple-900">{{ $item->name }}</p>
                                        @if($item->is_recommended)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-amber-100 text-amber-800">
                                                <i class="fas fa-star mr-1"></i>
                                                Recommended
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800">
                                    {{ ucfirst($item->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                @if($item && $item->restaurant)
                                    <span class="px-2 py-1 text-xs font-medium rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white font-bold">
                                        {{ strtoupper(substr($item->restaurant->name ?? 'R', 0, 2)) }}
                                    </span>
                                    <span class="text-purple-900 text-sm">{{ $item->restaurant->name }}</span>
                                @else
                                    <span class="text-purple-500">No Restaurant</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm text-purple-900">{{ $item->name ?? 'No Name' }}</p>
                                <p class="text-xs text-purple-500">{{ $item->category ?? 'No Category' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                @if($item && $item->price > 0)
                                    <div class="text-sm">
                                        <p class="font-medium text-purple-900">Price: {{ number_format($item->price, 0) }}</p>
                                        <p class="text-purple-500">Single Price</p>
                                    </div>
                                @else
                                    <div class="text-sm">
                                        <p class="font-medium text-purple-900">Full: {{ number_format($item->full_plate_price ?? 0, 0) }}</p>
                                        <p class="text-purple-500">Half: {{ number_format($item->half_plate_price ?? 0, 0) }}</p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($item->is_available)
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Available
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>
                                        Unavailable
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="toggleAvailability({{ $item->id }})" class="text-purple-600 hover:text-purple-800">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button onclick="editMenuItem({{ $item->id }})" class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteMenuItem({{ $item->id }})" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-purple-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>No menu items found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($menuItems->hasPages())
            <div class="px-6 py-4 border-t border-purple-100">
                {{ $menuItems->links() }}
            </div>
            @endif
        </section>
    </div>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function goBack() {
            window.location.href = '{{ route("admin.restaurants") }}';
        }

        function filterByRestaurant(restaurantId) {
            const url = new URL(window.location.href);
            if (restaurantId) {
                url.pathname = `/admin/menu/restaurant/${restaurantId}`;
            } else {
                url.pathname = '{{ route("admin.menu") }}';
            }
            // Keep other filters
            url.searchParams.delete('restaurant_id');
            window.location.href = url.toString();
        }

        function filterByCategory(category) {
            const url = new URL(window.location.href);
            if (category) {
                url.searchParams.set('category', category);
            } else {
                url.searchParams.delete('category');
            }
            window.location.href = url.toString();
        }

        function filterByAvailability(availability) {
            const url = new URL(window.location.href);
            if (availability) {
                url.searchParams.set('availability', availability);
            } else {
                url.searchParams.delete('availability');
            }
            window.location.href = url.toString();
        }

        function clearFilters() {
            window.location.href = '{{ route("admin.menu") }}';
        }

        function toggleAvailability(id) {
            Swal.fire({
                title: 'Toggle Availability?',
                text: 'Are you sure you want to toggle availability?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#22c55e',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, toggle it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/menu-items/${id}/toggle-availability`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Availability updated successfully!',
                                icon: 'success',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: 'Error: ' + (data.message || 'Unknown error'),
                                icon: 'error'
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'Error!',
                            text: 'Error updating availability. Please try again.',
                            icon: 'error'
                        });
                    });
                }
            });
        }

        function editMenuItem(id) {
            window.location.href = `/admin/menu/${id}/edit`;
        }

        function deleteMenuItem(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            console.log('Deleting item with ID:', id);
            console.log('URL:', `/admin/menu/items/${id}`);
            
            fetch(`/admin/menu/items/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    Swal.fire({
                        title: 'Deleted!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Delete Failed!',
                        text: data.message || 'Failed to delete menu item',
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                console.error('Delete error:', error);
                Swal.fire({
                    title: 'Error!',
                    text: 'Error deleting menu item: ' + error.message,
                    icon: 'error'
                });
            });
        }
    });
}
    </script>
@endsection
