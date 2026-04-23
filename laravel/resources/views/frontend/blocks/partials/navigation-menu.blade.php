@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $layout = $content['layout'] ?? 'horizontal';
    $tone = $content['tone'] ?? 'dark';
    $style = $content['style'] ?? 'luxury';
    $showServices = (bool) ($content['show_services'] ?? true);
    $showLocations = (bool) ($content['show_locations'] ?? true);
    $showPortfolio = (bool) ($content['show_portfolio'] ?? true);
    $showAbout = (bool) ($content['show_about'] ?? true);
    $showContact = (bool) ($content['show_contact'] ?? true);
    $serviceLimit = max(1, (int) ($content['service_limit'] ?? 6));
    $cityLimit = max(1, (int) ($content['city_limit'] ?? 8));

    $navCities = $theme->navCities($cityLimit);
    $navCats = $theme->navCategories($serviceLimit);

    $textClass = match ($tone) {
        'light' => 'text-ink/85 hover:text-forest',
        'muted' => 'text-white/60 hover:text-white',
        default => 'text-white/78 hover:text-white',
    };

    $panelClass = match ($tone) {
        'light' => 'bg-white border border-stone shadow-luxury',
        default => 'bg-forest-dark/95 border border-white/10 backdrop-blur-xl shadow-luxury',
    };

    $panelLinkClass = match ($tone) {
        'light' => 'text-ink hover:bg-forest/6 hover:text-forest',
        default => 'text-white/75 hover:bg-white/8 hover:text-white',
    };

    $overlayLinkClass = match ($tone) {
        'light' => 'text-ink hover:text-forest',
        default => 'text-white hover:text-accent-100',
    };

    $overlayMetaClass = match ($tone) {
        'light' => 'text-text-secondary',
        default => 'text-white/55',
    };

    $overlayChipClass = match ($tone) {
        'light' => 'border border-stone bg-white text-ink hover:border-forest/30 hover:text-forest',
        default => 'border border-white/12 bg-white/5 text-white/78 hover:border-white/28 hover:bg-white/9 hover:text-white',
    };

    $trackingClass = $style === 'luxury' ? 'uppercase tracking-[0.22em] text-[11px] font-semibold' : 'text-sm font-medium';

    $baseLinks = collect([
        $showPortfolio ? ['label' => 'Portfolio', 'url' => '/portfolio'] : null,
        $showAbout ? ['label' => 'About', 'url' => '/about'] : null,
        $showContact ? ['label' => 'Contact', 'url' => '/contact'] : null,
    ])->filter()->values();

    $wrapperClass = match ($layout) {
        'vertical' => 'flex flex-col gap-4',
        'footer' => 'flex flex-col gap-3',
        default => 'hidden lg:flex items-center gap-8',
    };
@endphp

@if($layout === 'mobile_overlay')
    <nav class="space-y-10 text-center" aria-label="Mobile navigation">
        <div class="space-y-5">
            @if($showServices)
                <a href="{{ url('/services') }}" class="block font-heading text-[clamp(2rem,8vw,3rem)] leading-none {{ $overlayLinkClass }}">
                    Services
                </a>
            @endif
            @if($showLocations)
                <a href="{{ url('/locations') }}" class="block font-heading text-[clamp(2rem,8vw,3rem)] leading-none {{ $overlayLinkClass }}">
                    Locations
                </a>
            @endif
            @foreach($baseLinks as $link)
                <a href="{{ $link['url'] }}" class="block font-heading text-[clamp(2rem,8vw,3rem)] leading-none {{ $overlayLinkClass }}">
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>

        @if($showServices && $navCats->isNotEmpty())
            <div class="space-y-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.24em] {{ $overlayMetaClass }}">Selected Services</p>
                <div class="flex flex-wrap justify-center gap-2.5">
                    @foreach($navCats as $cat)
                        <a href="{{ url('/services/' .  $cat->slug  . '') }}"
                            class="rounded-full px-3.5 py-2 text-[11px] font-medium transition {{ $overlayChipClass }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($showLocations && $navCities->isNotEmpty())
            <div class="space-y-4">
                <p class="text-[10px] font-semibold uppercase tracking-[0.24em] {{ $overlayMetaClass }}">Featured Locations</p>
                <div class="flex flex-wrap justify-center gap-2.5">
                    @foreach($navCities as $city)
                        <a href="{{ url('/professional-' .  $city->slug  . '') }}"
                            class="rounded-full px-3.5 py-2 text-[11px] font-medium transition {{ $overlayChipClass }}">
                            {{ $city->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>
@else
<nav class="{{ $wrapperClass }}">
    @if($layout === 'horizontal')
        @if($showServices)
            <div class="relative group">
                <a href="{{ url('/services') }}" class="{{ $trackingClass }} {{ $textClass }} transition flex items-center gap-1.5">
                    Services <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </a>
                <div class="absolute top-full left-0 mt-3 min-w-[18rem] rounded-2xl p-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50 {{ $panelClass }}">
                    @foreach($navCats as $cat)
                        <a href="{{ url('/services/' .  $cat->slug  . '') }}" class="block rounded-xl px-4 py-3 text-sm transition {{ $panelLinkClass }}">
                            {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @if($showLocations)
            <div class="relative group">
                <a href="{{ url('/locations') }}" class="{{ $trackingClass }} {{ $textClass }} transition flex items-center gap-1.5">
                    Locations <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </a>
                <div class="absolute top-full left-0 mt-3 min-w-[18rem] rounded-2xl p-3 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition z-50 {{ $panelClass }}">
                    @foreach($navCities as $city)
                        <a href="{{ url('/professional-' .  $city->slug  . '') }}" class="block rounded-xl px-4 py-3 text-sm transition {{ $panelLinkClass }}">
                            {{ $city->name }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach($baseLinks as $link)
            <a href="{{ $link['url'] }}" class="{{ $trackingClass }} {{ $textClass }} transition">
                {{ $link['label'] }}
            </a>
        @endforeach
    @else
        @if($showServices)
            <a href="{{ url('/services') }}" class="{{ $layout === 'footer' ? 'text-sm font-medium text-white/70 hover:text-white' : $trackingClass.' '.$textClass }} transition">
                Services
            </a>
        @endif
        @if($showLocations)
            <a href="{{ url('/locations') }}" class="{{ $layout === 'footer' ? 'text-sm font-medium text-white/70 hover:text-white' : $trackingClass.' '.$textClass }} transition">
                Locations
            </a>
        @endif
        @foreach($baseLinks as $link)
            <a href="{{ $link['url'] }}" class="{{ $layout === 'footer' ? 'text-sm font-medium text-white/70 hover:text-white' : $trackingClass.' '.$textClass }} transition">
                {{ $link['label'] }}
            </a>
        @endforeach
    @endif
</nav>
@endif
