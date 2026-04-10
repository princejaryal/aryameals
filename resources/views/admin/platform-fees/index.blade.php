@extends('admin.layouts.app')

@section('title', 'Platform Fees Management - AryaMeals Admin')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="window.history.length > 1 ? window.history.back() : (window.location.href = '{{ route('admin.dashboard') }}')" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Platform Fees Management</h1>
                    <p class="text-sm text-purple-500 mt-1">Manage platform fees, taxes, and delivery charges</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.platform-fees.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors flex items-center space-x-2">
                    <i class="fas fa-plus"></i>
                    <span>Add New Fee</span>
                </a>
            </div>
        </section>

        <!-- Fees Table -->
        <section class="bg-white rounded-xl shadow-sm border border-purple-100">
            <div class="p-6 border-b border-purple-100">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-purple-900">All Platform Fees</h2>
                    <div class="flex items-center space-x-3">
                        <!-- Bulk Actions Dropdown -->
                        <div class="relative">
                            <button class="px-3 py-2 bg-purple-100 text-purple-700 rounded-lg hover:bg-purple-200 transition-colors flex items-center space-x-2 text-sm" onclick="document.getElementById('bulkDropdown').classList.toggle('hidden')">
                                <i class="fas fa-tasks"></i>
                                <span>Bulk Actions</span>
                                <span id="selectedCount" class="ml-2 px-2 py-1 bg-purple-600 text-white text-xs rounded-full">0</span>
                            </button>
                            <div id="bulkDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-purple-100 z-10">
                                <a href="#" onclick="bulkAction('activate')" class="block px-4 py-2 text-sm text-purple-700 hover:bg-purple-50">
                                    <i class="fas fa-check text-green mr-2"></i>Activate Selected
                                </a>
                                <a href="#" onclick="bulkAction('deactivate')" class="block px-4 py-2 text-sm text-purple-700 hover:bg-purple-50">
                                    <i class="fas fa-times text-orange mr-2"></i>Deactivate Selected
                                </a>
                                <hr class="border-purple-100">
                                <a href="#" onclick="bulkAction('delete')" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                    <i class="fas fa-trash mr-2"></i>Delete Selected
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">
                                <input type="checkbox" id="selectAll" class="rounded border-purple-300 text-purple-600 focus:ring-purple-500">
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Fee Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Fee Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Calculation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Amount</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-100">
                        @forelse($fees as $fee)
                        <tr class="hover:bg-purple-50">
                            <td class="px-6 py-4">
                                <input type="checkbox" class="fee-checkbox rounded border-purple-300 text-purple-600 focus:ring-purple-500" value="{{ $fee->id }}">
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    {{ $fee->fee_type }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="text-sm font-medium text-purple-900">{{ $fee->fee_name }}</div>
                                    @if($fee->description)
                                        <div class="text-sm text-purple-500">{{ Str::limit($fee->description, 50) }}</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($fee->fee_type_calculation === 'fixed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Fixed</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Percentage</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($fee->fee_type_calculation === 'fixed')
                                    <span class="text-sm font-semibold text-green-600">{{ $fee->formatted_amount }}</span>
                                @else
                                    <span class="text-sm font-semibold text-blue-600">{{ $fee->fee_percentage }}%</span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <input type="checkbox" 
                                           class="rounded border-purple-300 text-purple-600 focus:ring-purple-500"
                                           {{ $fee->is_active ? 'checked' : '' }}
                                           onchange="toggleStatus({{ $fee->id }})">
                                    <span class="ml-2 text-sm {{ $fee->is_active ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $fee->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.platform-fees.edit', $fee) }}" 
                                       class="text-purple-600 hover:text-purple-900">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button class="text-red-600 hover:text-red-900" 
                                            onclick="deleteFee({{ $fee->id }})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-coins text-4xl text-purple-300 mb-4"></i>
                                    <h3 class="text-lg font-medium text-purple-900 mb-2">No platform fees found</h3>
                                    <p class="text-purple-500 mb-4">Get started by creating your first platform fee</p>
                                    <a href="{{ route('admin.platform-fees.create') }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors inline-flex items-center space-x-2">
                                        <i class="fas fa-plus"></i>
                                        <span>Add Your First Fee</span>
                                    </a>
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

@push('scripts')
<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Toggle fee status
function toggleStatus(feeId) {
    fetch(`/admin/platform-fees/${feeId}/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            location.reload();
        }
    })
    .catch(error => {
        showNotification('Error updating fee status', 'error');
    });
}

// Delete fee
function deleteFee(feeId) {
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
            fetch(`/admin/platform-fees/${feeId}`, {
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
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error deleting fee',
                    icon: 'error'
                });
            });
        }
    });
}

// Select all checkbox
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.fee-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateSelectedCount();
});

// Update selected count
document.querySelectorAll('.fee-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', updateSelectedCount);
});

function updateSelectedCount() {
    const selected = document.querySelectorAll('.fee-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = selected;
}

// Bulk action
function bulkAction(action) {
    const selectedIds = Array.from(document.querySelectorAll('.fee-checkbox:checked'))
        .map(cb => cb.value);
    
    if (selectedIds.length === 0) {
        Swal.fire({
            title: 'No Selection',
            text: 'Please select fees to perform bulk action',
            icon: 'warning'
        });
        return;
    }
    
    const confirmMessages = {
        'activate': 'Are you sure you want to activate selected fees?',
        'deactivate': 'Are you sure you want to deactivate selected fees?',
        'delete': 'Are you sure you want to delete selected fees? This action cannot be undone.'
    };
    
    const titles = {
        'activate': 'Activate Selected?',
        'deactivate': 'Deactivate Selected?',
        'delete': 'Delete Selected?'
    };
    
    Swal.fire({
        title: titles[action],
        text: confirmMessages[action],
        icon: action === 'delete' ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonColor: action === 'delete' ? '#ef4444' : '#22c55e',
        cancelButtonColor: '#6b7280',
        confirmButtonText: action === 'delete' ? 'Yes, delete them!' : 'Yes, proceed!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('/admin/platform-fees/bulk-action', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    action: action,
                    fee_ids: selectedIds
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'Error performing bulk action',
                    icon: 'error'
                });
            });
        }
    });
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed top-0 start-50 translate-middle-x mt-3`;
    notification.style.zIndex = '9999';
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>${message}
    `;
    
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}
</script>
@endpush
