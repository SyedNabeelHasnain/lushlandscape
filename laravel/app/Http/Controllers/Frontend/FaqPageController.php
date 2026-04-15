<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\Service;
use App\Models\ServiceCityPage;
use App\Services\SchemaService;

class FaqPageController extends Controller
{
    public function index()
    {
        // Detect referrer for pre-filtering
        $referrer = request()->header('referer', '');
        $preFilter = $this->detectReferrerContext($referrer);

        // Load filter options
        $categories = FaqCategory::where('status', 'published')
            ->withCount(['faqs' => fn ($q) => $q->where('status', 'published')])
            ->having('faqs_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        $cities = City::where('status', 'published')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug_final']);

        $services = Service::where('status', 'published')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug_final']);

        // Build FAQ query based on filters
        $query = Faq::where('status', 'published')
            ->with('category')
            ->orderBy('is_featured', 'desc')
            ->orderBy('is_pinned', 'desc')
            ->orderBy('display_order');

        $activeType = request('type');
        $activeCity = request('city');
        $activeService = request('service');
        $activeCategory = request('category');
        $searchQuery = request('q');

        if ($searchQuery) {
            $term = '%'.$searchQuery.'%';
            $query->where(function ($q) use ($term) {
                $q->where('question', 'like', $term)
                    ->orWhere('short_answer', 'like', $term)
                    ->orWhere('answer', 'like', $term);
            });
        }

        if ($activeType) {
            $query->where('faq_type', $activeType);
        }

        if ($activeCity) {
            $query->where('city_relevance', $activeCity);
        }

        if ($activeService) {
            // Service FAQs are assigned to pages; match by question content or semantic keywords
            $query->where(function ($q) use ($activeService) {
                $q->where('question', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $activeService).'%')
                    ->orWhereJsonContains('semantic_keywords', strtolower($activeService));
            });
        }

        if ($activeCategory) {
            $faqCat = FaqCategory::where('slug', $activeCategory)->first();
            if ($faqCat) {
                $query->where('category_id', $faqCat->id);
            }
        }

        $faqs = $query->paginate(20)->withQueryString();

        // Schema markup
        $breadcrumbs = [['label' => 'FAQs']];
        $schema = SchemaService::breadcrumbList($breadcrumbs);

        // Add FAQPage schema for the first page of results (max 50 for schema)
        $schemaFaqs = Faq::where('status', 'published')
            ->where('schema_eligible', true)
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->take(50)
            ->get();

        if ($schemaFaqs->isNotEmpty()) {
            $schema .= SchemaService::faqPage($schemaFaqs->toArray());
        }

        // Grouped FAQs for the default (unfiltered) view
        $grouped = null;
        if (! $activeType && ! $activeCity && ! $activeService && ! $activeCategory && ! $searchQuery) {
            $grouped = $this->getGroupedFaqs();
        }

        return view('frontend.pages.faqs', compact(
            'faqs', 'categories', 'cities', 'services',
            'activeType', 'activeCity', 'activeService', 'activeCategory',
            'searchQuery', 'preFilter', 'grouped', 'breadcrumbs', 'schema'
        ));
    }

    /**
     * Detect context from referrer URL to pre-filter FAQs.
     */
    private function detectReferrerContext(string $referrer): array
    {
        $context = ['type' => null, 'city' => null, 'service' => null];

        if (! $referrer) {
            return $context;
        }

        $appUrl = config('app.url');
        if (! str_starts_with($referrer, $appUrl)) {
            return $context;
        }

        $path = parse_url($referrer, PHP_URL_PATH) ?? '';
        $path = trim($path, '/');

        // Match city pages: /landscaping-{slug}
        if (preg_match('/^landscaping-([a-z0-9\-]+)$/', $path, $m)) {
            $city = City::where('slug_final', $m[1])->first();
            if ($city) {
                $context['city'] = $city->name;
                $context['type'] = 'local';
            }

            return $context;
        }

        // Match service pages: /services/{slug} or /services/{cat}/{slug}
        if (preg_match('/^services\//', $path)) {
            $segments = explode('/', $path);
            $slug = end($segments);
            $service = Service::where('slug_final', $slug)->first();
            if ($service) {
                $context['service'] = $service->name;
                $context['type'] = 'service';
            }

            return $context;
        }

        // Match service-city pages (catch-all slug)
        if ($path && ! str_contains($path, '/')) {
            $page = ServiceCityPage::where('slug_final', $path)
                ->where('is_active', true)
                ->with(['service', 'city'])
                ->first();
            if ($page) {
                $context['service'] = $page->service->name;
                $context['city'] = $page->city->name;
                $context['type'] = 'service';
            }
        }

        return $context;
    }

    /**
     * Get FAQs grouped by type for the default unfiltered view.
     */
    private function getGroupedFaqs(): array
    {
        $general = Faq::where('status', 'published')
            ->where('faq_type', 'general')
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->get();

        $service = Faq::where('status', 'published')
            ->where('faq_type', 'service')
            ->orderBy('is_featured', 'desc')
            ->orderBy('display_order')
            ->take(30)
            ->get();

        $local = Faq::where('status', 'published')
            ->where('faq_type', 'local')
            ->orderBy('city_relevance')
            ->orderBy('display_order')
            ->get();

        // Group by sub-types
        $billing = Faq::where('status', 'published')
            ->where('faq_type', 'billing')
            ->orderBy('display_order')
            ->get();

        $booking = Faq::where('status', 'published')
            ->where('faq_type', 'booking')
            ->orderBy('display_order')
            ->get();

        $compliance = Faq::where('status', 'published')
            ->where('faq_type', 'compliance')
            ->orderBy('display_order')
            ->get();

        return [
            'general' => $general,
            'service' => $service,
            'local' => $local,
            'billing' => $billing,
            'booking' => $booking,
            'compliance' => $compliance,
        ];
    }
}
