@extends('admin.layouts.app')
@section('title', 'Settings')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Settings" subtitle="Manage all site-wide configuration, branding, and features">
    <x-admin.import-export-buttons table="settings" />
</x-admin.page-header>

@php
    $tabDefs = [
        'general'       => ['icon' => 'settings',        'label' => 'General'],
        'trust'         => ['icon' => 'star',             'label' => 'Trust & Social'],
        'social'        => ['icon' => 'share-2',          'label' => 'Social Links'],
        'integrations'  => ['icon' => 'plug',             'label' => 'Integrations'],
        'seo'           => ['icon' => 'search',           'label' => 'SEO'],
        'theme'         => ['icon' => 'palette',          'label' => 'Theme & Branding'],
        'navigation'    => ['icon' => 'navigation',       'label' => 'Navigation'],
        'footer'        => ['icon' => 'layout-panel-top', 'label' => 'Footer'],
        'notifications' => ['icon' => 'bell',             'label' => 'Notifications'],
        'features'      => ['icon' => 'toggle-right',     'label' => 'Features'],
        'ai'            => ['icon' => 'bot',              'label' => 'AI Content'],
    ];
    // Tooltip map keyed by setting key for dynamic loop tabs
    $settingTooltips = [
        // General
        'site_name'              => 'Your business name used in the site title, Schema.org markup, and the footer copyright line.',
        'site_tagline'           => 'Short tagline displayed in the browser tab title suffix and used in Schema.org organization markup.',
        'site_email'             => 'Primary contact email shown in the header, footer, and used in Schema.org LocalBusiness structured data.',
        'site_phone'             => 'Primary phone number displayed in the header, footer, and used in Schema.org LocalBusiness structured data.',
        'site_address'           => 'Full street address for Schema.org LocalBusiness markup and GEO SEO signals.',
        'site_city'              => 'City component of the business address. Used in LocalBusiness structured data.',
        'site_province'          => 'Province component of the business address. Used in LocalBusiness structured data.',
        'site_postal_code'       => 'Postal code component of the business address. Used in LocalBusiness structured data.',
        'site_country'           => 'Country component of the business address. Used in LocalBusiness structured data.',
        'site_latitude'          => 'GPS latitude of the business location for geo-targeting and LocalBusiness structured data.',
        'site_longitude'         => 'GPS longitude of the business location for geo-targeting and LocalBusiness structured data.',
        'google_maps_embed_url'  => 'Google Maps embed URL shown in the contact page map section.',
        // Trust
        'trust_google_rating'    => 'Google review rating displayed in the top bar and trust badges. Example: 4.9',
        'trust_google_count'     => 'Total number of Google reviews to display alongside the rating.',
        'trust_years_experience' => 'Years in business figure shown in trust badges and the about section.',
        'trust_projects_count'   => 'Total completed projects figure shown in stats bars and trust sections.',
        'trust_cities_count'     => 'Number of cities served shown in trust badges and stats sections.',
        'trust_google_place_id'  => 'Google Place ID for your business. Used to link directly to your Google reviews page.',
        // Social
        'social_facebook'        => 'Full Facebook page URL. Used for OpenGraph sameAs structured data linking your social profiles to your business entity.',
        'social_instagram'       => 'Full Instagram profile URL. Used for OpenGraph sameAs structured data.',
        'social_twitter'         => 'Full Twitter/X profile URL. Used for OpenGraph sameAs structured data.',
        'social_linkedin'        => 'Full LinkedIn company page URL. Used for OpenGraph sameAs structured data.',
        'social_youtube'         => 'Full YouTube channel URL. Used for OpenGraph sameAs structured data.',
        'social_houzz'           => 'Full Houzz profile URL. Used for OpenGraph sameAs structured data and trust signals.',
        'social_yelp'            => 'Full Yelp business page URL. Used for OpenGraph sameAs structured data.',
        'social_homestars'       => 'Full HomeStars profile URL. Used for OpenGraph sameAs structured data (Canada-specific trust signal).',
        // Integrations
        'google_analytics_id'    => 'Google Analytics 4 measurement ID (e.g. G-XXXXXXXXXX). Enables traffic and conversion tracking.',
        'google_tag_manager_id'  => 'Google Tag Manager container ID (e.g. GTM-XXXXXXX). Manages all tracking scripts centrally.',
        'google_search_console'  => 'Google Search Console verification meta tag content value. Paste only the content attribute value.',
        'recaptcha_site_key'     => 'Google reCAPTCHA v3 site key used in public-facing forms for bot protection.',
        'recaptcha_secret_key'   => 'Google reCAPTCHA v3 secret key used server-side to verify form submissions.',
        // SEO
        'seo_default_title_suffix' => 'Suffix appended to every page title in search results, e.g. | Super WMS Service. Keep it short.',
        'seo_default_og_image_id'  => 'Default Open Graph image used when a page has no specific OG image set. Should be at least 1200×630px.',
        'seo_robots_txt'           => 'Custom robots.txt content. Controls which pages search engines can crawl. Use with caution.',
        'seo_canonical_domain'     => 'The canonical domain for all URLs (e.g. https://example.com). Used in canonical link tags.',
        // AI Content
        'openai_api_key'           => 'Your OpenAI API key. Required to enable AI content generation. Keep this confidential.',
        'openai_model'             => 'OpenAI model to use for content generation, e.g. gpt-4o, gpt-4o-mini. Higher-tier models produce better content.',
        'openai_temperature'       => 'Controls creativity (0.0 = deterministic, 1.0 = creative). Recommended: 0.7 for marketing content.',
        'ai_features_enabled'      => 'Master toggle for AI content generation features across the admin panel.',
        'ai_context_markdown'      => 'Markdown document describing your website structure, brand voice, and keyword strategy. This is included as context in every AI generation request.',
    ];
