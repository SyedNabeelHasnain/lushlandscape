<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Term;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class ServicePageController extends Controller
{
    public function hub(PageContextService $pageContext)
    {
        $categories = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'service-categories'))
            ->with(['entries' => fn ($q) => $q->where('status', 'published')->orderBy('sort_order')])
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
        $category = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'service-categories'))->where('slug', $slug)->firstOrFail();
        $services = $category->entries()->where('status', 'published')->orderBy('sort_order')->get();

        $breadcrumbs = [
            ['label' => 'Services', 'url' => url('/services')],
            ['label' => $category->name, 'url' => url('/services/'.$category->slug)],
        ];
        $schema = SchemaService::breadcrumbList($breadcrumbs);
        $blocks = BlockBuilderService::getBlocks('service_category', $category->id);
        $context = $pageContext->compose(array_merge(
            $pageContext->serviceCategory($category),
            [
                'services' => $services,
            ]
        ));

        // Use the new taxonomy fallback view since service-category.blade.php was moved
        return view('frontend.taxonomies.service-categories', compact('category', 'services', 'breadcrumbs', 'schema', 'blocks', 'context'));
    }

    public function detail(string $categorySlug, string $slug, PageContextService $pageContext)
    {
        $service = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'service'))->where('slug', $slug)->where('status', 'published')->with(['terms'])->firstOrFail();

        $serviceCategory = $service->terms->first();
        // Redirect to canonical URL if category slug doesn't match
        if ($serviceCategory && $serviceCategory->slug !== $categorySlug) {
            return redirect()->to('/services/'.$serviceCategory->slug.'/'.$service->slug, 301);
        }

        $breadcrumbs = [
            ['label' => 'Services', 'url' => url('/services')],
        ];
        if ($serviceCategory) {
            $breadcrumbs[] = ['label' => $serviceCategory->name, 'url' => url('/services/'.$serviceCategory->slug)];
        }
        $breadcrumbs[] = ['label' => $service->title];
        
        $heroMediaUrl = $service->heroMedia ? $service->heroMedia->url : null;
        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::service($service->title, $service->data['service_summary'] ?? '', null, $service->frontend_url, $heroMediaUrl);

        $cityPages = $service->inverseRelatedEntries()->where('relation_type', 'matrix_service')->where('status', 'published')->get();
        $blocks = BlockBuilderService::getBlocks('service', $service->id);
        $context = $pageContext->service($service, $cityPages);

        return view('frontend.entries.service', compact('service', 'breadcrumbs', 'schema', 'cityPages', 'blocks', 'context'));
    }
}
