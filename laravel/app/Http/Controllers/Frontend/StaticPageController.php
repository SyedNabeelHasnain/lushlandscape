<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class StaticPageController extends Controller
{
    public function show(string $slug, PageContextService $pageContext)
    {
        $page = StaticPage::where('slug', $slug)->where('status', 'published')->firstOrFail();

        $breadcrumbs = [['label' => $page->title]];
        $schema = SchemaService::breadcrumbList($breadcrumbs);
        $blocks = BlockBuilderService::getBlocks('static_page', $page->id);
        $context = $pageContext->staticPage($page);

        return view('frontend.pages.static', compact('page', 'breadcrumbs', 'schema', 'blocks', 'context'));
    }
}
