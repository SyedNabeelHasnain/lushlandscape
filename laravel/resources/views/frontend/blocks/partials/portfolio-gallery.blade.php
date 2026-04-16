{{-- Block: portfolio_gallery --}}
@if($data->isNotEmpty())
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : (($context['city_name'] ?? null) ? 'Recent Projects in '.($context['city_name'] ?? '') : 'Recent Projects');
    $subtitle = $content['subtitle'] ?? '';
    $layout = $content['layout'] ?? 'grid';
    $columns = $content['columns'] ?? '3';
    $variant = $content['variant'] ?? 'editorial';
    $tone = $content['tone'] ?? 'light';
    $showViewAll = $content['show_view_all'] ?? true;
    $viewAllText = $content['view_all_text'] ?? 'View All Projects';
    $viewAllUrl = $content['view_all_url'] ?? '/portfolio';
    $colMap = ['2' => 'md:grid-cols-2', '3' => 'md:grid-cols-2 lg:grid-cols-3', '4' => 'md:grid-cols-2 lg:grid-cols-4'];
    $colClass = $colMap[$columns] ?? 'md:grid-cols-2 lg:grid-cols-3';
    $masonryCols = ['2' => 'md:columns-2', '3' => 'md:columns-2 lg:columns-3', '4' => 'md:columns-2 lg:columns-4'];
    $masonryClass = $masonryCols[$columns] ?? 'md:columns-2 lg:columns-3';
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'meta' => 'text-white/60',
            'surface' => 'bg-white/6',
            'link' => 'text-white',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'meta' => 'text-text-secondary',
            'surface' => 'bg-white/75',
            'link' => 'text-forest',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'meta' => 'text-text-secondary',
            'surface' => 'bg-white',
            'link' => 'text-forest',
        ],
    };
@endphp

<div class="mb-10 max-w-4xl">
    @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
    @if($heading)<h2 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
    @if($subtitle)<p class="mt-4 text-body-lg {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
</div>

@php
    $renderCard = function ($project) use ($variant, $tone, $toneMap) {
        $cardClass = match ($variant) {
            'minimal' => 'space-y-4',
            'stacked' => 'rounded-[1.75rem] overflow-hidden '.$toneMap['surface'].' shadow-editorial',
            'compact' => 'rounded-[1.5rem] overflow-hidden border border-stone '.$toneMap['surface'].' transition-all duration-500 hover:shadow-luxury',
            default => 'space-y-5',
        };
        $imageClass = match ($variant) {
            'minimal' => 'rounded-[1.25rem]',
            'stacked' => '',
            'compact' => '',
            default => 'rounded-[1.75rem]',
        };
        $metaCase = $variant === 'compact'
            ? 'text-[10px] uppercase tracking-[0.22em] font-semibold'
            : ($variant === 'minimal' ? 'text-[11px] uppercase tracking-[0.22em] font-semibold' : 'text-xs uppercase tracking-[0.22em] font-semibold');

        $imageRatio = $variant === 'compact' ? 'aspect-[4/3]' : 'aspect-[4/5]';
        $titleClass = $variant === 'compact' ? 'text-lg lg:text-xl' : 'text-h3';
        $descClamp = $variant === 'compact' ? 'line-clamp-1' : 'line-clamp-2';
        $padding = $variant === 'stacked' ? 'px-6 pb-6' : ($variant === 'compact' ? 'p-5' : '');

        return view('frontend.blocks.partials._portfolio-card', compact(
            'project',
            'cardClass',
            'imageClass',
            'metaCase',
            'imageRatio',
            'titleClass',
            'descClamp',
            'padding',
            'tone',
            'toneMap',
        ))->render();
    };
@endphp

@if($layout === 'slider')
    <div class="swiper portfolio-gallery-swiper overflow-hidden" data-columns="{{ $columns }}">
        <div class="swiper-wrapper">
            @foreach($data as $project)
                <div class="swiper-slide h-auto">
                    {!! $renderCard($project) !!}
                </div>
            @endforeach
        </div>
        <div class="mt-10 flex flex-wrap items-center justify-between gap-6">
            <div class="portfolio-gallery-pagination"></div>
            <div class="flex items-center gap-4">
                <button type="button" class="portfolio-gallery-prev btn-luxury border border-current/10 {{ $toneMap['link'] }}">Prev</button>
                <button type="button" class="portfolio-gallery-next btn-luxury border border-current/10 {{ $toneMap['link'] }}">Next</button>
            </div>
        </div>
    </div>
@elseif($layout === 'rail')
    <div class="flex overflow-x-auto gap-6 lg:gap-8 pb-8 snap-x snap-mandatory hide-scrollbar -mx-4 px-4 md:mx-0 md:px-0">
        @foreach($data as $project)
            <div class="snap-start shrink-0 w-[85vw] sm:w-[45vw] md:w-[35vw] lg:w-[28vw]">
                {!! $renderCard($project) !!}
            </div>
        @endforeach
    </div>
@elseif($layout === 'masonry')
    <div class="{{ $masonryClass }} gap-6 lg:gap-8 [column-gap:1.5rem] lg:[column-gap:2rem]">
        @foreach($data as $project)
            <div class="break-inside-avoid mb-6 lg:mb-8">
                {!! $renderCard($project) !!}
            </div>
        @endforeach
    </div>
@else
    <div class="grid {{ $colClass }} gap-6 lg:gap-8">
        @foreach($data as $project)
            {!! $renderCard($project) !!}
        @endforeach
    </div>
@endif

@if($showViewAll && $viewAllText && $viewAllUrl)
<div class="mt-10">
    <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.18em] {{ $toneMap['link'] }}">
        {{ $viewAllText }} <span class="w-8 h-px bg-current/35"></span>
    </a>
</div>
@endif
@endif
