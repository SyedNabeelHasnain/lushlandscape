<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxonomy;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TermController extends Controller
{
    public function index(Request $request, Taxonomy $taxonomy)
    {
        $terms = $taxonomy->terms()->with('parent')->orderBy('name')->paginate(20);

        return View::make('admin.terms.index', compact('taxonomy', 'terms'));
    }

    public function create(Taxonomy $taxonomy)
    {
        $parents = [];
        if ($taxonomy->is_hierarchical) {
            $parents = $taxonomy->terms()->whereNull('parent_id')->orderBy('name')->pluck('name', 'id');
        }

        return View::make('admin.terms.form', [
            'taxonomy' => $taxonomy,
            'term' => new Term,
            'parents' => $parents,
        ]);
    }

    public function store(Request $request, Taxonomy $taxonomy)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:terms,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:terms,slug',
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'data_json' => 'nullable|json',
            'data' => 'nullable|array',
        ]);

        $validated['taxonomy_id'] = $taxonomy->id;

        if ($request->has('data') && is_array($request->input('data'))) {
            // Handle file uploads (e.g. image types in schema)
            $data = $request->input('data');
            foreach ($data as $key => $value) {
                if ($request->hasFile("data.{$key}")) {
                    $file = $request->file("data.{$key}");
                    // Here you would normally upload the file to MediaAsset and get its ID
                    // For now, we assume the UI handles the upload separately and passes the ID via a hidden input,
                    // as standard for the media picker component.
                }
            }
            $validated['data'] = $data;
        } else {
            $data = $request->input('data_json') ? json_decode($request->input('data_json'), true) : [];
            $validated['data'] = $data;
        }

        Term::create($validated);

        return redirect()->route('admin.taxonomies.terms.index', $taxonomy)->with('success', 'Term created.');
    }

    public function edit(Taxonomy $taxonomy, Term $term)
    {
        $parents = [];
        if ($taxonomy->is_hierarchical) {
            $parents = $taxonomy->terms()
                ->whereNull('parent_id')
                ->where('id', '!=', $term->id)
                ->orderBy('name')
                ->pluck('name', 'id');
        }

        return View::make('admin.terms.form', compact('taxonomy', 'term', 'parents'));
    }

    public function update(Request $request, Taxonomy $taxonomy, Term $term)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:terms,id',
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:terms,slug,'.$term->id,
            'description' => 'nullable|string|max:500',
            'sort_order' => 'nullable|integer',
            'data_json' => 'nullable|json',
            'data' => 'nullable|array',
        ]);

        $termData = $term->data ? $term->data->toArray() : [];

        if ($request->has('data') && is_array($request->input('data'))) {
            $data = $request->input('data');
            // Assuming standard media picker handles file uploads and passes hidden ID
            $validated['data'] = array_merge($termData, $data);
        } else {
            $data = $request->input('data_json') ? json_decode($request->input('data_json'), true) : [];
            $validated['data'] = array_merge($termData, $data);
        }

        $term->update($validated);

        return redirect()->route('admin.taxonomies.terms.index', $taxonomy)->with('success', 'Term updated.');
    }

    public function destroy(Taxonomy $taxonomy, Term $term)
    {
        if ($term->children()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete term that has children.');
        }

        $term->delete();

        return redirect()->route('admin.taxonomies.terms.index', $taxonomy)->with('success', 'Term deleted.');
    }
}
