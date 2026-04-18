@extends('admin.layouts.app')
@section('title', isset($page) ? 'Edit Page' : 'Create Service-City Page')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($page) ? 'Edit: ' . ($page->page_title ?? '') : 'Create Service-City Page'" :viewUrl="isset($page) ? url('/' . $page->slug_final) : null" />
<form method="POST" action="{{ isset($page) ? route('admin.service-city-pages.update', $page) : route('admin.service-city-pages.store') }}" data-ajax-form="true" data-success-message="{{ isset($page) ? 'Page updated successfully.' : 'Page created.' }}">
    @csrf
    @if(isset($page)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Page Assignment">
                @if(!isset($page))
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Service <span class="text-red-500">*</span></label>
                        <select name="service_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white">
                            <option value="">Select service...</option>
                            @foreach($services as $svc)
                            <option value="{{ $svc->id }}" {{ old('service_id') == $svc->id ? 'selected' : '' }}>[{{ $svc->category->name }}] {{ $svc->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">City <span class="text-red-500">*</span></label>
                        <select name="city_id" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white">
                            <option value="">Select city...</option>
                            @foreach($cities as $c)
                            <option value="{{ $c->id }}" {{ old('city_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @else
                <p class="text-sm text-text-secondary">{{ $page->service->name ?? '' }} in {{ $page->city->name ?? '' }}</p>
                @endif
            </x-admin.card>
            <x-admin.card title="Page Content">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="page_title" context="SEO page title for a landscaping service-city page. Max 60 characters. Include service name and city name." :pageContext="isset($page) ? ($page->service->name ?? '') . ' in ' . ($page->city->name ?? '') : ''" />
                    </div>
                    <x-admin.form-input name="page_title" label="Page Title (Title Tag)" :value="$page->page_title ?? ''" required tooltip="The HTML title tag for this city service page. Auto-generated as 'Service Name in City Name' but can be customized. Keep under 60 characters." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="h1" context="H1 heading for a landscaping service-city page. Should be unique and include service name and city name." :pageContext="isset($page) ? ($page->service->name ?? '') . ' in ' . ($page->city->name ?? '') : ''" />
                    </div>
                    <x-admin.form-input name="h1" label="H1 Header" :value="$page->h1 ?? ''" required tooltip="The main heading displayed on the city service page. Usually 'Service Name in City Name'. Only one H1 per page." />
                    @if(isset($page))
                    <x-admin.form-input name="custom_slug" label="Custom Slug" :value="$page->custom_slug ?? ''" help="System slug: {{ $page->system_slug }}" tooltip="Override the auto-generated URL for this page. Affects SEO. Only change if necessary, as it breaks any existing inbound links." />
                    @endif
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="local_intro" context="Opening paragraph for a landscaping service-city page. 100-150 words. Direct answer to page intent. Customer-facing, using you/your language. Mention the city name." :pageContext="isset($page) ? ($page->service->name ?? '') . ' in ' . ($page->city->name ?? '') : ''" />
                    </div>
                    <x-admin.form-textarea name="local_intro" label="Opening Answer Paragraph" :value="$page->local_intro ?? ''" :rows="6" help="First 150 words. Direct answer to page intent. Critical for AI extraction." tooltip="The opening paragraph that directly answers the page intent. First 150 words are critical for AI search extraction and featured snippets. Be direct and specific." />
                </div>
            </x-admin.card>
            <x-admin.card title="Page Layout & Content">
                @if(isset($page))
                <p class="text-xs text-text-secondary mb-4">Manage both layout sections and content blocks in a single unified builder. Drag and drop to reorder.</p>
                <x-admin.block-editor
                    pageType="service_city_page"
                    :pageId="$page->id"
                    :blocks="$blocks ?? collect()"
                    :blockTypes="$blockTypes ?? []"
                />
                @else
                <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 px-4 py-5 text-sm text-text-secondary">
                    Create the service-city page first, then you can manage its layout sections, content blocks, and import/export tools.
                </div>
                @endif
            </x-admin.card>
            @if(isset($page))
            @endif

            <x-admin.card title="SEO & Social">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="meta_title" context="SEO meta title. Max 60 characters. Include service name, city name, and brand. Format: Service in City | Lush Landscape" :pageContext="isset($page) ? ($page->service->name ?? '') . ' in ' . ($page->city->name ?? '') : ''" />
                    </div>
                    <x-admin.form-input name="meta_title" label="Meta Title" :value="$page->meta_title ?? ''" tooltip="SEO title for this city-service page shown in search results. Keep under 60 characters. Include both the service and city name." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="meta_description" context="SEO meta description. Max 155 characters. Include service name, city name, and a call to action. Conversion-oriented." :pageContext="isset($page) ? ($page->service->name ?? '') . ' in ' . ($page->city->name ?? '') : ''" />
                    </div>
                    <x-admin.form-textarea name="meta_description" label="Meta Description" :value="$page->meta_description ?? ''" :rows="2" tooltip="SEO description for this city-service page shown in search results. Keep under 160 characters. Include the service, city, and a call to action." />
                    <x-admin.form-input name="og_title" label="OG Title" :value="$page->og_title ?? ''" tooltip="Title used when this page is shared on social media. Can differ from the meta title. Make it engaging." />
                    <x-admin.form-textarea name="og_description" label="OG Description" :value="$page->og_description ?? ''" :rows="2" tooltip="Description shown in social media link previews when this page is shared. Keep under 200 characters." />
                </div>
            </x-admin.card>

            <x-admin.card title="Target Keywords">
                <div class="space-y-4">
                    <p class="text-xs text-text-secondary">Define target keywords for this page. Used to guide AI content generation and SEO optimization.</p>
                    @php $kw = $page->keywords_json ?? []; @endphp
                    <x-admin.form-input name="keywords_primary" label="Primary Keywords" :value="implode(', ', $kw['primary'] ?? [])" help="Comma-separated. Main keyword targets for this page." tooltip="Primary keywords this page should rank for. These are used in AI content generation prompts." />
                    <x-admin.form-input name="keywords_secondary" label="Secondary Keywords" :value="implode(', ', $kw['secondary'] ?? [])" help="Comma-separated. Supporting keyword variations." tooltip="Secondary keyword variations and synonyms to include naturally in the content." />
                    <x-admin.form-input name="keywords_long_tail" label="Long-Tail Keywords" :value="implode(', ', $kw['long_tail'] ?? [])" help="Comma-separated. Question-based or specific phrases." tooltip="Long-tail keyword phrases, often question-based, that target specific search intents." />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-toggle name="is_active" label="Active" :checked="$page->is_active ?? true" help="Page appears on site when active" tooltip="Active pages are publicly visible and indexed. Inactive pages still exist in the database but are hidden from visitors." />
                    @if(isset($page) && $page->slug_final)
                    <div class="p-3 bg-gray-50 rounded-lg text-xs text-text-secondary break-all">
                        <span class="font-medium text-text">URL:</span>
                        <a href="{{ url('/'.$page->slug_final) }}" target="_blank" class="text-forest hover:underline ml-1">{{ url('/'.$page->slug_final) }}</a>
                    </div>
                    @endif
                    <x-admin.form-toggle name="is_indexable" label="Indexable" :checked="$page->is_indexable ?? true" help="Include in sitemap and allow indexing" tooltip="Controls whether search engines can index this page (robots meta tag). Turn off for thin or duplicate-risk pages to protect overall site quality." />
                    <x-admin.form-input name="sort_order" label="Sort Order" type="number" :value="$page->sort_order ?? 0" tooltip="Display order when multiple city-service pages are listed together. Lower numbers appear first." />
                </div>
            </x-admin.card>
            <x-admin.card title="Hero Configuration">
                <p class="text-xs text-text-secondary mb-4">Set the hero background. Video takes priority. If no video, multiple images = slider. Single image = static hero.</p>
                <div class="space-y-5">
                    <x-admin.form-media name="hero_media_id" label="Primary Hero Image" :mediaAsset="$page->heroMedia ?? null" tooltip="Primary hero background image for this city service page." :croppable="true" />
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Hero Video URL <span class="text-xs text-text-secondary font-normal ml-1">(mp4, overrides all images)</span></label>
                        <input type="url" name="hero_video_url" value="{{ old('hero_video_url', $page->hero_video_url ?? '') }}" placeholder="https://cdn.example.com/hero.mp4" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                    </div>
                    <div class="border-t border-gray-100 pt-5">
                        <p class="text-xs font-semibold text-text-secondary uppercase tracking-wide mb-3">Slider Images (shown when no video is set)</p>
                        <div class="grid grid-cols-1 gap-4">
                            <x-admin.form-media name="hero_image_2_media_id" label="Slide 2" :mediaAsset="$page->heroImage2 ?? null" tooltip="Second image in the hero slider." />
                            <x-admin.form-media name="hero_image_3_media_id" label="Slide 3" :mediaAsset="$page->heroImage3 ?? null" tooltip="Third image in the hero slider." />
                            <x-admin.form-media name="hero_image_4_media_id" label="Slide 4" :mediaAsset="$page->heroImage4 ?? null" tooltip="Fourth image in the hero slider." />
                        </div>
                    </div>
                </div>
            </x-admin.card>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($page) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.service-city-pages.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
