<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class ServicePageController extends Controller
{
    public function hub(PageContextService $pageContext)
    {
        $categories = ServiceCategory::where('status', 'published')
            ->with(['services' => fn ($q) => $q->where('status', 'published')->orderBy('sort_order')->with('heroMedia')])
            ->orderBy('sort_order')
            ->get();

        $schema = SchemaService::breadcrumbList([['label' => 'Services', 'url' => url('/services')]]);
        $blocks = BlockBuilderService::getBlocks('services_hub', 0);
        $context = $pageContext->listing('Services', 'services', url('/services'), [
            'categories' => $categories,
        ]);

        return view('frontend.pages.services-hub', compact('categories', 'schema', 'blocks', 'context'));
    }

    public function category(string $slug, PageContextService $pageContext)
    {
        $category = ServiceCategory::where('slug_final', $slug)->where('status', 'published')->firstOrFail();
        $services = $category->services()->where('status', 'published')->orderBy('sort_order')->with('heroMedia')->get();

        $breadcrumbs = [
            ['label' => 'Services', 'url' => url('/services')],
            ['label' => $category->name, 'url' => url('/services/'.$category->slug_final)],
        ];
        $schema = SchemaService::breadcrumbList($breadcrumbs);
        $blocks = BlockBuilderService::getBlocks('service_category', $category->id);
        $context = $pageContext->compose(array_merge(
            $pageContext->serviceCategory($category),
            [
                'services' => $services,
            ]
        ));

        return view('frontend.pages.service-category', compact('category', 'services', 'breadcrumbs', 'schema', 'blocks', 'context'));
    }

    public function detail(string $categorySlug, string $slug, PageContextService $pageContext)
    {
        $service = Service::where('slug_final', $slug)->where('status', 'published')->with(['category', 'heroMedia'])->firstOrFail();

        // Redirect to canonical URL if category slug doesn't match
        if ($service->category && $service->category->slug_final !== $categorySlug) {
            return redirect()->to('/services/'.$service->category->slug_final.'/'.$service->slug_final, 301);
        }

        $breadcrumbs = [
            ['label' => 'Services', 'url' => url('/services')],
        ];
        if ($service->category) {
            $breadcrumbs[] = ['label' => $service->category->name, 'url' => url('/services/'.$service->category->slug_final)];
        }
        $breadcrumbs[] = ['label' => $service->name];
        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::service($service->name, $service->service_summary ?? '', null, $service->frontend_url, $service->heroMedia?->url);

        $cityPages = $service->activeCityPages()->with('city')->get();
        $blocks = BlockBuilderService::getBlocks('service', $service->id);
        $context = $pageContext->service($service, $cityPages);

        return view('frontend.pages.service-detail', compact('service', 'breadcrumbs', 'schema', 'cityPages', 'blocks', 'context'));
    }
}
