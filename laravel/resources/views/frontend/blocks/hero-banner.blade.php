@php
    $asset   = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null;
    $height  = match($content['height'] ?? 'md') { 'sm' => 'py-16 md:py-20', 'lg' => 'py-28 md:py-40', 'xl' => 'min-h-screen flex items-center', default => 'py-20 md:py-28' };
    $align   = ($content['align'] ?? 'center') === 'center' ? 'text-center items-center' : 'text-left items-start';
    $overlayOpacity = is_numeric($content['overlay_opacity'] ?? null) ? (int) $content['overlay_opacity'] : 55;
    $overlayOpacity = max(0, min(100, $overlayOpacity));
    $overlayAlpha = $overlayOpacity / 100;
    $overlayPreset = $content['overlay'] ?? 'medium';
    $overlayPreset = in_array($overlayPreset, ['light', 'medium', 'dark', 'none'], true) ? $overlayPreset : 'medium';
    $overlayA = match ($overlayPreset) {
        'light' => min(1, $overlayAlpha * 0.75),
        'dark' => min(1, max($overlayAlpha, 0.7)),
        'none' => 0,
        default => $overlayAlpha,
    };
@endphp
@if(!empty($content['heading']))
<section class="relative overflow-hidden {{ $height }}">
    @if($asset)
    <x-frontend.media :asset="$asset" :alt="$content['heading'] ?? 'Hero banner'" class="absolute inset-0 w-full h-full object-cover" />
    <div class="absolute inset-0" style="background:linear-gradient(rgba(21, 56, 35, {{ min(1, $overlayA + 0.15) }}), rgba(21, 56, 35, {{ $overlayA }}));"></div>
    @else
    <div class="absolute inset-0 bg-luxury-green-deep"></div>
    @endif
    <div class="relative z-10 max-w-5xl mx-auto px-6 lg:px-12 {{ ($content['height'] ?? 'md') === 'xl' ? 'w-full' : '' }}">
        <div class="flex flex-col {{ $align }}">
            <h2 class="text-3xl md:text-5xl font-heading font-bold text-white leading-tight {{ ($content['align'] ?? 'center') === 'center' ? 'max-w-3xl' : '' }}">{{ $content['heading'] }}</h2>
            @if(!empty($content['subtitle']))
            <p class="mt-4 text-base md:text-lg text-white/80 leading-relaxed {{ ($content['align'] ?? 'center') === 'center' ? 'max-w-2xl' : 'max-w-xl' }}">{{ $content['subtitle'] }}</p>
            @endif
            @if(!empty($content['button_url']))
            <a href="{{ $content['button_url'] }}"
               class="mt-8 inline-flex items-center gap-2 btn-luxury btn-luxury-white">
                {{ $content['button_text'] ?? 'Get Started' }}
                <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
            @endif
        </div>
    </div>
</section>
@endif
