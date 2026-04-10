<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlatformFee;

class PlatformFeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fees = [
            [
                'fee_type' => 'delivery',
                'fee_name' => 'Delivery Fee',
                'fee_amount' => 49.00,
                'fee_percentage' => null,
                'fee_type_calculation' => 'fixed',
                'is_active' => true,
                'description' => 'Standard delivery fee for all orders',
                'sort_order' => 1,
            ],
            [
                'fee_type' => 'tax',
                'fee_name' => 'GST Tax',
                'fee_amount' => null,
                'fee_percentage' => 5.00,
                'fee_type_calculation' => 'percentage',
                'is_active' => false, // Inactive as requested
                'description' => 'Goods and Services Tax (currently inactive)',
                'sort_order' => 2,
            ],
            [
                'fee_type' => 'platform_fee',
                'fee_name' => 'Platform Fee',
                'fee_amount' => 10.00,
                'fee_percentage' => null,
                'fee_type_calculation' => 'fixed',
                'is_active' => false, // Inactive by default
                'description' => 'Platform usage fee (currently inactive)',
                'sort_order' => 3,
            ],
            [
                'fee_type' => 'service_fee',
                'fee_name' => 'Service Charge',
                'fee_amount' => null,
                'fee_percentage' => 2.00,
                'fee_type_calculation' => 'percentage',
                'is_active' => false, // Inactive by default
                'description' => 'Service charge (currently inactive)',
                'sort_order' => 4,
            ],
        ];

        foreach ($fees as $fee) {
            PlatformFee::updateOrCreate(
                ['fee_type' => $fee['fee_type']],
                $fee
            );
        }
    }
}
