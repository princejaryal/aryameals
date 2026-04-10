<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    public function run()
    {
        // Clear existing restaurants
        DB::table('restaurants')->delete();
        
        $restaurants = [
            [
                'name' => 'Ajay Bhojnalya',
                'email' => 'ajay@bhojnalya.com',
                'phone' => '+91-9876543210',
                'address' => '123 Main Street, Mumbai, Maharashtra',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'category' => 'Indian',
                'delivery_time' => 30,
                'min_order' => 150.00,
                'description' => 'Authentic Indian cuisine serving traditional biryani and specialty dishes',
                'image' => 'ajay-bhojnalya.jpg',
                'is_active' => true,
                'rating' => 4.2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cloud9',
                'email' => 'info@cloud9.com',
                'phone' => '+91-9876543211',
                'address' => '456 Park Avenue, Delhi',
                'city' => 'New Delhi',
                'state' => 'Delhi',
                'category' => 'Cafe',
                'delivery_time' => 25,
                'min_order' => 200.00,
                'description' => 'Modern cafe serving premium coffee, sandwiches and light meals',
                'image' => 'cloud9.jpg',
                'is_active' => true,
                'rating' => 4.5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'CrisPizza',
                'email' => 'orders@crispizza.com',
                'phone' => '+91-9876543212',
                'address' => '789 Pizza Street, Chennai',
                'city' => 'Chennai',
                'state' => 'Tamil Nadu',
                'category' => 'Pizza',
                'delivery_time' => 35,
                'min_order' => 250.00,
                'description' => 'Authentic wood-fired pizzas with fresh ingredients and traditional recipes',
                'image' => 'crispizza.jpg',
                'is_active' => true,
                'rating' => 4.0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'New Light',
                'email' => 'hello@newlight.com',
                'phone' => '+91-9876543213',
                'address' => '321 Light Lane, Bangalore',
                'city' => 'Bangalore',
                'state' => 'Karnataka',
                'category' => 'Multi-cuisine',
                'delivery_time' => 40,
                'min_order' => 300.00,
                'description' => 'Contemporary restaurant offering diverse menu from Indian to Chinese cuisine',
                'image' => 'newlight.jpg',
                'is_active' => true,
                'rating' => 3.8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Olive Bistro and Cafe',
                'email' => 'contact@olivebistro.com',
                'phone' => '+91-9876543214',
                'address' => '654 Olive Road, Pune',
                'city' => 'Pune',
                'state' => 'Maharashtra',
                'category' => 'Bistro',
                'delivery_time' => 45,
                'min_order' => 180.00,
                'description' => 'Cozy bistro serving Mediterranean and continental cuisine in a relaxed atmosphere',
                'image' => 'olive-bistro.jpg',
                'is_active' => true,
                'rating' => 4.3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Rinku Chicken Corner',
                'email' => 'info@rinkuchicken.com',
                'phone' => '+91-9876543215',
                'address' => '987 Chicken Street, Lucknow',
                'city' => 'Lucknow',
                'state' => 'Uttar Pradesh',
                'category' => 'Specialty',
                'delivery_time' => 30,
                'min_order' => 120.00,
                'description' => 'Specialized chicken restaurant famous for tandoori and kebabs',
                'image' => 'rinku-chicken.jpg',
                'is_active' => true,
                'rating' => 4.1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Arya Grocery',
                'email' => 'support@aryagrocery.com',
                'phone' => '+91-9876543216',
                'address' => '147 Grocery Market, Hyderabad',
                'city' => 'Hyderabad',
                'state' => 'Telangana',
                'category' => 'Grocery',
                'delivery_time' => 60,
                'min_order' => 500.00,
                'description' => 'Premium grocery store offering fresh vegetables, fruits and daily essentials',
                'image' => 'arya-grocery.jpg',
                'is_active' => true,
                'rating' => 3.9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Grill Inn',
                'email' => 'reservations@grillinn.com',
                'phone' => '+91-9876543217',
                'address' => '258 Grill Highway, Kolkata',
                'city' => 'Kolkata',
                'state' => 'West Bengal',
                'category' => 'Grill & BBQ',
                'delivery_time' => 50,
                'min_order' => 400.00,
                'description' => 'Premium grill house specializing in barbecue and grilled delicacies',
                'image' => 'grill-inn.jpg',
                'is_active' => true,
                'rating' => 4.6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert restaurants
        foreach ($restaurants as $restaurant) {
            Restaurant::create($restaurant);
        }

        $this->command->info('Restaurant seeder completed successfully!');
        $this->command->info('Seeded ' . count($restaurants) . ' restaurants into the database.');
    }
}
