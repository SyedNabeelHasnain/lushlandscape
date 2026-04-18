@props([
    'logoDesktop'  => null,
    'logoMobile'   => null,
    'siteName'     => 'Lush Landscape Service',
    'navCtaText'   => 'Book a Consultation',
    'navCtaUrl'    => '/contact',
    'phone'        => '',
    'phoneClean'   => '',
])

@php
    $navMaxServices = (int) \App\Models\Setting::get('nav_max_services', '5');

    $megaCats = \Illuminate\Support\Facades\Cache::remember('mega_nav_categories_v2', 3600, function () use ($navMaxServices) {
        return \App\Models\ServiceCategory::where('status', 'published')
            ->whereNull('parent_id')
            ->with([
                'services' => fn($q) => $q->where('status', 'published')
                    ->orderBy('sort_order')->take($navMaxServices),
                'children' => fn($q) => $q->where('status', 'published')
                    ->orderBy('sort_order')
                    ->with(['services' => fn($q2) => $q2->where('status', 'published')
                        ->orderBy('sort_order')->take($navMaxServices)]),
            ])
            ->orderBy('sort_order')
            ->get();
    });

    $catCount = $megaCats->count();
    $gridCols = $catCount <= 2 ? 'grid-cols-2' : ($catCount <= 3 ? 'grid-cols-3' : 'grid-cols-4');

    $megaCities = \Illuminate\Support\Facades\Cache::remember('mega_nav_cities', 3600, fn () =>
        \App\Models\City::where('status', 'published')
            ->orderBy('name')
            ->get(['id', 'name', 'slug_final'])
    );

    $searchEnabled     = \App\Models\Setting::get('search_enabled', '1') === '1';
    $searchInHeader    = \App\Models\Setting::get('search_show_in_header', '1') === '1';
    $searchPlaceholder = \App\Models\Setting::get('search_placeholder', 'Search services, cities, blog…');
    $searchMinChars    = (int) \App\Models\Setting::get('search_min_chars', '2');
    $showSearch        = $searchEnabled && $searchInHeader;
@endphp

