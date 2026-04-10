<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PlatformFee extends Model
{
    use HasFactory;

    protected $fillable = [
        'fee_type',
        'fee_name',
        'fee_amount',
        'fee_percentage',
        'fee_type_calculation',
        'is_active',
        'description',
        'sort_order'
    ];

    protected $casts = [
        'fee_amount' => 'decimal:2',
        'fee_percentage' => 'decimal:2',
        'is_active' => 'boolean',
        'sort_order' => 'integer'
    ];

    // Scope for active fees
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered fees
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('fee_name', 'asc');
    }

    // Scope for specific fee type
    public function scopeByType($query, $feeType)
    {
        return $query->where('fee_type', $feeType);
    }

    // Get formatted amount
    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->fee_amount, 0);
    }

    // Get display label for frontend
    public function getDisplayLabelAttribute()
    {
        if ($this->fee_type_calculation === 'percentage') {
            return "{$this->fee_name} ({$this->fee_percentage}%)";
        }
        return $this->fee_name;
    }

    // Calculate fee amount based on subtotal
    public function calculateFee($subtotal)
    {
        if ($this->fee_type_calculation === 'percentage') {
            return $subtotal * ($this->fee_percentage / 100);
        }
        return $this->fee_amount;
    }

    // Get all active fees for cart calculation
    public static function getActiveFees()
    {
        return self::active()->ordered()->get();
    }

    // Get specific active fee by type
    public static function getActiveFeeByType($feeType)
    {
        return self::active()->byType($feeType)->first();
    }
}
