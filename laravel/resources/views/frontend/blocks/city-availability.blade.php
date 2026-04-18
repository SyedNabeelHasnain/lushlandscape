@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: city_availability (used on service pages) --}}
@php
    $availHeading = !empty($section['settings']['heading'])
        ? $section['settings']['heading']
        : ((isset($service) ? $service->name : 'Our Services') . ' - Cities We Serve');
@endphp
@if(isset($cityPages) && $cityPages->count() > 0)
<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12">
        <div class="text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-heading font-bold tracking-tight text-text">{{ $availHeading }}</h2>
            <p class="mt-3 text-text-secondary">Select your city for location-specific pricing and availability.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
            @foreach($cityPages as $cp)
            <a href="{{ $cp->frontend_url }}"
               class="group bg-white  p-4 text-center hover:border-forest/20 hover:shadow-md border border-stone transition-all duration-300"
              >
                <i data-lucide="map-pin" class="w-5 h-5 text-forest mx-auto mb-2 group-hover:scale-110 transition-transform"></i>
                <span class="text-sm font-semibold text-text group-hover:text-forest transition-colors">{{ $cp->city->name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif
