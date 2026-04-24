<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\CardTemplate;
use App\Models\PageBlock;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class CardTemplateController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = CardTemplate::orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $templates = $query->paginate(30);

        return View::make('admin.card-templates.index', compact('templates'));
    }

    public function create()
    {
        return View::make('admin.card-templates.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $template = CardTemplate::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Card template created.', [], route('admin.card-templates.edit', $template));
        }

        return Redirect::route('admin.card-templates.edit', $template)
            ->with('success', 'Card template created. You can now design it.');
    }

    public function edit(CardTemplate $cardTemplate)
    {
        $pageType = 'template_card';
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, $cardTemplate->id);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.card-templates.form', [
            'template' => $cardTemplate,
            'blocks' => $blocks,
            'blockTypes' => $blockTypes,
        ]);
    }

    public function update(Request $request, CardTemplate $cardTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $cardTemplate->update($validated);

        // Save unified blocks
        $blocksData = json_decode($request->input('blocks_json', '[]'), true) ?: [];
        BlockBuilderService::saveUnifiedBlocks('template_card', $cardTemplate->id, $blocksData);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Card template updated.');
        }

        return Redirect::route('admin.card-templates.index')
            ->with('success', 'Card template updated.');
    }

    public function destroy(Request $request, CardTemplate $cardTemplate)
    {
        PageBlock::forPage('template_card', $cardTemplate->id)->delete();
        $cardTemplate->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Card template deleted.');
        }

        return Redirect::route('admin.card-templates.index')
            ->with('success', 'Card template deleted.');
    }
}
