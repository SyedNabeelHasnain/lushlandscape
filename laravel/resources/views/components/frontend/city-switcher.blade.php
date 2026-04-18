@props([
    'currentCity'  => null,
    'allCities'    => null,
    'serviceSlug'  => null,
    'mode'         => 'service-city',
])

@if($allCities && $allCities->count() > 1)
@php
    $currentId = $currentCity?->id;
@endphp
<div x-data="{ open: false }" class="relative" x-on:click.outside="open = false" x-on:keydown.escape.window="open = false">
    <button type="button"
            x-on:click="open = !open"
            class="w-full flex items-center justify-between gap-2 bg-white border border-stone px-4 py-3 text-sm text-ink hover:border-forest transition group"
            aria-haspopup="listbox" :aria-expanded="open">
        <span class="flex items-center gap-2">
            <i data-lucide="map-pin" class="w-4 h-4 text-forest shrink-0"></i>
            <span class="font-medium">{{ $currentCity?->name ?? 'Select City' }}</span>
        </span>
        <i data-lucide="chevron-down" class="w-4 h-4 text-text-secondary group-hover:text-forest transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 -translate-y-1"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-1"
         class="absolute z-20 mt-1 w-full bg-white border border-stone shadow-editorial overflow-hidden"
         role="listbox" aria-label="Switch city">

        <div class="max-h-64 overflow-y-auto">
            @foreach($allCities as $city)
            @php
                $isCurrent = $city->id === $currentId;
                if ($mode === 'service-city' && $serviceSlug) {
                    $href = '/' . $serviceSlug . '-' . $city->slug_final;
                } else {
                    $href = '/professional-' . $city->slug_final;
                }
            @endphp
            <a href="{{ $href }}"
               role="option"
               @if($isCurrent) aria-selected="true" @endif
               class="flex items-center gap-3 px-4 py-2.5 text-sm transition {{ $isCurrent ? 'bg-forest/8 text-forest font-semibold' : 'text-text-secondary hover:bg-cream hover:text-forest' }}">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 shrink-0 {{ $isCurrent ? 'text-forest' : 'text-stone' }}"></i>
                <span>{{ $city->name }}</span>
                @if($isCurrent)
                <i data-lucide="check" class="w-3.5 h-3.5 ml-auto text-forest"></i>
                @endif
            </a>
            @endforeach
        </div>
    </div>
</div>
@endif
