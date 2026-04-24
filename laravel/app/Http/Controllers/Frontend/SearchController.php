<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Faq;
use App\Models\MediaAsset;
use App\Models\SearchLog;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function live(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'services' => [], 'categories' => [], 'cities' => [],
                'blog' => [], 'faqs' => [], 'portfolio' => [],
            ]);
        }

        $services = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'service'))->where('status', 'published'), $q, ['title', 'data->service_summary'])
            ->with('routeAlias')->orderBy('sort_order')->take(5)->get(['id', 'title', 'slug', 'data']);

        $categories = $this->applySearch(Term::whereHas('taxonomy', fn ($q) => $q->where('slug', 'service-categories')), $q, ['name', 'data->description', 'data->long_description'])
            ->orderBy('sort_order')->take(3)->get(['id', 'name', 'slug', 'data']);

        $cities = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'city'))->where('status', 'published'), $q, ['title', 'data->region_name'])
            ->with('routeAlias')->orderBy('title')->take(6)->get(['id', 'title', 'slug', 'data']);

        $blog = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'blog-post'))->where('status', 'published'), $q, ['title', 'data->excerpt'])
            ->with('routeAlias')->orderByDesc('published_at')->take(4)->get(['id', 'title', 'slug', 'data']);

        $faqs = $this->applySearch(Faq::where('status', 'published'), $q, ['question', 'answer'])
            ->take(3)->get(['id', 'question', 'slug']);

        $portfolio = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published'), $q, ['title', 'data->description'])
            ->with('routeAlias')->orderByDesc('created_at')->take(3)->get(['id', 'title', 'slug', 'data']);

        $enrichEntry = fn ($items) => $items->map(function ($item) {
            $item->frontend_url = $item->routeAlias ? url('/'.ltrim($item->routeAlias->slug, '/')) : null;

            return $item;
        })->filter(fn ($item) => filled($item->frontend_url))->values();

        $enrichTerm = fn ($items) => $items->map(function ($item) {
            $item->frontend_url = url('/services/'.ltrim($item->slug, '/'));

            return $item;
        })->filter(fn ($item) => filled($item->frontend_url))->values();

        $services = $enrichEntry($services);
        $categories = $enrichTerm($categories);
        $cities = $enrichEntry($cities);
        $blog = $enrichEntry($blog);
        $portfolio = $enrichEntry($portfolio);
        $faqs = $faqs->filter(fn ($faq) => filled($faq->frontend_url))->values();

        $totalCount = $services->count() + $categories->count() + $cities->count() + $blog->count() + $faqs->count() + $portfolio->count();

        try {
            SearchLog::create([
                'query' => mb_substr($q, 0, 500),
                'results_count' => $totalCount,
                'session_id' => session()->getId(),
                'page_context' => 'header',
                'ip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Search log failed: '.$e->getMessage(), ['exception' => $e]);
        }

        return response()->json([
            'services' => $services->map(fn ($service) => ['name' => $service->title, 'url' => $service->frontend_url])->values(),
            'categories' => $categories->map(fn ($category) => ['name' => $category->title, 'url' => $category->frontend_url])->values(),
            'cities' => $cities->map(fn ($city) => ['name' => $city->title, 'url' => $city->frontend_url])->values(),
            'blog' => $blog->map(fn ($post) => ['title' => $post->title, 'url' => $post->frontend_url])->values(),
            'faqs' => $faqs->map(fn ($faq) => ['question' => $faq->question, 'url' => $faq->frontend_url])->values(),
            'portfolio' => $portfolio->map(fn ($project) => ['title' => $project->title, 'url' => $project->frontend_url])->values(),
            'total' => $totalCount,
            'query' => $q,
        ]);
    }

    public function results(Request $request)
    {
        $q = trim($request->query('q', ''));
        $type = $request->query('type', 'all');

        if (mb_strlen($q) < 2) {
            return view('frontend.pages.search', compact('q', 'type'))->with([
                'services' => collect(), 'categories' => collect(), 'cities' => collect(),
                'blog' => collect(), 'faqs' => collect(), 'portfolio' => collect(), 'total' => 0,
            ]);
        }

        $services = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'service'))->where('status', 'published'), $q, ['title', 'data->service_summary'])
            ->with('routeAlias')->orderBy('sort_order')->take(20)->get();

        $categories = $this->applySearch(Term::whereHas('taxonomy', fn ($q) => $q->where('slug', 'service-categories')), $q, ['name', 'data->description', 'data->long_description'])
            ->orderBy('sort_order')->take(20)->get();

        $cities = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'city'))->where('status', 'published'), $q, ['title', 'data->region_name'])
            ->with('routeAlias')->take(20)->get();

        $blog = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'blog-post'))->where('status', 'published'), $q, ['title', 'data->excerpt', 'data->body'])
            ->with('routeAlias')->orderByDesc('published_at')->take(20)->get();

        $faqs = $this->applySearch(Faq::where('status', 'published'), $q, ['question', 'answer'])
            ->take(20)->get();

        $portfolio = $this->applySearch(Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published'), $q, ['title', 'data->description'])
            ->with('routeAlias')->take(20)->get();

        // Eager load all media assets directly referenced in JSON fields to prevent N+1 issues
        $allMediaIds = collect([$services, $categories, $cities, $blog, $portfolio])
            ->flatten()->pluck('data.hero_media_id')->filter()->unique();
        $mediaAssets = MediaAsset::whereIn('id', $allMediaIds)->get()->keyBy('id');

        $mapEntry = function ($items) use ($mediaAssets) {
            return $items->map(function ($item) use ($mediaAssets) {
                $item->frontend_url = $item->routeAlias ? url('/'.ltrim($item->routeAlias->slug, '/')) : null;
                $item->heroMedia = $mediaAssets->get($item->data['hero_media_id'] ?? null);
                $item->title = $item->title ?? $item->title;
                $item->service_summary = $item->data['service_summary'] ?? null;
                $item->excerpt = $item->data['excerpt'] ?? null;

                return $item;
            })->filter(fn ($item) => filled($item->frontend_url))->values();
        };

        $mapTerm = function ($items) use ($mediaAssets) {
            return $items->map(function ($item) use ($mediaAssets) {
                $item->frontend_url = url('/services/'.ltrim($item->slug, '/'));
                $item->heroMedia = $mediaAssets->get($item->data['hero_media_id'] ?? null);
                $item->description = $item->data['short_description'] ?? null;

                return $item;
            })->filter(fn ($item) => filled($item->frontend_url))->values();
        };

        $services = $mapEntry($services);
        $categories = $mapTerm($categories);
        $cities = $mapEntry($cities);
        $blog = $mapEntry($blog);
        $portfolio = $mapEntry($portfolio);
        $faqs = $faqs->filter(fn ($faq) => filled($faq->frontend_url))->values();

        $total = $services->count() + $categories->count() + $cities->count() + $blog->count() + $faqs->count() + $portfolio->count();

        try {
            SearchLog::create([
                'query' => mb_substr($q, 0, 500),
                'results_count' => $total,
                'session_id' => session()->getId(),
                'page_context' => 'results_page',
                'ip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Search log failed (results page): '.$e->getMessage(), ['exception' => $e]);
        }

        return view('frontend.pages.search', compact('q', 'type', 'services', 'categories', 'cities', 'blog', 'faqs', 'portfolio', 'total'));
    }

    private function like(string $query): string
    {
        return '%'.str_replace(['%', '_'], ['\%', '\_'], $query).'%';
    }

    private function applySearch($queryBuilder, string $q, array $columns)
    {
        $words = array_filter(explode(' ', $q), fn ($w) => mb_strlen($w) > 1) ?: [$q];

        return $queryBuilder->where(function ($query) use ($words, $columns) {
            foreach ($words as $word) {
                $query->where(function ($subQuery) use ($word, $columns) {
                    $like = $this->like($word);
                    foreach ($columns as $column) {
                        $subQuery->orWhere($column, 'LIKE', $like);
                    }
                });
            }
        });
    }
}
