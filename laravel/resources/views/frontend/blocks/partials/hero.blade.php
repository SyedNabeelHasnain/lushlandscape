{{-- Block: hero --}}
@php
    $heroHeading = !empty($content['heading']) ? $content['heading'] : (($context['city_name'] ?? null) ? 'Landscaping Services in '.($context['city_name'] ?? '').', Our Region' : "Our Region's Trusted Landscaping Contractors");
    $heroSubtitle = !empty($content['subtitle']) ? $content['subtitle'] : ($context['city_summary'] ?? 'From interlocking driveways to full backyard transformations, backed by premium craftsmanship and a 10-year workmanship warranty.');
    $heroCtaText = !empty($content['cta_primary_text']) ? $content['cta_primary_text'] : \App\Models\Setting::get('hero_cta_primary', 'Book a Consultation');
    $heroCtaUrl = !empty($content['cta_primary_url']) ? $content['cta_primary_url'] : '/contact';
    $heroCtaSecondaryText = !empty($content['cta_secondary_text']) ? $content['cta_secondary_text'] : \App\Models\Setting::get('hero_cta_secondary', 'View Our Work');
    $heroCtaSecondaryUrl = !empty($content['cta_secondary_url']) ? $content['cta_secondary_url'] : '/portfolio';
    $eyebrow = !empty($content['eyebrow']) ? $content['eyebrow'] : (($context['city_name'] ?? null) ? ($context['city_name'] ?? '').', '.($context['province_name'] ?? 'Our Region') : '');
    $videoUrl = $content['video_url'] ?? '';
    $extraImageIds = $content['extra_image_ids'] ?? [];
    if (!is_array($extraImageIds)) {
        $extraImageIds = array_filter(array_map('trim', explode(',', (string) $extraImageIds)));
    }
    $heroImages = !empty($extraImageIds) ? \App\Models\MediaAsset::whereIn('id', $extraImageIds)->get()->sortBy(fn($m) => array_search($m->id, $extraImageIds))->values()->all() : [];
    $heroMedia = !empty($content['hero_media_id']) ? \App\Models\MediaAsset::find($content['hero_media_id']) : ($context['hero_media'] ?? null);
    $overlayOpacity = is_numeric($content['overlay_opacity'] ?? null) ? (int) $content['overlay_opacity'] : 50;
    $overlayPreset = $content['overlay_preset'] ?? 'gradient';
    $align = $content['align'] ?? 'center';
    $height = $content['height'] ?? 'viewport';
@endphp
<x-frontend.hero
    :title="$heroHeading"
    :subtitle="$heroSubtitle"
    :ctaPrimary="['text' => $heroCtaText, 'url' => $heroCtaUrl]"
    :ctaSecondary="['text' => $heroCtaSecondaryText, 'url' => $heroCtaSecondaryUrl]"
    :eyebrow="$eyebrow"
    :mediaAsset="$heroMedia"
    :videoUrl="$videoUrl ?: null"
    :images="$heroImages"
    :overlayOpacity="$overlayOpacity"
    :overlayPreset="$overlayPreset"
    :align="$align"
    :height="$height"
/>
