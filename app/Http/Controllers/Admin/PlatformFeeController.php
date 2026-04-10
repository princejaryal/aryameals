<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformFee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PlatformFeeController extends Controller
{
    // Display all platform fees
    public function index()
    {
        $fees = PlatformFee::orderBy('sort_order', 'asc')->get();
        return view('admin.platform-fees.index', compact('fees'));
    }

    // Show form for creating new fee
    public function create()
    {
        return view('admin.platform-fees.create');
    }

    // Store new fee
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fee_type' => 'required|string|unique:platform_fees,fee_type',
            'fee_name' => 'required|string|max:255',
            'fee_type_calculation' => 'required|in:fixed,percentage',
            'fee_amount' => 'required_if:fee_type_calculation,fixed|nullable|numeric|min:0',
            'fee_percentage' => 'required_if:fee_type_calculation,percentage|nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0'
        ]);

        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        PlatformFee::create($validated);

        return redirect()->route('admin.platform-fees.index')
            ->with('success', 'Platform fee created successfully!');
    }

    // Show form for editing fee
    public function edit(PlatformFee $platformFee)
    {
        return view('admin.platform-fees.edit', compact('platformFee'));
    }

    // Update fee
    public function update(Request $request, PlatformFee $platformFee)
    {
        $validated = $request->validate([
            'fee_name' => 'required|string|max:255',
            'fee_type_calculation' => 'required|in:fixed,percentage',
            'fee_amount' => 'required_if:fee_type_calculation,fixed|nullable|numeric|min:0',
            'fee_percentage' => 'required_if:fee_type_calculation,percentage|nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'integer|min:0'
        ]);

        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $platformFee->update($validated);

        return redirect()->route('admin.platform-fees.index')
            ->with('success', 'Platform fee updated successfully!');
    }

    // Delete fee
    public function destroy(PlatformFee $platformFee)
    {
        $platformFee->delete();
        return response()->json([
            'success' => true,
            'message' => 'Platform fee deleted successfully!'
        ]);
    }

    // Toggle fee status (active/inactive)
    public function toggleStatus(PlatformFee $platformFee)
    {
        $platformFee->is_active = !$platformFee->is_active;
        $platformFee->save();

        $status = $platformFee->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Platform fee {$status} successfully!",
            'is_active' => $platformFee->is_active
        ]);
    }

    // Get fee details for AJAX
    public function show(PlatformFee $platformFee)
    {
        return response()->json([
            'success' => true,
            'fee' => $platformFee
        ]);
    }

    // Bulk actions
    public function bulkAction(Request $request)
    {
        $validated = $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'fee_ids' => 'required|array',
            'fee_ids.*' => 'exists:platform_fees,id'
        ]);

        $fees = PlatformFee::whereIn('id', $validated['fee_ids']);

        switch ($validated['action']) {
            case 'activate':
                $fees->update(['is_active' => true]);
                $message = 'Selected fees activated successfully!';
                break;
            case 'deactivate':
                $fees->update(['is_active' => false]);
                $message = 'Selected fees deactivated successfully!';
                break;
            case 'delete':
                $fees->delete();
                $message = 'Selected fees deleted successfully!';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    // Reorder fees
    public function reorder(Request $request)
    {
        $validated = $request->validate([
            'fee_orders' => 'required|array',
            'fee_orders.*.id' => 'exists:platform_fees,id',
            'fee_orders.*.sort_order' => 'integer|min:0'
        ]);

        foreach ($validated['fee_orders'] as $feeOrder) {
            PlatformFee::where('id', $feeOrder['id'])
                ->update(['sort_order' => $feeOrder['sort_order']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Fees reordered successfully!'
        ]);
    }
}
