@extends('admin.layouts.app')

@section('title', 'Arya Meals - Order Management')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <div>
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Order Management</h1>
                    <p class="text-sm text-purple-500 mt-1">Manage customer orders and delivery</p>
                </div>
            </div>
            <div class="flex items-center space-x-4">
            </div>
        </section>

        <!-- Orders Table -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <div class="flex justify-between items-center">
                    <h2 class="text-lg font-bold text-purple-900">Recent Orders</h2>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-purple-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-purple-700 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-purple-100">
                        @forelse($orders as $order)
                        <tr class="hover:bg-purple-50 transition-colors order-row">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-purple-900">{{ $order->customer_name }}</p>
                                    <p class="text-sm text-purple-500">{{ $order->customer_phone }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-bold text-purple-900">₹{{ number_format($order->total_amount, 0) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="viewOrderDetails({{ $order->id }})" class="text-purple-600 hover:text-purple-800" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-8 text-center text-purple-500">
                                <i class="fas fa-inbox text-4xl mb-2"></i>
                                <p>No orders found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($orders->hasPages())
            <div class="px-6 py-4 border-t border-purple-100">
                {{ $orders->links() }}
            </div>
            @endif
        </section>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-purple-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-purple-900">Order Details</h3>
                        <button onclick="closeOrderModal()" class="text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="orderDetails" class="p-6">
                    <!-- Order details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function goBack() {
            window.location.href = '{{ route("admin.dashboard") }}';
        }


        function viewOrderDetails(orderId) {
            window.location.href = `/admin/orders/${orderId}`;
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.add('hidden');
        }

        function updateOrderStatus(orderId) {
            const newStatus = prompt('Enter new status (pending, preparing, ready, delivered, cancelled):');
            if (newStatus) {
                fetch(`/admin/orders/${orderId}/status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ status: newStatus })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Error updating status');
                    }
                });
            }
        }

        function filterOrders() {
            const selectedStatus = document.getElementById('statusFilter').value;
            const orderRows = document.querySelectorAll('.order-row');
            
            orderRows.forEach(row => {
                const orderStatus = row.getAttribute('data-status');
                
                if (selectedStatus === 'all' || orderStatus === selectedStatus) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
@endsection
