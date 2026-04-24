<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index(PageContextService $pageContext)
    {
        // Get all blocks for home page (singleton, page_id = null)
        $blocks = BlockBuilderService::getBlocks('home', null);

        // Build context for data blocks
        $context = $pageContext->home();

        // Data resolution for these blocks is handled dynamically via BlockBuilderService
        // using the 'auto' filters and contextual data sources defined in config.

        // Schema is always required for SEO
        $citiesServed = Cache::remember('home_schema_cities', 3600, function () {
            return \App\Models\Entry::whereHas('contentType', function($q) { $q->where('slug', 'city'); })->where('status', 'published')->orderBy('sort_order')->pluck('title')->toArray();
        });
        $schema = SchemaService::webSite().SchemaService::organization().SchemaService::localBusiness(null, $citiesServed);

        return view('frontend.pages.home', compact('blocks', 'context', 'schema'));
    }
}
