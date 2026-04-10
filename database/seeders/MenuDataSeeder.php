<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Restaurant;
use App\Models\MenuItem;

class MenuDataSeeder extends Seeder
{
    public function run()
    {
        // Read JSON data
        $jsonPath = base_path('../public_html/data/data.json');
        if (!file_exists($jsonPath)) {
            $this->command->error('data.json file not found at: ' . $jsonPath);
            return;
        }
        
        $jsonData = json_decode(file_get_contents($jsonPath), true);
        
        if (!$jsonData || !isset($jsonData['restaurants']) || !isset($jsonData['categories'])) {
            $this->command->error('Invalid JSON structure');
            return;
        }
        
        // Create restaurants
        $restaurants = $jsonData['restaurants'] ?? [];
        foreach ($restaurants as $restaurantName) {
            Restaurant::updateOrCreate(
                ['name' => $restaurantName],
                [
                    'is_active' => true,
                    'address' => 'Chamba',
                    'phone' => '+918544772623',
                    'email' => strtolower(str_replace(' ', '', $restaurantName)) . '@aryameals.com',
                    'description' => "Quality food from {$restaurantName}",
                    'category' => 'General',
                ]
            );
        }
        
        // Create menu items
        $categories = $jsonData['categories'] ?? [];
        foreach ($categories as $category) {
            $items = $category['items'] ?? [];
            $categoryName = $category['category'];
            
            foreach ($items as $item) {
                // Find restaurant
                $restaurant = Restaurant::where('name', $item['restaurant'])->first();
                
                if ($restaurant) {
                    // Check if price exists, skip item if not
                    if (!isset($item['price']) || empty($item['price'])) {
                        $this->command->warn("Skipping item '{$item['name']}' - no price found");
                        continue;
                    }
                    
                    // Clean price - remove ₹ symbol and convert to decimal
                    $price = str_replace('₹', '', $item['price']);
                    $price = is_numeric($price) ? floatval($price) : 0.00;
                    
                    // Determine pricing strategy based on category
                    $isBeverage = in_array($categoryName, ['Beverages', 'Juices', 'Shakes', 'Tea & Coffee']);
                    
                    if ($isBeverage) {
                        $halfPlatePrice = $price;
                        $fullPlatePrice = $price * 1.5; // Beverages have different pricing
                    } else {
                        $halfPlatePrice = $price * 0.6; // Half plate is 60% of full price
                        $fullPlatePrice = $price;
                    }
                    
                    MenuItem::updateOrCreate(
                        [
                            'restaurant_id' => $restaurant->id,
                            'name' => $item['name']
                        ],
                        [
                            'category' => $categoryName,
                            'description' => $this->cleanDescription($item['description'] ?? ''),
                            'half_plate_price' => $halfPlatePrice,
                            'full_plate_price' => $fullPlatePrice,
                            'preparation_time' => $this->estimatePreparationTime($categoryName),
                            'spice_level' => $this->determineSpiceLevel($item, $categoryName),
                            'allergens' => $this->determineAllergens($item, $categoryName),
                            'calories' => $this->estimateCalories($categoryName),
                            'image' => $this->cleanImagePath($item['image'] ?? ''),
                            'is_available' => true,
                            'is_recommended' => $this->shouldBeRecommended($item, $categoryName)
                        ]
                    );
                }
            }
        }
        
        $this->command->info('Menu data seeded successfully!');
    }
    
    private function cleanDescription(string $description): string
    {
        // Remove restaurant name in parentheses at the end
        $description = preg_replace('/\s*\([^)]*\)\s*$/', '', $description);
        return trim($description);
    }
    
    private function cleanImagePath(string $imagePath): string
    {
        // Extract just the filename from the path
        if (empty($imagePath)) {
            return '';
        }
        
        // Remove 'images/' prefix and get just the filename
        $filename = basename($imagePath);
        
        // If the filename starts with 'images/', remove it
        if (strpos($filename, 'images/') === 0) {
            $filename = substr($filename, 7);
        }
        
        return $filename;
    }
    
    private function estimatePreparationTime(string $category): int
    {
        $times = [
            'Biryani' => 25,
            'Rice & Fried Rice' => 20,
            'Starters' => 15,
            'Main Course' => 25,
            'Beverages' => 5,
            'Juices' => 8,
            'Shakes' => 10,
            'Tea & Coffee' => 5,
            'Desserts' => 10,
            'Soups' => 12,
            'Salads' => 8,
            'Chinese' => 20,
            'Pizza' => 20,
            'Burgers' => 15,
            'Sandwiches' => 10,
            'Pasta' => 18,
            'Noodles' => 15,
        ];
        
        return $times[$category] ?? 20;
    }
    
    private function determineSpiceLevel(array $item, string $category): ?string
    {
        $name = strtolower($item['name']);
        
        if (strpos($name, 'spicy') !== false || strpos($name, 'hot') !== false) {
            return 'spicy';
        }
        
        if (strpos($name, 'mild') !== false || strpos($name, 'plain') !== false) {
            return 'mild';
        }
        
        $spicyCategories = ['Biryani', 'Starters', 'Main Course', 'Chinese'];
        if (in_array($category, $spicyCategories)) {
            return 'medium';
        }
        
        $nonSpicyCategories = ['Beverages', 'Juices', 'Shakes', 'Tea & Coffee', 'Desserts'];
        if (in_array($category, $nonSpicyCategories)) {
            return null;
        }
        
        return 'medium';
    }
    
    private function determineAllergens(array $item, string $category): ?string
    {
        $name = strtolower($item['name']);
        $allergens = [];
        
        if (strpos($name, 'nut') !== false || strpos($name, 'almond') !== false) {
            $allergens[] = 'nuts';
        }
        
        if (strpos($name, 'dairy') !== false || strpos($name, 'cheese') !== false || strpos($name, 'milk') !== false) {
            $allergens[] = 'dairy';
        }
        
        if (strpos($name, 'gluten') !== false || strpos($name, 'wheat') !== false) {
            $allergens[] = 'gluten';
        }
        
        return empty($allergens) ? null : implode(', ', $allergens);
    }
    
    private function estimateCalories(string $category): ?int
    {
        $calories = [
            'Biryani' => 350,
            'Rice & Fried Rice' => 250,
            'Starters' => 200,
            'Main Course' => 300,
            'Beverages' => 80,
            'Juices' => 120,
            'Shakes' => 250,
            'Tea & Coffee' => 30,
            'Desserts' => 180,
            'Soups' => 100,
            'Salads' => 80,
            'Chinese' => 280,
            'Pizza' => 320,
            'Burgers' => 400,
            'Sandwiches' => 250,
            'Pasta' => 350,
            'Noodles' => 300,
        ];
        
        return $calories[$category] ?? null;
    }
    
    private function shouldBeRecommended(array $item, string $category): bool
    {
        $name = strtolower($item['name']);
        
        // Mark items with "special", "chef", "signature" as recommended
        if (strpos($name, 'special') !== false || 
            strpos($name, 'chef') !== false || 
            strpos($name, 'signature') !== false) {
            return true;
        }
        
        // Randomly recommend 20% of items
        return rand(1, 100) <= 20;
    }
}
