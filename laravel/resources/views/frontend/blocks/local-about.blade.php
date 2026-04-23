@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: local_about — neighbourhoods, why local, permit info --}}
@if(isset($city) && $city->city_body && is_array($city->city_body))
@php $cityBody = $city->city_body; @endphp
@if(!empty($cityBody['local_intro_extended']) || !empty($cityBody['neighborhoods_served']) || !empty($cityBody['why_local_para']) || !empty($cityBody['permit_summary']))
@php
    $warrantyYears = \App\Models\Setting::get('warranty_years', '10');
    $totalProjects = \App\Models\Setting::get('total_projects_count', '500+');
    $foundingYear  = \App\Models\Setting::get('founding_year', '2018');
    $yearsExp      = date('Y') - (int) $foundingYear;
@endphp
<section class="section-editorial bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">

        {{-- Stats row --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-20 reveal"
             x-data="{ visible: false }" x-intersect.once="visible = true">
            @foreach([
                ['target' => $yearsExp, 'suffix' => '+', 'label' => 'Years Experience'],
                ['target' => (int) filter_var($totalProjects, FILTER_SANITIZE_NUMBER_INT) ?: 500, 'suffix' => '+', 'label' => 'Projects Completed'],
                ['target' => (int) $warrantyYears, 'suffix' => '-Year', 'label' => 'Workmanship Warranty'],
                ['target' => (int) \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')->count(), 'suffix' => '+', 'label' => 'Cities Served'],
            ] as $stat)
            <div class="text-center p-6 border border-stone bg-cream/50"
                 x-data="{ count: 0, target: {{ $stat['target'] }} }">
                <template x-if="visible">
                    <span x-init="
                        let step = Math.max(1, Math.ceil(target / 40));
                        let interval = setInterval(() => {
                            count = Math.min(count + step, target);
                            if (count >= target) clearInterval(interval);
                        }, 30);
                    "></span>
                </template>
                <p class="text-4xl lg:text-5xl font-heading font-bold text-forest leading-none">
                    <span x-text="count">0</span><span class="text-2xl">{{ $stat['suffix'] }}</span>
                </p>
                <p class="text-xs text-text-secondary uppercase tracking-[0.15em] font-semibold mt-3">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>

        {{-- Content grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
            <div class="reveal-left">
                @if(!empty($cityBody['local_intro_extended']))
                <span class="text-eyebrow text-forest mb-5 block">About {{ $city->name }}</span>
                <h2 class="text-h2 font-heading font-bold text-ink mb-6">Landscaping in {{ $city->name }}</h2>
                <div class="text-text-secondary leading-relaxed text-body">{{ $cityBody['local_intro_extended'] }}</div>
                @endif

                @if(!empty($cityBody['neighborhoods_served']))
                <div class="mt-10">
                    <h3 class="text-sm font-semibold text-ink uppercase tracking-[0.15em] mb-4">Neighbourhoods We Serve</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($cityBody['neighborhoods_served'] as $nb)
                        <span class="text-sm bg-forest/6 text-forest border border-forest/10 px-4 py-2 font-medium hover:bg-forest hover:text-white transition-all duration-300 cursor-default">{{ $nb }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div class="reveal-right space-y-6">
                @if(!empty($cityBody['why_local_para']))
                <div class="bg-cream border border-stone p-8 group hover:border-forest transition-all duration-500 hover:shadow-luxury">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-forest/10 flex items-center justify-center shrink-0 group-hover:bg-forest transition-all duration-300">
                            <i data-lucide="map-pin" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-heading font-bold text-ink mb-2">Why Local Expertise Matters</h3>
                            <p class="text-sm text-text-secondary leading-relaxed">{{ $cityBody['why_local_para'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if(!empty($cityBody['permit_summary']))
                <div class="bg-cream border border-stone p-8 group hover:border-forest transition-all duration-500 hover:shadow-luxury">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-forest/10 flex items-center justify-center shrink-0 group-hover:bg-forest transition-all duration-300">
                            <i data-lucide="file-check" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-300"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-heading font-bold text-ink mb-2">Permits in {{ $city->name }}</h3>
                            <p class="text-sm text-text-secondary leading-relaxed">{{ $cityBody['permit_summary'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Trust badges --}}
                <div class="bg-forest p-8 text-white">
                    <h3 class="text-lg font-heading font-bold mb-4">Our {{ $city->name }} Commitment</h3>
                    <ul class="space-y-3">
                        @foreach(['WSIB compliant & $5M insured', $warrantyYears . '-year workmanship warranty', 'All permits pulled & documented', 'Free on-site consultations'] as $item)
                        <li class="flex items-center gap-3 text-sm text-white/80">
                            <i data-lucide="check" class="w-4 h-4 text-white/60 shrink-0"></i>{{ $item }}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
@endif
