@php
    $asset = !empty($content['media_id'])
        ? ((($mediaLookup ?? [])[$content['media_id']] ?? null) ?: \App\Models\MediaAsset::find((int) $content['media_id']))
        : null;
    $mediaSide = $content['media_side'] ?? 'left';
    $tone = $content['tone'] ?? 'light';
    $featureLayout = $content['feature_layout'] ?? 'stacked';
    $ornamentStyle = $content['ornament_style'] ?? 'oval';
    $features = collect($content['features'] ?? [])->filter(fn ($item) => !empty($item['title']) || !empty($item['description']));

    $ratioClass = match ($content['media_ratio'] ?? '4:5') {
        '4:3' => 'aspect-[4/3]',
        '16:9' => 'aspect-video',
        '1:1' => 'aspect-square',
        default => 'aspect-[4/5]',
    };

    $isDarkTone = in_array($tone, ['forest', 'dark'], true);
    $headingClass = $isDarkTone ? 'text-white' : 'text-ink';
    $eyebrowClass = $isDarkTone ? 'text-white/74' : 'text-text-secondary';
    $bodyClass = $isDarkTone ? 'text-white/78' : 'text-black/80';
    $mediaOrderClass = $mediaSide === 'right' ? 'lg:order-2' : '';
    $contentOrderClass = $mediaSide === 'right' ? 'lg:order-1' : '';
    $featureCardClass = $isDarkTone
        ? 'border border-white/12 bg-white/6'
        : 'border border-stone-light bg-white';
    $ctaClass = $isDarkTone ? 'btn-luxury btn-luxury-white' : 'btn-luxury btn-luxury-primary';
@endphp

@if($asset || !empty($content['heading']) || $features->isNotEmpty())
    <div class="mx-auto max-w-7xl px-6 lg:px-12">
        <div class="grid grid-cols-1 items-center gap-14 lg:grid-cols-12 lg:gap-16">
            <div class="relative lg:col-span-6 {{ $mediaOrderClass }}">
                @if($asset)
                    @if($ornamentStyle === 'oval')
                        <div class="editorial-feature-orbit hidden lg:block"></div>
                    @endif

                    <div class="relative {{ $ornamentStyle === 'offset' ? 'editorial-feature-offset' : '' }}">
                        <x-frontend.media
                            :asset="$asset"
                            :alt="$content['heading'] ?? ''"
                            class="relative z-10 w-full {{ $ratioClass }} object-cover shadow-luxury"
                        />
                    </div>
                @endif
            </div>

            <div class="lg:col-span-6 {{ $contentOrderClass }}">
                @if(!empty($content['eyebrow']))
                    <p class="mb-6 text-sm font-bold uppercase tracking-[0.2em] {{ $eyebrowClass }}">{{ $content['eyebrow'] }}</p>
                @endif

                @if(!empty($content['heading']))
                    <h2 class="text-h2 font-heading font-bold leading-[0.95] text-balance {{ $headingClass }}">
                        {!! $content['heading'] !!}
                    </h2>
                @endif

                @if(!empty($content['description']))
                    <p class="mt-6 text-[1.02rem] leading-[1.7] {{ $bodyClass }}">
                        {!! $content['description'] !!}
                    </p>
                @endif

                @if($features->isNotEmpty())
                    <div class="mt-10 space-y-8">
                        @foreach($features as $feature)
                            <div @class([
                                'flex gap-5',
                                'rounded-[1.5rem] p-6 lg:p-7' => $featureLayout === 'cards',
                                $featureCardClass => $featureLayout === 'cards',
                            ])>
                                <div class="pt-1 text-[1.8rem] {{ $isDarkTone ? 'text-white' : 'text-ink' }}">
                                    <i data-lucide="{{ $feature['icon'] ?? 'check' }}" class="h-6 w-6"></i>
                                </div>
                                <div>
                                    @if(!empty($feature['title']))
                                        <h3 class="mb-3 text-[1.35rem] font-semibold uppercase leading-tight {{ $headingClass }}" style="font-family: var(--font-sans);">
                                            {{ $feature['title'] }}
                                        </h3>
                                    @endif
                                    @if(!empty($feature['description']))
                                        <p class="max-w-xl text-[1rem] leading-[1.7] {{ $bodyClass }}">
                                            {{ $feature['description'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                @if(!empty($content['cta_text']) && !empty($content['cta_url']))
                    <div class="mt-10">
                        <a href="{{ $content['cta_url'] }}" class="{{ $ctaClass }}">
                            {{ $content['cta_text'] }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif
