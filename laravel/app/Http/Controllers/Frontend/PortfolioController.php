<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Term;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class PortfolioController extends Controller
{
    public function index(PageContextService $pageContext)
    {
        $query = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published');

        $activeCategory = request('category');
        $activeCity = request('city');
        $featured = request('featured');

        if ($activeCategory) {
            $cat = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'portfolio-categories'))->where('slug', $activeCategory)->first();
            if ($cat) {
                $query->whereHas('terms', fn($q) => $q->where('id', $cat->id));
            }
        }

        if ($activeCity) {
            $city = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('slug', $activeCity)->first();
            if ($city) {
                $query->whereHas('relatedEntries', fn($q) => $q->where('target_entry_id', $city->id)->where('relation_type', 'portfolio_city'));
            }
        }

        if ($featured) {
            $query->where('data->is_featured', true);
        }

        $projects = $query->orderByDesc('data->is_featured')
            ->orderByDesc('data->completion_date')
            ->paginate(12)
            ->withQueryString();

        $categories = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'portfolio-categories'))
            ->whereHas('entries', fn($q) => $q->where('status', 'published'))
            ->orderBy('sort_order')
            ->get();

        $cities = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')
            ->whereHas('inverseRelatedEntries', fn($q) => $q->where('relation_type', 'portfolio_city')->where('status', 'published'))
            ->orderBy('sort_order')
            ->get(['id', 'title as name', 'slug']);

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
        $category = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'portfolio-categories'))
            ->where('slug', $slug)
            ->firstOrFail();

        $projects = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published')
            ->whereHas('terms', fn($q) => $q->where('id', $category->id))
            ->orderByDesc('data->is_featured')
            ->orderByDesc('data->completion_date')
            ->paginate(12);

        $categories = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'portfolio-categories'))
            ->whereHas('entries', fn($q) => $q->where('status', 'published'))
            ->orderBy('sort_order')
            ->get();

        $cities = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')
            ->whereHas('inverseRelatedEntries', function($q) use ($category) {
                $q->where('relation_type', 'portfolio_city')
                  ->where('status', 'published')
                  ->whereHas('terms', fn($q2) => $q2->where('id', $category->id));
            })
            ->orderBy('sort_order')
            ->get(['id', 'title as name', 'slug']);

        $breadcrumbs = [
            ['label' => 'Portfolio', 'url' => url('/portfolio')],
            ['label' => $category->name],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::webPage(
                $category->data['meta_title'] ?? ($category->name.' Portfolio Projects'),
                $category->data['meta_description'] ?? ($category->description ?? ''),
                url('/portfolio/category/' . $category->slug)
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
        $project = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('slug', $slug)
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
                $project->meta_description ?: ($project->data['description'] ?? ''),
                url('/portfolio/'.$project->slug)
            );
        }

        $relatedProjects = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published')
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
