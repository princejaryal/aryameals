<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    public function index()
    {
        $restaurants = Restaurant::withCount('menuItems')
            ->with(['menuItems' => function($query) {
                $query->available();
            }])
            ->orderBy('created_at', 'desc') // Newest first
            ->orderBy('name') // Then by name
            ->paginate(10);

        $stats = [
            'total' => Restaurant::count(),
            'active' => Restaurant::active()->count(),
            'total_menu_items' => MenuItem::count(),
            'avg_rating' => Restaurant::avg('rating') ?: 0
        ];

        return view('admin.restaurants.index', compact('restaurants', 'stats'));
    }

    public function apiIndex()
    {
        $restaurants = Restaurant::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'category', 'city', 'phone', 'image', 'description', 'rating', 'is_active']);
            
        // Add full image path to each restaurant
        $restaurants->each(function ($restaurant) {
            if ($restaurant->image) {
                $restaurant->image_url = url('storage/restaurants/' . $restaurant->image);
            } else {
                $restaurant->image_url = 'https://via.placeholder.com/300x200';
            }
        });
            
        return response()->json([
            'success' => true,
            'restaurants' => $restaurants
        ]);
    }

    public function create()
    {
        return view('admin.restaurants.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:indian,chinese,italian,mexican,thai,american',
            'email' => 'required|email|unique:restaurants',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'required|image|max:10240'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('restaurants', $imageName, 'public');
            $validated['image'] = $imageName;
        }

        $validated['rating'] = $request->input('rating', 5);
        $validated['is_active'] = $request->has('is_active') ? 1 : 0;
        $restaurant = Restaurant::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Restaurant added successfully!',
                'restaurant' => $restaurant
            ]);
        }

        return redirect()
            ->route('admin.restaurants')
            ->with('success', 'Restaurant saved successfully!');
    }

    public function show($id)
    {
        $restaurant = Restaurant::with(['menuItems'])->findOrFail($id);

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'restaurant' => $restaurant
            ]);
        }

        return view('admin.restaurants.show', compact('restaurant'));
    }

    public function showBySlug($id)
    {
        $restaurant = Restaurant::with(['availableMenuItems'])
            ->findOrFail($id);

        // Process menu items to handle single price vs dual prices
        $menuItems = $restaurant->availableMenuItems->map(function ($item) {
            $item->has_single_price = $item->price !== null;
            return $item;
        });

        $restaurant->availableMenuItems = $menuItems;

        return view('restaurant-detail', compact('restaurant'));
    }

    public function edit($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:indian,chinese,italian,mexican,thai,american',
            'email' => 'required|email|unique:restaurants,email,' . $id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:10240',
            'rating' => 'required|numeric|min:1|max:5',
            'is_active' => 'boolean'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($restaurant->image) {
                Storage::disk('public')->delete('restaurants/' . $restaurant->image);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
            $image->storeAs('restaurants', $imageName, 'public');
            $validated['image'] = $imageName;
        }

        $restaurant->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Restaurant updated successfully!',
            'restaurant' => $restaurant
        ]);
    }

    public function destroy($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        
        // Check if restaurant has existing orders
        if ($restaurant->orders()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete restaurant with existing orders. Please cancel or complete all orders first.'
            ], 422);
        }
        
        // Delete related menu items and their images
        $menuItems = $restaurant->menuItems;
        foreach ($menuItems as $menuItem) {
            // Delete menu item image
            if ($menuItem->image) {
                Storage::disk('public')->delete('menu-items/' . $menuItem->image);
            }
            $menuItem->forceDelete(); // Actually delete menu item
        }
        
        // Delete restaurant image
        if ($restaurant->image) {
            Storage::disk('public')->delete('restaurants/' . $restaurant->image);
        }

        $restaurant->forceDelete(); // Actually delete restaurant

        return response()->json([
            'success' => true,
            'message' => 'Restaurant and all related menu items deleted successfully!'
        ]);
    }

    public function toggleStatus($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->is_active = !$restaurant->is_active;
        $restaurant->save();

        return response()->json([
            'success' => true,
            'message' => "Restaurant status updated to " . ($restaurant->is_active ? 'Active' : 'Inactive'),
            'status' => $restaurant->is_active
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('home');
        }

        // Search in restaurants
        $restaurants = Restaurant::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%");
            })
            ->with(['menuItems' => function($q) {
                $q->where('is_available', true);
            }])
            ->get();

        // Search in menu items
        $menuItems = MenuItem::where('is_available', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('category', 'like', "%{$query}%");
            })
            ->with('restaurant')
            ->get();

        return view('search-results', compact('restaurants', 'menuItems', 'query'));
    }
}
