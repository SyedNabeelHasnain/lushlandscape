{{-- Block: paragraph --}}
@php $text = $content['text'] ?? ''; @endphp
@if($text)
<p class="text-text-secondary leading-relaxed">{{ $text }}</p>
@endif
