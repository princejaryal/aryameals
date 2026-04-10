// Hero carousel
const carousel = document.querySelector('#heroCarousel');

if (carousel) {
    new bootstrap.Carousel(carousel, {
        interval: 4000,
        ride: 'carousel',
        pause: false
    });
}

// Category carousel
const scrollContainer = document.getElementById("categoryScroll");
const nextBtn = document.querySelector(".next-btn");
const prevBtn = document.querySelector(".prev-btn");

const scrollAmount = 170; // match column width

if (nextBtn && scrollContainer) {
    nextBtn.addEventListener("click", () => {
        scrollContainer.scrollBy({
            left: scrollAmount,
            behavior: "smooth"
        });
    });
}

if (prevBtn && scrollContainer) {
    prevBtn.addEventListener("click", () => {
        scrollContainer.scrollBy({
            left: -scrollAmount,
            behavior: "smooth"
        });
    });
}

// Load data when page loads
document.addEventListener('DOMContentLoaded', function() {
    loadData();
});

function loadData() {
    // Load restaurants
    fetch('/api/v1/restaurants')
        .then(response => response.json())
        .then(data => {
            displayRestaurants(data.restaurants || []);
        })
        .catch(error => {
            console.error('Error loading restaurants:', error);
        });
    
}

function displayRestaurants(restaurants) {
    const grid = document.getElementById('restaurantsGrid');
    
    if (!grid) return;
    
    if (restaurants.length === 0) {
        grid.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No restaurants found.</p></div>';
        return;
    }
    
    grid.innerHTML = restaurants.map(restaurant => `
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="restaurant-card" onclick="window.location.href='/restaurant/${restaurant.id}'" style="cursor: pointer;">
                <img src="${restaurant.image_url || 'https://via.placeholder.com/300x200'}" alt="${restaurant.name}" class="card-img-top">
                <div class="restaurant-card-content">
                    <div class="restaurant-header">
                        <h5 class="restaurant-name">${restaurant.name}</h5>
                        <div class="rating-badge">
                            <i class="fas fa-star"></i>
                            <span>${restaurant.rating || '4.5'}</span>
                        </div>
                    </div>
                    <p class="restaurant-category">${restaurant.category || 'Restaurant'}</p>
                    <p class="restaurant-location">
                        <i class="fas fa-map-marker-alt"></i> ${restaurant.city || 'Chamba'}
                    </p>
                </div>
            </div>
        </div>
    `).join('');
}

function displayCategories(categories) {
    const filters = document.getElementById('categoryFilters');
    
    if (!filters) return;
    
    if (categories.length === 0) {
        filters.innerHTML = '';
        return;
    }
    
    filters.innerHTML = categories.map(category => `
        <button class="filter-btn">${category}</button>
    `).join('');
}

function displayRecommendedItems(items) {
    const container = document.getElementById('recommendedItems');
    
    if (!container) return;
    
    if (items.length === 0) {
        container.innerHTML = '<div class="col-12 text-center py-5"><p class="text-muted">No recommended items found.</p></div>';
        return;
    }
    
    container.innerHTML = items.map(item => `
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="food-card" onclick="addToCart(${item.id})">
                <div class="position-relative">
                    <img src="${item.image_url || 'https://via.placeholder.com/300x200'}" alt="${item.name}" class="card-img-top">
                    ${item.is_recommended ? '<span class="badge-recommended">Recommended</span>' : ''}
                </div>
                <div class="food-card-content">
                    <h5 class="food-card-title">${item.name}</h5>
                    <p class="food-card-subtitle">${item.restaurant_name || 'Restaurant'}</p>
                    <div class="food-card-footer">
                        <span class="food-price">₹${item.price}</span>
                        <span class="food-meta">${item.spice_level_badge || '🟢 Mild'}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <span class="food-meta">
                            <i class="fas fa-clock me-1"></i>${item.preparation_time || 20} min
                        </span>
                        <button class="add-btn">
                            <i class="fas fa-plus me-1"></i>Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `).join('');
}

// Cart functionality
window.cart = window.cart || [];
let cartCount = 0;

function updateCartCount() {
    const cartCountElements = document.querySelectorAll('#cart-count');
    cartCountElements.forEach(element => {
        element.textContent = cartCount;
    });
}

function addToCart(itemId = null) {
    window.cart.push(itemId || Date.now());
    cartCount++;
    updateCartCount();
    showNotification('Item added to cart!', 'success');
}

function toggleCart() {
    const modal = document.getElementById('cartModal');
    const overlay = document.getElementById('cartOverlay');
    
    if (modal && overlay) {
        modal.classList.toggle('show');
        overlay.classList.toggle('show');
    }
}

function proceedToCheckout() {
    // Check if cart has items
    const cartItems = document.querySelectorAll('#cartItemsContainer .cart-item');
    
    if (cartItems.length === 0 || cartItems[0].textContent.includes('empty')) {
        showNotification('Your cart is empty. Add items before checkout.', 'error');
        return;
    }
    
    // Redirect to checkout page
    window.location.href = '/checkout';
}

function showNotification(message, type = 'success') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.alert');
    existingNotifications.forEach(notification => notification.remove());
    
    // Create new notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type} position-fixed top-0 end-0 m-3 shadow-lg`;
    notification.style.zIndex = '9999';
    notification.style.minWidth = '300px';
    notification.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
            <span>${message}</span>
        </div>
    `;
    
    // Add to page
    document.body.appendChild(notification);
    
    // Animate in
    notification.style.opacity = '0';
    notification.style.transform = 'translateX(100%)';
    setTimeout(() => {
        notification.style.transition = 'all 0.3s ease';
        notification.style.opacity = '1';
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}
