<!-- Sidebar Component -->
<aside class="sidebar sidebar-fixed w-64 bg-gradient-to-br from-purple-900 via-purple-800 to-pink-900 text-white flex flex-col shadow-2xl fixed top-0 left-0 h-screen overflow-y-auto hide-scrollbar">
    <div class="flex items-center justify-between px-6 py-5 border-b border-purple-700/50">
        <div class="flex items-center space-x-3">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-sm font-bold shadow-lg">
                AM
            </div>
            <div class="sidebar-label">
                <p class="text-xs uppercase tracking-[0.2em] text-purple-400 font-semibold">Arya Meals</p>
            </div>
        </div>
    </div>

    <nav class="flex-1 px-3 py-6 space-y-2 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-home w-4"></i></span>
            <span class="sidebar-label">Dashboard</span>
        </a>
        <a href="{{ route('admin.restaurants') }}" class="nav-item {{ request()->routeIs('admin.restaurants*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-store w-4"></i></span>
            <span class="sidebar-label">Restaurants</span>
        </a>
        <a href="{{ route('admin.menu') }}" class="nav-item {{ request()->routeIs('admin.menu*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-utensils w-4"></i></span>
            <span class="sidebar-label">Menu</span>
        </a>
        <a href="{{ route('admin.orders') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-shopping-cart w-4"></i></span>
            <span class="sidebar-label">Orders</span>
        </a>
                <a href="{{ route('admin.customers') }}" class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-users w-4"></i></span>
            <span class="sidebar-label">Customers</span>
        </a>
        <a href="{{ route('admin.reviews.index') }}" class="nav-item {{ request()->routeIs('admin.reviews*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-star w-4"></i></span>
            <span class="sidebar-label">Reviews</span>
        </a>
        <a href="{{ route('admin.platform-fees.index') }}" class="nav-item {{ request()->routeIs('admin.platform-fees*') ? 'active' : '' }} flex items-center px-4 py-3 rounded-xl text-purple-100 hover:bg-purple-700/30 hover:text-white transition-all font-medium">
            <span class="mr-3 text-base"><i class="fas fa-coins w-4"></i></span>
            <span class="sidebar-label">Fees</span>
        </a>
    </nav>

    <div class="px-4 py-4 border-t border-purple-700/50 text-xs sidebar-label">
        <div class="bg-gradient-to-r from-purple-700/50 to-pink-600/50 rounded-lg p-3">
            <p class="font-semibold text-purple-400 mb-2">Today's Performance</p>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-purple-200">Orders in queue</span>
                    <span class="text-pink-300 font-bold">12</span>
                </div>
                <div class="h-2 w-full bg-purple-600/50 rounded-full overflow-hidden">
                    <div class="h-full w-3/4 bg-gradient-to-r from-purple-400 to-pink-500 rounded-full animate-pulse"></div>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <span class="text-purple-200">Efficiency</span>
                    <span class="text-yellow-300 font-bold">87%</span>
                </div>
            </div>
        </div>
    </div>
</aside>
