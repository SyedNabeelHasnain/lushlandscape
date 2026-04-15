@php
    $align = match ($content['align'] ?? 'right') {
        'left' => 'justify-start',
        'center' => 'justify-center',
        default => 'justify-end',
    };

    $tone = $content['tone'] ?? 'dark';
    $styleMap = [
        'primary' => 'btn-luxury btn-luxury-primary',
        'white' => 'btn-luxury btn-luxury-white',
        'ghost' => $tone === 'light'
            ? 'btn-luxury border border-forest text-forest hover:bg-forest hover:text-white'
            : 'btn-luxury btn-luxury-ghost',
    ];
@endphp

<div class="flex flex-wrap items-center gap-3 {{ $align }}">
    @if(!empty($content['primary_text']) && !empty($content['primary_url']))
        <a href="{{ $content['primary_url'] }}" class="{{ $styleMap[$content['primary_style'] ?? 'ghost'] ?? $styleMap['ghost'] }}">
            {{ $content['primary_text'] }}
        </a>
    @endif
    @if(!empty($content['secondary_text']) && !empty($content['secondary_url']))
        <a href="{{ $content['secondary_url'] }}" class="{{ $styleMap[$content['secondary_style'] ?? 'white'] ?? $styleMap['white'] }}">
            {{ $content['secondary_text'] }}
        </a>
    @endif
</div>
