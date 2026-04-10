<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Restaurant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'description',
        'image',
        'is_active',
        'rating',
        'category'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rating' => 'decimal:2'
    ];

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }

    public function availableMenuItems()
    {
        return $this->hasMany(MenuItem::class)->where('is_available', true);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getActiveMenuItemsCount()
    {
        return $this->menuItems()->where('is_available', true)->count();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/restaurants/' . $this->image);
        }
        return asset('images/default-restaurant.jpg');
    }
}