@endphp

<form method="POST" action="{{ route('admin.settings.update') }}"
      x-data="{ tab: window.location.hash.replace('#','') || 'general' }"
      data-ajax-form="true" data-success-message="Settings saved successfully.">
    @csrf

    {{-- Tab Nav --}}
    <div class="bg-white rounded-2xl border border-gray-200 mb-6 overflow-hidden">
        <div class="grid grid-cols-2 sm:flex sm:items-center sm:min-w-max">
            @foreach($tabDefs as $key => $def)
            @if($settings->has($key))
            <button type="button"
                    x-on:click="tab = '{{ $key }}'; window.location.hash = '{{ $key }}'"
                    class="flex items-center justify-center gap-2 px-4 py-3 text-sm font-medium whitespace-nowrap border-b border-r border-gray-100 transition-colors sm:justify-start sm:px-5 sm:py-4 sm:border-b-2 sm:border-r-0"
                    :class="tab === '{{ $key }}' ? 'border-forest text-forest' : 'border-transparent text-text-secondary hover:text-text hover:border-gray-300'">
                <i data-lucide="{{ $def['icon'] }}" class="w-4 h-4"></i>
                {{ $def['label'] }}
            </button>
            @endif
            @endforeach
        </div>
    </div>

    {{-- ===== GENERAL ===== --}}
    <div x-show="tab === 'general'" x-cloak>
        <x-admin.card title="General Settings">
            <div class="space-y-5">
                @foreach($settings->get('general', collect()) as $s)
                    @if($s->type === 'textarea')
                        <x-admin.form-textarea :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :rows="3" :tooltip="$settingTooltips[$s->key] ?? 'Site-wide setting: ' . ($s->label ?? $s->key)" />
                    @else
                        <x-admin.form-input :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :tooltip="$settingTooltips[$s->key] ?? 'Site-wide setting: ' . ($s->label ?? $s->key)" />
                    @endif
                @endforeach
            </div>
        </x-admin.card>
    </div>

    {{-- ===== TRUST & SOCIAL PROOF ===== --}}
    <div x-show="tab === 'trust'" x-cloak>
        <x-admin.card title="Trust & Social Proof">
            <div class="space-y-5">
                @foreach($settings->get('trust', collect()) as $s)
                    <x-admin.form-input :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :tooltip="$settingTooltips[$s->key] ?? 'Trust signal: ' . ($s->label ?? $s->key) . '. Displayed in trust badges and stats sections across the site.'" />
                @endforeach
            </div>
        </x-admin.card>
    </div>

    {{-- ===== SOCIAL LINKS ===== --}}
    <div x-show="tab === 'social'" x-cloak>
        <x-admin.card title="Social & Directory Links">
            <div class="space-y-5">
                @foreach($settings->get('social', collect()) as $s)
                    <x-admin.form-input :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :tooltip="$settingTooltips[$s->key] ?? 'Full profile URL for ' . ($s->label ?? $s->key) . '. Used for OpenGraph sameAs structured data to link your social profiles to your business entity.'" />
                @endforeach
            </div>
        </x-admin.card>
    </div>

    {{-- ===== INTEGRATIONS ===== --}}
    <div x-show="tab === 'integrations'" x-cloak>
        <x-admin.card title="Third-Party Integrations">
            <div class="space-y-5">
                @foreach($settings->get('integrations', collect()) as $s)
                    <x-admin.form-input :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :tooltip="$settingTooltips[$s->key] ?? 'Integration key for ' . ($s->label ?? $s->key) . '. Keep this value confidential. Do not share publicly.'" />
                @endforeach
            </div>
        </x-admin.card>
    </div>

    {{-- ===== SEO ===== --}}
    <div x-show="tab === 'seo'" x-cloak>
        <x-admin.card title="SEO Defaults">
            <div class="space-y-5">
                @foreach($settings->get('seo', collect()) as $s)
                    @if($s->type === 'textarea')
                        <x-admin.form-textarea :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :rows="4" :tooltip="$settingTooltips[$s->key] ?? 'SEO default setting: ' . ($s->label ?? $s->key) . '. Applied to all pages that do not have this field set individually.'" />
                    @else
                        <x-admin.form-input :name="$s->key" :label="$s->label ?? $s->key" :value="$s->value ?? ''" :tooltip="$settingTooltips[$s->key] ?? 'SEO default setting: ' . ($s->label ?? $s->key) . '. Applied to all pages that do not have this field set individually.'" />
                    @endif
                @endforeach
            </div>
        </x-admin.card>
    </div>

    {{-- ===== THEME & BRANDING ===== --}}
    <div x-show="tab === 'theme'" x-cloak>
        @php
            $themeSettings = $settings->get('theme', collect())->keyBy('key');
            $brandColorKeys = [
                'theme_primary_color',
                'theme_primary_light',
                'theme_primary_dark',
                'theme_accent_color',
                'theme_bg_primary',
                'theme_bg_secondary',
                'theme_bg_dark',
                'theme_border_color',
            ];
            $surfaceColorKeys = [
                'theme_text_color',
                'theme_text_secondary',
                'theme_text_on_light',
                'theme_text_on_dark',
                'theme_link_color',
                'theme_link_hover_color',
            ];
        @endphp

        <div class="space-y-6">
            <x-admin.card title="Logos & Favicon">
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @php
                        $logoTooltips = [
                            'logo_desktop_media_id' => 'Desktop logo displayed in the site header on screens wider than mobile. Recommended: SVG or PNG with transparent background.',
                            'logo_mobile_media_id'  => 'Compact logo used in the mobile header. Should be a simplified or square version of the main logo.',
                            'favicon_media_id'      => 'Small icon shown in browser tabs and bookmarks. Should be a square image, ideally 512×512px.',
                        ];
                    @endphp
                    @foreach(['logo_desktop_media_id' => 'Desktop Logo', 'logo_mobile_media_id' => 'Mobile Logo', 'favicon_media_id' => 'Favicon'] as $key => $lbl)
                    @if($themeSettings->has($key))
                    <div>
                        <x-admin.form-media :name="$key" :label="$lbl" :mediaAsset="$mediaAssets[$key] ?? null" :tooltip="$logoTooltips[$key] ?? ''" />
                    </div>
                    @endif
                    @endforeach
                </div>
            </x-admin.card>

            <x-admin.card title="Brand Colors">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($brandColorKeys as $key)
                    @php $s = $themeSettings[$key] ?? null; @endphp
                    @if($s)
                    <div x-data="{ color: '{{ $s->value ?? '#000000' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">{{ $s->label }}</label>
                        <p class="text-xs text-text-secondary mb-1.5">Core theme token used by the live frontend at runtime. Changes apply across the design system without hardcoded template edits.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="{{ $s->key }}" x-model="color"
                                   placeholder="#2d5a27"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </x-admin.card>

            <x-admin.card title="Surface & Contrast">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @foreach($surfaceColorKeys as $key)
                        @php $s = $themeSettings[$key] ?? null; @endphp
                        @if($s)
                        <div x-data="{ color: '{{ $s->value ?? '#000000' }}' }">
                            <label class="block text-sm font-medium text-text mb-1.5">{{ $s->label }}</label>
                            <p class="text-xs text-text-secondary mb-1.5">Shared contrast token used for light surfaces, dark gradient sections, links, and supporting text.</p>
                            <div class="flex items-center gap-3">
                                <input type="color" x-model="color"
                                       class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                                <input type="text" name="{{ $s->key }}" x-model="color"
                                       class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                            </div>
                        </div>
                        @endif
                    @endforeach

                    @if($themeSettings->has('theme_surface_gradient_start'))
                    <x-admin.form-input
                        name="theme_surface_gradient_start"
                        label="Section Gradient Start"
                        :value="$themeSettings['theme_surface_gradient_start']->value ?? 'rgba(21, 56, 35, 0.60)'"
                        help="Example: rgba(21, 56, 35, 0.60)"
                        tooltip="Top color stop used for luxury green gradient sections and hero overlays." />
                    @endif

                    @if($themeSettings->has('theme_surface_gradient_end'))
                    <x-admin.form-input
                        name="theme_surface_gradient_end"
                        label="Section Gradient End"
                        :value="$themeSettings['theme_surface_gradient_end']->value ?? 'rgba(21, 56, 35, 0.40)'"
                        help="Example: rgba(21, 56, 35, 0.40)"
                        tooltip="Bottom color stop used for luxury green gradient sections and hero overlays." />
                    @endif

                    @if($themeSettings->has('theme_surface_gradient_deep_start'))
                    <x-admin.form-input
                        name="theme_surface_gradient_deep_start"
                        label="Deep Gradient Start"
                        :value="$themeSettings['theme_surface_gradient_deep_start']->value ?? 'rgba(21, 56, 35, 0.92)'"
                        help="Example: rgba(21, 56, 35, 0.92)"
                        tooltip="Stronger start tone used for darker split panels, premium contact sections, and deep gradient surfaces." />
                    @endif

                    @if($themeSettings->has('theme_surface_gradient_deep_end'))
                    <x-admin.form-input
                        name="theme_surface_gradient_deep_end"
                        label="Deep Gradient End"
                        :value="$themeSettings['theme_surface_gradient_deep_end']->value ?? 'rgba(22, 56, 35, 0.78)'"
                        help="Example: rgba(22, 56, 35, 0.78)"
                        tooltip="Stronger end tone used for darker split panels, premium contact sections, and deep gradient surfaces." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Typography">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                    {{-- Font Families --}}
                    <x-admin.form-select name="theme_heading_font" label="Heading Font Family (Serif)" 
                        :value="$themeSettings['theme_heading_font']->value ?? 'Playfair Display'"
                        :options="['Playfair Display' => 'Playfair Display']"
                        help="Self-hosted locally for performance and consistent rendering."
                        tooltip="Luxury display font used for H1-H6 headings. This project is design-locked to the locally hosted Playfair Display family." />

                    <x-admin.form-select name="theme_body_font" label="Body Font Family (Sans)" 
                        :value="$themeSettings['theme_body_font']->value ?? 'Inter'"
                        :options="['Inter' => 'Inter']"
                        help="Self-hosted locally for performance and consistent rendering."
                        tooltip="Clean font used for body text, UI, and navigation. This project is design-locked to the locally hosted Inter family." />

                    {{-- Scale & Size --}}
                    <x-admin.form-select name="theme_base_size" label="Base Font Size" 
                        :value="$themeSettings['theme_base_size']->value ?? 'default'"
                        :options="['small' => 'Small (0.95rem)', 'default' => 'Default (1rem)', 'large' => 'Large (1.1rem)']"
                        tooltip="Controls the overall base text size across the platform." />

                    <x-admin.form-select name="theme_heading_scale" label="Heading Scale" 
                        :value="$themeSettings['theme_heading_scale']->value ?? 'default'"
                        :options="['compact' => 'Compact', 'default' => 'Default', 'spacious' => 'Spacious']"
                        tooltip="Adjusts the visual hierarchy and size of all headings." />

                    {{-- Spacing & Height --}}
                    <x-admin.form-select name="theme_letter_spacing" label="Letter Spacing Preset" 
                        :value="$themeSettings['theme_letter_spacing']->value ?? 'luxury'"
                        :options="['tight' => 'Tight', 'normal' => 'Normal', 'wide' => 'Wide', 'luxury' => 'Luxury (Ultra-Wide)']"
                        tooltip="Controls character spacing for navigation and labels." />

                    <x-admin.form-select name="theme_line_height" label="Line Height" 
                        :value="$themeSettings['theme_line_height']->value ?? 'comfortable'"
                        :options="['compact' => 'Compact', 'comfortable' => 'Comfortable', 'relaxed' => 'Relaxed']"
                        tooltip="Controls the vertical space between lines of text." />

                    {{-- Weight & Styling --}}
                    <x-admin.form-select name="theme_font_weight_base" label="Base Font Weight" 
                        :value="$themeSettings['theme_font_weight_base']->value ?? 'normal'"
                        :options="['light' => 'Light (300)', 'normal' => 'Regular (400)', 'medium' => 'Medium (500)', 'bold' => 'Bold (700)']"
                        tooltip="Sets the default weight for body and UI text." />

                    <div>
                        <label class="flex items-center gap-3 cursor-pointer mt-8">
                            <input type="hidden" name="theme_uppercase_ui" value="0">
                            <input type="checkbox" name="theme_uppercase_ui" value="1"
                                   {{ ($themeSettings['theme_uppercase_ui']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Uppercase Labels & Navigation</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Standardize labels and nav items to uppercase for a premium feel.</p>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Buttons & Calls To Action">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($themeSettings->has('theme_btn_primary_bg'))
                    <div x-data="{ color: '{{ $themeSettings['theme_btn_primary_bg']->value ?? '#1E4A2D' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Primary Button Background</label>
                        <p class="text-xs text-text-secondary mb-1.5">Default solid CTA background used across buttons and form submits.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_btn_primary_bg" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @if($themeSettings->has('theme_btn_primary_text'))
                    <div x-data="{ color: '{{ $themeSettings['theme_btn_primary_text']->value ?? '#FFFFFF' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Primary Button Text</label>
                        <p class="text-xs text-text-secondary mb-1.5">Text color used inside primary CTA buttons.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_btn_primary_text" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @if($themeSettings->has('theme_btn_secondary_border'))
                    <div x-data="{ color: '{{ $themeSettings['theme_btn_secondary_border']->value ?? '#1E4A2D' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Secondary Button Border</label>
                        <p class="text-xs text-text-secondary mb-1.5">Border tone used by outline and ghost button treatments.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_btn_secondary_border" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @if($themeSettings->has('theme_btn_radius'))
                    <x-admin.form-input name="theme_btn_radius" label="Button Border Radius" :value="$themeSettings['theme_btn_radius']->value ?? '0.75rem'" help="e.g. 0.5rem (slight), 0.75rem (rounded), 9999px (pill)" tooltip="CSS border-radius value applied to all buttons site-wide. Use 0 for square, 0.5rem for slight rounding, 0.75rem for rounded, or 9999px for pill-shaped buttons." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Cards & Panels">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($themeSettings->has('theme_card_radius'))
                    <x-admin.form-input
                        name="theme_card_radius"
                        label="Card Radius"
                        :value="$themeSettings['theme_card_radius']->value ?? '0rem'"
                        help="Example: 0rem, 0.75rem, 1rem"
                        tooltip="Default radius for editorial cards, service cards, and similar bordered content cards." />
                    @endif
                    @if($themeSettings->has('theme_panel_radius'))
                    <x-admin.form-input
                        name="theme_panel_radius"
                        label="Panel Radius"
                        :value="$themeSettings['theme_panel_radius']->value ?? '2rem'"
                        help="Example: 1.5rem, 2rem"
                        tooltip="Default radius for larger composed surfaces such as contact panels and glass shells." />
                    @endif
                    @if($themeSettings->has('theme_card_border_color'))
                    <div x-data="{ color: '{{ $themeSettings['theme_card_border_color']->value ?? '#DBDEDA' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Card Border Color</label>
                        <p class="text-xs text-text-secondary mb-1.5">Default border color for editorial cards and neutral content panels.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_card_border_color" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @if($themeSettings->has('theme_card_hover_border_color'))
                    <div x-data="{ color: '{{ $themeSettings['theme_card_hover_border_color']->value ?? '#A47148' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Card Hover Border Color</label>
                        <p class="text-xs text-text-secondary mb-1.5">Accent border used when premium cards lift or become interactive.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_card_hover_border_color" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Forms & Inputs">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($themeSettings->has('theme_form_input_bg'))
                    <div x-data="{ color: '{{ $themeSettings['theme_form_input_bg']->value ?? '#F8F8F6' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Field Background</label>
                        <p class="text-xs text-text-secondary mb-1.5">Default background used for inputs, selects, textareas, and choice panels.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_form_input_bg" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @if($themeSettings->has('theme_form_input_border'))
                    <div x-data="{ color: '{{ $themeSettings['theme_form_input_border']->value ?? '#DADDD8' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Field Border</label>
                        <p class="text-xs text-text-secondary mb-1.5">Neutral border used around form controls and structured choice options.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_form_input_border" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                    @if($themeSettings->has('theme_form_focus_color'))
                    <div x-data="{ color: '{{ $themeSettings['theme_form_focus_color']->value ?? '#A47148' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Field Focus Accent</label>
                        <p class="text-xs text-text-secondary mb-1.5">Highlight color used when fields are focused, verified, or selected.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="theme_form_focus_color" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Layout Rhythm & Motion">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($themeSettings->has('theme_section_padding'))
                    <x-admin.form-input
                        name="theme_section_padding"
                        label="Section Vertical Padding"
                        :value="$themeSettings['theme_section_padding']->value ?? 'clamp(4.5rem, 8vw, 10rem)'"
                        help="Example: clamp(4.5rem, 8vw, 10rem)"
                        tooltip="Global section spacing token used by editorial page sections throughout the frontend." />
                    @endif
                    @if($themeSettings->has('theme_motion_preset'))
                    <x-admin.form-select
                        name="theme_motion_preset"
                        label="Motion Preset"
                        :value="$themeSettings['theme_motion_preset']->value ?? 'refined'"
                        :options="['none' => 'None', 'subtle' => 'Subtle', 'refined' => 'Refined', 'cinematic' => 'Cinematic']"
                        tooltip="Controls how assertive scroll reveals and hero transitions feel across the site." />
                    @endif
                </div>
            </x-admin.card>
        </div>
    </div>

    {{-- ===== NAVIGATION ===== --}}
    <div x-show="tab === 'navigation'" x-cloak>
        @php $navSettings = $settings->get('navigation', collect())->keyBy('key'); @endphp

        <div class="space-y-6">
            <x-admin.card title="Header CTA Button">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($navSettings->has('nav_cta_text'))
                    <x-admin.form-input name="nav_cta_text" label="CTA Button Text" :value="$navSettings['nav_cta_text']->value ?? 'Book a Consultation'" tooltip="Text displayed on the primary call-to-action button in the site header. Keep it short and action-oriented, e.g. Book a Consultation." />
                    @endif
                    @if($navSettings->has('nav_cta_url'))
                    <x-admin.form-input name="nav_cta_url" label="CTA Button URL" :value="$navSettings['nav_cta_url']->value ?? '/contact'" tooltip="URL the header CTA button links to. Use a relative path like /contact or an absolute URL." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Top Bar Display">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($navSettings->has('nav_show_phone'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="nav_show_phone" value="0">
                            <input type="checkbox" name="nav_show_phone" value="1"
                                   {{ ($navSettings['nav_show_phone']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Show Phone Number in Top Bar</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Displays the site phone number in the top announcement bar for easy visitor access.</p>
                    </div>
                    @endif
                    @if($navSettings->has('nav_show_google_rating'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="nav_show_google_rating" value="0">
                            <input type="checkbox" name="nav_show_google_rating" value="1"
                                   {{ ($navSettings['nav_show_google_rating']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Show Google Rating in Top Bar</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Displays the Google star rating and review count in the top bar as a trust signal.</p>
                    </div>
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Mega Menu Settings">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    @if($navSettings->has('nav_max_services'))
                    <x-admin.form-input name="nav_max_services" type="number" label="Max Services Per Category" :value="$navSettings['nav_max_services']->value ?? '5'" tooltip="Maximum number of individual services to show under each category column in the mega menu. Increase if you have many services per category; decrease to keep the menu compact." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Custom Navigation Items (JSON)">
                @if($navSettings->has('nav_items_json'))
                <div class="space-y-3">
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <p class="text-xs text-blue-700 font-medium mb-2">JSON Format: leave <code class="font-mono">[]</code> to use the auto-generated Services dropdown from service categories.</p>
                        <pre class="text-xs text-blue-600 overflow-x-auto">{{ '[{"type":"link","label":"Portfolio","url":"/portfolio"},{"type":"services_dropdown","label":"Services"},{"type":"dropdown","label":"Company","items":[{"label":"About","url":"/about"},{"label":"Contact","url":"/contact"}]}]' }}</pre>
                    </div>
                    <p class="text-xs text-text-secondary">Custom navigation structure in JSON. Leave as <code class="font-mono">[]</code> to use the auto-generated mega menu built from service categories and cities.</p>
                    <textarea name="nav_items_json" rows="10"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">{{ $navSettings['nav_items_json']->value ?? '[]' }}</textarea>
                </div>
                @endif
            </x-admin.card>
        </div>
    </div>

    {{-- ===== FOOTER ===== --}}
    <div x-show="tab === 'footer'" x-cloak>
        @php $footerSettings = $settings->get('footer', collect())->keyBy('key'); @endphp

        <div class="space-y-6">
            <x-admin.card title="Footer Logo & Branding">
                <div class="space-y-5">
                    @if($footerSettings->has('footer_logo_media_id'))
                    <x-admin.form-media
                        name="footer_logo_media_id"
                        label="Footer Logo"
                        :mediaAsset="$mediaAssets['footer_logo_media_id'] ?? null"
                        help="Usually a light/white version of the main logo. Leave blank to show site name text."
                        tooltip="Logo displayed in the site footer. Recommended: SVG or PNG with transparent background, white/light version." />
                    @endif
                    @if($footerSettings->has('footer_tagline'))
                    <x-admin.form-textarea name="footer_tagline" label="Footer Tagline" :value="$footerSettings['footer_tagline']->value ?? ''" :rows="2" tooltip="Short tagline or description displayed beneath the logo in the footer. Reinforce your brand promise in one or two sentences." />
                    @endif
                    @if($footerSettings->has('footer_copyright_text'))
                    <x-admin.form-input name="footer_copyright_text" label="Copyright Text" :value="$footerSettings['footer_copyright_text']->value ?? ''" help="Use {year} to insert the current year automatically." tooltip="Copyright notice displayed in the footer bottom bar. Use {year} as a placeholder and it will be replaced with the current year automatically." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Newsletter Signup">
                @php $nlEnabled = ($footerSettings['footer_newsletter_enabled']->value ?? '1') === '1'; @endphp
                <div class="space-y-5">
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="footer_newsletter_enabled" value="0">
                            <input type="checkbox" name="footer_newsletter_enabled" value="1"
                                   {{ $nlEnabled ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Show Newsletter Signup Section in Footer</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Displays an email capture section at the bottom of the footer for newsletter signups.</p>
                    </div>
                    @if($footerSettings->has('footer_newsletter_heading'))
                    <x-admin.form-input name="footer_newsletter_heading" label="Newsletter Heading" :value="$footerSettings['footer_newsletter_heading']->value ?? ''" tooltip="Heading text displayed above the newsletter signup form in the footer. Should be compelling and action-oriented." />
                    @endif
                    @if($footerSettings->has('footer_newsletter_subtext'))
                    <x-admin.form-input name="footer_newsletter_subtext" label="Newsletter Subtext" :value="$footerSettings['footer_newsletter_subtext']->value ?? ''" tooltip="Subtext displayed below the newsletter heading. Briefly explain what subscribers will receive or how often you send emails." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Footer Columns (JSON)">
                @if($footerSettings->has('footer_columns_json'))
                <div class="space-y-3">
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <p class="text-xs text-blue-700 font-medium mb-2">JSON Format: leave <code class="font-mono">[]</code> to use auto-generated Services and Locations columns.</p>
                        <pre class="text-xs text-blue-600 overflow-x-auto">{{ '[{"heading":"Services","type":"auto_services"},{"heading":"Locations","type":"auto_cities"},{"heading":"Company","type":"custom","links":[{"label":"About","url":"/about"},{"label":"Portfolio","url":"/portfolio"}]}]' }}</pre>
                    </div>
                    <p class="text-xs text-text-secondary">Define footer link columns in JSON. Use type: auto_services or auto_cities to auto-populate from the database, or type: custom with a links array for manual entries.</p>
                    <textarea name="footer_columns_json" rows="10"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">{{ $footerSettings['footer_columns_json']->value ?? '[]' }}</textarea>
                </div>
                @endif
            </x-admin.card>

            <x-admin.card title="Footer Bottom Links (JSON)">
                @if($footerSettings->has('footer_bottom_links_json'))
                <div class="space-y-3">
                    <div class="p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <p class="text-xs text-blue-700 font-medium mb-2">Array of <code class="font-mono">&#123;"label":"...", "url":"..."&#125;</code> objects shown in the footer bottom bar.</p>
                    </div>
                    <p class="text-xs text-text-secondary">Links shown in the very bottom bar of the footer, e.g. Privacy Policy, Terms of Service, Sitemap. Each object needs a label and url.</p>
                    <textarea name="footer_bottom_links_json" rows="5"
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-xs font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">{{ $footerSettings['footer_bottom_links_json']->value ?? '[]' }}</textarea>
                </div>
                @endif
            </x-admin.card>
        </div>
    </div>

    {{-- ===== NOTIFICATIONS ===== --}}
    <div x-show="tab === 'notifications'" x-cloak>
        @php $notifSettings = $settings->get('notifications', collect())->keyBy('key'); @endphp

        <div class="space-y-6">
            <x-admin.card title="Announcement Bar">
                <div class="space-y-5">
                    @if($notifSettings->has('announcement_bar_enabled'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="announcement_bar_enabled" value="0">
                            <input type="checkbox" name="announcement_bar_enabled" value="1"
                                   {{ ($notifSettings['announcement_bar_enabled']->value ?? '0') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Show Announcement Bar at Top of Site</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Visitors can dismiss it; it won't reappear during the same browser session.</p>
                    </div>
                    @endif
                    @if($notifSettings->has('announcement_bar_text'))
                    <x-admin.form-input name="announcement_bar_text" label="Announcement Message" :value="$notifSettings['announcement_bar_text']->value ?? ''" tooltip="The message text displayed in the announcement bar at the top of every page. Use for promotions, seasonal offers, or important notices." />
                    @endif
                    @if($notifSettings->has('announcement_bar_url'))
                    <x-admin.form-input name="announcement_bar_url" label="Link URL (optional)" :value="$notifSettings['announcement_bar_url']->value ?? ''" help="If set, the message becomes a clickable link." tooltip="Optional URL the announcement message links to. If set, the entire announcement bar becomes clickable. Use a relative or absolute URL." />
                    @endif
                    @if($notifSettings->has('announcement_bar_bg_color'))
                    <div x-data="{ color: '{{ $notifSettings['announcement_bar_bg_color']->value ?? '#2d5a27' }}' }">
                        <label class="block text-sm font-medium text-text mb-1.5">Background Color</label>
                        <p class="text-xs text-text-secondary mb-1.5">Background color of the announcement bar. Use your brand forest green or a contrasting accent color.</p>
                        <div class="flex items-center gap-3">
                            <input type="color" x-model="color"
                                   class="w-12 h-10 rounded-lg border border-gray-200 cursor-pointer p-1 bg-white">
                            <input type="text" name="announcement_bar_bg_color" x-model="color"
                                   class="flex-1 px-3 py-2 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                        </div>
                    </div>
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Cookie Consent Banner">
                <div class="space-y-5">
                    @if($notifSettings->has('cookie_consent_enabled'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="cookie_consent_enabled" value="0">
                            <input type="checkbox" name="cookie_consent_enabled" value="1"
                                   {{ ($notifSettings['cookie_consent_enabled']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Show Cookie Consent Banner</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Displays a cookie consent notice to comply with PIPEDA and GDPR. Required if you use analytics or tracking cookies.</p>
                    </div>
                    @endif
                    @if($notifSettings->has('cookie_consent_text'))
                    <x-admin.form-textarea name="cookie_consent_text" label="Banner Text" :value="$notifSettings['cookie_consent_text']->value ?? ''" :rows="3" tooltip="The message displayed in the cookie consent banner. Should briefly explain what cookies are used for and link to your privacy policy." />
                    @endif
                    @if($notifSettings->has('cookie_consent_link_url'))
                    <x-admin.form-input name="cookie_consent_link_url" label="Privacy / Cookie Policy URL" :value="$notifSettings['cookie_consent_link_url']->value ?? '/privacy-policy'" tooltip="URL of your privacy or cookie policy page. Linked from the cookie consent banner so visitors can read the full policy." />
                    @endif
                </div>
            </x-admin.card>
        </div>
    </div>

    {{-- ===== FEATURES ===== --}}
    <div x-show="tab === 'features'" x-cloak>
        @php $featureSettings = $settings->get('features', collect())->keyBy('key'); @endphp
        <div class="space-y-6">
            <x-admin.card title="Search">
                <div class="space-y-5">
                    @if($featureSettings->has('search_enabled'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="search_enabled" value="0">
                            <input type="checkbox" name="search_enabled" value="1"
                                   {{ ($featureSettings['search_enabled']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Enable Site Search</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Master toggle for the live search feature. When disabled, the search bar and search API are both hidden.</p>
                    </div>
                    @endif
                    @if($featureSettings->has('search_show_in_header'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="search_show_in_header" value="0">
                            <input type="checkbox" name="search_show_in_header" value="1"
                                   {{ ($featureSettings['search_show_in_header']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Show Search in Header / Mega Nav</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Displays the search bar in the desktop header and within the mega navigation dropdown.</p>
                    </div>
                    @endif
                    @if($featureSettings->has('search_log_queries'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="search_log_queries" value="0">
                            <input type="checkbox" name="search_log_queries" value="1"
                                   {{ ($featureSettings['search_log_queries']->value ?? '1') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Log Search Queries for Analytics</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">Saves visitor search queries to the database so you can see what people are searching for on the site.</p>
                    </div>
                    @endif
                    @if($featureSettings->has('search_placeholder'))
                    <x-admin.form-input name="search_placeholder" label="Search Placeholder Text" :value="$featureSettings['search_placeholder']->value ?? 'Search services, cities, blog…'" tooltip="Placeholder text displayed inside the search input field before the visitor starts typing. Should hint at what can be searched." />
                    @endif
                    @if($featureSettings->has('search_min_chars'))
                    <x-admin.form-input name="search_min_chars" label="Minimum Characters to Trigger Search" type="number" :value="$featureSettings['search_min_chars']->value ?? '2'" tooltip="Minimum number of characters a visitor must type before the live search activates. Lower values (2–3) give faster results; higher values reduce server load." />
                    @endif
                </div>
            </x-admin.card>

            <x-admin.card title="Portfolio">
                <div class="space-y-5">
                    @if($featureSettings->has('portfolio_detail_enabled'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="portfolio_detail_enabled" value="0">
                            <input type="checkbox" name="portfolio_detail_enabled" value="1"
                                   {{ ($featureSettings['portfolio_detail_enabled']->value ?? '0') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Enable Portfolio Detail Pages</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">When enabled, individual portfolio project pages are accessible via their slug.</p>
                    </div>
                    @endif
                </div>
            </x-admin.card>
        </div>
    </div>

    {{-- ===== AI CONTENT ===== --}}
    <div x-show="tab === 'ai'" x-cloak>
        @php $aiSettings = $settings->get('ai', collect())->keyBy('key'); @endphp
        <div class="space-y-6">
            <x-admin.card title="OpenAI Configuration">
                <div class="space-y-5">
                    @if($aiSettings->has('ai_features_enabled'))
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="hidden" name="ai_features_enabled" value="0">
                            <input type="checkbox" name="ai_features_enabled" value="1"
                                   {{ ($aiSettings['ai_features_enabled']->value ?? '0') === '1' ? 'checked' : '' }}
                                   class="w-4 h-4 rounded accent-forest">
                            <span class="text-sm font-medium text-text">Enable AI Content Features</span>
                        </label>
                        <p class="text-xs text-text-secondary mt-1 ml-7">When enabled, AI generate buttons appear on content forms throughout the admin panel.</p>
                    </div>
                    @endif
                    @if($aiSettings->has('openai_api_key'))
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">OpenAI API Key</label>
                        <p class="text-xs text-text-secondary mb-1.5">Your secret API key from OpenAI. Required for AI content generation.</p>
                        <input type="password" name="openai_api_key" value="{{ $aiSettings['openai_api_key']->value ?? '' }}"
                               placeholder="sk-..."
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                    </div>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        @if($aiSettings->has('openai_model'))
                        <x-admin.form-input name="openai_model" label="Model" :value="$aiSettings['openai_model']->value ?? 'gpt-4o'" help="e.g. gpt-4o, gpt-4o-mini" :tooltip="$settingTooltips['openai_model'] ?? ''" />
                        @endif
                        @if($aiSettings->has('openai_temperature'))
                        <x-admin.form-input name="openai_temperature" label="Temperature" :value="$aiSettings['openai_temperature']->value ?? '0.7'" help="0.0 (deterministic) to 1.0 (creative)" :tooltip="$settingTooltips['openai_temperature'] ?? ''" />
                        @endif
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="AI Context Document">
                <div class="space-y-3">
                    <p class="text-xs text-text-secondary">Describe your website structure, brand voice, services, cities, and keyword strategy. This document is included as context in every AI content generation request to ensure consistent, on-brand output.</p>
                    @if($aiSettings->has('ai_context_markdown'))
                    <textarea name="ai_context_markdown" rows="20"
                              placeholder="# Super WMS Service&#10;&#10;## Brand Voice&#10;Professional, conversational, customer-facing...&#10;&#10;## Services&#10;- Professional Driveways&#10;- Concrete Patios...&#10;&#10;## Cities Served&#10;- Mississauga, Hamilton, Oakville..."
                              class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">{{ $aiSettings['ai_context_markdown']->value ?? '' }}</textarea>
                    @endif
                </div>
            </x-admin.card>
        </div>
    </div>

    {{-- Save Button --}}
    <div class="flex justify-end mt-6">
        <button type="submit" data-loading-label="Saving…" class="bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-10 rounded-xl transition text-sm">
            Save All Settings
        </button>
    </div>
</form>
@endsection
