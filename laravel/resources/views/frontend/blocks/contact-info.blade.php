@php
    $style = $content['style'] ?? 'horizontal';
    $tone = $content['tone'] ?? 'light';
    $panelShell = $tone === 'dark'
        ? 'rounded-[2rem] bg-luxury-green-deep border border-white/10 text-white'
        : 'rounded-[2rem] bg-white border border-stone shadow-luxury text-ink';
    $panelSub = $tone === 'dark' ? 'text-white/68' : 'text-text-secondary';
@endphp
@if(!empty($content['phone']) || !empty($content['email']) || !empty($content['address']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-16">
    @if(!empty($content['heading']))
    <h2 class="text-h3 font-heading font-bold {{ $tone === 'dark' && $style === 'panel' ? 'text-white' : 'text-forest' }} mb-10 {{ $style === 'card' ? 'text-center' : '' }}">{{ $content['heading'] }}</h2>
    @endif

    @if($style === 'card')
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-4xl mx-auto reveal-stagger">
        @if(!empty($content['phone']))
        <a href="tel:{{ $content['phone'] }}" class="bg-white border border-stone p-8 flex items-center gap-5 hover:border-forest/20 hover:shadow-luxury transition-all duration-500 group">
            <div class="w-14 h-14 bg-forest/6 flex items-center justify-center shrink-0 group-hover:bg-forest transition-all duration-500">
                <i data-lucide="phone" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-500"></i>
            </div>
            <div>
                <p class="text-[10px] text-text-secondary mb-1 tracking-[0.15em] uppercase">Phone</p>
                <p class="font-semibold text-ink text-sm">{{ $content['phone'] }}</p>
            </div>
        </a>
        @endif
        @if(!empty($content['email']))
        <a href="mailto:{{ $content['email'] }}" class="bg-white border border-stone p-8 flex items-center gap-5 hover:border-forest/20 hover:shadow-luxury transition-all duration-500 group">
            <div class="w-14 h-14 bg-forest/6 flex items-center justify-center shrink-0 group-hover:bg-forest transition-all duration-500">
                <i data-lucide="mail" class="w-5 h-5 text-forest group-hover:text-white transition-colors duration-500"></i>
            </div>
            <div>
                <p class="text-[10px] text-text-secondary mb-1 tracking-[0.15em] uppercase">Email</p>
                <p class="font-semibold text-ink text-sm">{{ $content['email'] }}</p>
            </div>
        </a>
        @endif
        @if(!empty($content['address']))
        <div class="bg-white border border-stone p-8 flex items-center gap-5">
            <div class="w-14 h-14 bg-forest/6 flex items-center justify-center shrink-0">
                <i data-lucide="map-pin" class="w-5 h-5 text-forest"></i>
            </div>
            <div>
                <p class="text-[10px] text-text-secondary mb-1 tracking-[0.15em] uppercase">Address</p>
                <p class="font-semibold text-ink text-sm">{{ $content['address'] }}</p>
            </div>
        </div>
        @endif
    </div>
    @if(!empty($content['hours']))
    <div class="mt-8 text-center">
        <p class="text-sm text-text-secondary whitespace-pre-line leading-relaxed">{{ $content['hours'] }}</p>
    </div>
    @endif

    @elseif($style === 'vertical')
    <div class="space-y-5 max-w-md">
        @if(!empty($content['phone']))
        <div class="flex items-center gap-4">
            <i data-lucide="phone" class="w-5 h-5 text-forest shrink-0"></i>
            <a href="tel:{{ $content['phone'] }}" class="text-ink hover:text-forest transition-colors duration-300">{{ $content['phone'] }}</a>
        </div>
        @endif
        @if(!empty($content['email']))
        <div class="flex items-center gap-4">
            <i data-lucide="mail" class="w-5 h-5 text-forest shrink-0"></i>
            <a href="mailto:{{ $content['email'] }}" class="text-ink hover:text-forest transition-colors duration-300">{{ $content['email'] }}</a>
        </div>
        @endif
        @if(!empty($content['address']))
        <div class="flex items-start gap-4">
            <i data-lucide="map-pin" class="w-5 h-5 text-forest shrink-0 mt-0.5"></i>
            <p class="text-text-secondary whitespace-pre-line leading-relaxed">{{ $content['address'] }}</p>
        </div>
        @endif
        @if(!empty($content['hours']))
        <div class="flex items-start gap-4">
            <i data-lucide="clock" class="w-5 h-5 text-forest shrink-0 mt-0.5"></i>
            <p class="text-text-secondary whitespace-pre-line leading-relaxed">{{ $content['hours'] }}</p>
        </div>
        @endif
    </div>

    @elseif($style === 'panel')
    <div class="grid gap-6 md:grid-cols-3 {{ $panelShell }} p-8 lg:p-10">
        @if(!empty($content['phone']))
        <div class="space-y-3">
            <p class="text-[10px] uppercase tracking-[0.2em] {{ $panelSub }}">Phone</p>
            <a href="tel:{{ $content['phone'] }}" class="text-xl font-heading hover:opacity-80 transition">{{ $content['phone'] }}</a>
        </div>
        @endif
        @if(!empty($content['email']))
        <div class="space-y-3">
            <p class="text-[10px] uppercase tracking-[0.2em] {{ $panelSub }}">Email</p>
            <a href="mailto:{{ $content['email'] }}" class="text-xl font-heading hover:opacity-80 transition">{{ $content['email'] }}</a>
        </div>
        @endif
        @if(!empty($content['address']))
        <div class="space-y-3">
            <p class="text-[10px] uppercase tracking-[0.2em] {{ $panelSub }}">Address</p>
            <p class="leading-relaxed whitespace-pre-line">{{ $content['address'] }}</p>
        </div>
        @endif
        @if(!empty($content['hours']))
        <div class="md:col-span-3 pt-2 border-t {{ $tone === 'dark' ? 'border-white/10' : 'border-stone' }}">
            <p class="text-sm {{ $panelSub }} whitespace-pre-line leading-relaxed">{{ $content['hours'] }}</p>
        </div>
        @endif
    </div>
    @else
    <div class="flex flex-wrap gap-8 md:gap-12">
        @if(!empty($content['phone']))
        <a href="tel:{{ $content['phone'] }}" class="flex items-center gap-3 text-ink hover:text-forest transition-colors duration-300">
            <i data-lucide="phone" class="w-5 h-5 text-forest"></i>
            <span class="font-medium">{{ $content['phone'] }}</span>
        </a>
        @endif
        @if(!empty($content['email']))
        <a href="mailto:{{ $content['email'] }}" class="flex items-center gap-3 text-ink hover:text-forest transition-colors duration-300">
            <i data-lucide="mail" class="w-5 h-5 text-forest"></i>
            <span class="font-medium">{{ $content['email'] }}</span>
        </a>
        @endif
        @if(!empty($content['address']))
        <div class="flex items-center gap-3 text-text-secondary">
            <i data-lucide="map-pin" class="w-5 h-5 text-forest"></i>
            <span>{{ $content['address'] }}</span>
        </div>
        @endif
    </div>
    @if(!empty($content['hours']))
    <p class="mt-4 text-sm text-text-secondary whitespace-pre-line leading-relaxed">{{ $content['hours'] }}</p>
    @endif
    @endif
</div>
@endif
