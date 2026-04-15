@extends('admin.layouts.app')
@section('title', isset($service) ? 'Edit Service' : 'Create Service')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($service) ? 'Edit: ' . $service->name : 'Create Service'" :viewUrl="isset($service) ? url('/services/' . ($service->category?->slug_final ?? '_') . '/' . $service->slug_final) : null" />
<form method="POST" action="{{ isset($service) ? route('admin.services.update', $service) : route('admin.services.store') }}" data-ajax-form="true" data-success-message="{{ isset($service) ? 'Service updated successfully.' : 'Service created.' }}">
    @csrf
    @if(isset($service)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Basic Information">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-input name="name" label="Service Name" :value="$service->name ?? ''" required tooltip="The service name displayed on the page and in navigation menus." />
                    <x-admin.form-select name="category_id" label="Category" :options="$categories->toArray()" :value="$service->category_id ?? ''" required tooltip="The parent service category this service belongs to. Determines where it appears in the mega menu." />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
                    <x-admin.form-input name="navigation_label" label="Nav Label" :value="$service->navigation_label ?? ''" tooltip="Short label for menus if the full service name is too long to display." />
                    <x-admin.form-input name="service_code" label="Service Code" :value="$service->service_code ?? ''" tooltip="Internal reference code for this service. Used for admin identification and reporting only." />
                </div>
                @if(isset($service))
                <div class="mt-5"><x-admin.form-input name="custom_slug" label="Custom Slug" :value="$service->custom_slug ?? ''" help="Auto: {{ $service->system_slug }}" tooltip="Override the auto-generated URL slug. Changing this breaks existing inbound links. Only change if necessary." /></div>
                @endif
                <div class="mt-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="service_summary" context="Service summary. 1-2 sentences describing this landscaping service. Customer-facing." />
                    </div>
                    <x-admin.form-textarea name="service_summary" label="Service Summary" :value="$service->service_summary ?? ''" :rows="4" tooltip="Brief summary shown in service cards and search results. Keep under 160 characters for best display." />
                </div>
            </x-admin.card>

            <x-admin.card title="Page Content Sections">
                @php
                    $benefitsVal  = !empty($service->service_body['benefits'])  ? implode("\n", (array)$service->service_body['benefits'])  : '';
                    $materialsVal = !empty($service->service_body['materials']) ? implode("\n", (array)$service->service_body['materials']) : '';
                @endphp
                <p class="text-xs text-text-secondary mb-4">These populate the structured sections on the frontend service detail page.</p>
                <div class="space-y-5">
                    <x-admin.form-textarea name="service_body[what_is]" label="What Is This Service?" :value="$service->service_body['what_is'] ?? ''" :rows="4" help="Plain-language description shown in the 'What Is' section." tooltip="Plain-language explanation of what this service involves. Shown in the 'What Is' section of the service page. Important for visitor understanding and SEO." />
                    <x-admin.form-textarea name="service_body[benefits]" label="Benefits (one per line)" :value="$benefitsVal" :rows="5" help="Each line = one benefit point shown as an icon bullet." tooltip="List the key benefits of this service, one per line. Each line becomes an icon bullet point on the service page." />
                    <x-admin.form-textarea name="service_body[pricing_note]" label="Pricing Note" :value="$service->service_body['pricing_note'] ?? ''" :rows="2" help="e.g. Starting at $12/sq ft. Varies by material and scope" tooltip="Approximate pricing note shown on the service page. Helps set visitor expectations. Example: Starting at $12/sq ft. Varies by material and scope." />
                    <x-admin.form-textarea name="service_body[materials]" label="Materials / Brands (one per line)" :value="$materialsVal" :rows="3" help="e.g. Permacon, Unilock, Techo-Bloc. Shown as trust badges." tooltip="List materials or brand partners used in this service, one per line. Displayed as trust badges on the service page. Example: Permacon, Unilock, Techo-Bloc." />
                </div>
            </x-admin.card>

            {{-- Unified Page Builder --}}
            @if(isset($service))
            <x-admin.card title="Page Layout & Content">
                <p class="text-xs text-text-secondary mb-6 italic">
                    Drag and drop any element to reorder. Use the <i data-lucide="eye" class="w-3 h-3 inline"></i> toggle to enable/disable, 
                    <i data-lucide="monitor" class="w-3 h-3 inline"></i> / <i data-lucide="smartphone" class="w-3 h-3 inline"></i> to control device visibility, 
                    and <i data-lucide="settings-2" class="w-3 h-3 inline"></i> to edit settings.
                </p>
                <x-admin.block-editor
                    pageType="service"
                    :pageId="$service->id"
                    :blocks="$blocks->toArray()"
                    :blockTypes="$blockTypes ?? []"
                />
            </x-admin.card>
            <x-admin.content-block-export type="service" :id="$service->id" />
            @endif
            @if(isset($service) && $service->cityPages->isNotEmpty())
            <x-admin.card title="City Pages">
                <div class="flex flex-wrap items-center justify-between gap-2 mb-4">
                    <p class="text-xs text-text-secondary">Service-city pages linked to this service.</p>
                    <a href="{{ route('admin.service-city-matrix') }}?service={{ $service->id }}" class="text-xs text-forest hover:underline font-medium">Matrix View &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="text-left text-xs text-text-secondary uppercase tracking-wide border-b border-gray-100">
                                <th class="pb-2 pr-4">City</th>
                                <th class="pb-2 pr-4">Slug</th>
                                <th class="pb-2 pr-4">Status</th>
                                <th class="pb-2 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($service->cityPages->sortBy(fn($cp) => $cp->city?->name) as $cp)
                            <tr class="group">
                                <td class="py-2 pr-4 font-medium text-text">{{ $cp->city?->name ?? '-' }}</td>
                                <td class="py-2 pr-4 text-text-secondary text-xs font-mono truncate max-w-[180px]">{{ $cp->slug_final }}</td>
                                <td class="py-2 pr-4">
                                    @if($cp->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-50 text-green-700">Active</span>
                                    @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-500">Inactive</span>
                                    @endif
                                </td>
                                <td class="py-2 text-right">
                                    <a href="{{ route('admin.service-city-pages.edit', $cp) }}" class="text-xs text-forest hover:underline font-medium">Edit</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-admin.card>
            @endif
            <x-admin.card title="Target Keywords">
                <div class="space-y-4">
                    <p class="text-xs text-text-secondary">Define target keywords. Used to guide AI content generation and SEO.</p>
                    @php $kw = $service->keywords_json ?? []; @endphp
                    <x-admin.form-input name="keywords_primary" label="Primary Keywords" :value="implode(', ', $kw['primary'] ?? [])" help="Comma-separated." />
                    <x-admin.form-input name="keywords_secondary" label="Secondary Keywords" :value="implode(', ', $kw['secondary'] ?? [])" help="Comma-separated." />
                    <x-admin.form-input name="keywords_long_tail" label="Long-Tail Keywords" :value="implode(', ', $kw['long_tail'] ?? [])" help="Comma-separated." />
                </div>
            </x-admin.card>

            <x-admin.card title="SEO & Social">
                <div class="space-y-5">
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="default_meta_title" context="Service SEO meta title. Max 60 characters." />
                    </div>
                    <x-admin.form-input name="default_meta_title" label="Meta Title" :value="$service->default_meta_title ?? ''" help="Max 60 characters" tooltip="Page title for search engines. Keep under 60 characters. Include the service name and 'Ontario' for local SEO impact." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="default_meta_description" context="Service SEO meta description. Max 155 characters." />
                    </div>
                    <x-admin.form-textarea name="default_meta_description" label="Meta Description" :value="$service->default_meta_description ?? ''" :rows="2" help="Max 160 characters" tooltip="Search result summary shown under the page title. Keep under 160 characters. Include the service name and a clear call to action." />
                    <x-admin.form-input name="default_og_title" label="OG Title" :value="$service->default_og_title ?? ''" tooltip="Title for social media link previews when this service page is shared. Can differ from the meta title." />
                    <x-admin.form-textarea name="default_og_description" label="OG Description" :value="$service->default_og_description ?? ''" :rows="2" tooltip="Description for social media link previews. Keep under 200 characters. Should be engaging and action-oriented." />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" :value="$service->status ?? 'draft'" required tooltip="Published = visible on the frontend. Draft = hidden from public. Archived = hidden and removed from sitemap." />
                    <x-admin.form-input name="sort_order" label="Sort Order" type="number" :value="$service->sort_order ?? 0" tooltip="Display order within the category. Lower numbers appear higher in the list and mega menu." />
                    <x-admin.form-select name="parent_id" label="Parent Service" :options="$parents->toArray()" :value="$service->parent_id ?? ''" placeholder="None" tooltip="Optional parent service. Use to create sub-services under a root service. Affects breadcrumbs and navigation hierarchy." />
                    <x-admin.form-input name="icon" label="Icon (Lucide name)" :value="$service->icon ?? ''" help="e.g. layers, wrench, hammer" tooltip="Lucide icon name used in the mega menu and service cards. Browse available icons at lucide.dev. Example: layers, wrench, hammer." />
                </div>
            </x-admin.card>
            <x-admin.card title="Hero Configuration">
                <p class="text-xs text-text-secondary mb-4">Set the hero background. Video takes priority. If no video, multiple images = slider. Single image = static hero.</p>
                <div class="space-y-5">
                    <x-admin.form-media name="hero_media_id" label="Primary Hero Image" :mediaAsset="$service->heroMedia ?? null" tooltip="Primary hero background image. Used as single background or as the first slide in slider mode. Also shown in service cards." :croppable="true" />
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Hero Video URL <span class="text-xs text-text-secondary font-normal ml-1">(mp4, overrides all images)</span></label>
                        <input type="url" name="hero_video_url" value="{{ old('hero_video_url', $service->hero_video_url ?? '') }}" placeholder="https://cdn.example.com/hero.mp4" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition">
                    </div>
                    <div class="border-t border-gray-100 pt-5">
                        <p class="text-xs font-semibold text-text-secondary uppercase tracking-wide mb-3">Slider Images (shown when no video is set)</p>
                        <div class="grid grid-cols-1 gap-4">
                            <x-admin.form-media name="hero_image_2_media_id" label="Slide 2" :mediaAsset="$service->heroImage2 ?? null" tooltip="Second image in the hero slider. Only shown if no video is set." />
                            <x-admin.form-media name="hero_image_3_media_id" label="Slide 3" :mediaAsset="$service->heroImage3 ?? null" tooltip="Third image in the hero slider." />
                            <x-admin.form-media name="hero_image_4_media_id" label="Slide 4" :mediaAsset="$service->heroImage4 ?? null" tooltip="Fourth image in the hero slider." />
                        </div>
                    </div>
                </div>
            </x-admin.card>
            <x-admin.card title="City Availability">
                @php $assignedCityIds = isset($service) ? $service->cities->pluck('id')->toArray() : []; @endphp
                <p class="text-xs text-text-secondary mb-3">Leave all unchecked to make this service available globally in all cities.</p>
                <div class="space-y-2 max-h-56 overflow-y-auto pr-1">
                    @foreach($cities as $cityId => $cityName)
                    <label class="flex items-center gap-2.5 cursor-pointer group">
                        <input type="checkbox" name="city_ids[]" value="{{ $cityId }}" {{ in_array($cityId, $assignedCityIds) ? 'checked' : '' }}
                            class="w-4 h-4 rounded border-gray-300 text-forest focus:ring-forest/30 cursor-pointer">
                        <span class="text-sm text-text group-hover:text-forest transition">{{ $cityName }}</span>
                    </label>
                    @endforeach
                </div>
            </x-admin.card>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($service) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.services.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
