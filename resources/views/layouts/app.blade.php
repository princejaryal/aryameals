<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'AryaMeals - Food Delivery')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merienda:wght@400;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS - Load AFTER Bootstrap to override -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Navigation Header -->
    @include('layouts.header')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('layouts.footer')
    
    <!-- Slide-out Cart Sidebar (Desktop Only) -->
    <div id="cartSidebar" class="cart-sidebar d-none d-lg-block">
        <div class="cart-sidebar-overlay" onclick="closeCartSidebar()"></div>
        <div class="cart-sidebar-content">
            <div class="cart-sidebar-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Shopping Cart
                </h5>
                <button class="btn-close" onclick="closeCartSidebar()"></button>
            </div>
            
            <div class="cart-sidebar-body">
                <div id="cartItemsContainer">
                    <!-- Cart items will be loaded here -->
                    <div class="text-center py-4">
                        <div class="spinner-border" style="color: #ff6b35;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="cart-sidebar-footer">
                <div class="cart-summary mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <span id="sidebarSubtotal">₹0</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <h6>Total</h6>
                        <h6 class="text-orange" id="sidebarTotal">₹0</h6>
                    </div>
                </div>
                
                <div class="cart-actions">
                    <a href="{{ route('cart.index') }}" class="btn btn-sm w-100 mb-2" style="border:1px solid #ff8c2b;color:#ff8c2b">
                        <i class="fas fa-eye me-2"></i>View Cart Details
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    <script src="{{ asset('js/script.js') }}"></script>
    
    <!-- Global Cart Functions -->
    <script>
        // Add to cart from card component
        function addToCartFromCard(itemId, itemName, quantity, price, restaurant, image, unit) {
            event.stopPropagation();
            
            // Get actual quantity from input
            const actualQuantity = document.getElementById(`qty-${itemId}`).value;
            
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    menu_item_id: itemId,
                    quantity: parseInt(actualQuantity),
                    portion_size: 'full' // Default to full portion for card adds
                })
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    showNotification(data.message, 'success');
                    updateCartCount(data.cart_count);
                    // Reset quantity to 1
                    document.getElementById(`qty-${itemId}`).value = 1;
                } else {
                    showNotification(data.message || 'Error adding to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                showNotification('Network error. Please try again.', 'error');
            });
        }

        // Update cart count in header
        function updateCartCount(count) {
            // Update desktop cart count
            const cartCountElement = document.getElementById('cartCount');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                // Show animation
                cartCountElement.classList.add('animate-bounce');
                setTimeout(() => {
                    cartCountElement.classList.remove('animate-bounce');
                }, 500);
            }
            
            // Update mobile cart count
            const cartCountMobileElement = document.getElementById('cartCountMobile');
            if (cartCountMobileElement) {
                cartCountMobileElement.textContent = count;
                // Show animation
                cartCountMobileElement.classList.add('animate-bounce');
                setTimeout(() => {
                    cartCountMobileElement.classList.remove('animate-bounce');
                }, 500);
            }
        }

        // Show notification with tooltip style
        function showNotification(message, type = 'success') {
            // Remove any existing notifications
            const existingNotifications = document.querySelectorAll('.custom-tooltip');
            existingNotifications.forEach(notif => notif.remove());
            
            // Create tooltip-style notification
            const notification = document.createElement('div');
            notification.className = `custom-tooltip custom-tooltip-${type}`;
            notification.innerHTML = `
                <div class="tooltip-content">
                    <div class="tooltip-icon">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i>
                    </div>
                    <div class="tooltip-text">${message}</div>
                </div>
                <div class="tooltip-arrow"></div>
            `;
            
            document.body.appendChild(notification);
            
            // Trigger show animation
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);
            
            // Auto remove after 2 seconds
            setTimeout(() => {
                notification.classList.add('tooltip-hiding');
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 2000);
        }

        // Load cart count on page load
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/cart/summary')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount(data.cart_count);
                    }
                })
                .catch(error => {
                    console.log('Could not load cart count');
                });
        });

        // Cart Sidebar Functions
        function openCartSidebar(event) {
            event.preventDefault();
            const sidebar = document.getElementById('cartSidebar');
            sidebar.classList.add('active');
            loadCartItems();
        }

        function closeCartSidebar() {
            const sidebar = document.getElementById('cartSidebar');
            sidebar.classList.remove('active');
        }

        function loadCartItems() {
            console.log('Loading cart items...');
            fetch('/cart/summary')
                .then(response => {
                    console.log('Response status:', response.status);
                    return response.json();
                })
                .then(data => {
                    console.log('Cart data received:', data);
                    if (data.success) {
                        console.log('Items to render:', data.items);
                        renderCartItems(data.items);
                        updateSidebarSummary(data.cart_count, data.cart_subtotal, data.fees, data.has_fees, data.cart_total);
                    } else {
                        console.error('Cart load failed:', data.message);
                        // Show error message
                        document.getElementById('cartItemsContainer').innerHTML = `
                            <div class="text-center py-4">
                                <i class="fas fa-exclamation-triangle text-warning mb-2"></i>
                                <p class="text-muted">Error loading cart items</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading cart items:', error);
                    // Show error message
                    document.getElementById('cartItemsContainer').innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-danger mb-2"></i>
                            <p class="text-muted">Network error loading cart</p>
                        </div>
                    `;
                });
        }

        function renderCartItems(items) {
            console.log('Rendering cart items:', items);
            const container = document.getElementById('cartItemsContainer');
            
            if (!items || items.length === 0) {
                console.log('Cart is empty or items not available');
                container.innerHTML = `
                    <div class="empty-cart-sidebar">
                        <i class="fas fa-shopping-cart"></i>
                        <h6>Your cart is empty</h6>
                        <p>Add some delicious items to get started!</p>
                        <button class="btn btn-orange btn-sm p-2" onclick="closeCartSidebar()">
                            <i class="fas fa-home me-2 fs-5"></i>Continue Shopping
                        </button>
                    </div>
                `;
                return;
            }

            console.log('Rendering', items.length, 'items');
            let html = '';
            items.forEach((item, index) => {
                console.log(`Item ${index}:`, item);
                console.log(`Menu item data:`, item.menu_item);
                
                // Check if menu_item exists and has data
                let itemName = 'Unknown Item';
                let restaurantName = 'Unknown Restaurant';
                let itemPrice = '₹0';
                let imagePath = '{{ asset("images/placeholder.jpg") }}';
                
                if (item.menu_item) {
                    itemName = item.menu_item.name || 'Unknown Item';
                    itemPrice = item.formatted_price || '₹' + (item.price || 0);
                    
                    if (item.menu_item.restaurant) {
                        restaurantName = item.menu_item.restaurant.name || 'Unknown Restaurant';
                    }
                    
                    if (item.menu_item.image) {
                        // Try the correct path - images are in public/images/home/
                        imagePath = `/images/home/${item.menu_item.image}`;
                        console.log('Trying image path:', imagePath);
                    } else {
                        // Use a default food image as placeholder
                        imagePath = '/images/home/27.jpg';
                    }
                }
                
                console.log(`Final data for item ${index}:`, {
                    name: itemName,
                    restaurant: restaurantName,
                    price: itemPrice,
                    image: imagePath
                });
                
                html += `
                    <div class="cart-item-sidebar">
                        <img src="${imagePath}" 
                             alt="${itemName}" 
                             class="cart-item-image"
                             onerror="this.onerror=null; this.src='/images/home/27.jpg';">
                        <div class="cart-item-details">
                            <div class="cart-item-name">${itemName}</div>
                            <div class="cart-item-restaurant">${restaurantName}</div>
                            <div class="cart-item-price">${itemPrice}</div>
                            <div class="cart-item-quantity">
                                <button class="cart-quantity-btn" onclick="updateSidebarQuantity(${item.id}, -1)">−</button>
                                <span class="cart-quantity-display">${item.quantity || 1}</span>
                                <button class="cart-quantity-btn" onclick="updateSidebarQuantity(${item.id}, 1)">+</button>
                                <button class="cart-item-remove" onclick="removeSidebarItem(${item.id})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            console.log('Final HTML:', html);
            container.innerHTML = html;
        }

        function updateSidebarSummary(count, subtotal, fees, hasFees, total) {
            // Clear existing fee displays
            const feeContainer = document.querySelector('.cart-summary');
            const subtotalElement = document.getElementById('sidebarSubtotal');
            const totalElement = document.getElementById('sidebarTotal');
            
            // Update subtotal
            if (subtotalElement) {
                subtotalElement.textContent = subtotal;
            }
            
            // Remove old fee elements (keep subtotal and total)
            const existingFees = feeContainer.querySelectorAll('.fee-item');
            existingFees.forEach(fee => fee.remove());
            
            // Add dynamic fees
            if (hasFees && fees.length > 0) {
                const hrElement = feeContainer.querySelector('hr');
                
                fees.forEach(fee => {
                    const feeDiv = document.createElement('div');
                    feeDiv.className = 'fee-item d-flex justify-content-between mb-2';
                    feeDiv.innerHTML = `
                        <span>${fee.display_label}</span>
                        <span>${fee.formatted_amount}</span>
                    `;
                    
                    // Insert before hr element
                    feeContainer.insertBefore(feeDiv, hrElement);
                });
            }
            
            // Update total
            if (totalElement) {
                totalElement.textContent = total;
            }
        }

        function updateSidebarQuantity(itemId, change) {
            // Find the correct quantity display for this item
            const itemElement = document.querySelector(`button[onclick*="updateSidebarQuantity(${itemId}"]`).closest('.cart-item-sidebar');
            const display = itemElement.querySelector('.cart-quantity-display');
            const currentQuantity = parseInt(display.textContent);
            const newQuantity = currentQuantity + change;
            
            if (newQuantity >= 1 && newQuantity <= 99) {
                fetch(`/cart/${itemId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        quantity: newQuantity
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartCount(data.cart_count);
                        loadCartItems(); // Reload cart items
                        showNotification(data.message, 'success');
                    } else {
                        showNotification('Error updating cart', 'error');
                    }
                })
                .catch(error => {
                    showNotification('Error updating cart', 'error');
                });
            }
        }

        function removeSidebarItem(itemId) {
            // Remove item immediately without confirmation
            fetch(`/cart/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateCartCount(data.cart_count);
                    loadCartItems(); // Reload cart items
                    showNotification(data.message, 'success');
                    
                    // Close sidebar if cart is empty
                    if (data.cart_count == 0) {
                        setTimeout(() => {
                            closeCartSidebar();
                        }, 1500);
                    }
                } else {
                    showNotification('Error removing item', 'error');
                }
            })
            .catch(error => {
                showNotification('Error removing item', 'error');
            });
        }


        // Update quantity for card component
        function updateQuantity(itemId, change, basePrice, unit) {
            event.stopPropagation();
            
            const qtyInput = document.getElementById(`qty-${itemId}`);
            let currentQty = parseInt(qtyInput.value) || 1;
            
            if (change === 0) {
                // Input changed directly
                currentQty = parseInt(qtyInput.value) || 1;
            } else {
                currentQty += change;
            }
            
            // Ensure quantity is within bounds
            if (currentQty < 1) currentQty = 1;
            if (currentQty > 99) currentQty = 99;
            
            // Update input value
            qtyInput.value = currentQty;
            
            // Update price display
            const newPrice = basePrice * currentQty;
            const priceElement = document.getElementById(`price-${itemId}`);
            if (priceElement) {
                priceElement.textContent = newPrice.toLocaleString('en-IN', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            }
            
            // Update unit display if unit exists
            if (unit) {
                const unitElement = document.getElementById(`unit-${itemId}`);
                if (unitElement) {
                    unitElement.innerHTML = `<i class="fas fa-weight"></i> ${unit} × ${currentQty}`;
                }
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>
