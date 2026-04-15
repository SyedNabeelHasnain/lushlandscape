@php $plans = $content['plans'] ?? []; @endphp
@if(!empty($plans))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-10">
    @if(!empty($content['heading']))
    <div class="text-center mb-10">
        <h2 class="text-3xl font-heading font-bold text-text">{{ $content['heading'] }}</h2>
        @if(!empty($content['subtitle']))
        <p class="mt-2 text-text-secondary">{{ $content['subtitle'] }}</p>
        @endif
    </div>
    @endif
    <div class="grid grid-cols-1 md:grid-cols-{{ min(count($plans), 3) }} gap-6 items-stretch">
        @foreach($plans as $plan)
        @php
            $highlighted = !empty($plan['highlighted']) && strtolower($plan['highlighted']) === 'yes';
            $features = array_filter(array_map('trim', explode("\n", $plan['features'] ?? '')));
        @endphp
        <div class="relative border {{ $highlighted ? 'border-forest ring-2 ring-forest/20 shadow-lg' : 'border-stone' }} bg-white p-6 md:p-8 flex flex-col hover:shadow-luxury transition-shadow">
            @if($highlighted)
            <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-forest text-white text-xs font-bold px-4 py-1 tracking-wider uppercase">Popular</span>
            @endif
            @if(!empty($plan['name']))
            <h3 class="text-lg font-bold text-text">{{ $plan['name'] }}</h3>
            @endif
            @if(!empty($plan['description']))
            <p class="text-sm text-text-secondary mt-1">{{ $plan['description'] }}</p>
            @endif
            @if(!empty($plan['price']))
            <div class="mt-4 mb-6">
                <span class="text-4xl font-bold text-text">{{ $plan['price'] }}</span>
                @if(!empty($plan['period']))
                <span class="text-sm text-text-secondary ml-1">/ {{ $plan['period'] }}</span>
                @endif
            </div>
            @endif
            @if(!empty($features))
            <ul class="space-y-2.5 mb-8 flex-1">
                @foreach($features as $feat)
                <li class="flex items-start gap-2.5 text-sm text-text-secondary">
                    <i data-lucide="check" class="w-4 h-4 text-forest shrink-0 mt-0.5"></i>
                    {{ $feat }}
                </li>
                @endforeach
            </ul>
            @endif
            @if(!empty($plan['button_url']))
            <a href="{{ $plan['button_url'] }}"
               class="{{ $highlighted ? 'btn-luxury btn-luxury-primary' : 'btn-luxury border border-forest text-forest hover:bg-forest hover:text-white' }} text-center mt-auto block">
                {{ $plan['button_text'] ?? 'Get Started' }}
            </a>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif
