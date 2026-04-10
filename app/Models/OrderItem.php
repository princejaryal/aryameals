<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'menu_item_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    // Accessor for formatted price
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    // Accessor for formatted subtotal
    public function getFormattedSubtotalAttribute()
    {
        return '₹' . number_format($this->price * $this->quantity, 2);
    }
}
