<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Faq;
use App\Models\Redirect;
use App\Models\ServiceCityPage;
use App\Models\StaticPage;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class SlugResolverController extends Controller
{
    public function resolve(string $slug, PageContextService $pageContext)
    {
        $serviceCityPage = ServiceCityPage::where('slug_final', $slug)
            ->where('is_active', true)
            ->with(['service.category', 'city', 'heroMedia', 'heroImage2', 'heroImage3', 'heroImage4'])
            ->first();

        if ($serviceCityPage) {
            return $this->renderServiceCityPage($serviceCityPage);
        }

        $staticPage = StaticPage::where('slug', $slug)
            ->where('status', 'published')
            ->with('heroMedia')
            ->first();

        if ($staticPage) {
            $breadcrumbs = [['label' => $staticPage->title]];
            $schema = SchemaService::breadcrumbList($breadcrumbs);
            $blocks = BlockBuilderService::getBlocks('static_page', $staticPage->id);

            return view('frontend.pages.static', [
                'page' => $staticPage,
                'breadcrumbs' => $breadcrumbs,
                'schema' => $schema,
                'blocks' => $blocks,
                'context' => $pageContext->staticPage($staticPage),
            ]);
        }

        $redirect = Redirect::where('old_url', '/'.$slug)
            ->where('is_active', true)
            ->first();

        if ($redirect) {
            $redirect->increment('hit_count');

            return redirect($redirect->new_url, $redirect->status_code);
        }

        abort(404);
    }

    private function renderServiceCityPage(ServiceCityPage $page)
    {
        $breadcrumbs = [
            ['label' => 'Locations', 'url' => url('/locations')],
            ['label' => $page->city->name, 'url' => url('/professional-'.$page->city->slug_final)],
            ['label' => $page->service->name],
        ];

        // Service-specific FAQs (assigned to this page)
        $faqAssignments = $page->faqAssignments()
            ->with('faq')
            ->where('is_visible', true)
            ->orderBy('local_display_order')
            ->get();
        $serviceFaqs = $faqAssignments->pluck('faq')->filter()->take(5);

        // General business FAQs
        $generalFaqs = Faq::where('status', 'published')
            ->where('faq_type', 'general')
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->take(5)
            ->get();

        // City-specific FAQs (not service-specific)
        $cityFaqs = Faq::where('status', 'published')
            ->where('faq_type', 'local')
            ->where('city_relevance', $page->city->name)
            ->orderBy('display_order')
            ->take(3)
            ->get();

        // Combined for schema
        $allFaqs = $generalFaqs->merge($serviceFaqs)->merge($cityFaqs)->unique('id');

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::service($page->h1, $page->local_intro ?? '', $page->city->name, url('/'.$page->slug_final), $page->heroMedia?->url)
            .SchemaService::localBusiness($page->city->name);

        if ($allFaqs->isNotEmpty()) {
            $schema .= SchemaService::faqPage($allFaqs->toArray());
        }

        $relatedPages = ServiceCityPage::where('city_id', $page->city_id)
            ->where('id', '!=', $page->id)
            ->where('is_active', true)
            ->with('service')
            ->take(4)
            ->get();

        // Pass context to blocks
        $context = app(PageContextService::class)->serviceCityPage(
            $page,
            collect([$page]),
            $serviceFaqs,
            $generalFaqs,
            $cityFaqs
        );

        $blocks = BlockBuilderService::getBlocks('service_city_page', $page->id);

        $switcherCities = City::where('status', 'published')
            ->whereHas('activeServicePages', fn ($q) => $q->where('service_id', $page->service_id))
            ->orderBy('sort_order')
            ->get();

        // Grouped FAQ data for section rendering
        $faqs = $serviceFaqs;
        $faqGroups = [
            'general' => $generalFaqs,
            'service' => $serviceFaqs,
            'city' => $cityFaqs,
        ];

        return view('frontend.pages.service-city', compact(
            'page', 'breadcrumbs', 'schema', 'faqs', 'faqGroups',
            'relatedPages', 'blocks', 'context', 'switcherCities'
        ));
    }
}
