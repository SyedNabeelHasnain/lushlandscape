@php
    $asset     = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null;
    $imgSide   = $content['image_side'] ?? 'left';
    $variant   = $content['variant'] ?? ($content['style'] ?? 'editorial');
    $ratioClass = match ($content['media_ratio'] ?? '4:3') {
        '3:4' => 'aspect-[3/4]',
        '16:9' => 'aspect-video',
        '1:1' => 'aspect-square',
        default => 'aspect-[4/3]',
    };
    $orderCls  = $imgSide === 'right' ? 'md:order-2' : '';
    $textOrder = $imgSide === 'right' ? 'md:order-1' : '';
@endphp
@if($asset || !empty($content['heading']) || !empty($content['text']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16 reveal">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16 items-center {{ $variant === 'panel' ? 'rounded-[2rem] border border-stone bg-white p-6 md:p-10 shadow-luxury' : '' }}">
        @if($asset)
        <div class="{{ $orderCls }}">
            <div class="{{ $variant === 'overlap' ? 'relative' : '' }}">
                <x-frontend.media
                    :asset="$asset"
                    :alt="$content['heading'] ?? ''"
                    class="w-full {{ $ratioClass }} object-cover {{ $variant === 'panel' ? '' : 'shadow-luxury' }}"
                />
                @if($variant === 'overlap')
                <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-forest/6 -z-10"></div>
                @endif
            </div>
        </div>
        @endif
        <div class="{{ $textOrder }}">
            @if(!empty($content['eyebrow']))
            <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-text-secondary mb-4">{{ $content['eyebrow'] }}</p>
            @endif
            @if(!empty($content['heading']))
            <h2 class="{{ $variant === 'panel' ? 'text-h2' : 'text-h3' }} font-heading font-bold tracking-tight text-forest mb-6">{{ $content['heading'] }}</h2>
            @endif
            @if(!empty($content['text']))
            <div class="prose prose-forest max-w-none text-text-secondary">{!! $content['text'] !!}</div>
            @endif
            @if(!empty($content['button_url']))
            <div class="mt-8">
                <a href="{{ $content['button_url'] }}" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                    {{ $content['button_text'] ?? 'Learn More' }}
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endif
