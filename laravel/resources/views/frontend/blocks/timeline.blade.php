@php $items = $content['items'] ?? []; @endphp
@if(!empty($items))
<div class="max-w-4xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <div class="text-center mb-14 reveal">
        <h2 class="text-h3 font-heading font-bold tracking-tight text-forest">{{ $content['heading'] }}</h2>
    </div>
    @endif
    <div class="relative">
        <div class="absolute left-4 md:left-1/2 top-0 bottom-0 w-px bg-forest/15 md:-translate-x-1/2"></div>
        <div class="space-y-10 reveal-stagger">
            @foreach($items as $i => $item)
            <div class="relative flex items-start gap-6 {{ $i % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse' }}">
                <div class="absolute left-4 md:left-1/2 -translate-x-1/2 w-4 h-4 bg-forest border-4 border-white shadow-lg z-10 mt-1.5"></div>
                <div class="ml-12 md:ml-0 md:w-[calc(50%-2rem)] {{ $i % 2 === 0 ? 'md:pr-10' : 'md:pl-10' }}">
                    <div class="bg-white border border-stone p-8 hover:border-forest/20 hover:shadow-luxury transition-all duration-500">
                        @if(!empty($item['date']))
                        <span class="text-[10px] font-bold text-forest uppercase tracking-[0.15em]">{{ $item['date'] }}</span>
                        @endif
                        <div class="flex items-start gap-4 mt-2">
                            @if(!empty($item['icon']))
                            <div class="w-10 h-10 bg-forest/6 flex items-center justify-center shrink-0">
                                <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4 text-forest"></i>
                            </div>
                            @endif
                            <div>
                                @if(!empty($item['title']))
                                <h3 class="font-heading font-bold text-ink">{{ $item['title'] }}</h3>
                                @endif
                                @if(!empty($item['description']))
                                <p class="text-sm text-text-secondary mt-2 leading-relaxed">{{ $item['description'] }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
