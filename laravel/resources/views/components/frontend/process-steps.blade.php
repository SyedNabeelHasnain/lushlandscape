@props([
    'heading'  => 'How It Works',
    'subtitle' => 'From first call to final walkthrough, we make your project simple and stress-free.',
    'steps'    => [],
])
@php
    $defaultSteps = [
        ['number'=>'01','icon'=>'phone','title'=>'Consultation','text'=>'Call us or submit a project inquiry. We confirm fit, goals, and timing before we schedule an on-site visit.'],
        ['number'=>'02','icon'=>'pencil-ruler','title'=>'Scope & Proposal','text'=>'We visit your property, assess constraints, and deliver a clear scope plan with material direction and next steps.'],
        ['number'=>'03','icon'=>'hard-hat','title'=>'Professional Installation','text'=>'Our certified crew handles everything: materials, logistics, and clean build-out to your exact specs.'],
        ['number'=>'04','icon'=>'shield-check','title'=>'Final Walkthrough','text'=>'We walk you through every detail and ensure 100% satisfaction before signing off. 10-year warranty included.'],
    ];
    $items = !empty($steps) ? $steps : $defaultSteps;
@endphp

<section class="section-editorial bg-forest-gradient">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-24 mb-20">
            <div class="lg:col-span-7 reveal">
                <span class="text-eyebrow text-white/50 mb-5 block">Our Process</span>
                <h2 class="text-h2 font-heading font-bold text-white">{{ $heading }}</h2>
            </div>
            <div class="lg:col-span-5 flex items-end reveal">
                <p class="text-white/60 text-body-lg leading-relaxed">{{ $subtitle }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-16 lg:gap-10 reveal-stagger">
            @foreach($items as $step)
            <div class="relative">
                <div class="text-7xl font-heading font-bold text-white/8 leading-none mb-6">{{ $step['number'] ?? sprintf('%02d', $loop->iteration) }}</div>
                <div class="w-12 h-12 border border-white/30 flex items-center justify-center mb-6">
                    <i data-lucide="{{ $step['icon'] ?? 'check' }}" class="w-5 h-5 text-white"></i>
                </div>
                <h3 class="text-lg font-heading font-bold text-white mb-4 leading-snug">{{ $step['title'] }}</h3>
                <p class="text-sm text-white/55 leading-relaxed">{{ $step['text'] ?? $step['desc'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
