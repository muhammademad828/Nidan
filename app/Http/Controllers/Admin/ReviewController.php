<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(Request $request): View
    {
        $query = Review::with('product');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $reviews = $query->orderBy('id', 'desc')->paginate(20);
        return view('admin.reviews.index', compact('reviews'));
    }

    public function approve(Review $review): RedirectResponse
    {
        $review->update(['status' => 'approved']);
        return back()->with('success', 'Review approved.');
    }

    public function reject(Review $review): RedirectResponse
    {
        $review->update(['status' => 'rejected']);
        return back()->with('success', 'Review rejected and hidden.');
    }

    public function hide(Review $review): RedirectResponse
    {
        $review->update(['status' => 'pending']);
        return back()->with('success', 'Review hidden (set to pending).');
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();
        return back()->with('success', 'Review deleted.');
    }
}
