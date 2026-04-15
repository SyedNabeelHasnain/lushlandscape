@php $asset = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null; @endphp
@if(!empty($content['name']))
<div class="max-w-sm mx-auto px-4 py-8">
    <div class="bg-white border border-stone overflow-hidden hover:border-forest/20 hover:shadow-luxury transition-all duration-500 text-center reveal">
        @if($asset)
        <div class="overflow-hidden">
            <img src="{{ $asset->url }}" alt="{{ $content['name'] }}" class="w-full aspect-square object-cover img-zoom" loading="lazy">
        </div>
        @else
        <div class="w-full aspect-square bg-cream flex items-center justify-center">
            <i data-lucide="user" class="w-16 h-16 text-stone"></i>
        </div>
        @endif
        <div class="p-8">
            <h3 class="text-lg font-heading font-bold text-ink">{{ $content['name'] }}</h3>
            @if(!empty($content['role']))
            <p class="text-sm text-forest font-medium mt-1">{{ $content['role'] }}</p>
            @endif
            @if(!empty($content['bio']))
            <p class="text-sm text-text-secondary mt-4 leading-relaxed">{{ $content['bio'] }}</p>
            @endif
            @if(!empty($content['phone']) || !empty($content['email']))
            <div class="mt-6 pt-6 border-t border-stone flex items-center justify-center gap-5">
                @if(!empty($content['phone']))
                <a href="tel:{{ $content['phone'] }}" class="text-text-secondary hover:text-forest transition-colors duration-300" aria-label="Call {{ $content['name'] }}">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                </a>
                @endif
                @if(!empty($content['email']))
                <a href="mailto:{{ $content['email'] }}" class="text-text-secondary hover:text-forest transition-colors duration-300" aria-label="Email {{ $content['name'] }}">
                    <i data-lucide="mail" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endif
