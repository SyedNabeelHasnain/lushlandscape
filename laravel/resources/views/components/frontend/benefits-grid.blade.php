@props([
    'title'    => 'Why Choose Super WMS?',
    'subtitle' => 'We combine craftsmanship, accountability, and local expertise to deliver results that last.',
    'benefits' => [],
])
@php
    $defaultBenefits = [
        ['icon'=>'shield-check',  'title'=>'10-Year Workmanship Warranty',    'text'=>'We back every project with a full decade of coverage, a guarantee almost no competitor matches in Our Region.'],
        ['icon'=>'badge-check',   'title'=>'Licensed & Fully Insured',         'text'=>'WSIB coverage, $5M liability insurance, and all required municipal permits pulled and documented.'],
        ['icon'=>'map-pin',       'title'=>'Local Our Region Experts',            'text'=>'We know Our Region soil, frost lines, permit processes, and HOA requirements across every city we serve.'],
        ['icon'=>'star',          'title'=>'Top-Rated on Google & HomeStars',  'text'=>'Hundreds of 5-star reviews from homeowners across the Region. Our reputation is built one project at a time.'],
    ];
    $items = !empty($benefits) ? $benefits : $defaultBenefits;
@endphp

<section class="section-editorial bg-forest-gradient">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">

            {{-- Left: Image --}}
            <div class="reveal-left">
                <div class="aspect-4/5 bg-white/10 flex items-center justify-center overflow-hidden border border-white/15">
                    <i data-lucide="image" class="w-16 h-16 text-white/25"></i>
                </div>
            </div>

            {{-- Right: Content --}}
            <div class="reveal-right">
                <span class="text-eyebrow text-white/50 mb-5 block">Why Us</span>
                <h2 class="text-h2 font-heading font-bold text-white">{{ $title }}</h2>
                <p class="mt-5 text-white/60 text-body-lg leading-relaxed">{{ $subtitle }}</p>

                <div class="mt-12 space-y-8">
                    @foreach($items as $benefit)
                    <div class="flex gap-4">
                        <div class="shrink-0 mt-1">
                            <i data-lucide="circle-check" class="w-5 h-5 text-white/80"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-heading font-bold text-white leading-snug">{{ $benefit['title'] }}</h3>
                            <p class="text-sm text-white/55 leading-relaxed mt-1.5">{{ $benefit['text'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
