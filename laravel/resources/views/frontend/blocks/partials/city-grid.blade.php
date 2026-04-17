{{-- Block: city_grid --}}
@if($data->isNotEmpty())
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : 'Service Areas';
    $subtitle = $content['subtitle'] ?? '';
    $layout = $content['layout'] ?? 'grid';
    $tone = $content['tone'] ?? 'light';
    $showViewAll = $content['show_view_all'] ?? true;
    $viewAllText = $content['view_all_text'] ?? 'View All Areas';
    $viewAllUrl = $content['view_all_url'] ?? '/locations';
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'card' => 'border-white/12 bg-white/6 text-white hover:border-white/24',
            'icon' => 'bg-white/10 text-white',
            'link' => 'text-white',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-white/75 text-ink hover:border-accent/55',
            'icon' => 'bg-white text-forest',
            'link' => 'text-forest',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-white text-ink hover:border-accent/55',
            'icon' => 'bg-forest/10 text-forest',
            'link' => 'text-forest',
        ],
    };
@endphp

<div class="mb-10 max-w-4xl {{ $layout === 'strip' ? 'mx-auto text-center' : '' }}">
    @if($eyebrow)<p class="text-luxury-label {{ $toneMap['label'] }}">{{ $eyebrow }}</p>@endif
    @if($heading)<h2 class="mt-4 text-h2 font-heading font-bold {{ $toneMap['heading'] }}">{{ $heading }}</h2>@endif
    @if($subtitle)<p class="mt-4 text-body-lg {{ $toneMap['sub'] }}">{{ $subtitle }}</p>@endif
</div>

@if($layout === 'strip')
    <div class="flex flex-wrap justify-center gap-x-10 gap-y-4">
        @foreach($data as $city)
            <a href="{{ url('/landscaping-' .  $city->slug_final  . '') }}" class="text-xl font-semibold transition {{ $toneMap['heading'] }} {{ $tone === 'dark' ? 'hover:text-white/80' : 'hover:text-forest' }}">
                {{ $city->name }}
            </a>
        @endforeach
    </div>
@elseif($layout === 'list')
    <div class="divide-y {{ $tone === 'dark' ? 'divide-white/10' : 'divide-stone-light' }} border-t {{ $tone === 'dark' ? 'border-white/10' : 'border-stone-light' }}">
        @foreach($data as $city)
            <a href="{{ url('/landscaping-' .  $city->slug_final  . '') }}" class="group flex flex-col gap-2 py-5 transition {{ $toneMap['card'] }} border-0 bg-transparent hover:bg-transparent">
                <div class="flex items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg shrink-0 transition-colors {{ $toneMap['icon'] }} {{ $tone === 'dark' ? 'group-hover:bg-white group-hover:text-forest' : 'group-hover:bg-forest group-hover:text-white' }}">
                            <i data-lucide="map-pin" class="w-4 h-4 transition-colors"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-semibold transition {{ $toneMap['heading'] }} {{ $tone === 'dark' ? 'group-hover:text-white' : 'group-hover:text-forest' }}">{{ $city->name }}</h3>
                            @if($city->region_name)<p class="text-xs {{ $toneMap['sub'] }}">{{ $city->region_name }}</p>@endif
                        </div>
                    </div>
                    <span class="text-[11px] font-semibold uppercase tracking-[0.18em] {{ $toneMap['label'] }} {{ $tone === 'dark' ? 'group-hover:text-white/70' : 'group-hover:text-forest/70' }}">View</span>
                </div>
                @if($city->neighborhoods && $city->neighborhoods->isNotEmpty())
                    <div class="text-sm {{ $toneMap['sub'] }}">
                        {{ $city->neighborhoods->pluck('name')->implode(' · ') }}
                    </div>
                @endif
            </a>
        @endforeach
    </div>
@else
    <div class="grid {{ $layout === 'compact' ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-3' : 'grid-cols-2 md:grid-cols-3 lg:grid-cols-4' }} gap-4">
        @foreach($data as $city)
        <a href="{{ url('/landscaping-' .  $city->slug_final  . '') }}" class="group border p-5 transition-all duration-300 {{ $toneMap['card'] }} {{ $layout === 'compact' ? 'rounded-[1.5rem]' : 'rounded-[1.25rem]' }}">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg shrink-0 transition-colors {{ $toneMap['icon'] }} {{ $tone === 'dark' ? 'group-hover:bg-white group-hover:text-forest' : 'group-hover:bg-forest group-hover:text-white' }}">
                    <i data-lucide="map-pin" class="w-4 h-4 transition-colors"></i>
                </div>
                <div>
                    <h3 class="text-sm font-semibold transition {{ $toneMap['heading'] }} {{ $tone === 'dark' ? 'group-hover:text-white' : 'group-hover:text-forest' }}">{{ $city->name }}</h3>
                    @if($city->region_name)<p class="text-xs {{ $toneMap['sub'] }}">{{ $city->region_name }}</p>@endif
                </div>
            </div>
        </a>
        @endforeach
    </div>
@endif

@if($showViewAll && $viewAllText && $viewAllUrl)
<div class="mt-10 {{ $layout === 'strip' ? 'text-center' : '' }}">
    <a href="{{ $viewAllUrl }}" class="inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.18em] {{ $toneMap['link'] }}">
        {{ $viewAllText }} <span class="w-8 h-px bg-current/35"></span>
    </a>
</div>
@endif
@endif
