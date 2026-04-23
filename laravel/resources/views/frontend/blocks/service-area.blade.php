@php
    $mode        = $content['mode'] ?? 'combined';
    $mapMode     = $content['map_mode'] ?? 'all_cities';
    $citySlug    = $content['city_slug'] ?? '';
    $centerLat   = (float) ($content['center_lat'] ?? 43.55);
    $centerLng   = (float) ($content['center_lng'] ?? -79.65);
    $zoom        = (int)   ($content['zoom'] ?? 9);
    $height      = (int)   ($content['height'] ?? 500);
    $showChips   = (bool)  ($content['show_chips'] ?? true);
    $pinColor    = '#1E4A2D';
    $popupCta    = $content['popup_cta_text'] ?? 'Book a Consultation';
    $schemaType  = $content['schema_type'] ?? 'LocalBusiness';
    $customMarkers = $content['markers'] ?? [];
    $areas       = $content['areas'] ?? [];

    $mapMarkers  = [];
    $filterItems = [];
    $allCities   = collect();

    if (in_array($mode, ['combined', 'map_only'])) {
        if ($mapMode === 'all_cities') {
            $allCities = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')
                ->with(['neighborhoods' => fn($q) => $q->where('status', 'published')->orderBy('sort_order')])
                ->orderBy('sort_order')
                ->get();

            foreach ($allCities as $c) {
                $hoodNames = $c->neighborhoods->pluck('name')->take(5)->implode(', ');
                $mapMarkers[] = [
                    'name'     => $c->name,
                    'lat'      => (float) $c->latitude,
                    'lng'      => (float) $c->longitude,
                    'type'     => 'city',
                    'slug'     => $c->slug,
                    'heading'  => 'Professional Services in ' . $c->name,
                    'desc'     => 'Professional landscape construction and hardscaping services for ' . $c->name . '. Serving ' . $hoodNames . '.',
                    'cta_text' => $popupCta,
                    'cta_url'  => '/professional-' . $c->slug,
                    'services' => '',
                    'hoods'    => $c->neighborhoods->map(fn($n) => ['name' => $n->name, 'lat' => (float) $n->latitude, 'lng' => (float) $n->longitude, 'slug' => $n->slug])->toArray(),
                ];
                $filterItems[] = ['name' => $c->name, 'slug' => $c->slug, 'type' => 'city'];
            }
        } elseif ($mapMode === 'single_city') {
            $city = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('slug', $citySlug)->where('status', 'published')
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
                        'cta_url'  => '/professional-' . $city->slug,
                        'services' => '',
                        'hoods'    => [],
                    ];
                    $filterItems[] = ['name' => $n->name, 'slug' => $n->slug, 'type' => 'neighborhood'];
                }
            }
        }
        foreach ($customMarkers as $cm) {
            if (empty($cm['name']) || empty($cm['lat']) || empty($cm['lng'])) continue;
            $mapMarkers[] = [
                'name' => $cm['name'], 'lat' => (float) $cm['lat'], 'lng' => (float) $cm['lng'],
                'type' => 'custom', 'slug' => \Illuminate\Support\Str::slug($cm['name']),
                'heading' => $cm['popup_heading'] ?? $cm['name'], 'desc' => $cm['popup_description'] ?? '',
                'cta_text' => $cm['popup_cta_text'] ?? $popupCta, 'cta_url' => $cm['popup_cta_url'] ?? '/contact',
                'services' => $cm['popup_services'] ?? '', 'hoods' => [],
            ];
        }
    }

    if (in_array($mode, ['combined', 'list_only']) && empty($areas) && $allCities->isEmpty()) {
        $allCities = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')->orderBy('sort_order')->get();
    }

    $mapId = 'imap-' . uniqid();
    $googleMapsJsKey = \App\Models\Setting::get('google_maps_js_key', '');
    $hasMap = in_array($mode, ['combined', 'map_only']) && !empty($mapMarkers) && $googleMapsJsKey;
    $hasList = in_array($mode, ['combined', 'list_only']);
    $hasEmbed = $mode === 'embed_only' && !empty($content['embed_url']);
@endphp

