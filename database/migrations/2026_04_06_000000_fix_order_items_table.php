<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['product_id']);
            // Drop the column
            $table->dropColumn('product_id');
            // Add menu_item_id column without FK
            $table->unsignedBigInteger('menu_item_id')->after('order_id');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('menu_item_id');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
        });
    }
};
