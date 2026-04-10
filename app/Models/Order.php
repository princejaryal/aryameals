<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'customer_name',
        'customer_phone',
        'customer_address',
        'payment_method',
        'payment_type',
        'utr_number',
        'status',
    ];

    // Order items as array in the same table
    protected $casts = [
        'items' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Accessor for formatted items
    public function getFormattedItemsAttribute()
    {
        $items = $this->orderItems()->with('menuItem')->get();

        return $items->map(function ($orderItem) {
            return [
                'name' => $orderItem->menuItem->name ?? 'Unknown Item',
                'quantity' => $orderItem->quantity,
                'price' => $orderItem->price,
                'image' => $orderItem->menuItem->image ?? null,
                'special_instructions' => $orderItem->special_instructions ?? null
            ];
        })->toArray();
    }

    // Mutator for items storage
    public function setItemsAttribute($value)
    {
        $this->attributes['items'] = is_array($value) ? json_encode($value) : $value;
    }

    // Calculate total from items
    public function calculateTotal()
    {
        $items = $this->formatted_items;
        $total = 0;

        foreach ($items as $item) {
            $total += ($item['price'] * $item['quantity']);
        }

        return $total;
    }
}
