@props([
    'heading' => 'Your Trusted Partner for Outdoor Living in Our Region',
    'body'    => [],
])
@php
    $defaults = [
        'At Super WMS Service, we believe your outdoor space should be an extension of the life you love. Whether you are dreaming of a welcoming interlocking driveway, a backyard retreat with natural stone, or a complete landscape transformation, our team brings the craftsmanship and local expertise to make it happen.',
        'Serving homeowners across the Greater Toronto and Hamilton Area since 2018, we combine premium materials with meticulous installation practices to deliver results that look stunning on day one and hold up for decades. Every project begins with an on-site consultation where we listen to your goals, assess your property, and present a clear scope plan with thoughtful material direction.',
        'From concept through completion, you work directly with our experienced project leads. No subcontractor surprises, no hidden fees, and a workmanship warranty that gives you lasting peace of mind.',
    ];
    $paragraphs = !empty($body) ? $body : $defaults;
@endphp

<section class="bg-forest-gradient section-editorial">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-20 items-start">

            {{-- Left column: Editorial content --}}
            <div class="lg:col-span-7 reveal-left">
                <p class="text-eyebrow text-white/50 mb-6">About Super WMS</p>
                <h2 class="text-h2 font-heading font-bold text-white">{{ $heading }}</h2>

                @if(!empty($paragraphs[0]))
                <p class="mt-6 text-body-lg text-white/70 leading-relaxed">{{ $paragraphs[0] }}</p>
                @endif

                <div class="w-16 h-[2px] bg-white/25 my-8" aria-hidden="true"></div>

                @if(!empty($paragraphs[1]))
                <p class="text-body text-white/60 leading-relaxed">{{ $paragraphs[1] }}</p>
                @endif

                @if(!empty($paragraphs[2]))
                <p class="mt-5 text-body text-white/60 leading-relaxed">{{ $paragraphs[2] }}</p>
                @endif

                <div class="mt-10">
                    <a href="{{ url('/about') }}" class="btn-luxury btn-luxury-white">
                        <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        Learn More About Us
                    </a>
                </div>
            </div>

            {{-- Right column: Interactive feature cards --}}
            <div class="lg:col-span-5 space-y-6 reveal-right">

                {{-- Card 1: Since 2018 --}}
                <div x-data="{ visible: false, current: 2000 }"
                     x-intersect.once="visible = true; let iv = setInterval(() => { current += 1; if (current >= 2018) { current = 2018; clearInterval(iv); } }, 55)"
                     class="bg-white border border-white/20 p-7 border-l-[3px] border-l-accent transition-all duration-500 hover:-translate-y-1 hover:shadow-luxury group">
                    <div class="flex items-start gap-5">
                        <div class="shrink-0 w-14 h-14 bg-cream border border-stone flex items-center justify-center group-hover:bg-accent/10 transition-colors duration-300">
                            <i data-lucide="calendar" class="w-6 h-6 text-accent"></i>
                        </div>
                        <div>
                            <div class="flex items-baseline gap-2 mb-1">
                                <span class="text-3xl font-heading font-bold text-ink tabular-nums"
                                      x-text="visible ? current : '2000'"
                                      aria-label="Since 2018">2000</span>
                                <span class="text-eyebrow text-accent">Since</span>
                            </div>
                            <p class="text-sm text-text-secondary leading-relaxed">Serving Our Region homeowners</p>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Premium Materials --}}
                <div x-data="{ visible: false, barWidth: 0 }"
                     x-intersect.once="visible = true; setTimeout(() => barWidth = 98, 150)"
                     class="bg-white border border-white/20 p-7 transition-all duration-500 hover:-translate-y-1 hover:shadow-luxury group">
                    <div class="flex items-start gap-5 mb-5">
                        <div class="shrink-0 w-14 h-14 bg-cream border border-stone flex items-center justify-center group-hover:bg-forest/5 transition-colors duration-300">
                            <i data-lucide="gem" class="w-6 h-6 text-forest"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-heading font-bold text-ink leading-snug">Premium Materials</h3>
                            <p class="text-sm text-text-secondary leading-relaxed mt-1">We use only top-tier materials from trusted manufacturers</p>
                        </div>
                    </div>
                    {{-- Progress bar --}}
                    <div class="px-1">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-text-secondary">Material Quality</span>
                            <span class="text-xs font-bold text-forest tabular-nums" x-text="barWidth + '%'">0%</span>
                        </div>
                        <div class="w-full h-1.5 bg-stone/60 overflow-hidden" role="progressbar" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100" aria-label="Material quality score">
                            <div class="h-full bg-forest transition-all duration-1000 ease-out"
                                 x-bind:style="'width:' + barWidth + '%'"></div>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Direct Communication --}}
                <div class="bg-white border border-white/20 p-7 transition-all duration-500 hover:-translate-y-1 hover:shadow-luxury group">
                    <div class="flex items-start gap-5 mb-5">
                        <div class="shrink-0 w-14 h-14 bg-cream border border-stone flex items-center justify-center group-hover:bg-forest/5 transition-colors duration-300">
                            <i data-lucide="users" class="w-6 h-6 text-forest"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-heading font-bold text-ink leading-snug">Direct Communication</h3>
                            <p class="text-sm text-text-secondary leading-relaxed mt-1">Work directly with our experienced project leads</p>
                        </div>
                    </div>
                    {{-- Avatar circles --}}
                    <div class="flex items-center gap-3 px-1">
                        <div class="flex -space-x-3" aria-hidden="true">
                            <div class="w-10 h-10 rounded-full bg-forest text-white text-xs font-bold flex items-center justify-center border-2 border-white">JK</div>
                            <div class="w-10 h-10 rounded-full bg-accent text-white text-xs font-bold flex items-center justify-center border-2 border-white">RM</div>
                            <div class="w-10 h-10 rounded-full bg-forest-light text-white text-xs font-bold flex items-center justify-center border-2 border-white">AS</div>
                        </div>
                        <span class="text-xs font-medium text-text-secondary">+8 team members</span>
                    </div>
                </div>

                {{-- Trust badge strip --}}
                <div class="flex flex-wrap gap-3 pt-2">
                    @foreach([
                        ['icon' => 'shield-check',  'label' => 'WSIB Compliant'],
                        ['icon' => 'file-check',    'label' => 'All Permits Handled'],
                        ['icon' => 'award',          'label' => '10-Year Warranty'],
                    ] as $badge)
                    <div class="inline-flex items-center gap-2 bg-white/15 border border-white/20 px-4 py-2.5">
                        <i data-lucide="{{ $badge['icon'] }}" class="w-4 h-4 text-white/80 shrink-0"></i>
                        <span class="text-xs font-semibold uppercase tracking-[0.12em] text-white whitespace-nowrap">{{ $badge['label'] }}</span>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</section>
