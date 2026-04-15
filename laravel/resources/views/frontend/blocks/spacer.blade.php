@php
    $height = match($content['height'] ?? 'md') {
        'xs'  => 'h-4',
        'sm'  => 'h-8',
        'lg'  => 'h-16',
        'xl'  => 'h-24',
        '2xl' => 'h-32',
        default => 'h-12',
    };
    $mobileHide = isset($content['show_on_mobile']) && !$content['show_on_mobile'];
@endphp
<div class="{{ $height }} {{ $mobileHide ? 'hidden md:block' : '' }}" aria-hidden="true"></div>