@if($hasMap || $hasList || $hasEmbed)
<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        {{-- Header --}}
        @if(!empty($content['heading']))
        <div class="mb-12 reveal">
            <span class="text-eyebrow text-forest mb-5 block">Service Areas</span>
            <h2 class="text-h2 font-heading font-bold text-ink">{{ $content['heading'] }}</h2>
            @if(!empty($content['description']))
            <p class="mt-4 text-text-secondary text-body-lg max-w-3xl leading-relaxed">{{ $content['description'] }}</p>
            @endif
        </div>
        @endif

        @if($hasMap)
        {{-- Interactive Map --}}
        <div x-data="interactiveMap({
            mapId: '{{ $mapId }}',
            markers: {{ json_encode($mapMarkers, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
            filters: {{ json_encode($filterItems, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
            center: [{{ $centerLat }}, {{ $centerLng }}],
            zoom: {{ $zoom }},
            pinColor: '{{ $pinColor }}',
            mapMode: '{{ $mapMode }}',
            apiKey: '{{ $googleMapsJsKey }}'
        })" x-init="initMap()" class="relative reveal">

            @if($showChips && !empty($filterItems))
            <div class="flex flex-wrap gap-2 mb-4" role="tablist" aria-label="Map location filter">
                <button type="button" x-on:click="resetFilter()"
                    :class="activeFilter === null ? 'bg-forest text-white border-forest' : 'bg-white text-text-secondary border-stone hover:border-forest hover:text-forest'"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium border transition">
                    <i data-lucide="map" class="w-3.5 h-3.5"></i>All
                </button>
                <template x-for="f in filters" :key="f.slug">
                    <button type="button" x-on:click="filterTo(f)"
                        :class="activeFilter === f.slug ? 'bg-forest text-white border-forest' : 'bg-white text-text-secondary border-stone hover:border-forest hover:text-forest'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium border transition"
                        x-text="f.name"></button>
                </template>
            </div>
            @endif

            <div id="{{ $mapId }}" class="w-full border border-stone overflow-hidden z-0" style="height: {{ $height }}px;"
                 aria-label="Interactive service area map"></div>

            <div x-show="activeDetail" x-transition.opacity x-cloak
                 class="mt-4 p-4 bg-white border border-stone flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex-1 min-w-0">
                    <h3 class="font-bold text-text" x-text="activeDetail?.heading"></h3>
                    <p class="text-sm text-text-secondary mt-1 line-clamp-2" x-text="activeDetail?.desc"></p>
                </div>
                <a :href="activeDetail?.cta_url"
                   class="shrink-0 btn-luxury btn-luxury-primary text-sm">
                    <span x-text="activeDetail?.cta_text"></span>
                </a>
            </div>
        </div>
        @endif

        @if($hasEmbed)
        <div class="overflow-hidden border border-stone reveal">
            <iframe src="{{ $content['embed_url'] }}" width="100%" height="{{ $height }}" style="border:0"
                    allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Service area map"></iframe>
        </div>
        @endif

        {{-- City/area links grid --}}
        @if($hasList)
        <div class="mt-12 reveal-stagger">
            @if(!empty($areas))
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($areas as $area)
                @if(!empty($area['name']))
                @if(!empty($area['url']))
                <a href="{{ $area['url'] }}" class="flex items-center gap-3 bg-white border border-stone px-5 py-4 hover:border-forest hover:shadow-luxury transition-all duration-500 group">
                    <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                    <span class="text-sm font-medium text-ink group-hover:text-forest transition-colors">{{ $area['name'] }}</span>
                </a>
                @else
                <div class="flex items-center gap-3 bg-white border border-stone px-5 py-4">
                    <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                    <span class="text-sm font-medium text-ink">{{ $area['name'] }}</span>
                </div>
                @endif
                @endif
                @endforeach
            </div>
            @elseif($allCities->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($allCities as $c)
                <a href="{{ url('/professional-' .  $c->slug  . '') }}"
                   class="flex items-center gap-3 bg-white border border-stone px-5 py-4 hover:border-forest hover:shadow-luxury transition-all duration-500 group">
                    <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                    <span class="text-sm font-medium text-ink group-hover:text-forest transition-colors">{{ $c->name }}</span>
                </a>
                @endforeach
            </div>
            @endif
        </div>
        @endif

        {{-- SEO: noscript fallback + structured data --}}
        @if($hasMap)
        <noscript>
            <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($mapMarkers as $m)
                <a href="{{ $m['cta_url'] }}" class="flex items-center gap-2 bg-white border border-stone px-4 py-3 hover:border-forest transition">
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
            'geo' => ['@type' => 'GeoCoordinates', 'latitude' => $m['lat'], 'longitude' => $m['lng']],
            'areaServed' => ['@type' => 'City', 'name' => $m['name']],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @endforeach
        @endif
        @endif
    </div>
</section>
@endif
