{{-- Block: authority_grid --}}
@php
    $eyebrow = $content['eyebrow'] ?? 'Our Standards';
    $heading = $content['heading'] ?? 'Built to Last';
    $introduction = $content['introduction'] ?? '';
    $items = $content['items'] ?? [];
    $cardSkin = $content['card_skin'] ?? 'premium-bordered';
@endphp

<div class="max-w-7xl mx-auto">
    <div class="text-center max-w-3xl mx-auto mb-16 animate-on-scroll" data-animation="fade-up">
        @if($eyebrow)
            <span class="text-luxury-label text-text-secondary block mb-3">{{ $eyebrow }}</span>
        @endif
        @if($heading)
            <h2 class="text-h2 font-heading font-bold text-ink">{{ $heading }}</h2>
        @endif
        @if($introduction)
            <p class="mt-4 text-body-lg text-text-secondary">{{ $introduction }}</p>
        @endif
        <div class="mt-6 w-12 h-px bg-forest/30 mx-auto"></div>
    </div>

    @if(!empty($items))
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 lg:gap-12">
            @foreach($items as $idx => $item)
                @php
                    $cardClass = match($cardSkin) {
                        'elevated' => 'bg-white rounded-[2rem] p-10 shadow-luxury hover:shadow-luxury-lg hover:-translate-y-2 transition-all duration-500',
                        'minimal' => 'group flex flex-col items-center text-center p-6',
                        default => 'bg-transparent border border-stone rounded-[1.5rem] p-8 lg:p-10 hover:border-forest/50 transition-colors duration-500 relative overflow-hidden group'
                    };
                    $iconClass = match($cardSkin) {
                        'elevated' => 'w-16 h-16 rounded-2xl bg-forest/5 text-forest flex items-center justify-center mb-8',
                        'minimal' => 'w-14 h-14 rounded-full bg-stone-light text-forest flex items-center justify-center mb-6 group-hover:bg-forest group-hover:text-white transition-colors duration-500',
                        default => 'w-12 h-12 text-forest mb-8'
                    };
                @endphp
                <div class="{{ $cardClass }} animate-on-scroll" data-animation="fade-up" data-delay="{{ $idx * 50 }}">
                    @if($cardSkin === 'premium-bordered')
                        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-forest/0 via-forest/40 to-forest/0 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    @endif
                    
                    <div class="{{ $iconClass }}">
                        <i data-lucide="{{ $item['icon'] ?? 'shield-check' }}" class="{{ $cardSkin === 'minimal' ? 'w-6 h-6' : 'w-8 h-8' }}"></i>
                    </div>
                    
                    <h3 class="text-xl font-bold text-ink mb-3 {{ $cardSkin === 'minimal' ? 'font-heading text-2xl' : '' }}">{{ $item['title'] ?? '' }}</h3>
                    <p class="text-text-secondary leading-relaxed {{ $cardSkin === 'minimal' ? 'text-sm' : '' }}">{{ $item['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>