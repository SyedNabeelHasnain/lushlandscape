<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\ThemeLayout;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class ThemeLayoutController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = ThemeLayout::query()->orderBy('type')->orderBy('name');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $layouts = $query->paginate(20)->withQueryString();

        return View::make('admin.theme-layouts.index', compact('layouts'));
    }

    public function create()
    {
        return View::make('admin.theme-layouts.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:header,footer,single,archive',
            'is_active' => 'boolean',
        ]);

        $layout = ThemeLayout::create([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_active' => $request->boolean('is_active'),
            'conditions' => [], // Will be implemented in future phase
        ]);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Layout created. You can now build it.', [], route('admin.theme-layouts.edit', $layout));
        }

        return Redirect::route('admin.theme-layouts.edit', $layout)
            ->with('success', 'Layout created. You can now build it.');
    }

    public function edit(ThemeLayout $themeLayout)
    {
        $blocks = BlockBuilderService::getUnifiedBlocks('theme_layout', $themeLayout->id);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.theme-layouts.form', [
            'layout' => $themeLayout,
            'blocks' => $blocks,
            'blockTypes' => $blockTypes,
        ]);
    }

    public function update(Request $request, ThemeLayout $themeLayout)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:header,footer,single,archive',
            'is_active' => 'boolean',
        ]);

        $themeLayout->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'is_active' => $request->boolean('is_active'),
        ]);

        $blocksData = json_decode($request->input('blocks_json', '[]'), true) ?: [];
        BlockBuilderService::saveUnifiedBlocks('theme_layout', $themeLayout->id, $blocksData);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Layout updated.');
        }

        return Redirect::back()->with('success', 'Layout updated.');
    }

    public function destroy(Request $request, ThemeLayout $themeLayout)
    {
        BlockBuilderService::deleteAllBlocksForPage('theme_layout', $themeLayout->id);
        $themeLayout->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Layout deleted.');
        }

        return Redirect::route('admin.theme-layouts.index')->with('success', 'Layout deleted.');
    }
}
