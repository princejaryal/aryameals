<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Get all addresses for the authenticated user.
     */
    public function index()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $addresses = Address::where('user_id', Auth::id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'addresses' => $addresses
        ]);
    }

    /**
     * Store a new address.
     */
    public function store(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login to add an address',
                'debug' => 'User not authenticated'
            ], 401);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'postal_code' => 'required|string|max:20',
                'country' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
                'is_default' => 'nullable|boolean',
                'type' => 'nullable|string|in:delivery,billing,other'
            ], [
                'name.required' => 'Address name is required',
                'address_line_1.required' => 'Address line 1 is required',
                'city.required' => 'City is required',
                'state.required' => 'State is required',
                'postal_code.required' => 'Postal code is required'
            ]);

            $address = Address::create([
                'user_id' => Auth::id(),
                'type' => $validated['type'] ?? 'delivery',
                'name' => $validated['name'],
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'] ?? 'India',
                'phone' => $validated['phone'] ?? null,
                'is_default' => $validated['is_default'] ?? false
            ]);

            // If this is set as default, remove default flag from other addresses
            if ($address->is_default) {
                Address::where('user_id', Auth::id())
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully!',
                'address' => $address
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
                'debug' => 'Validation error'
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add address. Please try again.',
                'debug' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing address.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_default' => 'nullable|boolean',
            'type' => 'nullable|string|in:delivery,billing,other'
        ]);

        try {
            $address->update([
                'type' => $validated['type'] ?? 'delivery',
                'name' => $validated['name'],
                'address_line_1' => $validated['address_line_1'],
                'address_line_2' => $validated['address_line_2'] ?? null,
                'city' => $validated['city'],
                'state' => $validated['state'],
                'postal_code' => $validated['postal_code'],
                'country' => $validated['country'] ?? 'India',
                'phone' => $validated['phone'] ?? null,
                'is_default' => $validated['is_default'] ?? false
            ]);

            // If this is set as default, remove default flag from other addresses
            if ($address->is_default) {
                Address::where('user_id', Auth::id())
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update address. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete an address.
     */
    public function destroy($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        try {
            $address->delete();

            return response()->json([
                'success' => true,
                'message' => 'Address deleted successfully!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete address. Please try again.'
            ], 500);
        }
    }

    /**
     * Set an address as default.
     */
    public function setDefault($id)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $address = Address::where('user_id', Auth::id())->findOrFail($id);

        try {
            // Remove default flag from all other addresses
            Address::where('user_id', Auth::id())
                ->where('id', '!=', $address->id)
                ->update(['is_default' => false]);

            // Set this address as default
            $address->update(['is_default' => true]);

            return response()->json([
                'success' => true,
                'message' => 'Default address updated successfully!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to set default address. Please try again.'
            ], 500);
        }
    }

    /**
     * Get default address for the user.
     */
    public function getDefault()
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $address = Address::where('user_id', Auth::id())
            ->where('is_default', true)
            ->first();

        return response()->json([
            'success' => true,
            'address' => $address
        ]);
    }
}
