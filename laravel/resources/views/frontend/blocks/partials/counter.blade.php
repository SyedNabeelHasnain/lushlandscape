{{-- Block: counter --}}
@php
    $number = $content['number'] ?? '0';
    $suffix = $content['suffix'] ?? '';
    $label = $content['label'] ?? '';
    $icon = $content['icon'] ?? '';
    $animate = $content['animate'] ?? true;
@endphp
<div class="text-center" {{ $animate ? 'x-data="{ count: 0, target: '.preg_replace('/[^0-9]/', '', $number).'}" x-intersect:enter.once="$el.querySelector(\'.counter-num\').textContent = target + \''.$suffix.'\'"' : '' }}>
    @if($icon)<i data-lucide="{{ $icon }}" class="w-8 h-8 text-forest mx-auto mb-3"></i>@endif
    <div class="text-4xl font-bold text-text counter-num">{{ $number }}{{ $suffix }}</div>
    @if($label)<div class="text-sm text-text-secondary mt-1">{{ $label }}</div>@endif
</div>
