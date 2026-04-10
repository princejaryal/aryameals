<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount(['orders' => function($query) {
            $query->selectRaw('count(*)');
        }])->withSum('orders', 'total_amount')->latest();
        
        // Search by name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        // Filter by registration date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Filter by order count
        if ($request->has('has_orders') && $request->has_orders) {
            $query->whereHas('orders');
        }
        
        $customers = $query->paginate(15);
        
        // Calculate dynamic stats
        $stats = [
            'total_customers' => User::count(),
            'new_customers_today' => User::whereDate('created_at', today())->count(),
            'new_customers_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'active_customers' => User::whereHas('orders', function($query) {
                $query->whereMonth('created_at', '>=', now()->subMonths(3));
            })->count(),
        ];
        
        return view('admin.customers.index', compact('customers', 'stats'));
    }
    
    public function show($id)
    {
        $customer = User::with(['orders' => function($query) {
            $query->with('restaurant')->latest();
        }])->findOrFail($id);
        
        // Calculate customer statistics
        $stats = [
            'total_orders' => $customer->orders->count(),
            'total_spent' => $customer->orders->sum('total_amount'),
            'average_order_value' => $customer->orders->count() > 0 ? 
                $customer->orders->sum('total_amount') / $customer->orders->count() : 0,
            'last_order' => $customer->orders->first(),
        ];
        
        return view('admin.customers.show', compact('customer', 'stats'));
    }
    
    public function edit($id)
    {
        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }
    
    public function update(Request $request, $id)
    {
        $customer = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        
        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }
        
        $customer->update($updateData);
        
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Customer updated successfully!');
    }
    
    public function destroy($id)
    {
        $customer = User::findOrFail($id);
        
        // Check if customer has orders
        $orderCount = $customer->orders()->count();
        if ($orderCount > 0) {
            return redirect()->back()
                ->with('error', "Cannot delete customer. Customer has {$orderCount} order(s) associated with their account.");
        }
        
        $customer->delete();
        
        return redirect()->route('admin.customers')
            ->with('success', 'Customer deleted successfully!');
    }
    
    public function customerOrders($id)
    {
        $customer = User::findOrFail($id);
        $orders = $customer->orders()->with('restaurant')->latest()->paginate(10);
        
        return view('admin.customers.orders', compact('customer', 'orders'));
    }
    
    public function dashboardStats()
    {
        $stats = [
            'total_customers' => User::count(),
            'new_customers_today' => User::whereDate('created_at', today())->count(),
            'new_customers_this_month' => User::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'active_customers' => User::whereHas('orders', function($query) {
                $query->whereMonth('created_at', '>=', now()->subMonths(3));
            })->count(),
        ];
        
        return response()->json($stats);
    }
    
}
