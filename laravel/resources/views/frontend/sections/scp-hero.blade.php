{{-- Section: hero (service_city_page context) --}}
@php
    $pageHeading = $page ? $page->h1 : 'Local Landscaping Services';
    $pageIntro = $page ? \Illuminate\Support\Str::limit(strip_tags($page->local_intro ?? ''), 200) : 'Premium landscaping services in your area.';
    $pageCtaText = $page ? ($page->cta_json['text'] ?? 'Book a Consultation') : 'Book a Consultation';
    
    $heroHeading  = !empty($section['settings']['heading'])  ? $section['settings']['heading']  : $pageHeading;
    $heroSubtitle = !empty($section['settings']['subtitle']) ? $section['settings']['subtitle'] : $pageIntro;
    $heroCtaText  = !empty($section['settings']['cta_text']) ? $section['settings']['cta_text'] : $pageCtaText;
    $heroCtaUrl   = !empty($section['settings']['cta_url'])  ? $section['settings']['cta_url']  : '/contact';
    
    $eyebrowText = !empty($section['settings']['eyebrow']) ? $section['settings']['eyebrow'] : ($page ? "{$page->service->name} in {$page->city->name}, Ontario" : 'Ontario Services');

    $configuredImageIds = $section['settings']['extra_image_ids'] ?? [];
    if (!is_array($configuredImageIds)) {
        $configuredImageIds = array_filter(array_map('trim', explode(',', (string) $configuredImageIds)));
    }

    $heroImages = !empty($configuredImageIds)
        ? \App\Models\MediaAsset::whereIn('id', $configuredImageIds)->get()->sortBy(fn ($m) => array_search($m->id, $configuredImageIds))->values()->all()
        : [];

    if (empty($heroImages) && $page) {
        $heroImages = array_values(array_filter([
            $page->heroImage2 ?? null,
            $page->heroImage3 ?? null,
            $page->heroImage4 ?? null,
        ]));
    }
    
    $heroMediaId = $section['settings']['hero_media_id'] ?? null;
    $heroMedia = $heroMediaId ? \App\Models\MediaAsset::find($heroMediaId) : ($page->heroMedia ?? null);
    $videoUrl = $section['settings']['video_url'] ?? ($page->hero_video_url ?? null);
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
