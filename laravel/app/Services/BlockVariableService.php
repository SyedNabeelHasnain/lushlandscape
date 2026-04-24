<?php

namespace App\Services;

use App\Models\Entry;
use App\Models\Term;
use App\Models\Setting;
use Illuminate\Support\Carbon;

class BlockVariableService
{
    public function editorVariableGroups(): array
    {
        return [
            [
                'key' => 'globals',
                'label' => 'Globals',
                'description' => 'Site-wide business and trust settings.',
                'variables' => [
                    $this->variable('setting.site_name', 'Site Name', 'Primary brand or business name.'),
                    $this->variable('setting.site_tagline', 'Site Tagline', 'Global tagline or short positioning statement.'),
                    $this->variable('setting.phone', 'Phone', 'Primary business phone number.'),
                    $this->variable('setting.email', 'Email', 'Primary business email address.'),
                    $this->variable('setting.address', 'Address', 'Business street address.'),
                    $this->variable('setting.primary_city', 'Primary City', 'Main city served or headquarters city.'),
                    $this->variable('setting.postal_code', 'Postal Code', 'Business postal code.'),
                    $this->variable('setting.warranty_years', 'Warranty Years', 'Global workmanship warranty years.'),
                    $this->variable('setting.founding_year', 'Founding Year', 'Business founding year.'),
                    $this->variable('setting.total_projects_count', 'Project Count', 'Display value for total completed projects.'),
                    $this->variable('setting.business_hours_weekday', 'Weekday Hours', 'Primary weekday business hours.'),
                    $this->variable('setting.business_hours_weekend', 'Weekend Hours', 'Weekend business hours.'),
                    $this->variable('setting.google_rating', 'Google Rating', 'Average Google review rating.'),
                    $this->variable('setting.google_review_count', 'Google Review Count', 'Total Google reviews count.'),
                ],
            ],
            [
                'key' => 'navigation',
                'label' => 'Navigation',
                'description' => 'Shared calls-to-action and app-wide utility values.',
                'variables' => [
                    $this->variable('setting.nav_cta_text', 'Header CTA Text', 'Primary navigation button label.'),
                    $this->variable('setting.nav_cta_url', 'Header CTA URL', 'Primary navigation button URL.'),
                    $this->variable('setting.hero_cta_primary', 'Primary Hero CTA', 'Default primary hero CTA label.'),
                    $this->variable('setting.hero_cta_secondary', 'Secondary Hero CTA', 'Default secondary hero CTA label.'),
                    $this->variable('setting.footer_copyright_text', 'Footer Copyright', 'Footer copyright line or legal text.'),
                    $this->variable('setting.footer_tagline', 'Footer Tagline', 'Footer brand/supporting text.'),
                    $this->variable('setting.seo_canonical_domain', 'Canonical Domain', 'Preferred canonical domain used for SEO.'),
                    $this->variable('app.url', 'App URL', 'Current application base URL.'),
                    $this->variable('app.name', 'App Name', 'Configured application name.'),
                    $this->variable('date.year', 'Current Year', 'Current 4-digit year.'),
                    $this->variable('date.month', 'Current Month', 'Current month name.'),
                    $this->variable('date.iso', 'Current Date', 'Current date in YYYY-MM-DD format.'),
                ],
            ],
            [
                'key' => 'social',
                'label' => 'Social & Trust',
                'description' => 'Common profile URLs and marketing trust statements.',
                'variables' => [
                    $this->variable('setting.facebook_url', 'Facebook URL', 'Facebook profile or business page URL.'),
                    $this->variable('setting.instagram_url', 'Instagram URL', 'Instagram profile URL.'),
                    $this->variable('setting.google_business_url', 'Google Business URL', 'Google Business profile URL.'),
                    $this->variable('setting.youtube_url', 'YouTube URL', 'YouTube channel or profile URL.'),
                    $this->variable('setting.houzz_url', 'Houzz URL', 'Houzz profile URL.'),
                    $this->variable('setting.homestars_url', 'HomeStars URL', 'HomeStars profile URL.'),
                    $this->variable('setting.associations_text', 'Associations', 'Trust badges or certifications text.'),
                    $this->variable('setting.urgency_message', 'Urgency Message', 'Global urgency or seasonal CTA message.'),
                ],
            ],
            [
                'key' => 'page',
                'label' => 'Page',
                'description' => 'Current page-level content and SEO context.',
                'variables' => [
                    $this->variable('page.title', 'Page Title', 'Best available title from the current page context.'),
                    $this->variable('page.h1', 'Page H1', 'Primary H1/headline when available.'),
                    $this->variable('page.slug', 'Page Slug', 'Current page slug.'),
                    $this->variable('page.url', 'Page URL', 'Current page public URL when it can be resolved.'),
                    $this->variable('page.summary', 'Page Summary', 'Best available page summary or intro text.'),
                    $this->variable('page.meta_title', 'Page Meta Title', 'SEO meta title for the current page.'),
                    $this->variable('page.meta_description', 'Page Meta Description', 'SEO meta description for the current page.'),
                ],
            ],
            [
                'key' => 'service',
                'label' => 'Service',
                'description' => 'Resolved service context for service or service-city pages.',
                'variables' => [
                    $this->variable('service.name', 'Service Name', 'Service display name.'),
                    $this->variable('service.navigation_label', 'Service Navigation Label', 'Navigation-friendly service label.'),
                    $this->variable('service.slug', 'Service Slug', 'Resolved service slug.'),
                    $this->variable('service.url', 'Service URL', 'Public service detail URL.'),
                    $this->variable('service.service_summary', 'Service Summary', 'Short service summary or intro.'),
                    $this->variable('service.category_name', 'Service Category Name', 'Resolved category name for the current service.'),
                    $this->variable('service_name', 'Service Name (Scalar)', 'Convenience scalar for resolved service name.'),
                ],
            ],
            [
                'key' => 'city',
                'label' => 'Location',
                'description' => 'Resolved city/location context.',
                'variables' => [
                    $this->variable('city.name', 'City Name', 'City display name.'),
                    $this->variable('city.province_name', 'Province', 'Province or state name.'),
                    $this->variable('city.region_name', 'Region', 'Region or area name.'),
                    $this->variable('city.slug', 'City Slug', 'Resolved city slug.'),
                    $this->variable('city.url', 'City URL', 'Public city landing page URL.'),
                    $this->variable('city.city_summary', 'City Summary', 'Short city summary.'),
                    $this->variable('city_name', 'City Name (Scalar)', 'Convenience scalar for resolved city name.'),
                    $this->variable('province_name', 'Province (Scalar)', 'Convenience scalar for resolved province name.'),
                    $this->variable('city_summary', 'City Summary (Scalar)', 'Convenience scalar for resolved city summary.'),
                ],
            ],
            [
                'key' => 'category',
                'label' => 'Category',
                'description' => 'Resolved category/taxonomy context.',
                'variables' => [
                    $this->variable('category.name', 'Category Name', 'Category or taxonomy term name.'),
                    $this->variable('category.slug', 'Category Slug', 'Resolved category slug.'),
                    $this->variable('category.url', 'Category URL', 'Public category URL when it can be resolved.'),
                    $this->variable('category.short_description', 'Category Summary', 'Short category summary or description.'),
                    $this->variable('category.meta_title', 'Category Meta Title', 'SEO meta title for the current category.'),
                    $this->variable('category.meta_description', 'Category Meta Description', 'SEO meta description for the current category.'),
                    $this->variable('category_name', 'Category Name (Scalar)', 'Convenience scalar for resolved category name.'),
                ],
            ],
            [
                'key' => 'content',
                'label' => 'Articles & Projects',
                'description' => 'Blog and portfolio item context.',
                'variables' => [
                    $this->variable('post.title', 'Blog Post Title', 'Current blog post title.'),
                    $this->variable('post.excerpt', 'Blog Post Excerpt', 'Current blog post excerpt.'),
                    $this->variable('post.slug', 'Blog Post Slug', 'Current blog post slug.'),
                    $this->variable('post.url', 'Blog Post URL', 'Current blog post URL.'),
                    $this->variable('post.category_name', 'Blog Category Name', 'Category name for the current blog post.'),
                    $this->variable('project.title', 'Project Title', 'Current portfolio project title.'),
                    $this->variable('project.description', 'Project Description', 'Current portfolio project description.'),
                    $this->variable('project.slug', 'Project Slug', 'Current portfolio project slug.'),
                    $this->variable('project.url', 'Project URL', 'Current portfolio project URL.'),
                    $this->variable('project.city_name', 'Project City Name', 'City name for the current project.'),
                    $this->variable('project.service_name', 'Project Service Name', 'Service name for the current project.'),
                ],
            ],
            [
                'key' => 'loop',
                'label' => 'Loop Item',
                'description' => 'Dynamic-loop item context for card templates and repeated blocks.',
                'variables' => [
                    $this->variable('item.title', 'Item Title', 'Best available title from the current loop item.'),
                    $this->variable('item.name', 'Item Name', 'Name field from the current loop item.'),
                    $this->variable('item.slug', 'Item Slug', 'Resolved slug from the current loop item.'),
                    $this->variable('item.url', 'Item URL', 'Public URL for the current loop item when it can be resolved.'),
                    $this->variable('item.short_description', 'Item Short Description', 'Short description from the current loop item.'),
                    $this->variable('item.excerpt', 'Item Excerpt', 'Excerpt from the current loop item.'),
                    $this->variable('item.category_name', 'Item Category Name', 'Category name from the current loop item when present.'),
                    $this->variable('item.city_name', 'Item City Name', 'City name from the current loop item when present.'),
                    $this->variable('item.service_name', 'Item Service Name', 'Service name from the current loop item when present.'),
                ],
            ],
        ];
    }

