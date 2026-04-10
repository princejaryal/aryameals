@extends('admin.layouts.app')

@section('title', 'Arya Meals - Review Management')

@section('content')
    <div class="p-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Review Management</h1>
                    <p class="text-sm text-purple-500 mt-1">Manage customer reviews and ratings</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Bulk Actions Dropdown -->
                <div class="relative">
                    <button onclick="toggleDropdown()" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-lg font-medium hover:from-purple-600 hover:to-pink-700 transition-all flex items-center">
                        <i class="fas fa-tasks mr-2"></i>
                        Bulk Actions
                        <span id="selectedCount" class="ml-2 bg-white/20 px-2 py-1 rounded-full text-xs">0</span>
                        <i class="fas fa-chevron-down ml-2 text-xs"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="bulkActionsDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-purple-100 z-50 hidden">
                        <div class="py-1">
                            <button onclick="bulkApprove()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-700 flex items-center">
                                <i class="fas fa-check mr-2 text-green-600"></i>
                                Approve Selected
                            </button>
                            <button onclick="bulkReject()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 flex items-center">
                                <i class="fas fa-times mr-2 text-orange-600"></i>
                                Reject Selected
                            </button>
                            <hr class="my-1 border-gray-200">
                            <button onclick="bulkDelete()" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-700 flex items-center">
                                <i class="fas fa-trash mr-2 text-red-600"></i>
                                Delete Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filters Section -->
        <div class="flex items-center space-x-4 flex-wrap gap-2 mb-6">
            <!-- Status Filter -->
            <div class="relative">
                <select id="statusFilter" onchange="filterReviews()" 
                        class="appearance-none bg-white border border-purple-200 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Status</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-purple-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Rating Filter -->
            <div class="relative">
                <select id="ratingFilter" onchange="filterReviews()" 
                        class="appearance-none bg-white border border-purple-200 rounded-lg px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Ratings</option>
                    @for($i = 5; $i >= 1; $i--)
                    <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Stars</option>
                    @endfor
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-purple-600">
                    <i class="fas fa-chevron-down text-xs"></i>
                </div>
            </div>

            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <input type="text" id="searchInput" placeholder="Search by name, email or review..." 
                       class="w-full bg-white border border-purple-200 rounded-lg px-4 py-2 pl-10 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                       value="{{ request('search') }}">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 text-purple-600">
                    <i class="fas fa-search text-sm"></i>
                </div>
            </div>
            
            <button onclick="clearFilters()" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                <i class="fas fa-filter-circle-xmark mr-2"></i>
                Clear Filters
            </button>
        </div>

        <!-- Reviews Table -->
        <div class="bg-white rounded-xl shadow-sm border border-purple-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r from-purple-50 to-pink-50 border-b border-purple-100">
                        <tr>
                            <th class="px-6 py-4 text-left">
                                <input type="checkbox" id="selectAll" onchange="toggleSelectAll()" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Rating</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Review</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Item</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Restaurant</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-50">
                        @forelse($reviews as $review)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="review-checkbox rounded border-purple-300 text-purple-600 focus:ring-purple-500" value="{{ $review->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $review->customer_name }}</span>
                                    <span class="text-sm text-gray-500">{{ $review->customer_email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star text-sm {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-600">({{ $review->rating }})</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-900 truncate">{{ $review->review_text }}</p>
                                    @if(strlen($review->review_text) > 50)
                                    <button onclick="showFullReview({{ $review->id }})" class="text-purple-600 hover:text-purple-800 text-xs">Read more</button>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="font-medium text-gray-900">{{ $review->menuItem->name }}</span>
                                    <span class="text-sm text-gray-500">₹{{ number_format($review->menuItem->full_plate_price, 0) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm text-gray-900">{{ $review->menuItem->restaurant->name }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $review->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($review->is_approved)
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Approved
                                </span>
                                @else
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-orange-100 text-orange-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    @if(!$review->is_approved)
                                    <button onclick="approveReview({{ $review->id }})" class="text-green-600 hover:text-green-800 transition-colors" title="Approve">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    @else
                                    <button onclick="rejectReview({{ $review->id }})" class="text-orange-600 hover:text-orange-800 transition-colors" title="Reject">
                                        <i class="fas fa-times-circle"></i>
                                    </button>
                                    @endif
                                    <button onclick="deleteReview({{ $review->id }})" class="text-red-600 hover:text-red-800 transition-colors" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-comments text-4xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg font-medium">No reviews found</p>
                                    <p class="text-gray-400 text-sm mt-1">Get started by adding some menu items for customers to review</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($reviews->hasPages())
            <div class="px-6 py-4 border-t border-purple-100">
                {{ $reviews->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Debounce function to prevent too many requests
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Add event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Add search input event listener with debounce
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        const debouncedSearch = debounce(filterReviews, 500);
        
        searchInput.addEventListener('input', debouncedSearch);
        
        // Add Enter key support for immediate search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                filterReviews();
            }
        });
    }
    
    // Add filter change listeners
    const statusFilter = document.getElementById('statusFilter');
    const ratingFilter = document.getElementById('ratingFilter');
    
    if (statusFilter) {
        statusFilter.addEventListener('change', filterReviews);
    }
    
    if (ratingFilter) {
        ratingFilter.addEventListener('change', filterReviews);
    }
    
    // Add checkbox event listeners to update selected count
    const checkboxes = document.querySelectorAll('.review-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });
});

// Function to update selected count
function updateSelectedCount() {
    const selectedCheckboxes = document.querySelectorAll('.review-checkbox:checked');
    const selectedCount = selectedCheckboxes.length;
    
    // Update single count display
    document.getElementById('selectedCount').textContent = selectedCount;
    
    // Update select all checkbox state
    const selectAll = document.getElementById('selectAll');
    const totalCheckboxes = document.querySelectorAll('.review-checkbox');
    
    if (selectedCount === 0) {
        selectAll.checked = false;
        selectAll.indeterminate = false;
    } else if (selectedCount === totalCheckboxes.length) {
        selectAll.checked = true;
        selectAll.indeterminate = false;
    } else {
        selectAll.checked = false;
        selectAll.indeterminate = true;
    }
}

// Toggle dropdown menu
function toggleDropdown() {
    const dropdown = document.getElementById('bulkActionsDropdown');
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('bulkActionsDropdown');
    const dropdownButton = event.target.closest('button[onclick="toggleDropdown()"]');
    
    if (!dropdownButton && !dropdown.contains(event.target)) {
        dropdown.classList.add('hidden');
    }
});

function filterReviews() {
    const status = document.getElementById('statusFilter').value;
    const rating = document.getElementById('ratingFilter').value;
    const search = document.getElementById('searchInput').value;
    
    const params = new URLSearchParams();
    if (status) params.append('status', status);
    if (rating) params.append('rating', rating);
    if (search) params.append('search', search);
    
    const currentUrl = '{{ route("admin.reviews.index") }}';
    const newUrl = params.toString() ? `${currentUrl}?${params.toString()}` : currentUrl;
    
    window.location.href = newUrl;
}

function clearFilters() {
    window.location.href = '{{ route("admin.reviews.index") }}';
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.review-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
    
    // Update selected count
    updateSelectedCount();
}

function getSelectedReviews() {
    const checkboxes = document.querySelectorAll('.review-checkbox:checked');
    return Array.from(checkboxes).map(cb => cb.value);
}

function bulkApprove() {
    const reviewIds = getSelectedReviews();
    if (reviewIds.length === 0) {
        Swal.fire({
            title: 'No Selection',
            text: 'Please select reviews to approve',
            icon: 'warning'
        });
        return;
    }
    
    // Close dropdown
    document.getElementById('bulkActionsDropdown').classList.add('hidden');
    
    Swal.fire({
        title: 'Approve Selected?',
        text: `Are you sure you want to approve ${reviewIds.length} review(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, approve them!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("admin.reviews.bulk-approve") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ review_ids: reviewIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Approved!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error approving reviews',
                    icon: 'error'
                });
            });
        }
    });
}

function bulkReject() {
    const reviewIds = getSelectedReviews();
    if (reviewIds.length === 0) {
        Swal.fire({
            title: 'No Selection',
            text: 'Please select reviews to reject',
            icon: 'warning'
        });
        return;
    }
    
    // Close dropdown
    document.getElementById('bulkActionsDropdown').classList.add('hidden');
    
    Swal.fire({
        title: 'Reject Selected?',
        text: `Are you sure you want to reject ${reviewIds.length} review(s)?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject them!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("admin.reviews.bulk-reject") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ review_ids: reviewIds })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Rejected!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error rejecting reviews',
                    icon: 'error'
                });
            });
        }
    });
}

