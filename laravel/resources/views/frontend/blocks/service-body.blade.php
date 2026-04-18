@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: service_body --}}
@php
    $body  = (isset($service) && is_array($service->service_body)) ? $service->service_body : [];
    $serviceName = isset($service) ? $service->name : 'Our Service';
    $phone = \App\Models\Setting::get('phone', '');
@endphp
@if(!empty($body))
<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 reveal">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            {{-- Main content --}}
            <div class="lg:col-span-2 space-y-10">
                @if(!empty($body['what_is']))
                <div>
                    <h2 class="text-h2 font-heading font-bold text-ink mb-4">What Is {{ $service->name }}?</h2>
                    <div class="text-text-secondary leading-relaxed text-lg">{{ $body['what_is'] }}</div>
                </div>
                @endif

                @if(!empty($body['benefits']))
                <div>
                    <h2 class="text-h2 font-heading font-bold text-ink mb-6">Benefits of {{ $service->name }}</h2>
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach($body['benefits'] as $benefit)
                        <li class="flex items-start gap-3 bg-white border border-stone p-4 hover:border-forest transition-all duration-500">
                            <i data-lucide="check-circle" class="w-5 h-5 text-forest shrink-0 mt-0.5"></i>
                            <span class="text-sm text-text">{{ $benefit }}</span>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if(!empty($body['materials']))
                <div>
                    <h2 class="text-h2 font-heading font-bold text-ink mb-6">Materials &amp; Options</h2>
                    <div class="flex flex-wrap gap-2">
                        @foreach($body['materials'] as $material)
                        <span class="bg-cream-warm text-text text-sm px-4 py-2  border border-stone">{{ $material }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                @if(!empty($body['pricing_note']))
                <div class="bg-forest/10 border border-forest/20 p-7">
                    <div class="flex items-center gap-3 mb-3">
                        <i data-lucide="dollar-sign" class="w-5 h-5 text-forest"></i>
                        <h3 class="text-lg font-bold text-ink">Pricing Information</h3>
                    </div>
                    <p class="text-text-secondary leading-relaxed">{{ $body['pricing_note'] }}</p>
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                <div class="bg-luxury-green-deep p-6 text-white sticky top-24">
                    <h3 class="text-lg font-bold mb-2">Book a {{ $service->name }} Consultation</h3>
                    <p class="text-white/70 text-sm mb-5">Licensed &amp; insured. 10-year warranty. Clear scope and material direction.</p>
                    <a href="{{ url('/contact') }}"
                       class="btn-luxury btn-luxury-white text-sm py-3.5 w-full mb-3">
                        Book a Consultation
                    </a>
                    @if($phone)
                    <a href="tel:{{ preg_replace('/[^+\d]/', '', $phone) }}"
                       class="flex items-center justify-center gap-2 text-white/70 hover:text-white text-sm transition">
                        <i data-lucide="phone" class="w-4 h-4"></i>{{ $phone }}
                    </a>
                    @endif
                </div>
                <div class="bg-cream border border-stone p-6">
                    <h3 class="text-sm font-bold text-ink mb-4">Our Guarantees</h3>
                    <ul class="space-y-3">
                        @foreach([['shield-check','10-Year Workmanship Warranty'],['badge-check','Licensed &amp; Fully Insured'],['clock','On-Time Delivery'],['star','5-Star Rated Service']] as [$icon,$text])
                        <li class="flex items-center gap-3 text-sm text-text-secondary">
                            <i data-lucide="{{ $icon }}" class="w-4 h-4 text-forest shrink-0"></i>{!! $text !!}
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
@endif
