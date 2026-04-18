@php
    $bgPattern = $content['bg_pattern'] ?? 'https://www.transparenttextures.com/patterns/cubes.png';
@endphp
<section class="bg-airy-gradient hero-viewport w-full relative">
    @if($bgPattern)
    <div class="absolute inset-0 opacity-[0.03] mix-blend-multiply pointer-events-none" style="background-image: url('{{ $bgPattern }}');"></div>
    @endif
    
    <div class="flex-1 flex flex-col justify-center items-center w-full max-w-6xl mx-auto px-5 lg:px-12 text-center pt-24 lg:pt-28 pb-10 relative z-10">
        <div class="gs-hero w-full">
            @if(!empty($content['eyebrow']))
            <span class="inline-flex items-center px-3 py-1.5 border border-forest/20 text-forest uppercase tracking-[0.2em] text-[9px] lg:text-[10px] font-semibold mb-6 lg:mb-8 bg-white/30 backdrop-blur-sm rounded-sm">{{ $content['eyebrow'] }}</span>
            @endif
            
            <h1 class="fluid-title text-forest mb-6 text-balance word-wrap-safe">
                {{ $content['heading'] ?? 'Luxury Outdoor Living' }}<br>
                @if(!empty($content['heading_highlight']))
                <span class="italic text-forest-light">{{ $content['heading_highlight'] }}</span>
                @endif
            </h1>
            
            @if(!empty($content['subtitle']))
            <p class="text-ink/70 text-base lg:text-xl font-light leading-relaxed max-w-2xl mx-auto mb-10 px-4 lg:px-0">{{ $content['subtitle'] }}</p>
            @endif
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-6 lg:gap-8">
                @if(!empty($content['cta_primary_url']))
                <a href="{{ $content['cta_primary_url'] }}" class="btn-solid h-12 flex items-center px-8 text-xs tracking-[0.15em] uppercase font-semibold rounded-sm w-full sm:w-auto justify-center">{{ $content['cta_primary_text'] ?? 'Request Consultation' }}</a>
                @endif
                
                @if(!empty($content['cta_secondary_url']))
                <a href="{{ $content['cta_secondary_url'] }}" class="btn-outline flex items-center text-[10px] lg:text-[11px] font-bold tracking-[0.15em] uppercase pb-1 w-full sm:w-auto justify-center">{{ $content['cta_secondary_text'] ?? 'View Projects →' }}</a>
                @endif
            </div>
        </div>
    </div>

    <div class="w-full border-t border-black/10 bg-white/50 backdrop-blur-md py-6 lg:py-8 z-10 relative">
        <div class="max-w-7xl mx-auto px-4 lg:px-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-y-6 lg:gap-0 divide-x-0 lg:divide-x divide-black/10">
                <div class="gs-hero flex flex-col items-center justify-center px-2 text-center">
                    <span class="text-[8px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-ink/50 mb-1">{{ $content['badge_1_title'] ?? 'Protected' }}</span>
                    <span class="text-forest text-sm sm:text-base lg:text-lg font-serif leading-tight">{{ $content['badge_1_value'] ?? '10-Year Warranty' }}</span>
                </div>
                <div class="gs-hero flex flex-col items-center justify-center px-2 text-center border-l border-black/10 lg:border-l-0">
                    <span class="text-[8px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-ink/50 mb-1">{{ $content['badge_2_title'] ?? 'Insured' }}</span>
                    <span class="text-forest text-sm sm:text-base lg:text-lg font-serif leading-tight">{{ $content['badge_2_value'] ?? '$5M Liability' }}</span>
                </div>
                <div class="gs-hero flex flex-col items-center justify-center px-2 text-center lg:border-l border-black/10">
                    <span class="text-[8px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-ink/50 mb-1">{{ $content['badge_3_title'] ?? 'Certified' }}</span>
                    <span class="text-forest text-sm sm:text-base lg:text-lg font-serif leading-tight">{{ $content['badge_3_value'] ?? 'WSIB Compliant' }}</span>
                </div>
                <div class="gs-hero flex flex-col items-center justify-center px-2 text-center border-l border-black/10 lg:border-l-0">
                    <span class="text-[8px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-ink/50 mb-1">{{ $content['badge_4_title'] ?? 'Trusted' }}</span>
                    <span class="text-forest text-sm sm:text-base lg:text-lg font-serif leading-tight">{{ $content['badge_4_value'] ?? 'Architect Alliance' }}</span>
                </div>
            </div>
        </div>
    </div>
</section>