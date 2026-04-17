@php
    $tabItems = $content['tabs'] ?? [];
    $style    = $content['style'] ?? 'underline';
    $uid      = 'tabs-' . uniqid();
@endphp
@if(!empty($tabItems))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10" x-data="{ active: 0 }">
    <div class="flex flex-wrap gap-1 mb-8 {{ $style === 'underline' ? 'border-b border-stone' : '' }}" role="tablist">
        @foreach($tabItems as $i => $tab)
        <button type="button" x-on:click="active = {{ $i }}" role="tab"
            :aria-selected="active === {{ $i }}"
            class="px-5 py-3 text-sm font-medium transition-all duration-300 flex items-center gap-2
            @if($style === 'pills')

            @elseif($style === 'boxed')
                border border-b-0 border-stone
            @else
                -mb-px border-b-2
            @endif"
            :class="active === {{ $i }}
                ? '{{ $style === 'pills' ? 'bg-forest text-white shadow-lg shadow-forest/20' : ($style === 'boxed' ? 'bg-white border-stone text-forest' : 'border-forest text-forest') }}'
                : '{{ $style === 'pills' ? 'text-text-secondary hover:bg-cream' : ($style === 'boxed' ? 'bg-cream border-transparent text-text-secondary hover:text-ink' : 'border-transparent text-text-secondary hover:text-ink hover:border-forest/30') }}'">
            @if(!empty($tab['icon']))
            <i data-lucide="{{ $tab['icon'] }}" class="w-4 h-4"></i>
            @endif
            {{ $tab['title'] ?? 'Tab ' . ($i + 1) }}
        </button>
        @endforeach
    </div>
    @foreach($tabItems as $i => $tab)
    <div x-show="active === {{ $i }}" x-cloak x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
         role="tabpanel" class="prose prose-forest max-w-none">
        {!! $tab['content'] ?? '' !!}
    </div>
    @endforeach
</div>
@endif
