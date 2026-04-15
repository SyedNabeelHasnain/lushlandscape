{{-- Section: trust_badges --}}
@php
    $badges = [
        ['icon' => 'shield-check',    'label' => '10-Year Warranty',         'sub' => 'All structural work'],
        ['icon' => 'award',           'label' => 'Licensed & Insured',        'sub' => 'Fully certified crew'],
        ['icon' => 'clock',           'label' => '24–48 Hr Response',         'sub' => 'Fast, reliable service'],
        ['icon' => 'thumbs-up',       'label' => 'No Hidden Costs',           'sub' => 'Fixed-price quotes'],
        ['icon' => 'leaf',            'label' => 'Eco-Friendly Practices',    'sub' => 'Sustainable materials'],
        ['icon' => 'star',            'label' => '500+ Projects Delivered',   'sub' => 'Across Ontario'],
    ];
@endphp
<section class="py-16 bg-cream border-y border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6 reveal-stagger">
            @foreach($badges as $i => $badge)
            <div class="flex flex-col items-center text-center gap-4 p-6 bg-white border border-stone hover:border-forest/20 hover:shadow-luxury transition-all duration-500">
                <div class="w-14 h-14 bg-forest/6 flex items-center justify-center">
                    <i data-lucide="{{ $badge['icon'] }}" class="w-6 h-6 text-forest"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-ink tracking-wide leading-snug">{{ $badge['label'] }}</p>
                    <p class="text-[11px] text-text-secondary mt-1">{{ $badge['sub'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
