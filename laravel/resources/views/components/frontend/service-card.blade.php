@props(['service', 'url' => '#'])
<a href="{{ $url }}" class="group block bg-white border border-stone overflow-hidden hover:border-forest transition-all duration-500 hover:shadow-luxury">
    <div class="aspect-16/10 overflow-hidden bg-cream relative">
        @if($service->heroMedia ?? null)
        <img src="{{ $service->heroMedia->url }}"
             alt="{{ $service->heroMedia->default_alt_text ?? $service->name }}"
             class="w-full h-full object-cover img-zoom"
             width="600" height="375"
             loading="lazy">
        @else
        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-cream to-stone-light">
            <i data-lucide="{{ $service->icon ?? 'layers' }}" class="w-10 h-10 text-forest/20"></i>
        </div>
        @endif
        @if($service->category ?? null)
        <div class="absolute top-5 left-5">
            <span class="bg-forest/90 text-white text-[10px] tracking-[0.2em] uppercase font-semibold px-4 py-2">{{ $service->category->name }}</span>
        </div>
        @endif
    </div>
    <div class="p-10">
        <h3 class="text-xl font-heading font-bold text-ink group-hover:text-forest transition-colors duration-300 leading-snug">{{ $service->name }}</h3>
        @if($service->service_summary ?? null)
        <p class="mt-4 text-sm text-text-secondary line-clamp-2 leading-relaxed">{{ $service->service_summary }}</p>
        @endif
        <div class="mt-6 flex items-center gap-2 text-forest text-[11px] font-semibold tracking-[0.15em] uppercase">
            Explore <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1.5 transition-transform duration-400"></i>
        </div>
    </div>
</a>
