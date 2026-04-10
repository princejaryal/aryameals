<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'restaurant'])->latest();
        
        // Filter by status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by restaurant
        if ($request->has('restaurant_id') && $request->restaurant_id) {
            $query->where('restaurant_id', $request->restaurant_id);
        }
        
        // Search by customer name or phone
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(15);
        $restaurants = Restaurant::active()->get();
        
        return view('admin.orders.index', compact('orders', 'restaurants'));
    }
    
    public function show($id)
    {
        $order = Order::with(['user', 'restaurant'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
    
    public function edit($id)
    {
        $order = Order::with(['user', 'restaurant'])->findOrFail($id);
        $restaurants = Restaurant::active()->get();
        return view('admin.orders.edit', compact('order', 'restaurants'));
    }
    
    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'order_notes' => 'nullable|string|max:1000',
            'delivery_type' => 'required|in:pickup,delivery',
            'payment_method' => 'required|in:cash,online,card',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $order->update($request->all());
        
        return redirect()->route('admin.orders.show', $order->id)
            ->with('success', 'Order updated successfully!');
    }
    
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        
        // Don't allow deletion of confirmed or preparing orders
        if (in_array($order->status, ['confirmed', 'preparing'])) {
            return redirect()->back()
                ->with('error', 'Cannot delete order that is confirmed or being prepared!');
        }
        
        $order->delete();
        
        return redirect()->route('admin.orders')
            ->with('success', 'Order deleted successfully!');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,confirmed,preparing,ready,delivered,cancelled',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        
        $order->status = $request->status;
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully!',
            'new_status' => $order->status
        ]);
    }
    
    public function updatePaymentStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:pending,paid,failed,refunded',
        ]);
        
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }
        
        $order->payment_status = $request->payment_status;
        $order->save();
        
        return response()->json([
            'success' => true,
            'message' => 'Payment status updated successfully!',
            'new_status' => $order->payment_status
        ]);
    }
    
    public function dashboardStats()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'today_orders' => Order::whereDate('created_at', today())->count(),
            'today_revenue' => Order::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('total_amount'),
        ];
        
        return response()->json($stats);
    }
}
