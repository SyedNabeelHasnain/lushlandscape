<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class FaqController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = Faq::with('category')->orderBy('display_order');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('question', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $request->search).'%');
        }

        $faqs = $query->paginate(20);
        $categories = FaqCategory::orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.faqs.index', compact('faqs', 'categories'));
    }

    public function create()
    {
        $categories = FaqCategory::orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.faqs.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category_id' => 'required|exists:faq_categories,id',
            'short_answer' => 'nullable|string',
            'faq_type' => 'nullable|string|max:50',
            'audience_type' => 'nullable|string|max:50',
            'chatbot_summary' => 'nullable|string',
            'local_relevance' => 'boolean',
            'city_relevance' => 'nullable|string|max:255',
            'schema_eligible' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'display_order' => 'nullable|integer',
        ]);

        $validated['local_relevance'] = $request->boolean('local_relevance');
        $validated['schema_eligible'] = $request->boolean('schema_eligible');
        $validated['is_featured'] = $request->boolean('is_featured');
        $baseSlug = Str::slug(Str::limit($validated['question'], 80));
        $slug = $baseSlug;
        $counter = 1;
        while (Faq::where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$counter;
            $counter++;
        }
        $validated['slug'] = $slug;

        $faq = Faq::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('FAQ created.', [], route('admin.faqs.edit', $faq));
        }

        return Redirect::route('admin.faqs.index')
            ->with('success', 'FAQ created.');
    }

    public function edit(Faq $faq)
    {
        $categories = FaqCategory::orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.faqs.form', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'question' => 'required|string',
            'answer' => 'required|string',
            'category_id' => 'required|exists:faq_categories,id',
            'short_answer' => 'nullable|string',
            'faq_type' => 'nullable|string|max:50',
            'audience_type' => 'nullable|string|max:50',
            'chatbot_summary' => 'nullable|string',
            'local_relevance' => 'boolean',
            'city_relevance' => 'nullable|string|max:255',
            'schema_eligible' => 'boolean',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'display_order' => 'nullable|integer',
        ]);

        $validated['local_relevance'] = $request->boolean('local_relevance');
        $validated['schema_eligible'] = $request->boolean('schema_eligible');
        $validated['is_featured'] = $request->boolean('is_featured');

        $faq->update($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('FAQ updated.');
        }

        return Redirect::route('admin.faqs.index')
            ->with('success', 'FAQ updated.');
    }

    public function destroy(Request $request, Faq $faq)
    {
        $faq->assignments()->delete();
        $faq->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('FAQ deleted.');
        }

        return Redirect::route('admin.faqs.index')->with('success', 'FAQ deleted.');
    }
}
