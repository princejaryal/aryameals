<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display the specified category and its menu items.
     */
    public function show($category)
    {
        // Get menu items for the specific category from database
        $menuItems = MenuItem::with('restaurant')
            ->byCategory($category)
            ->whereHas('restaurant', function ($query) {
                return $query->where('is_active', true); // Only get items from active restaurants
            })
            ->available()
            ->get()
            ->map(function ($item) use ($category) {
                $result = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'price' => $item->price ?? $item->full_plate_price,
                    'half_plate_price' => $item->half_plate_price,
                    'full_plate_price' => $item->full_plate_price,
                    'description' => $item->description,
                    'image' => $item->image ? basename($item->image) : '5-1-1.jpg',
                    'type' => $this->getVegNonVegType($item->spice_level),
                    'restaurant' => $item->restaurant ? $item->restaurant->name : 'Arya Meals Kitchen',
                    'has_single_price' => $item->price !== null
                ];
                
                // Add unit information for grocery items
                if ($item->restaurant && $item->restaurant->name === 'Arya Meals' && $category === 'add-ons-&-extras') {
                    // Map grocery items to their units
                    $units = [
                        'Tomatoes' => '1 kg', 'Onions' => '1 kg', 'Potatoes' => '1 kg', 'Carrots' => '1 kg',
                        'Green Chilies' => '250 g', 'Lemon' => '1 kg', 'Garlic' => '1 kg', 'Ginger' => '1 kg',
                        'Apples' => '1 kg', 'Oranges' => '1 kg', 'Mangoes' => '1 kg', 'Grapes' => '1 kg', 'Pomegranate' => '1 kg',
                        'Cheese' => '200 g', 'Paneer' => '200 g', 'Butter Naan' => '4 pcs', 'Biscuits' => '1 pack',
                        'Basmati Rice' => '1 kg', 'Chana Dal' => '1 kg', 'Toor Dal' => '1 kg', 'Moong Dal' => '1 kg',
                        'Sunflower Oil' => '1 litre', 'Turmeric Powder' => '200 g', 'Red Chili Powder' => '200 g',
                        'Coriander Powder' => '200 g', 'Cumin Seeds' => '200 g', 'Cooking Masala' => '200 g', 'Sambar Powder' => '200 g'
                    ];
                    
                    $result['unit'] = $units[$item->name] ?? null;
                }
                
                return $result;
            });

        // Get category info for display
        $categoryInfo = $this->getCategoryInfo($category);
        
        return view('category', compact('categoryInfo', 'menuItems'));
    }
    
    /**
     * Get category information for display
     */
    private function getCategoryInfo($category)
    {
        $categories = [
            'fried-chicken' => ['name' => 'Fried Chicken', 'description' => 'Crispy golden fried chicken with special herbs and spices, perfectly cooked for maximum flavor and tenderness'],
            'salads' => ['name' => 'Salads', 'description' => 'Fresh and healthy salad options with crisp vegetables, nutritious ingredients and delicious dressings'],
            'wraps' => ['name' => 'Wraps', 'description' => 'Delicious wraps with various fillings, fresh vegetables and flavorful sauces wrapped in soft tortillas'],
            'add-ons-&-extras' => ['name' => 'Groceries & Essentials', 'description' => 'Fresh grocery items and daily essentials including vegetables, fruits, spices and pantry staples'],
            'burgers' => ['name' => 'Burgers', 'description' => 'Juicy burgers with various toppings and fillings, premium patties, fresh vegetables and special sauces'],
            'groceries' => ['name' => 'Groceries', 'description' => 'Daily grocery items and essentials including fresh produce, dairy products and household necessities'],
            'pizza' => ['name' => 'Pizza', 'description' => 'Variety of delicious pizzas with fresh toppings, melted cheese and crispy thin or thick crust options'],
            'non-veg' => ['name' => 'Non Veg', 'description' => 'Tasty non-vegetarian dishes with premium quality meat, aromatic spices and authentic flavors'],
            'burger' => ['name' => 'Burger', 'description' => 'Juicy burgers with various toppings, fresh ingredients and flavorful condiments in soft buns'],
            'drinks' => ['name' => 'Drinks', 'description' => 'Refreshing drinks and beverages including fresh juices, soft drinks, shakes and specialty beverages'],
            'biryani' => ['name' => 'Biryani', 'description' => 'Flavorful biryani varieties with aromatic basmati rice, tender meat and authentic Indian spices'],
            'spring-roll' => ['name' => 'Spring Roll', 'description' => 'Crispy spring rolls with vegetable or meat fillings, served with dipping sauces and seasonings'],
            'fish' => ['name' => 'Fish', 'description' => 'Fresh fish preparations with delicate flavors, healthy nutrients and various cooking styles'],
            'dhaam' => ['name' => 'Dhaam', 'description' => 'Traditional Himachali Dhaam with authentic local flavors, festive dishes and cultural heritage'],
            'omelette' => ['name' => 'Omelette', 'description' => 'Variety of omelettes with fresh eggs, vegetables, cheese and different cooking styles and toppings'],
            'malai-champ' => ['name' => 'Malai Champ', 'description' => 'Sweet malai champ with rich cream, traditional flavors and delightful texture for dessert lovers'],
            'egg-bhurji' => ['name' => 'Egg Bhurji', 'description' => 'Spicy egg bhurji with scrambled eggs, aromatic spices, onions and tomatoes for hearty breakfast'],
            'chocolate' => ['name' => 'Chocolate', 'description' => 'Chocolate items and desserts with rich cocoa, sweet flavors and various chocolate preparations'],
            'sandwich' => ['name' => 'Sandwich', 'description' => 'Fresh sandwiches with quality bread, vegetables, meats and delicious spreads for quick meals'],
            'momos' => ['name' => 'Momos', 'description' => 'Steamed and fried momos with vegetable or meat fillings, served with spicy chutney and sauces'],
            'paneer' => ['name' => 'Paneer', 'description' => 'Delicious paneer dishes with fresh cottage cheese, rich gravies and authentic Indian flavors'],
            'roti' => ['name' => 'Roti', 'description' => 'Fresh Indian breads including chapati, naan, paratha and other traditional flatbreads'],
            'rice' => ['name' => 'Rice', 'description' => 'Various rice preparations including biryani, pulao, fried rice and traditional Indian rice dishes'],
            'soup' => ['name' => 'Soup', 'description' => 'Hot and soups with fresh vegetables, meats, aromatic herbs and comforting flavors for any season'],
            'pasta' => ['name' => 'Pasta', 'description' => 'Italian pasta dishes with various sauces, fresh ingredients and authentic Mediterranean flavors'],
            'raita' => ['name' => 'Raita', 'description' => 'Fresh raita varieties with yogurt, vegetables, spices and cooling effects for perfect accompaniment'],
            'kheer' => ['name' => 'Kheer', 'description' => 'Traditional Indian kheer with rice pudding, milk, nuts and aromatic cardamom for sweet dessert']
        ];
        
        return $categories[$category] ?? ['name' => 'Category', 'description' => 'Category items'];
    }
    
    /**
     * Determine veg/non-veg type based on spice level
     */
    private function getVegNonVegType($spiceLevel)
    {
        // Non-veg spice levels
        $nonVegLevels = ['medium', 'spicy', 'extra_spicy'];
        
        return in_array($spiceLevel, $nonVegLevels) ? 'non-veg' : 'veg';
    }
}
