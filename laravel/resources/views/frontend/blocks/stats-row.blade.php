@php
    $stats  = $content['stats'] ?? [];
    $bg     = $content['bg'] ?? 'forest';
    $bgCls  = match($bg) { 'cream' => 'bg-cream', 'white' => 'bg-white', 'dark' => 'bg-luxury-dark', default => 'bg-forest' };
    $valClr = in_array($bg, ['forest', 'dark']) ? 'text-white' : 'text-text';
    $lblClr = in_array($bg, ['forest', 'dark']) ? 'text-white/70' : 'text-text-secondary';
    $iconClr = in_array($bg, ['forest', 'dark']) ? 'text-white/60' : 'text-forest';
@endphp
@if(!empty($stats))
<section class="{{ $bgCls }} py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-{{ min(count($stats), 4) }} gap-6">
            @foreach($stats as $stat)
            @if(!empty($stat['value']))
            <div class="text-center">
                @if(!empty($stat['icon']))
                <i data-lucide="{{ $stat['icon'] }}" class="w-8 h-8 {{ $iconClr }} mx-auto mb-2"></i>
                @endif
                <p class="text-3xl md:text-4xl font-bold {{ $valClr }}">{{ $stat['value'] }}</p>
                @if(!empty($stat['label']))
                <p class="text-sm {{ $lblClr }} mt-1">{{ $stat['label'] }}</p>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
