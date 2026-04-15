@php
    $counters = $content['counters'] ?? [];
    $bg       = $content['bg'] ?? 'white';
    $bgCls    = match($bg) { 'cream' => 'bg-cream', 'forest' => 'bg-forest', 'dark' => 'bg-luxury-dark', default => 'bg-white' };
    $textClr  = in_array($bg, ['forest', 'dark']) ? 'text-white' : 'text-text';
    $subClr   = in_array($bg, ['forest', 'dark']) ? 'text-white/70' : 'text-text-secondary';
    $iconBg   = in_array($bg, ['forest', 'dark']) ? 'bg-white/10' : 'bg-forest/10';
    $iconClr  = in_array($bg, ['forest', 'dark']) ? 'text-white' : 'text-forest';
@endphp
@if(!empty($counters))
<section class="{{ $bgCls }} py-12">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="grid grid-cols-2 md:grid-cols-{{ min(count($counters), 4) }} gap-6 md:gap-8">
            @foreach($counters as $counter)
            @if(!empty($counter['target']))
            <div class="text-center" x-data="{ current: 0, target: {{ intval($counter['target']) }}, started: false }"
                 x-intersect.once="if(!started){ started=true; let step=Math.ceil(target/40); let iv=setInterval(()=>{ current+=step; if(current>=target){current=target;clearInterval(iv)} },30) }">
                @if(!empty($counter['icon']))
                <div class="w-12 h-12 {{ $iconBg }}  flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="{{ $counter['icon'] }}" class="w-6 h-6 {{ $iconClr }}"></i>
                </div>
                @endif
                <p class="text-3xl md:text-4xl font-bold {{ $textClr }}">
                    <span x-text="current.toLocaleString()">0</span><span>{{ $counter['suffix'] ?? '' }}</span>
                </p>
                @if(!empty($counter['label']))
                <p class="text-sm {{ $subClr }} mt-1">{{ $counter['label'] }}</p>
                @endif
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
