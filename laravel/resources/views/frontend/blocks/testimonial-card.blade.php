@php
    $style  = $content['style'] ?? 'card';
    $rating = intval($content['rating'] ?? 5);
    $asset  = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null;
@endphp
@if(!empty($content['quote']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-6">
    @if($style === 'featured')
    {{-- Large featured testimonial --}}
    <div class="bg-cream  p-8 md:p-12 text-center max-w-3xl mx-auto">
        @if($rating > 0)
        <div class="flex items-center justify-center gap-1 mb-4">
            @for($s = 1; $s <= 5; $s++)
            <svg class="w-5 h-5 {{ $s <= $rating ? 'text-amber-400' : 'text-stone' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        @endif
        <p class="text-lg md:text-xl text-text leading-relaxed italic">"{{ $content['quote'] }}"</p>
        <div class="mt-6 flex items-center justify-center gap-3">
            @if($asset)
            <img src="{{ $asset->url }}" alt="{{ $content['author'] ?? '' }}" class="w-12 h-12  object-cover" loading="lazy">
            @endif
            <div class="text-left">
                @if(!empty($content['author']))
                <p class="font-bold text-text">{{ $content['author'] }}</p>
                @endif
                @if(!empty($content['role']))
                <p class="text-sm text-text-secondary">{{ $content['role'] }}</p>
                @endif
            </div>
        </div>
    </div>
    @elseif($style === 'minimal')
    {{-- Minimal testimonial --}}
    <div class="max-w-2xl mx-auto">
        <p class="text-base text-text-secondary italic leading-relaxed">"{{ $content['quote'] }}"</p>
        <div class="mt-3 flex items-center gap-2">
            @if($asset)
            <img src="{{ $asset->url }}" alt="{{ $content['author'] ?? '' }}" class="w-8 h-8  object-cover" loading="lazy">
            @endif
            <span class="text-sm font-semibold text-text">{{ $content['author'] ?? '' }}</span>
            @if(!empty($content['role']))
            <span class="text-sm text-text-secondary">· {{ $content['role'] }}</span>
            @endif
        </div>
    </div>
    @else
    {{-- Card style --}}
    <div class="bg-white border border-stone  p-6 shadow-sm max-w-lg mx-auto">
        @if($rating > 0)
        <div class="flex items-center gap-0.5 mb-3">
            @for($s = 1; $s <= 5; $s++)
            <svg class="w-4 h-4 {{ $s <= $rating ? 'text-amber-400' : 'text-stone' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            @endfor
        </div>
        @endif
        <p class="text-text leading-relaxed">"{{ $content['quote'] }}"</p>
        <div class="mt-4 pt-4 border-t border-stone flex items-center gap-3">
            @if($asset)
            <img src="{{ $asset->url }}" alt="{{ $content['author'] ?? '' }}" class="w-10 h-10  object-cover" loading="lazy">
            @endif
            <div>
                @if(!empty($content['author']))
                <p class="font-semibold text-text text-sm">{{ $content['author'] }}</p>
                @endif
                @if(!empty($content['role']))
                <p class="text-xs text-text-secondary">{{ $content['role'] }}</p>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
@endif
