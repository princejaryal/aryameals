<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'carts'; // Use correct table name

    protected $fillable = [
        'user_id',
        'session_id',
        'menu_item_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'quantity',
        'portion_size',
        'price',
        'total_price',
        'special_instructions'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 0);
    }

    public function getFormattedTotalPriceAttribute()
    {
        return '₹' . number_format($this->total_price, 0);
    }

    public function getPortionDisplayAttribute()
    {
        return ucfirst($this->portion_size) . ' Plate';
    }

    // Scope for getting cart by session
    public function scopeBySession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    // Scope for getting cart items with menu items
    public function scopeWithMenuItem($query)
    {
        return $query->with('menuItem');
    }

    // Alternative scope name for consistency
    public function scopeWithMenuItems($query)
    {
        return $query->with('menuItem');
    }
}
