<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class PortfolioProjectController extends Controller
{
    use HandlesAjaxRequests;

    public function index()
    {
        $projects = PortfolioProject::with(['city', 'service'])->orderByDesc('created_at')->paginate(20);

        return View::make('admin.portfolio.index', compact('projects'));
    }

    public function create()
    {
        $cities = City::orderBy('sort_order')->pluck('name', 'id');
        $services = Service::orderBy('sort_order')->pluck('name', 'id');
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.portfolio.form', compact('cities', 'services', 'blockTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:portfolio_projects,slug',
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'project_type' => 'nullable|string|max:100',
            'city_id' => 'nullable|exists:cities,id',
            'service_id' => 'nullable|exists:services,id',
            'neighborhood' => 'nullable|string|max:255',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'before_image_id' => 'nullable|exists:media_assets,id',
            'after_image_id' => 'nullable|exists:media_assets,id',
            'gallery_media_ids' => 'nullable|string',
            'project_value_range' => 'nullable|string|max:100',
            'project_duration' => 'nullable|string|max:100',
            'video_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'completion_date' => 'nullable|date',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['gallery_media_ids'] = $this->parseGalleryIds($request->input('gallery_media_ids', ''));

        $portfolio = PortfolioProject::create($validated);

        $blocksJson = $request->input('blocks_json');
        if ($blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true);
            BlockBuilderService::saveUnifiedBlocks('portfolio_project', $portfolio->id, $blocksData ?: []);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Project created.', [], route('admin.portfolio.edit', $portfolio));
        }

        return Redirect::route('admin.portfolio.index')
            ->with('success', 'Portfolio project created.');
    }

    public function edit(PortfolioProject $portfolio)
    {
        $portfolio->load(['heroMedia', 'beforeImage', 'afterImage']);
        $cities = City::orderBy('sort_order')->pluck('name', 'id');
        $services = Service::orderBy('sort_order')->pluck('name', 'id');
        $blocks = BlockBuilderService::getUnifiedBlocks('portfolio_project', $portfolio->id);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.portfolio.form', [
            'project' => $portfolio, 'cities' => $cities, 'services' => $services,
            'blocks' => $blocks, 'blockTypes' => $blockTypes,
        ]);
    }

    public function update(Request $request, PortfolioProject $portfolio)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:portfolio_projects,slug,'.$portfolio->id,
            'description' => 'nullable|string',
            'body' => 'nullable|string',
            'project_type' => 'nullable|string|max:100',
            'city_id' => 'nullable|exists:cities,id',
            'service_id' => 'nullable|exists:services,id',
            'neighborhood' => 'nullable|string|max:255',
            'hero_media_id' => 'nullable|exists:media_assets,id',
            'before_image_id' => 'nullable|exists:media_assets,id',
            'after_image_id' => 'nullable|exists:media_assets,id',
            'gallery_media_ids' => 'nullable|string',
            'project_value_range' => 'nullable|string|max:100',
            'project_duration' => 'nullable|string|max:100',
            'video_url' => 'nullable|url|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
            'completion_date' => 'nullable|date',
            'sort_order' => 'nullable|integer',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['gallery_media_ids'] = $this->parseGalleryIds($request->input('gallery_media_ids', ''));

        $portfolio->update($validated);

        $blocksJson = $request->input('blocks_json');
        if ($blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true);
            BlockBuilderService::saveUnifiedBlocks('portfolio_project', $portfolio->id, $blocksData ?: []);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Project updated.');
        }

        return Redirect::route('admin.portfolio.index')
            ->with('success', 'Portfolio project updated.');
    }

    public function destroy(Request $request, PortfolioProject $portfolio)
    {
        BlockBuilderService::deleteAllBlocksForPage('portfolio_project', $portfolio->id);
        $portfolio->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Project deleted.');
        }

        return Redirect::route('admin.portfolio.index')->with('success', 'Portfolio project deleted.');
    }

    private function parseGalleryIds(string $input): array
    {
        if (empty(trim($input))) {
            return [];
        }

        return array_values(array_filter(
            array_map('intval', array_map('trim', preg_split('/[\s,]+/', $input)))
        ));
    }
}
