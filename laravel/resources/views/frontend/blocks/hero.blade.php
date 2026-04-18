@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: hero --}}
@php
    $cityName = $city ? $city->name : 'Ontario';
    $provinceName = $city ? ($city->province_name ?? 'Ontario') : 'Ontario';
    $citySummary = $city->city_summary ?? 'Premium landscaping construction: interlocking, concrete, patios, retaining walls, and more.';
    
    $heroHeading  = !empty($section['settings']['heading'])  ? $section['settings']['heading']  : 'Landscaping Services in ' . $cityName;
    $heroSubtitle = !empty($section['settings']['subtitle']) ? $section['settings']['subtitle'] : $citySummary;
    $heroCtaText  = !empty($section['settings']['cta_text']) ? $section['settings']['cta_text'] : 'Book a Consultation';
    $heroCtaUrl   = !empty($section['settings']['cta_url'])  ? $section['settings']['cta_url']  : '/contact';
    $heroEyebrow  = !empty($section['settings']['eyebrow'])  ? $section['settings']['eyebrow']  : ($city ? "{$cityName}, {$provinceName}" : 'Lush Landscape');

    $configuredImageIds = $section['settings']['extra_image_ids'] ?? [];
    if (!is_array($configuredImageIds)) {
        $configuredImageIds = array_filter(array_map('trim', explode(',', (string) $configuredImageIds)));
    }

    $heroImages = !empty($configuredImageIds)
        ? \App\Models\MediaAsset::whereIn('id', $configuredImageIds)->get()->sortBy(fn ($m) => array_search($m->id, $configuredImageIds))->values()->all()
        : [];

    if (empty($heroImages) && $city) {
        $heroImages = array_values(array_filter([
            $city->heroImage2 ?? null,
            $city->heroImage3 ?? null,
            $city->heroImage4 ?? null,
        ]));
    }
    
    // Override with custom user-selected media if this block was dynamically configured
    $heroMediaId = $section['settings']['hero_media_id'] ?? null;
    $heroMedia = $heroMediaId ? \App\Models\MediaAsset::find($heroMediaId) : ($city->heroMedia ?? null);
    $videoUrl = $section['settings']['video_url'] ?? ($city->hero_video_url ?? null);
@endphp

<x-frontend.hero
    :title="$heroHeading"
    :subtitle="$heroSubtitle"
    :ctaPrimary="['text' => $heroCtaText, 'url' => $heroCtaUrl]"
    :eyebrow="$heroEyebrow"
    :mediaAsset="$heroMedia"
    :videoUrl="$videoUrl"
    :images="$heroImages"
/>
