<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ReviewController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $reviews = Review::orderByDesc('created_at')->paginate(20);

        return View::make('admin.reviews.index', compact('reviews'));
    }

    public function create()
    {
        return View::make('admin.reviews.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'source' => 'nullable|string|max:50',
            'source_url' => 'nullable|url|max:500',
            'city_relevance' => 'nullable|string|max:255',
            'neighborhood_mention' => 'nullable|string|max:255',
            'service_relevance' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'reviewer_avatar_url' => 'nullable|url|max:500',
            'review_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['reviewer_initial'] = strtoupper(substr($validated['reviewer_name'], 0, 1));

        $review = Review::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Review created.', [], route('admin.reviews.edit', $review));
        }

        return Redirect::route('admin.reviews.index')
            ->with('success', 'Review created.');
    }

    public function edit(Review $review)
    {
        return View::make('admin.reviews.form', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'reviewer_name' => 'required|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'source' => 'nullable|string|max:50',
            'source_url' => 'nullable|url|max:500',
            'city_relevance' => 'nullable|string|max:255',
            'neighborhood_mention' => 'nullable|string|max:255',
            'service_relevance' => 'nullable|string|max:255',
            'project_type' => 'nullable|string|max:100',
            'reviewer_avatar_url' => 'nullable|url|max:500',
            'review_date' => 'nullable|date',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['reviewer_initial'] = strtoupper(substr($validated['reviewer_name'], 0, 1));

        $review->update($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Review updated.');
        }

        return Redirect::route('admin.reviews.index')
            ->with('success', 'Review updated.');
    }

    public function destroy(Request $request, Review $review)
    {
        ReviewAssignment::where('review_id', $review->id)->delete();
        $review->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Review deleted.');
        }

        return Redirect::route('admin.reviews.index')->with('success', 'Review deleted.');
    }
}
