{{-- Block: html_embed — raw HTML / embed code --}}
@php
    $html = $content['html'] ?? '';
@endphp

@if(!empty($html))
    <div class="html-embed-block prose max-w-none">
        {!! $html !!}
    </div>
@endif
