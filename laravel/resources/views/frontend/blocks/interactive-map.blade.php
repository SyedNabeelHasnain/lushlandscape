@php
    $mapMode     = $content['map_mode'] ?? 'all_cities';
    $citySlug    = $content['city_slug'] ?? '';
    $centerLat   = (float) ($content['center_lat'] ?? 43.55);
    $centerLng   = (float) ($content['center_lng'] ?? -79.65);
    $zoom        = (int)   ($content['zoom'] ?? 9);
    $height      = (int)   ($content['height'] ?? 500);
    $showChips   = (bool)  ($content['show_chips'] ?? true);
    $markerColor = $content['marker_color'] ?? 'forest';
    $popupCta    = $content['popup_cta_text'] ?? 'Book a Consultation';
    $schemaType  = $content['schema_type'] ?? 'LocalBusiness';
    $customMarkers = $content['markers'] ?? [];

    $colorMap = [
        'forest' => '#1E4A2D',
        'accent' => '#1E4A2D',
        'blue'   => '#2563EB',
        'red'    => '#DC2626',
    ];
    $pinColor = $colorMap[$markerColor] ?? '#1E4A2D';

    // Build markers from DB based on map_mode
    $mapMarkers = [];
    $filterItems = [];

    if ($mapMode === 'all_cities') {
        $cities = \Illuminate\Support\Facades\Cache::remember('interactive_map_all_cities', 3600, function () {
            return \App\Models\City::where('status', 'published')
                ->with(['neighborhoods' => fn($q) => $q->where('status', 'published')->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get();
        });

        foreach ($cities as $c) {
            $hoodNames = $c->neighborhoods->pluck('name')->take(5)->implode(', ');
            $mapMarkers[] = [
                'name'     => $c->name,
                'lat'      => (float) $c->latitude,
                'lng'      => (float) $c->longitude,
                'type'     => 'city',
                'slug'     => $c->slug_final,
                'heading'  => 'Professional Services in ' . $c->name,
                'desc'     => 'Professional landscape construction, interlocking, concrete, and hardscaping services for ' . $c->name . ' homeowners. Serving neighbourhoods including ' . $hoodNames . '.',
                'cta_text' => $popupCta,
                'cta_url'  => '/professional-' . $c->slug_final,
                'services' => '',
                'hoods'    => $c->neighborhoods->map(fn($n) => [
                    'name' => $n->name,
                    'lat'  => (float) $n->latitude,
                    'lng'  => (float) $n->longitude,
                    'slug' => $n->slug,
                ])->toArray(),
            ];
            $filterItems[] = ['name' => $c->name, 'slug' => $c->slug_final, 'type' => 'city'];
        }
    } elseif ($mapMode === 'single_city') {
        $city = \App\Models\City::where('slug_final', $citySlug)->where('status', 'published')
            ->with(['neighborhoods' => fn($q) => $q->where('status', 'published')->whereNotNull('latitude')->orderBy('sort_order')])
            ->first();

        if ($city) {
            $centerLat = (float) $city->latitude;
            $centerLng = (float) $city->longitude;
            $zoom = max($zoom, 12);

            foreach ($city->neighborhoods as $n) {
                $mapMarkers[] = [
                    'name'     => $n->name,
                    'lat'      => (float) $n->latitude,
                    'lng'      => (float) $n->longitude,
                    'type'     => 'neighborhood',
                    'slug'     => $n->slug,
                    'heading'  => $n->name . ', ' . $city->name,
                    'desc'     => $n->summary,
                    'cta_text' => $popupCta,
                    'cta_url'  => '/professional-' . $city->slug_final,
                    'services' => '',
                    'hoods'    => [],
                ];
                $filterItems[] = ['name' => $n->name, 'slug' => $n->slug, 'type' => 'neighborhood'];
            }
        }
    }

    // Merge/override with custom markers from block content
    foreach ($customMarkers as $cm) {
        if (empty($cm['name']) || empty($cm['lat']) || empty($cm['lng'])) continue;
        $mapMarkers[] = [
            'name'     => $cm['name'],
            'lat'      => (float) $cm['lat'],
            'lng'      => (float) $cm['lng'],
            'type'     => 'custom',
            'slug'     => \Illuminate\Support\Str::slug($cm['name']),
            'heading'  => $cm['popup_heading'] ?? $cm['name'],
            'desc'     => $cm['popup_description'] ?? '',
            'cta_text' => $cm['popup_cta_text'] ?? $popupCta,
            'cta_url'  => $cm['popup_cta_url'] ?? '/contact',
            'services' => $cm['popup_services'] ?? '',
            'hoods'    => [],
        ];
    }

    $mapId = 'imap-' . uniqid();
    $googleMapsJsKey = \App\Models\Setting::get('google_maps_js_key', '');
@endphp
@if(!empty($mapMarkers))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10 md:py-14">

    @if(!empty($content['heading']))
    <h2 class="text-2xl md:text-3xl font-bold text-text mb-3">{{ $content['heading'] }}</h2>
    @endif
    @if(!empty($content['description']))
    <p class="text-text-secondary mb-6 max-w-3xl">{{ $content['description'] }}</p>
    @endif

    <div x-data="interactiveMap({
        mapId: '{{ $mapId }}',
        markers: {{ json_encode($mapMarkers, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
        filters: {{ json_encode($filterItems, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
        center: [{{ $centerLat }}, {{ $centerLng }}],
        zoom: {{ $zoom }},
        pinColor: '{{ $pinColor }}',
        mapMode: '{{ $mapMode }}',
        apiKey: '{{ $googleMapsJsKey }}'
    })" x-init="initMap()" class="relative">

        {{-- Filter chips --}}
        @if($showChips && !empty($filterItems))
        <div class="flex flex-wrap gap-2 mb-4" role="toolbar" aria-label="Map location filter">
            <button type="button"
                x-on:click="resetFilter()"
                :class="activeFilter === null ? 'bg-forest text-white border-forest' : 'bg-white text-text-secondary border-stone hover:border-forest hover:text-forest'"
                class="inline-flex items-center gap-1.5 px-3 py-1.5  text-sm font-medium border transition">
                <i data-lucide="map" class="w-3.5 h-3.5"></i>
                All Locations
            </button>
            <template x-for="f in filters" :key="f.slug">
                <button type="button"
                    x-on:click="filterTo(f)"
                    :class="activeFilter === f.slug ? 'bg-forest text-white border-forest' : 'bg-white text-text-secondary border-stone hover:border-forest hover:text-forest'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5  text-sm font-medium border transition"
                    x-text="f.name">
                </button>
            </template>
        </div>
        @endif

        {{-- Map container --}}
        <div id="{{ $mapId }}"
             class="w-full  border border-stone overflow-hidden z-0"
             style="height: {{ $height }}px;"
             aria-label="Interactive service area map showing Super WMS Service locations across Our Region">
        </div>

        {{-- Active location detail strip --}}
        <div x-show="activeDetail" x-transition.opacity x-cloak
             class="mt-4 p-4 bg-cream  border border-stone flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex-1 min-w-0">
                <h3 class="font-bold text-text" x-text="activeDetail?.heading"></h3>
                <p class="text-sm text-text-secondary mt-1 line-clamp-2" x-text="activeDetail?.desc"></p>
            </div>
            <a :href="activeDetail?.cta_url"
               class="shrink-0 inline-flex items-center gap-2 bg-forest hover:bg-black text-white font-semibold px-5 py-2.5  transition text-sm">
                <i data-lucide="phone" class="w-4 h-4"></i>
                <span x-text="activeDetail?.cta_text"></span>
            </a>
        </div>
    </div>

    {{-- SEO: noscript fallback + structured data --}}
    <noscript>
        <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach($mapMarkers as $m)
            <a href="{{ $m['cta_url'] }}" class="flex items-center gap-2 bg-white border border-stone  px-4 py-3 hover:border-forest transition">
                <svg class="w-4 h-4 text-forest shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span class="text-sm font-medium text-text">{{ $m['name'] }}</span>
            </a>
            @endforeach
        </div>
    </noscript>

    @if($schemaType !== 'none')
    @foreach($mapMarkers as $m)
    <script type="application/ld+json">{!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => $schemaType === 'LocalBusiness' ? 'LocalBusiness' : 'Place',
        'name' => 'Super WMS Service - ' . $m['name'],
        'description' => $m['desc'],
        'url' => url($m['cta_url']),
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => $m['lat'],
            'longitude' => $m['lng'],
        ],
        'areaServed' => [
            '@type' => 'City',
            'name' => $m['name'],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
    @endforeach
    @endif
</div>
@endif
