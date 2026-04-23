<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\ContentType;
use App\Models\Entry;
use App\Models\RouteAlias;
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

        return View::make('admin.entries.form', compact('contentType', 'parents'));
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
        ]);

        $contentType = ContentType::findOrFail($validated['content_type_id']);
        
        $data = $request->input('data_json') ? json_decode($request->input('data_json'), true) : [];
        $validated['data'] = $data;
        
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $blocksJson = $request->input('blocks_json');

        $entry = DB::transaction(function () use ($validated, $blocksJson) {
            $entry = Entry::create($validated);

            // Generate RouteAlias
            RouteAlias::create([
                'slug' => $entry->slug, // In a real app, logic would prepend taxonomy slugs if needed
                'routable_type' => Entry::class,
                'routable_id' => $entry->id,
                'is_active' => $entry->status === 'published'
            ]);

            // Save unified blocks
            if ($blocksJson) {
                $blocksData = json_decode($blocksJson, true) ?? [];
                BlockBuilderService::saveUnifiedBlocks('entry', $entry->id, $blocksData);
            }

            return $entry;
        });

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

        return view('admin.entries.form', compact('entry', 'contentType', 'parents', 'blocks', 'blockTypes'));
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
        ]);

        $data = $request->input('data_json') ? json_decode($request->input('data_json'), true) : [];
        
        // Merge with existing data so we don't lose unstructured fields not in the form
        $entryData = $entry->data ? $entry->data->toArray() : [];
        $validated['data'] = array_merge($entryData, $data);

        if ($validated['status'] === 'published' && !$entry->published_at) {
            $validated['published_at'] = now();
        }

        $blocksJson = $request->input('blocks_json', '[]');

        DB::transaction(function () use ($entry, $validated, $blocksJson) {
            $entry->update($validated);

            // Update RouteAlias
            $alias = $entry->routeAlias;
            if ($alias) {
                $alias->update([
                    'slug' => $entry->slug,
                    'is_active' => $entry->status === 'published'
                ]);
            } else {
                RouteAlias::create([
                    'slug' => $entry->slug,
                    'routable_type' => Entry::class,
                    'routable_id' => $entry->id,
                    'is_active' => $entry->status === 'published'
                ]);
            }

            // Save unified blocks
            $blocksData = json_decode($blocksJson, true) ?: [];
            BlockBuilderService::saveUnifiedBlocks('entry', $entry->id, $blocksData);
        });

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

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Entry deleted.');
        }

        return Redirect::route('admin.entries.index', ['type' => $typeId])
            ->with('success', 'Entry deleted.');
    }
}
