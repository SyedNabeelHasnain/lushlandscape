<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Faq;
use App\Models\PortfolioProject;
use App\Models\SearchLog;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    // AJAX live search — returns grouped JSON
    public function live(Request $request)
    {
        $q = trim($request->query('q', ''));

        if (mb_strlen($q) < 2) {
            return response()->json([
                'services' => [],
                'categories' => [],
                'cities' => [],
                'blog' => [],
                'faqs' => [],
                'portfolio' => [],
            ]);
        }

        $services = $this->applySearch(Service::where('status', 'published'), $q, ['name', 'service_summary'])
            ->with('category')
            ->orderBy('sort_order')
            ->take(5)
            ->get(['id', 'category_id', 'name', 'slug_final']);

        $categories = $this->applySearch(ServiceCategory::where('status', 'published'), $q, ['name', 'short_description', 'long_description'])
            ->orderBy('sort_order')
            ->take(3)
            ->get(['id', 'name', 'slug_final', 'short_description']);

        $cities = $this->applySearch(City::where('status', 'published'), $q, ['name', 'region_name'])
            ->orderBy('name')
            ->take(6)
            ->get(['id', 'name', 'slug_final']);

        $blog = $this->applySearch(BlogPost::where('status', 'published'), $q, ['title', 'excerpt'])
            ->orderByDesc('published_at')
            ->take(4)
            ->get(['id', 'title', 'slug']);

        $faqs = $this->applySearch(Faq::where('status', 'published'), $q, ['question', 'answer'])
            ->take(3)
            ->get(['id', 'question', 'slug']);

        $portfolio = $this->applySearch(PortfolioProject::where('status', 'published'), $q, ['title', 'description'])
            ->orderByDesc('completion_date')
            ->take(3)
            ->get(['id', 'title', 'slug']);

        $services = $services->filter(fn (Service $service) => filled($service->frontend_url))->values();
        $categories = $categories->filter(fn (ServiceCategory $category) => filled($category->frontend_url))->values();
        $cities = $cities->filter(fn (City $city) => filled($city->frontend_url))->values();
        $blog = $blog->filter(fn (BlogPost $post) => filled($post->frontend_url))->values();
        $faqs = $faqs->filter(fn (Faq $faq) => filled($faq->frontend_url))->values();
        $portfolio = $portfolio->filter(fn (PortfolioProject $project) => filled($project->frontend_url))->values();

        $totalCount = $services->count()
            + $categories->count()
            + $cities->count()
            + $blog->count()
            + $faqs->count()
            + $portfolio->count();

        // Log search asynchronously (don't fail request if log fails)
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
            'services' => $services
                ->map(fn (Service $service) => ['name' => $service->name, 'url' => $service->frontend_url])
                ->values(),
            'categories' => $categories
                ->map(fn (ServiceCategory $category) => ['name' => $category->name, 'url' => $category->frontend_url])
                ->values(),
            'cities' => $cities
                ->map(fn (City $city) => ['name' => $city->name, 'url' => $city->frontend_url])
                ->values(),
            'blog' => $blog
                ->map(fn (BlogPost $post) => ['title' => $post->title, 'url' => $post->frontend_url])
                ->values(),
            'faqs' => $faqs
                ->map(fn (Faq $faq) => ['question' => $faq->question, 'url' => $faq->frontend_url])
                ->values(),
            'portfolio' => $portfolio
                ->map(fn (PortfolioProject $project) => ['title' => $project->title, 'url' => $project->frontend_url])
                ->values(),
            'total' => $totalCount,
            'query' => $q,
        ]);
    }

    // Full search results page
    public function results(Request $request)
    {
        $q = trim($request->query('q', ''));
        $type = $request->query('type', 'all');

        if (mb_strlen($q) < 2) {
            return view('frontend.pages.search', compact('q', 'type'))->with([
                'services' => collect(),
                'categories' => collect(),
                'cities' => collect(),
                'blog' => collect(),
                'faqs' => collect(),
                'portfolio' => collect(),
                'total' => 0,
            ]);
        }

        $services = $this->applySearch(Service::where('status', 'published'), $q, ['name', 'service_summary'])
            ->with(['category', 'heroMedia'])
            ->orderBy('sort_order')
            ->take(20)->get();

        $categories = $this->applySearch(ServiceCategory::where('status', 'published'), $q, ['name', 'short_description', 'long_description'])
            ->with('heroMedia')
            ->orderBy('sort_order')
            ->take(20)->get();

        $cities = $this->applySearch(City::where('status', 'published'), $q, ['name', 'region_name'])
            ->take(20)->get();

        $blog = $this->applySearch(BlogPost::where('status', 'published'), $q, ['title', 'excerpt', 'body'])
            ->with('heroMedia')
            ->orderByDesc('published_at')
            ->take(20)->get();

        $faqs = $this->applySearch(Faq::where('status', 'published'), $q, ['question', 'answer'])
            ->take(20)->get();

        $portfolio = $this->applySearch(PortfolioProject::where('status', 'published'), $q, ['title', 'description'])
            ->with('heroMedia')
            ->take(20)->get();

        $services = $services->filter(fn (Service $service) => filled($service->frontend_url))->values();
        $categories = $categories->filter(fn (ServiceCategory $category) => filled($category->frontend_url))->values();
        $cities = $cities->filter(fn (City $city) => filled($city->frontend_url))->values();
        $blog = $blog->filter(fn (BlogPost $post) => filled($post->frontend_url))->values();
        $faqs = $faqs->filter(fn (Faq $faq) => filled($faq->frontend_url))->values();
        $portfolio = $portfolio->filter(fn (PortfolioProject $project) => filled($project->frontend_url))->values();

        $total = $services->count()
            + $categories->count()
            + $cities->count()
            + $blog->count()
            + $faqs->count()
            + $portfolio->count();

        // Log full-page search
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
        $words = array_filter(explode(' ', $q), fn ($w) => mb_strlen($w) > 1);

        if (empty($words)) {
            $words = [$q];
        }

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
