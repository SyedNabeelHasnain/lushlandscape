{{-- Block: map --}}
@php
    $address = $content['address'] ?? \App\Models\Setting::get('address', '');
    $zoom = $content['zoom'] ?? 12;
    $height = $content['height'] ?? '400px';
    $mapsUrl = config('services.maps.embed_url', 'https://www.google.com/maps/embed');
@endphp
@if($address)
<div class="overflow-hidden rounded-xl" style="height: {{ $height }}">
    <iframe
        src="{{ $mapsUrl }}?pb=!1m18!1m12!1m3!1d100000!2d-79.8!3d43.25!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2s{{ urlencode($address) }}!5e0!3m2!1sen!2sca!4v1"
        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</div>
@endif
