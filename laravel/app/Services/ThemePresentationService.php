<?php

namespace App\Services;

use App\Models\City;
use App\Models\MediaAsset;
use App\Models\ServiceCategory;
use App\Models\Setting;
use Illuminate\Support\Collection;

class ThemePresentationService
{
    public function siteName(): string
    {
        return (string) Setting::get('site_name', 'Lush Landscape Service');
    }

    public function tagline(): string
    {
        return (string) Setting::get('site_tagline', 'Premium landscaping construction contractors serving Ontario, Canada.');
    }

    public function phone(): string
    {
        return (string) Setting::get('phone', '');
    }

    public function phoneClean(): string
    {
        return preg_replace('/[^+\d]/', '', $this->phone()) ?: '';
    }

    public function email(): string
    {
        return (string) Setting::get('email', '');
    }

    public function address(): string
    {
        return (string) Setting::get('address', '');
    }

    public function weekdayHours(): string
    {
        return (string) Setting::get('business_hours_weekday', 'Mon-Fri: 8am-6pm');
    }

    public function weekendHours(): string
    {
        return (string) Setting::get('business_hours_weekend', 'Sat: 9am-4pm');
    }

    public function ratingValue(): string
    {
        return (string) Setting::get('google_rating', '');
    }

    public function reviewCount(): string
    {
        return (string) Setting::get('google_review_count', '');
    }

    public function ctaText(): string
    {
        $text = (string) Setting::get('nav_cta_text', 'Book a Consultation');

        if (preg_match('/\b(quote|estimate)\b/i', $text)) {
            return 'Book a Consultation';
        }

        return $text;
    }

    public function ctaUrl(): string
    {
        $url = (string) Setting::get('nav_cta_url', '/request-quote');

        if (preg_match('/request-quote/i', $url)) {
            return '/request-quote';
        }

        return $url;
    }

    public function newsletterHeading(): string
    {
        $heading = (string) Setting::get('footer_newsletter_heading', 'Exclusive Landscape Insights');

        if (preg_match('/seasonal\s+deals/i', $heading)) {
            return 'Exclusive Landscape Insights';
        }

        return $heading;
    }

    public function newsletterSubtext(): string
    {
        $subtext = (string) Setting::get('footer_newsletter_subtext', 'Curated design inspiration and project planning strategies for your estate.');

        if (preg_match('/Join 2,000\+ Ontario homeowners/i', $subtext)) {
            return 'Curated design inspiration and project planning strategies for your estate.';
        }

        return $subtext;
    }

    public function copyrightText(): string
    {
        $copyright = (string) Setting::get(
            'footer_copyright_text',
            '© {year} {site_name}. All rights reserved.'
        );

        if (preg_match('/Licensed\s*&\s*Insured/i', $copyright)) {
            $copyright = '© {year} {site_name}. All rights reserved.';
        }

        $copyright = str_replace('{year}', date('Y'), $copyright);

        return str_replace('{site_name}', $this->siteName(), $copyright);
    }

    public function bottomLinks(): array
    {
        $raw = Setting::get('footer_bottom_links_json', '');
        $decoded = $raw ? (json_decode($raw, true) ?? []) : [];

        $links = ! empty($decoded) ? $decoded : [
            ['label' => 'Privacy Policy', 'url' => '/privacy-policy'],
            ['label' => 'Terms & Conditions', 'url' => '/terms'],
            ['label' => 'Sitemap', 'url' => '/sitemap.xml'],
        ];

        return array_values(array_filter(array_map(function ($link) {
            if (! is_array($link)) {
                return null;
            }

            $label = (string) ($link['label'] ?? '');
            $url = (string) ($link['url'] ?? '');

            if ($label !== '' && preg_match('/\b(quote|estimate)\b/i', $label)) {
                $link['label'] = 'Consultation';
            }
            if ($url !== '' && preg_match('/request-quote/i', $url)) {
                $link['url'] = '/request-quote';
            }

            return $link;
        }, $links)));
    }

    public function configuredFooterColumns(): array
    {
        $raw = Setting::get('footer_columns_json', '');

        $columns = $raw ? (json_decode($raw, true) ?? []) : [];

        return array_values(array_map(function ($column) {
            if (! is_array($column)) {
                return $column;
            }

            if (! isset($column['links']) || ! is_array($column['links'])) {
                return $column;
            }

            $column['links'] = array_values(array_filter(array_map(function ($link) {
                if (! is_array($link)) {
                    return null;
                }

                $label = (string) ($link['label'] ?? '');
                $url = (string) ($link['url'] ?? '');

                if ($label !== '' && preg_match('/\b(quote|estimate)\b/i', $label)) {
                    $link['label'] = 'Consultation';
                }
                if ($url !== '' && preg_match('/request-quote/i', $url)) {
                    $link['url'] = '/request-quote';
                }

                return $link;
            }, $column['links'])));

            return $column;
        }, $columns));
    }

    public function logo(string $source = 'auto'): ?MediaAsset
    {
        $settingMap = [
            'header_desktop' => 'logo_desktop_media_id',
            'header_mobile' => 'logo_mobile_media_id',
            'footer' => 'footer_logo_media_id',
        ];

        if ($source !== 'auto' && isset($settingMap[$source])) {
            $id = Setting::get($settingMap[$source], '');

            return $id ? MediaAsset::find((int) $id) : null;
        }

        foreach (['logo_desktop_media_id', 'logo_mobile_media_id', 'footer_logo_media_id'] as $key) {
            $id = Setting::get($key, '');
            if ($id) {
                return MediaAsset::find((int) $id);
            }
        }

        return null;
    }

    public function socialLinks(): array
    {
        $links = [
            ['platform' => 'facebook', 'url' => Setting::get('facebook_url', '')],
            ['platform' => 'instagram', 'url' => Setting::get('instagram_url', '')],
            ['platform' => 'youtube', 'url' => Setting::get('youtube_url', '')],
            ['platform' => 'linkedin', 'url' => Setting::get('linkedin_url', '')],
            ['platform' => 'houzz', 'url' => Setting::get('houzz_url', '')],
            ['platform' => 'homestars', 'url' => Setting::get('homestars_url', '')],
            ['platform' => 'google', 'url' => Setting::get('google_business_url', '')],
        ];

        return array_values(array_filter($links, fn (array $link) => ! empty($link['url'])));
    }

    public function navCategories(int $limit = 6): Collection
    {
        return ServiceCategory::where('status', 'published')
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get(['id', 'name', 'slug_final']);
    }

    public function navCities(int $limit = 8): Collection
    {
        return City::where('status', 'published')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get(['id', 'name', 'slug_final']);
    }

    public function allFooterCategories(): Collection
    {
        return ServiceCategory::where('status', 'published')
            ->orderBy('sort_order')
            ->get(['id', 'name', 'slug_final']);
    }

    public function allFooterCities(int $limit = 12): Collection
    {
        return City::where('status', 'published')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get(['id', 'name', 'slug_final']);
    }
}
