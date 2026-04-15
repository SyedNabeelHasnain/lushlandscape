@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head
    :title="($project->meta_title ?: $project->title) . ' | Lush Landscape Service'"
    :description="$project->meta_description ?: Illuminate\Support\Str::limit($project->description ?? '', 155)"
    :canonical="url('/portfolio/' . $project->slug)"
    :ogTitle="$project->title"
    :ogDescription="$project->description"
    :ogImage="$project->heroMedia?->url ?? null"
    :schema="$schema"
/>
@endsection
@section('content')

@php
    $phone = \App\Models\Setting::get('phone', '');
    $phoneClean = preg_replace('/[^+\d]/', '', $phone);
@endphp

<div class="bg-white border-b border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 py-3">
        <x-frontend.breadcrumbs :items="$breadcrumbs" />
    </div>
</div>

{{-- Hero image --}}
@if($project->heroMedia)
<div class="relative bg-black overflow-hidden">
    <img src="{{ $project->heroMedia->url }}"
         alt="{{ $project->heroMedia->default_alt_text ?? $project->title }}"
         class="w-full h-64 md:h-96 object-cover opacity-90"
         width="1200" height="600" loading="eager" fetchpriority="high" decoding="async">
    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
    <div class="absolute bottom-0 left-0 right-0 p-6 md:p-10">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap gap-2 mb-3">
                @if($project->is_featured)
                <span class="bg-white text-forest text-xs px-3 py-1  font-semibold">Featured Project</span>
                @endif
                @if($project->city)
                <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-1 ">{{ $project->city->name }}, Ontario</span>
                @endif
                @if($project->service)
                <span class="bg-white/20 backdrop-blur-sm text-white text-xs px-3 py-1 ">{{ $project->service->name }}</span>
                @endif
            </div>
            <h1 class="text-2xl md:text-4xl font-bold text-white font-heading leading-tight">{{ $project->title }}</h1>
        </div>
    </div>
</div>
@else
<section class="py-10 md:py-14 bg-white">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex flex-wrap gap-2 mb-3">
            @if($project->is_featured)
            <span class="bg-forest/10 text-forest text-xs px-3 py-1  font-semibold">Featured Project</span>
            @endif
            @if($project->city)
            <span class="text-xs bg-forest/10 text-forest px-3 py-1 ">{{ $project->city->name }}, Ontario</span>
            @endif
            @if($project->service)
            <span class="text-xs bg-forest/6 text-text-secondary px-3 py-1 ">{{ $project->service->name }}</span>
            @endif
        </div>
        <h1 class="text-2xl md:text-4xl font-bold text-text font-heading leading-tight">{{ $project->title }}</h1>
    </div>
</section>
@endif

