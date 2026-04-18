<?php

namespace App\Services;

use App\Models\Setting;

class SchemaService
{
    private static function encode(array $data): string
    {
        // JSON_HEX_TAG escapes < and > to prevent </script> injection into the page
        return '<script type="application/ld+json">'.json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG).'</script>';
    }

    public static function organization(): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            '@id' => config('app.url').'/#organization',
            'name' => Setting::get('site_name', 'Super WMS'),
            'url' => config('app.url'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('images/logo.png'),
                'width' => 200,
                'height' => 60,
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => Setting::get('phone', ''),
                'contactType' => 'customer service',
                'areaServed' => 'US-NY',
                'availableLanguage' => 'English',
            ],
        ];

        $sameAs = array_filter([
            Setting::get('facebook_url'),
            Setting::get('instagram_url'),
            Setting::get('youtube_url'),
            Setting::get('google_business_url'),
            Setting::get('houzz_url'),
            Setting::get('homestars_url'),
        ]);
        if ($sameAs) {
            $data['sameAs'] = array_values($sameAs);
        }

        return self::encode($data);
    }

    public static function localBusiness(?string $city = null, array $citiesServed = []): string
    {
        $phone = Setting::get('phone', '');
        $address = Setting::get('address', '');
        $foundingYear = Setting::get('founding_year', '');

        $data = [
            '@context' => 'https://schema.org',
            '@type' => ['HomeAndConstructionBusiness', 'LocalBusiness'],
            '@id' => config('app.url').'/#localbusiness',
            'name' => Setting::get('site_name', 'Super WMS'),
            'description' => Setting::get('site_tagline', 'Landscaping Construction Contractors in Our Region'),
            'url' => config('app.url'),
            'telephone' => $phone,
            'email' => Setting::get('email', 'hello@example.com'),
            'priceRange' => '$$',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $address,
                'addressLocality' => Setting::get('primary_city', 'Hamilton'),
                'addressRegion' => 'Our Region',
                'postalCode' => Setting::get('postal_code', ''),
                'addressCountry' => 'CA',
            ],
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens' => '08:00',
                    'closes' => '18:00',
                ],
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Saturday'],
                    'opens' => '09:00',
                    'closes' => '16:00',
                ],
            ],
        ];

        if ($foundingYear) {
            $data['foundingDate'] = $foundingYear;
        }

        $ogImage = Setting::get('default_og_image');
        if ($ogImage) {
            $data['image'] = asset($ogImage);
        }

        // Aggregate rating from settings (populated once Google reviews are real)
        $rating = Setting::get('google_rating');
        $reviewCount = Setting::get('google_review_count');
        if ($rating && $reviewCount && (int) $reviewCount > 0) {
            $data['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $rating,
                'reviewCount' => (int) $reviewCount,
                'bestRating' => '5',
                'worstRating' => '1',
            ];
        }

        // areaServed — full city list or single city
        if (! empty($citiesServed)) {
            $data['areaServed'] = array_map(fn ($c) => [
                '@type' => 'City',
                'name' => $c,
                'containedInPlace' => [
                    '@type' => 'State',
                    'name' => 'Our Region',
                    'containedInPlace' => ['@type' => 'Country', 'name' => 'Canada'],
                ],
            ], $citiesServed);
        } elseif ($city) {
            $data['areaServed'] = [
                '@type' => 'City',
                'name' => $city,
                'containedInPlace' => [
                    '@type' => 'State',
                    'name' => 'Our Region',
                    'containedInPlace' => ['@type' => 'Country', 'name' => 'Canada'],
                ],
            ];
        }

        $sameAs = array_filter([
            Setting::get('facebook_url'),
            Setting::get('instagram_url'),
            Setting::get('youtube_url'),
            Setting::get('google_business_url'),
            Setting::get('houzz_url'),
            Setting::get('homestars_url'),
        ]);
        if ($sameAs) {
            $data['sameAs'] = array_values($sameAs);
        }

        return self::encode($data);
    }

    public static function service(string $name, string $description, ?string $city = null, ?string $url = null, ?string $imageUrl = null): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $name,
            'description' => $description,
            'serviceType' => 'Landscaping',
            'provider' => [
                '@type' => 'HomeAndConstructionBusiness',
                '@id' => config('app.url').'/#localbusiness',
                'name' => Setting::get('site_name', 'Super WMS'),
                'url' => config('app.url'),
            ],
            'areaServed' => [
                '@type' => 'State',
                'name' => 'Our Region',
                'containedInPlace' => ['@type' => 'Country', 'name' => 'Canada'],
            ],
        ];

        if ($city) {
            $data['areaServed'] = [
                '@type' => 'City',
                'name' => $city,
                'containedInPlace' => ['@type' => 'State', 'name' => 'Our Region'],
            ];
        }
        if ($url) {
            $data['url'] = $url;
        }
        if ($imageUrl) {
            $data['image'] = ['@type' => 'ImageObject', 'url' => $imageUrl];
        }

        return self::encode($data);
    }

    public static function faqPage(array $faqs): string
    {
        if (empty($faqs)) {
            return '';
        }

        $items = [];
        foreach ($faqs as $faq) {
            $q = is_object($faq) ? $faq->question : ($faq['question'] ?? '');
            $a = is_object($faq) ? $faq->answer : ($faq['answer'] ?? '');
            if ($q && $a) {
                $items[] = [
                    '@type' => 'Question',
                    'name' => $q,
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => strip_tags($a),
                    ],
                ];
            }
        }

        if (empty($items)) {
            return '';
        }

        return self::encode([
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ]);
    }

    public static function breadcrumbList(array $items): string
    {
        $listItems = [[
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => config('app.url'),
        ]];

        foreach ($items as $i => $item) {
            $entry = [
                '@type' => 'ListItem',
                'position' => $i + 2,
                'name' => $item['label'],
            ];
            if (isset($item['url'])) {
                $entry['item'] = $item['url'];
            }
            $listItems[] = $entry;
        }

        return self::encode([
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $listItems,
        ]);
    }

    public static function article(
        string $title,
        string $description,
        string $url,
        ?string $datePublished = null,
        ?string $dateModified = null,
        ?string $authorName = null,
        ?string $imageUrl = null
    ): string {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $title,
            'description' => $description,
            'url' => $url,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => config('app.url').'/#organization',
                'name' => Setting::get('site_name', 'Super WMS'),
                'url' => config('app.url'),
            ],
        ];

        if ($datePublished) {
            $data['datePublished'] = $datePublished;
        }
        if ($dateModified) {
            $data['dateModified'] = $dateModified;
        }
        if ($authorName) {
            $data['author'] = ['@type' => 'Person', 'name' => $authorName];
        }
        if ($imageUrl) {
            $data['image'] = ['@type' => 'ImageObject', 'url' => $imageUrl];
        }

        return self::encode($data);
    }

    public static function webSite(): string
    {
        return self::encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            '@id' => config('app.url').'/#website',
            'name' => Setting::get('site_name', 'Super WMS'),
            'url' => config('app.url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url').'/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ]);
    }

    public static function webPage(string $title, string $description, string $url): string
    {
        return self::encode([
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            '@id' => $url,
            'name' => $title,
            'description' => $description,
            'url' => $url,
            'isPartOf' => [
                '@type' => 'WebSite',
                '@id' => config('app.url').'/#website',
                'url' => config('app.url'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                '@id' => config('app.url').'/#organization',
            ],
        ]);
    }

    public static function review(object $review): string
    {
        $data = [
            '@context' => 'https://schema.org',
            '@type' => 'Review',
            'itemReviewed' => [
                '@type' => 'HomeAndConstructionBusiness',
                '@id' => config('app.url').'/#localbusiness',
            ],
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => (string) $review->rating,
                'bestRating' => '5',
                'worstRating' => '1',
            ],
            'author' => ['@type' => 'Person', 'name' => $review->reviewer_name],
            'reviewBody' => $review->content,
        ];

        if ($review->review_date) {
            $data['datePublished'] = $review->review_date->toDateString();
        }

        return self::encode($data);
    }
}
