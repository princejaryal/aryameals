<!-- Header Component -->
<header class="glass-effect h-16 border-b border-slate-200/50 flex items-center justify-between px-6 shadow-sm header-fixed">
    <div class="flex items-center space-x-4">
    </div>
    <div class="flex items-center space-x-4">
        <div class="relative">
            <button onclick="toggleNotifications()" class="relative h-10 w-10 rounded-xl bg-gradient-to-r from-purple-100 to-pink-100 flex items-center justify-center text-purple-600 hover:from-purple-200 hover:to-pink-200 transition-all hover:scale-105 shadow-sm">
                <i class="fas fa-bell"></i>
                <span id="notificationBadge" class="absolute -top-1 -right-1 h-3 w-3 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 border-2 border-white animate-pulse hidden"></span>
            </button>
            
            <!-- Notifications Dropdown -->
            <div id="notificationDropdown" class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-purple-200 hidden z-50">
                <div class="p-4 border-b border-purple-100">
                    <h3 class="font-bold text-purple-800 text-sm">Order Notifications</h3>
                    <p class="text-xs text-purple-500 mt-1">New orders and updates</p>
                </div>
                <div id="notificationList" class="max-h-96 overflow-y-auto">
                    <div class="p-4 text-center text-purple-500 text-sm">
                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                        <p>No new notifications</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="flex items-center space-x-3 pl-4 border-l border-purple-200">
            <div class="text-right">
                <a href="{{ route('admin.profile') }}" class="group cursor-pointer hover:opacity-80 transition-opacity">
                    <p class="font-bold text-purple-800 text-sm group-hover:text-purple-600 transition-colors">Super Admin</p>
                    <p class="text-purple-500 text-xs group-hover:text-purple-400 transition-colors">admin@aryameals.test</p>
                </a>
            </div>
            <a href="{{ route('admin.profile') }}" class="group cursor-pointer hover:scale-105 transition-transform">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-sm font-bold text-white shadow-lg group-hover:from-purple-500 group-hover:to-pink-700 transition-all">
                    SA
                </div>
            </a>
        </div>
    </div>
</header>

<!-- Notification Sound -->
<audio id="notificationSound" preload="auto">
    <source src="https://cdn.freesound.org/previews/316/316920_4939433-lq.mp3" type="audio/mpeg">
</audio>

<script>
let notificationDropdown = document.getElementById('notificationDropdown');
let notificationBadge = document.getElementById('notificationBadge');
let notificationSound = document.getElementById('notificationSound');
let notifications = [];
let lastOrderCount = 0;

function toggleNotifications() {
    notificationDropdown.classList.toggle('hidden');
    if (!notificationDropdown.classList.contains('hidden')) {
        markNotificationsAsRead();
    }
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        notificationDropdown.classList.add('hidden');
    }
});

function checkForNewOrders() {
    fetch('{{ route("admin.dashboard.stats") }}')
        .then(response => response.json())
        .then(data => {
            const currentOrderCount = data.today_orders || 0;
            
            if (lastOrderCount > 0 && currentOrderCount > lastOrderCount) {
                const newOrdersCount = currentOrderCount - lastOrderCount;
                showOrderNotification(newOrdersCount);
            }
            
            lastOrderCount = currentOrderCount;
        })
        .catch(error => console.error('Error checking orders:', error));
}

function showOrderNotification(orderCount) {
    // Show badge
    notificationBadge.classList.remove('hidden');
    
    // Play sound
    notificationSound.play().catch(e => console.log('Sound play failed:', e));
    
    // Create notification
    const notification = {
        id: Date.now(),
        message: `${orderCount} new order${orderCount > 1 ? 's' : ''} placed!`,
        time: new Date().toLocaleTimeString(),
        read: false
    };
    
    notifications.unshift(notification);
    if (notifications.length > 10) notifications.pop();
    
    updateNotificationList();
}

function updateNotificationList() {
    const notificationList = document.getElementById('notificationList');
    
    if (notifications.length === 0) {
        notificationList.innerHTML = `
            <div class="p-4 text-center text-purple-500 text-sm">
                <i class="fas fa-bell-slash text-2xl mb-2"></i>
                <p>No new notifications</p>
            </div>
        `;
        return;
    }
    
    notificationList.innerHTML = notifications.map(notif => `
        <div class="p-3 border-b border-purple-50 hover:bg-purple-50 transition-colors ${!notif.read ? 'bg-purple-50' : ''}">
            <div class="flex items-start space-x-3">
                <div class="h-8 w-8 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-white text-xs">
                    <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-medium text-purple-800">${notif.message}</p>
                    <p class="text-xs text-purple-500">${notif.time}</p>
                </div>
                ${!notif.read ? '<div class="h-2 w-2 rounded-full bg-purple-500 mt-2"></div>' : ''}
            </div>
        </div>
    `).join('');
}

function markNotificationsAsRead() {
    notifications.forEach(notif => notif.read = true);
    notificationBadge.classList.add('hidden');
    updateNotificationList();
}

// Check for new orders every 30 seconds
setInterval(checkForNewOrders, 30000);

// Initial check
checkForNewOrders();
</script>