    public function parseString(string $subject, array $context): string
    {
        return preg_replace_callback('/\{([\w.]+)\}/', function (array $matches) use ($context) {
            $value = $this->resolveToken($matches[1], $context);

            return $this->isRenderableScalar($value) ? (string) $value : $matches[0];
        }, $subject);
    }

    public function parseContent(array $content, array $context): array
    {
        array_walk_recursive($content, function (&$value) use ($context) {
            if (is_string($value) && str_contains($value, '{')) {
                $value = $this->parseString($value, $context);
            }
        });

        return $content;
    }

    public function resolveToken(string $token, array $context): mixed
    {
        $token = $this->tokenAliases()[$token] ?? $token;
        $context = $this->normalizedContext($context);

        $directValue = data_get($context, $token);
        if ($this->isRenderableScalar($directValue)) {
            return $directValue;
        }

        if (str_starts_with($token, 'setting.')) {
            foreach ($this->settingKeyCandidates(substr($token, 8)) as $candidateKey) {
                $settingValue = Setting::get($candidateKey);

                if ($this->isRenderableScalar($settingValue)) {
                    return $settingValue;
                }
            }

            return null;
        }

        if (! str_contains($token, '.')) {
            return null;
        }

        [$scope, $field] = explode('.', $token, 2);

        return $this->resolveScopedAlias($scope, $field, $context);
    }

