@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    title="Frequently Asked Questions | Landscaping Services Ontario | Lush Landscape Service"
    description="Find answers to common questions about landscaping services, costs, permits, timelines, and materials across Ontario. Interlocking, concrete, hardscaping, and softscaping FAQs."
    :canonical="url('/faqs')"
    :schema="$schema"
    :paginator="$faqs"
/>
@endsection
@section('content')

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

{{-- Hero with search --}}
<section class="section-editorial bg-white">
    <div class="max-w-4xl mx-auto px-6 lg:px-12 text-center">
        <h1 class="text-h2 font-heading font-bold text-ink">Frequently Asked Questions</h1>
        <p class="mt-3 text-text-secondary text-lg max-w-2xl mx-auto">Get answers about our landscaping services, pricing, permits, timelines, and more across Ontario.</p>

        <form method="GET" action="{{ url('/faqs') }}" class="mt-8 max-w-xl mx-auto">
            <div class="relative">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-text-secondary pointer-events-none"></i>
                <input type="text" name="q" value="{{ $searchQuery }}" placeholder="Search FAQs (e.g. driveway cost, permit requirements...)"
                       aria-label="Search FAQs"
                       class="w-full pl-12 pr-24 py-4 bg-white border border-stone  text-sm focus:outline-none focus:border-forest focus:ring-1 focus:ring-forest transition">
                <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 btn-luxury btn-luxury-primary text-sm">
                    Search
                </button>
            </div>
            {{-- Preserve active filters --}}
            @if($activeType)<input type="hidden" name="type" value="{{ $activeType }}">@endif
            @if($activeCity)<input type="hidden" name="city" value="{{ $activeCity }}">@endif
            @if($activeService)<input type="hidden" name="service" value="{{ $activeService }}">@endif
            @if($activeCategory)<input type="hidden" name="category" value="{{ $activeCategory }}">@endif
        </form>
    </div>
</section>

