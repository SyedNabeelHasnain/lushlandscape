<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Models\MediaAsset;
use App\Models\Popup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class PopupController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $popups = Popup::orderBy('sort_order')->orderByDesc('created_at')->paginate(20);

        return View::make('admin.popups.index', compact('popups'));
    }

    public function create()
    {
        $forms = Form::orderBy('name')->get(['id', 'name', 'slug']);

        return View::make('admin.popups.form', compact('forms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,active,archived',
            'heading' => 'nullable|string|max:255',
            'body_content' => 'nullable|string',
            'image_media_id' => 'nullable|integer|exists:media_assets,id',
            'form_id' => 'nullable|integer|exists:forms,id',
            'trigger_type' => 'required|in:delay,scroll_percent,exit_intent',
            'trigger_delay_seconds' => 'nullable|integer|min:0|max:300',
            'trigger_scroll_percent' => 'nullable|integer|min:0|max:100',
            'suppress_days' => 'nullable|integer|min:0|max:365',
            'show_on_mobile' => 'boolean',
            'show_to_returning' => 'boolean',
            'excluded_pages' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['show_on_mobile'] = $request->boolean('show_on_mobile');
        $validated['show_to_returning'] = $request->boolean('show_to_returning');
        $validated['excluded_pages'] = $this->parseExcludedPages($request->input('excluded_pages'));
        $validated['trigger_delay_seconds'] = $validated['trigger_delay_seconds'] ?? 5;
        $validated['trigger_scroll_percent'] = $validated['trigger_scroll_percent'] ?? 50;
        $validated['suppress_days'] = $validated['suppress_days'] ?? 7;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $popup = Popup::create($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Popup created successfully.', [], route('admin.popups.edit', $popup));
        }

        return Redirect::route('admin.popups.edit', $popup)->with('success', 'Popup created successfully.');
    }

    public function edit(Popup $popup)
    {
        $forms = Form::orderBy('name')->get(['id', 'name', 'slug']);
        $imageAsset = $popup->image_media_id ? MediaAsset::find($popup->image_media_id) : null;

        return View::make('admin.popups.form', compact('popup', 'forms', 'imageAsset'));
    }

    public function update(Request $request, Popup $popup)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => 'required|in:draft,active,archived',
            'heading' => 'nullable|string|max:255',
            'body_content' => 'nullable|string',
            'image_media_id' => 'nullable|integer|exists:media_assets,id',
            'form_id' => 'nullable|integer|exists:forms,id',
            'trigger_type' => 'required|in:delay,scroll_percent,exit_intent',
            'trigger_delay_seconds' => 'nullable|integer|min:0|max:300',
            'trigger_scroll_percent' => 'nullable|integer|min:0|max:100',
            'suppress_days' => 'nullable|integer|min:0|max:365',
            'show_on_mobile' => 'boolean',
            'show_to_returning' => 'boolean',
            'excluded_pages' => 'nullable|string',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['show_on_mobile'] = $request->boolean('show_on_mobile');
        $validated['show_to_returning'] = $request->boolean('show_to_returning');
        $validated['excluded_pages'] = $this->parseExcludedPages($request->input('excluded_pages'));
        $validated['trigger_delay_seconds'] = $validated['trigger_delay_seconds'] ?? 5;
        $validated['trigger_scroll_percent'] = $validated['trigger_scroll_percent'] ?? 50;
        $validated['suppress_days'] = $validated['suppress_days'] ?? 7;
        $validated['sort_order'] = $validated['sort_order'] ?? 0;

        $popup->update($validated);

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Popup updated successfully.');
        }

        return Redirect::route('admin.popups.index')->with('success', 'Popup updated successfully.');
    }

    public function destroy(Request $request, Popup $popup)
    {
        $popup->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Popup deleted.');
        }

        return Redirect::route('admin.popups.index')->with('success', 'Popup deleted.');
    }

    private function parseExcludedPages(?string $input): array
    {
        if (! $input) {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode("\n", $input))));
    }
}
