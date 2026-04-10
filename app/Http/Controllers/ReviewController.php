<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $query = Review::with(['menuItem', 'menuItem.restaurant']);

        // Filter by status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by restaurant
        if ($request->filled('restaurant_id')) {
            $query->whereHas('menuItem', function($q) use ($request) {
                $q->where('restaurant_id', $request->restaurant_id);
            });
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        // Search by customer name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', '%' . $search . '%')
                  ->orWhere('customer_email', 'like', '%' . $search . '%')
                  ->orWhere('review_text', 'like', '%' . $search . '%');
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);

        // Get stats
        $stats = [
            'total' => Review::count(),
            'approved' => Review::where('is_approved', true)->count(),
            'pending' => Review::where('is_approved', false)->count(),
            'avg_rating' => Review::approved()->avg('rating') ?: 0
        ];

        return view('admin.reviews.index', compact('reviews', 'stats'));
    }

    public function show($id)
    {
        $review = Review::with(['menuItem', 'menuItem.restaurant'])->findOrFail($id);
        return response()->json([
            'success' => true,
            'review' => $review
        ]);
    }

    public function approve($id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = true;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review approved successfully!',
            'review' => $review->load(['menuItem', 'menuItem.restaurant'])
        ]);
    }

    public function reject($id)
    {
        $review = Review::findOrFail($id);
        $review->is_approved = false;
        $review->save();

        return response()->json([
            'success' => true,
            'message' => 'Review rejected successfully!',
            'review' => $review->load(['menuItem', 'menuItem.restaurant'])
        ]);
    }

    public function destroy($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => 'Review deleted successfully!'
        ]);
    }

    public function bulkApprove(Request $request)
    {
        $reviewIds = $request->input('review_ids', []);
        
        if (empty($reviewIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No reviews selected!'
            ]);
        }

        Review::whereIn('id', $reviewIds)->update(['is_approved' => true]);

        return response()->json([
            'success' => true,
            'message' => count($reviewIds) . ' reviews approved successfully!'
        ]);
    }

    public function bulkReject(Request $request)
    {
        $reviewIds = $request->input('review_ids', []);
        
        if (empty($reviewIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No reviews selected!'
            ]);
        }

        Review::whereIn('id', $reviewIds)->update(['is_approved' => false]);

        return response()->json([
            'success' => true,
            'message' => count($reviewIds) . ' reviews rejected successfully!'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $reviewIds = $request->input('review_ids', []);
        
        if (empty($reviewIds)) {
            return response()->json([
                'success' => false,
                'message' => 'No reviews selected!'
            ]);
        }

        Review::whereIn('id', $reviewIds)->delete();

        return response()->json([
            'success' => true,
            'message' => count($reviewIds) . ' reviews deleted successfully!'
        ]);
    }
}