{{-- Main content + sidebar --}}
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10 md:py-16">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

        {{-- Main column --}}
        <div class="lg:col-span-2 space-y-10">

            {{-- Description --}}
            @if($project->description)
            <div>
                <p class="text-lg text-text-secondary leading-relaxed">{{ $project->description }}</p>
            </div>
            @endif

            {{-- Before & After --}}
            @if($project->beforeImage && $project->afterImage)
            <div>
                <h2 class="text-h2 font-heading font-bold text-ink mb-5 flex items-center gap-2">
                    <i data-lucide="arrow-right-left" class="w-5 h-5 text-forest"></i> Before and After
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="relative  overflow-hidden border border-stone">
                        <img src="{{ $project->beforeImage->url }}"
                             alt="Before: {{ $project->title }}"
                             class="w-full aspect-4/3 object-cover" loading="lazy" decoding="async">
                        <div class="absolute bottom-3 left-3">
                            <span class="bg-black/70 text-white text-xs px-3 py-1.5  font-semibold">Before</span>
                        </div>
                    </div>
                    <div class="relative  overflow-hidden border border-stone">
                        <img src="{{ $project->afterImage->url }}"
                             alt="After: {{ $project->title }}"
                             class="w-full aspect-4/3 object-cover" loading="lazy" decoding="async">
                        <div class="absolute bottom-3 left-3">
                            <span class="bg-forest text-white text-xs px-3 py-1.5  font-semibold">After</span>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Full body --}}
            @if($project->body)
            <div class="prose prose-sm max-w-none text-text-secondary leading-relaxed">
                {!! nl2br(e($project->body)) !!}
            </div>
            @endif

            {{-- Gallery --}}
            @if($gallery->isNotEmpty())
            <div>
                <h2 class="text-h2 font-heading font-bold text-ink mb-5 flex items-center gap-2">
                    <i data-lucide="images" class="w-5 h-5 text-forest"></i> Project Gallery
                </h2>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($gallery as $media)
                    <div class="aspect-4/3 overflow-hidden border border-stone hover:border-forest hover:shadow-luxury transition-all duration-500">
                        <img src="{{ $media->url }}"
                             alt="{{ $media->default_alt_text ?? $project->title . ' gallery image' }}"
                             class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                             loading="lazy" decoding="async">
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick navigation for service-city page --}}
            @if($project->service && $project->city)
            @php
                $scpSlug = \App\Models\ServiceCityPage::where('service_id', $project->service_id)
                    ->where('city_id', $project->city_id)
                    ->where('is_active', true)
                    ->value('slug_final');
            @endphp
            @if($scpSlug)
            <div class="bg-forest/10  p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <p class="text-sm font-semibold text-text">Interested in {{ $project->service->name }}?</p>
                    <p class="text-xs text-text-secondary mt-1">Learn more about our {{ $project->service->name }} services in {{ $project->city->name }}.</p>
                </div>
                <a href="/{{ $scpSlug }}" class="shrink-0 inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-semibold px-5 py-2.5  text-sm transition">
                    View Service <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            </div>
            @endif
            @endif
        </div>

        {{-- Sidebar --}}
        <div class="space-y-6">

            {{-- Project Details card --}}
            <div class="bg-cream border border-stone p-6">
                <h3 class="text-sm font-bold text-text mb-4 uppercase tracking-wider">Project Details</h3>
                <dl class="space-y-4">
                    @if($project->service)
                    <div class="flex items-start gap-3">
                        <i data-lucide="wrench" class="w-4 h-4 text-forest mt-0.5 shrink-0"></i>
                        <div>
                            <dt class="text-xs text-text-secondary">Service</dt>
                            <dd class="text-sm font-medium text-text">{{ $project->service->name }}</dd>
                        </div>
                    </div>
                    @endif
                    @if($project->city)
                    <div class="flex items-start gap-3">
                        <i data-lucide="map-pin" class="w-4 h-4 text-forest mt-0.5 shrink-0"></i>
                        <div>
                            <dt class="text-xs text-text-secondary">Location</dt>
                            <dd class="text-sm font-medium text-text">{{ $project->neighborhood ? $project->neighborhood . ', ' : '' }}{{ $project->city->name }}</dd>
                        </div>
                    </div>
                    @endif
                    @if($project->project_value_range)
                    <div class="flex items-start gap-3">
                        <i data-lucide="dollar-sign" class="w-4 h-4 text-forest mt-0.5 shrink-0"></i>
                        <div>
                            <dt class="text-xs text-text-secondary">Project Value</dt>
                            <dd class="text-sm font-medium text-text">{{ $project->project_value_range }}</dd>
                        </div>
                    </div>
                    @endif
                    @if($project->project_duration)
                    <div class="flex items-start gap-3">
                        <i data-lucide="clock" class="w-4 h-4 text-forest mt-0.5 shrink-0"></i>
                        <div>
                            <dt class="text-xs text-text-secondary">Duration</dt>
                            <dd class="text-sm font-medium text-text">{{ $project->project_duration }}</dd>
                        </div>
                    </div>
                    @endif
                    @if($project->completion_date)
                    <div class="flex items-start gap-3">
                        <i data-lucide="calendar-check" class="w-4 h-4 text-forest mt-0.5 shrink-0"></i>
                        <div>
                            <dt class="text-xs text-text-secondary">Completed</dt>
                            <dd class="text-sm font-medium text-text">{{ $project->completion_date->format('F Y') }}</dd>
                        </div>
                    </div>
                    @endif
                    @if($project->category)
                    <div class="flex items-start gap-3">
                        <i data-lucide="tag" class="w-4 h-4 text-forest mt-0.5 shrink-0"></i>
                        <div>
                            <dt class="text-xs text-text-secondary">Category</dt>
                            <dd class="text-sm font-medium text-text">{{ $project->category->name }}</dd>
                        </div>
                    </div>
                    @endif
                </dl>
            </div>

            {{-- CTA card --}}
            <div class="bg-forest  p-6 text-white sticky top-24">
                <h3 class="text-lg font-bold mb-2">Want Similar Results?</h3>
                <p class="text-white/70 text-sm mb-5">Book your on-site consultation for your {{ $project->service->name ?? 'landscaping' }} project{{ $project->city ? ' in ' . $project->city->name : '' }}.</p>
                <a href="/contact"
                   class="block bg-white text-forest font-bold py-3.5  text-center hover:bg-white/90 transition mb-3 text-sm">
                    Book a Consultation
                </a>
                @if($phone)
                <a href="tel:{{ $phoneClean }}"
                   class="flex items-center justify-center gap-2 text-white/70 hover:text-white text-sm transition">
                    <i data-lucide="phone" class="w-4 h-4"></i>{{ $phone }}
                </a>
                @endif
            </div>

            {{-- Why Choose Us --}}
            <div class="bg-cream  border border-stone p-6">
                <h3 class="text-sm font-bold text-text mb-4">Why Choose Us</h3>
                <ul class="space-y-3">
                    @foreach([
                        ['shield-check', '10-Year Workmanship Warranty'],
                        ['badge-check', 'Licensed &amp; Fully Insured'],
                        ['clock', 'On-Time Completion'],
                        ['star', 'Top-Rated on Google'],
                        ['users', 'Local Crews, Local Knowledge'],
                    ] as [$icon, $text])
                    <li class="flex items-center gap-3 text-sm text-text-secondary">
                        <i data-lucide="{{ $icon }}" class="w-4 h-4 text-forest shrink-0"></i>
                        {!! $text !!}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Browse by service --}}
            @if($project->service)
            <div class="bg-white  border border-stone p-6">
                <h3 class="text-sm font-bold text-text mb-3">More {{ $project->service->name }} Projects</h3>
                <a href="{{ $project->category ? route('portfolio.category', $project->category->slug) : route('portfolio.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-forest hover:text-forest-300 transition">
                    View all projects <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@if(isset($blocks) && $blocks->isNotEmpty())
    @foreach($blocks as $block)
        <x-frontend.block-renderer :block="$block" :context="$context" />
    @endforeach
