{{-- Block: heading --}}
@php
    $text = $content['text'] ?? '';
    $level = $content['level'] ?? 'h2';
    $align = $content['align'] ?? 'left';
    $alignClass = match($align) { 'center' => 'text-center', 'right' => 'text-right', default => '' };
@endphp
@if($text)
<{{ $level }} class="font-bold text-text {{ $alignClass }} {{ $level === 'h1' ? 'text-4xl' : ($level === 'h3' ? 'text-2xl' : 'text-3xl') }}">{{ $text }}</{{ $level }}>
@endif