{{-- Filters --}}
<section class="bg-white border-b border-stone sticky top-0 z-30" x-data="{ showFilters: false }">
    <div class="max-w-5xl mx-auto px-6 lg:px-12 py-4">
        {{-- Type tabs (desktop) --}}
        <div class="hidden md:flex items-center gap-2 flex-wrap">
            <a href="{{ url('/faqs') }}" class="px-4 py-2  text-sm font-medium transition {{ !$activeType && !$activeCity && !$activeService && !$activeCategory && !$searchQuery ? 'bg-forest text-white' : 'bg-forest/6 text-text-secondary hover:bg-forest/10' }}">
                All FAQs
            </a>
            @foreach([
                'general'    => ['label' => 'General', 'icon' => 'help-circle'],
                'service'    => ['label' => 'Service', 'icon' => 'wrench'],
                'local'      => ['label' => 'By City', 'icon' => 'map-pin'],
                'compliance' => ['label' => 'Permits & Regulations', 'icon' => 'shield-check'],
                'billing'    => ['label' => 'Pricing & Payment', 'icon' => 'credit-card'],
                'booking'    => ['label' => 'Booking & Process', 'icon' => 'calendar'],
            ] as $type => $meta)
            <a href="{{ url('/faqs?type=' . $type) }}" class="inline-flex items-center gap-1.5 px-4 py-2  text-sm font-medium transition {{ $activeType === $type ? 'bg-forest text-white' : 'bg-forest/6 text-text-secondary hover:bg-forest/10' }}">
                <i data-lucide="{{ $meta['icon'] }}" class="w-3.5 h-3.5"></i>
                {{ $meta['label'] }}
            </a>
            @endforeach
        </div>

        {{-- Mobile filter toggle --}}
        <div class="md:hidden">
            <button type="button" x-on:click="showFilters = !showFilters" class="w-full flex items-center justify-between px-4 py-3 bg-cream  text-sm font-medium text-text">
                <span class="flex items-center gap-2">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                    Filter FAQs
                    @if($activeType || $activeCity || $activeService)
                    <span class="bg-forest text-white text-xs px-2 py-0.5 ">Active</span>
                    @endif
                </span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform" :class="showFilters ? 'rotate-180' : ''"></i>
            </button>
        </div>

        {{-- Expanded filters (mobile + advanced) --}}
        <div x-show="showFilters" x-collapse x-cloak class="md:hidden mt-3 space-y-3">
            <div class="flex flex-wrap gap-2">
                <a href="{{ url('/faqs') }}" class="px-3 py-1.5  text-xs font-medium {{ !$activeType ? 'bg-forest text-white' : 'bg-forest/6 text-text-secondary' }}">All</a>
                @foreach(['general' => 'General', 'service' => 'Service', 'local' => 'By City', 'compliance' => 'Permits', 'billing' => 'Pricing', 'booking' => 'Booking'] as $type => $label)
                <a href="{{ url('/faqs?type=' . $type) }}" class="px-3 py-1.5  text-xs font-medium {{ $activeType === $type ? 'bg-forest text-white' : 'bg-forest/6 text-text-secondary' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        {{-- City/Service dropdowns (always visible on desktop, in expanded panel on mobile) --}}
        <div class="hidden md:flex items-center gap-3 mt-3">
            <form method="GET" action="{{ url('/faqs') }}" class="flex items-center gap-3">
                @if($activeType)<input type="hidden" name="type" value="{{ $activeType }}">@endif
                @if($searchQuery)<input type="hidden" name="q" value="{{ $searchQuery }}">@endif

                <select name="city" x-on:change="$el.form.submit()" aria-label="Filter by city" class="text-sm border border-stone  px-3 py-2 bg-white focus:border-forest focus:ring-1 focus:ring-forest">
                    <option value="">All Cities</option>
                    @foreach($cities as $city)
                    <option value="{{ $city->name }}" {{ $activeCity === $city->name ? 'selected' : '' }}>{{ $city->name }}</option>
                    @endforeach
                </select>

                <select name="service" x-on:change="$el.form.submit()" aria-label="Filter by service" class="text-sm border border-stone  px-3 py-2 bg-white focus:border-forest focus:ring-1 focus:ring-forest">
                    <option value="">All Services</option>
                    @foreach($services as $service)
                    <option value="{{ $service->name }}" {{ $activeService === $service->name ? 'selected' : '' }}>{{ $service->name }}</option>
                    @endforeach
                </select>

                @if($activeCity || $activeService)
                <a href="{{ url('/faqs' . ($activeType ? '?type=' . $activeType : '')) }}" class="text-xs text-forest font-medium hover:text-forest-300 transition flex items-center gap-1">
                    <i data-lucide="x" class="w-3 h-3"></i> Clear filters
                </a>
                @endif
            </form>
        </div>
    </div>
</section>

{{-- Referrer context banner --}}
@if($preFilter['city'] || $preFilter['service'])
<div class="bg-forest/10 border-b border-forest/20">
    <div class="max-w-5xl mx-auto px-6 lg:px-12 py-3 flex items-center justify-between">
        <p class="text-sm text-forest">
            <i data-lucide="info" class="w-4 h-4 inline-block mr-1 -mt-0.5"></i>
            Showing FAQs related to
            @if($preFilter['service'])<strong>{{ $preFilter['service'] }}</strong>@endif
            @if($preFilter['service'] && $preFilter['city']) in @endif
            @if($preFilter['city'])<strong>{{ $preFilter['city'] }}</strong>@endif
        </p>
        <a href="{{ url('/faqs') }}" class="text-xs text-forest font-medium hover:text-forest-300 transition">View all FAQs</a>
    </div>
</div>
@endif

