@php
    $rating = floatval($content['rating'] ?? 5);
    $full   = intval(floor($rating));
    $half   = ($rating - $full) >= 0.5;
    $style  = $content['style'] ?? 'card';
@endphp
@if($rating > 0)
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
    @if($style === 'banner')
    <div class="bg-luxury-green-deep py-12 px-8 text-center text-white reveal">
        <div class="flex items-center justify-center gap-1 mb-3">
            @for($s = 1; $s <= 5; $s++)
            <svg class="w-6 h-6 {{ $s <= $full ? 'text-accent' : ($s === $full + 1 && $half ? 'text-accent' : 'text-white/30') }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        <p class="text-4xl font-heading font-bold text-gold">{{ $rating }}</p>
        @if(!empty($content['total_reviews']))
        <p class="text-white/50 text-sm mt-2">Based on {{ $content['total_reviews'] }} reviews</p>
        @endif
        @if(!empty($content['source']))
        <p class="text-white/30 text-xs mt-2 tracking-[0.15em] uppercase">on {{ $content['source'] }}</p>
        @endif
    </div>

    @elseif($style === 'inline')
    <div class="flex flex-wrap items-center gap-4">
        <div class="flex items-center gap-0.5">
            @for($s = 1; $s <= 5; $s++)
            <svg class="w-5 h-5 {{ $s <= $full ? 'text-accent' : 'text-stone' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        <span class="font-bold text-ink">{{ $rating }}</span>
        @if(!empty($content['total_reviews']))
        <span class="text-sm text-text-secondary">({{ $content['total_reviews'] }} reviews)</span>
        @endif
        @if(!empty($content['source']))
        <span class="text-xs text-text-secondary">on {{ $content['source'] }}</span>
        @endif
        @if(!empty($content['text']))
        <span class="text-sm text-text-secondary">- {{ $content['text'] }}</span>
        @endif
    </div>

    @else
    <div class="bg-white border border-stone p-8 text-center max-w-xs mx-auto hover:shadow-luxury transition-all duration-500 reveal">
        <div class="flex items-center justify-center gap-0.5 mb-4">
            @for($s = 1; $s <= 5; $s++)
            <svg class="w-5 h-5 {{ $s <= $full ? 'text-accent' : 'text-stone' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        <p class="text-3xl font-heading font-bold text-gold">{{ $rating }}</p>
        @if(!empty($content['total_reviews']))
        <p class="text-sm text-text-secondary mt-2">{{ $content['total_reviews'] }} reviews</p>
        @endif
        @if(!empty($content['source']))
        <p class="text-xs text-text-secondary mt-3 flex items-center justify-center gap-1">
            <i data-lucide="external-link" class="w-3 h-3"></i> {{ $content['source'] }}
        </p>
        @endif
    </div>
    @endif
</div>
@endif
