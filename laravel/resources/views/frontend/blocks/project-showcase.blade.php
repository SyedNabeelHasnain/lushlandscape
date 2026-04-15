@php
    $asset   = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null;
    $details = $content['details'] ?? [];
@endphp
@if(!empty($content['title']))
<div class="max-w-5xl mx-auto px-6 lg:px-12 py-16">
    <div class="bg-white border border-stone overflow-hidden hover:shadow-luxury transition-all duration-500 reveal">
        <div class="grid grid-cols-1 md:grid-cols-2">
            @if($asset)
            <div class="overflow-hidden">
                <x-frontend.media :asset="$asset" :alt="$content['title']" class="w-full h-64 md:h-full object-cover img-zoom" />
            </div>
            @endif
            <div class="p-10 md:p-12 flex flex-col justify-center">
                @if(!empty($content['category']))
                <span class="text-[10px] font-bold text-forest uppercase tracking-[0.15em] mb-3">{{ $content['category'] }}</span>
                @endif
                <h3 class="text-2xl font-heading font-bold text-ink mb-4">{{ $content['title'] }}</h3>
                @if(!empty($content['description']))
                <p class="text-text-secondary leading-relaxed mb-8">{{ $content['description'] }}</p>
                @endif
                @if(!empty($details))
                <div class="grid grid-cols-2 gap-3 mb-8">
                    @foreach($details as $detail)
                    @if(!empty($detail['label']) && !empty($detail['value']))
                    <div class="bg-cream p-4">
                        <p class="text-[10px] text-text-secondary mb-1 tracking-[0.15em] uppercase">{{ $detail['label'] }}</p>
                        <p class="text-sm font-semibold text-ink">{{ $detail['value'] }}</p>
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif
                @if(!empty($content['link_url']))
                <a href="{{ $content['link_url'] }}" class="inline-flex items-center gap-2 text-forest font-semibold text-[11px] tracking-[0.15em] uppercase hover:gap-3 transition-all duration-300 mt-auto">
                    View Full Project <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endif
