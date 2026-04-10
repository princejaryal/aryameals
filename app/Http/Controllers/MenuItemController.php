<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index(Request $request, $restaurantId = null)
    {
        $query = MenuItem::with('restaurant');

        // Filter by restaurant if provided
        if ($restaurantId) {
            $query->where('restaurant_id', $restaurantId);
            $restaurant = Restaurant::findOrFail($restaurantId);
        } else {
            $restaurant = null;
        }

        // Filter by category if provided
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by availability if provided
        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where('is_available', true);
            } elseif ($request->availability === 'unavailable') {
                $query->where('is_available', false);
            }
        }

        // Sort by newest first, then by category and name
        $menuItems = $query->orderBy('created_at', 'desc')
                           ->orderBy('category')
                           ->orderBy('name')
                           ->paginate(15);

        // Get all restaurants for filter dropdown
        $restaurants = Restaurant::orderBy('name')->get();

        // Get all categories for filter dropdown
        $categories = MenuItem::distinct('category')->pluck('category')->filter();

        $stats = [
            'total' => MenuItem::count(),
            'available' => MenuItem::available()->count(),
            'categories' => MenuItem::distinct('category')->count('category'),
            'avg_price' => MenuItem::avg('full_plate_price') ?: 0
        ];

        return view('admin.menu.index', compact('menuItems', 'restaurant', 'restaurants', 'categories', 'stats'));
    }
    
    public function create()
    {
        $restaurants = Restaurant::orderBy('name')->get();
        return view('admin.menu.create', compact('restaurants'));
    }
    
    public function edit($id)
    {
        $menuItem = MenuItem::with('restaurant')->findOrFail($id);
        $restaurants = Restaurant::orderBy('name')->get();
        return view('admin.menu.edit', compact('menuItem', 'restaurants'));
    }

    public function store(Request $request)
    {
        $priceType = $request->input('price_type', 'dual');
        
        $validationRules = [
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'preparation_time' => 'nullable|integer|min:5|max:60',
            'spice_level' => 'nullable|in:mild,medium,spicy,extra_spicy',
            'allergens' => 'nullable|string|max:255',
            'calories' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:5120',
            'is_available' => 'boolean',
            'is_recommended' => 'boolean'
        ];
        
        
        $validated = $request->validate($validationRules);
        
        // Add remaining fields to validated data
        $validated['spice_level'] = $request->input('spice_level');
        $validated['allergens'] = $request->input('allergens');
        $validated['calories'] = $request->input('calories');
        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_recommended'] = $request->boolean('is_recommended');
        
        // Add nullable fields with default values if not provided
        $validated['category'] = $request->input('category', 'general');
        
        // Handle pricing based on price type selection
        $priceType = $request->input('price_type', 'dual');
        if ($priceType === 'single') {
            $validated['price'] = $request->input('single_price');
            $validated['half_plate_price'] = 0;
            $validated['full_plate_price'] = 0;
        } else {
            $validated['price'] = 0;
            $validated['half_plate_price'] = $request->input('half_plate_price') ?: 0;
            $validated['full_plate_price'] = $request->input('full_plate_price') ?: 0;
        }
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('menu-items', $imageName, 'public');
            $validated['image'] = $imageName;
        }

        $menuItem = MenuItem::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu item added successfully!',
            'menu_item' => $menuItem->load('restaurant')
        ]);
    }

    public function show($id)
    {
        $menuItem = MenuItem::with('restaurant')->findOrFail($id);

        return response()->json([
            'success' => true,
            'menu_item' => $menuItem
        ]);
    }

    public function detail($id)
    {
        $menuItem = MenuItem::with(['restaurant', 'approvedReviews'])->findOrFail($id);
        
        // Add flag for single price items
        $menuItem->has_single_price = $menuItem->price !== null;
        
        // Get related items from the same restaurant (excluding current item)
        $relatedItems = MenuItem::where('restaurant_id', $menuItem->restaurant_id)
                                ->where('id', '!=', $menuItem->id)
                                ->where('is_available', true)
                                ->inRandomOrder()
                                ->limit(8)
                                ->get();
        
        // Get similar items from same category
        $similarItems = MenuItem::where('category', $menuItem->category)
                                ->where('id', '!=', $menuItem->id)
                                ->where('is_available', true)
                                ->inRandomOrder()
                                ->limit(4)
                                ->get();

        return view('item-detail', compact('menuItem', 'relatedItems', 'similarItems'));
    }

    public function update(Request $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'half_plate_price' => 'nullable|numeric|min:0',
            'full_plate_price' => 'nullable|numeric|min:0',
            'preparation_time' => 'nullable|integer|min:5|max:60',
            'spice_level' => 'nullable|in:mild,medium,spicy,extra_spicy',
            'allergens' => 'nullable|string|max:255',
            'calories' => 'nullable|integer|min:0',
            'image' => 'nullable|image|max:5120',
            'is_available' => 'boolean',
            'is_recommended' => 'boolean'
        ]);

        // Handle pricing based on price type selection
        $priceType = $request->input('price_type', 'dual');
        if ($priceType === 'single') {
            $validated['price'] = $request->input('single_price');
            $validated['half_plate_price'] = 0;
            $validated['full_plate_price'] = 0;
        } else {
            $validated['price'] = 0;
            $validated['half_plate_price'] = $request->input('half_plate_price') ?: 0;
            $validated['full_plate_price'] = $request->input('full_plate_price') ?: 0;
        }

        if ($request->hasFile('image')) {
            // Delete old image
            if ($menuItem->image) {
                Storage::disk('public')->delete('menu-items/' . $menuItem->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('menu-items', $imageName, 'public');
            $validated['image'] = $imageName;
        }

        $menuItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Menu item updated successfully!',
            'menu_item' => $menuItem->load('restaurant')
        ]);
    }

    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        
        // Delete image
        if ($menuItem->image) {
            Storage::disk('public')->delete('menu-items/' . $menuItem->image);
        }

        // Use forceDelete() because SoftDeletes is enabled
        $menuItem->forceDelete();
        
        return response()->json([
            'success' => true,
            'message' => 'Menu item deleted successfully!'
        ]);
    }

    public function toggleAvailability($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->is_available = !$menuItem->is_available;
        $menuItem->save();

        return response()->json([
            'success' => true,
            'message' => "Menu item availability updated to " . ($menuItem->is_available ? 'Available' : 'Unavailable'),
            'status' => $menuItem->is_available
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $status = $request->get('status');
        $restaurantId = $request->get('restaurant_id');

        $menuItems = MenuItem::with('restaurant')
            ->when($query, function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%');
            })
            ->when($category, function($q) use ($category) {
                $q->byCategory($category);
            })
            ->when($status === 'available', function($q) {
                $q->available();
            })
            ->when($status === 'unavailable', function($q) {
                $q->where('is_available', false);
            })
            ->when($restaurantId, function($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId);
            })
            ->orderBy('name')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'menu_items' => $menuItems
        ]);
    }

    // API Methods
    public function apiIndex(Request $request)
    {
        $query = MenuItem::where('is_available', true);

        // Filter by restaurant if provided
        if ($request->has('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        // Filter by category if provided
        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $menuItems = $query->with('restaurant')
                           ->orderBy('name')
                           ->get();

        // Add full image path to each menu item
        $menuItems->each(function ($item) {
            if ($item->image) {
                $item->image_url = url('storage/menu-items/' . $item->image);
            } else {
                $item->image_url = 'https://via.placeholder.com/300x200';
            }
        });

        return response()->json([
            'success' => true,
            'menu_items' => $menuItems
        ]);
    }

    public function apiCategories()
    {
        $categories = MenuItem::where('is_available', true)
                             ->distinct('category')
                             ->pluck('category')
                             ->filter()
                             ->values();

        return response()->json($categories);
    }

    public function apiByCategory($category)
    {
        $menuItems = MenuItem::where('category', $category)
                             ->where('is_available', true)
                             ->with('restaurant')
                             ->orderBy('name')
                             ->get();

        // Add full image path to each menu item
        $menuItems->each(function ($item) {
            if ($item->image) {
                $item->image_url = url('storage/menu-items/' . $item->image);
            } else {
                $item->image_url = 'https://via.placeholder.com/300x200';
            }
        });

        return response()->json($menuItems);
    }

    public function apiByRestaurant($restaurantId)
    {
        $menuItems = MenuItem::where('restaurant_id', $restaurantId)
                             ->where('is_available', true)
                             ->with('restaurant')
                             ->orderBy('name')
                             ->get();

        // Add full image path to each menu item
        $menuItems->each(function ($item) {
            if ($item->image) {
                $item->image_url = url('storage/menu-items/' . $item->image);
            } else {
                $item->image_url = 'https://via.placeholder.com/300x200';
            }
        });

        return response()->json($menuItems);
    }

    public function storeReview(Request $request, $menuItemId)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string|max:1000'
        ]);

        $menuItem = MenuItem::findOrFail($menuItemId);
        
        $review = Review::create([
            'menu_item_id' => $menuItemId,
            'customer_name' => $validated['customer_name'],
            'customer_email' => $validated['customer_email'],
            'rating' => $validated['rating'],
            'review_text' => $validated['review_text'],
            'is_approved' => false // Reviews need admin approval
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thank you for your review! It will be visible after approval.',
            'review' => $review
        ]);
    }
}
