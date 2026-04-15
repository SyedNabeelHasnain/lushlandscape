@php
    $features = $content['features'] ?? [];
    $cols     = ($content['columns'] ?? '2') === '2' ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1';
    $variant = $content['variant'] ?? 'editorial';
@endphp
@if(!empty($features))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['eyebrow']))
    <p class="text-[10px] font-semibold uppercase tracking-[0.2em] text-text-secondary mb-4">{{ $content['eyebrow'] }}</p>
    @endif
    @if(!empty($content['heading']))
    <h2 class="text-h3 font-heading font-bold tracking-tight text-forest mb-10">{{ $content['heading'] }}</h2>
    @endif
    <div class="grid {{ $cols }} gap-8 reveal-stagger">
        @foreach($features as $feat)
        <div class="flex gap-5 {{ $variant === 'editorial' ? 'rounded-[1.5rem] border border-stone-light bg-white p-6' : '' }}">
            <div class="w-12 h-12 {{ $variant === 'editorial' ? 'bg-forest text-white' : 'bg-forest/6' }} flex items-center justify-center shrink-0">
                <i data-lucide="{{ $feat['icon'] ?? 'check' }}" class="w-5 h-5 {{ $variant === 'editorial' ? 'text-white' : 'text-forest' }}"></i>
            </div>
            <div>
                @if(!empty($feat['title']))
                <h3 class="font-heading font-bold text-ink mb-2">{{ $feat['title'] }}</h3>
                @endif
                @if(!empty($feat['description']))
                <p class="text-sm text-text-secondary leading-relaxed">{{ $feat['description'] }}</p>
                @endif
            </div>
        </div>
        @endforeach
    </div>
</div>
@endif
