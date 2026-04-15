@php
    $style   = $content['style'] ?? 'line';
    $spacing = match($content['spacing'] ?? 'md') { 'sm' => 'py-4', 'lg' => 'py-12', default => 'py-8' };
@endphp
<div class="max-w-7xl mx-auto px-6 lg:px-12 {{ $spacing }}">
    @if($style === 'line')
    <hr class="border-stone">
    @elseif($style === 'dashed')
    <hr class="border-dashed border-stone">
    @elseif($style === 'thick')
    <div class="h-1 bg-forest/15"></div>
    @elseif($style === 'dots')
    <div class="flex items-center justify-center gap-3">
        <div class="w-1.5 h-1.5 bg-forest/30"></div>
        <div class="w-1.5 h-1.5 bg-forest/30"></div>
        <div class="w-1.5 h-1.5 bg-forest/30"></div>
    </div>
    @elseif($style === 'leaf')
    <div class="flex items-center gap-4">
        <div class="flex-1 h-px bg-stone"></div>
        <i data-lucide="leaf" class="w-5 h-5 text-forest/50"></i>
        <div class="flex-1 h-px bg-stone"></div>
    </div>
    @elseif($style === 'decorative')
    <div class="flex items-center gap-4">
        <div class="flex-1 h-px bg-stone"></div>
        <div class="w-2 h-2 bg-forest"></div>
        <div class="w-3 h-3 bg-forest/40"></div>
        <div class="w-2 h-2 bg-forest"></div>
        <div class="flex-1 h-px bg-stone"></div>
    </div>
    @endif
</div>
