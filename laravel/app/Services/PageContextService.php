<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Entry;
use App\Models\MediaAsset;
use App\Models\Term;
use Illuminate\Support\Collection;

class PageContextService
{
    public function home(): array
    {
        return $this->compose([
            'page' => $this->listingPage('Home', '', url('/')),
        ]);
    }

    public function listing(string $title, string $slug, string $url, array $overrides = []): array
    {
        return $this->compose(array_merge([
            'page' => $this->listingPage($title, $slug, $url),
            'page_title' => $title,
        ], $overrides));
    }

    public function staticPage(Entry $page): array
    {
        return $this->compose([
            'page' => $page,
            'page_id' => $page->id,
            'page_title' => $page->title,
            'hero_media' => ! empty($page->data['hero_media_id']) ? MediaAsset::find($page->data['hero_media_id']) : null,
        ]);
    }

    public function serviceCategory(Term $category): array
    {
        return $this->compose([
            'page' => $category,
            'category' => $category,
            'category_id' => $category->id,
            'category_name' => $category->name,
        ]);
    }

    public function service(Entry $service, ?Collection $cityPages = null): array
    {
        $category = $service->terms->first();

        return $this->compose([
            'page' => $service,
            'service' => $service,
            'category' => $category,
            'service_id' => $service->id,
            'service_name' => $service->title,
            'category_id' => $category?->id,
            'category_name' => $category?->name,
            'hero_media' => ! empty($service->data['hero_media_id']) ? MediaAsset::find($service->data['hero_media_id']) : null,
            'cityPages' => $cityPages ?? collect(),
        ]);
    }

    public function city(Entry $city, ?Collection $servicePages = null): array
    {
        return $this->compose([
            'page' => $city,
            'city' => $city,
            'city_id' => $city->id,
            'city_name' => $city->title,
            'province_name' => $city->data['province_name'] ?? '',
            'city_summary' => $city->data['city_summary'] ?? '',
            'hero_media' => ! empty($city->data['hero_media_id']) ? MediaAsset::find($city->data['hero_media_id']) : null,
            'cityPages' => $servicePages ?? collect(),
        ]);
    }

    public function blogPost(Entry $post): array
    {
        $category = $post->terms->first();

        return $this->compose([
            'page' => $post,
            'post' => $post,
            'category' => $category,
            'post_id' => $post->id,
            'category_id' => $category?->id,
            'category_name' => $category?->name,
            'hero_media' => ! empty($post->data['featured_image_id']) ? MediaAsset::find($post->data['featured_image_id']) : null,
        ]);
    }

    public function portfolioProject(Entry $project): array
    {
        $service = clone $project->relatedEntries->firstWhere('pivot.relation_type', 'portfolio_service') ?? new Entry;
        $city = clone $project->relatedEntries->firstWhere('pivot.relation_type', 'portfolio_city') ?? new Entry;
        $category = clone $project->terms->first() ?? new Term;

        return $this->compose([
            'page' => $project,
            'project' => $project,
            'service' => $service,
            'city' => $city,
            'category' => $category,
            'project_id' => $project->id,
            'service_id' => $service->id,
            'city_id' => $city->id,
            'category_id' => $category->id,
            'service_name' => $service->title ?? '',
            'city_name' => $city->title ?? '',
            'category_name' => $category->name ?? '',
            'province_name' => $city->data['province_name'] ?? '',
            'city_summary' => $city->data['city_summary'] ?? '',
            'hero_media' => ! empty($project->data['hero_media_id']) ? MediaAsset::find($project->data['hero_media_id']) : null,
        ]);
    }

    public function serviceCityPage(
        Entry $page,
        ?Collection $cityPages = null,
        ?Collection $faqs = null,
        ?Collection $generalFaqs = null,
        ?Collection $cityFaqs = null
    ): array {
        $service = clone $page->relatedEntries->firstWhere('pivot.relation_type', 'matrix_service') ?? new Entry;
        $city = clone $page->relatedEntries->firstWhere('pivot.relation_type', 'matrix_city') ?? new Entry;
        $category = clone $service->terms->first() ?? new Term;

        return $this->compose([
            'page' => $page,
            'service' => $service,
            'city' => $city,
            'category' => $category,
            'page_id' => $page->id,
            'service_id' => $service->id,
            'city_id' => $city->id,
            'category_id' => $category->id,
            'service_name' => $service->title ?? '',
            'city_name' => $city->title ?? '',
            'category_name' => $category->name ?? '',
            'province_name' => $city->data['province_name'] ?? '',
            'city_summary' => $city->data['city_summary'] ?? '',
            'hero_media' => ! empty($page->data['hero_media_id']) ? MediaAsset::find($page->data['hero_media_id']) : null,
            'cityPages' => $cityPages ?? collect(),
            'faqs' => $faqs ?? collect(),
            'generalFaqs' => $generalFaqs ?? collect(),
            'cityFaqs' => $cityFaqs ?? collect(),
        ]);
    }

    public function compose(array $overrides = []): array
    {
        return array_merge([
            'page' => null,
            'service' => null,
            'city' => null,
            'category' => null,
            'post' => null,
            'project' => null,
            'item' => null,
            'page_id' => null,
            'service_id' => null,
            'city_id' => null,
            'category_id' => null,
            'post_id' => null,
            'project_id' => null,
            'page_title' => null,
            'city_name' => null,
            'service_name' => null,
            'category_name' => null,
            'province_name' => null,
            'city_summary' => null,
            'hero_media' => null,
            'servicePages' => collect(),
            'cityPages' => collect(),
            'faqs' => collect(),
            'generalFaqs' => collect(),
            'cityFaqs' => collect(),
        ], $overrides);
    }

    private function listingPage(string $title, string $slug, string $url): array
    {
        return [
            'title' => $title,
            'page_title' => $title,
            'slug' => $slug,
            'url' => $url,
        ];
    }
}
