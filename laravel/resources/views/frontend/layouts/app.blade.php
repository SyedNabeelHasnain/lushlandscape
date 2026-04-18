<!DOCTYPE html>
@php
    $getSetting = fn($k, $d='') => $globalSettings[$k] ?? $d;
    $motionPreset = $getSetting('theme_motion_preset', 'refined');
@endphp
<html lang="en-CA" data-motion-preset="{{ $motionPreset }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('seo')

    @php
        $themeVarMap = [
            // Colors
            'theme_primary_color' => '--color-forest',
            'theme_primary_light' => '--color-forest-light',
            'theme_primary_dark' => '--color-forest-dark',
            'theme_accent_color' => '--color-accent',
            'theme_text_color' => '--color-text',
            'theme_text_secondary' => '--color-text-secondary',
            'theme_bg_primary' => '--color-bg-primary',
            'theme_bg_secondary' => '--color-cream',
            'theme_bg_dark' => '--color-bg-dark',
            'theme_border_color' => '--color-stone',
            'theme_link_color' => '--color-link',
            'theme_link_hover_color' => '--color-link-hover',
            'theme_text_on_light' => '--color-text-on-light',
            'theme_text_on_dark' => '--color-text-on-dark',
            'theme_surface_gradient_start' => '--surface-gradient-start',
            'theme_surface_gradient_end' => '--surface-gradient-end',
            'theme_surface_gradient_deep_start' => '--surface-gradient-deep-start',
            'theme_surface_gradient_deep_end' => '--surface-gradient-deep-end',
            'theme_btn_primary_bg' => '--color-btn-primary-bg',
            'theme_btn_primary_text' => '--color-btn-primary-text',
            'theme_btn_secondary_border' => '--color-btn-secondary-border',
            'theme_card_border_color' => '--color-card-border',
            'theme_card_hover_border_color' => '--color-card-border-hover',
            'theme_form_input_bg' => '--color-field-bg',
            'theme_form_input_border' => '--color-field-border',
            'theme_form_focus_color' => '--color-field-focus',
            'theme_section_padding' => '--spacing-section',
        ];
        $cssVars = '';
        foreach ($themeVarMap as $settingKey => $cssVar) {
            $val = $getSetting($settingKey, '');
            if ($val)
                $cssVars .= "{$cssVar}:{$val};";
        }
        $btnRadius = $getSetting('theme_btn_radius', '');
        if ($btnRadius)
            $cssVars .= "--radius-btn:{$btnRadius};";
        $cardRadius = $getSetting('theme_card_radius', '');
        if ($cardRadius)
            $cssVars .= "--radius-card:{$cardRadius};";
        $panelRadius = $getSetting('theme_panel_radius', '');
        if ($panelRadius)
            $cssVars .= "--radius-panel:{$panelRadius};";

        /* Typography System Overhaul — local self-hosted font families only */
        $availableHeadingFonts = [
            'Playfair Display' => '"Playfair Display", serif',
        ];
        $availableBodyFonts = [
            'Inter' => '"Inter", sans-serif',
        ];

        $headingFontKey = $getSetting('theme_heading_font', 'Playfair Display');
        $bodyFontKey = $getSetting('theme_body_font', 'Inter');
        $headingFontStack = $availableHeadingFonts[$headingFontKey] ?? $availableHeadingFonts['Playfair Display'];
        $bodyFontStack = $availableBodyFonts[$bodyFontKey] ?? $availableBodyFonts['Inter'];

        $baseSizeSetting = $getSetting('theme_base_size', 'default');
        $headingScaleSetting = $getSetting('theme_heading_scale', 'default');
        $spacingPreset = $getSetting('theme_letter_spacing', 'luxury');
        $weightBase = $getSetting('theme_font_weight_base', 'normal');
        $lineHeightSetting = $getSetting('theme_line_height', 'comfortable');
        $uppercaseUi = $getSetting('theme_uppercase_ui', '1') === '1';

        // Mappings
        $baseSizeMap = ['small' => '0.95rem', 'default' => '1rem', 'large' => '1.1rem'];
        $lineHeightMap = ['compact' => '1.4', 'comfortable' => '1.6', 'relaxed' => '1.8'];
        $weightMap = ['light' => '300', 'normal' => '400', 'medium' => '500', 'bold' => '700'];
        $spacingMap = ['tight' => '-0.02em', 'normal' => '0em', 'wide' => '0.1em', 'luxury' => '0.22em'];

        $h1Sizes = ['compact' => '5rem', 'default' => '6.5rem', 'spacious' => '8rem'];
        $h2Sizes = ['compact' => '2.8rem', 'default' => '4rem', 'spacious' => '5.6rem'];

        // Strict branding enforcements (Variables without !important)
        $cssVars .= "--font-heading:{$headingFontStack};";
        $cssVars .= "--font-sans:{$bodyFontStack};";
        $cssVars .= "--font-size-body-base:" . ($baseSizeMap[$baseSizeSetting] ?? '1rem') . ";";
        $cssVars .= "--line-height-body:" . ($lineHeightMap[$lineHeightSetting] ?? '1.6') . ";";
        $cssVars .= "--font-weight-body:" . ($weightMap[$weightBase] ?? '400') . ";";
        $cssVars .= "--tracking-nav:" . ($spacingMap[$spacingPreset] ?? '0.22em') . ";";
        $cssVars .= "--tracking-luxury:" . ($spacingMap['luxury']) . ";";
        $cssVars .= "--text-transform-ui:" . ($uppercaseUi ? 'uppercase' : 'none') . ";";

        $cssVars .= "--font-size-h1:" . ($h1Sizes[$headingScaleSetting] ?? '6.5rem') . ";";
        $cssVars .= "--font-size-h2:" . ($h2Sizes[$headingScaleSetting] ?? '4rem') . ";";

        $faviconMediaId = $getSetting('favicon_media_id', '');
        $faviconAsset = $faviconMediaId ? \App\Models\MediaAsset::find((int) $faviconMediaId) : null;

        $logoDesktopId = $getSetting('logo_desktop_media_id', '');
        $logoMobileId = $getSetting('logo_mobile_media_id', '');
        $logoDesktop = $logoDesktopId ? \App\Models\MediaAsset::find((int) $logoDesktopId) : null;
        $logoMobile = $logoMobileId ? \App\Models\MediaAsset::find((int) $logoMobileId) : null;
    @endphp

    @if($faviconAsset)
        <link rel="icon" href="{{ $faviconAsset->url }}" type="image/x-icon">
    @endif

    @if(!app()->environment('testing'))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style nonce="{{ Vite::cspNonce() }}">
        :root {
            {!! $cssVars !!}
        }

        /* Strict global resets for premium typography */
        html,
        body {
            font-family: var(--font-sans) !important;
            font-size: var(--font-size-body-base) !important;
            line-height: var(--line-height-body) !important;
            font-weight: var(--font-weight-body) !important;
            overflow-x: clip;
        }

        body {
            min-width: 320px;
        }

        main {
            overflow-x: clip;
        }

        img,
        picture,
        video,
        canvas,
        svg {
            max-width: 100%;
            height: auto;
        }

        iframe {
            max-width: 100%;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .font-heading,
        .display-font {
            font-family: var(--font-heading) !important;
            text-wrap: balance;
        }

        h1 {
            font-size: clamp(2.6rem, 8vw, var(--font-size-h1)) !important;
            line-height: 0.95 !important;
        }

        h2 {
            font-size: clamp(2.15rem, 6vw, var(--font-size-h2)) !important;
            line-height: 1.02 !important;
        }

        h3 {
            font-size: clamp(1.5rem, 4vw, var(--font-size-h3, 2rem)) !important;
            line-height: 1.14 !important;
        }

        .nav-link,
        .label-caps,
        .uppercase-ui,
        .tracking-luxury {
            text-transform: var(--text-transform-ui) !important;
            letter-spacing: var(--tracking-nav) !important;
            font-weight: 600 !important;
        }
    </style>
</head>

<body class="bg-white text-ink font-sans antialiased">
    <a href="#main-content"
        class="sr-only focus:not-sr-only focus:fixed focus:top-2 focus:left-2 focus:z-[999] focus:bg-forest focus:text-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold">Skip
        to main content</a>

    @php
        $phone = $getSetting('phone', '');
        $email = $getSetting('email', '');
        $fbUrl = $getSetting('facebook_url', '');
        $igUrl = $getSetting('instagram_url', '');
        $ytUrl = $getSetting('youtube_url', '');
        $googleBizUrl = $getSetting('google_business_url', '');
        $houzzUrl = $getSetting('houzz_url', '');
        $homestarsUrl = $getSetting('homestars_url', '');
        $siteName = $getSetting('site_name', 'Lush Landscape Service');
        $tagline = $getSetting('site_tagline', 'Premium landscaping construction contractors serving Ontario, Canada.');
        $googleRating = $getSetting('google_rating', '');
        $reviewCount = $getSetting('google_review_count', '');
        $phoneClean = preg_replace('/[^+\d]/', '', $phone);

        $navCtaText = $getSetting('nav_cta_text', 'Book a Consultation');
        $navCtaUrl = $getSetting('nav_cta_url', url('/contact'));
        $navShowPhone = $getSetting('nav_show_phone', '1') === '1';
        $navShowRating = $getSetting('nav_show_google_rating', '1') === '1';

        if (preg_match('/\\b(quote|estimate)\\b/i', $navCtaText)) {
            $navCtaText = 'Book a Consultation';
        }
        if (preg_match('/consultation/i', $navCtaUrl)) {
            $navCtaUrl = '/contact';
        }

        $footerBottomLinksRaw = $getSetting('footer_bottom_links_json', '');
        $footerBottomLinks = $footerBottomLinksRaw ? (json_decode($footerBottomLinksRaw, true) ?? []) : [];
        $footerColumnsRaw = $getSetting('footer_columns_json', '');
        $footerColumns = ($footerColumnsRaw && $footerColumnsRaw !== '[]') ? (json_decode($footerColumnsRaw, true) ?? []) : [];

        $linkIsAllowed = function ($link): bool {
            if (!is_array($link)) {
                return false;
            }

            $label = (string) ($link['label'] ?? '');
            $url = (string) ($link['url'] ?? '');

            if ($label !== '' && preg_match('/\\b(quote|estimate)\\b/i', $label)) {
                return false;
            }
            if ($url !== '' && preg_match('/consultation/i', $url)) {
                return false;
            }

            return true;
        };

        $footerBottomLinks = array_values(array_filter($footerBottomLinks, $linkIsAllowed));
        $footerColumns = array_values(array_map(function ($col) use ($linkIsAllowed) {
            if (!is_array($col)) {
                return $col;
            }
            if (isset($col['links']) && is_array($col['links'])) {
                $col['links'] = array_values(array_filter($col['links'], $linkIsAllowed));
            }
            return $col;
        }, $footerColumns));

        $announcementEnabled = $getSetting('announcement_bar_enabled', '0') === '1';
        $announcementText = $getSetting('announcement_bar_text', '');
        $announcementUrl = $getSetting('announcement_bar_url', '');
        $announcementBg = $getSetting('announcement_bar_bg_color', '#2d5a27');

        $cookieConsentEnabled = $getSetting('cookie_consent_enabled', '1') === '1';
        $cookieConsentText = $getSetting('cookie_consent_text', 'We use cookies to enhance your experience and analyse site traffic.');
        $cookieConsentLink = $getSetting('cookie_consent_link_url', '/privacy-policy');

        $footerLogoId = $getSetting('footer_logo_media_id', '');
        $footerLogo = $footerLogoId ? \App\Models\MediaAsset::find((int) $footerLogoId) : null;
        $footerTagline = $getSetting('footer_tagline', $tagline);
        $footerCopyright = $getSetting('footer_copyright_text', '© {year} ' . $siteName . '. All rights reserved. Licensed & Insured. Serving Ontario, Canada.');
        $footerCopyright = str_replace('{year}', date('Y'), $footerCopyright);
        $nlEnabled = $getSetting('footer_newsletter_enabled', '1') === '1';
        $nlHeading = $getSetting('footer_newsletter_heading', 'Landscape Insights & Project Planning');
        $nlSubtext = $getSetting('footer_newsletter_subtext', 'Join 2,000+ Ontario homeowners getting our free monthly newsletter.');
        $customFooter = $globalThemeFooter ?? null;
        $footerBlocks = $globalThemeFooterBlocks ?? collect();

        if (preg_match('/seasonal\\s+deals/i', $nlHeading)) {
            $nlHeading = 'Landscape Insights & Project Planning';
        }
    @endphp

    {{-- ── Announcement Bar ───────────────────────────────────────────────────── --}}
    @if($announcementEnabled && $announcementText)
        <div x-data="{ show: !sessionStorage.getItem('lush_announcement_dismissed') }" x-show="show" x-cloak
            style="background-color: {{ $announcementBg }}">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 py-2.5 flex items-center justify-center gap-3 text-white text-sm">
                @if($announcementUrl)
                    <a href="{{ $announcementUrl }}"
                        class="font-medium underline underline-offset-2 hover:no-underline flex-1 text-center">{{ $announcementText }}</a>
                @else
                    <span class="flex-1 text-center font-medium">{{ $announcementText }}</span>
                @endif
                <button x-on:click="show = false; sessionStorage.setItem('lush_announcement_dismissed', '1')"
                    class="shrink-0 p-1 hover:bg-white/20 transition" aria-label="Dismiss announcement">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </button>
            </div>
        </div>
    @endif

    @php
        $customHeader = $globalThemeHeader ?? null;
        $headerBlocks = $globalThemeHeaderBlocks ?? collect();
    @endphp

    @if($headerBlocks->isNotEmpty())
        <header class="site-header w-full relative z-[100]">
            @foreach($headerBlocks as $block)
                <x-frontend.block-renderer :block="$block" :context="[]" />
            @endforeach
        </header>
    @else
        <x-frontend.mega-nav :logoDesktop="$logoDesktop" :logoMobile="$logoMobile" :siteName="$siteName"
            :navCtaText="$navCtaText" :navCtaUrl="$navCtaUrl" :phone="$phone" :phoneClean="$phoneClean" />
    @endif

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    {{-- ── Newsletter Section (standalone, above footer) ───────────────────── --}}
    @if($nlEnabled && $footerBlocks->isEmpty())
        <section class="bg-forest">
            <div class="max-w-7xl mx-auto px-6 lg:px-12 py-20 lg:py-24">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-white/60 mb-4">Stay Updated</p>
                        <h3 class="text-white font-heading text-3xl lg:text-4xl font-bold leading-tight">{{ $nlHeading }}
                        </h3>
                        <p class="text-sm text-white/60 mt-4 leading-relaxed max-w-md">{{ $nlSubtext }}</p>
                    </div>
                    <div x-data="contactForm('newsletter-form', 'subscribe')" x-cloak>
                        <form id="newsletter-form" x-on:submit.prevent="submitForm()" class="flex gap-0">
                            <input type="hidden" name="source" value="footer_newsletter">
                            <label for="newsletter-email" class="sr-only">Email address for newsletter</label>
                            <input type="email" id="newsletter-email" name="email" autocomplete="email" required
                                placeholder="your@email.com" aria-label="Email address for newsletter"
                                class="flex-1 px-6 py-4 bg-white/6 border border-white/10 text-white placeholder-white/30 text-sm focus:outline-none focus:border-white/25 transition">
                            <button type="submit" :disabled="formSubmitting"
                                class="shrink-0 bg-white hover:bg-white/90 disabled:opacity-60 text-forest font-semibold px-8 py-4 text-[11px] tracking-[0.1em] uppercase transition flex items-center gap-2">
                                <i data-lucide="loader-2" x-show="formSubmitting" x-cloak class="w-4 h-4 animate-spin"></i>
                                <span x-text="formSubmitting ? '...' : 'Subscribe'">Subscribe</span>
                            </button>
                        </form>
                        <div x-show="formSuccess && formMessage" x-cloak class="mt-3 text-sm text-green-300 text-left"
                            x-text="formMessage"></div>
                        <div x-show="!formSuccess && formMessage" x-cloak class="mt-3 text-sm text-red-300 text-left"
                            x-text="formMessage"></div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ── Footer ─────────────────────────────────────────────────────────── --}}
    @if($footerBlocks->isNotEmpty())
        <footer class="site-footer w-full">
            @foreach($footerBlocks as $block)
                <x-frontend.block-renderer :block="$block" :context="[]" />
            @endforeach
        </footer>
    @else
        <footer class="bg-forest-dark text-white/60">
            @php
                if (empty($footerColumns)) {
                    $footerCities = $globalCities;
                    $footerCats = $globalServiceCategories;
                }
            @endphp

            <div class="h-px bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>

            <div class="max-w-7xl mx-auto px-6 lg:px-12 pt-20 lg:pt-28 pb-16 lg:pb-20">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-10">

                    {{-- Brand column --}}
                    <div class="lg:col-span-4">
                        <a href="{{ url('/') }}" class="inline-block mb-6">
                            @if($footerLogo)
                                <img src="{{ $footerLogo->url }}" alt="{{ $siteName }}" class="h-12 w-auto object-contain"
                                    height="48" loading="lazy" decoding="async">
                            @else
                                <span class="text-white font-heading text-2xl font-bold tracking-tight">{{ $siteName }}</span>
                            @endif
                        </a>
                        <p class="text-sm text-white/65 leading-relaxed max-w-sm">{{ $footerTagline }}</p>
                        @if($phone)
                            <a href="tel:{{ $phoneClean }}"
                                class="inline-flex items-center gap-3 mt-8 text-white font-heading text-xl font-bold hover:text-white/80 transition group">
                                <span
                                    class="w-10 h-10 bg-white/15 flex items-center justify-center shrink-0 group-hover:bg-white/25 transition">
                                    <i data-lucide="phone" class="w-4 h-4"></i>
                                </span>
                                {{ $phone }}
                            </a>
                        @endif
                        <div class="flex items-center gap-2.5 mt-8">
                            @if($fbUrl)<a href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer"
                                aria-label="Facebook"
                                class="w-9 h-9 bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all duration-300"><i
                            data-lucide="facebook" class="w-3.5 h-3.5 text-white/70"></i></a>@endif
                            @if($igUrl)<a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer"
                                aria-label="Instagram"
                                class="w-9 h-9 bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all duration-300"><i
                            data-lucide="instagram" class="w-3.5 h-3.5 text-white/70"></i></a>@endif
                            @if($ytUrl)<a href="{{ $ytUrl }}" target="_blank" rel="noopener noreferrer" aria-label="YouTube"
                                class="w-9 h-9 bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all duration-300"><i
                            data-lucide="youtube" class="w-3.5 h-3.5 text-white/70"></i></a>@endif
                            @if($googleBizUrl)<a href="{{ $googleBizUrl }}" target="_blank" rel="noopener noreferrer"
                                aria-label="Google Business"
                                class="w-9 h-9 bg-white/10 flex items-center justify-center hover:bg-white/20 transition-all duration-300"><i
                            data-lucide="search" class="w-3.5 h-3.5 text-white/70"></i></a>@endif
                        </div>
                        @if($houzzUrl || $homestarsUrl)
                            <div class="flex items-center gap-3 mt-4 text-xs text-white/25">
                                @if($houzzUrl)<a href="{{ $houzzUrl }}" target="_blank" rel="noopener noreferrer"
                                class="hover:text-white/50 transition link-underline">Houzz</a>@endif
                                @if($houzzUrl && $homestarsUrl)<span aria-hidden="true">&middot;</span>@endif
                                @if($homestarsUrl)<a href="{{ $homestarsUrl }}" target="_blank" rel="noopener noreferrer"
                                class="hover:text-white/50 transition link-underline">HomeStars</a>@endif
                            </div>
                        @endif
                    </div>

                    {{-- Link columns --}}
                    @if(empty($footerColumns))
                        <div class="lg:col-span-2">
                            <h3
                                class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-6 pb-3 border-b border-white/15">
                                Services</h3>
                            <ul class="space-y-3 text-sm">
                                @foreach($footerCats as $fc)
                                    <li><a href="{{ url('/services/' . $fc->slug_final) }}"
                                            class="hover:text-white transition">{{ $fc->name }}</a></li>
                                @endforeach
                                <li class="pt-1"><a href="{{ url('/services') }}"
                                        class="text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-[0.1em]">View
                                        All &rarr;</a></li>
                            </ul>
                        </div>

                        <div class="lg:col-span-2">
                            <h3
                                class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-6 pb-3 border-b border-white/15">
                                Locations</h3>
                            <ul class="space-y-3 text-sm">
                                @foreach($footerCities as $fc)
                                    <li><a href="{{ url('/landscaping-' . $fc->slug_final) }}"
                                            class="hover:text-white transition">{{ $fc->name }}</a></li>
                                @endforeach
                                <li class="pt-1"><a href="{{ url('/locations') }}"
                                        class="text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-[0.1em]">All
                                        Areas &rarr;</a></li>
                            </ul>
                        </div>

                        <div class="lg:col-span-2">
                            <h3
                                class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-6 pb-3 border-b border-white/15">
                                Company</h3>
                            <ul class="space-y-3 text-sm">
                                <li><a href="{{ url('/about') }}" class="hover:text-white transition">About Us</a></li>
                                <li><a href="{{ url('/portfolio') }}" class="hover:text-white transition">Portfolio</a></li>
                                <li><a href="{{ url('/blog') }}" class="hover:text-white transition">Blog</a></li>
                                <li><a href="{{ url('/faqs') }}" class="hover:text-white transition">FAQs</a></li>
                                <li><a href="{{ url('/contact') }}" class="hover:text-white transition">Contact Us</a></li>
                                <li><a href="{{ $navCtaUrl }}" class="hover:text-white transition">{{ $navCtaText }}</a></li>
                            </ul>
                            <div class="mt-8 p-5 bg-white/8 border border-white/15">
                                <p class="text-[11px] text-white/60 tracking-wide uppercase mb-1">
                                    {{ $getSetting('business_hours_weekday', 'Mon–Fri: 8am–6pm') }}</p>
                                <p class="text-[11px] text-white/60 tracking-wide uppercase mb-4">
                                    {{ $getSetting('business_hours_weekend', 'Sat: 9am–4pm') }}</p>
                                @if($phone)
                                    <a href="tel:{{ $phoneClean }}"
                                        class="btn-luxury btn-luxury-primary w-full text-[10px] py-3">Call Now</a>
                                @endif
                            </div>
                        </div>

                    @else
                        @foreach($footerColumns as $col)
                            <div class="lg:col-span-{{ floor(8 / max(count($footerColumns), 1)) }}">
                                <h3
                                    class="text-white text-[11px] font-semibold uppercase tracking-[0.2em] mb-6 pb-3 border-b border-white/15">
                                    {{ $col['heading'] ?? '' }}</h3>
                                <ul class="space-y-3 text-sm">
                                    @if(($col['type'] ?? 'custom') === 'auto_services')
                                        @foreach($globalServiceCategories as $fc)
                                            <li><a href="{{ url('/services/' . $fc->slug_final) }}"
                                                    class="hover:text-white transition">{{ $fc->name }}</a></li>
                                        @endforeach
                                        <li class="pt-1"><a href="{{ url('/services') }}"
                                                class="text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-[0.1em]">View
                                                All &rarr;</a></li>
                                    @elseif(($col['type'] ?? 'custom') === 'auto_cities')
                                        @foreach($globalCities as $fc)
                                            <li><a href="{{ url('/landscaping-' . $fc->slug_final) }}"
                                                    class="hover:text-white transition">{{ $fc->name }}</a></li>
                                        @endforeach
                                        <li class="pt-1"><a href="{{ url('/locations') }}"
                                                class="text-white/80 hover:text-white transition text-xs font-semibold uppercase tracking-[0.1em]">All
                                                Areas &rarr;</a></li>
                                    @else
                                        @foreach($col['links'] ?? [] as $link)
                                            <li><a href="{{ $link['url'] ?? '#' }}"
                                                    class="hover:text-white transition">{{ $link['label'] ?? '' }}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            </div>
                        @endforeach
                    @endif
                </div>

                {{-- Trust badges row --}}
                <div class="mt-16 pt-10 border-t border-white/10">
                    <div
                        class="flex flex-wrap items-center justify-center gap-8 lg:gap-14 text-[11px] text-white/50 tracking-wide uppercase">
                        <span class="flex items-center gap-2"><i data-lucide="shield-check"
                                class="w-4 h-4 text-white/70"></i>10-Year Warranty</span>
                        <span class="flex items-center gap-2"><i data-lucide="award" class="w-4 h-4 text-white/70"></i>CMHA
                            Certified</span>
                        <span class="flex items-center gap-2"><i data-lucide="file-check"
                                class="w-4 h-4 text-white/70"></i>$5M Insured</span>
                        <span class="flex items-center gap-2"><i data-lucide="hard-hat"
                                class="w-4 h-4 text-white/70"></i>WSIB Compliant</span>
                    </div>
                </div>

                {{-- Copyright bar --}}
                <div
                    class="mt-10 pt-8 border-t border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[11px] text-white/60 tracking-wide">{{ $footerCopyright }}</p>
                    @if(!empty($footerBottomLinks))
                        <div class="flex gap-6 text-[11px] text-white/60 tracking-wide">
                            @foreach($footerBottomLinks as $link)
                                <a href="{{ $link['url'] ?? '#' }}"
                                    class="hover:text-white/70 transition">{{ $link['label'] ?? '' }}</a>
                            @endforeach
                        </div>
                    @else
                        <div class="flex gap-6 text-[11px] text-white/60 tracking-wide">
                            <a href="{{ url('/privacy-policy') }}" class="hover:text-white/70 transition">Privacy Policy</a>
                            <a href="{{ url('/terms') }}" class="hover:text-white/70 transition">Terms &amp; Conditions</a>
                            <a href="{{ url('/sitemap.xml') }}" class="hover:text-white/70 transition">Sitemap</a>
                        </div>
                    @endif
                </div>
            </div>
        </footer>
    @endif

    {{-- Sticky mobile CTA --}}
    <div
        class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-forest/95 backdrop-blur-lg border-t border-white/10 px-4 py-3">
        <div class="grid grid-cols-2 gap-2.5">
            @if($phone)
                <a href="tel:{{ $phoneClean }}"
                    class="flex items-center justify-center gap-2 bg-white/10 border border-white/15 text-white font-medium py-3.5 text-[11px] tracking-[0.1em] uppercase transition">
                    <i data-lucide="phone" class="w-4 h-4"></i>Call Now
                </a>
            @else
                <a href="{{ url('/contact') }}"
                    class="flex items-center justify-center gap-2 bg-white/10 border border-white/15 text-white font-medium py-3.5 text-[11px] tracking-[0.1em] uppercase transition">
                    <i data-lucide="mail" class="w-4 h-4"></i>Contact
                </a>
            @endif
            <a href="{{ $navCtaUrl }}" class="btn-luxury btn-luxury-primary py-3.5 text-[11px]">
                <i data-lucide="clipboard-list" class="w-4 h-4"></i>{{ $navCtaText }}
            </a>
        </div>
    </div>
    <div class="h-16 lg:hidden" aria-hidden="true"></div>

    {{-- ── Cookie Consent Banner ─────────────────────────────────────────────── --}}
    @if($cookieConsentEnabled)
        <div x-data="cookieConsent()" x-init="init()" x-show="visible" x-cloak
            x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            class="fixed bottom-20 lg:bottom-8 left-4 right-4 lg:left-auto lg:right-8 lg:max-w-sm z-[190] bg-white border border-stone shadow-luxury p-8"
            role="dialog" aria-live="polite" aria-label="Cookie consent">
            <p class="text-sm text-text-secondary leading-relaxed mb-6">{{ $cookieConsentText }}</p>
            <div class="flex items-center gap-3">
                <button x-on:click="accept()" class="btn-luxury btn-luxury-primary flex-1 py-3 text-[10px]">Accept</button>
                <button x-on:click="decline()"
                    class="flex-1 bg-stone-light hover:bg-stone text-ink font-semibold py-3 px-4 text-[10px] tracking-[0.1em] uppercase transition">Decline</button>
            </div>
            @if($cookieConsentLink)
                <a href="{{ $cookieConsentLink }}" class="block mt-4 text-xs text-forest link-underline">Learn more</a>
            @endif
        </div>
    @endif

    {{-- ── Active Popups ─────────────────────────────────────────────────────── --}}
    <x-frontend.popup />

</body>

</html>
