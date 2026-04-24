@props([
    'title'        => 'Exceptional Landscapes for Distinctive Properties',
    'subtitle'     => 'Expert landscape design and premium construction tailored to elevate your estate.',
    'ctaPrimary'   => ['text' => 'Book a Consultation', 'url' => '/contact'],
    'ctaSecondary' => ['text' => 'Explore Portfolio', 'url' => '/portfolio'],
    'mediaAsset'   => null,
    'eyebrow'      => null,
    'badges'       => [],
    'dark'         => true,
    'videoUrl'     => null,
    'images'       => [],
    'overlayOpacity' => 50,
    'overlayPreset' => 'gradient',
    'align' => 'left',
    'height' => 'standard',
])
@php
    $phone        = \App\Models\Setting::get('phone', '');
    $phoneClean   = preg_replace('/[^+\d]/', '', $phone);
    $googleRating = \App\Models\Setting::get('google_rating', '');
    $reviewCount  = \App\Models\Setting::get('google_review_count', '');
    $backgroundVideo = $videoUrl ? app(\App\Services\VideoEmbedService::class)->resolve($videoUrl, [
        'autoplay' => true,
        'muted' => true,
        'loop' => true,
        'background' => true,
    ]) : null;

    $hasVideo  = !empty($backgroundVideo);
    $allImages = array_values(array_filter(array_merge($mediaAsset ? [$mediaAsset] : [], (array) $images)));
    $isSlider  = !$hasVideo && count($allImages) > 1;
    $isSingle  = !$hasVideo && count($allImages) === 1;
    $singleImg = $isSingle ? $allImages[0] : null;
    $hasMedia  = $hasVideo || $isSlider || $isSingle;

    $overlayOpacity = is_numeric($overlayOpacity) ? max(0, min(100, (int) $overlayOpacity)) : 50;
    $overlayAlpha = $overlayOpacity / 100;
    $overlayPreset = in_array($overlayPreset, ['gradient', 'solid', 'none'], true) ? $overlayPreset : 'gradient';
    $align = $align === 'left' ? 'left' : 'center';
    $height = in_array($height, ['viewport', 'tall', 'standard'], true) ? $height : 'viewport';
    $heightClass = match ($height) {
        'standard' => 'min-h-[72vh] lg:min-h-[78vh]',
        'tall' => 'min-h-[88vh] lg:min-h-[92vh]',
        default => 'min-h-screen',
    };
    $textAlignClass = $align === 'left' ? 'text-left' : 'text-center';
    $contentAlignClass = $align === 'left' ? 'items-start justify-start' : 'items-center justify-center';
    $ctaAlignClass = $align === 'left' ? 'justify-start' : 'justify-center';
    $copyWidthClass = $align === 'left' ? 'max-w-3xl' : 'max-w-4xl';
@endphp

