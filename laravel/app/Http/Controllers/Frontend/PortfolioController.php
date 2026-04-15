<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\PortfolioCategory;
use App\Models\PortfolioProject;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class PortfolioController extends Controller
{
    public function index(PageContextService $pageContext)
    {
        $query = PortfolioProject::where('status', 'published')
            ->with(['city', 'service', 'heroMedia', 'category']);

        $activeCategory = request('category');
        $activeCity = request('city');
        $featured = request('featured');

        if ($activeCategory) {
            $cat = PortfolioCategory::where('slug', $activeCategory)->first();
            if ($cat) {
                $query->where('category_id', $cat->id);
            }
        }

        if ($activeCity) {
            $city = City::where('name', $activeCity)->first();
            if ($city) {
                $query->where('city_id', $city->id);
            }
        }

        if ($featured) {
            $query->where('is_featured', true);
        }

        $projects = $query->orderByDesc('is_featured')
            ->orderByDesc('completion_date')
            ->paginate(12)
            ->withQueryString();

        $categories = PortfolioCategory::where('status', 'published')
            ->withCount(['projects' => fn ($q) => $q->where('status', 'published')])
            ->having('projects_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        $cities = City::where('status', 'published')
            ->whereHas('portfolioProjects', fn ($q) => $q->where('status', 'published'))
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $schema = SchemaService::breadcrumbList([['label' => 'Portfolio', 'url' => url('/portfolio')]]);
        $blocks = BlockBuilderService::getBlocks('portfolio_index', 0);
        $context = $pageContext->listing('Portfolio', 'portfolio', url('/portfolio'), [
            'projects' => $projects,
            'categories' => $categories,
            'cities' => $cities,
            'activeCategory' => $activeCategory,
            'activeCity' => $activeCity,
            'featured' => (bool) $featured,
        ]);

        return view('frontend.pages.portfolio', compact(
            'projects', 'categories', 'cities', 'schema',
            'activeCategory', 'activeCity', 'featured', 'blocks', 'context'
        ));
    }

    public function category(string $slug, PageContextService $pageContext)
    {
        $category = PortfolioCategory::published()
            ->with('image')
            ->where('slug', $slug)
            ->firstOrFail();

        $projects = PortfolioProject::where('status', 'published')
            ->where('category_id', $category->id)
            ->with(['city', 'service', 'heroMedia', 'category'])
            ->orderByDesc('is_featured')
            ->orderByDesc('completion_date')
            ->paginate(12);

        $categories = PortfolioCategory::published()
            ->withCount(['projects' => fn ($query) => $query->where('status', 'published')])
            ->having('projects_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        $cities = City::where('status', 'published')
            ->whereHas('portfolioProjects', fn ($query) => $query->where('status', 'published')->where('category_id', $category->id))
            ->orderBy('sort_order')
            ->get(['id', 'name']);

        $breadcrumbs = [
            ['label' => 'Portfolio', 'url' => url('/portfolio')],
            ['label' => $category->name],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::webPage(
                $category->meta_title ?? ($category->name.' Portfolio Projects'),
                $category->meta_description ?? ($category->short_description ?? ''),
                route('portfolio.category', $category->slug)
            );

        $blocks = BlockBuilderService::getBlocks('portfolio_category', $category->id);
        $context = $pageContext->compose([
            'page' => $category,
            'category' => $category,
            'category_id' => $category->id,
            'category_name' => $category->name,
            'projects' => $projects,
            'categories' => $categories,
            'cities' => $cities,
        ]);

        return view('frontend.pages.portfolio-category', compact(
            'category',
            'projects',
            'categories',
            'cities',
            'breadcrumbs',
            'schema',
            'blocks',
            'context'
        ));
    }

    public function show(string $slug, PageContextService $pageContext)
    {
        $project = PortfolioProject::where('slug', $slug)
            ->where('status', 'published')
            ->with(['city', 'service.category', 'category', 'heroMedia', 'beforeImage', 'afterImage'])
            ->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Portfolio', 'url' => url('/portfolio')],
            ['label' => $project->title],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs);

        if ($project->heroMedia) {
            $schema .= SchemaService::webPage(
                $project->meta_title ?: $project->title,
                $project->meta_description ?: ($project->description ?? ''),
                url('/portfolio/'.$project->slug)
            );
        }

        $relatedProjects = PortfolioProject::where('status', 'published')
            ->where('id', '!=', $project->id)
            ->where(function ($q) use ($project) {
                $q->where('service_id', $project->service_id)
                    ->orWhere('city_id', $project->city_id);
            })
            ->with(['city', 'service', 'heroMedia'])
            ->orderByDesc('is_featured')
            ->take(3)
            ->get();

        $gallery = $project->galleryMedia();
        $blocks = BlockBuilderService::getBlocks('portfolio_project', $project->id);
        $context = $pageContext->portfolioProject($project);

        return view('frontend.pages.portfolio-show', compact(
            'project', 'breadcrumbs', 'schema', 'relatedProjects', 'gallery', 'blocks', 'context'
        ));
    }
}
