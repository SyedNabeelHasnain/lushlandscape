<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Taxonomy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class TaxonomyController extends Controller
{
    public function index()
    {
        $taxonomies = Taxonomy::orderBy('name')->paginate(20);
        return View::make('admin.taxonomies.index', compact('taxonomies'));
    }

    public function create()
    {
        return View::make('admin.taxonomies.form', ['taxonomy' => new Taxonomy()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:taxonomies,slug',
            'description' => 'nullable|string|max:500',
            'is_hierarchical' => 'boolean',
            'schema_json' => 'nullable|json',
        ]);

        if (isset($validated['schema_json'])) {
            $validated['schema_json'] = json_decode($validated['schema_json'], true);
        }

        Taxonomy::create($validated);

        return redirect()->route('admin.taxonomies.index')->with('success', 'Taxonomy created.');
    }

    public function edit(Taxonomy $taxonomy)
    {
        return View::make('admin.taxonomies.form', compact('taxonomy'));
    }

    public function update(Request $request, Taxonomy $taxonomy)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:taxonomies,slug,' . $taxonomy->id,
            'description' => 'nullable|string|max:500',
            'is_hierarchical' => 'boolean',
            'schema_json' => 'nullable|json',
        ]);

        if (isset($validated['schema_json'])) {
            $validated['schema_json'] = json_decode($validated['schema_json'], true);
        }

        $taxonomy->update($validated);

        return redirect()->route('admin.taxonomies.index')->with('success', 'Taxonomy updated.');
    }

    public function destroy(Taxonomy $taxonomy)
    {
        if ($taxonomy->terms()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete Taxonomy with active terms.');
        }

        $taxonomy->delete();

        return redirect()->route('admin.taxonomies.index')->with('success', 'Taxonomy deleted.');
    }
}