<section class="relative overflow-hidden {{ $heightClass }} flex {{ $contentAlignClass }} bg-forest" data-hero>

    <div class="absolute inset-0 z-[1] pointer-events-none" aria-hidden="true"
        @if($overlayPreset === 'none')
            style="background:transparent;"
        @elseif($overlayPreset === 'solid')
            style="background:rgba(21, 56, 35, {{ $overlayAlpha }});"
        @else
            style="background:linear-gradient(to bottom, rgba(21, 56, 35, {{ min(1, $overlayAlpha + 0.15) }}), rgba(21, 56, 35, {{ $overlayAlpha }}), rgba(21, 56, 35, {{ min(1, $overlayAlpha + 0.15) }}));"
        @endif
    ></div>

    {{-- ── Video background ──────────────────────────────────── --}}
    @if($hasVideo)
    <div class="absolute inset-0 z-0" aria-hidden="true">
        @if(($backgroundVideo['type'] ?? null) === 'video')
            <video class="w-full h-full object-cover parallax-hero" autoplay muted loop playsinline data-hero-video>
                <source src="{{ $backgroundVideo['src'] }}" type="{{ $backgroundVideo['mime'] ?? 'video/mp4' }}">
            </video>
        @elseif(($backgroundVideo['type'] ?? null) === 'iframe')
            <div class="absolute inset-0 overflow-hidden">
                <iframe
                    src="{{ $backgroundVideo['src'] }}"
                    class="pointer-events-none absolute left-1/2 top-1/2 h-[130%] w-[130%] min-h-full min-w-full -translate-x-1/2 -translate-y-1/2 scale-[1.1]"
                    frameborder="0"
                    allow="autoplay; fullscreen; picture-in-picture"
                    allowfullscreen
                    title="Hero background video"
                ></iframe>
            </div>
        @endif
    </div>

    {{-- ── Image slider background ────────────────────────────── --}}
    @elseif($isSlider)
    <div class="absolute inset-0 z-0 hero-swiper overflow-hidden bg-forest">
        <div class="swiper-wrapper h-full">
            @foreach($allImages as $img)
            @php
                $caption = $img->default_caption ?? $img->default_alt_text ?? '';
            @endphp
            <div class="swiper-slide h-full relative" data-hero-caption="{{ $caption }}">
                <x-frontend.media
                    :asset="$img"
                    class="w-full h-full object-cover"
                    fetchpriority="{{ $loop->first ? 'high' : 'auto' }}"
                    loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                />
                
                @if($caption !== '')
                <div class="absolute bottom-0 left-0 right-0 bg-black/40 backdrop-blur-md px-6 py-4 text-center z-20 md:hidden">
                    <p class="text-white/80 text-[10px] uppercase tracking-widest font-bold">
                        {{ $caption }}
                    </p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
        
        <div class="hero-pagination absolute bottom-12 left-0 right-0 flex justify-center z-30"></div>
        
        <button type="button" class="hero-prev absolute left-6 top-1/2 -translate-y-1/2 z-30 w-12 h-12 bg-white/5 hover:bg-white/12 border border-white/10 flex items-center justify-center transition-all duration-400 group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Previous slide">
            <i data-lucide="chevron-left" class="w-5 h-5 text-white/70 group-hover:text-white transition-colors" aria-hidden="true"></i>
        </button>
        <button type="button" class="hero-next absolute right-6 top-1/2 -translate-y-1/2 z-30 w-12 h-12 bg-white/5 hover:bg-white/12 border border-white/10 flex items-center justify-center transition-all duration-400 group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Next slide">
            <i data-lucide="chevron-right" class="w-5 h-5 text-white/70 group-hover:text-white transition-colors" aria-hidden="true"></i>
        </button>

        {{-- Desktop Caption (Integrated into pagination area or separate) --}}
        <div class="hidden md:block absolute bottom-6 right-12 z-30 text-right opacity-40 hover:opacity-100 transition-opacity duration-500">
            <p data-hero-caption-display class="text-white text-[10px] uppercase tracking-[0.2em] font-bold max-w-xs ml-auto">
                {{ $allImages[0]->default_caption ?? $allImages[0]->default_alt_text ?? '' }}
            </p>
        </div>
    </div>

    {{-- ── Single image background ────────────────────────────── --}}
    @elseif($isSingle)
    <div class="absolute inset-0 z-0" aria-hidden="true">
        <x-frontend.media
            :asset="$singleImg"
            class="w-full h-full object-cover parallax-hero"
            fetchpriority="high"
            loading="eager"
        />
    </div>

    {{-- ── No media fallback ─────────────────────────────────── --}}
    @else
    <div class="absolute inset-0 z-0 bg-forest flex items-center justify-center overflow-hidden" aria-hidden="true">
        <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
    </div>
    @endif

    {{-- ── Text content (centered) ────────────────────────────── --}}
    <div class="relative w-full max-w-6xl mx-auto px-6 lg:px-12 py-16 lg:py-24 {{ $textAlignClass }} z-10">
        <div class="{{ $copyWidthClass }} {{ $align === 'left' ? '' : 'mx-auto' }}">
            @if($eyebrow)
            <div class="reveal mb-8">
                <span class="eyebrow-box">{{ $eyebrow }}</span>
            </div>
            @endif

            <h1 class="reveal-hero font-heading text-white tracking-tight leading-[1.02] text-4xl sm:text-5xl lg:text-6xl xl:text-7xl">
                {!! $title !!}
            </h1>

            <p class="reveal mt-7 text-white/90 text-base sm:text-lg lg:text-xl font-light leading-relaxed {{ $align === 'left' ? 'max-w-2xl' : 'max-w-3xl mx-auto' }}">
                {{ $subtitle }}
            </p>

            <div class="reveal mt-10 flex flex-col sm:flex-row flex-wrap items-center {{ $ctaAlignClass }} gap-5">
                <a href="{{ $ctaPrimary['url'] }}" class="btn-luxury btn-luxury-white text-sm px-10 py-4">
                    {{ $ctaPrimary['text'] }}
                </a>
                
                @if(!empty($ctaSecondary['text']) && !empty($ctaSecondary['url']))
                <a href="{{ $ctaSecondary['url'] }}" class="btn-luxury btn-luxury-ghost text-sm px-10 py-4">
                    {{ $ctaSecondary['text'] }}
                </a>
                @endif

                @if($phone)
                <a href="tel:{{ $phoneClean }}" class="btn-luxury btn-luxury-ghost text-sm px-10 py-4 max-sm:w-full max-sm:justify-center">
                    <i data-lucide="phone" class="w-4 h-4" aria-hidden="true"></i>Call {{ $phone }}
                </a>
                @endif
            </div>
        </div>

        {{-- ── Trust bar (integrated into hero) ───────────────── --}}
        <div class="hidden lg:grid grid-cols-3 gap-px mt-24 max-w-4xl {{ $align === 'left' ? '' : 'mx-auto' }} border border-white/[0.18] bg-white/[0.14] reveal">
            <div class="bg-white/[0.04] px-8 py-6 text-left">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/80">Approach</p>
                <p class="text-white text-2xl lg:text-[2rem] font-heading leading-none mt-2">Design & Build</p>
            </div>
            <div class="bg-white/[0.04] px-8 py-6 text-left">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/80">Expertise</p>
                <p class="text-white text-2xl lg:text-[2rem] font-heading leading-none mt-2">Master Craftsmanship</p>
            </div>
            <div class="bg-white/[0.04] px-8 py-6 text-left">
                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-white/80">Assurance</p>
                <p class="text-white text-2xl lg:text-[2rem] font-heading leading-none mt-2">Uncompromising Quality</p>
            </div>
        </div>
    </div>
</section>