    public function urlForSubject(mixed $subject, ?string $scope = null): ?string
    {
        if (is_array($subject) && is_string(data_get($subject, 'url')) && data_get($subject, 'url') !== '') {
            return data_get($subject, 'url');
        }

        if ($subject instanceof Entry) {
            return $subject->routeAlias ? url('/' . ltrim($subject->routeAlias->slug, '/')) : null;
        }

        if ($subject instanceof Term) {
            $base = match ($subject->taxonomy->slug) {
                'service-categories' => '/services/',
                'blog-categories' => '/blog/category/',
                'portfolio-categories' => '/portfolio/category/',
                default => '/'
            };
            return url($base . ltrim($subject->slug, '/'));
        }

        if (is_array($subject)) {
            return $this->arrayUrl($subject, $scope);
        }

        return null;
    }

    private function resolveScopedAlias(string $scope, string $field, array $context): mixed
    {
        $subject = data_get($context, $scope);

        return match ($field) {
            'url' => $this->urlForSubject($subject, $scope),
            'id' => $this->valueFromSubject($subject, ['id']),
            'slug' => $this->valueFromSubject($subject, ['slug_final', 'slug']),
            'title' => $this->valueFromSubject($subject, ['page_title', 'title', 'h1', 'name']),
            'summary' => $this->valueFromSubject($subject, ['service_summary', 'city_summary', 'short_description', 'excerpt', 'description', 'local_intro']),
            'meta_title' => $this->valueFromSubject($subject, ['meta_title', 'default_meta_title']),
            'meta_description' => $this->valueFromSubject($subject, ['meta_description', 'default_meta_description']),
            'category_name' => $this->valueFromSubject($subject, ['category.name', 'category_name']),
            'city_name' => $this->valueFromSubject($subject, ['city.name', 'city_name']),
            'service_name' => $this->valueFromSubject($subject, ['service.name', 'service_name']),
            default => null,
        };
    }

