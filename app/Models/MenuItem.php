<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'restaurant_id',
        'name',
        'description',
        'category',
        'half_plate_price',
        'full_plate_price',
        'price',
        'preparation_time',
        'spice_level',
        'allergens',
        'calories',
        'image',
        'is_available',
        'is_recommended'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'is_recommended' => 'boolean',
        'half_plate_price' => 'decimal:2',
        'full_plate_price' => 'decimal:2',
        'price' => 'decimal:2',
        'preparation_time' => 'integer',
        'calories' => 'integer'
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeRecommended($query)
    {
        return $query->where('is_recommended', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeBySpiceLevel($query, $level)
    {
        return $query->where('spice_level', $level);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/menu-items/' . $this->image);
        }
        return asset('images/default-food.jpg');
    }

    public function getFormattedPriceAttribute()
    {
        return [
            'half' => '₹' . number_format($this->half_plate_price, 0),
            'full' => '₹' . number_format($this->full_plate_price, 0)
        ];
    }

    public function getSpiceLevelBadgeAttribute()
    {
        $badges = [
            'mild' => '🟢 Mild',
            'medium' => '🟡 Medium',
            'spicy' => '🟠 Spicy',
            'extra_spicy' => '🔴 Extra Spicy'
        ];
        
        return $badges[$this->spice_level] ?? '🟢 Mild';
    }
}
