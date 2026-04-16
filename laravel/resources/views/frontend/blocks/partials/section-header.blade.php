{{-- Block: section_header --}}
@php
    $heading = $content['heading'] ?? '';
    $subtitle = $content['subtitle'] ?? '';
    $align = $content['align'] ?? 'center';
    $tag = $content['tag'] ?? '';
    $showLine = $content['show_line'] ?? true;
    $variant = $content['variant'] ?? 'editorial';
    $ctaText = $content['cta_text'] ?? '';
    $ctaUrl = $content['cta_url'] ?? '';

    $alignClass = $align === 'left' ? 'text-left' : 'text-center';
@endphp

@if($variant === 'title-only')
    <div class="{{ $alignClass }}">
        @if($tag)<span class="text-luxury-label text-text-secondary block mb-3">{{ $tag }}</span>@endif
        @if($heading)<h2 class="text-h2 font-heading font-bold text-ink">{{ $heading }}</h2>@endif
        @if($showLine)<div class="mt-6 w-12 h-px bg-forest/30 {{ $align === 'center' ? 'mx-auto' : '' }}"></div>@endif
    </div>

@elseif($variant === 'with-right-cta')
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div class="text-left max-w-2xl">
            @if($tag)<span class="text-luxury-label text-text-secondary block mb-3">{{ $tag }}</span>@endif
            @if($heading)<h2 class="text-h2 font-heading font-bold text-ink">{{ $heading }}</h2>@endif
            @if($subtitle)<p class="mt-4 text-body-lg text-text-secondary">{{ $subtitle }}</p>@endif
            @if($showLine)<div class="mt-6 w-12 h-px bg-forest/30"></div>@endif
        </div>
        @if($ctaText && $ctaUrl)
        <div class="shrink-0 pb-2">
            <a href="{{ $ctaUrl }}" class="inline-flex items-center gap-3 text-sm font-semibold uppercase tracking-[0.18em] text-forest hover:text-accent transition-colors duration-300">
                {{ $ctaText }} <span class="w-8 h-px bg-current/35"></span>
            </a>
        </div>
        @endif
    </div>

@elseif($variant === 'split')
    <div class="grid grid-cols-1 md:grid-cols-12 gap-8 md:gap-12 lg:gap-24 items-start">
        <div class="md:col-span-5 lg:col-span-4 text-left">
            @if($tag)<span class="text-luxury-label text-text-secondary block mb-3">{{ $tag }}</span>@endif
            @if($heading)<h2 class="text-h2 font-heading font-bold text-ink leading-tight">{{ $heading }}</h2>@endif
            @if($showLine)<div class="mt-6 w-12 h-px bg-forest/30"></div>@endif
        </div>
        @if($subtitle)
        <div class="md:col-span-7 lg:col-span-8">
            <p class="text-xl md:text-2xl text-text-secondary font-light leading-relaxed">{{ $subtitle }}</p>
            @if($ctaText && $ctaUrl)
            <div class="mt-8">
                <a href="{{ $ctaUrl }}" class="btn-luxury text-forest hover:text-accent">{{ $ctaText }}</a>
            </div>
            @endif
        </div>
        @endif
    </div>

@elseif($variant === 'full-editorial')
    <div class="{{ $alignClass }} max-w-4xl {{ $align === 'center' ? 'mx-auto' : '' }}">
        @if($tag)<span class="text-luxury-label text-forest block mb-4">{{ $tag }}</span>@endif
        @if($heading)<h2 class="text-display font-heading font-bold text-ink leading-tight">{{ $heading }}</h2>@endif
        @if($showLine)<div class="my-8 w-24 h-px bg-forest/40 {{ $align === 'center' ? 'mx-auto' : '' }}"></div>@endif
        @if($subtitle)<p class="text-xl lg:text-2xl text-text-secondary font-light leading-relaxed max-w-3xl {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $subtitle }}</p>@endif
        @if($ctaText && $ctaUrl)
        <div class="mt-10">
            <a href="{{ $ctaUrl }}" class="btn-luxury border border-forest/20 text-forest hover:bg-forest hover:text-white transition-all duration-300 px-8 py-4 rounded-full">{{ $ctaText }}</a>
        </div>
        @endif
    </div>

@elseif($variant === 'compact')
    <div class="{{ $alignClass }}">
        @if($tag)<span class="text-xs uppercase tracking-[0.2em] text-text-secondary block mb-2">{{ $tag }}</span>@endif
        @if($heading)<h2 class="text-xl font-bold text-ink">{{ $heading }}</h2>@endif
        @if($subtitle)<p class="mt-2 text-sm text-text-secondary max-w-lg {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $subtitle }}</p>@endif
    </div>

@else {{-- editorial / default --}}
    <div class="{{ $alignClass }} max-w-3xl {{ $align === 'center' ? 'mx-auto' : '' }}">
        @if($tag)<span class="text-luxury-label text-text-secondary block mb-3">{{ $tag }}</span>@endif
        @if($heading)<h2 class="text-h2 font-heading font-bold text-ink">{{ $heading }}</h2>@endif
        @if($subtitle)<p class="mt-4 text-body-lg text-text-secondary {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $subtitle }}</p>@endif
        @if($showLine)<div class="mt-6 w-12 h-px bg-forest/30 {{ $align === 'center' ? 'mx-auto' : '' }}"></div>@endif
    </div>
@endif