    private function normalizedContext(array $context): array
    {
        $now = Carbon::now();

        $context['page'] ??= $context['post']
            ?? $context['project']
            ?? $context['service']
            ?? $context['city']
            ?? $context['category']
            ?? null;

        $context['page_id'] ??= data_get($context, 'page.id');
        $context['service_id'] ??= data_get($context, 'service.id');
        $context['city_id'] ??= data_get($context, 'city.id');
        $context['category_id'] ??= data_get($context, 'category.id') ?? data_get($context, 'service.category.id');
        $context['post_id'] ??= data_get($context, 'post.id');
        $context['project_id'] ??= data_get($context, 'project.id');
        $context['page_title'] ??= data_get($context, 'page.page_title')
            ?? data_get($context, 'page.title')
            ?? data_get($context, 'page.name')
            ?? null;
        $context['service_name'] ??= data_get($context, 'service.name');
        $context['city_name'] ??= data_get($context, 'city.name');
        $context['category_name'] ??= data_get($context, 'category.name')
            ?? data_get($context, 'service.category.name');
        $context['province_name'] ??= data_get($context, 'city.province_name');
        $context['city_summary'] ??= data_get($context, 'city.city_summary');
        $context['hero_media'] ??= data_get($context, 'page.heroMedia')
            ?? data_get($context, 'service.heroMedia')
            ?? data_get($context, 'city.heroMedia')
            ?? data_get($context, 'project.heroMedia')
            ?? data_get($context, 'post.heroMedia');

        $context['app'] ??= [
            'url' => rtrim((string) config('app.url', url('/')), '/'),
            'name' => (string) config('app.name', 'Laravel'),
        ];

        $context['date'] ??= [
            'year' => $now->format('Y'),
            'month' => $now->format('F'),
            'month_number' => $now->format('m'),
            'day' => $now->format('d'),
            'iso' => $now->toDateString(),
        ];

        return $context;
    }

    private function tokenAliases(): array
    {
        return [
            'year' => 'date.year',
            'site_name' => 'setting.site_name',
            'site_phone' => 'setting.phone',
            'site_email' => 'setting.email',
            'site_address' => 'setting.address',
            'site_city' => 'setting.primary_city',
            'site_postal_code' => 'setting.postal_code',
            'trust_google_rating' => 'setting.google_rating',
            'trust_google_count' => 'setting.google_review_count',
        ];
    }

    private function settingKeyCandidates(string $key): array
    {
        $aliases = [
            'phone' => ['phone', 'site_phone'],
            'email' => ['email', 'site_email'],
            'address' => ['address', 'site_address'],
            'primary_city' => ['primary_city', 'site_city'],
            'postal_code' => ['postal_code', 'site_postal_code'],
            'google_rating' => ['google_rating', 'trust_google_rating'],
            'google_review_count' => ['google_review_count', 'trust_google_count'],
            'nav_cta_text' => ['nav_cta_text'],
            'nav_cta_url' => ['nav_cta_url'],
            'footer_copyright_text' => ['footer_copyright_text'],
            'seo_canonical_domain' => ['seo_canonical_domain'],
            'site_name' => ['site_name'],
            'site_tagline' => ['site_tagline'],
        ];

        return $aliases[$key] ?? [$key];
    }

    private function valueFromSubject(mixed $subject, array $keys): mixed
    {
        foreach ($keys as $key) {
            $value = data_get($subject, $key);
            if ($this->isRenderableScalar($value)) {
                return $value;
            }
        }

        return null;
    }

    private function arrayUrl(array $subject, ?string $scope): ?string
    {
        if ($scope === 'service') {
            $slug = data_get($subject, 'slug_final') ?: data_get($subject, 'slug');
            $categorySlug = data_get($subject, 'category.slug_final') ?: data_get($subject, 'category.slug') ?: data_get($subject, 'category_slug');

            return ($slug && $categorySlug) ? route('services.detail', ['categorySlug' => $categorySlug, 'slug' => $slug]) : null;
        }

        if ($scope === 'city') {
            $slug = data_get($subject, 'slug_final') ?: data_get($subject, 'slug');

            return $slug ? route('locations.city', ['slug' => $slug]) : null;
        }

        if ($scope === 'category') {
            $slugFinal = data_get($subject, 'slug_final');
            if ($slugFinal) {
                return route('services.category', ['slug' => $slugFinal]);
            }

            $slug = data_get($subject, 'slug');
            if ($slug && data_get($subject, 'posts_count') !== null) {
                return route('blog.category', ['slug' => $slug]);
            }
            if ($slug && data_get($subject, 'projects_count') !== null) {
                return route('portfolio.category', ['slug' => $slug]);
            }

            return data_get($subject, 'url');
        }

        if ($scope === 'post') {
            $slug = data_get($subject, 'slug');

            return $slug ? route('blog.show', ['slug' => $slug]) : null;
        }

        if ($scope === 'project') {
            $slug = data_get($subject, 'slug');

            return $slug ? route('portfolio.show', ['slug' => $slug]) : null;
        }

        if ($scope === 'page') {
            $slug = data_get($subject, 'slug_final') ?: data_get($subject, 'slug');

            return $slug ? url('/'.$slug) : null;
        }

        return data_get($subject, 'url');
    }

    private function isRenderableScalar(mixed $value): bool
    {
        return is_string($value) || is_numeric($value) || is_bool($value);
    }

    private function variable(string $token, string $label, string $description): array
    {
        return [
            'token' => $token,
            'label' => $label,
            'description' => $description,
        ];
    }
}
