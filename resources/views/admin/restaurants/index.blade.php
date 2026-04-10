@extends('admin.layouts.app')

@section('title', 'Arya Meals - Restaurants')

@section('content')
<div class="p-6 space-y-8">
    <!-- Header Section -->
    <section class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Restaurants</h1>
            <p class="text-sm text-purple-500 mt-1">Manage restaurants, menus, and pricing</p>
        </div>
        <button onclick="window.location.href='{{ route('admin.restaurants.create') }}'" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Add Restaurant
        </button>
    </section>

    <!-- Search and Filter Section -->
    <section class="bg-white rounded-2xl shadow-lg p-6 border border-purple-200">
        <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
                <div class="relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-purple-400">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" id="searchInput" placeholder="Search restaurants..." class="w-full pl-10 pr-4 py-2.5 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-slate-800">
                </div>
            </div>
            <div class="flex gap-3">
                <select id="statusFilter" class="px-4 py-2.5 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-slate-800">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <select id="categoryFilter" class="px-4 py-2.5 border border-purple-200 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 text-slate-800">
                    <option value="">All Categories</option>
                    <option value="indian">Indian</option>
                    <option value="chinese">Chinese</option>
                    <option value="italian">Italian</option>
                    <option value="mexican">Mexican</option>
                    <option value="thai">Thai</option>
                    <option value="american">American</option>
                </select>
            </div>
        </div>
    </section>

    <!-- Restaurants Table -->
    <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
        <div class="p-6 border-b border-purple-100">
            <h2 class="text-lg font-bold text-purple-900">Restaurant List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-purple-50">
                    <tr>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-purple-700 uppercase tracking-wider">Restaurant</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-purple-700 uppercase tracking-wider">Category</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-purple-700 uppercase tracking-wider">Menu Items</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-purple-700 uppercase tracking-wider">Rating</th>
                        <th class="text-left py-3 px-6 text-xs font-semibold text-purple-700 uppercase tracking-wider">Status</th>
                        <th class="text-center py-3 px-6 text-xs font-semibold text-purple-700 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-purple-100" id="restaurantsTableBody">
                    @forelse($restaurants as $restaurant)
                    <tr class="hover:bg-purple-50 transition-colors">
                        <td class="py-4 px-6">
                            <div class="flex items-center space-x-3">
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white font-bold">
                                    {{ strtoupper(substr($restaurant->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-purple-900">{{ $restaurant->name }}</p>
                                    <p class="text-sm text-purple-600">{{ $restaurant->city ?? 'N/A' }} • {{ $restaurant->category }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-700">{{ ucfirst($restaurant->category) }}</span>
                        </td>
                        <td class="py-4 px-6">
                            <p class="font-semibold text-purple-900">{{ $restaurant->menu_items_count ?? 0 }}</p>
                            <p class="text-sm text-purple-600">items</p>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center">
                                @php
                                    $rating = $restaurant->rating ?? 0;
                                    $fullStars = floor($rating);
                                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
                                    $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                                @endphp
                                
                                <!-- Full Stars -->
                                @for($i = 0; $i < $fullStars; $i++)
                                    <i class="fas fa-star text-xs" style="color: red;"></i>
                                @endfor
                                
                                <!-- Half Star -->
                                @if($hasHalfStar)
                                    <i class="fas fa-star-half-alt text-xs" style="color: red;"></i>
                                @endif
                                
                                <!-- Empty Stars -->
                                @for($i = 0; $i < $emptyStars; $i++)
                                    <i class="far fa-star text-xs text-red-500"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="py-4 px-6">
                            <span class="px-3 py-1 text-xs font-medium rounded-full {{ $restaurant->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $restaurant->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="py-4 px-6">
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="viewRestaurant({{ $restaurant->id }})" class="text-purple-600 hover:text-purple-800">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editRestaurant({{ $restaurant->id }})" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteRestaurant({{ $restaurant->id }})" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-purple-500">
                            <i class="fas fa-store text-4xl mb-2"></i>
                            <p>No restaurants found</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($restaurants->hasPages())
        <div class="p-4 border-t border-purple-100">
            {{ $restaurants->links() }}
        </div>
        @endif
    </section>
</div>

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function viewRestaurant(id) {
        window.location.href = `/admin/restaurants/${id}`;
    }

    function editRestaurant(id) {
        window.location.href = `/admin/restaurants/${id}/edit`;
    }

    function deleteRestaurant(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this! This will also delete all menu items.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/restaurants/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
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
                            title: 'Cannot Delete!',
                            text: data.message,
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Error deleting restaurant',
                        icon: 'error'
                    });
                });
            }
        });
    }

    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        const searchValue = this.value.toLowerCase();
        const rows = document.querySelectorAll('#restaurantsTableBody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchValue) ? '' : 'none';
        });
    });
</script>
@endsection