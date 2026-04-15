@php
    $style = $content['style'] ?? 'forest';
    $bg    = match($style) { 'cream' => 'bg-cream', 'dark' => 'bg-luxury-dark text-white', default => 'bg-luxury-green-deep text-white' };
    $textClr  = $style === 'cream' ? 'text-text' : 'text-white';
    $subClr   = $style === 'cream' ? 'text-text-secondary' : 'text-white/75';
    $btnClass = $style === 'forest' ? 'btn-luxury btn-luxury-white' : 'btn-luxury btn-luxury-primary';
@endphp
@if(!empty($content['heading']))
<section class="{{ $bg }} py-14">
    <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center">
        <h2 class="text-3xl font-heading font-bold {{ $textClr }} mb-3">{{ $content['heading'] }}</h2>
        @if(!empty($content['subheading']))
        <p class="{{ $subClr }} mb-8">{{ $content['subheading'] }}</p>
        @endif
        @if(!empty($content['button_url']))
        <a href="{{ $content['button_url'] }}"
           class="{{ $btnClass }} inline-block">
            {{ $content['button_text'] ?? 'Get Started' }}
        </a>
        @endif
    </div>
</section>
@endif