{{-- Main content --}}
<section class="section-editorial bg-cream">
    <div class="max-w-4xl mx-auto px-6 lg:px-12">

        {{-- Active search/filter indicator --}}
        @if($searchQuery)
        <div class="mb-8">
            <p class="text-sm text-text-secondary">
                {{ $faqs->total() }} result{{ $faqs->total() !== 1 ? 's' : '' }} for "<strong class="text-text">{{ $searchQuery }}</strong>"
            </p>
        </div>
        @endif

        @if($grouped && !$searchQuery)
        {{-- Default grouped view --}}
        <div x-data="{ open: null }" class="space-y-12">

            {{-- General FAQs --}}
            @if($grouped['general']->isNotEmpty())
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 bg-forest/10  flex items-center justify-center">
                        <i data-lucide="help-circle" class="w-5 h-5 text-forest"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text">General Questions</h2>
                        <p class="text-xs text-text-secondary">Common questions about our landscaping services</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($grouped['general'] as $faq)
                    @php $uid = 'gen-' . $loop->index; @endphp
                    <div class="bg-white  border border-stone overflow-hidden">
                        <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                            class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                            <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                               :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                            <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                                {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Compliance / Permits FAQs --}}
            @if($grouped['compliance']->isNotEmpty())
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 bg-forest/10  flex items-center justify-center">
                        <i data-lucide="shield-check" class="w-5 h-5 text-forest"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text">Permits and Regulations</h2>
                        <p class="text-xs text-text-secondary">Ontario building codes, municipal permits, and conservation authority requirements</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($grouped['compliance'] as $faq)
                    @php $uid = 'comp-' . $loop->index; @endphp
                    <div class="bg-white  border border-stone overflow-hidden">
                        <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                            class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                            <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                               :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                            <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                                {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Billing / Pricing FAQs --}}
            @if($grouped['billing']->isNotEmpty())
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 bg-forest/10  flex items-center justify-center">
                        <i data-lucide="credit-card" class="w-5 h-5 text-forest"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text">Pricing and Payment</h2>
                        <p class="text-xs text-text-secondary">Cost estimates, payment options, and financing</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($grouped['billing'] as $faq)
                    @php $uid = 'bill-' . $loop->index; @endphp
                    <div class="bg-white  border border-stone overflow-hidden">
                        <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                            class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                            <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                               :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                            <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                                {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Booking / Process FAQs --}}
            @if($grouped['booking']->isNotEmpty())
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 bg-forest/10  flex items-center justify-center">
                        <i data-lucide="calendar" class="w-5 h-5 text-forest"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text">Booking and Process</h2>
                        <p class="text-xs text-text-secondary">How to get started, project timelines, and what to expect</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($grouped['booking'] as $faq)
                    @php $uid = 'book-' . $loop->index; @endphp
                    <div class="bg-white  border border-stone overflow-hidden">
                        <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                            class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                            <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                               :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                            <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                                {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Service-specific FAQs (sample) --}}
            @if($grouped['service']->isNotEmpty())
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 bg-forest/10  flex items-center justify-center">
                        <i data-lucide="wrench" class="w-5 h-5 text-forest"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text">Service-Specific Questions</h2>
                        <p class="text-xs text-text-secondary">Detailed answers about specific landscaping services</p>
                    </div>
                </div>
                <div class="space-y-3">
                    @foreach($grouped['service'] as $faq)
                    @php $uid = 'svc-' . $loop->index; @endphp
                    <div class="bg-white  border border-stone overflow-hidden">
                        <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                            class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                            <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                               :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                            <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                                {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <a href="{{ url('/faqs?type=service') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-forest hover:text-forest-300 transition">
                        View all service FAQs <i data-lucide="arrow-right" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
            @endif

            {{-- Local / City FAQs --}}
            @if($grouped['local']->isNotEmpty())
            <div>
                <div class="flex items-center gap-2 mb-5">
                    <div class="w-9 h-9 bg-forest/10  flex items-center justify-center">
                        <i data-lucide="map-pin" class="w-5 h-5 text-forest"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-text">City-Specific Questions</h2>
                        <p class="text-xs text-text-secondary">Local landscaping information for cities across Ontario</p>
                    </div>
                </div>
                @php $localGrouped = $grouped['local']->groupBy('city_relevance'); @endphp
                <div class="space-y-6">
                    @foreach($localGrouped as $cityName => $cityFaqs)
                    <div>
                        <h3 class="text-sm font-bold text-text mb-3 flex items-center gap-1.5">
                            <i data-lucide="navigation" class="w-3.5 h-3.5 text-forest"></i>
                            {{ $cityName }}
                        </h3>
                        <div class="space-y-3">
                            @foreach($cityFaqs as $faq)
                            @php $uid = 'loc-' . Str::slug($cityName) . '-' . $loop->index; @endphp
                            <div class="bg-white  border border-stone overflow-hidden">
                                <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                                    class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                                    <span class="text-sm font-semibold text-text leading-snug">{{ $faq->question }}</span>
                                    <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                                       :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                                </button>
                                <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                                    <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                                        {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        @else
        {{-- Filtered/Paginated view --}}
        @if($faqs->isNotEmpty())
        <div x-data="{ open: null }" class="space-y-3">
            @foreach($faqs as $faq)
            @php $uid = 'faq-' . $faq->id; @endphp
            <div class="bg-white  border border-stone overflow-hidden">
                <button type="button" x-on:click="open = open === '{{ $uid }}' ? null : '{{ $uid }}'"
                    class="w-full flex items-center justify-between px-6 py-5 text-left gap-4">
                    <div class="flex-1 min-w-0">
                        <span class="text-sm font-semibold text-text leading-snug block">{{ $faq->question }}</span>
                        <div class="flex items-center gap-2 mt-1.5">
                            @if($faq->category)
                            <span class="text-xs bg-forest/10 text-forest px-2 py-0.5 ">{{ $faq->category->name }}</span>
                            @endif
                            @if($faq->city_relevance)
                            <span class="text-xs text-text-secondary flex items-center gap-1">
                                <i data-lucide="map-pin" class="w-3 h-3"></i>{{ $faq->city_relevance }}
                            </span>
                            @endif
                        </div>
                    </div>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-forest shrink-0 transition-transform duration-200"
                       :class="open === '{{ $uid }}' ? 'rotate-180' : ''"></i>
                </button>
                <div x-show="open === '{{ $uid }}'" x-collapse class="px-6 pb-5">
                    <div class="text-sm text-text-secondary leading-relaxed border-t border-stone pt-4">
                        {!! nl2br(e($faq->short_answer ?: $faq->answer)) !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-10">{{ $faqs->links() }}</div>
        @else
        <div class="text-center py-16">
            <i data-lucide="search-x" class="w-12 h-12 text-text-secondary mx-auto mb-4"></i>
            <h2 class="text-lg font-bold text-text mb-2">No FAQs Found</h2>
            <p class="text-sm text-text-secondary mb-6">We could not find any FAQs matching your search or filters.</p>
            <a href="{{ url('/faqs') }}" class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-semibold px-6 py-3  text-sm transition">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> View All FAQs
            </a>
        </div>
        @endif
        @endif
    </div>
</section>

{{-- Still have questions CTA --}}
<section class="section-editorial bg-luxury-green-deep text-white">
    <div class="max-w-3xl mx-auto px-6 lg:px-12 text-center">
        <h2 class="text-2xl md:text-3xl font-heading font-bold tracking-tight">Still Have Questions?</h2>
        <p class="mt-3 text-white/70 text-lg">Our team can help you plan scope, material direction, and the right path from consultation to construction.</p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/contact" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i> Book a Consultation
            </a>
            <a href="/contact" class="btn-luxury btn-luxury-ghost inline-flex items-center gap-2">
                <i data-lucide="mail" class="w-5 h-5"></i> Contact Us
            </a>
        </div>
    </div>
</section>

@endsection
