@php
    $faqs = $context['faqs'] ?? collect();
@endphp
@if($faqs->isNotEmpty())
<section class="bg-white py-20 lg:py-32 px-5 lg:px-12 section-fade-to-airy border-t border-stone/50">
    <div class="max-w-4xl mx-auto gs-reveal">
        <div class="text-center mb-12 lg:mb-20">
            <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-accent mb-4 lg:mb-6">{{ $content['eyebrow'] ?? 'Project Guidelines' }}</p>
            <h2 class="fluid-heading text-forest mb-6 word-wrap-safe">{{ $content['heading'] ?? 'Common Inquiries' }}</h2>
        </div>

        <div class="space-y-4 lg:space-y-6" x-data="{ activeAccordion: null }">
            @foreach($faqs as $faq)
            <div class="border border-stone/60 bg-[#F9FAF9] hover:border-accent transition-colors duration-300 gs-reveal">
                <button @click="activeAccordion = activeAccordion === {{ $faq->id }} ? null : {{ $faq->id }}" 
                        class="w-full text-left px-6 lg:px-8 py-5 lg:py-6 flex items-center justify-between focus:outline-none focus-visible:ring-2 focus-visible:ring-forest focus-visible:ring-inset">
                    <span class="text-lg lg:text-xl font-serif text-forest" :class="activeAccordion === {{ $faq->id }} ? 'text-forest-light' : ''">
                        {{ $faq->question }}
                    </span>
                    <div class="w-8 h-8 rounded-full border border-forest/10 flex items-center justify-center shrink-0 transition-transform duration-300 bg-white"
                         :class="activeAccordion === {{ $faq->id }} ? 'rotate-180 bg-accent text-white border-accent' : 'text-forest'">
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </div>
                </button>
                <div x-show="activeAccordion === {{ $faq->id }}" 
                     x-collapse 
                     x-cloak>
                    <div class="px-6 lg:px-8 pb-6 lg:pb-8 pt-2 text-ink/70 font-light text-base lg:text-lg leading-relaxed prose prose-stone max-w-none">
                        {!! $faq->answer !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif