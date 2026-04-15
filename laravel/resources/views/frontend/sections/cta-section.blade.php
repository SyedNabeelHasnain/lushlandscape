{{-- Section: cta_section --}}
@php
    // Resolve city context: city page passes $city directly, service-city page passes $page with $page->city
    $cityObj  = isset($city) ? $city : (isset($page) ? $page->city : null);
    $cityName = $cityObj?->name;

    $ctaTitle   = !empty($section['settings']['title'])       ? $section['settings']['title']       : ($cityName ? 'Start Your ' . $cityName . ' Project Today' : 'Start Your Project Today');
    $ctaSub     = !empty($section['settings']['subtitle'])    ? $section['settings']['subtitle']    : '';
    $ctaBtnText = !empty($section['settings']['button_text']) ? $section['settings']['button_text'] : ($cityName ? 'Book a Consultation in ' . $cityName : 'Book a Consultation');
    $ctaBtnUrl  = !empty($section['settings']['button_url'])  ? $section['settings']['button_url']  : '/contact';
@endphp
<x-frontend.cta-section
    :title="$ctaTitle"
    :subtitle="$ctaSub"
    :buttonText="$ctaBtnText"
    :buttonUrl="$ctaBtnUrl"
/>
