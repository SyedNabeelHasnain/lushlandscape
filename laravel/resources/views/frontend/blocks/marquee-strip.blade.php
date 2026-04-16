{{-- Block: marquee_strip --}}
@php
    $textItemsString = $content['text_items'] ?? 'Premium Landscape Design, Expert Installation, 10-Year Warranty, Interlocking & Stonework';
    $textItems = array_map('trim', explode(',', $textItemsString));
    $separator = $content['separator_style'] ?? 'star';
    $speed = $content['speed'] ?? 'normal';
    $direction = $content['direction'] ?? 'left';
    $tone = $content['tone'] ?? 'dark';

    $duration = match($speed) { 'slow' => '40s', 'fast' => '15s', default => '25s' };
    $animDirection = $direction === 'right' ? 'reverse' : 'normal';

    $toneClasses = match($tone) {
        'light' => 'bg-white text-ink border-stone',
        'forest' => 'bg-forest-50 text-forest border-forest/20',
        default => 'bg-ink text-white border-white/10' // dark
    };

    $separatorHtml = match($separator) {
        'dot' => '<div class="w-1.5 h-1.5 rounded-full bg-current opacity-40 mx-8"></div>',
        'line' => '<div class="w-8 h-px bg-current opacity-30 mx-8"></div>',
        'star' => '<i data-lucide="star" class="w-4 h-4 opacity-40 mx-8"></i>',
        default => '<div class="mx-8"></div>'
    };
@endphp

@if(!empty($textItems))
<div class="overflow-hidden py-4 lg:py-6 border-y {{ $toneClasses }}" aria-hidden="true">
    <div class="flex items-center animate-marquee whitespace-nowrap"
         style="animation-duration: {{ $duration }}; animation-direction: {{ $animDirection }};">
        
        {{-- Render 4 sets of the items to ensure seamless loop on wide screens --}}
        @for($i = 0; $i < 4; $i++)
            @foreach($textItems as $text)
                <span class="text-sm md:text-base font-semibold uppercase tracking-[0.2em]">{{ $text }}</span>
                {!! $separatorHtml !!}
            @endforeach
        @endfor
    </div>
</div>

<style>
@keyframes marquee { 
    from { transform: translateX(0); } 
    to { transform: translateX(-50%); } 
}
.animate-marquee { 
    animation: marquee linear infinite; 
    width: max-content;
}
</style>
@endif