{{-- Block: spacer --}}
@php
    $height = $content['height'] ?? 'md';
    $heightMap = ['xs' => 'h-4', 'sm' => 'h-8', 'md' => 'h-16', 'lg' => 'h-24', 'xl' => 'h-32'];
    $heightClass = $heightMap[$height] ?? 'h-16';
@endphp
<div class="{{ $heightClass }}"></div>
