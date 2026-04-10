// Quantity selector functionality for cards
window.basePrices = window.basePrices || {};

// Initialize base prices for category pages
function initializeCategoryPrices(items) {
    items.forEach((item) => {
        window.basePrices[item.id] = item.price;
    });
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", function () {
    // Check if we have category data from Laravel
    if (typeof window.categoryItems !== "undefined") {
        initializeCategoryPrices(window.categoryItems);
    }
});

function updateQuantity(itemId, change, basePrice, unit) {
    const qtyInput = document.getElementById("qty-" + itemId);
    const priceSpan = document.getElementById("price-" + itemId);

    if (!qtyInput || !priceSpan) {
        console.error("Elements not found for item:", itemId);
        return;
    }

    // Handle undefined/null basePrice
    if (basePrice === undefined || basePrice === null || basePrice === 0) {
        console.warn("Price not available for item:", itemId);
        priceSpan.textContent = "Price not available";
        return;
    }

    let currentQty = parseInt(qtyInput.value) || 1;

    // Calculate new quantity
    let newQty;
    if (change === 0) {
        // Manual input
        newQty = Math.max(1, Math.min(99, parseInt(qtyInput.value) || 1));
    } else {
        // Plus/minus button
        newQty = Math.max(1, Math.min(99, currentQty + change));
    }

    // Update quantity input
    qtyInput.value = newQty;

    // Calculate new price
    const newPrice = basePrice * newQty;

    // Update price display
    priceSpan.textContent = newPrice.toFixed(2);

    // Update unit display for grocery items
    if (unit) {
        const unitElement = document.getElementById("unit-" + itemId);
        if (unitElement) {
            const unitParts = unit.split(" ");
            if (unitParts.length >= 2) {
                const baseQty = parseInt(unitParts[0]);
                const unitType = unitParts.slice(1).join(" ");

                if (newQty > 1 && baseQty > 0) {
                    const totalQty = baseQty * newQty;
                    unitElement.innerHTML =
                        '<i class="fas fa-weight"></i> ' +
                        totalQty +
                        " " +
                        unitType;
                } else {
                    unitElement.innerHTML =
                        '<i class="fas fa-weight"></i> ' + unit;
                }
            }
        }
    }
}

// Cart functionality for cards
let cardCart = [];

function addToCart(id, name, quantity, basePrice, restaurant, image, unit) {
    // Handle undefined/null basePrice
    if (basePrice === undefined || basePrice === null || basePrice === 0) {
        console.warn('Price not available for item:', id);
        showNotification('Price not available for this item');
        return;
    }

    // Send to server
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            item_id: id,
            quantity: quantity,
            portion_size: 'full', // Default to full for all items
            restaurant: restaurant,
            unit: unit
        })
    })
    .then(data => data.json())
    .then(data => {
        if(data.success){
            // Update local cart
            const price = basePrice * quantity;
            const item = cardCart.find(i=>i.id===id);
            if(item){
                item.quantity += parseInt(quantity);
                item.totalPrice = item.basePrice * item.quantity;
            } else {
                cardCart.push({
                    id,
                    name,
                    quantity: parseInt(quantity),
                    basePrice,
                    totalPrice: price,
                    restaurant,
                    image,
                    unit
                });
            }
            updateCartUI();
            showNotification(name + " added to cart!");
            // Update cart count badges immediately
            if (data.cart_count !== undefined) {
                const cartCountEl = document.getElementById("cartCount");
                const cartCountMobileEl = document.getElementById("cartCountMobile");
                if (cartCountEl) cartCountEl.textContent = data.cart_count;
                if (cartCountMobileEl) cartCountMobileEl.textContent = data.cart_count;
            }
        } else {
            showNotification(data.message || "Failed to add to cart");
        }
    })
    .catch((error) => {
        console.error("Error:", error);
        showNotification("Network error. Please try again.");
    });
}

function updateCartUI() {
    const el = document.querySelector(".cart-count");
    if (el) {
        // Combine both carts for total count
        const mainCartCount = window.cart ? window.cart.length : 0;
        const cardCartCount = cardCart.reduce((t, i) => t + i.quantity, 0);
        el.innerText = mainCartCount + cardCartCount;
    }
}

function showNotification(msg) {
    const notification = document.createElement("div");
    notification.className = "notification";
    notification.textContent = msg;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: #28a745;
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        z-index: 9999;
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;

    document.body.appendChild(notification);

    setTimeout(() => {
        notification.style.transition = "opacity 0.3s";
        notification.style.opacity = "0";
        setTimeout(() => notification.remove(), 300);
    }, 2000);
}
