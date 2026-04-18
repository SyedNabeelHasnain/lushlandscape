@props([
    'title'   => 'Our Service Area',
    'address' => '',
    'height'  => 400,
])
@php
    $embedKey = \App\Models\Setting::get('google_maps_embed_key', '');
    $addr     = $address ?: \App\Models\Setting::get('address', 'New York, NY');
    $encoded  = rawurlencode($addr);
    $mapsUrl = config('services.maps.embed_url', 'https://www.google.com/maps/embed/v1/place');
@endphp

<div class="w-full overflow-hidden border border-stone bg-stone-light" style="height: {{ $height }}px;">
    @if($embedKey)
    <iframe
        title="{{ $title }}"
        width="100%"
        height="{{ $height }}"
        style="border:0"
        loading="lazy"
        allowfullscreen
        referrerpolicy="no-referrer-when-downgrade"
        src="{{ $mapsUrl }}?key={{ $embedKey }}&q={{ $encoded }}&zoom=10"
    ></iframe>
    @else
    <div class="w-full h-full flex flex-col items-center justify-center gap-3">
        <i data-lucide="map" class="w-12 h-12 text-forest/30"></i>
        <p class="text-sm text-text-secondary">{{ $addr }}</p>
    </div>
    @endif
</div>
