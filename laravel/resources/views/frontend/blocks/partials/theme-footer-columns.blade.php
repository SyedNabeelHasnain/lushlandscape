@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $source = $content['source'] ?? 'settings';
    $configuredColumns = $theme->configuredFooterColumns();
    $servicesHeading = $content['services_heading'] ?? 'Services';
    $locationsHeading = $content['locations_heading'] ?? 'Locations';
    $companyHeading = $content['company_heading'] ?? 'Company';
    $phone = $theme->phone();
    $phoneClean = $theme->phoneClean();
@endphp

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
    @if($source === 'settings' && !empty($configuredColumns))
        @foreach($configuredColumns as $column)
            <div>
                <h3 class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-5 pb-3 border-b border-white/12">
                    {{ $column['heading'] ?? '' }}
                </h3>
                <ul class="space-y-3 text-sm text-white/70">
                    @if(($column['type'] ?? 'custom') === 'auto_services')
                        @foreach($theme->allFooterCategories() as $item)
                            <li><a href="{{ url('/services/' .  $item->slug  . '') }}" class="hover:text-white transition">{{ $item->name }}</a></li>
                        @endforeach
                    @elseif(($column['type'] ?? 'custom') === 'auto_cities')
                        @foreach($theme->allFooterCities() as $item)
                            <li><a href="{{ url('/professional-' .  $item->slug  . '') }}" class="hover:text-white transition">{{ $item->name }}</a></li>
                        @endforeach
                    @else
                        @foreach($column['links'] ?? [] as $link)
                            <li><a href="{{ $link['url'] ?? '#' }}" class="hover:text-white transition">{{ $link['label'] ?? '' }}</a></li>
                        @endforeach
                    @endif
                </ul>
            </div>
        @endforeach
    @else
        @if($content['show_services'] ?? true)
            <div>
                <h3 class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-5 pb-3 border-b border-white/12">{{ $servicesHeading }}</h3>
                <ul class="space-y-3 text-sm text-white/70">
                    @foreach($theme->allFooterCategories() as $item)
                        <li><a href="{{ url('/services/' .  $item->slug  . '') }}" class="hover:text-white transition">{{ $item->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($content['show_locations'] ?? true)
            <div>
                <h3 class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-5 pb-3 border-b border-white/12">{{ $locationsHeading }}</h3>
                <ul class="space-y-3 text-sm text-white/70">
                    @foreach($theme->allFooterCities() as $item)
                        <li><a href="{{ url('/professional-' .  $item->slug  . '') }}" class="hover:text-white transition">{{ $item->name }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($content['show_company'] ?? true)
            <div>
                <h3 class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-5 pb-3 border-b border-white/12">{{ $companyHeading }}</h3>
                <ul class="space-y-3 text-sm text-white/70">
                    <li><a href="{{ url('/about') }}" class="hover:text-white transition">About</a></li>
                    <li><a href="{{ url('/portfolio') }}" class="hover:text-white transition">Portfolio</a></li>
                    <li><a href="{{ url('/blog') }}" class="hover:text-white transition">Blog</a></li>
                    <li><a href="{{ url('/faqs') }}" class="hover:text-white transition">FAQs</a></li>
                    <li><a href="{{ url('/contact') }}" class="hover:text-white transition">Contact</a></li>
                </ul>

                @if(($content['show_call_panel'] ?? true) && $phone)
                    <div class="mt-6 rounded-[1.5rem] border border-white/12 bg-white/6 p-5">
                        <p class="text-[10px] uppercase tracking-[0.22em] text-white/45 mb-3">Call Direct</p>
                        <a href="tel:{{ $phoneClean }}" class="text-xl font-heading text-white hover:text-white/80 transition">{{ $phone }}</a>
                        <p class="mt-3 text-[11px] uppercase tracking-[0.16em] text-white/45">{{ $theme->weekdayHours() }}</p>
                        <p class="text-[11px] uppercase tracking-[0.16em] text-white/45">{{ $theme->weekendHours() }}</p>
                    </div>
                @endif
            </div>
        @endif
    @endif
</div>
