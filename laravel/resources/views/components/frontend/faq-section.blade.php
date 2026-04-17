@props([
    'faqs'     => [],
    'title'    => 'Frequently Asked Questions',
    'subtitle' => '',
    'bg'       => 'white',
])

@if(count($faqs) > 0)
<section class="section-editorial bg-{{ $bg === 'cream' ? 'cream' : 'white' }}">
    <div class="max-w-3xl mx-auto px-6 lg:px-12">
        <div class="mb-16 reveal text-center">
            <span class="text-eyebrow text-forest mb-5 block">FAQ</span>
            <h2 class="text-h2 font-heading font-bold text-ink">{{ $title }}</h2>
            @if($subtitle)<p class="mt-5 text-text-secondary text-body-lg max-w-xl mx-auto">{{ $subtitle }}</p>@endif
        </div>

        <div class="divide-y divide-stone" x-data="{ active: null }">
            @foreach($faqs as $i => $faq)
            @php
                $q = is_object($faq) ? $faq->question : ($faq['question'] ?? '');
                $a = is_object($faq) ? $faq->answer : ($faq['answer'] ?? '');
            @endphp
            @if($q && $a)
            <div class="group reveal">
                <button
                    x-on:click="active = active === {{ $i }} ? null : {{ $i }}"
                    class="flex items-center justify-between w-full py-8 text-left gap-8"
                    :aria-expanded="active === {{ $i }}"
                    aria-controls="faq-answer-{{ $i }}"
                    id="faq-btn-{{ $i }}"
                >
                    <span class="text-lg font-heading font-bold text-ink leading-snug">{{ $q }}</span>
                    <span class="shrink-0 w-10 h-10 border flex items-center justify-center transition-all duration-400"
                          :class="active === {{ $i }} ? 'bg-forest border-forest text-white' : 'border-stone text-forest'">
                        <i data-lucide="plus" class="w-4 h-4 transition-transform duration-300" :class="{'rotate-45': active === {{ $i }}}"></i>
                    </span>
                </button>
                <div
                    id="faq-answer-{{ $i }}"
                    role="region"
                    aria-labelledby="faq-btn-{{ $i }}"
                    x-show="active === {{ $i }}" x-cloak
                    x-collapse
                >
                    <div class="pb-8 text-text-secondary leading-relaxed max-w-2xl">
                        {!! $a !!}
                    </div>
                </div>
            </div>
            @endif
            @endforeach
        </div>
    </div>
</section>
@endif
