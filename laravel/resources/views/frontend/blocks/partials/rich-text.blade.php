{{-- Block: rich_text --}}
@php $html = $content['html'] ?? ''; @endphp
@if($html)
<div class="prose prose-lg max-w-none text-text-secondary">
    {!! $html !!}
</div>
@endif
