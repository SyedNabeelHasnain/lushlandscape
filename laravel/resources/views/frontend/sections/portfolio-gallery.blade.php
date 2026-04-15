{{-- Section: portfolio_gallery --}}
@php
    use App\Models\PortfolioProject;
    $galleryLimit   = (int) ($section['settings']['limit'] ?? 6);
    $isCityContext  = isset($city);
    $isPageContext  = isset($page) && !$isCityContext; // service-city page context

    $galleryHeading = !empty($section['settings']['heading'])
        ? $section['settings']['heading']
        : ($isCityContext
            ? 'Recent Projects in ' . $city->name
            : ($isPageContext
                ? 'Our ' . $page->service->name . ' Projects in ' . $page->city->name
                : 'Our Recent Projects'));

    $portfolioQuery = PortfolioProject::where('status', 'published')
        ->with('heroMedia')
        ->orderBy('sort_order');

    if ($isCityContext) {
        $portfolioQuery->where('city_id', $city->id);
    } elseif ($isPageContext) {
        $portfolioQuery->where('city_id', $page->city_id);
    }

    $portfolioItems = $portfolioQuery->take($galleryLimit)->get();

    $portfolioAllUrl = $isCityContext
        ? '/portfolio?city=' . urlencode($city->name)
        : ($isPageContext ? '/portfolio?city=' . urlencode($page->city->name) : '/portfolio');
@endphp
@if($portfolioItems->isNotEmpty())
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="mb-10 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-heading font-bold tracking-tight text-text">{{ $galleryHeading }}</h2>
                <p class="mt-2 text-text-secondary">Real work, real results. See what we have built in your neighbourhood.</p>
            </div>
            <a href="{{ $portfolioAllUrl }}" class="text-sm font-semibold text-forest hover:underline whitespace-nowrap">View all projects &rarr;</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach($portfolioItems as $proj)
            <a href="/portfolio/{{ $proj->slug }}"
               class="group block  overflow-hidden border border-stone bg-white hover:shadow-lg transition-all duration-300"
              >
                <div class="aspect-4/3 overflow-hidden bg-cream relative">
                    @if($proj->heroMedia)
                    <img src="{{ $proj->heroMedia->url }}" alt="{{ $proj->heroMedia->default_alt_text ?? $proj->title }}"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" width="600" height="450" loading="lazy" decoding="async">
                    @else
                    <div class="w-full h-full flex items-center justify-center">
                        <i data-lucide="image" class="w-10 h-10 text-stone"></i>
                    </div>
                    @endif
                    @if($proj->project_type)
                    <span class="absolute top-3 left-3 bg-forest text-white text-xs font-semibold px-2.5 py-1 ">{{ $proj->project_type }}</span>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-sm font-bold text-text group-hover:text-forest transition-colors leading-snug">{{ $proj->title }}</h3>
                    @if($proj->neighborhood)
                    <p class="text-xs text-text-secondary mt-1 flex items-center gap-1">
                        <i data-lucide="map-pin" class="w-3 h-3"></i>{{ $proj->neighborhood }}@if($isCityContext), {{ $city->name }}@elseif($isPageContext), {{ $page->city->name }}@endif
                    </p>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
