@extends('admin.layouts.app')

@section('title', 'Arya Meals - Admin Dashboard')

@section('content')
    <div class="p-6 space-y-8">
        <!-- Statistics Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="stat-card customers rounded-2xl p-6 shadow-lg bg-gradient-to-br from-purple-600 to-purple-800">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-white uppercase tracking-[0.16em] font-semibold">Total Customers</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ number_format($stats['total_customers']) }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center text-white text-xs font-semibold">
                                <i class="fas fa-users mr-1"></i>
                                Active
                            </span>
                            <span class="text-white/80 text-xs">Registered</span>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-user-friends text-white text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full w-3/4 bg-white rounded-full"></div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="stat-card total-orders rounded-2xl p-6 shadow-lg bg-gradient-to-br from-blue-600 to-cyan-800">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-white uppercase tracking-[0.16em] font-semibold">Total Orders</p>
                        <p class="mt-3 text-3xl font-bold text-white">{{ number_format($stats['total_orders']) }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center text-cyan-300 text-xs font-semibold">
                                <i class="fas fa-shopping-bag mr-1"></i>
                                All Time
                            </span>
                            <span class="text-white/80 text-xs">Complete</span>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-line text-cyan-300 text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full w-3/4 bg-cyan-300 rounded-full"></div>
                </div>
            </div>
            
            <div class="stat-card avg-order rounded-2xl p-6 shadow-lg bg-gradient-to-br from-purple-600 to-indigo-800">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-white uppercase tracking-[0.16em] font-semibold">Avg Order Value</p>
                        <p class="mt-3 text-3xl font-bold text-white">₹{{ number_format($stats['avg_order_value'], 0) }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center text-purple-300 text-xs font-semibold">
                                <i class="fas fa-chart-line mr-1"></i>
                                Average
                            </span>
                            <span class="text-white/80 text-xs">Per Order</span>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-chart-bar text-purple-300 text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full w-1/2 bg-purple-300 rounded-full"></div>
                </div>
            </div>
            
            <div class="stat-card total-revenue rounded-2xl p-6 shadow-lg bg-gradient-to-br from-green-600 to-emerald-800">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs text-white uppercase tracking-[0.16em] font-semibold">Total Revenue</p>
                        <p class="mt-3 text-3xl font-bold text-white">₹{{ number_format($stats['total_revenue'], 0) }}</p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center text-emerald-300 text-xs font-semibold">
                                <i class="fas fa-chart-line mr-1"></i>
                                All Time
                            </span>
                            <span class="text-white/80 text-xs">Complete</span>
                        </div>
                    </div>
                    <div class="h-12 w-12 rounded-xl bg-white/20 flex items-center justify-center">
                        <i class="fas fa-wallet text-emerald-300 text-lg"></i>
                    </div>
                </div>
                <div class="mt-4 h-1 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full w-2/3 bg-emerald-300 rounded-full"></div>
                </div>
            </div>
        </section>

        <!-- Analytics Section -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Live Orders Table -->
            <div class="lg:col-span-2 glass-effect rounded-2xl shadow-lg p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Live Orders</h2>
                        <p class="text-xs text-purple-500 mt-1">Real-time order tracking</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <span class="status-badge inline-flex items-center rounded-full bg-gradient-to-r from-purple-50 to-pink-100 text-purple-700 px-3 py-1.5 text-xs font-semibold border border-purple-200">
                            <span class="h-2 w-2 bg-purple-500 rounded-full mr-2 animate-pulse"></span>
                            {{ $liveOrders->count() }} Active
                        </span>
                        <button class="h-8 w-8 rounded-lg bg-purple-100 hover:bg-purple-200 flex items-center justify-center transition-colors">
                            <i class="fas fa-refresh text-purple-600 text-xs"></i>
                        </button>
                    </div>
                </div>
                <div class="h-96 overflow-hidden">
                    <div class="h-full overflow-y-auto scrollbar-hide">
                        <table class="data-table w-full min-w-full">
                            <thead class="sticky top-0 bg-white/95 backdrop-blur">
                                <tr class="text-xs">
                                    <th class="text-left py-3 px-4 font-semibold text-purple-700">Order ID</th>
                                    <th class="text-left py-3 px-4 font-semibold text-purple-700">Customer</th>
                                    <th class="text-left py-3 px-4 font-semibold text-purple-700">Items</th>
                                    <th class="text-left py-3 px-4 font-semibold text-purple-700">Amount</th>
                                    <th class="text-center py-3 px-4 font-semibold text-purple-700">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($liveOrders as $order)
                                <tr class="hover:bg-purple-50 transition-colors">
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-purple-900 text-sm">#AM-{{ $order->id }}</span>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center space-x-2">
                                            <div class="h-6 w-6 rounded-full bg-gradient-to-br from-purple-400 to-pink-600 flex items-center justify-center text-xs text-white font-bold">
                                                {{ strtoupper(substr($order->customer_name ?? $order->user->name ?? 'U', 0, 2)) }}
                                            </div>
                                            <span class="text-purple-700 text-sm">{{ $order->customer_name ?? $order->user->name ?? 'Unknown' }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-purple-600 text-sm">
                                        @if($order->items && is_array($order->items))
                                            {{ implode(', ', array_column($order->items, 'name')) }}
                                        @else
                                            Order items
                                        @endif
                                    </td>
                                    <td class="py-3 px-4">
                                        <span class="font-bold text-purple-900 text-sm">${{ number_format($order->total_amount, 2) }}</span>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">
                                            View
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-purple-500">
                                        <i class="fas fa-shopping-bag text-2xl mb-2"></i>
                                        <p>No active orders</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Top Performing Dishes -->
            <div class="glass-effect rounded-2xl shadow-lg p-6 w-full">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Top Dishes</h2>
                    <div class="h-8 w-8 rounded-lg bg-gradient-to-r from-orange-100 to-orange-200 flex items-center justify-center">
                        <i class="fas fa-crown text-orange-600 text-sm"></i>
                    </div>
                </div>
                <div class="h-80 overflow-hidden">
                    <div class="h-full overflow-y-auto scrollbar-hide space-y-4 w-full">
                        @forelse($topDishes as $index => $dish)
                        <div class="flex items-center space-x-4 p-4 rounded-xl bg-gradient-to-r from-emerald-50 to-emerald-100/50 border border-emerald-200 w-full hover:shadow-md transition-shadow">
                            <!-- Dish Image -->
                            <div class="flex-shrink-0">
                                @if($dish->image)
                                    <img src="{{ asset('storage/menu-items/' . $dish->image) }}" 
                                         class="h-12 w-12 rounded-lg object-cover border-2 border-emerald-200"
                                         alt="{{ $dish->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-sm">
                                        {{ strtoupper(substr($dish->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Dish Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="h-6 w-6 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold text-xs">
                                        {{ $index + 1 }}
                                    </span>
                                    <h3 class="font-bold text-slate-900 text-sm truncate">{{ $dish->name }}</h3>
                                </div>
                                <p class="text-xs text-emerald-600 truncate">{{ $dish->category ?? 'Uncategorized' }}</p>
                                @if($dish->description)
                                    <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($dish->description, 50) }}</p>
                                @endif
                            </div>
                            
                            <!-- Price and Stats -->
                            <div class="flex flex-col items-end space-y-1">
                                <span class="text-sm font-bold text-emerald-700">Rs.{{ number_format($dish->full_plate_price ?? $dish->half_plate_price ?? 0, 0) }}</span>
                                <div class="flex items-center space-x-1">
                                    <span class="text-xs text-gray-500">{{ $dish->order_items_count ?? 0 }} orders</span>
                                    @if($dish->is_available)
                                        <span class="h-2 w-2 bg-emerald-500 rounded-full" title="Available"></span>
                                    @else
                                        <span class="h-2 w-2 bg-gray-400 rounded-full" title="Unavailable"></span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8">
                            <i class="fas fa-utensils text-2xl text-emerald-500 mb-2"></i>
                            <p class="text-emerald-600">No dishes available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

    <!-- Chart Section -->

        <!-- Revenue Chart -->
        <section class="glass-effect rounded-2xl shadow-lg p-6 w-full">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-lg font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Revenue Analytics</h2>
                    <p class="text-xs text-purple-500 mt-1">Weekly performance overview</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button onclick="changePeriod('week')" id="weekBtn" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-sm hover:shadow-md transition-shadow">Week</button>
                    <button onclick="changePeriod('month')" id="monthBtn" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 hover:from-gray-200 hover:to-gray-300 transition-all">Month</button>
                    <button onclick="changePeriod('year')" id="yearBtn" class="px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 hover:from-gray-200 hover:to-gray-300 transition-all">Year</button>
                </div>
            </div>

            <!-- Chart Container -->
            <div class="h-64">
                <canvas id="revenueChart"></canvas>
            </div>
        </section>
    </div>
@endsection

@push('scripts')
    <script>
        // Revenue Chart - Wait for Chart.js to load
        let chartInstance = null;
        let currentPeriod = 'week';
        
        // Store all period data - only orders
        const periodData = {
            week: {
                orders: {!! json_encode($revenueAnalytics['weekly']['orders']) !!},
                labels: {!! json_encode($revenueAnalytics['weekly']['labels']) !!},
                subtitle: 'Weekly orders overview'
            },
            month: {
                orders: {!! json_encode($revenueAnalytics['monthly']['orders']) !!},
                labels: {!! json_encode($revenueAnalytics['monthly']['labels']) !!},
                subtitle: 'Monthly orders overview'
            },
            year: {
                orders: {!! json_encode($revenueAnalytics['yearly']['orders']) !!},
                labels: {!! json_encode($revenueAnalytics['yearly']['labels']) !!},
                subtitle: 'Yearly orders overview'
            }
        };
        
        function changePeriod(period) {
            currentPeriod = period;
            
            // Update button styles
            updateButtonStyles(period);
            
            // Update subtitle
            document.querySelector('p.text-xs.text-purple-500').textContent = periodData[period].subtitle;
            
            // Update chart
            updateChart(period);
        }
        
        function updateButtonStyles(activePeriod) {
            // Reset all buttons to inactive
            document.getElementById('weekBtn').className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 hover:from-gray-200 hover:to-gray-300 transition-all';
            document.getElementById('monthBtn').className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 hover:from-gray-200 hover:to-gray-300 transition-all';
            document.getElementById('yearBtn').className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-gray-100 to-gray-200 text-gray-600 hover:from-gray-200 hover:to-gray-300 transition-all';
            
            // Set active button
            document.getElementById(activePeriod + 'Btn').className = 'px-3 py-1.5 text-xs font-semibold rounded-lg bg-gradient-to-r from-purple-600 to-purple-700 text-white shadow-sm hover:shadow-md transition-shadow';
        }
        
        function updateChart(period) {
            if (chartInstance && periodData[period]) {
                chartInstance.data.labels = periodData[period].labels;
                chartInstance.data.datasets[0].data = periodData[period].orders;
                chartInstance.update();
            }
        }
        
        function initializeChart() {
            const ctx = document.getElementById('revenueChart');
            
            if (ctx && typeof Chart !== 'undefined') {
                // Destroy existing chart instance if it exists
                if (chartInstance) {
                    chartInstance.destroy();
                    chartInstance = null;
                }
                
                // Get initial data (week)
                const data = periodData.week;
                
                try {
                    chartInstance = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Total Orders',
                                data: data.orders,
                                borderColor: 'rgb(139, 92, 246)',
                                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: true,
                                    position: 'top'
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return value + ' orders';
                                        }
                                    }
                                }
                            }
                        }
                    });
                } catch (error) {
                    // Silently handle chart creation error
                }
            }
        }
        
        // Initialize chart only once
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeChart);
        } else {
            initializeChart();
        }
    </script>

@section('scripts')
    <script>
        // Load dashboard stats
        function loadDashboardStats() {
            fetch('{{ route("admin.dashboard.stats") }}')
                .then(response => response.json())
                .then(data => {
                    // Update stats cards with real-time data
                    updateStatValue('.stat-card.customers .text-3xl', data.total_customers);
                    updateStatValue('.stat-card.total-orders .text-3xl', data.total_orders);
                    updateStatValue('.stat-card.avg-order .text-3xl', 'Rs.' + data.avg_order_value.toFixed(0));
                    updateStatValue('.stat-card.total-revenue .text-3xl', 'Rs.' + data.total_revenue.toFixed(0));
                })
                .catch(error => console.error('Error loading dashboard stats:', error));
        }
        
        function updateStatValue(selector, value) {
            const elements = document.querySelectorAll(selector);
            elements.forEach(element => {
                if (element) {
                    element.textContent = value;
                }
            });
        }
        
        // Auto-refresh stats every 30 seconds
        setInterval(loadDashboardStats, 30000);
        
        // Load stats on page load
        document.addEventListener('DOMContentLoaded', loadDashboardStats);
    </script>
@endsection
