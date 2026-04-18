@php
    $activeTabDefault = $content['tab_1_name'] ?? 'Oakville';
@endphp
<section class="bg-airy-gradient py-20 lg:py-32 px-5 lg:px-12 overflow-hidden section-fade-to-white" x-data="{ activeTab: '{{ $activeTabDefault }}' }">
    <div class="max-w-7xl mx-auto gs-reveal">
        <div class="text-center mb-10 lg:mb-16">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-3 lg:mb-4">{{ $content['eyebrow'] ?? 'The Top 1% of the Region' }}</p>
            <h2 class="fluid-heading text-forest mb-4 lg:mb-6 word-wrap-safe">{{ $content['heading'] ?? 'Executive Enclaves' }}</h2>
            @if(!empty($content['description']))
            <p class="text-ink/70 text-base lg:text-lg font-light max-w-2xl mx-auto">{{ $content['description'] }}</p>
            @endif
        </div>

        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 bg-white p-6 lg:p-16 border border-black/5 shadow-sm max-w-5xl mx-auto">
            <div class="w-full lg:w-1/3 flex lg:flex-col overflow-x-auto lg:overflow-x-visible space-x-6 lg:space-x-0 lg:space-y-2 border-b lg:border-b-0 lg:border-r border-stone pb-4 lg:pb-0 lg:pr-8 no-scrollbar">
                @for($i = 1; $i <= 5; $i++)
                    @if(!empty($content["tab_{$i}_name"]))
                    <button @click="activeTab = '{{ $content["tab_{$i}_name"] }}'" 
                            :aria-selected="activeTab === '{{ $content["tab_{$i}_name"] }}'"
                            :class="activeTab === '{{ $content["tab_{$i}_name"] }}' ? 'text-forest-light font-medium lg:border-accent border-b-2 lg:border-b-0 lg:border-l-2' : 'text-ink/50 font-light border-transparent hover:text-ink/80'" 
                            class="tab-btn text-left text-base sm:text-lg whitespace-nowrap lg:py-3 py-2 lg:pl-6 transition-all font-serif">
                            {{ $content["tab_{$i}_name"] }}
                    </button>
                    @endif
                @endfor
            </div>
            
            <div class="w-full lg:w-2/3 lg:pl-10 flex flex-col justify-center min-h-[140px] lg:min-h-[180px]">
                <p class="text-[9px] lg:text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">Service Footprint</p>
                
                @for($i = 1; $i <= 5; $i++)
                    @if(!empty($content["tab_{$i}_name"]) && !empty($content["tab_{$i}_items"]))
                    <ul x-show="activeTab === '{{ $content["tab_{$i}_name"] }}'" class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 sm:gap-y-6 text-forest font-light text-lg sm:text-xl font-serif" x-cloak>
                        @foreach(explode(',', $content["tab_{$i}_items"]) as $item)
                        <li>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                    @endif
                @endfor
            </div>
        </div>
    </div>
</section>