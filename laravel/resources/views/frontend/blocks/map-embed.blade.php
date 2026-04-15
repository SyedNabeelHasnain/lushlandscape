@php $rounded = $content['rounded'] ?? true; @endphp
@if(!empty($content['embed_url']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
    <div class="{{ $rounded ? '' : '' }} overflow-hidden border border-stone">
        <iframe src="{{ $content['embed_url'] }}"
                width="100%"
                height="{{ $content['height'] ?? '400' }}"
                style="border:0"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Map"></iframe>
    </div>
</div>
@endif