<header id="siteHeader" class="header-shell fixed top-0 inset-x-0 z-[100]"
    x-data="{
        activeMenu: null,
        mobileOpen: false,
        mobileExpanded: {},
        searchOpen: false,
        searchQuery: '',
        searchResults: null,
        searchLoading: false,
        _hoverTimer: null,

        openMenu(name) {
            clearTimeout(this._hoverTimer);
            this.activeMenu = name;
        },
        scheduleClose() {
            this._hoverTimer = setTimeout(() => { this.activeMenu = null; }, 300);
        },
        cancelClose() { clearTimeout(this._hoverTimer); },
        closeAll()    { this.activeMenu = null; this.mobileOpen = false; this.searchOpen = false; },

        async doSearch() {
            if (!this.searchQuery || this.searchQuery.length < 2) { this.searchResults = null; return; }
            this.searchLoading = true;
            try {
                const res = await fetch('/search/live?q=' + encodeURIComponent(this.searchQuery), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                this.searchResults = await res.json();
                this.$nextTick(() => { if(window.renderIcons) window.renderIcons(); });
            } catch { this.searchResults = null; } finally { this.searchLoading = false; }
        },
        totalResults() {
            return this.searchResults?.total ?? 0;
        }
    }"
    x-on:keydown.escape.window="closeAll()">

    <div class="header-inner max-w-7xl mx-auto px-5 lg:px-12 py-5 lg:py-6 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center z-[110]" aria-label="{{ $siteName }} - Home">
            @if($logoDesktop)
            <img src="{{ $logoDesktop->url }}" alt="{{ $siteName }}" class="brand-logo hidden sm:block relative" width="200" height="56" loading="eager">
            @endif
            @if($logoMobile)
            <img src="{{ $logoMobile->url }}" alt="{{ $siteName }}" class="brand-logo block sm:hidden relative" width="150" height="40" loading="eager">
            @elseif($logoDesktop)
            <img src="{{ $logoDesktop->url }}" alt="{{ $siteName }}" class="brand-logo block sm:hidden relative" width="150" height="40" loading="eager">
            @endif
            @if(!$logoDesktop && !$logoMobile)
            <div class="hidden sm:block">
                <span class="text-forest font-heading text-2xl font-bold leading-none tracking-tight block">{{ $siteName }}</span>
                <span class="text-forest/40 text-[10px] tracking-[0.25em] uppercase mt-1 block">Luxury Landscaping</span>
            </div>
            <span class="sm:hidden text-forest font-heading text-xl font-bold tracking-tight">{{ $siteName }}</span>
            @endif
        </a>

        {{-- Desktop Navigation --}}
        <nav class="hidden lg:flex items-center gap-12" aria-label="Main Navigation">
            <a href="{{ url('/services') }}" class="nav-link text-xs font-semibold tracking-[0.2em] uppercase transition-all duration-300">Services</a>
            <a href="{{ url('/portfolio') }}" class="nav-link text-xs font-semibold tracking-[0.2em] uppercase transition-all duration-300">Portfolio</a>
            <a href="{{ url('/about-us') }}" class="nav-link text-xs font-semibold tracking-[0.2em] uppercase transition-all duration-300">Process</a>
            @if($phone)
            <a href="tel:{{ $phoneClean }}" class="hidden xl:flex items-center gap-2 text-forest/50 hover:text-forest text-[11px] tracking-[0.15em] transition-all duration-300">
                <i data-lucide="phone" class="w-3.5 h-3.5"></i>{{ $phone }}
            </a>
            @endif
            @if($showSearch)
            <button x-on:click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.desktopSearch.focus())"
                    class="w-4 h-4 flex items-center justify-center text-forest/50 hover:text-forest transition-all duration-300"
                    aria-label="Search">
                <i data-lucide="search" class="w-4 h-4"></i>
            </button>
            @endif
            <a href="{{ $navCtaUrl }}" class="border border-deepGreen text-deepGreen hover:bg-deepGreen hover:text-white transition-colors px-8 h-10 inline-flex items-center justify-center text-[10px] font-bold tracking-[0.15em] uppercase rounded-sm">
                Request Quote
            </a>
        </nav>

        {{-- Mobile Toggle --}}
        <div class="lg:hidden flex items-center gap-4 z-[110]">
            @if($showSearch)
            <button x-on:click="searchOpen = !searchOpen; if(searchOpen) $nextTick(() => $refs.desktopSearch.focus())"
                    class="w-6 h-6 flex items-center justify-center text-forest hover:text-forest/80 transition" aria-label="Search">
                <i data-lucide="search" class="w-5 h-5"></i>
            </button>
            @endif
            <button @click="mobileOpen = !mobileOpen" class="relative w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none group" aria-label="Toggle mobile menu" :aria-expanded="mobileOpen.toString()">
                <span class="w-6 h-[2px] bg-deepGreen block transition-all duration-300 origin-center" :class="mobileOpen ? 'rotate-45 translate-y-[8px]' : ''"></span>
                <span class="w-6 h-[2px] bg-deepGreen block transition-all duration-300" :class="mobileOpen ? 'opacity-0' : ''"></span>
                <span class="w-6 h-[2px] bg-deepGreen block transition-all duration-300 origin-center" :class="mobileOpen ? '-rotate-45 -translate-y-[8px]' : ''"></span>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Overlay --}}
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="opacity-0 -translate-y-full" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-400" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-full" 
         class="fixed inset-0 bg-white z-[105] lg:hidden flex flex-col pt-24 px-6 pb-10 overflow-y-auto" x-cloak>
        <nav class="flex flex-col gap-8 mb-12">
            <a href="{{ url('/services') }}" @click="mobileOpen = false" class="text-3xl font-serif text-deepGreen border-b border-stone/30 pb-4">Services</a>
            <a href="{{ url('/portfolio') }}" @click="mobileOpen = false" class="text-3xl font-serif text-deepGreen border-b border-stone/30 pb-4">Portfolio</a>
            <a href="{{ url('/about-us') }}" @click="mobileOpen = false" class="text-3xl font-serif text-deepGreen border-b border-stone/30 pb-4">Process</a>
        </nav>
        <div class="mt-auto flex flex-col gap-6">
            <a href="{{ url('/consultation') }}" @click="mobileOpen = false" class="btn-solid h-14 flex items-center justify-center w-full text-xs font-bold tracking-[0.2em] uppercase rounded-sm">Request Quote</a>
            @if($phone)
            <a href="tel:{{ $phoneClean }}" class="text-center text-sm font-semibold tracking-widest text-deepGreen/70 flex items-center justify-center gap-2">
                <i class="fa-solid fa-phone"></i> {{ $phone }}
            </a>
            @endif
        </div>
    </div>

    {{-- ── Search Bar ─────────────────────────────────────────────────────── --}}
    @if($showSearch)
    <div x-show="searchOpen" x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="bg-black/80 backdrop-blur-xl border-t border-white/5">
        <div class="max-w-3xl mx-auto px-6 py-5">
            <div class="relative">
                <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-white/40"></i>
                </div>
                <label for="desktop-search" class="sr-only">Search services, cities, and blog posts</label>
                <input type="text"
                       id="desktop-search"
                       name="q"
                       autocomplete="off"
                       x-ref="desktopSearch"
                       x-model="searchQuery"
                       x-on:input.debounce.300ms="doSearch()"
                       placeholder="{{ $searchPlaceholder }}"
                       aria-label="Search services, cities, and blog posts"
                       class="w-full pl-12 pr-12 py-4 bg-white/5 border border-white/10 text-white placeholder-white/30 text-sm focus:outline-none focus:border-white/25 transition">
                <button x-show="searchQuery" x-cloak x-on:click="searchQuery = ''; searchResults = null;"
                        class="absolute inset-y-0 right-4 flex items-center text-white/40 hover:text-white transition" aria-label="Clear search">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
            <div x-show="searchQuery.length >= {{ $searchMinChars }}" x-cloak aria-live="polite" class="mt-3 bg-white shadow-luxury border border-stone overflow-hidden max-h-[70vh] overflow-y-auto">
                <div x-show="searchLoading" x-cloak class="flex items-center justify-center py-10">
                    <i data-lucide="loader-2" class="w-5 h-5 animate-spin text-forest"></i>
                </div>
                <template x-if="!searchLoading && searchResults">
                    <div>
                        <template x-if="totalResults() === 0">
                            <div class="py-10 text-center text-sm text-text-secondary">No results found for "<span x-text="searchQuery"></span>"</div>
                        </template>
                        <template x-if="searchResults.services && searchResults.services.length > 0">
                            <div class="border-b border-stone">
                                <div class="px-5 py-3 bg-cream flex items-center gap-2">
                                    <i data-lucide="wrench" class="w-3.5 h-3.5 text-forest"></i>
                                    <span class="text-eyebrow text-text-secondary">Services</span>
                                </div>
                                <template x-for="r in searchResults.services" :key="r.url">
                                    <a :href="r.url" class="flex items-center gap-3 px-5 py-3.5 hover:bg-forest/4 transition group border-b border-stone-light last:border-b-0">
                                        <div class="w-8 h-8 bg-forest/6 group-hover:bg-forest flex items-center justify-center shrink-0 transition">
                                            <i data-lucide="wrench" class="w-3.5 h-3.5 text-forest group-hover:text-white transition"></i>
                                        </div>
                                        <p class="text-sm text-ink group-hover:text-forest transition" x-text="r.name || r.title"></p>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="searchResults.categories && searchResults.categories.length > 0">
                            <div class="border-b border-stone">
                                <div class="px-5 py-3 bg-cream flex items-center gap-2">
                                    <i data-lucide="folder-tree" class="w-3.5 h-3.5 text-forest"></i>
                                    <span class="text-eyebrow text-text-secondary">Service Categories</span>
                                </div>
                                <template x-for="r in searchResults.categories" :key="r.url">
                                    <a :href="r.url" class="flex items-center gap-3 px-5 py-3.5 hover:bg-forest/4 transition group border-b border-stone-light last:border-b-0">
                                        <div class="w-8 h-8 bg-forest/6 group-hover:bg-forest flex items-center justify-center shrink-0 transition">
                                            <i data-lucide="folder-tree" class="w-3.5 h-3.5 text-forest group-hover:text-white transition"></i>
                                        </div>
                                        <p class="text-sm text-ink group-hover:text-forest transition" x-text="r.name || r.title"></p>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="searchResults.cities && searchResults.cities.length > 0">
                            <div class="border-b border-stone">
                                <div class="px-5 py-3 bg-cream flex items-center gap-2">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-forest"></i>
                                    <span class="text-eyebrow text-text-secondary">Locations</span>
                                </div>
                                <template x-for="r in searchResults.cities" :key="r.url">
                                    <a :href="r.url" class="flex items-center gap-3 px-5 py-3.5 hover:bg-forest/4 transition group border-b border-stone-light last:border-b-0">
                                        <div class="w-8 h-8 bg-forest/6 group-hover:bg-forest flex items-center justify-center shrink-0 transition">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-forest group-hover:text-white transition"></i>
                                        </div>
                                        <p class="text-sm text-ink group-hover:text-forest transition" x-text="r.name || r.title"></p>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="searchResults.blog && searchResults.blog.length > 0">
                            <div class="border-b border-stone">
                                <div class="px-5 py-3 bg-cream flex items-center gap-2">
                                    <i data-lucide="newspaper" class="w-3.5 h-3.5 text-forest"></i>
                                    <span class="text-eyebrow text-text-secondary">Blog</span>
                                </div>
                                <template x-for="r in searchResults.blog" :key="r.url">
                                    <a :href="r.url" class="block px-5 py-3.5 hover:bg-forest/4 transition border-b border-stone-light last:border-b-0">
                                        <p class="text-sm text-ink hover:text-forest transition" x-text="r.title"></p>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="searchResults.portfolio && searchResults.portfolio.length > 0">
                            <div class="border-b border-stone">
                                <div class="px-5 py-3 bg-cream flex items-center gap-2">
                                    <i data-lucide="image" class="w-3.5 h-3.5 text-forest"></i>
                                    <span class="text-eyebrow text-text-secondary">Portfolio</span>
                                </div>
                                <template x-for="r in searchResults.portfolio" :key="r.url">
                                    <a :href="r.url" class="block px-5 py-3.5 hover:bg-forest/4 transition border-b border-stone-light last:border-b-0">
                                        <p class="text-sm text-ink hover:text-forest transition" x-text="r.title || r.name"></p>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="searchResults.faqs && searchResults.faqs.length > 0">
                            <div>
                                <div class="px-5 py-3 bg-cream flex items-center gap-2">
                                    <i data-lucide="help-circle" class="w-3.5 h-3.5 text-forest"></i>
                                    <span class="text-eyebrow text-text-secondary">FAQs</span>
                                </div>
                                <template x-for="r in searchResults.faqs" :key="r.url">
                                    <a :href="r.url" class="block px-5 py-3.5 hover:bg-forest/4 transition border-b border-stone-light last:border-b-0">
                                        <p class="text-sm text-ink hover:text-forest transition" x-text="r.title || r.question"></p>
                                    </a>
                                </template>
                            </div>
                        </template>
                        <template x-if="totalResults() > 0">
                            <div class="px-5 py-4 bg-cream border-t border-stone">
                                <a :href="'/search?q=' + encodeURIComponent(searchQuery)" class="text-eyebrow text-forest hover:underline">
                                    View all results &rarr;
                                </a>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </div>
    @endif

    {{-- ── Services Mega Panel ──────────────────────────────────────────── --}}
    <div x-show="activeMenu === 'services'" x-cloak
         x-on:mouseenter="cancelClose()"
         x-on:mouseleave="scheduleClose()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute top-full left-0 right-0 bg-white shadow-luxury z-40 border-t border-forest/20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
            <div class="flex gap-10">
                <div class="flex-1 grid {{ $gridCols }} gap-10">
                    @foreach($megaCats as $nc)
                    <div>
                        <a href="{{ url('/services/' .  $nc->slug_final  . '') }}" class="flex items-center gap-3 mb-5 group">
                            <div class="w-10 h-10 bg-forest/6 group-hover:bg-forest flex items-center justify-center shrink-0 transition-all duration-300 border border-forest/10 group-hover:border-forest">
                                <i data-lucide="{{ $nc->icon ?? 'layers' }}" class="w-4 h-4 text-forest group-hover:text-white transition-colors duration-300"></i>
                            </div>
                            <span class="text-[11px] font-semibold text-ink uppercase tracking-[0.2em] group-hover:text-forest transition-colors duration-300">{{ $nc->name }}</span>
                        </a>

                        @if($nc->children->isNotEmpty())
                        <div class="space-y-5">
                            @foreach($nc->children as $sub)
                            <div>
                                <a href="{{ url('/services/' .  $sub->slug_final  . '') }}"
                                   class="flex items-center gap-1.5 text-[10px] font-semibold text-text-secondary uppercase tracking-[0.2em] hover:text-forest transition mb-2.5">
                                    <i data-lucide="{{ $sub->icon ?? 'chevron-right' }}" class="w-3 h-3 shrink-0"></i>
                                    {{ $sub->name }}
                                </a>
                                <ul class="space-y-0.5 pl-4 border-l border-stone">
                                    @foreach($sub->services as $svc)
                                    <li>
                                        <a href="{{ url('/services/' .  $sub->slug_final  . '/' .  $svc->slug_final  . '') }}"
                                           class="flex items-center gap-2 text-sm text-text-secondary hover:text-forest hover:bg-forest/3 px-3 py-2 transition">
                                            <span class="w-1 h-1 bg-stone-dark shrink-0"></span>
                                            <span class="truncate">{{ $svc->name }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <ul class="space-y-0.5 border-l border-stone pl-4">
                            @foreach($nc->services as $svc)
                            <li>
                                <a href="{{ url('/services/' .  $nc->slug_final  . '/' .  $svc->slug_final  . '') }}"
                                   class="flex items-center gap-2 text-sm text-text-secondary hover:text-forest hover:bg-forest/3 px-3 py-2 transition">
                                    <span class="w-1 h-1 bg-stone-dark shrink-0"></span>
                                    <span class="truncate">{{ $svc->name }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                        @endif

                        <a href="{{ url('/services/' .  $nc->slug_final  . '') }}" class="inline-flex items-center gap-1 mt-5 text-[11px] font-semibold uppercase tracking-[0.15em] text-forest link-underline">
                            All {{ $nc->name }} <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                    @endforeach
                </div>

                {{-- CTA sidebar --}}
                <div class="w-60 shrink-0">
                    <div class="bg-luxury-green-deep p-8 h-full flex flex-col">
                        <h3 class="text-white font-heading text-xl font-bold leading-snug mb-3">Begin Your Transformation</h3>
                        <p class="text-white/50 text-sm leading-relaxed mb-6">Complimentary consultation. 10-year warranty. No hidden costs.</p>
                        <a href="{{ url('/consultation') }}" class="btn-luxury btn-luxury-white text-[10px] mt-auto">
                            Project Consultation
                        </a>
                        @if($phone)
                        <a href="tel:{{ $phoneClean }}" class="flex items-center justify-center gap-2 mt-4 text-white/40 text-xs hover:text-white/70 transition">
                            <i data-lucide="phone" class="w-3 h-3"></i>{{ $phone }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-stone flex items-center justify-between">
                <p class="text-[11px] text-text-secondary tracking-[0.1em] uppercase">Serving Ontario, Canada &mdash; Consultation-led design &amp; build</p>
                <a href="{{ url('/services') }}" class="text-[11px] font-semibold uppercase tracking-[0.15em] text-forest link-underline flex items-center gap-1.5">
                    Browse All Services <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Locations Mega Panel ─────────────────────────────────────────── --}}
    <div x-show="activeMenu === 'locations'" x-cloak
         x-on:mouseenter="cancelClose()"
         x-on:mouseleave="scheduleClose()"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-2"
         class="absolute top-full left-0 right-0 bg-white shadow-luxury z-40 border-t border-forest/20">
        <div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
            <div class="flex gap-10">
                <div class="flex-1">
                    @if($megaCities->isNotEmpty())
                    <div class="columns-3 lg:columns-4 gap-x-10 gap-y-0">
                        @foreach($megaCities as $mc)
                        <a href="{{ url('/landscaping-' .  $mc->slug_final  . '') }}"
                           class="flex items-center gap-2.5 px-3 py-2.5 hover:bg-forest/3 group transition break-inside-avoid">
                            <span class="w-1.5 h-1.5 bg-stone group-hover:bg-forest transition shrink-0"></span>
                            <span class="text-sm text-text-secondary group-hover:text-forest transition truncate">{{ $mc->name }}</span>
                        </a>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-text-secondary">No published cities found.</p>
                    @endif
                </div>

                <div class="w-56 shrink-0">
                    <div class="bg-cream p-6 border border-stone h-full flex flex-col">
                        <h3 class="text-ink font-heading text-lg font-bold mb-2">Find Your Area</h3>
                        <p class="text-text-secondary text-sm leading-relaxed mb-5 flex-1">Professional landscaping across Ontario. Find services in your city.</p>
                        <a href="{{ url('/locations') }}" class="btn-luxury btn-luxury-primary text-[10px]">
                            All Locations
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-stone flex items-center justify-between">
                <p class="text-[11px] text-text-secondary tracking-[0.1em] uppercase">Local experts, local knowledge &mdash; serving communities across Ontario</p>
                <a href="{{ url('/locations') }}" class="text-[11px] font-semibold uppercase tracking-[0.15em] text-forest link-underline flex items-center gap-1.5">
                    View All Service Areas <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- ── Mobile Menu ─────────────────────────────────────────────────── --}}
    <div x-show="mobileOpen" x-cloak
         x-trap.inert.noscroll="mobileOpen"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="lg:hidden bg-forest-900/98 backdrop-blur-sm border-t border-white/5 max-h-[85vh] overflow-y-auto">
        <nav class="px-6 py-4 space-y-0.5">

            <div>
                <button type="button" x-on:click="mobileExpanded.services = !mobileExpanded.services"
                        class="w-full flex items-center justify-between px-4 py-4 text-white/70 hover:text-white text-[11px] font-semibold uppercase tracking-[0.2em] transition">
                    <span class="flex items-center gap-3">
                        <i data-lucide="wrench" class="w-4 h-4 shrink-0"></i>Services
                    </span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="mobileExpanded.services ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="mobileExpanded.services" x-cloak x-collapse class="mt-1 space-y-0.5">
                    @foreach($megaCats as $nc)
                    <div class="pl-4">
                        <button type="button" x-on:click="mobileExpanded['cat_{{ $nc->id }}'] = !mobileExpanded['cat_{{ $nc->id }}']"
                                class="w-full flex items-center justify-between px-3 py-3 text-white/60 hover:text-white text-sm transition">
                            <span class="flex items-center gap-2">
                                <i data-lucide="{{ $nc->icon ?? 'layers' }}" class="w-3.5 h-3.5 shrink-0"></i>{{ $nc->name }}
                            </span>
                            <i data-lucide="chevron-right" class="w-3 h-3 transition-transform" :class="mobileExpanded['cat_{{ $nc->id }}'] ? 'rotate-90' : ''"></i>
                        </button>
                        <div x-show="mobileExpanded[' x-cloakcat_{{ $nc->id }}']" x-collapse class="pl-3 space-y-0.5 mt-0.5">
                            @if($nc->children->isNotEmpty())
                                @foreach($nc->children as $sub)
                                <div>
                                    <button type="button" x-on:click="mobileExpanded['sub_{{ $sub->id }}'] = !mobileExpanded['sub_{{ $sub->id }}']"
                                            class="w-full flex items-center justify-between px-3 py-2.5 text-white/50 hover:text-white text-xs font-semibold transition">
                                        <span class="flex items-center gap-1.5">
                                            <i data-lucide="{{ $sub->icon ?? 'chevron-right' }}" class="w-3 h-3 shrink-0"></i>{{ $sub->name }}
                                        </span>
                                        <i data-lucide="chevron-right" class="w-2.5 h-2.5 transition-transform" :class="mobileExpanded['sub_{{ $sub->id }}'] ? 'rotate-90' : ''"></i>
                                    </button>
                                    <div x-show="mobileExpanded[' x-cloaksub_{{ $sub->id }}']" x-collapse class="pl-4 space-y-0.5 mt-0.5">
                                        @foreach($sub->services as $svc)
                                        <a href="{{ url('/services/' .  $sub->slug_final  . '/' .  $svc->slug_final  . '') }}"
                                           x-on:click="mobileOpen = false"
                                           class="block px-3 py-2.5 text-xs text-white/40 hover:text-white transition border-l border-white/8">
                                            {{ $svc->name }}
                                        </a>
                                        @endforeach
                                        <a href="{{ url('/services/' .  $sub->slug_final  . '') }}" x-on:click="mobileOpen = false" class="block px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.1em] text-forest-300 hover:text-white transition">
                                            All {{ $sub->name }} &rarr;
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                @foreach($nc->services as $svc)
                                <a href="{{ url('/services/' .  $nc->slug_final  . '/' .  $svc->slug_final  . '') }}"
                                   x-on:click="mobileOpen = false"
                                   class="block px-3 py-2.5 text-xs text-white/45 hover:text-white transition border-l border-white/8">
                                    {{ $svc->name }}
                                </a>
                                @endforeach
                            @endif
                            <a href="{{ url('/services/' .  $nc->slug_final  . '') }}" x-on:click="mobileOpen = false" class="block px-3 py-2 text-[11px] font-semibold uppercase tracking-[0.1em] text-forest-300 hover:text-white transition">
                                All {{ $nc->name }} &rarr;
                            </a>
                        </div>
                    </div>
                    @endforeach
                    <a href="{{ url('/services') }}" x-on:click="mobileOpen = false" class="block px-4 py-3 text-[11px] font-semibold uppercase tracking-[0.15em] text-white/60 hover:text-white transition">
                        All Services &rarr;
                    </a>
                </div>
            </div>

            <div>
                <button type="button" x-on:click="mobileExpanded.locations = !mobileExpanded.locations"
                        class="w-full flex items-center justify-between px-4 py-4 text-white/70 hover:text-white text-[11px] font-semibold uppercase tracking-[0.2em] transition">
                    <span class="flex items-center gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 shrink-0"></i>Locations
                    </span>
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-300" :class="mobileExpanded.locations ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="mobileExpanded.locations" x-cloak x-collapse class="mt-1 space-y-0.5 pl-4">
                    <div class="grid grid-cols-2 gap-0.5">
                        @foreach($megaCities as $mc)
                        <a href="{{ url('/landscaping-' .  $mc->slug_final  . '') }}" x-on:click="mobileOpen = false"
                           class="px-3 py-2.5 text-sm text-white/50 hover:text-white transition">
                            {{ $mc->name }}
                        </a>
                        @endforeach
                    </div>
                    <a href="{{ url('/locations') }}" x-on:click="mobileOpen = false" class="block px-3 py-2.5 text-[11px] font-semibold uppercase tracking-[0.1em] text-forest-300 hover:text-white transition">
                        All Locations &rarr;
                    </a>
                </div>
            </div>

            @foreach([
                ['url' => '/portfolio', 'label' => 'Portfolio',   'icon' => 'image'],
                ['url' => '/about',     'label' => 'About Us',    'icon' => 'users'],
                ['url' => '/contact',   'label' => 'Contact',     'icon' => 'mail'],
            ] as $link)
            <a href="{{ $link['url'] }}" x-on:click="mobileOpen = false"
               class="flex items-center gap-3 px-4 py-4 text-white/70 hover:text-white text-[11px] font-semibold uppercase tracking-[0.2em] transition">
                <i data-lucide="{{ $link['icon'] }}" class="w-4 h-4 shrink-0"></i>{{ $link['label'] }}
            </a>
            @endforeach

            @if($showSearch)
            <div class="pt-3 pb-1">
                <div class="relative">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-white/40"></i>
                    <label for="mobile-search" class="sr-only">Search services, cities, and blog posts</label>
                    <input type="text"
                           id="mobile-search"
                           name="q"
                           autocomplete="off"
                           x-ref="mobileSearch"
                           x-model="searchQuery"
                           x-on:input.debounce.300ms="doSearch()"
                           placeholder="Search…"
                           aria-label="Search"
                           class="w-full pl-10 pr-4 py-3.5 bg-white/5 border border-white/10 text-white placeholder-white/30 text-sm focus:outline-none focus:border-white/20 transition">
                </div>
                <div x-show="searchQuery.length >= {{ $searchMinChars }} && searchResults" x-cloak class="mt-2 bg-white overflow-hidden shadow-luxury max-h-64 overflow-y-auto border border-stone">
                    <template x-if="totalResults() === 0">
                        <p class="py-6 text-center text-sm text-text-secondary">No results found</p>
                    </template>
                    <template x-if="totalResults() > 0">
                        <div>
                            <template x-for="group in ['services','categories','cities','blog','portfolio','faqs']">
                                <template x-if="searchResults[group] && searchResults[group].length > 0">
                                    <div>
                                        <template x-for="r in searchResults[group]" :key="(r.url || '') + group">
                                            <a :href="r.url" x-on:click="mobileOpen = false"
                                               class="block px-5 py-3.5 text-sm text-ink hover:text-forest hover:bg-forest/4 border-b border-stone-light transition"
                                               x-text="r.name || r.title || r.question"></a>
                                        </template>
                                    </div>
                                </template>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
            @endif

            <div class="pt-5 pb-6 border-t border-white/8 mt-3 space-y-3">
                <a href="{{ $navCtaUrl }}" x-on:click="mobileOpen = false"
                   class="btn-luxury btn-luxury-white w-full text-[11px]">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>{{ $navCtaText }}
                </a>
                @if($phone)
                <a href="tel:{{ $phoneClean }}" class="btn-luxury btn-luxury-ghost w-full text-[11px]">
                    <i data-lucide="phone" class="w-4 h-4"></i>Call {{ $phone }}
                </a>
                @endif
            </div>
        </nav>
    </div>

    {{-- Mega panel backdrop --}}
    <div x-show="activeMenu !== null" x-cloak
         x-on:click="closeAll()"
         class="fixed inset-0 bg-black/30 z-30 top-[var(--header-h,96px)]"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"></div>

</header>
