@php
$service = $context['service'] ?? null;
$city = $context['city'] ?? null;
$page = $context['page'] ?? null;
$servicePages = $context['servicePages'] ?? collect();
$cityPages = $context['cityPages'] ?? collect();
@endphp
{{-- Section: service_hero --}}
@php
    $serviceName = $service ? $service->name : 'Our Services';
    $serviceSummary = $service->service_summary ?? 'Premium professional construction and maintenance services.';
    
    $heroHeading  = !empty($section['settings']['heading'])  ? $section['settings']['heading']  : $serviceName;
    $heroSubtitle = !empty($section['settings']['subtitle']) ? $section['settings']['subtitle'] : $serviceSummary;
    $heroCtaText  = !empty($section['settings']['cta_text']) ? $section['settings']['cta_text'] : 'Book a Consultation';
    $heroCtaUrl   = !empty($section['settings']['cta_url'])  ? $section['settings']['cta_url']  : '/contact';
    
    $eyebrowText  = !empty($section['settings']['eyebrow']) ? $section['settings']['eyebrow'] : ($service->category->name ?? 'Our Services');

    $configuredImageIds = $section['settings']['extra_image_ids'] ?? [];
    if (!is_array($configuredImageIds)) {
        $configuredImageIds = array_filter(array_map('trim', explode(',', (string) $configuredImageIds)));
    }

    $heroImages = !empty($configuredImageIds)
        ? \App\Models\MediaAsset::whereIn('id', $configuredImageIds)->get()->sortBy(fn ($m) => array_search($m->id, $configuredImageIds))->values()->all()
        : [];

    if (empty($heroImages) && $service) {
        $heroImages = array_values(array_filter([
            $service->heroImage2 ?? null,
            $service->heroImage3 ?? null,
            $service->heroImage4 ?? null,
        ]));
    }
    
    $heroMediaId = $section['settings']['hero_media_id'] ?? null;
    $heroMedia = $heroMediaId ? \App\Models\MediaAsset::find($heroMediaId) : ($service->heroMedia ?? null);
    $videoUrl = $section['settings']['video_url'] ?? ($service->hero_video_url ?? null);
@endphp

<x-frontend.hero
    :title="$heroHeading"
    :subtitle="$heroSubtitle"
    :ctaPrimary="['text' => $heroCtaText, 'url' => $heroCtaUrl]"
    :eyebrow="$eyebrowText"
    :mediaAsset="$heroMedia"
    :videoUrl="$videoUrl"
    :images="$heroImages"
/>
