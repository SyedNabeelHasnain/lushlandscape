<?php

namespace App\Services;

use InvalidArgumentException;

class SingletonPageBuilderService
{
    private const PAGES = [
        'services-hub' => [
            'key' => 'services-hub',
            'label' => 'Services Hub',
            'title' => 'Services Hub Builder',
            'page_type' => 'services_hub',
            'page_id' => 0,
            'preview_path' => '/services',
            'success_message' => 'Services hub updated successfully.',
            'helper' => 'Build the main services landing page with content blocks instead of hardcoded sections.',
        ],
        'locations-hub' => [
            'key' => 'locations-hub',
            'label' => 'Locations Hub',
            'title' => 'Locations Hub Builder',
            'page_type' => 'locations_hub',
            'page_id' => 0,
            'preview_path' => '/locations',
            'success_message' => 'Locations hub updated successfully.',
            'helper' => 'Build the main locations landing page with content blocks instead of hardcoded sections.',
        ],
        'portfolio-index' => [
            'key' => 'portfolio-index',
            'label' => 'Portfolio Index',
            'title' => 'Portfolio Index Builder',
            'page_type' => 'portfolio_index',
            'page_id' => 0,
            'preview_path' => '/portfolio',
            'success_message' => 'Portfolio index updated successfully.',
            'helper' => 'Build the portfolio landing page with content blocks instead of hardcoded sections.',
        ],
        'blog-index' => [
            'key' => 'blog-index',
            'label' => 'Blog Index',
            'title' => 'Blog Index Builder',
            'page_type' => 'blog_index',
            'page_id' => 0,
            'preview_path' => '/blog',
            'success_message' => 'Blog index updated successfully.',
            'helper' => 'Build the blog landing page with content blocks instead of hardcoded sections.',
        ],
        'contact' => [
            'key' => 'contact',
            'label' => 'Contact',
            'title' => 'Contact Page Builder',
            'page_type' => 'contact',
            'page_id' => 0,
            'preview_path' => '/contact',
            'success_message' => 'Contact page updated successfully.',
            'helper' => 'Add optional governed content blocks around the fixed contact form and map.',
        ],
        'consultation' => [
            'key' => 'consultation',
            'label' => 'Consultation',
            'title' => 'Consultation Page Builder',
            'page_type' => 'consultation',
            'page_id' => 0,
            'preview_path' => '/request-quote',
            'success_message' => 'Consultation page updated successfully.',
            'helper' => 'Add optional governed content blocks around the fixed inquiry form.',
        ],
        'faqs-index' => [
            'key' => 'faqs-index',
            'label' => 'FAQ Index',
            'title' => 'FAQ Page Builder',
            'page_type' => 'faq_index',
            'page_id' => 0,
            'preview_path' => '/faqs',
            'success_message' => 'FAQ page updated successfully.',
            'helper' => 'Build the FAQ landing page with content blocks.',
        ],
    ];

    public function all(): array
    {
        return array_values(self::PAGES);
    }

    public function find(string $key): ?array
    {
        return self::PAGES[$key] ?? null;
    }

    public function get(string $key): array
    {
        $page = $this->find($key);

        if ($page === null) {
            throw new InvalidArgumentException("Unknown singleton page builder key [{$key}].");
        }

        return $page;
    }
}
