<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Entry;
use App\Models\Term;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class BlogController extends Controller
{
    public function index(PageContextService $pageContext)
    {
        $posts = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'blog-post'))->where('status', 'published')
            ->with(['terms', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'blog-categories'))
            ->whereHas('entries', fn($q) => $q->where('status', 'published'))
            ->orderBy('sort_order')
            ->get();

        $schema = SchemaService::breadcrumbList([['label' => 'Blog', 'url' => url('/blog')]]);
        $blocks = BlockBuilderService::getBlocks('blog_index', 0);
        $context = $pageContext->listing('Blog', 'blog', url('/blog'), [
            'posts' => $posts,
            'categories' => $categories,
        ]);

        return view('frontend.pages.blog-index', compact('posts', 'categories', 'schema', 'blocks', 'context'));
    }

    public function category(string $slug, PageContextService $pageContext)
    {
        $category = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'blog-categories'))
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'blog-post'))->where('status', 'published')
            ->whereHas('terms', fn($q) => $q->where('id', $category->id))
            ->with(['terms', 'author'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'blog-categories'))
            ->whereHas('entries', fn($q) => $q->where('status', 'published'))
            ->orderBy('sort_order')
            ->get();

        $breadcrumbs = [
            ['label' => 'Blog', 'url' => url('/blog')],
            ['label' => $category->name],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::webPage(
                $category->data['meta_title'] ?? ($category->name.' Blog Articles'),
                $category->data['meta_description'] ?? ($category->description ?? ''),
                url('/blog/category/' . $category->slug)
            );

        $blocks = BlockBuilderService::getBlocks('blog_category', $category->id);
        $context = $pageContext->compose([
            'page' => $category,
            'category' => $category,
            'category_id' => $category->id,
            'category_name' => $category->name,
            'posts' => $posts,
            'categories' => $categories,
        ]);

        return view('frontend.taxonomies.blog-categories', compact(
            'category',
            'posts',
            'categories',
            'breadcrumbs',
            'schema',
            'blocks',
            'context'
        ));
    }

    public function show(string $slug, PageContextService $pageContext)
    {
        $post = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'blog-post'))->where('slug', $slug)->where('status', 'published')->with(['terms', 'author'])->firstOrFail();
        $category = $post->terms->first();

        $breadcrumbs = [
            ['label' => 'Blog', 'url' => url('/blog')],
            ['label' => $category->name ?? 'Blog', 'url' => $category ? url('/blog/category/' . $category->slug) : url('/blog')],
            ['label' => $post->title],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::article(
                $post->title,
                $post->data['meta_description'] ?? $post->data['excerpt'] ?? '',
                url('/blog/'.$post->slug),
                $post->published_at?->toIso8601String(),
                $post->updated_at?->toIso8601String(),
                $post->author->name ?? null,
                $post->heroMedia?->url
            );

        $relatedPosts = collect();
        if ($category) {
            $relatedPosts = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'blog-post'))->where('status', 'published')
                ->whereHas('terms', fn($q) => $q->where('id', $category->id))
                ->where('id', '!=', $post->id)
                ->with(['terms'])
                ->take(3)
                ->get();
        }

        $popularPosts = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'blog-post'))->where('status', 'published')
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $blocks = BlockBuilderService::getBlocks('blog_post', $post->id);
        $context = $pageContext->blogPost($post);

        return view('frontend.entries.blog-post', compact('post', 'breadcrumbs', 'schema', 'relatedPosts', 'popularPosts', 'blocks', 'context'));
    }
}
