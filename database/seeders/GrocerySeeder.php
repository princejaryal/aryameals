<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\MenuItem;
use App\Models\Restaurant;

class GrocerySeeder extends Seeder
{
    public function run()
    {
        // Get Arya Grocery restaurant ID
        $restaurant = Restaurant::where('name', 'Arya Grocery')->first();
        
        if (!$restaurant) {
            $this->command->error('Arya Grocery restaurant not found!');
            return;
        }

        // Read grocery data from JSON file
        $jsonPath = base_path('../public_html/data/grocery.json');
        
        if (!file_exists($jsonPath)) {
            $this->command->error('Grocery JSON file not found at: ' . $jsonPath);
            return;
        }

        $groceryData = json_decode(file_get_contents($jsonPath), true);
        
        if (!$groceryData) {
            $this->command->error('Failed to parse grocery JSON data!');
            return;
        }

        // Clear existing grocery items for Arya Grocery
        DB::table('menu_items')->where('restaurant_id', $restaurant->id)->delete();

        $menuItems = [];
        
        foreach ($groceryData as $item) {
            // Convert price string to float (remove all currency symbols and convert)
            $originalPrice = $item['price'];
            $price = floatval(preg_replace('/[^0-9.]/', '', $originalPrice));
            
            // Extract just the image name from path
            $imageName = basename($item['image']);
            
            $menuItems[] = [
                'restaurant_id' => $restaurant->id,
                'name' => $item['name'],
                'description' => $item['description'],
                'category' => 'add-ons & extras', // Set all items to add-ons & extras category
                'half_plate_price' => $price / 2, // Half price for half portion
                'full_plate_price' => $price, // Set same price for both
                'unit' => $item['unit'],
                'image' => $imageName,
                'is_available' => true,
                'is_recommended' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Insert menu items in batches
        DB::table('menu_items')->insert($menuItems);

        $this->command->info('Grocery seeder completed successfully!');
        $this->command->info('Seeded ' . count($menuItems) . ' grocery items for Arya Grocery restaurant.');
        $this->command->info('All items set to category: add-ons & extras');
    }
}
