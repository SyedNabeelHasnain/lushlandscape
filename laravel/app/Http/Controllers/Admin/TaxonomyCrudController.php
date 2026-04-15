<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

class TaxonomyCrudController extends Controller
{
    use HandlesAjaxRequests;

    /** Resolve taxonomy config from the current route name. */
    private function cfg(): array
    {
        // e.g. admin.blog-categories.index → key = blog-categories
        $routeName = Route::currentRouteName();
        $key = Str::betweenFirst($routeName, 'admin.', '.');
        $all = config('taxonomies');

        abort_unless(isset($all[$key]), 404);

        return array_merge($all[$key], ['key' => $key]);
    }

    /**
     * Extract the entity ID from the current route parameters.
     * Resource routes use slugified singular names (e.g. {blog_category}) — we grab the first value.
     */
    private function routeItemId(): int
    {
        $params = array_values(Route::current()->parameters());
        abort_if(empty($params), 404);

        return (int) $params[0];
    }

    private function findItem(array $cfg, int $id)
    {
        return ($cfg['model'])::findOrFail($id);
    }

    private function rules(array $cfg, ?int $ignoreId = null): array
    {
        $table = (new ($cfg['model'])())->getTable();
        $slugUniq = "unique:{$table},slug".($ignoreId ? ",{$ignoreId}" : '');

        $rules = [
            'name' => 'required|string|max:255',
            'slug' => "required|string|max:255|{$slugUniq}",
            'parent_id' => "nullable|exists:{$table},id",
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'image_media_id' => 'nullable|exists:media_assets,id',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'schema_type' => 'nullable|string|max:100',
            'schema_json' => 'nullable|json',
            'status' => 'required|in:published,draft',
            'sort_order' => 'nullable|integer|min:0',
        ];

        if ($cfg['has_icon']) {
            $rules['icon'] = 'nullable|string|max:100';
        }

        if ($cfg['has_language']) {
            $rules['language'] = 'nullable|string|max:10';
        }

        return $rules;
    }

    private function parentsList(array $cfg, ?int $excludeId = null)
    {
        $query = ($cfg['model'])::roots()->orderBy('sort_order')->orderBy('name');
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->pluck('name', 'id');
    }

    private function decodeSchemaJson(array &$validated): void
    {
        if (isset($validated['schema_json']) && is_string($validated['schema_json'])) {
            $validated['schema_json'] = json_decode($validated['schema_json'], true);
        }
    }

    private function pageType(array $cfg): string
    {
        return str_replace('-', '_', Str::singular($cfg['key']));
    }

    private function supportsPageBuilder(array $cfg): bool
    {
        return (bool) ($cfg['supports_page_builder'] ?? false);
    }

    private function frontendUrl(array $cfg, mixed $item = null): ?string
    {
        $routeName = $cfg['frontend_route'] ?? null;
        if (! $routeName || ! $item) {
            return null;
        }

        return route($routeName, $item->slug);
    }

    public function index()
    {
        $cfg = $this->cfg();
        $rel = $cfg['post_rel'];
        $items = ($cfg['model'])::with('parent')
            ->withCount($rel)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(30);

        return View::make('admin.taxonomies.index', compact('cfg', 'items', 'rel'));
    }

    public function create()
    {
        $cfg = $this->cfg();
        $parents = $this->parentsList($cfg);
        $supportsPageBuilder = $this->supportsPageBuilder($cfg);
        $blockTypes = $supportsPageBuilder ? BlockBuilderService::allTypes() : [];
        $pageType = $supportsPageBuilder ? $this->pageType($cfg) : null;
        $viewUrl = null;

        return View::make('admin.taxonomies.form', compact('cfg', 'parents', 'blockTypes', 'pageType', 'supportsPageBuilder', 'viewUrl'));
    }

    public function store(Request $request)
    {
        $cfg = $this->cfg();
        $validated = $request->validate($this->rules($cfg));
        $this->decodeSchemaJson($validated);

        $item = ($cfg['model'])::create($validated);

        $blocksJson = $request->input('blocks_json');
        if ($this->supportsPageBuilder($cfg) && $blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true);
            BlockBuilderService::saveUnifiedBlocks($this->pageType($cfg), $item->id, $blocksData ?: []);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess($cfg['singular'].' created successfully.', [], route("admin.{$cfg['key']}.index"));
        }

        return Redirect::route("admin.{$cfg['key']}.index")
            ->with('success', $cfg['singular'].' created successfully.');
    }

    public function edit()
    {
        $cfg = $this->cfg();
        $item = $this->findItem($cfg, $this->routeItemId());
        $parents = $this->parentsList($cfg, $item->id);
        $supportsPageBuilder = $this->supportsPageBuilder($cfg);
        $pageType = $supportsPageBuilder ? $this->pageType($cfg) : null;
        $blocks = $supportsPageBuilder ? BlockBuilderService::getUnifiedBlocks($pageType, $item->id) : collect();
        $blockTypes = $supportsPageBuilder ? BlockBuilderService::allTypes() : [];
        $viewUrl = $this->frontendUrl($cfg, $item);

        return View::make('admin.taxonomies.form', compact('cfg', 'item', 'parents', 'blocks', 'blockTypes', 'pageType', 'supportsPageBuilder', 'viewUrl'));
    }

    public function update(Request $request)
    {
        $cfg = $this->cfg();
        $item = $this->findItem($cfg, $this->routeItemId());
        $validated = $request->validate($this->rules($cfg, $item->id));
        $this->decodeSchemaJson($validated);

        // Prevent circular parent assignment
        if (! empty($validated['parent_id']) && (int) $validated['parent_id'] === $item->id) {
            $validated['parent_id'] = null;
        }

        $item->update($validated);

        $blocksJson = $request->input('blocks_json');
        if ($this->supportsPageBuilder($cfg) && $blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true);
            BlockBuilderService::saveUnifiedBlocks($this->pageType($cfg), $item->id, $blocksData ?: []);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess($cfg['singular'].' updated successfully.');
        }

        return Redirect::route("admin.{$cfg['key']}.index")
            ->with('success', $cfg['singular'].' updated successfully.');
    }

    public function destroy(Request $request)
    {
        $cfg = $this->cfg();
        $item = $this->findItem($cfg, $this->routeItemId());
        $rel = $cfg['post_rel'];

        if ($item->{$rel}()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError("Cannot delete: this {$cfg['singular']} still has associated {$rel}.", [], 422);
            }

            return Redirect::back()->with('error', "Cannot delete: this {$cfg['singular']} still has associated {$rel}.");
        }

        if ($item->children()->count() > 0) {
            if ($this->isAjax($request)) {
                return $this->jsonError("Cannot delete: this {$cfg['singular']} has sub-categories. Remove them first.", [], 422);
            }

            return Redirect::back()->with('error', "Cannot delete: this {$cfg['singular']} has sub-categories. Remove them first.");
        }

        if ($this->supportsPageBuilder($cfg)) {
            BlockBuilderService::deleteAllBlocksForPage($this->pageType($cfg), $item->id);
        }
        $item->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess($cfg['singular'].' deleted.');
        }

        return Redirect::route("admin.{$cfg['key']}.index")
            ->with('success', $cfg['singular'].' deleted.');
    }
}
