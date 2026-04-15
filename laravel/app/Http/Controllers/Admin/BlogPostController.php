<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesAjaxRequests;
use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Services\BlockBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

class BlogPostController extends Controller
{
    use HandlesAjaxRequests;

    public function index(Request $request)
    {
        $query = BlogPost::with(['category', 'author'])->orderByDesc('created_at');

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.str_replace(['%', '_'], ['\%', '\_'], $request->search).'%');
        }

        $posts = $query->paginate(20);
        $categories = BlogCategory::orderBy('sort_order')->pluck('name', 'id');

        return View::make('admin.blog-posts.index', compact('posts', 'categories'));
    }

    public function create()
    {
        $categories = BlogCategory::orderBy('sort_order')->pluck('name', 'id');
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.blog-posts.form', compact('categories', 'blockTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug',
            'category_id' => 'required|exists:blog_categories,id',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'featured_image_id' => 'nullable|exists:media_assets,id',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['author_id'] = auth()->id();
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        $blogPost = BlogPost::create($validated);

        $blocksJson = $request->input('blocks_json');
        if ($blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true);
            BlockBuilderService::saveUnifiedBlocks('blog_post', $blogPost->id, $blocksData ?: []);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Blog post created.', [], route('admin.blog-posts.edit', $blogPost));
        }

        return Redirect::route('admin.blog-posts.index')
            ->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blogPost)
    {
        $blogPost->load('heroMedia');
        $categories = BlogCategory::orderBy('sort_order')->pluck('name', 'id');
        $blocks = BlockBuilderService::getUnifiedBlocks('blog_post', $blogPost->id);
        $blockTypes = BlockBuilderService::allTypes();

        return View::make('admin.blog-posts.form', [
            'post' => $blogPost, 'categories' => $categories,
            'blocks' => $blocks, 'blockTypes' => $blockTypes,
        ]);
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,'.$blogPost->id,
            'category_id' => 'required|exists:blog_categories,id',
            'excerpt' => 'nullable|string',
            'body' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'featured_image_id' => 'nullable|exists:media_assets,id',
            'is_featured' => 'boolean',
            'status' => 'required|in:draft,published,archived',
        ]);

        $validated['is_featured'] = $request->boolean('is_featured');

        if ($validated['status'] === 'published' && ! $blogPost->published_at) {
            $validated['published_at'] = now();
        }

        $blogPost->update($validated);

        $blocksJson = $request->input('blocks_json');
        if ($blocksJson !== null) {
            $blocksData = json_decode($blocksJson, true);
            BlockBuilderService::saveUnifiedBlocks('blog_post', $blogPost->id, $blocksData ?: []);
        }

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Blog post updated.');
        }

        return Redirect::route('admin.blog-posts.index')
            ->with('success', 'Blog post updated.');
    }

    public function destroy(Request $request, BlogPost $blogPost)
    {
        BlockBuilderService::deleteAllBlocksForPage('blog_post', $blogPost->id);
        $blogPost->delete();

        if ($this->isAjax($request)) {
            return $this->jsonSuccess('Blog post deleted.');
        }

        return Redirect::route('admin.blog-posts.index')->with('success', 'Blog post deleted.');
    }
}
