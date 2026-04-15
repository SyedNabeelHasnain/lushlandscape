{{-- Block: testimonials --}}
@if($data->isNotEmpty())
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : (($context['city_name'] ?? null) ? 'What '.($context['city_name'] ?? '').' Clients Say' : 'What Our Clients Say');
    $subtitle = $content['subtitle'] ?? '';
    $layout = $content['layout'] ?? 'grid';
    $variant = $content['variant'] ?? 'editorial';
    $tone = $content['tone'] ?? 'cream';
    $gridColsClass = match (min($data->count(), 3)) {
        1 => 'md:grid-cols-1',
        2 => 'md:grid-cols-2',
        default => 'md:grid-cols-3',
    };
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'card' => 'border-white/10 bg-white/6 text-white',
            'icon' => 'bg-white/10 text-white',
            'meta' => 'text-white/55',
        ],
        'light' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-white text-ink',
            'icon' => 'bg-forest text-white',
            'meta' => 'text-text-secondary',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-cream text-ink',
            'icon' => 'bg-forest text-white',
            'meta' => 'text-text-secondary',
        ],
    };
@endphp

<div class="mb-12 max-w-4xl {{ $variant === 'highlight' ? '' : 'mx-auto text-center' }}">
    @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
    @if($heading)<h2 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
    @if($subtitle)<p class="mt-4 text-body-lg {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
</div>

@if($layout === 'slider')
<div class="swiper testimonials-swiper">
    <div class="swiper-wrapper">
        @foreach($data as $review)
        <div class="swiper-slide">
            <div class="flex h-full flex-col rounded-[1.75rem] border p-7 {{ $toneMap['card'] }}">
                <div class="flex gap-0.5 mb-4">
                    @for($i = 1; $i <= 5; $i++)
                    <i data-lucide="star" class="w-4 h-4 {{ $i <= ($review->rating ?? 5) ? 'text-amber-400 fill-amber-400' : 'text-stone' }}"></i>
                    @endfor
                </div>
                <p class="flex-1 text-sm leading-relaxed {{ $toneMap['sub'] }}">&ldquo;{{ $review->content }}&rdquo;</p>
                <div class="mt-5 flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center shrink-0 font-bold text-sm {{ $toneMap['icon'] }}">
                        {{ $review->reviewer_initial ?? mb_strtoupper(mb_substr($review->reviewer_name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold {{ $toneMap['heading'] }}">{{ $review->reviewer_name }}</p>
                        @if($review->city_relevance)<p class="text-xs {{ $toneMap['meta'] }}">{{ $review->city_relevance }}</p>@endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="swiper-pagination mt-8"></div>
</div>
@else
<div class="grid grid-cols-1 {{ $gridColsClass }} gap-6">
    @foreach($data as $review)
    <div class="flex flex-col rounded-[1.75rem] border p-7 {{ $toneMap['card'] }} {{ $variant === 'highlight' ? 'lg:flex-row lg:items-start lg:gap-8' : '' }}">
        <div class="{{ $variant === 'highlight' ? 'lg:max-w-[4rem]' : '' }}">
        <div class="flex gap-0.5 mb-4">
            @for($i = 1; $i <= 5; $i++)
            <i data-lucide="star" class="w-4 h-4 {{ $i <= ($review->rating ?? 5) ? 'text-amber-400 fill-amber-400' : 'text-stone' }}"></i>
            @endfor
        </div>
        </div>
        <div class="flex-1">
        <p class="text-sm leading-relaxed {{ $toneMap['sub'] }}">&ldquo;{{ $review->content }}&rdquo;</p>
        <div class="mt-5 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center shrink-0 font-bold text-sm {{ $toneMap['icon'] }}">
                {{ $review->reviewer_initial ?? mb_strtoupper(mb_substr($review->reviewer_name, 0, 1)) }}
            </div>
            <div>
                <p class="text-sm font-semibold {{ $toneMap['heading'] }}">{{ $review->reviewer_name }}</p>
                @if($review->city_relevance)<p class="text-xs {{ $toneMap['meta'] }}">{{ $review->city_relevance }}</p>@endif
            </div>
        </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endif
