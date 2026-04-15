{{-- Block: divider --}}
@php
    $style = $content['style'] ?? 'solid';
    $width = $content['width'] ?? 'full';
    $styleMap = ['solid' => 'border-solid', 'dashed' => 'border-dashed', 'dotted' => 'border-dotted'];
    $widthClass = $width === 'centered' ? 'max-w-xs mx-auto' : '';
    $borderClass = $styleMap[$style] ?? 'border-solid';
@endphp
<div class="{{ $widthClass }}">
    <hr class="border-stone {{ $borderClass }}">
</div>
