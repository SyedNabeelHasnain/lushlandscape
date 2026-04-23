{{-- Block: service_categories --}}
@php
    $eyebrow = $content['eyebrow'] ?? '';
    $heading = !empty($content['heading']) ? $content['heading'] : 'Our Service Categories';
    $subtitle = $content['subtitle'] ?? '';
    $layout = $content['layout'] ?? 'grid';
    $variant = $content['variant'] ?? 'editorial';
    $tone = $content['tone'] ?? 'light';
    $showServicePreview = $content['show_service_preview'] ?? true;
    
    $colClass = $layout === 'list' ? 'grid-cols-1' : 'md:grid-cols-2 lg:grid-cols-3';
    $toneMap = match ($tone) {
        'dark' => [
            'heading' => 'text-white',
            'sub' => 'text-white/72',
            'label' => 'text-white/55',
            'card' => 'border-white/12 bg-white/6 text-white hover:border-white/22',
            'icon' => 'bg-white/10 text-white',
            'divider' => 'border-white/10',
            'link' => 'text-white',
        ],
        'cream' => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-cream text-ink hover:border-accent/55',
            'icon' => 'bg-white text-forest',
            'divider' => 'border-stone',
            'link' => 'text-forest',
        ],
        default => [
            'heading' => 'text-ink',
            'sub' => 'text-text-secondary',
            'label' => 'text-text-secondary',
            'card' => 'border-stone bg-white text-ink hover:border-accent/55',
            'icon' => 'bg-forest/6 text-forest',
            'divider' => 'border-stone-light',
            'link' => 'text-forest',
        ],
    };
@endphp

@if($data->isNotEmpty())
    @php
        $data->loadMissing(['services' => function ($query) {
            $query->where('status', 'published')->orderBy('sort_order');
        }]);
    @endphp
    <div class="space-y-12">
        <div class="max-w-3xl {{ $layout === 'list' ? '' : 'mx-auto text-center' }} mb-16">
            @if($eyebrow)
                <p class="text-luxury-label {{ $toneMap['label'] }} block mb-4">{{ $eyebrow }}</p>
            @endif
            @if($heading)
                <h2 class="text-h2 font-heading font-bold tracking-tight text-balance {{ $toneMap['heading'] }} animate-on-scroll" data-animation="fade-up">
                    {!! $heading !!}
                </h2>
            @endif
            @if($subtitle)
                <p class="mt-6 text-body-lg leading-relaxed text-balance {{ $toneMap['sub'] }} animate-on-scroll" data-animation="fade-up" data-delay="100">
                    {!! $subtitle !!}
                </p>
            @endif
        </div>

        <div class="grid {{ $colClass }} gap-8">
            @foreach($data as $idx => $cat)
                @php
                    $shell = $variant === 'minimal'
                        ? 'rounded-[1.5rem] border p-6'
                        : 'editorial-card rounded-[1.8rem]';
                @endphp
                <div class="group relative transition-all duration-500 animate-on-scroll {{ $toneMap['card'] }} {{ $shell }}" 
                     data-animation="fade-up" 
                     data-delay="{{ $idx * 50 }}">
                    
                    <div class="absolute inset-0 bg-gradient-to-br from-forest/5 to-transparent opacity-0 transition-opacity duration-500 {{ $tone === 'dark' ? 'from-white/6' : '' }} group-hover:opacity-100"></div>

                    <div class="relative z-10">
                        <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl transition-colors duration-500 {{ $toneMap['icon'] }} {{ $tone === 'dark' ? 'group-hover:bg-white group-hover:text-forest' : 'group-hover:bg-forest group-hover:text-white' }}">
                            <i data-lucide="{{ $cat->icon ?? 'layers' }}" class="w-7 h-7 transition-colors duration-500"></i>
                        </div>

                        <h3 class="mb-3 text-xl font-bold transition-colors duration-300 {{ $toneMap['heading'] }} {{ $tone === 'dark' ? 'group-hover:text-white' : 'group-hover:text-forest' }}">
                            {{ $cat->name }}
                        </h3>

                        @if($cat->short_description)
                            <p class="mb-6 line-clamp-3 text-sm leading-relaxed {{ $toneMap['sub'] }}">
                                {{ $cat->short_description }}
                            </p>
                        @endif

                        <div class="pt-6 border-t {{ $toneMap['divider'] }}">
                            @if($showServicePreview)
                                <ul class="mb-8 space-y-2">
                                    @foreach($cat->services->take(3) as $service)
                                        <li class="group/link flex items-center gap-2 text-sm {{ $toneMap['sub'] }}">
                                            <div class="h-1.5 w-1.5 rounded-full bg-forest/30 transition-colors group-hover/link:bg-forest"></div>
                                            <a href="{{ url('/services/' .  $cat->slug  . '/' .  $service->slug  . '') }}" class="transition {{ $tone === 'dark' ? 'hover:text-white' : 'hover:text-forest' }}">
                                                {{ $service->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <a href="{{ url('/services/' .  $cat->slug  . '') }}" class="group/btn inline-flex items-center gap-2 text-sm font-bold uppercase tracking-wider {{ $toneMap['link'] }}">
                                Explore {{ $cat->name }}
                                <i data-lucide="arrow-right" class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
