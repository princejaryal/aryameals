<?php

namespace App\Services;

use App\Models\PlatformFee;

class PlatformFeeService
{
    /**
     * Get all active fees for cart calculation
     */
    public static function getActiveFees()
    {
        return PlatformFee::active()->ordered()->get();
    }

    /**
     * Calculate total fees for a given subtotal
     */
    public static function calculateTotalFees($subtotal)
    {
        $fees = self::getActiveFees();
        $totalFees = 0;
        $feeBreakdown = [];

        foreach ($fees as $fee) {
            $feeAmount = $fee->calculateFee($subtotal);
            $totalFees += $feeAmount;
            
            $feeBreakdown[] = [
                'type' => $fee->fee_type,
                'name' => $fee->fee_name,
                'display_label' => $fee->display_label,
                'amount' => $feeAmount,
                'formatted_amount' => '₹' . number_format($feeAmount, 0),
                'calculation_type' => $fee->fee_type_calculation
            ];
        }

        return [
            'total_fees' => $totalFees,
            'total_fees_formatted' => '₹' . number_format($totalFees, 0),
            'fee_breakdown' => $feeBreakdown,
            'grand_total' => $subtotal + $totalFees,
            'grand_total_formatted' => '₹' . number_format($subtotal + $totalFees, 0)
        ];
    }

    /**
     * Get specific fee by type
     */
    public static function getFeeByType($feeType)
    {
        return PlatformFee::getActiveFeeByType($feeType);
    }

    /**
     * Check if a specific fee type is active
     */
    public static function isFeeActive($feeType)
    {
        return self::getFeeByType($feeType) !== null;
    }

    /**
     * Get formatted fee data for frontend display
     */
    public static function getFeeDisplayData($subtotal)
    {
        $feeData = self::calculateTotalFees($subtotal);
        
        return [
            'subtotal' => $subtotal,
            'subtotal_formatted' => '₹' . number_format($subtotal, 0),
            'fees' => $feeData['fee_breakdown'],
            'total_fees' => $feeData['total_fees_formatted'],
            'total_fees_numeric' => $feeData['total_fees'], // Add numeric value
            'grand_total' => '₹' . number_format(ceil($feeData['grand_total']), 0), // Apply ceil
            'grand_total_numeric' => ceil($feeData['grand_total']), // Apply ceil
            'has_fees' => count($feeData['fee_breakdown']) > 0
        ];
    }

    /**
     * Update fee status (active/inactive)
     */
    public static function updateFeeStatus($feeType, $isActive)
    {
        $fee = PlatformFee::where('fee_type', $feeType)->first();
        if ($fee) {
            $fee->is_active = $isActive;
            $fee->save();
            return true;
        }
        return false;
    }

    /**
     * Update fee amount/percentage
     */
    public static function updateFeeAmount($feeType, $amount, $percentage = null)
    {
        $fee = PlatformFee::where('fee_type', $feeType)->first();
        if ($fee) {
            $fee->fee_amount = $amount;
            $fee->fee_percentage = $percentage;
            $fee->save();
            return true;
        }
        return false;
    }
}
