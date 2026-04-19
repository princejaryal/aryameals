<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\MenuItem;

class DashboardController extends Controller
{
    public function index()
    {
        // Get today's statistics
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');
        
        // Calculate statistics
        $stats = [
            'total_customers' => User::count(),
            'total_restaurants' => Restaurant::count(),
            'total_menu_items' => MenuItem::count(),
            'total_orders' => Order::count(),
            
            // Total revenue from all orders
            'total_revenue' => Order::sum('total_amount'),
            
            // Today's stats
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)->sum('total_amount'),
            
            // Average order value
            'avg_order_value' => Order::count() > 0 ? Order::average('total_amount') : 0,
            
            // This month stats
            'this_month_orders' => Order::whereMonth('created_at', $thisMonth)->count(),
            'this_month_revenue' => Order::whereMonth('created_at', $thisMonth)->sum('total_amount'),
            
            // Last month stats
            'last_month_orders' => Order::whereMonth('created_at', $lastMonth)->count(),
            'last_month_revenue' => Order::whereMonth('created_at', $lastMonth)->sum('total_amount'),
            
            // Active entities
            'active_restaurants' => Restaurant::where('is_active', true)->count(),
            'available_menu_items' => MenuItem::where('is_available', true)->count(),
        ];
        
        // Get live orders (recent orders)
        $liveOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        // Get top menu items (most ordered based on order items, minimum 5 orders)
        $topDishes = MenuItem::withCount('orderItems')
            ->where('is_available', true)
            ->having('order_items_count', '>=', 5)
            ->orderBy('order_items_count', 'desc')
            ->limit(8)
            ->get();
        
        // Calculate percentage changes
        $stats['orders_change'] = $stats['last_month_orders'] > 0 ? 
            (($stats['this_month_orders'] - $stats['last_month_orders']) / $stats['last_month_orders']) * 100 : 0;
        $stats['revenue_change'] = $stats['last_month_revenue'] > 0 ? 
            (($stats['this_month_revenue'] - $stats['last_month_revenue']) / $stats['last_month_revenue']) * 100 : 0;
        
        // Get revenue analytics data for charts
        $revenueAnalytics = $this->getRevenueAnalytics($stats);
        
        return view('admin.dashboard', compact('stats', 'liveOrders', 'topDishes', 'revenueAnalytics'));
    }
    
    private function getRevenueAnalytics($stats)
    {
        // Get last 7 days revenue data
        $weeklyRevenue = [];
        $weeklyOrders = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $labels[] = $date->format('D');
            $weeklyRevenue[] = Order::whereDate('created_at', $date)->sum('total_amount');
            $weeklyOrders[] = Order::whereDate('created_at', $date)->count();
        }
        
        // Get monthly data (last 4 weeks)
        $monthlyRevenue = [];
        $monthlyOrders = [];
        $monthlyLabels = [];
        
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $monthlyLabels[] = 'Week ' . (4 - $i);
            $monthlyRevenue[] = Order::whereBetween('created_at', [$weekStart, $weekEnd])->sum('total_amount');
            $monthlyOrders[] = Order::whereBetween('created_at', [$weekStart, $weekEnd])->count();
        }
        
        // Get yearly data (last 12 months)
        $yearlyRevenue = [];
        $yearlyOrders = [];
        $yearlyLabels = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $yearlyLabels[] = $month->format('M');
            $yearlyRevenue[] = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_amount');
            $yearlyOrders[] = Order::whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->count();
        }
        
        return [
            'weekly' => [
                'labels' => $labels,
                'revenue' => $weeklyRevenue,
                'orders' => $weeklyOrders
            ],
            'monthly' => [
                'labels' => $monthlyLabels,
                'revenue' => $monthlyRevenue,
                'orders' => $monthlyOrders
            ],
            'yearly' => [
                'labels' => $yearlyLabels,
                'revenue' => $yearlyRevenue,
                'orders' => $yearlyOrders
            ]
        ];
    }
    
    public function dashboardStats()
    {
        $today = now()->format('Y-m-d');
        $thisMonth = now()->format('Y-m');
        
        return response()->json([
            'today_orders' => Order::whereDate('created_at', $today)->count(),
            'today_revenue' => Order::whereDate('created_at', $today)->sum('total_amount'),
            'avg_order_value' => Order::count() > 0 ? Order::average('total_amount') : 0,
            'this_month_orders' => Order::whereMonth('created_at', $thisMonth)->count(),
            'this_month_revenue' => Order::whereMonth('created_at', $thisMonth)->sum('total_amount'),
            'total_revenue' => Order::sum('total_amount'),
            'total_customers' => User::count(),
            'total_restaurants' => Restaurant::count(),
            'active_restaurants' => Restaurant::where('is_active', true)->count(),
        ]);
    }
    
    public function apiBusinessInfo()
    {
        $businessInfo = [
            'name' => 'Arya Meals',
            'description' => 'Delicious food delivered to your doorstep',
            'phone' => '+91 98765 43210',
            'email' => 'info@aryameals.com',
            'address' => '123 Food Street, Culinary City, 560001',
            'delivery_time' => '30-45 minutes',
            'delivery_fee' => 20,
            'min_order' => 100,
            'working_hours' => [
                'monday' => '10:00 AM - 11:00 PM',
                'tuesday' => '10:00 AM - 11:00 PM',
                'wednesday' => '10:00 AM - 11:00 PM',
                'thursday' => '10:00 AM - 11:00 PM',
                'friday' => '10:00 AM - 11:00 PM',
                'saturday' => '10:00 AM - 11:00 PM',
                'sunday' => '10:00 AM - 11:00 PM'
            ]
        ];
        
        return response()->json($businessInfo);
    }
}
