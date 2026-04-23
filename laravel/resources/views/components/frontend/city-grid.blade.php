@props([
    'cities'   => collect(),
    'heading'  => 'Serving Communities Across Our Region',
    'subtitle' => 'From the shores of Burlington to the growing neighbourhoods of Brampton, our crews deliver the same quality craftsmanship to every community we serve.',
])

@php
    if ($cities->isEmpty()) {
        $cities = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')
            ->with(['neighborhoods' => fn($q) => $q->where('status', 'published')->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get();
    }

    if ($cities->isEmpty()) return;

    // Build map markers for Google Maps
    $mapMarkers  = [];
    $filterItems = [];
    $popupCta    = 'Book a Consultation';
    $pinColor    = '#1E4A2D';
    $centerLat   = 43.55;
    $centerLng   = -79.65;
    $zoom        = 9;

    foreach ($cities as $c) {
        $hoodNames = $c->relationLoaded('neighborhoods')
            ? $c->neighborhoods->pluck('name')->take(5)->implode(', ')
            : '';

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
            'hoods'    => $c->relationLoaded('neighborhoods')
                ? $c->neighborhoods->map(fn($n) => ['name' => $n->name, 'lat' => (float) $n->latitude, 'lng' => (float) $n->longitude, 'slug' => $n->slug])->toArray()
                : [],
        ];
        $filterItems[] = ['name' => $c->name, 'slug' => $c->slug, 'type' => 'city'];
    }

    $mapId           = 'imap-' . uniqid();
    $googleMapsJsKey = \App\Models\Setting::get('google_maps_js_key', '');

    // City lat/lng for 3D globe projection
    $globeCities = $cities->map(fn($c) => [
        'name'  => $c->name,
        'slug'  => $c->slug,
        'url'   => '/professional-' . $c->slug,
        'lat'   => (float) $c->latitude,
        'lng'   => (float) $c->longitude,
        'hoods' => $c->relationLoaded('neighborhoods') ? $c->neighborhoods->take(4)->pluck('name')->implode(', ') : '',
        'desc'  => $c->city_summary ?? ('Professional professional services in ' . $c->name),
    ])->values()->toArray();
@endphp

@if($cities->isNotEmpty())
<section class="section-editorial bg-white" aria-labelledby="service-areas-heading">
    <div class="max-w-7xl mx-auto px-6 lg:px-12"
         x-data="{
             activeCity: null,
             showMap: false,
             showCard: false,
             cardCity: null,
             cities: {{ json_encode($globeCities, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
             setActive(slug) {
                 this.activeCity = slug;
                 this.cardCity = this.cities.find(c => c.slug === slug) || null;
                 this.showCard = !!this.cardCity;
             },
             clearActive() {
                 this.activeCity = null;
                 this.showCard = false;
             }
         }">

        {{-- Header --}}
        <div class="mb-16 lg:mb-24 reveal">
            <span class="text-eyebrow text-forest mb-5 block">Service Areas</span>
            <h2 id="service-areas-heading" class="text-h2 font-heading font-bold text-ink max-w-3xl">{{ $heading }}</h2>
            <p class="mt-5 text-text-secondary text-body-lg leading-relaxed max-w-3xl">{{ $subtitle }}</p>
        </div>

        {{-- Two-column: Globe + City List --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-16 mb-20 items-center">

            {{-- LEFT: 3D Rotating Globe --}}
            <div class="reveal-left relative" x-data="lushGlobe()" x-init="init()" x-intersect.once="start()">
                <canvas x-ref="globeCanvas" class="w-full max-w-lg mx-auto" style="aspect-ratio: 1/1;" aria-label="Interactive 3D globe showing service areas across Our Region"></canvas>

                {{-- City detail card (appears on hover/click) --}}
                <div x-show="showCard && cardCity" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak
                     class="absolute top-4 right-4 lg:-right-4 w-72 bg-white border border-stone shadow-luxury p-6 z-10">
                    <p class="text-eyebrow text-forest mb-2">Service Area</p>
                    <h3 class="text-2xl font-heading font-bold text-ink" x-text="cardCity?.name"></h3>
                    <p class="text-eyebrow text-accent mt-1">Our Region</p>
                    <p class="text-sm text-text-secondary mt-3 leading-relaxed line-clamp-3" x-text="cardCity?.desc"></p>
                    <a :href="cardCity?.url" class="mt-4 inline-flex items-center gap-2 text-forest text-[11px] font-semibold tracking-[0.15em] uppercase link-underline">
                        View Services <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                    </a>
                </div>
            </div>

            {{-- RIGHT: City List --}}
            <div class="reveal-right">
                <div class="space-y-1">
                    @foreach($cities as $city)
                    <a href="{{ url('/professional-' .  $city->slug  . '') }}"
                       class="group flex items-center justify-between gap-4 px-5 py-4 bg-white border border-transparent hover:border-forest/15 hover:shadow-luxury transition-all duration-500"
                       x-on:mouseenter="setActive('{{ $city->slug }}')"
                       x-on:mouseleave="clearActive()"
                       :class="activeCity === '{{ $city->slug }}' ? 'border-forest/15 shadow-luxury' : ''">
                        <div class="min-w-0">
                            <span class="block text-base font-heading font-bold text-ink group-hover:text-forest transition-colors duration-300"
                                  :class="activeCity === '{{ $city->slug }}' ? 'text-forest' : ''">{{ $city->name }}</span>
                            @if($city->relationLoaded('neighborhoods') && $city->neighborhoods->isNotEmpty())
                            <span class="block text-sm text-text-secondary mt-0.5 truncate">{{ $city->neighborhoods->take(4)->pluck('name')->implode(', ') }}</span>
                            @endif
                        </div>
                        <i data-lucide="arrow-right" class="w-4 h-4 text-stone-dark group-hover:text-forest shrink-0 transition-all duration-300 group-hover:translate-x-1"></i>
                    </a>
                    @endforeach
                </div>

                @if($googleMapsJsKey)
                <button type="button" x-on:click="showMap = !showMap"
                        class="mt-6 inline-flex items-center gap-2.5 btn-luxury btn-luxury-primary text-sm">
                    <i data-lucide="map" class="w-4 h-4"></i>
                    <span x-text="showMap ? 'Hide Interactive Map' : 'View Interactive Map'"></span>
                </button>
                @endif
            </div>
        </div>

        {{-- Google Maps (toggled) --}}
        @if($googleMapsJsKey && !empty($mapMarkers))
        <div x-show="showMap" x-collapse x-cloak class="mb-20">
            <div x-data="interactiveMap({
                mapId: '{{ $mapId }}',
                markers: {{ json_encode($mapMarkers, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
                filters: {{ json_encode($filterItems, JSON_HEX_TAG | JSON_UNESCAPED_UNICODE) }},
                center: [{{ $centerLat }}, {{ $centerLng }}],
                zoom: {{ $zoom }},
                pinColor: '{{ $pinColor }}',
                mapMode: 'all_cities',
                apiKey: '{{ $googleMapsJsKey }}'
            })" x-effect="if (showMap && !googleMap) initMap()" class="relative">
                @if(!empty($filterItems))
                <div class="flex flex-wrap gap-2 mb-4" role="tablist" aria-label="Map location filter">
                    <button type="button" x-on:click="resetFilter()"
                        :class="activeFilter === null ? 'bg-forest text-white border-forest' : 'bg-white text-text-secondary border-stone hover:border-forest hover:text-forest'"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium border transition">
                        <i data-lucide="map" class="w-3.5 h-3.5"></i> All Locations
                    </button>
                    <template x-for="f in filters" :key="f.slug">
                        <button type="button" x-on:click="filterTo(f)"
                            :class="activeFilter === f.slug ? 'bg-forest text-white border-forest' : 'bg-white text-text-secondary border-stone hover:border-forest hover:text-forest'"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium border transition"
                            x-text="f.name"></button>
                    </template>
                </div>
                @endif
                <div id="{{ $mapId }}" class="w-full border border-stone overflow-hidden z-0" style="height: 500px;" aria-label="Interactive service area map"></div>
                <div x-show="activeDetail" x-transition.opacity x-cloak class="mt-4 p-4 bg-white border border-stone flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-text" x-text="activeDetail?.heading"></h3>
                        <p class="text-sm text-text-secondary mt-1 line-clamp-2" x-text="activeDetail?.desc"></p>
                    </div>
                    <a :href="activeDetail?.cta_url" class="shrink-0 btn-luxury btn-luxury-primary text-sm">
                        <span x-text="activeDetail?.cta_text"></span>
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- City link grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-px bg-stone reveal-stagger">
            @foreach($cities as $city)
            <a href="{{ url('/professional-' .  $city->slug  . '') }}"
               class="group bg-white p-8 flex flex-col items-center text-center hover:bg-forest transition-all duration-500">
                <i data-lucide="map-pin" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-500 mb-4"></i>
                <span class="text-sm font-semibold text-ink group-hover:text-white transition-colors duration-300">{{ $city->name }}</span>
                @if($city->relationLoaded('neighborhoods') && $city->neighborhoods->isNotEmpty())
                <span class="text-xs text-text-secondary group-hover:text-white/60 mt-2 transition-colors leading-relaxed">{{ $city->neighborhoods->take(3)->pluck('name')->implode(', ') }}</span>
                @endif
            </a>
            @endforeach
        </div>

        {{-- SEO: noscript fallback --}}
        <noscript>
            <div class="mt-6 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                @foreach($cities as $city)
                <a href="{{ url('/professional-' .  $city->slug  . '') }}" class="flex items-center gap-2 bg-white border border-stone px-4 py-3 hover:border-forest transition">
                    <svg class="w-4 h-4 text-forest shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <span class="text-sm font-medium text-text">{{ $city->name }}</span>
                </a>
                @endforeach
            </div>
        </noscript>

        {{-- Schema.org structured data --}}
        @foreach($mapMarkers as $m)
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => 'Super WMS Service - ' . $m['name'],
            'description' => $m['desc'],
            'url' => url($m['cta_url']),
            'geo' => ['@type' => 'GeoCoordinates', 'latitude' => $m['lat'], 'longitude' => $m['lng']],
            'areaServed' => ['@type' => 'City', 'name' => $m['name']],
        ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
        @endforeach
    </div>
</section>
@endif