function bulkDelete() {
    const reviewIds = getSelectedReviews();
    if (reviewIds.length === 0) {
        showNotification('Please select reviews to delete', 'error');
        return;
    }
    
    // Close dropdown
    document.getElementById('bulkActionsDropdown').classList.add('hidden');
    
    if (confirm(`Are you sure you want to delete ${reviewIds.length} review(s)? This action cannot be undone.`)) {
        fetch('{{ route("admin.reviews.bulk-delete") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ review_ids: reviewIds })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification(data.message, 'success');
                location.reload();
            } else {
                showNotification(data.message, 'error');
            }
        })
        .catch(error => {
            showNotification('Error deleting reviews', 'error');
        });
    }
}

function approveReview(id) {
    Swal.fire({
        title: 'Approve Review?',
        text: 'Are you sure you want to approve this review?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, approve it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.reviews.approve', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Approved!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error approving review',
                    icon: 'error'
                });
            });
        }
    });
}

function rejectReview(id) {
    Swal.fire({
        title: 'Reject Review?',
        text: 'Are you sure you want to reject this review?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#f59e0b',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, reject it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.reviews.reject', ':id') }}`.replace(':id', id), {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Rejected!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error rejecting review',
                    icon: 'error'
                });
            });
        }
    });
}

function deleteReview(id) {
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
            fetch(`{{ route('admin.reviews.destroy', ':id') }}`.replace(':id', id), {
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
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error deleting review',
                    icon: 'error'
                });
            });
        }
    });
}

function showFullReview(id) {
    fetch(`{{ route('admin.reviews.show', ':id') }}`.replace(':id', id))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const review = data.review;
                alert(`Full Review:\n\n${review.review_text}\n\nBy: ${review.customer_name}\nRating: ${review.rating}/5`);
            }
        })
        .catch(error => {
            showNotification('Error loading review', 'error');
        });
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg text-white z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    }`;
    notification.innerHTML = `
        <div class="flex items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
}

function goBack() {
    window.history.back();
}
</script>
@endpush