@endif

{{-- Related projects --}}
@if($relatedProjects->isNotEmpty())
<section class="section-editorial bg-cream border-t border-stone">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-h2 font-heading font-bold text-ink">Related Projects</h2>
            </div>
            <a href="/portfolio" class="text-sm font-medium text-forest hover:text-forest-300 transition flex items-center gap-1">
                View all <i data-lucide="arrow-right" class="w-4 h-4"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($relatedProjects as $rp)
            <a href="/portfolio/{{ $rp->slug }}" class="group bg-white border border-stone overflow-hidden hover:border-forest hover:shadow-luxury transition-all duration-500">
                <div class="aspect-4/3 overflow-hidden bg-forest/10 relative">
                    @if($rp->heroMedia)
                    <x-frontend.media :asset="$rp->heroMedia" :alt="$rp->title" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                    @else
                    <div class="w-full h-full flex items-center justify-center"><i data-lucide="image" class="w-12 h-12 text-stone"></i></div>
                    @endif
                    @if($rp->city)
                    <div class="absolute top-3 left-3"><span class="bg-black/60 backdrop-blur-sm text-white text-xs px-2.5 py-1 ">{{ $rp->city->name }}</span></div>
                    @endif
                </div>
                <div class="p-5">
                    <h3 class="text-sm font-bold text-text group-hover:text-forest transition-colors line-clamp-2">{{ $rp->title }}</h3>
                    <div class="flex flex-wrap gap-1.5 mt-2">
                        @if($rp->service)<span class="text-xs text-text-secondary">{{ $rp->service->name }}</span>@endif
                        @if($rp->project_value_range)<span class="text-xs text-text-secondary">&middot; {{ $rp->project_value_range }}</span>@endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Bottom CTA --}}
<section class="section-editorial bg-luxury-green-deep text-white">
    <div class="max-w-3xl mx-auto px-6 lg:px-12 text-center">
        <h2 class="text-2xl md:text-3xl font-bold font-heading">Ready to Start Your Project?</h2>
        <p class="mt-3 text-white/70 text-lg">Book your consultation and receive a clear scope plan with thoughtful material direction.</p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="/contact" class="btn-luxury btn-luxury-primary inline-flex items-center gap-2">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i> Book a Consultation
            </a>
            <a href="/portfolio" class="btn-luxury btn-luxury-ghost inline-flex items-center gap-2">
                <i data-lucide="images" class="w-5 h-5"></i> Browse All Projects
            </a>
        </div>
    </div>
</section>

@endsection
