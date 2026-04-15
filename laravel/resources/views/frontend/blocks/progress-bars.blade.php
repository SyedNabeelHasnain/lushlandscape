@php $bars = $content['bars'] ?? []; @endphp
@if(!empty($bars))
<div class="max-w-3xl mx-auto px-6 lg:px-12 py-12">
    @if(!empty($content['heading']))
    <h2 class="text-h3 font-heading font-bold text-forest mb-10">{{ $content['heading'] }}</h2>
    @endif
    <div class="space-y-6">
        @foreach($bars as $bar)
        @php $pct = max(0, min(100, intval($bar['percent'] ?? 0))); @endphp
        @if(!empty($bar['label']))
        <div x-data="{ width: 0 }" x-intersect.once="setTimeout(() => width = {{ $pct }}, 100)">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-ink">{{ $bar['label'] }}</span>
                <span class="text-sm font-bold text-forest" x-text="width + '%'">0%</span>
            </div>
            <div class="w-full bg-forest/6 h-2 overflow-hidden">
                <div class="bg-forest h-full transition-all duration-1000 ease-out"
                     :style="'width: ' + width + '%'"></div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>
@endif
