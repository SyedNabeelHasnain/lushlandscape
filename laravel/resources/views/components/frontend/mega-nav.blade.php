@props([
    'logoDesktop'  => null,
    'logoMobile'   => null,
    'siteName'     => 'Lush Landscape Service',
    'navCtaText'   => 'Request Quote',
    'navCtaUrl'    => '/consultation',
    'phone'        => '',
    'phoneClean'   => '',
])

<header id="siteHeader" class="header-shell fixed top-0 inset-x-0 z-[100]"
    x-data="{ mobileOpen: false }"
    x-on:keydown.escape.window="mobileOpen = false">

    <div class="header-inner max-w-7xl mx-auto px-5 lg:px-12 py-5 lg:py-6 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ url('/') }}" class="flex items-center z-[110]" aria-label="{{ $siteName }} - Home">
            @if($logoDesktop)
            <img src="{{ $logoDesktop->url }}" alt="{{ $siteName }}" class="brand-logo hidden sm:block relative" width="200" height="56" loading="eager">
            @endif
            @if($logoMobile)
            <img src="{{ $logoMobile->url }}" alt="{{ $siteName }}" class="brand-logo block sm:hidden relative" width="150" height="40" loading="eager">
            @elseif($logoDesktop)
            <img src="{{ $logoDesktop->url }}" alt="{{ $siteName }}" class="brand-logo block sm:hidden relative" width="150" height="40" loading="eager">
            @endif
            @if(!$logoDesktop && !$logoMobile)
            <div class="hidden sm:block">
                <span class="text-forest font-heading text-2xl font-bold leading-none tracking-tight block">{{ $siteName }}</span>
                <span class="text-forest/40 text-[10px] tracking-[0.25em] uppercase mt-1 block">Luxury Landscaping</span>
            </div>
            <span class="sm:hidden text-forest font-heading text-xl font-bold tracking-tight">{{ $siteName }}</span>
            @endif
        </a>

        {{-- Desktop Navigation --}}
        <nav class="hidden lg:flex items-center gap-12" aria-label="Main Navigation">
            <a href="{{ url('/services') }}" class="nav-link text-xs font-semibold tracking-[0.2em] uppercase transition-all duration-300">Services</a>
            <a href="{{ url('/portfolio') }}" class="nav-link text-xs font-semibold tracking-[0.2em] uppercase transition-all duration-300">Portfolio</a>
            <a href="{{ url('/about-us') }}" class="nav-link text-xs font-semibold tracking-[0.2em] uppercase transition-all duration-300">Process</a>
            @if($phone)
            <a href="tel:{{ $phoneClean }}" class="hidden xl:flex items-center gap-2 text-forest/50 hover:text-forest text-[11px] tracking-[0.15em] transition-all duration-300">
                <i data-lucide="phone" class="w-3.5 h-3.5"></i>{{ $phone }}
            </a>
            @endif
            <a href="{{ $navCtaUrl }}" class="border border-forest text-forest hover:bg-forest hover:text-white transition-colors px-8 h-10 inline-flex items-center justify-center text-[10px] font-bold tracking-[0.15em] uppercase rounded-sm">
                {{ $navCtaText }}
            </a>
        </nav>

        {{-- Mobile Toggle --}}
        <div class="lg:hidden flex items-center gap-4 z-[110]">
            <button @click="mobileOpen = !mobileOpen" class="relative w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none group" aria-label="Toggle mobile menu" :aria-expanded="mobileOpen.toString()">
                <span class="w-6 h-[2px] bg-forest block transition-all duration-300 origin-center" :class="mobileOpen ? 'rotate-45 translate-y-[8px]' : ''"></span>
                <span class="w-6 h-[2px] bg-forest block transition-all duration-300" :class="mobileOpen ? 'opacity-0' : ''"></span>
                <span class="w-6 h-[2px] bg-forest block transition-all duration-300 origin-center" :class="mobileOpen ? '-rotate-45 -translate-y-[8px]' : ''"></span>
            </button>
        </div>
    </div>

    {{-- Mobile Menu Overlay --}}
    <div x-show="mobileOpen" 
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="opacity-0 -translate-y-full" 
         x-transition:enter-end="opacity-100 translate-y-0" 
         x-transition:leave="transition ease-in duration-400" 
         x-transition:leave-start="opacity-100 translate-y-0" 
         x-transition:leave-end="opacity-0 -translate-y-full" 
         class="fixed inset-0 bg-white z-[105] lg:hidden flex flex-col pt-24 px-6 pb-10 overflow-y-auto" x-cloak>
        <nav class="flex flex-col gap-8 mb-12">
            <a href="{{ url('/services') }}" @click="mobileOpen = false" class="text-3xl font-serif text-forest border-b border-stone/30 pb-4">Services</a>
            <a href="{{ url('/portfolio') }}" @click="mobileOpen = false" class="text-3xl font-serif text-forest border-b border-stone/30 pb-4">Portfolio</a>
            <a href="{{ url('/about-us') }}" @click="mobileOpen = false" class="text-3xl font-serif text-forest border-b border-stone/30 pb-4">Process</a>
        </nav>
        <div class="mt-auto flex flex-col gap-6">
            <a href="{{ $navCtaUrl }}" @click="mobileOpen = false" class="btn-solid h-14 flex items-center justify-center w-full text-xs font-bold tracking-[0.2em] uppercase rounded-sm bg-forest text-white">{{ $navCtaText }}</a>
            @if($phone)
            <a href="tel:{{ $phoneClean }}" class="text-center text-sm font-semibold tracking-widest text-forest/70 flex items-center justify-center gap-2">
                <i data-lucide="phone" class="w-4 h-4"></i> {{ $phone }}
            </a>
            @endif
        </div>
    </div>
</header>
