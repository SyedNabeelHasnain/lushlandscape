@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: process_steps --}}
@php
    $processHeading = !empty($section['settings']['heading']) ? $section['settings']['heading'] : 'Our Landscaping Process';
    $steps = $section['settings']['steps'] ?? [
        ['title' => 'Free Consultation',  'desc' => 'We meet on-site, assess your space, and discuss your vision and goals.'],
        ['title' => 'Detailed Proposal',  'desc' => 'You receive a written scope of work and comprehensive scope plan within 48 hours.'],
        ['title' => 'Expert Installation','desc' => 'Our certified crew handles every detail with precision and care.'],
        ['title' => '10-Year Warranty',   'desc' => 'All structural work is backed by our industry-leading workmanship warranty.'],
    ];
@endphp
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-text">{{ $processHeading }}</h2>
            <p class="mt-3 text-text-secondary text-lg">Simple, transparent, and stress-free from start to finish.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($steps as $i => $step)
            <div class="relative">
                @if(!$loop->last)
                <div class="hidden lg:block absolute top-8 left-full w-full h-px bg-forest/15 z-0" style="width:calc(100% - 4rem);left:calc(50% + 2rem)"></div>
                @endif
                <div class="text-center relative z-10">
                    <div class="w-16 h-16  bg-forest text-white text-2xl font-bold flex items-center justify-center mx-auto mb-5">
                        {{ $i + 1 }}
                    </div>
                    <h3 class="text-base font-bold text-text mb-2">{{ $step['title'] }}</h3>
                    <p class="text-sm text-text-secondary leading-relaxed">{{ $step['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
