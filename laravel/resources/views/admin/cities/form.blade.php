@extends('admin.layouts.app')
@section('title', isset($city) ? 'Edit City' : 'Create City')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($city) ? 'Edit: ' . $city->name : 'Create City'" :viewUrl="isset($city) ? url('/landscaping-' . $city->slug_final) : null" />
<form method="POST" action="{{ isset($city) ? route('admin.cities.update', $city) : route('admin.cities.store') }}" data-ajax-form="true" data-success-message="{{ isset($city) ? 'City updated successfully.' : 'City created.' }}">
    @csrf
    @if(isset($city)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="City Details">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="name" label="City Name" :value="$city->name ?? ''" required tooltip="The full city name displayed on the page and in the navigation, e.g. Oakville" />
                    <x-admin.form-input name="navigation_label" label="Nav Label" :value="$city->navigation_label ?? ''" tooltip="Shortened label for navigation menus. Defaults to the city name if left blank." />
                    <x-admin.form-input name="province_name" label="Province" :value="$city->province_name ?? 'Ontario'" tooltip="The province this city is in. Defaults to Ontario. Used for structured data and SEO." />
                    <x-admin.form-input name="region_name" label="Region" :value="$city->region_name ?? ''" tooltip="The region or area grouping (e.g. GTA, Hamilton Area). Used to group cities in the Locations mega menu." />
                    <x-admin.form-input name="latitude" label="Latitude" type="number" :value="$city->latitude ?? ''" tooltip="GPS latitude for this city. Used for geo-targeting, structured data, and the GEO meta tags." />
                    <x-admin.form-input name="longitude" label="Longitude" type="number" :value="$city->longitude ?? ''" tooltip="GPS longitude for this city. Used for geo-targeting, structured data, and the GEO meta tags." />
                </div>
                @if(isset($city))
                <div class="mt-5"><x-admin.form-input name="custom_slug" label="Custom Slug" :value="$city->custom_slug ?? ''" help="Auto: {{ $city->system_slug }}" tooltip="Override the auto-generated URL slug. Leave blank to use the system-generated slug. Changes affect SEO and any inbound links." /></div>
                @endif
                <div class="mt-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="city_summary" context="City landing page summary. 1-2 sentences about landscaping services in this city." />
                    </div>
                    <x-admin.form-textarea name="city_summary" label="City Summary" :value="$city->city_summary ?? ''" :rows="4" help="Short intro shown at the top of the city page." tooltip="Short introductory paragraph shown at the top of the city landing page. Keep under 300 characters for best results." />
                </div>
            </x-admin.card>

            <x-admin.card title="Local Content">
                <p class="text-xs text-text-secondary mb-4">These populate rich content sections on the city landing page.</p>
                <div class="space-y-5">
                    <x-admin.form-textarea name="city_body[local_intro_extended]" label="Extended Local Intro" :value="$city->city_body['local_intro_extended'] ?? ''" :rows="5" help="Mention specific neighbourhoods, landmarks, or local context." tooltip="Extended paragraph about this city, mentioning specific neighborhoods, landmarks, or local context. Boosts local SEO relevance." />
                    <x-admin.form-textarea name="city_body[neighborhoods_served]" label="Neighbourhoods Served (one per line)" :value="isset($city->city_body['neighborhoods_served']) ? implode(PHP_EOL, (array)$city->city_body['neighborhoods_served']) : ''" :rows="4" help="Each line becomes one neighbourhood badge shown on the page." tooltip="List neighborhoods you serve in this city, one per line. Each becomes a visual badge on the city landing page." />
                    <x-admin.form-textarea name="city_body[why_local_para]" label="Why Hire Locally" :value="$city->city_body['why_local_para'] ?? ''" :rows="4" help="Highlight local advantages: faster response, local knowledge, etc." tooltip="Paragraph highlighting local advantages: faster response times, local supplier relationships, familiarity with local bylaws." />
                    <x-admin.form-textarea name="city_body[permit_summary]" label="Local Permit / Municipality Note" :value="$city->city_body['permit_summary'] ?? ''" :rows="3" help="e.g. In Oakville, retaining walls over 1.2m require a permit from the Town of Oakville." tooltip="Note about local permit requirements specific to this city. Example: retaining walls over 1.2m require a permit from the Town of Oakville." />
                </div>
            </x-admin.card>

            {{-- Unified Page Builder --}}
            @if(isset($city))
            <x-admin.card title="Page Layout & Content">
                <p class="text-xs text-text-secondary mb-6 italic">
                    Drag and drop any element to reorder. Use the <i data-lucide="eye" class="w-3 h-3 inline"></i> toggle to enable/disable, 
                    <i data-lucide="monitor" class="w-3 h-3 inline"></i> / <i data-lucide="smartphone" class="w-3 h-3 inline"></i> to control device visibility, 
                    and <i data-lucide="settings-2" class="w-3 h-3 inline"></i> to edit settings.
                </p>
                <x-admin.block-editor
                    pageType="city"
                    :pageId="$city->id"
                    :blocks="$blocks->toArray()"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            <x-admin.content-block-export type="city" :id="$city->id" />
            @endif

            <x-admin.card title="Target Keywords">
                <div class="space-y-4">
                    <p class="text-xs text-text-secondary">Define target keywords. Used to guide AI content generation and SEO.</p>
                    @php $kw = $city->keywords_json ?? []; @endphp
                    <x-admin.form-input name="keywords_primary" label="Primary Keywords" :value="implode(', ', $kw['primary'] ?? [])" help="Comma-separated." />
                    <x-admin.form-input name="keywords_secondary" label="Secondary Keywords" :value="implode(', ', $kw['secondary'] ?? [])" help="Comma-separated." />
                    <x-admin.form-input name="keywords_long_tail" label="Long-Tail Keywords" :value="implode(', ', $kw['long_tail'] ?? [])" help="Comma-separated." />
                </div>
            </x-admin.card>

            <x-admin.card title="SEO & Social">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="default_meta_title" context="City SEO meta title. Max 60 characters." />
                    </div>
                    <x-admin.form-input name="default_meta_title" label="Meta Title" :value="$city->default_meta_title ?? ''" help="Max 60 characters" tooltip="Page title shown in search engine results for the city landing page. Keep under 60 characters for best display." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="default_meta_description" context="City SEO meta description. Max 155 characters." />
                    </div>
                    <x-admin.form-textarea name="default_meta_description" label="Meta Description" :value="$city->default_meta_description ?? ''" :rows="2" help="Max 160 characters" tooltip="Summary shown under the title in search results. Keep under 160 characters. Should include the city name and main service." />
                    <x-admin.form-input name="default_og_title" label="OG Title" :value="$city->default_og_title ?? ''" tooltip="Title used when this city page is shared on Facebook, LinkedIn, or iMessage. Can differ from the meta title." />
                    <x-admin.form-textarea name="default_og_description" label="OG Description" :value="$city->default_og_description ?? ''" :rows="2" tooltip="Description shown in social media link previews. Keep under 200 characters." />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" :value="$city->status ?? 'draft'" required tooltip="Published cities appear on the frontend and in the locations menu. Draft cities are hidden from public view." />
                    <x-admin.form-input name="sort_order" label="Sort Order" type="number" :value="$city->sort_order ?? 0" tooltip="Controls the display order in the city sidebar and locations menu. Lower numbers appear first." />
                </div>
            </x-admin.card>
            <x-admin.card title="Hero Configuration">
                <p class="text-xs text-text-secondary mb-4">Set the hero background. Video takes priority. If no video, multiple images = slider. Single image = static hero.</p>
                <div class="space-y-5">
                    <x-admin.form-media name="hero_media_id" label="Primary Hero Image" :mediaAsset="$city->heroMedia ?? null" tooltip="Primary hero background image. Used as single background or as the first slide in slider mode." :croppable="true" />
                    <div>
                        <label for="hero_video_url" class="block text-sm font-medium text-text mb-1.5">Hero Video URL <span class="text-xs text-text-secondary font-normal ml-1">(mp4, overrides all images)</span></label>
                        <input type="url" id="hero_video_url" name="hero_video_url" value="{{ old('hero_video_url', $city->hero_video_url ?? '') }}" placeholder="https://cdn.example.com/hero.mp4" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                    </div>
                    <div class="border-t border-gray-100 pt-5">
                        <p class="text-xs font-semibold text-text-secondary uppercase tracking-wide mb-3">Slider Images (shown when no video is set)</p>
                        <div class="grid grid-cols-1 gap-4">
                            <x-admin.form-media name="hero_image_2_media_id" label="Slide 2" :mediaAsset="$city->heroImage2 ?? null" tooltip="Second image in the hero slider. Only shown if no video is set." />
                            <x-admin.form-media name="hero_image_3_media_id" label="Slide 3" :mediaAsset="$city->heroImage3 ?? null" tooltip="Third image in the hero slider." />
                            <x-admin.form-media name="hero_image_4_media_id" label="Slide 4" :mediaAsset="$city->heroImage4 ?? null" tooltip="Fourth image in the hero slider." />
                        </div>
                    </div>
                </div>
            </x-admin.card>
            @if(isset($city))
            {{-- Service Categories for this City --}}
            <x-admin.card title="Service Categories">
                <p class="text-xs text-text-secondary mb-4">
                    Check which service categories are offered in <strong>{{ $city->name }}</strong>. Saving will auto-activate all services in checked categories and deactivate services in unchecked ones.
                </p>
                @if(isset($allCategories) && $allCategories->isNotEmpty())
                <div class="space-y-2">
                    @foreach($allCategories as $cat)
                    <label class="flex items-center gap-3 p-2.5 rounded-xl hover:bg-gray-50 transition cursor-pointer group">
                        <input type="checkbox"
                               id="category_{{ $cat->id }}"
                               name="category_ids[]"
                               value="{{ $cat->id }}"
                               {{ in_array($cat->id, $activeCategoryIds ?? []) ? 'checked' : '' }}
                               class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest/30 cursor-pointer">
                        <div class="flex items-center gap-2 flex-1 min-w-0">
                            <i data-lucide="{{ $cat->icon ?? 'layers' }}" class="w-3.5 h-3.5 text-forest shrink-0"></i>
                            <span class="text-xs font-medium text-text group-hover:text-forest transition truncate">{{ $cat->name }}</span>
                            <span class="ml-auto text-xs text-text-secondary shrink-0">{{ $cat->services_count ?? 0 }} services</span>
                        </div>
                    </label>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-text-secondary">No published service categories found.</p>
                @endif
            </x-admin.card>
            @endif

            @if(isset($city) && $city->servicePages->isNotEmpty())
            <x-admin.card title="City Pages">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <p class="text-xs text-text-secondary">Service-city pages for {{ $city->name }}.</p>
                    <a href="{{ route('admin.service-city-matrix') }}?city={{ $city->id }}" class="text-xs text-forest hover:underline font-medium">Matrix View &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-text-secondary uppercase tracking-wide border-b border-gray-100">
                                <th class="pb-2 pr-4">Service</th>
                                <th class="pb-2 pr-4">Category</th>
                                <th class="pb-2 pr-4">Status</th>
                                <th class="pb-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($city->servicePages->sortBy(fn($sp) => $sp->service?->name) as $sp)
                            <tr class="group">
                                <td class="py-2 pr-4 font-medium text-text text-xs">{{ $sp->service?->name ?? '-' }}</td>
                                <td class="py-2 pr-4 text-text-secondary text-xs">{{ $sp->service?->category?->name ?? '-' }}</td>
                                <td class="py-2 pr-4">
                                    @if($sp->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Active</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('admin.service-city-pages.edit', $sp) }}" class="text-xs text-forest hover:underline font-medium">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
            @endif

            {{-- Save buttons --}}
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($city) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.cities.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
