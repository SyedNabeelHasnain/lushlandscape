{{-- Block: services_grid --}}
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : (($context['city_name'] ?? null) ? 'Services Available in '.($context['city_name'] ?? '') : 'Our Services');
    $subtitle = $content['subtitle'] ?? '';
    $layout = $content['layout'] ?? 'grid';
    $columns = $content['columns'] ?? '3';
    $variant = $content['variant'] ?? 'architectural';
    $tone = $content['tone'] ?? 'light';
    $showCategoryNav = $content['show_category_nav'] ?? true;
    $showViewAll = $content['show_view_all'] ?? true;
    $viewAllText = $content['view_all_text'] ?? 'View All Services';
    $viewAllUrl = $content['view_all_url'] ?? '/services';
    
    // Premium 2x2 and card specifics
    $showIcon = $content['show_icon'] ?? true;
    $showDivider = $content['show_divider'] ?? false;
    $showUspList = $content['show_usp_list'] ?? false;
    $cardCtaLabel = $content['card_cta_label'] ?? 'Details';
    
    // Group by category if we have mixed results
    $grouped = $data->groupBy(fn($s) => $s->category_id ?? 0);
    $categoryMap = $data->pluck('category')->filter()->unique('id')->sortBy('sort_order')->values();
    
    $colMap = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-2 lg:grid-cols-3', '4' => 'md:grid-cols-2 lg:grid-cols-4'];
    $colClass = $colMap[$columns] ?? 'md:grid-cols-2 lg:grid-cols-3';

    $sectionTone = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'chip' => 'border-white/12 bg-white/6 text-white/82 hover:border-white/25 hover:text-white',
            'card' => 'border-white/10 bg-white/6 text-white shadow-luxury hover:border-white/20',
            'cardSub' => 'text-white/68',
            'iconShell' => 'bg-white/8 text-white',
            'link' => 'text-white',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'chip' => 'border-stone bg-white/70 text-text-secondary hover:border-forest/25 hover:text-forest',
            'card' => 'border-stone bg-cream text-ink shadow-editorial hover:border-accent/60',
            'cardSub' => 'text-text-secondary',
            'iconShell' => 'bg-white text-forest',
            'link' => 'text-forest',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'chip' => 'border-stone bg-white text-text-secondary hover:border-forest/25 hover:text-forest',
            'card' => 'border-stone bg-white text-ink shadow-editorial hover:border-accent/60',
            'cardSub' => 'text-text-secondary',
            'iconShell' => 'bg-stone-light text-forest',
            'link' => 'text-forest',
        ],
    };
@endphp

@if($data->isNotEmpty())
    <div class="space-y-12">
        <div class="mb-16 max-w-4xl">
            @if($eyebrow)
                <p class="text-luxury-label {{ $sectionTone['label'] }}">{{ $eyebrow }}</p>
            @endif
            @if($heading)
                <h2 class="mt-4 text-h2 font-heading font-bold tracking-tight {{ $sectionTone['heading'] }} animate-on-scroll" data-animation="fade-up">
                    {{ $heading }}
                </h2>
            @endif
            @if($subtitle)
                <p class="mt-4 max-w-2xl text-body-lg {{ $sectionTone['sub'] }} animate-on-scroll" data-animation="fade-up" data-delay="100">
                    {{ $subtitle }}
                </p>
            @endif
        </div>

        @if($showCategoryNav && $categoryMap->count() > 1)
            <div class="flex flex-wrap gap-3 mb-12 animate-on-scroll" data-animation="fade-in">
                @foreach($categoryMap as $cat)
                    <a href="#cat-{{ $cat->slug_final }}" class="inline-flex items-center gap-2.5 rounded-full border px-6 py-3 text-sm font-semibold transition-all duration-300 {{ $sectionTone['chip'] }}">
                        <i data-lucide="{{ $cat->icon ?? 'layers' }}" class="w-4 h-4"></i>
                        {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        @endif

        @foreach($categoryMap->isEmpty() ? [null] : $categoryMap as $catIdx => $cat)
            @php $catServices = $cat ? $grouped->get($cat->id, collect()) : $data; @endphp
            @if($catServices->isNotEmpty())
                <div @if($cat) id="cat-{{ $cat->slug_final }}" @endif class="{{ $catIdx > 0 ? 'mt-20' : '' }}">
                    @if($cat)
                        <div class="flex items-center gap-4 mb-8">
                            <div class="flex h-10 w-10 items-center justify-center rounded-xl {{ $tone === 'dark' ? 'bg-white/10' : 'bg-forest text-white' }}">
                                <i data-lucide="{{ $cat->icon ?? 'layers' }}" class="w-5 h-5 {{ $tone === 'dark' ? 'text-white' : 'text-white' }}"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold {{ $sectionTone['heading'] }}">{{ $cat->name }}</h3>
                                @if($cat->short_description)
                                    <p class="mt-0.5 text-sm {{ $sectionTone['sub'] }}">{{ $cat->short_description }}</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="grid {{ $colClass }} gap-6 lg:gap-8">
                        @foreach($catServices as $sIdx => $service)
                            <div class="animate-on-scroll" data-animation="fade-up" data-delay="{{ $sIdx * 40 }}">
                                <x-frontend.service-card 
                                    :service="$service" 
                                    :url="$service->category ? $service->public_url : '/services/'.($service->slug_final ?? '')"
                                    :variant="$variant"
                                    :show-icon="$showIcon"
                                    :show-divider="$showDivider"
                                    :cta-label="$cardCtaLabel"
                                />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endforeach

        @if($showViewAll && $viewAllText && $viewAllUrl)
            <div class="pt-2">
                <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.18em] {{ $sectionTone['link'] }}">
                    {{ $viewAllText }}
                    <span class="h-px w-8 bg-current/35"></span>
                </a>
            </div>
        @endif
    </div>
@endif
