<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->integer('delivery_time')->nullable(); // in minutes
            $table->decimal('min_order', 8, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('rating', 3, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'category']);
            $table->index('rating');
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
};
