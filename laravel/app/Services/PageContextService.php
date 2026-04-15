<?php

namespace App\Services;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Models\StaticPage;
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

    public function staticPage(StaticPage $page): array
    {
        return $this->compose([
            'page' => $page,
            'page_id' => $page->id,
            'page_title' => $page->title,
            'hero_media' => $page->heroMedia ?? null,
        ]);
    }

    public function serviceCategory(ServiceCategory $category): array
    {
        return $this->compose([
            'page' => $category,
            'category' => $category,
            'category_id' => $category->id,
            'category_name' => $category->name,
        ]);
    }

    public function service(Service $service, ?Collection $cityPages = null): array
    {
        return $this->compose([
            'page' => $service,
            'service' => $service,
            'category' => $service->category,
            'service_id' => $service->id,
            'service_name' => $service->name,
            'category_id' => $service->category?->id,
            'category_name' => $service->category?->name,
            'hero_media' => $service->heroMedia,
            'cityPages' => $cityPages ?? collect(),
        ]);
    }

    public function city(City $city, ?Collection $servicePages = null): array
    {
        return $this->compose([
            'page' => $city,
            'city' => $city,
            'city_id' => $city->id,
            'city_name' => $city->name,
            'province_name' => $city->province_name,
            'city_summary' => $city->city_summary,
            'hero_media' => $city->heroMedia,
            'cityPages' => $servicePages ?? collect(),
        ]);
    }

    public function blogPost(BlogPost $post): array
    {
        return $this->compose([
            'page' => $post,
            'post' => $post,
            'category' => $post->category,
            'post_id' => $post->id,
            'category_id' => $post->category_id,
            'category_name' => $post->category?->name,
            'hero_media' => $post->heroMedia,
        ]);
    }

    public function portfolioProject(PortfolioProject $project): array
    {
        return $this->compose([
            'page' => $project,
            'project' => $project,
            'service' => $project->service,
            'city' => $project->city,
            'category' => $project->category,
            'project_id' => $project->id,
            'service_id' => $project->service?->id,
            'city_id' => $project->city?->id,
            'category_id' => $project->category?->id,
            'service_name' => $project->service?->name,
            'city_name' => $project->city?->name,
            'category_name' => $project->category?->name,
            'province_name' => $project->city?->province_name,
            'city_summary' => $project->city?->city_summary,
            'hero_media' => $project->heroMedia,
        ]);
    }

    public function serviceCityPage(
        ServiceCityPage $page,
        ?Collection $cityPages = null,
        ?Collection $faqs = null,
        ?Collection $generalFaqs = null,
        ?Collection $cityFaqs = null
    ): array {
        return $this->compose([
            'page' => $page,
            'service' => $page->service,
            'city' => $page->city,
            'category' => $page->service->category ?? null,
            'page_id' => $page->id,
            'service_id' => $page->service?->id,
            'city_id' => $page->city?->id,
            'category_id' => $page->service->category?->id,
            'service_name' => $page->service?->name,
            'city_name' => $page->city?->name,
            'category_name' => $page->service->category?->name,
            'province_name' => $page->city?->province_name,
            'city_summary' => $page->city?->city_summary,
            'hero_media' => $page->heroMedia,
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
