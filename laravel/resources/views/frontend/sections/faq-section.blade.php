{{-- Section: faq_section --}}
@php
    use App\Models\Faq;
    $faqLimit   = (int) ($section['settings']['limit'] ?? 6);
    $faqCatId   = $section['settings']['faq_category_id'] ?? null;
    $isPage     = isset($page);
    $hasGroups  = $isPage && isset($faqGroups) && is_array($faqGroups);

    $faqHeading = !empty($section['settings']['heading'])
        ? $section['settings']['heading']
        : ($isPage
            ? 'Frequently Asked Questions'
            : (isset($city)
                ? 'Frequently Asked Questions: ' . $city->name . ' Landscaping'
                : (isset($service) ? 'Frequently Asked Questions: ' . $service->name : 'Frequently Asked Questions')));

    // Fallback: single list of FAQs for non-grouped contexts
    if (!$hasGroups) {
        if ($isPage && isset($faqs) && $faqs->isNotEmpty()) {
            $displayFaqs = $faqs->take($faqLimit);
        } else {
            $displayFaqs = Faq::where('status', 'published')
                ->when($faqCatId, fn($q) => $q->where('category_id', $faqCatId))
                ->orderBy('is_pinned', 'desc')
                ->orderBy('display_order')
                ->take($faqLimit)
                ->get();
        }
    }
@endphp

@if($hasGroups && ($faqGroups['general']->isNotEmpty() || $faqGroups['service']->isNotEmpty() || $faqGroups['city']->isNotEmpty()))
{{-- Grouped FAQ display for service-city pages --}}
<section class="section-editorial bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-heading font-bold tracking-tight text-text">{{ $faqHeading }}</h2>
        </div>

        @php $globalIndex = 0; @endphp
        <div x-data="{ open: null }">

        {{-- General FAQs --}}
        @if($faqGroups['general']->isNotEmpty())
        <div class="mb-10">
            <h3 class="text-lg font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="help-circle" class="w-5 h-5 text-forest"></i>
                General Questions
            </h3>
            <div class="space-y-3">
                @foreach($faqGroups['general'] as $faq)
                @php $idx = $globalIndex++; @endphp
                <div class="bg-white  border border-stone overflow-hidden">
                    <button type="button"
                        x-on:click="open = open === {{ $idx }} ? null : {{ $idx }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                        <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                           :class="open === {{ $idx }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === {{ $idx }}" x-collapse class="px-6 pb-5">
                        <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                            {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3 text-right">
                <a href="/faqs?type=general" class="inline-flex items-center gap-1 text-sm font-medium text-forest hover:text-forest-300 transition">
                    View all general FAQs <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- Service-specific FAQs --}}
        @if($faqGroups['service']->isNotEmpty())
        <div class="mb-10">
            <h3 class="text-lg font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="wrench" class="w-5 h-5 text-forest"></i>
                {{ $page->service->name }} FAQs
            </h3>
            <div class="space-y-3">
                @foreach($faqGroups['service'] as $faq)
                @php $idx = $globalIndex++; @endphp
                <div class="bg-white  border border-stone overflow-hidden">
                    <button type="button"
                        x-on:click="open = open === {{ $idx }} ? null : {{ $idx }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                        <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                           :class="open === {{ $idx }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === {{ $idx }}" x-collapse class="px-6 pb-5">
                        <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                            {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3 text-right">
                <a href="/faqs?service={{ urlencode($page->service->name) }}" class="inline-flex items-center gap-1 text-sm font-medium text-forest hover:text-forest-300 transition">
                    View all {{ $page->service->name }} FAQs <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
        @endif

        {{-- City-specific FAQs --}}
        @if($faqGroups['city']->isNotEmpty())
        <div>
            <h3 class="text-lg font-semibold text-text mb-4 flex items-center gap-2">
                <i data-lucide="map-pin" class="w-5 h-5 text-forest"></i>
                {{ $page->city->name }} Local FAQs
            </h3>
            <div class="space-y-3">
                @foreach($faqGroups['city'] as $faq)
                @php $idx = $globalIndex++; @endphp
                <div class="bg-white  border border-stone overflow-hidden">
                    <button type="button"
                        x-on:click="open = open === {{ $idx }} ? null : {{ $idx }}"
                        class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                        <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                           :class="open === {{ $idx }} ? 'rotate-180' : ''"></i>
                    </button>
                    <div x-show="open === {{ $idx }}" x-collapse class="px-6 pb-5">
                        <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                            {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-3 text-right">
                <a href="/faqs?city={{ urlencode($page->city->name) }}" class="inline-flex items-center gap-1 text-sm font-medium text-forest hover:text-forest-300 transition">
                    View all {{ $page->city->name }} FAQs <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
        </div>
        @endif

        </div>
    </div>
</section>

@elseif(isset($displayFaqs) && $displayFaqs->isNotEmpty())
{{-- Fallback: single FAQ list for non-service-city pages --}}
<section class="section-editorial bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-heading font-bold tracking-tight text-text">{{ $faqHeading }}</h2>
        </div>
        <div class="space-y-3" x-data="{ open: null }">
            @foreach($displayFaqs as $i => $faq)
            <div class="bg-white  border border-stone overflow-hidden"
                >
                <button type="button"
                    x-on:click="open = open === {{ $i }} ? null : {{ $i }}"
                    class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                    <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                       :class="open === {{ $i }} ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open === {{ $i }}" x-collapse class="px-6 pb-5">
                    <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                        {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif
