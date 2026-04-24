<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ContentTypeController extends Controller
{
    public function index(Request $request)
    {
        $contentTypes = ContentType::orderBy('name')->paginate(20);

        return View::make('admin.content-types.index', compact('contentTypes'));
    }

    public function create()
    {
        return View::make('admin.content-types.form', ['contentType' => new ContentType]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:content_types,slug',
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'layout_template' => 'nullable|string|max:255',
            'is_hierarchical' => 'boolean',
            'has_archives' => 'boolean',
            'schema_json' => 'nullable|json',
        ]);

        if (isset($validated['schema_json'])) {
            $validated['schema_json'] = json_decode($validated['schema_json'], true);
        }

        $contentType = ContentType::create($validated);

        return redirect()->route('admin.content-types.index')->with('success', 'Content Type created.');
    }

    public function edit(ContentType $contentType)
    {
        return View::make('admin.content-types.form', compact('contentType'));
    }

    public function update(Request $request, ContentType $contentType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:content_types,slug,'.$contentType->id,
            'description' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'layout_template' => 'nullable|string|max:255',
            'is_hierarchical' => 'boolean',
            'has_archives' => 'boolean',
            'schema_json' => 'nullable|json',
        ]);

        if (isset($validated['schema_json'])) {
            $validated['schema_json'] = json_decode($validated['schema_json'], true);
        }

        $contentType->update($validated);

        return redirect()->route('admin.content-types.index')->with('success', 'Content Type updated.');
    }

    public function destroy(ContentType $contentType)
    {
        if ($contentType->entries()->count() > 0) {
            return redirect()->back()->with('error', 'Cannot delete Content Type with active entries.');
        }

        $contentType->delete();

        return redirect()->route('admin.content-types.index')->with('success', 'Content Type deleted.');
    }
}
