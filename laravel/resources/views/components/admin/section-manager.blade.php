@props(['sections', 'pageType'])

@php
    // Prepare sections as a flat JS-safe array
    $sectionsJson = json_encode(array_values(array_map(fn($s) => [
        'key'        => $s['key'],
        'label'      => $s['label'],
        'icon'       => $s['icon'],
        'is_enabled' => $s['is_enabled'],
        'desktop'    => $s['desktop'],
        'mobile'     => $s['mobile'],
        'sort_order' => $s['sort_order'],
        'settings'   => (object) ($s['settings'] ?? []),
        'expanded'   => false,
    ], $sections)));
@endphp

<div x-data="sectionManager({{ $sectionsJson }})" x-init="init()">
    <input type="hidden" name="sections_config" :value="sectionsJson">

    <div class="space-y-2" x-ref="sectionList">
        <template x-for="(section, index) in sections" :key="section.key">
            <div class="border border-gray-200 rounded-xl overflow-hidden transition-all"
                 :class="section.is_enabled ? 'bg-white' : 'bg-gray-50'"
                 data-section-item
                 :data-key="section.key">

                {{-- Section Row --}}
                <div class="flex flex-wrap items-start gap-3 px-4 py-3 sm:flex-nowrap sm:items-center">

                    {{-- Drag Handle --}}
                    <div data-section-drag class="shrink-0 cursor-grab active:cursor-grabbing text-gray-300 hover:text-gray-500 transition touch-none">
                        <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                    </div>

                    {{-- Enable Toggle --}}
                    <button type="button" x-on:click="section.is_enabled = !section.is_enabled"
                        class="shrink-0 w-10 h-6 rounded-full transition-colors duration-200 relative"
                        :class="section.is_enabled ? 'bg-forest' : 'bg-gray-200'">
                        <span class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform duration-200"
                              :class="section.is_enabled ? 'translate-x-4' : 'translate-x-0'"></span>
                    </button>

                    {{-- Icon + Label --}}
                    <div class="flex items-center gap-2.5 flex-1 min-w-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 transition-colors"
                             :class="section.is_enabled ? 'bg-forest-50 text-forest' : 'bg-gray-100 text-gray-400'">
                            <i :data-lucide="section.icon" class="w-4 h-4"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-text truncate" x-text="section.label"></p>
                            <p class="text-xs text-text-secondary font-mono" x-text="section.key"></p>
                        </div>
                    </div>

                    {{-- Visibility Toggles: Desktop / Mobile --}}
                    <div class="ml-auto flex items-center gap-1 shrink-0 sm:ml-0">
                        <button type="button" x-on:click="section.desktop = !section.desktop"
                            :title="section.desktop ? 'Desktop: visible (click to hide)' : 'Desktop: hidden (click to show)'"
                            class="flex items-center gap-1 px-2 py-1.5 rounded-lg text-xs font-medium transition border"
                            :class="section.desktop ? 'bg-blue-50 text-blue-600 border-blue-200' : 'bg-gray-50 text-gray-300 border-gray-200'">
                            <i data-lucide="monitor" class="w-3.5 h-3.5"></i>
                            <span class="text-[10px] leading-none" x-text="section.desktop ? 'ON' : 'OFF'"></span>
                        </button>
                        <button type="button" x-on:click="section.mobile = !section.mobile"
                            :title="section.mobile ? 'Mobile: visible (click to hide)' : 'Mobile: hidden (click to show)'"
                            class="flex items-center gap-1 px-2 py-1.5 rounded-lg text-xs font-medium transition border"
                            :class="section.mobile ? 'bg-purple-50 text-purple-600 border-purple-200' : 'bg-gray-50 text-gray-300 border-gray-200'">
                            <i data-lucide="smartphone" class="w-3.5 h-3.5"></i>
                            <span class="text-[10px] leading-none" x-text="section.mobile ? 'ON' : 'OFF'"></span>
                        </button>
                    </div>

                    {{-- Expand Settings --}}
                    <button type="button" x-on:click="section.expanded = !section.expanded"
                        class="shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-text-secondary hover:bg-gray-100 transition"
                        :class="section.expanded ? 'bg-gray-100' : ''">
                        <i data-lucide="settings-2" class="w-4 h-4"></i>
                    </button>
                </div>

                {{-- Settings Panel --}}
                <div x-show="section.expanded" x-collapse class="border-t border-gray-100 bg-gray-50 px-4 py-4">
                    <p class="text-xs font-semibold text-text-secondary uppercase tracking-wide mb-3">Section Settings</p>

                    {{-- Hero / CTA section: heading, subtitle, cta_text, cta_url, video_url, extra_image_ids --}}
                    <template x-if="section.key === 'hero'">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Heading Override <span class="text-text-secondary font-normal">(blank = auto)</span></label>
                                <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Subtitle Override</label>
                                <input type="text" x-model="section.settings.subtitle" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">CTA Button Text</label>
                                    <input type="text" x-model="section.settings.cta_text" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">CTA Button URL</label>
                                    <input type="text" x-model="section.settings.cta_url" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                            </div>
                            @if($pageType === 'home')
                            <div class="border-t border-gray-200 pt-3">
                                <p class="text-xs font-semibold text-text-secondary uppercase tracking-wide mb-2">Background Media</p>
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">Video URL <span class="text-text-secondary font-normal">(mp4, overrides images)</span></label>
                                    <input type="url" x-model="section.settings.video_url" placeholder="https://cdn.example.com/hero.mp4" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                                <div class="mt-2">
                                    <label class="block text-xs font-medium text-text mb-1">Extra Image IDs <span class="text-text-secondary font-normal">(comma-separated, enables slider)</span></label>
                                    <input type="text" x-model="section.settings.extra_image_ids" placeholder="e.g. 3, 7, 12" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                    <p class="mt-1 text-xs text-text-secondary">Primary hero image (set on the record) is always first. Add more IDs here for a slider.</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </template>

                    {{-- Services Grid: heading --}}
                    <template x-if="section.key === 'services_grid'">
                        <div>
                            <label class="block text-xs font-medium text-text mb-1">Heading Override <span class="text-text-secondary font-normal">(blank = auto)</span></label>
                            <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                        </div>
                    </template>

                    {{-- Process Steps: heading + steps array --}}
                    <template x-if="section.key === 'process_steps'">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Section Heading</label>
                                <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div class="space-y-2">
                                <p class="text-xs font-medium text-text">Steps</p>
                                <template x-for="(step, si) in section.settings.steps" :key="si">
                                    <div class="bg-white border border-gray-200 rounded-lg p-3 space-y-2">
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 rounded-full bg-forest text-white text-xs flex items-center justify-center shrink-0 font-bold" x-text="si + 1"></span>
                                            <input type="text" x-model="step.title" placeholder="Step title" class="flex-1 px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-forest/30">
                                        </div>
                                        <textarea x-model="step.desc" placeholder="Step description" rows="2" class="w-full px-3 py-1.5 border border-gray-200 rounded-lg text-xs focus:outline-none focus:ring-2 focus:ring-forest/30 resize-none"></textarea>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    {{-- Portfolio Gallery / Testimonials: heading + limit --}}
                    <template x-if="section.key === 'portfolio_gallery' || section.key === 'testimonials'">
                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Heading Override</label>
                                <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Max Items</label>
                                <input type="number" min="1" max="12" x-model.number="section.settings.limit" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                        </div>
                    </template>

                    {{-- FAQ Section: heading + limit + faq_category_id --}}
                    <template x-if="section.key === 'faq_section'">
                        <div class="space-y-3">
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">Heading Override</label>
                                    <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">Max FAQs</label>
                                    <input type="number" min="1" max="20" x-model.number="section.settings.limit" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">FAQ Category ID <span class="text-text-secondary font-normal">(blank = all)</span></label>
                                <input type="number" min="1" x-model.number="section.settings.faq_category_id" placeholder="e.g. 3" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                        </div>
                    </template>

                    {{-- CTA Section: title, subtitle, button_text, button_url --}}
                    <template x-if="section.key === 'cta_section'">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Title Override <span class="text-text-secondary font-normal">(blank = auto)</span></label>
                                <input type="text" x-model="section.settings.title" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Subtitle</label>
                                <input type="text" x-model="section.settings.subtitle" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">Button Text</label>
                                    <input type="text" x-model="section.settings.button_text" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">Button URL</label>
                                    <input type="text" x-model="section.settings.button_url" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- Service Hero settings --}}
                    <template x-if="section.key === 'service_hero'">
                        <div class="space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Heading Override <span class="text-text-secondary font-normal">(blank = service name)</span></label>
                                <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-text mb-1">Subtitle Override <span class="text-text-secondary font-normal">(blank = service summary)</span></label>
                                <input type="text" x-model="section.settings.subtitle" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                            </div>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">CTA Button Text</label>
                                    <input type="text" x-model="section.settings.cta_text" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-text mb-1">CTA Button URL</label>
                                    <input type="text" x-model="section.settings.cta_url" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                                </div>
                            </div>
                        </div>
                    </template>

                    {{-- City Availability settings --}}
                    <template x-if="section.key === 'city_availability'">
                        <div>
                            <label class="block text-xs font-medium text-text mb-1">Heading Override <span class="text-text-secondary font-normal">(blank = auto)</span></label>
                            <input type="text" x-model="section.settings.heading" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                        </div>
                    </template>

                    {{-- No settings sections: stats_bar, local_about, local_intro, trust_badges, service_body --}}
                    <template x-if="['stats_bar','local_about','local_intro','trust_badges','service_body'].includes(section.key)">
                        <p class="text-xs text-text-secondary italic">This section has no configurable settings. Its content is driven automatically.</p>
                    </template>
                </div>
            </div>
        </template>
    </div>

    <p class="mt-3 text-xs text-text-secondary">
        <i data-lucide="info" class="w-3 h-3 inline mr-1"></i>
        Drag to reorder. Toggle on/off, control desktop <span class="text-blue-500 font-medium">&#9635;</span> / mobile <span class="text-purple-500 font-medium">&#9711;</span> visibility, and expand settings per section.
    </p>
</div>
