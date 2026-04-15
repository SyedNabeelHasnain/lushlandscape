<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class HomePageController extends Controller
{
    use HandlesAjaxRequests;

    public function edit()
    {
        $pageType = 'home';
        $blocks = BlockBuilderService::getUnifiedBlocks($pageType, null);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.home-page.edit', compact('pageType', 'blocks', 'blockTypes'));
    }

    public function update(Request $request)
    {
        $blocksData = json_decode($request->input('blocks_json', '[]'), true) ?: [];

        BlockBuilderService::saveUnifiedBlocks('home', null, $blocksData);

        // Save SEO fields dynamically
        $seoFields = ['seo_home_title', 'seo_home_description', 'seo_home_og_title', 'seo_home_og_description', 'seo_home_og_image_id'];
        foreach ($seoFields as $key) {
            if ($request->has($key)) {
                $value = $request->input($key);
                Setting::updateOrCreate(
                    ['key' => $key],
                    [
                        'group' => 'seo',
                        'type' => str_contains($key, 'image_id') ? 'media' : (str_contains($key, 'description') ? 'textarea' : 'text'),
                        'label' => 'Home Page: '.ucwords(str_replace(['seo_home_', '_id', '_'], ['', '', ' '], $key)),
                        'value' => $value,
                    ]
                );
            }
        }
        Setting::flushCache();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Home page updated successfully.');
        }

        return Redirect::route('admin.home-page.edit')
            ->with('success', 'Home page updated successfully.');
    }
}
