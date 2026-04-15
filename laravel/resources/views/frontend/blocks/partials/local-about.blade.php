{{-- Block: local_about — neighbourhood-aware about section --}}
@php
    $heading = !empty($content['heading']) ? $content['heading'] : (($context['city_name'] ?? null) ? 'About Landscaping in '.($context['city_name'] ?? '') : 'About Local Services');
    $subtitle = $content['subtitle'] ?? '';
    $cityName = $context['city_name'] ?? null;
    $citySummary = $context['city_summary'] ?? null;
@endphp

<div class="space-y-10">
    <div class="mb-10">
        @if($heading)
            <h2 class="text-3xl lg:text-4xl font-bold text-text tracking-tight animate-on-scroll" data-animation="fade-up">
                {{ $heading }}
            </h2>
        @endif
        @if($subtitle)
            <p class="mt-4 text-text-secondary text-lg max-w-2xl animate-on-scroll" data-animation="fade-up" data-delay="100">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    @if($citySummary)
        <div class="prose prose-lg max-w-none text-text-secondary animate-on-scroll" data-animation="fade-up" data-delay="150">
            {!! nl2br(e($citySummary)) !!}
        </div>
    @endif

    {{-- Neighbourhoods grid (from data source) --}}
    @if(isset($data) && $data->isNotEmpty())
        <div class="mt-12">
            <h3 class="text-xl font-bold text-text mb-6">Neighbourhoods We Serve{{ $cityName ? ' in ' . $cityName : '' }}</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($data as $neighbourhood)
                    <div class="bg-cream border border-stone-light p-4 hover:border-forest/20 hover:shadow-md transition-all duration-300 animate-on-scroll" data-animation="fade-up" data-delay="{{ $loop->index * 30 }}">
                        <div class="flex items-center gap-3">
                            <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
                            <span class="text-sm font-semibold text-text">{{ $neighbourhood->name }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
