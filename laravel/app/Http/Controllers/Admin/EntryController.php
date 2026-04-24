<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\ContentType;
use App\Models\Entry;
use App\Models\RouteAlias;
use App\Models\Taxonomy;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class EntryController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $contentTypeId = $request->query('type');
        $contentType = $contentTypeId ? ContentType::find($contentTypeId) : null;

        $query = Entry::with('contentType')->orderBy('sort_order')->orderBy('title');

        if ($contentType) {
            $query->where('content_type_id', $contentType->id);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $request->search).'%');
        }

        $entries = $query->paginate(20);
        $contentTypes = ContentType::orderBy('name')->pluck('name', 'id');

        return View::make('admin.entries.index', compact('entries', 'contentTypes', 'contentType'));
    }

    public function create(Request $request)
    {
        $contentTypeId = $request->query('type');
        $contentType = ContentType::findOrFail($contentTypeId);

        $parents = [];
        if ($contentType->is_hierarchical) {
            $parents = Entry::where('content_type_id', $contentType->id)
                ->whereNull('parent_id')
                ->orderBy('title')
                ->pluck('title', 'id');
        }

        $taxonomies = Taxonomy::with('terms')->orderBy('name')->get();

        return View::make('admin.entries.form', compact('contentType', 'parents', 'taxonomies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content_type_id' => 'required|exists:content_types,id',
            'parent_id' => 'nullable|exists:entries,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
            'data_json' => 'nullable|json',
            'data' => 'nullable|array',
        ]);

        $contentType = ContentType::findOrFail($validated['content_type_id']);

        // Use dynamically submitted data array if present, otherwise fallback to raw JSON
        if ($request->has('data') && is_array($request->input('data'))) {
            $validated['data'] = $request->input('data');
        } else {
            $data = $request->input('data_json') ? json_decode($request->input('data_json'), true) : [];
            $validated['data'] = $data;
        }

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $blocksJson = $request->input('blocks_json');

        $entry = DB::transaction(function () use ($validated, $blocksJson, $request) {
            $entry = Entry::create($validated);

            // Save Taxonomies
            if ($request->has('terms')) {
                $entry->terms()->sync($request->input('terms'));
            }

            // Generate RouteAlias
            RouteAlias::create([
                'slug' => ltrim($entry->slug, '/'),
                'routable_type' => Entry::class,
                'routable_id' => $entry->id,
                'is_active' => $entry->status === 'published',
            ]);

            // Save unified blocks
            if ($blocksJson) {
                $blocksData = json_decode($blocksJson, true) ?? [];
                BlockBuilderService::saveUnifiedBlocks('entry', $entry->id, $blocksData);
            }

            return $entry;
        });

        event('entry.saved', $entry);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Entry created.', [], route('admin.entries.edit', $entry));
        }

        return Redirect::route('admin.entries.index', ['type' => $entry->content_type_id])
            ->with('success', 'Entry created successfully.');
    }

    public function edit(Entry $entry)
    {
        $contentType = $entry->contentType;

        $parents = [];
        if ($contentType->is_hierarchical) {
            $parents = Entry::where('content_type_id', $contentType->id)
                ->whereNull('parent_id')
                ->where('id', '!=', $entry->id)
                ->orderBy('title')
                ->pluck('title', 'id');
        }

        $blocks = BlockBuilderService::getUnifiedBlocks('entry', $entry->id);
        $blockTypes = BlockBuilderService::allTypes();
        $taxonomies = Taxonomy::with('terms')->orderBy('name')->get();

        return view('admin.entries.form', compact('entry', 'contentType', 'parents', 'blocks', 'blockTypes', 'taxonomies'));
    }

    public function update(Request $request, Entry $entry)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:entries,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'status' => 'required|in:draft,published,archived',
            'sort_order' => 'nullable|integer',
            'data_json' => 'nullable|json',
            'data' => 'nullable|array',
        ]);

        $entryData = $entry->data ? $entry->data->toArray() : [];

        if ($request->has('data') && is_array($request->input('data'))) {
            // Update using dynamic form array
            $validated['data'] = array_merge($entryData, $request->input('data'));
        } else {
            // Fallback to raw JSON
            $data = $request->input('data_json') ? json_decode($request->input('data_json'), true) : [];
            $validated['data'] = array_merge($entryData, $data);
        }

        if ($validated['status'] === 'published' && ! $entry->published_at) {
            $validated['published_at'] = now();
        }

        $blocksJson = $request->input('blocks_json', '[]');

        DB::transaction(function () use ($entry, $validated, $blocksJson, $request) {
            $entry->update($validated);

            // Update Taxonomies
            if ($request->has('terms')) {
                $entry->terms()->sync($request->input('terms'));
            } else {
                $entry->terms()->detach();
            }

            // Update RouteAlias
            $alias = $entry->routeAlias ?? new RouteAlias;
            $alias->slug = ltrim($entry->slug, '/');
            $alias->routable_type = Entry::class;
            $alias->routable_id = $entry->id;
            $alias->is_active = $entry->status === 'published';
            $alias->save();

            // Save unified blocks
            $blocksData = json_decode($blocksJson, true) ?: [];
            BlockBuilderService::saveUnifiedBlocks('entry', $entry->id, $blocksData);
        });

        event('entry.saved', $entry);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Entry updated.');
        }

        return Redirect::route('admin.entries.index', ['type' => $entry->content_type_id])
            ->with('success', 'Entry updated successfully.');
    }

    public function destroy(Request $request, Entry $entry)
    {
        $typeId = $entry->content_type_id;

        DB::transaction(function () use ($entry) {
            if ($entry->routeAlias) {
                $entry->routeAlias->delete();
            }
            $entry->delete();
        });

        event('entry.deleted', $entry);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Entry deleted.');
        }

        return Redirect::route('admin.entries.index', ['type' => $typeId])
            ->with('success', 'Entry deleted.');
    }
}
