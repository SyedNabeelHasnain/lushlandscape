@extends('admin.layouts.app')
@section('title', isset($post) ? 'Edit Post' : 'Create Post')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($post) ? 'Edit: ' . $post->title : 'Create Blog Post'" :viewUrl="isset($post) ? url('/blog/' . $post->slug) : null" />
<form method="POST" action="{{ isset($post) ? route('admin.blog-posts.update', $post) : route('admin.blog-posts.store') }}" data-ajax-form="true" data-success-message="{{ isset($post) ? 'Blog post updated successfully.' : 'Blog post created.' }}">
    @csrf
    @if(isset($post)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Post Content">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="title" label="Title" :value="$post->title ?? ''" required tooltip="The blog post title shown in the post header, on the blog index page, and in search engine results." />
                    <x-admin.form-input name="slug" label="Slug" :value="$post->slug ?? ''" required tooltip="The URL slug for this blog post. Auto-generated from the title but can be customized. Changing it after publishing breaks inbound links." />
                </div>
                <div class="mt-5"><x-admin.form-select name="category_id" label="Category" :options="$categories->toArray()" :value="$post->category_id ?? ''" required tooltip="Blog category for organizing posts. Determines where this post appears in category archives and navigation." /></div>
                <div class="mt-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="excerpt" context="Blog post excerpt. 1-2 sentences summarizing the article. Engaging and click-worthy." />
                    </div>
                    <x-admin.form-textarea name="excerpt" label="Excerpt" :value="$post->excerpt ?? ''" :rows="3" tooltip="Short summary shown on the blog index page and in search results. Keep under 200 characters. If left blank, the system will auto-generate one from the body." />
                </div>
                <div class="mt-5"><x-admin.rich-editor name="body" label="Body" :value="$post->body ?? ''" required tooltip="The full blog post body content. Aim for at least 600 words for SEO value. Include relevant keywords naturally." /></div>
            </x-admin.card>
            <x-admin.card title="Featured Image">
                <x-admin.form-media name="featured_image_id" label="Featured Image" :mediaAsset="isset($post) ? $post->heroMedia : null" tooltip="The main image shown on the blog index card, social shares, and at the top of the post. Recommended: 1200×630px." :croppable="true" />
            </x-admin.card>

            @if(isset($post))
            <x-admin.card title="Content Blocks">
                <p class="text-xs text-text-secondary mb-4">Add custom content blocks to this blog post: images, CTAs, comparison tables, feature lists, and more.</p>
                @php
                    $existingBlocks = isset($blocks) ? $blocks->values()->all() : [];
                @endphp
                <x-admin.block-editor
                    pageType="blog_post"
                    :pageId="$post->id"
                    :blocks="$existingBlocks"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            <x-admin.content-block-export type="blog_post" :id="$post->id" />
            @endif

            <x-admin.card title="SEO">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="meta_title" context="Blog SEO meta title. Max 60 characters. Engaging and keyword-rich." />
                    </div>
                    <x-admin.form-input name="meta_title" label="Meta Title" :value="$post->meta_title ?? ''" tooltip="SEO title shown in search engine results. Keep under 60 characters. If left blank, the post title is used." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="meta_description" context="Blog SEO meta description. Max 155 characters. Include primary keyword and call to action." />
                    </div>
                    <x-admin.form-textarea name="meta_description" label="Meta Description" :value="$post->meta_description ?? ''" :rows="2" tooltip="SEO description shown under the title in search results. Keep under 160 characters. Include the main keyword and a clear value proposition." />
                    <x-admin.form-input name="og_title" label="OG Title" :value="$post->og_title ?? ''" tooltip="Title used when this post is shared on Facebook, LinkedIn, or iMessage. Can differ from the meta title. Make it compelling." />
                    <x-admin.form-textarea name="og_description" label="OG Description" :value="$post->og_description ?? ''" :rows="2" tooltip="Description shown in social media link previews when this post is shared. Keep under 200 characters." />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published','archived'=>'Archived']" :value="$post->status ?? 'draft'" required tooltip="Published = visible on the blog and included in the sitemap. Draft = hidden from public. Archived = hidden and removed from sitemap." />
                    <x-admin.form-toggle name="is_featured" label="Featured" :checked="$post->is_featured ?? false" tooltip="Featured posts are highlighted on the blog index and may appear in homepage or sidebar featured sections." />
                </div>
            </x-admin.card>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($post) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.blog-posts.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
