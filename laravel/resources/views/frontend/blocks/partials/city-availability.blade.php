{{-- Block: city_availability — cities-we-serve grid --}}
@php
    $heading = !empty($content['heading']) ? $content['heading'] : 'Cities We Serve';
    $subtitle = $content['subtitle'] ?? '';
@endphp

@if(isset($data) && $data->isNotEmpty())
<div class="space-y-10">
    <div class="text-center mb-12">
        @if($heading)
            <h2 class="text-3xl lg:text-4xl font-bold text-text tracking-tight animate-on-scroll" data-animation="fade-up">
                {{ $heading }}
            </h2>
        @endif
        @if($subtitle)
            <p class="mt-4 text-text-secondary text-lg max-w-2xl mx-auto animate-on-scroll" data-animation="fade-up" data-delay="100">
                {{ $subtitle }}
            </p>
        @endif
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-5">
        @foreach($data as $idx => $city)
            @if($city->frontend_url)
            <a href="{{ $city->frontend_url }}"
               class="group relative bg-white border border-stone-light p-6 hover:border-forest/20 transition-all duration-500 hover:shadow-xl hover:-translate-y-0.5 animate-on-scroll"
               data-animation="fade-up"
               data-delay="{{ $idx * 40 }}">
                
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-forest/10 flex items-center justify-center shrink-0 group-hover:bg-forest transition-colors duration-300">
                        <i data-lucide="map-pin" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-300"></i>
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-text group-hover:text-forest transition-colors text-sm">{{ $city->name }}</h3>
                        @if($city->province)
                            <p class="text-xs text-text-secondary mt-0.5">{{ $city->province }}</p>
                        @endif
                    </div>
                </div>

                <div class="absolute top-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity">
                    <i data-lucide="arrow-up-right" class="w-4 h-4 text-forest/40"></i>
                </div>
            </a>
            @endif
        @endforeach
    </div>
</div>
@endif
