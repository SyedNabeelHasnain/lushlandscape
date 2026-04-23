@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: services_grid (grouped by category) --}}
@if(isset($servicePages) && $servicePages->isNotEmpty())
@php
    $cityName = isset($city) ? $city->name : 'Your Area';
    $gridHeading = !empty($section['settings']['heading'])
        ? $section['settings']['heading']
        : 'Services Available in ' . $cityName;

    // Group service pages by their category
    $grouped = $servicePages->groupBy(fn($sp) => $sp->service->category->id ?? 0);

    // Build category order map from actual categories
    $categoryMap = $servicePages
        ->pluck('service.category')
        ->filter()
        ->unique('id')
        ->sortBy('sort_order')
        ->values();
@endphp
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="mb-10">
            <h2 class="text-3xl font-bold text-text">{{ $gridHeading }}</h2>
            <p class="mt-3 text-text-secondary text-lg">Click any service to learn more and get a tailored scope plan for your {{ $cityName }} project.</p>
        </div>

        {{-- Category jump links --}}
        @if($categoryMap->count() > 1)
        <div class="flex flex-wrap gap-2 mb-10">
            @foreach($categoryMap as $cat)
            <a href="#cat-{{ $cat->slug }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-stone  text-sm text-text-secondary hover:border-forest/20 hover:text-forest transition">
                <i data-lucide="{{ $cat->icon ?? 'layers' }}" class="w-3.5 h-3.5"></i>
                {{ $cat->name }}
            </a>
            @endforeach
        </div>
        @endif

        {{-- Services grouped by category --}}
        @foreach($categoryMap as $catIdx => $cat)
        @php $catPages = $grouped->get($cat->id, collect()); @endphp
        @if($catPages->isNotEmpty())
        <div id="cat-{{ $cat->slug }}" class="{{ $catIdx > 0 ? 'mt-12' : '' }}">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-9 h-9 bg-forest/10  flex items-center justify-center shrink-0">
                    <i data-lucide="{{ $cat->icon ?? 'layers' }}" class="w-4 h-4 text-forest"></i>
                </div>
                <h3 class="text-xl font-bold text-text">{{ $cat->name }}</h3>
            </div>
            @if($cat->short_description)
            <p class="text-sm text-text-secondary mb-6 ml-12">{{ $cat->short_description }}</p>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                @foreach($catPages as $sp)
                <a href="{{ $sp->frontend_url }}"
                   class="group bg-white  border border-stone p-6 hover:border-forest/20 hover:shadow-lg transition-all duration-300"
                  >
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 bg-forest/10  flex items-center justify-center shrink-0 group-hover:bg-forest transition-colors duration-300">
                            <i data-lucide="{{ $sp->service->icon ?? 'wrench' }}" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div class="min-w-0">
                            <h3 class="text-sm font-bold text-text group-hover:text-forest transition-colors leading-snug">{{ $sp->service->name }}</h3>
                            @if($sp->service->service_summary)
                            <p class="text-xs text-text-secondary mt-2 line-clamp-2">{{ $sp->service->service_summary }}</p>
                            @endif
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>
@endif
