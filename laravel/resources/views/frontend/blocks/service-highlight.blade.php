@php
    $asset    = !empty($content['media_id']) ? ($mediaLookup[$content['media_id']] ?? null) : null;
    $features = array_filter(array_map('trim', explode("\n", $content['features'] ?? '')));
    $layout   = $content['layout'] ?? 'card';
@endphp
@if(!empty($content['heading']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-8">
    @if($layout === 'wide')
    {{-- Wide banner layout --}}
    <div class="bg-cream  overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2">
            @if($asset)
            <x-frontend.media :asset="$asset" :alt="$content['heading']" class="w-full h-64 md:h-full object-cover" />
            @endif
            <div class="p-6 md:p-10 flex flex-col justify-center">
                <h3 class="text-2xl font-bold text-text mb-3">{{ $content['heading'] }}</h3>
                @if(!empty($content['description']))
                <p class="text-text-secondary leading-relaxed mb-4">{{ $content['description'] }}</p>
                @endif
                @if(!empty($features))
                <ul class="space-y-2 mb-6">
                    @foreach($features as $feat)
                    <li class="flex items-center gap-2 text-sm text-text">
                        <i data-lucide="check-circle" class="w-4 h-4 text-forest shrink-0"></i>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>
                @endif
                @if(!empty($content['button_url']))
                <a href="{{ $content['button_url'] }}" class="inline-flex items-center gap-2 bg-forest text-white font-semibold px-6 py-3  hover:bg-black transition text-sm w-fit">
                    {{ $content['button_text'] ?? 'Learn More' }} <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
                @endif
            </div>
        </div>
    </div>

    @elseif($layout === 'minimal')
    {{-- Minimal layout --}}
    <div class="flex flex-col md:flex-row gap-6 items-start">
        @if($asset)
        <x-frontend.media :asset="$asset" :alt="$content['heading']" class="w-full md:w-48 aspect-video md:aspect-square object-cover shrink-0" />
        @endif
        <div>
            <h3 class="text-xl font-bold text-text mb-2">{{ $content['heading'] }}</h3>
            @if(!empty($content['description']))
            <p class="text-text-secondary leading-relaxed mb-3">{{ $content['description'] }}</p>
            @endif
            @if(!empty($features))
            <div class="flex flex-wrap gap-2 mb-4">
                @foreach($features as $feat)
                <span class="text-xs bg-forest/8 text-forest font-medium px-3 py-1 ">{{ $feat }}</span>
                @endforeach
            </div>
            @endif
            @if(!empty($content['button_url']))
            <a href="{{ $content['button_url'] }}" class="text-forest font-semibold text-sm hover:underline">{{ $content['button_text'] ?? 'Learn More' }} →</a>
            @endif
        </div>
    </div>

    @else
    {{-- Card layout --}}
    <div class="bg-white border border-stone  overflow-hidden shadow-sm hover:shadow-md transition-shadow max-w-md mx-auto">
        @if($asset)
        <x-frontend.media :asset="$asset" :alt="$content['heading']" class="w-full aspect-video object-cover" />
        @endif
        <div class="p-6">
            <h3 class="text-lg font-bold text-text mb-2">{{ $content['heading'] }}</h3>
            @if(!empty($content['description']))
            <p class="text-sm text-text-secondary leading-relaxed mb-4">{{ $content['description'] }}</p>
            @endif
            @if(!empty($features))
            <ul class="space-y-1.5 mb-5">
                @foreach($features as $feat)
                <li class="flex items-center gap-2 text-sm text-text">
                    <i data-lucide="check" class="w-3.5 h-3.5 text-forest shrink-0"></i>
                    {{ $feat }}
                </li>
                @endforeach
            </ul>
            @endif
            @if(!empty($content['button_url']))
            <a href="{{ $content['button_url'] }}" class="block text-center bg-forest text-white font-semibold px-5 py-2.5  hover:bg-black transition text-sm">
                {{ $content['button_text'] ?? 'Learn More' }}
            </a>
            @endif
        </div>
    </div>
    @endif
</div>
@endif
