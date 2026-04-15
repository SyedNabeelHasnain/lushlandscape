@php
    $cards = $content['cards'] ?? [];
    $cols = $content['columns'] ?? '3';
    $variant = $content['variant'] ?? 'editorial';
    $tone = $content['tone'] ?? 'light';
    $gridCls = match($cols) { '2' => 'grid-cols-1 sm:grid-cols-2', '4' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-4', default => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3' };
    $sectionToneClass = $tone === 'forest' ? 'bg-luxury-green-deep text-white rounded-[2rem] px-6 py-8 lg:px-10 lg:py-10' : '';
    $headingClass = $tone === 'forest' ? 'text-white' : 'text-ink';
    $subClass = $tone === 'forest' ? 'text-white/70' : 'text-text-secondary';
@endphp
@if(!empty($cards))
<section class="max-w-7xl mx-auto px-6 lg:px-12 py-12">
    <div class="{{ $sectionToneClass }}">
        @if(!empty($content['eyebrow']) || !empty($content['heading']) || !empty($content['subtitle']))
            <div class="mb-10 lg:mb-14">
                @if(!empty($content['eyebrow']))
                    <p class="text-[10px] font-semibold uppercase tracking-[0.22em] {{ $tone === 'forest' ? 'text-white/45' : 'text-text-secondary' }}">{{ $content['eyebrow'] }}</p>
                @endif
                @if(!empty($content['heading']))
                    <h2 class="mt-4 text-h2 font-heading font-bold tracking-tight {{ $headingClass }}">{{ $content['heading'] }}</h2>
                @endif
                @if(!empty($content['subtitle']))
                    <p class="mt-4 max-w-2xl text-body-lg {{ $subClass }}">{{ $content['subtitle'] }}</p>
                @endif
            </div>
        @endif

        <div class="grid {{ $gridCls }} gap-6 lg:gap-8">
            @foreach($cards as $card)
                @php
                    $cardAsset = !empty($card['media_id']) ? ($mediaLookup[$card['media_id']] ?? null) : null;
                    $cardClass = match ($variant) {
                        'minimal' => 'bg-transparent border-t border-current/10 pt-6',
                        'icon' => 'editorial-card bg-white',
                        default => 'editorial-card '.($tone === 'forest' ? 'bg-white text-ink' : 'bg-white'),
                    };
                @endphp
                <article class="{{ $cardClass }}">
                    @if(!empty($card['meta']))
                        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] {{ $tone === 'forest' && $variant === 'minimal' ? 'text-white/45' : 'text-text-secondary' }}">
                            {{ $card['meta'] }}
                        </p>
                    @endif

                    @if($cardAsset)
                        <div class="mt-4 overflow-hidden">
                            <x-frontend.media
                                :asset="$cardAsset"
                                :alt="$card['title'] ?? ''"
                                class="w-full aspect-[4/3] object-cover img-zoom"
                            />
                        </div>
                    @elseif(!empty($card['icon']))
                        <div class="mt-4 w-14 h-14 border {{ $variant === 'icon' ? 'border-stone bg-cream' : 'border-current/10 bg-current/5' }} flex items-center justify-center">
                            <i data-lucide="{{ $card['icon'] }}" class="w-6 h-6 text-forest"></i>
                        </div>
                    @endif

                    <div class="{{ $cardAsset || !empty($card['icon']) ? 'mt-6' : 'mt-3' }}">
                        @if(!empty($card['title']))
                            <h3 class="{{ $variant === 'minimal' ? 'text-2xl font-heading' : 'text-[1.65rem] font-heading' }} leading-tight text-current mb-3 line-clamp-2">
                                {{ $card['title'] }}
                            </h3>
                        @endif
                        @if(!empty($card['description']))
                            <p class="text-sm leading-relaxed {{ $tone === 'forest' && $variant === 'minimal' ? 'text-white/72' : 'text-text-secondary' }} line-clamp-3">
                                {{ $card['description'] }}
                            </p>
                        @endif
                        @if(!empty($card['link_url']))
                            <a href="{{ $card['link_url'] }}" class="mt-6 inline-flex items-center gap-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-forest hover:gap-3 transition-all">
                                {{ $card['link_text'] ?? 'Learn more' }}
                                <span class="w-4 h-px bg-forest/40"></span>
                            </a>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
