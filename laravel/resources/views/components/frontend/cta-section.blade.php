@props([
    'title'      => 'Ready to Transform Your Outdoor Space?',
    'subtitle'   => 'Book your on-site consultation and receive a clear scope plan with thoughtful material direction.',
    'buttonText' => 'Book a Consultation',
    'buttonUrl'  => '/contact',
    'variant'    => 'dark',
])
@php
    $phone      = \App\Models\Setting::get('phone', '');
    $phoneClean = preg_replace('/[^+\d]/', '', $phone);
    $urgency    = \App\Models\Setting::get('urgency_message', '');
@endphp

@if($variant === 'light')
<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 lg:gap-24 items-center">
            <div class="lg:col-span-7 reveal">
                <span class="text-eyebrow text-forest mb-5 block">Get Started</span>
                <h2 class="text-h2 font-heading font-bold text-ink">{{ $title }}</h2>
                <p class="mt-6 text-text-secondary text-body-lg max-w-lg">{{ $subtitle }}</p>
                @if($urgency)<p class="mt-4 text-sm text-accent font-medium flex items-center gap-2"><i data-lucide="clock" class="w-4 h-4"></i>{{ $urgency }}</p>@endif
            </div>
            <div class="lg:col-span-5 flex flex-col gap-4 reveal">
                <a href="{{ $buttonUrl }}" class="btn-luxury btn-luxury-primary text-sm py-5">
                    <i data-lucide="clipboard-list" class="w-5 h-5"></i>{{ $buttonText }}
                </a>
                @if($phone)
                <a href="tel:{{ $phoneClean }}" class="btn-luxury text-sm py-5 bg-transparent color-ink border border-ink hover:bg-ink hover:text-white transition">
                    <i data-lucide="phone" class="w-5 h-5"></i>{{ $phone }}
                </a>
                @endif
            </div>
        </div>
    </div>
</section>
@else
<section class="relative overflow-hidden py-28 lg:py-40">
    <div class="absolute inset-0 bg-luxury-green-deep"></div>
    <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center relative reveal">
        <span class="text-eyebrow text-white/40 mb-6 block">Get Started</span>
        <h2 class="text-h2 font-heading font-bold text-white">{{ $title }}</h2>
        <p class="mt-6 text-white/50 text-body-lg max-w-2xl mx-auto">{{ $subtitle }}</p>
        @if($urgency)<p class="mt-5 inline-flex items-center gap-2 text-white/60 text-sm font-medium"><i data-lucide="clock" class="w-4 h-4"></i>{{ $urgency }}</p>@endif
        <div class="mt-14 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ $buttonUrl }}" class="btn-luxury btn-luxury-white text-sm px-12 py-5">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>{{ $buttonText }}
            </a>
            @if($phone)
            <a href="tel:{{ $phoneClean }}" class="btn-luxury btn-luxury-ghost text-sm px-10 py-5">
                <i data-lucide="phone" class="w-5 h-5"></i>{{ $phone }}
            </a>
            @endif
        </div>
        <p class="mt-12 text-white/25 text-xs tracking-[0.15em] uppercase">On-site consultation &middot; Clear scope plan &middot; Material direction</p>
    </div>
</section>
@endif
