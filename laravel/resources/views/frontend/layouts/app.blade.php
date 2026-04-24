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
    
    <x-frontend.data-layer />
    
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />

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

<body class="bg-bg-primary text-text font-sans antialiased selection:bg-forest selection:text-white">
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
        $siteName = $getSetting('site_name', 'Super WMS Service');
        $tagline = $getSetting('site_tagline', 'Premium professional construction contractors serving Our Region, Canada.');
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
        $footerCopyright = $getSetting('footer_copyright_text', '© {year} ' . $siteName . '. All rights reserved. Licensed & Insured. Serving Our Region, Canada.');
        $footerCopyright = str_replace('{year}', date('Y'), $footerCopyright);
        $nlEnabled = $getSetting('footer_newsletter_enabled', '1') === '1';
        $nlHeading = $getSetting('footer_newsletter_heading', 'Landscape Insights & Project Planning');
        $nlSubtext = $getSetting('footer_newsletter_subtext', 'Join 2,000+ Our Region homeowners getting our free monthly newsletter.');
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
            :navCtaText="$navCtaText" :navCtaUrl="$navCtaUrl" :phone="$phone" :phoneClean="$phoneClean" :services="$globalServiceCategories" />
    @endif

    <main id="main-content" tabindex="-1">
        @yield('content')
    </main>

    {{-- ── Footer ─────────────────────────────────────────────────────────── --}}
    @if($footerBlocks->isNotEmpty())
        <footer class="site-footer w-full">
            @foreach($footerBlocks as $block)
                <x-frontend.block-renderer :block="$block" :context="[]" />
            @endforeach
        </footer>
    @else
        <footer class="bg-forest pt-16 lg:pt-24 pb-8 lg:pb-10 px-5 lg:px-12 text-white border-t border-white/10">
            <div class="max-w-7xl mx-auto grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12 text-white/90 text-sm border-b border-white/10 pb-12 lg:pb-16 mb-8 lg:mb-10">
                <div class="sm:col-span-2 lg:pr-8">
                    @if($footerLogo)
                        <img src="{{ $footerLogo->url }}" alt="{{ $siteName }}" class="h-8 mb-6 lg:mb-8 w-auto brightness-0 invert opacity-90" loading="lazy" width="200" height="32">
                    @elseif($logoDesktop)
                        <img src="{{ $logoDesktop->url }}" alt="{{ $siteName }}" class="h-8 mb-6 lg:mb-8 w-auto brightness-0 invert opacity-90" loading="lazy" width="200" height="32">
                    @else
                        <span class="text-white font-heading text-2xl font-bold tracking-tight block mb-6 lg:mb-8">{{ $siteName }}</span>
                    @endif
                    <p class="max-w-md leading-[1.8] font-light text-white/90">The Region's technical authority for luxury outdoor construction. We specialize in high-ticket hardscaping, structural masonry, and complete outdoor living environments, backed by a 10-year workmanship warranty.</p>
                </div>
                <div>
                    <h3 class="text-white text-[10px] lg:text-[11px] font-bold uppercase tracking-[0.2em] mb-4 lg:mb-6">Signature Disciplines</h3>
                    <ul class="space-y-3 lg:space-y-4 font-light text-white/90">
                        @foreach($globalServiceCategories->take(4) as $cat)
                        <li><a href="{{ url('/services/' . $cat->slug) }}" class="hover:text-white transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="text-white text-[10px] lg:text-[11px] font-bold uppercase tracking-[0.2em] mb-4 lg:mb-6">Primary Enclaves</h3>
                    <ul class="space-y-3 lg:space-y-4 font-light text-white/90">
                        <li>Lorne Park & Mineola</li>
                        <li>Joshua Creek & Morrison</li>
                        <li>Shoreacres & Roseland</li>
                        <li>The Bridle Path & Forest Hill</li>
                    </ul>
                </div>
            </div>
            <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center text-[9px] lg:text-[10px] uppercase tracking-[0.2em] text-white/70 gap-4 lg:gap-6">
                <p class="text-center md:text-left">&copy; {{ date('Y') }} {{ $siteName }}. All Rights Reserved.</p>
                <div class="flex items-center gap-4 lg:gap-8 flex-wrap justify-center">
                    <a href="{{ url('/privacy-policy') }}" class="hover:text-white transition-colors">Privacy</a>
                    <span class="hidden sm:inline">|</span>
                    <a href="{{ url('/terms-of-service') }}" class="hover:text-white transition-colors">Terms</a>
                    <div class="flex gap-4 lg:gap-5 text-white/90 text-sm md:border-l border-white/20 md:pl-8 mt-2 md:mt-0">
                        @if($igUrl)<a href="{{ $igUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i data-lucide="instagram" class="w-4 h-4 hover:text-white transition-all cursor-pointer"></i></a>@endif
                        @if($fbUrl)<a href="{{ $fbUrl }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i data-lucide="facebook" class="w-4 h-4 hover:text-white transition-all cursor-pointer"></i></a>@endif
                    </div>
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

    {{-- Cookie Consent ────────────────────────────────────────────────────── --}}
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
