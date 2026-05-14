<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\Front\StoreReviewRequest;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    public function store(StoreReviewRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        Review::create([
            'user_id' => auth()->id(),
            'product_id' => $validated['product_id'],
            'name' => auth()->user()->name ?? $validated['name'] ?? 'Customer',
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
            'guest_phone' => auth()->user()->phone ?? $validated['guest_phone'] ?? null,
            'status' => 'pending', // Always pending — admin must approve
        ]);

        return back()->with('success', 'Thank you! Your review has been submitted and is awaiting approval.');
    }
}
