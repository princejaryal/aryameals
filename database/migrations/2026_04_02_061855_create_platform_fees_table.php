<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('platform_fees', function (Blueprint $table) {
            $table->id();
            $table->string('fee_type'); // delivery, tax, platform_fee, service_fee, etc.
            $table->string('fee_name'); // Display name for frontend
            $table->decimal('fee_amount', 8, 2)->nullable(); // Fixed amount (nullable for percentage fees)
            $table->decimal('fee_percentage', 5, 2)->nullable(); // Percentage if applicable
            $table->string('fee_type_calculation'); // 'fixed' or 'percentage'
            $table->boolean('is_active')->default(true); // Active/Inactive status
            $table->text('description')->nullable(); // Description for admin
            $table->integer('sort_order')->default(0); // Order in which fees appear
            $table->timestamps();
            
            // Add indexes
            $table->index(['fee_type', 'is_active']);
            $table->unique('fee_type'); // Each fee type should be unique
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('platform_fees');
    }
};
