@php $items = $content['items'] ?? []; @endphp
@if(!empty($items))
<section class="max-w-3xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <h2 class="text-h3 font-heading font-bold tracking-tight text-forest mb-10">{{ $content['heading'] }}</h2>
    @endif
    <div class="space-y-3" x-data="{ open: null }">
        @foreach($items as $i => $item)
        @if(!empty($item['question']))
        <div class="border border-stone overflow-hidden bg-white hover:border-forest/20 transition-all duration-500 reveal">
            <button type="button"
                    x-on:click="open === {{ $i }} ? open = null : open = {{ $i }}"
                    class="w-full flex items-center justify-between px-8 py-6 text-left font-medium text-ink hover:bg-cream transition-all duration-300"
                    :aria-expanded="open === {{ $i }}">
                <span class="text-sm">{{ $item['question'] }}</span>
                <div class="w-10 h-10 flex items-center justify-center shrink-0 transition-colors duration-300"
                     :class="open === {{ $i }} ? 'bg-forest' : 'bg-forest/6'">
                    <i data-lucide="chevron-down" class="w-4 h-4 transition-all duration-300"
                       :class="open === {{ $i }} ? 'rotate-180 text-white' : 'text-forest'"></i>
                </div>
            </button>
            <div x-show="open === {{ $i }}" x-cloak x-collapse class="border-t border-stone">
                <div class="px-8 py-6 text-sm text-text-secondary leading-relaxed">{{ $item['answer'] ?? '' }}</div>
            </div>
        </div>
        @endif
        @endforeach
    </div>
</section>
@endif
