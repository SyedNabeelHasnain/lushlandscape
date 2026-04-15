<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class BlogController extends Controller
{
    public function index(PageContextService $pageContext)
    {
        $posts = BlogPost::published()
            ->with(['category', 'author', 'heroMedia'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = BlogCategory::where('status', 'published')
            ->withCount(['posts' => fn ($q) => $q->where('status', 'published')])
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
        $category = BlogCategory::published()
            ->with('image')
            ->where('slug', $slug)
            ->firstOrFail();

        $posts = BlogPost::published()
            ->where('category_id', $category->id)
            ->with(['category', 'author', 'heroMedia'])
            ->orderByDesc('published_at')
            ->paginate(12);

        $categories = BlogCategory::published()
            ->withCount(['posts' => fn ($query) => $query->published()])
            ->orderBy('sort_order')
            ->get();

        $breadcrumbs = [
            ['label' => 'Blog', 'url' => url('/blog')],
            ['label' => $category->name],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::webPage(
                $category->meta_title ?? ($category->name.' Blog Articles'),
                $category->meta_description ?? ($category->short_description ?? ''),
                route('blog.category', $category->slug)
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

        return view('frontend.pages.blog-category', compact(
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
        $post = BlogPost::where('slug', $slug)->published()->with(['category', 'author', 'heroMedia', 'tags'])->firstOrFail();

        $breadcrumbs = [
            ['label' => 'Blog', 'url' => url('/blog')],
            ['label' => $post->category->name ?? 'Blog', 'url' => $post->category ? route('blog.category', $post->category->slug) : url('/blog')],
            ['label' => $post->title],
        ];

        $schema = SchemaService::breadcrumbList($breadcrumbs)
            .SchemaService::article(
                $post->title,
                $post->meta_description ?? $post->excerpt ?? '',
                url('/blog/'.$post->slug),
                $post->published_at?->toIso8601String(),
                $post->updated_at?->toIso8601String(),
                $post->author->name ?? null,
                $post->heroMedia?->url ?? null
            );

        $relatedPosts = BlogPost::published()
            ->where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->with(['category', 'heroMedia'])
            ->take(3)
            ->get();

        $popularPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderByDesc('published_at')
            ->take(5)
            ->get(['id', 'title', 'slug', 'published_at']);

        $blocks = BlockBuilderService::getBlocks('blog_post', $post->id);
        $context = $pageContext->blogPost($post);

        return view('frontend.pages.blog-post', compact('post', 'breadcrumbs', 'schema', 'relatedPosts', 'popularPosts', 'blocks', 'context'));
    }
}
