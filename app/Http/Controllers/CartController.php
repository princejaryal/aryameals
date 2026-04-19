<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PlatformFeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    // Get or create session ID
    private function getOrCreateSessionId()
    {
        $sessionId = Session::get('cart_session_id');
        if (!$sessionId) {
            $sessionId = uniqid('cart_', true);
            Session::put('cart_session_id', $sessionId);
        }
        return $sessionId;
    }

    // Show cart page
    public function index()
    {
        $sessionId = $this->getOrCreateSessionId();

        // Get cart items for the authenticated user using Cart model
        $cartItems = Cart::where('session_id', $sessionId)
            ->where('user_id', auth()->id()) // Only get items for logged-in user
            ->with(['menuItem' => function ($query) {
                $query->with('restaurant');
            }])
            ->get();

        $subtotal = 0;
        $itemCount = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->total_price;
            $itemCount += $item->quantity;
        }

        // Get dynamic fee calculations
        $feeData = PlatformFeeService::getFeeDisplayData($subtotal);

        return view('cart', compact('cartItems', 'subtotal', 'itemCount', 'feeData'));
    }

    /**
     * Get cart items for API (for checkout integration)
     */
    public function getCartItemsForApi()
    {
        try {
            // Check if user is authenticated
            if (!auth()->check()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'cart_items' => [],
                    'subtotal' => 0,
                    'items_count' => 0
                ], 401);
            }

            $sessionId = $this->getOrCreateSessionId();
            \Log::info('Cart API called', [
                'session_id' => $sessionId,
                'user_id' => auth()->id(),
                'method' => 'getCartItemsForApi'
            ]);

            $cartItems = Cart::where('session_id', $sessionId)
                ->where('user_id', auth()->id()) // Only get items for logged-in user
                ->with(['menuItem' => function ($query) {
                    $query->with('restaurant');
                }])
                ->get();

            \Log::info('Cart items found', [
                'count' => $cartItems->count(),
                'items' => $cartItems->toArray()
            ]);

            $items = [];
            $subtotal = 0;
            $itemCount = 0;

            if ($cartItems->isEmpty()) {
                // No items in cart - return empty cart response
                \Log::info('Cart is empty');
                return response()->json([
                    'success' => true,
                    'cart_items' => [],
                    'subtotal' => 0,
                    'items_count' => 0,
                    'message' => 'Cart is empty'
                ]);
            }

            foreach ($cartItems as $item) {
                $subtotal += $item->total_price;
                $itemCount += $item->quantity;

                $items[] = [
                    'menu_item_id' => $item->menu_item_id,
                    'name' => $item->menuItem ? $item->menuItem->name : 'Item',
                    'price' => $item->total_price / $item->quantity, // Price per item
                    'quantity' => $item->quantity,
                    'subtotal' => $item->total_price
                ];
            }

            // Get dynamic fee calculations
            $feeData = PlatformFeeService::getFeeDisplayData($subtotal);

            // Use the calculated grand total with fees and round up to next integer
            $totalWithFees = ceil($feeData['grand_total_numeric']);

            \Log::info('Cart data calculated', [
                'subtotal' => $subtotal,
                'total_fees' => $feeData['total_fees_numeric'],
                'total_fees_formatted' => $feeData['total_fees'],
                'total_with_fees' => $totalWithFees,
                'items_count' => $itemCount
            ]);

            return response()->json([
                'success' => true,
                'cart_items' => $items,
                'subtotal' => $subtotal,
                'grand_total' => $totalWithFees, // Include fees in grand total for checkout
                'items_count' => $itemCount,
                'fee_data' => $feeData // Also send fee data for display
            ]);
        } catch (\Exception $e) {
            \Log::error('Cart API error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Server error: ' . $e->getMessage(),
                'cart_items' => [],
                'subtotal' => 0,
                'items_count' => 0
            ], 500);
        }
    }

    // Add item to cart
    public function add(Request $request)
    {
        try {
            $validated = $request->validate([
                'menu_item_id' => 'required|integer|exists:menu_items,id',
                'quantity' => 'required|integer|min:1|max:99',
                'portion_size' => 'required|in:half,full',
                'customer_name' => 'nullable|string|max:255',
                'customer_email' => 'nullable|email|max:255',
                'customer_phone' => 'nullable|string|max:20',
                'special_instructions' => 'nullable|string|max:500'
            ]);

            $sessionId = $this->getOrCreateSessionId();
            $menuItem = MenuItem::findOrFail($validated['menu_item_id']);

            // Check if menu item is available
            if (!$menuItem->is_available) {
                return response()->json([
                    'success' => false,
                    'message' => 'This item is currently not available.'
                ], 400);
            }

            // Determine price based on item pricing type
            if ($menuItem->price > 0) {
                // Single price item
                $price = $menuItem->price;
                // For single price items, ignore portion size and set to 'full'
                $validated['portion_size'] = 'full';
            } else {
                // Dual price item - determine based on portion size
                $price = $validated['portion_size'] === 'half'
                    ? $menuItem->half_plate_price
                    : $menuItem->full_plate_price;
            }

            // Check if price exists
            if (!$price || $price <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Price not available for this item.'
                ], 400);
            }

            $totalPrice = $price * $validated['quantity'];

            // Check if item already exists in cart
            $existingItem = Cart::bySession($sessionId)
                ->where('menu_item_id', $validated['menu_item_id'])
                ->where('portion_size', $validated['portion_size'])
                ->first();

            if ($existingItem) {
                // Update quantity if item exists
                $existingItem->quantity += $validated['quantity'];
                $existingItem->total_price = $price * $existingItem->quantity;
                $existingItem->save();
            } else {
                // Create new cart item
                Cart::create([
                    'user_id' => auth()->id(), // Associate with logged-in user
                    'session_id' => $sessionId,
                    'menu_item_id' => $validated['menu_item_id'],
                    'customer_name' => $validated['customer_name'] ?? null,
                    'customer_email' => $validated['customer_email'] ?? null,
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'quantity' => $validated['quantity'],
                    'portion_size' => $validated['portion_size'],
                    'price' => $price,
                    'total_price' => $totalPrice,
                    'special_instructions' => $validated['special_instructions'] ?? null
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Item added to cart successfully!',
                'cart_count' => $this->getCartCount($sessionId)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors())
            ], 422);
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Cart add error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error adding item to cart. Please try again.'
            ], 500);
        }
    }

    // Update cart item quantity
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $sessionId = $this->getOrCreateSessionId();
        $cartItem = Cart::bySession($sessionId)->findOrFail($id);

        $cartItem->quantity = $validated['quantity'];
        $cartItem->total_price = $cartItem->price * $validated['quantity'];
        $cartItem->save();

        // Get updated cart totals and fees
        $cartTotal = $this->getCartTotal($sessionId);
        $cartCount = $this->getCartCount($sessionId);
        $feeData = PlatformFeeService::getFeeDisplayData($cartTotal);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully!',
            'total_price' => $cartItem->formatted_total_price,
            'cart_total' => '₹' . number_format($cartTotal, 0),
            'cart_count' => $cartCount,
            'fees' => $feeData['fees'],
            'grand_total' => $feeData['grand_total'],
            'has_fees' => $feeData['has_fees']
        ]);
    }

    // Remove item from cart
    public function remove($id)
    {
        $sessionId = $this->getOrCreateSessionId();
        $cartItem = Cart::bySession($sessionId)->findOrFail($id);
        $cartItem->delete();

        // Get updated cart totals and fees
        $cartTotal = $this->getCartTotal($sessionId);
        $cartCount = $this->getCartCount($sessionId);
        $feeData = PlatformFeeService::getFeeDisplayData($cartTotal);

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart!',
            'cart_total' => '₹' . number_format($cartTotal, 0),
            'cart_count' => $cartCount,
            'fees' => $feeData['fees'],
            'grand_total' => $feeData['grand_total'],
            'has_fees' => $feeData['has_fees']
        ]);
    }

    // Clear entire cart
    public function clear()
    {
        $sessionId = $this->getOrCreateSessionId();
        Cart::bySession($sessionId)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully!'
        ]);
    }

    // Get cart count
    private function getCartCount($sessionId)
    {
        return Cart::bySession($sessionId)->sum('quantity');
    }

    // Get cart total
    private function getCartTotal($sessionId)
    {
        return Cart::bySession($sessionId)->sum('total_price');
    }

    // Show checkout page
    public function checkout()
    {
        $sessionId = $this->getOrCreateSessionId();
        $cartItems = Cart::bySession($sessionId)
            ->with(['menuItem' => function ($query) {
                $query->with('restaurant');
            }])
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty. Please add items before checkout.');
        }

        $subtotal = 0;
        $itemCount = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->total_price;
            $itemCount += $item->quantity;
        }

        // Get dynamic fee calculations
        $feeData = PlatformFeeService::getFeeDisplayData($subtotal);

        return view('checkout', compact('cartItems', 'subtotal', 'itemCount', 'feeData'));
    }

    // Process checkout
    public function processCheckout(Request $request)
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to place an order.'
            ], 401);
        }

        $validated = $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|string|in:cod,online',
            'special_instructions' => 'nullable|string|max:1000'
        ]);

        $sessionId = $this->getOrCreateSessionId();
        $user = auth()->user();

        // Get the delivery address
        $address = $user->addresses()->findOrFail($validated['address_id']);

        $cartItems = Cart::where('session_id', $sessionId)
            ->where('user_id', $user->id)
            ->with(['menuItem' => function ($query) {
                $query->with('restaurant');
            }])
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty. Please add items before checkout.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cartItems->sum('total_price');
            $feeData = PlatformFeeService::getFeeDisplayData($subtotal);
            $grandTotal = $feeData['grand_total_numeric'];

            // Build delivery address string
            $deliveryAddress = $address->address_line_1;
            if ($address->address_line_2) {
                $deliveryAddress .= ', ' . $address->address_line_2;
            }
            $deliveryAddress .= ', ' . $address->city . ', ' . $address->state . ' - ' . $address->postal_code;

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_phone' => $user->phone ?? $address->phone,
                'customer_address' => $deliveryAddress,
                'total_amount' => $grandTotal,
            ]);

            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_id' => $cartItem->menu_item_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->price,
                ]);
            }

            // Clear cart
            Cart::where('session_id', $sessionId)
                ->where('user_id', $user->id)
                ->delete();

            DB::commit();

            // Prepare order details for WhatsApp message
            $whatsappMessage = $this->generateWhatsAppOrderMessage($order, $cartItems, $address, $feeData);

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $order->id,
                'redirect_url' => route('checkout.success', $order->id),
                'whatsapp_message' => $whatsappMessage,
                'whatsapp_number' => '+918544772623'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            \Log::error('Checkout error:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing your order: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get cart summary for API
    public function summary()
    {
        $sessionId = $this->getOrCreateSessionId();
        $cartItems = Cart::bySession($sessionId)
            ->with(['menuItem' => function ($query) {
                $query->with('restaurant');
            }])
            ->get();

        $subtotal = 0;
        $itemCount = 0;

        foreach ($cartItems as $item) {
            $subtotal += $item->total_price;
            $itemCount += $item->quantity;
        }

        // Get dynamic fee calculations
        $feeData = PlatformFeeService::getFeeDisplayData($subtotal);

        return response()->json([
            'success' => true,
            'cart_count' => $itemCount,
            'cart_subtotal' => '₹' . number_format($subtotal, 0),
            'cart_total' => $feeData['grand_total'],
            'fees' => $feeData['fees'],
            'has_fees' => $feeData['has_fees'],
            'items' => $cartItems
        ]);
    }

    // Checkout success page
    public function checkoutSuccess($orderId)
    {
        $order = Order::with(['orderItems.menuItem.restaurant'])->find($orderId);

        if (!$order) {
            return redirect()->route('home')->with('error', 'Order not found.');
        }

        return view('checkout-success', compact('order'));
    }

    // User orders page
    public function userOrders(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please login to view your orders.');
        }

        $status = $request->get('status', 'all');

        $orders = Order::with(['orderItems.menuItem'])
            ->where('user_id', $user->id)
            ->when($status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('order_list', compact('orders', 'status'));
    }

    /**
     * Generate WhatsApp message with complete order details
     */
    private function generateWhatsAppOrderMessage($order, $cartItems, $address, $feeData)
    {
        $message =  "New order received from Arya Meals\n\n";
        $message .= "*Order Details:*\n";
        $message .= "Order ID: #{$order->id}\n";
        $message .= "Customer: {$order->customer_name}\n";
        $message .= "Phone: {$order->customer_phone}\n\n";

        $message .= "*Delivery Address:*\n";
        $message .= "{$address->address_line_1}\n";
        if ($address->address_line_2) {
            $message .= "{$address->address_line_2}\n";
        }
        $message .= "{$address->city}, {$address->state} - {$address->postal_code}\n\n";

        $message .= "*Order Items:*\n";
        foreach ($cartItems as $item) {
            $restaurantName = $item->menuItem->restaurant->name ?? 'Unknown Restaurant';
            $portionSize = $item->portion_size == 'half' ? 'Half Plate' : 'Full Plate';
            $message .= "• {$item->quantity}x {$item->menuItem->name} ({$portionSize})\n";
            $message .= "  {$restaurantName}\n";
            $message .= "  Rs. " . number_format($item->total_price, 0) . "\n\n";
        }

        $message .= "*Payment Summary:*\n";
        if (isset($feeData['subtotal'])) {
            $message .= "Subtotal: {$feeData['subtotal']}\n";
        }
        if (isset($feeData['fees']) && !empty($feeData['fees'])) {
            foreach ($feeData['fees'] as $fee) {
                $message .= "{$fee['name']}: {$fee['amount']}\n";
            }
        }
        $message .= "Total Amount: {$feeData['grand_total']}\n\n";

        $message .= "*Order Date & Time:*\n";
        $message .= $order->created_at->format('d M Y, h:i A') . "\n\n";

        $message .= "*Status: Pending*\n\n";
        $message .= "_Please prepare this order for delivery._";

        return urlencode($message);
    }
}
