<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('category');
            $table->decimal('half_plate_price', 8, 2);
            $table->decimal('full_plate_price', 8, 2);
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->string('spice_level')->nullable(); // mild, medium, spicy, extra_spicy
            $table->string('allergens')->nullable();
            $table->integer('calories')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_available')->default(true);
            $table->boolean('is_recommended')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['restaurant_id', 'is_available']);
            $table->index('category');
            $table->index('spice_level');
        });
    }

    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
};
