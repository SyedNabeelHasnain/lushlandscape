{{-- Block: parallax_media_band --}}
@php
    $heading = $content['heading'] ?? '';
    $subheadline = $content['subheadline'] ?? '';
    $mediaId = $content['media_id'] ?? null;
    $videoUrl = $content['video_url'] ?? '';
    $intensity = $content['parallax_intensity'] ?? 'medium';
    $overlayPreset = $content['overlay_preset'] ?? 'dark';

    $asset = $mediaId ? ($mediaLookup[$mediaId] ?? null) : null;
    $mediaUrl = $asset ? $asset->url : null;
    
    $overlayClass = match($overlayPreset) {
        'light' => 'bg-white/40',
        'forest' => 'bg-forest/60',
        'none' => 'bg-transparent',
        default => 'bg-ink/50' // dark
    };

    $textClass = match($overlayPreset) {
        'light' => 'text-ink',
        'none' => 'text-white drop-shadow-md',
        default => 'text-white' // dark and forest
    };

    $parallaxScale = match($intensity) {
        'strong' => 'scale-125',
        'medium' => 'scale-110',
        'subtle' => 'scale-105',
        default => 'scale-100'
    };
@endphp

<div class="relative w-full min-h-[50vh] lg:min-h-[70vh] flex items-center justify-center overflow-hidden group">
    {{-- Media Background --}}
    @if($videoUrl)
        <video autoplay loop muted playsinline class="absolute inset-0 w-full h-full object-cover z-0 {{ $intensity !== 'none' ? 'animate-on-scroll parallax-video ' . $parallaxScale : '' }}">
            <source src="{{ $videoUrl }}" type="video/mp4">
        </video>
    @elseif($mediaUrl)
        <img src="{{ $mediaUrl }}" alt="{{ $heading }}" class="absolute inset-0 w-full h-full object-cover z-0 {{ $intensity !== 'none' ? 'animate-on-scroll parallax-image ' . $parallaxScale : '' }}" loading="lazy">
    @else
        <div class="absolute inset-0 w-full h-full bg-forest z-0"></div>
    @endif

    {{-- Overlay --}}
    <div class="absolute inset-0 z-10 {{ $overlayClass }} transition-colors duration-500"></div>

    {{-- Content --}}
    @if($heading || $subheadline)
        <div class="relative z-20 text-center max-w-4xl px-6 md:px-12 py-20 animate-on-scroll" data-animation="fade-up">
            @if($heading)
                <h2 class="text-4xl md:text-5xl lg:text-7xl font-heading font-bold mb-6 {{ $textClass }} leading-tight">
                    {{ $heading }}
                </h2>
            @endif
            @if($subheadline)
                <p class="text-lg md:text-xl lg:text-2xl font-light opacity-90 max-w-3xl mx-auto {{ $textClass }} leading-relaxed">
                    {{ $subheadline }}
                </p>
            @endif
        </div>
    @endif
</div>