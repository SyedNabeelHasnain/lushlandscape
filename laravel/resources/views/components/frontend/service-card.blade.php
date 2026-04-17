@props(['service', 'url' => '#', 'variant' => 'architectural', 'showIcon' => false, 'showDivider' => false, 'ctaLabel' => 'Explore'])

@php
    // Variant Logic
    $cardClasses = match($variant) {
        'minimal' => 'group block transition-all duration-500 hover:translate-y-[-2px]',
        'editorial' => 'group block bg-white rounded-[1.75rem] p-8 border border-stone hover:-translate-y-1 hover:shadow-luxury hover:border-accent/60 transition-all duration-500',
        'premium-2x2' => 'group block bg-cream-light rounded-[2rem] p-10 border border-stone/50 hover:shadow-luxury-lg hover:-translate-y-2 hover:border-accent/40 transition-all duration-700 relative overflow-hidden',
        default => 'group block bg-white border border-stone overflow-hidden hover:border-forest transition-all duration-500 hover:shadow-luxury',
    };

    $imageWrapper = match($variant) {
        'minimal' => 'aspect-4/3 overflow-hidden rounded-2xl mb-6',
        'editorial' => 'hidden', // editorial usually skips hero image or uses icon
        'premium-2x2' => 'hidden',
        default => 'aspect-16/10 overflow-hidden bg-cream relative',
    };
@endphp

<a href="{{ $url }}" class="{{ $cardClasses }}">
    {{-- Image Section (for architectural/minimal) --}}
    <div class="{{ $imageWrapper }}">
        @if($service->heroMedia ?? null)
        <img src="{{ $service->heroMedia->url }}"
             alt="{{ $service->heroMedia->default_alt_text ?? $service->name }}"
             class="w-full h-full object-cover img-zoom group-hover:scale-105 transition-transform duration-700"
             width="600" height="375"
             loading="lazy">
        @else
        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-cream to-stone-light">
            <i data-lucide="{{ $service->icon ?? 'layers' }}" class="w-10 h-10 text-forest/20"></i>
        </div>
        @endif
        
        @if(($service->category ?? null) && $variant === 'architectural')
        <div class="absolute top-5 left-5">
            <span class="bg-forest/90 text-white text-[10px] tracking-[0.2em] uppercase font-semibold px-4 py-2">{{ $service->category->name }}</span>
        </div>
        @endif
    </div>

    {{-- Content Section --}}
    <div class="{{ $variant === 'architectural' ? 'p-10' : '' }} flex flex-col h-full">
        @if($variant === 'premium-2x2')
            <div class="absolute top-0 right-0 w-32 h-32 bg-forest/[0.03] rounded-bl-full -mr-16 -mt-16 group-hover:bg-forest/[0.08] transition-colors duration-700 pointer-events-none"></div>
        @endif

        @if($showIcon && ($service->icon ?? null))
        <div class="mb-6 relative z-10">
            <div class="flex h-14 w-14 items-center justify-center rounded-2xl bg-white text-forest group-hover:bg-forest group-hover:text-white transition-colors duration-500 shadow-sm border border-stone">
                <i data-lucide="{{ $service->icon }}" class="w-7 h-7"></i>
            </div>
        </div>
        @endif

        <h3 class="text-xl font-heading font-bold text-ink group-hover:text-forest transition-colors duration-300 leading-snug relative z-10">{{ $service->name }}</h3>
        
        @if($showDivider)
        <div class="w-12 h-px bg-forest/20 my-4 group-hover:w-20 group-hover:bg-accent transition-all duration-500 relative z-10"></div>
        @endif

        @if($service->service_summary ?? null)
        <p class="mt-4 text-[0.95rem] text-text-secondary line-clamp-3 leading-relaxed flex-grow relative z-10">{{ $service->service_summary }}</p>
        @endif

        <div class="mt-8 flex items-center gap-2 text-forest text-[11px] font-semibold tracking-[0.15em] uppercase mt-auto pt-4 relative z-10">
            {{ $ctaLabel }} <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1.5 transition-transform duration-400"></i>
        </div>
    </div>
</a>