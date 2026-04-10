@extends('admin.layouts.app')

@section('title', 'Arya Meals - Create Order')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Header Section -->
        <section class="flex items-center space-x-4">
            <button onclick="goBack()" class="p-2 rounded-lg bg-purple-100 text-purple-600 hover:bg-purple-200">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div>
                <h1 class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Create Order</h1>
                <p class="text-sm text-purple-500 mt-1">Create new customer order</p>
            </div>
        </section>

        <!-- Create Order Form -->
        <section class="bg-white rounded-2xl shadow-lg border border-purple-200">
            <div class="p-6 border-b border-purple-100">
                <h2 class="text-lg font-bold text-purple-900">Order Information</h2>
            </div>
            
            <form id="createOrderForm" class="p-6">
                @csrf
                
                <!-- Customer Information -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Customer Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Customer Name <span class="text-red-600">*</span></label>
                            <input type="text" name="customer_name" required
                                   class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Enter customer name">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Phone Number <span class="text-red-600">*</span></label>
                            <input type="tel" name="customer_phone" required
                                   class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                   placeholder="Enter phone number">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-purple-700 mb-2">Delivery Address <span class="text-red-600">*</span></label>
                            <textarea name="customer_address" rows="3" required
                                      class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Enter delivery address"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Restaurant Selection -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Restaurant Selection</h3>
                    
                    <div>
                        <label class="block text-sm font-medium text-purple-700 mb-2">Restaurant <span class="text-red-600">*</span></label>
                        <select name="restaurant_id" id="restaurantSelect" onchange="loadMenuItems(this.value)" required
                                class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="">Select Restaurant</option>
                            @foreach($restaurants as $restaurant)
                                <option value="{{ $restaurant->id }}">{{ $restaurant->name }} - {{ $restaurant->city }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Order Items</h3>
                    
                    <div id="orderItemsContainer" class="space-y-4">
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                            <p class="text-purple-500">Select a restaurant to view menu items</p>
                        </div>
                    </div>
                    
                    <button type="button" onclick="addOrderItem()" 
                            class="mt-4 px-4 py-2 bg-purple-100 text-purple-600 rounded-lg hover:bg-purple-200 transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Add Item
                    </button>
                </div>

                <!-- Order Settings -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Order Settings</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Delivery Type</label>
                            <select name="delivery_type" 
                                    class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Delivery</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-purple-700 mb-2">Payment Method</label>
                            <select name="payment_method" 
                                    class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="cash">Cash on Delivery</option>
                                <option value="online">Online Payment</option>
                                <option value="card">Credit/Debit Card</option>
                            </select>
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-purple-700 mb-2">Order Notes</label>
                            <textarea name="order_notes" rows="3"
                                      class="w-full px-4 py-3 rounded-lg border border-purple-200 bg-white text-slate-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"
                                      placeholder="Special instructions or notes"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="mb-8">
                    <h3 class="text-md font-semibold text-purple-800 mb-4 pb-2 border-b border-purple-100">Order Summary</h3>
                    
                    <div class="p-4 bg-purple-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-purple-700">Subtotal:</span>
                            <span id="subtotal" class="font-medium text-purple-900">₹0</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-purple-700">Delivery Fee:</span>
                            <span id="deliveryFee" class="font-medium text-purple-900">₹0</span>
                        </div>
                        <div class="flex justify-between items-center pt-2 border-t border-purple-200">
                            <span class="text-lg font-bold text-purple-900">Total:</span>
                            <span id="totalAmount" class="text-xl font-bold text-purple-900">₹0</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-lg font-medium hover:from-purple-700 hover:to-pink-700 transition-all hover:scale-105 transform shadow-lg">
                        <i class="fas fa-save mr-2"></i>
                        Create Order
                    </button>
                    <button type="button" onclick="goBack()" class="flex-1 px-6 py-3 bg-gradient-to-r from-slate-100 to-slate-200 text-slate-700 rounded-lg font-medium hover:from-slate-200 hover:to-slate-300 transition-all hover:scale-105 transform">
                        <i class="fas fa-times mr-2"></i>
                        Cancel
                    </button>
                </div>
            </form>
        </section>
    </div>

    <!-- Menu Item Modal -->
    <div id="menuItemModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-purple-100">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-purple-900">Select Menu Items</h3>
                        <button onclick="closeMenuItemModal()" class="text-purple-600 hover:text-purple-800">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                </div>
                <div id="menuItemsList" class="p-6">
                    <!-- Menu items will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        let orderItems = [];
        let menuItems = [];

        function goBack() {
            window.location.href = '{{ route("admin.orders.index") }}';
        }

        function loadMenuItems(restaurantId) {
            if (!restaurantId) {
                document.getElementById('orderItemsContainer').innerHTML = `
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                        <p class="text-purple-500">Select a restaurant to view menu items</p>
                    </div>
                `;
                return;
            }

            fetch(`/admin/restaurants/${restaurantId}/menu-items`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    menuItems = data.menu_items;
                    displayOrderItems();
                }
            });
        }

        function addOrderItem() {
            document.getElementById('menuItemModal').classList.remove('hidden');
            if (menuItems.length > 0) {
                displayMenuItems();
            }
        }

        function displayMenuItems() {
            const container = document.getElementById('menuItemsList');
            container.innerHTML = menuItems.map(item => `
                <div class="border border-purple-200 rounded-lg p-4 mb-4 hover:bg-purple-50 cursor-pointer" onclick="selectMenuItem(${item.id}, '${item.name}', ${item.half_plate_price}, ${item.full_plate_price}, '${item.image}')">
                    <div class="flex items-center space-x-4">
                        ${item.image ? `<img src="/storage/menu-items/${item.image}" alt="${item.name}" class="w-20 h-20 rounded-lg object-cover">` : '<div class="w-20 h-20 rounded-lg bg-purple-100 flex items-center justify-center"><i class="fas fa-utensils text-purple-600"></i></div>'}
                        <div class="flex-1">
                            <h4 class="font-medium text-purple-900">${item.name}</h4>
                            <p class="text-sm text-purple-600">${item.description || 'No description available'}</p>
                            <div class="flex items-center space-x-4 mt-2">
                                <span class="text-purple-900">Half: ₹${item.half_plate_price}</span>
                                <span class="text-purple-900">Full: ₹${item.full_plate_price}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function selectMenuItem(id, name, halfPrice, fullPrice, image) {
            const price = prompt(`Select portion size for ${name}:\n1. Half Plate - ₹${halfPrice}\n2. Full Plate - ₹${fullPrice}\nEnter 1 or 2:`);
            
            if (price === '1') {
                orderItems.push({
                    id: id,
                    name: name,
                    price: halfPrice,
                    quantity: 1,
                    image: image
                });
            } else if (price === '2') {
                orderItems.push({
                    id: id,
                    name: name,
                    price: fullPrice,
                    quantity: 1,
                    image: image
                });
            }
            
            closeMenuItemModal();
            displayOrderItems();
            updateOrderSummary();
        }

        function closeMenuItemModal() {
            document.getElementById('menuItemModal').classList.add('hidden');
        }

        function displayOrderItems() {
            const container = document.getElementById('orderItemsContainer');
            
            if (orderItems.length === 0) {
                container.innerHTML = `
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                        <p class="text-purple-500">No items added to order yet</p>
                    </div>
                `;
                return;
            }
            
            container.innerHTML = orderItems.map((item, index) => `
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        ${item.image ? `<img src="/storage/menu-items/${item.image}" alt="${item.name}" class="w-16 h-16 rounded-lg object-cover">` : '<div class="w-16 h-16 rounded-lg bg-purple-100 flex items-center justify-center"><i class="fas fa-utensils text-purple-600"></i></div>'}
                        <div>
                            <h4 class="font-medium text-purple-900">${item.name}</h4>
                            <p class="text-sm text-purple-600">₹${item.price} each</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <input type="number" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)" 
                               class="w-20 px-2 py-1 border border-purple-200 rounded text-center">
                        <button type="button" onclick="removeOrderItem(${index})" 
                                class="px-2 py-1 bg-red-100 text-red-600 rounded hover:bg-red-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function updateQuantity(index, quantity) {
            orderItems[index].quantity = parseInt(quantity);
            updateOrderSummary();
        }

        function removeOrderItem(index) {
            orderItems.splice(index, 1);
            displayOrderItems();
            updateOrderSummary();
        }

        function updateOrderSummary() {
            const subtotal = orderItems.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            const deliveryFee = subtotal > 0 ? 40 : 0; // ₹40 delivery fee
            const total = subtotal + deliveryFee;
            
            document.getElementById('subtotal').textContent = `₹${subtotal}`;
            document.getElementById('deliveryFee').textContent = `₹${deliveryFee}`;
            document.getElementById('totalAmount').textContent = `₹${total}`;
        }

        // Form submission
        document.getElementById('createOrderForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (orderItems.length === 0) {
                alert('Please add at least one item to the order');
                return;
            }
            
            const formData = new FormData(this);
            formData.set('items', JSON.stringify(orderItems));
            formData.set('total_amount', document.getElementById('totalAmount').textContent.replace('₹', ''));
            
            fetch('{{ route("admin.orders.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '{{ route("admin.orders.index") }}';
                } else {
                    alert('Error creating order: ' + (data.message || 'Unknown error'));
                }
            });
        });
    </script>
@endsection
