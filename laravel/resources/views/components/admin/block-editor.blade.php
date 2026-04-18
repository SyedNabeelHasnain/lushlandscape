@props(['pageType', 'pageId', 'blocks' => [], 'blockTypes' => []])

{{--
Block Editor component.
Usage: <x-admin.block-editor :pageType="'static_page'" :pageId="$page->id" :blocks="$blocks"
    :blockTypes="$blockTypes" />
Outputs a hidden input `blocks_json` with the serialized block array on form submit.
--}}

@php
    $styleFields = \App\Services\BlockBuilderService::styleFields();
    $styleDefaults = \App\Services\BlockBuilderService::styleDefaults();
    $dynamicVariableGroups = app(\App\Services\BlockVariableService::class)->editorVariableGroups();
@endphp
<div x-data="blockEditor('{{ $pageType }}', {{ json_encode($blocks ?: []) }}, {{ json_encode($blockTypes ?: []) }}, {{ json_encode($styleFields ?: []) }}, {{ json_encode($styleDefaults ?: []) }}, {{ json_encode($dynamicVariableGroups ?: []) }})" class="space-y-4">
    {{-- Hidden input carrying block data --}}
    <input type="hidden" name="blocks_json" :value="blocksJson">

        </div>

    {{-- Toolbar: Add Block --}}
    <div class="flex flex-wrap items-center gap-3">
        <div class="relative" x-data="{ addOpen: false, addSearch: '', addCategory: 'all' }">
            <button type="button" x-on:click="addOpen = !addOpen"
                class="flex items-center gap-2 px-4 py-2 bg-forest text-white text-sm font-medium rounded-xl hover:bg-forest-dark transition">
                <i data-lucide="plus" class="w-4 h-4"></i>
                Add Block
                <i data-lucide="chevron-down" class="w-3.5 h-3.5 ml-1 transition-transform"
                    :class="addOpen ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="addOpen" x-cloak x-on:click.outside="addOpen = false"
                class="absolute top-full left-0 mt-1 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl z-50">
                <div class="border-b border-gray-100 p-3">
                    <input type="text" x-model="addSearch" placeholder="Search blocks..."
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/20">
                    <div class="mt-2 flex flex-wrap gap-1">
                        <button type="button" x-on:click="addCategory = 'all'"
                            class="rounded-full px-2.5 py-1 text-[11px] font-medium transition"
                            :class="addCategory === 'all' ? 'bg-forest text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'">
                            All
                        </button>
                        <template x-for="category in availableBlockCategories()" :key="category">
                            <button type="button" x-on:click="addCategory = category"
                                class="rounded-full px-2.5 py-1 text-[11px] font-medium capitalize transition"
                                :class="addCategory === category ? 'bg-forest text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200'">
                                <span x-text="category"></span>
                            </button>
                        </template>
                    </div>
                </div>
                <div class="max-h-80 overflow-y-auto py-2">
                <template x-if="filteredAddBlockTypes(addSearch, addCategory).length === 0">
                    <p class="px-4 py-6 text-center text-sm text-gray-400">No matching blocks found.</p>
                </template>
                <template x-for="type in filteredAddBlockTypes(addSearch, addCategory)" :key="type.key">
                    <button type="button" x-on:click="addBlock(type.key); addOpen = false"
                        class="w-full flex items-center gap-3 px-4 py-2.5 hover:bg-forest-50 transition text-left">
                        <div class="w-7 h-7 rounded-lg bg-forest/10 flex items-center justify-center shrink-0">
                            <i :data-lucide="type.icon" class="w-3.5 h-3.5 text-forest"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-1.5">
                                <span class="text-sm font-medium text-gray-700" x-text="type.label"></span>
                                <span class="rounded-full bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-gray-500" x-text="type.category"></span>
                                <span x-show="type.supports_children" x-cloak class="rounded-full bg-amber-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-amber-700">Nested</span>
                                <span x-show="type.data_source" x-cloak class="rounded-full bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">Data</span>
                            </div>
                            <p class="mt-0.5 text-[11px] text-gray-400" x-text="(type.content_fields?.length || 0) + ' configurable field' + ((type.content_fields?.length || 0) === 1 ? '' : 's')"></p>
                        </div>
                    </button>
                </template>
                </div>
            </div>
        </div>
        <span class="text-xs text-gray-400" x-text="blocks.length + ' block' + (blocks.length === 1 ? '' : 's')"></span>
    </div>

    {{-- Empty state --}}
    <template x-if="blocks.length === 0">
        <div class="border-2 border-dashed border-gray-200 rounded-2xl py-12 text-center">
            <i data-lucide="layout-template" class="w-10 h-10 text-gray-300 mx-auto mb-3"></i>
            <p class="text-sm text-gray-400">No blocks yet. Click "Add Block" to start building.</p>
        </div>
    </template>

    {{-- Block list --}}
    <div class="space-y-3" x-ref="blockList">
        <template x-for="(block, index) in blocks" :key="block._uid">
            <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden shadow-sm" data-block-item
                :data-uid="block._uid" :class="block.is_enabled ? '' : 'opacity-60'">

                {{-- Block header bar --}}
                <div class="flex flex-wrap items-start gap-3 px-4 py-3 bg-gray-50 border-b border-gray-100 sm:flex-nowrap sm:items-center">
                    {{-- Drag handle --}}
                    <div data-drag-handle
                        class="cursor-grab active:cursor-grabbing text-gray-300 hover:text-gray-500 transition touch-none">
                        <i data-lucide="grip-vertical" class="w-4 h-4"></i>
                    </div>

                    {{-- Block type badge --}}
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        <div class="w-6 h-6 rounded-md bg-forest/10 flex items-center justify-center shrink-0">
                            <i :data-lucide="getTypeIcon(block.block_type)" class="w-3 h-3 text-forest"></i>
                        </div>
                        <span class="text-xs font-semibold text-gray-600 uppercase tracking-wide"
                            x-text="getTypeLabel(block.block_type)"></span>
                        {{-- Preview of first text field --}}
                        <span class="hidden text-xs text-gray-400 truncate sm:inline" x-text="blockPreview(block)"></span>
                    </div>

                    {{-- Controls --}}
                    <div class="ml-auto flex flex-wrap items-center gap-1 shrink-0 sm:ml-0">
                        {{-- Enable/disable toggle --}}
                        <button type="button" x-on:click="block.is_enabled = !block.is_enabled"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
                            :title="block.is_enabled ? 'Disable block' : 'Enable block'">
                            <i :data-lucide="block.is_enabled ? 'eye' : 'eye-off'"
                                class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        {{-- Visibility toggles (Desktop/Mobile) --}}
                        <button type="button" x-on:click="block.show_on_desktop = !block.show_on_desktop"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
                            :title="block.show_on_desktop ? 'Hide on Desktop' : 'Show on Desktop'">
                            <i data-lucide="monitor" class="w-3.5 h-3.5"
                                :class="block.show_on_desktop ? 'text-forest' : 'text-gray-300'"></i>
                        </button>
                        <button type="button" x-on:click="block.show_on_tablet = !block.show_on_tablet"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
                            :title="block.show_on_tablet ? 'Hide on Tablet' : 'Show on Tablet'">
                            <i data-lucide="tablet" class="w-3.5 h-3.5"
                                :class="block.show_on_tablet ? 'text-forest' : 'text-gray-300'"></i>
                        </button>
                        <button type="button" x-on:click="block.show_on_mobile = !block.show_on_mobile"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
                            :title="block.show_on_mobile ? 'Hide on Mobile' : 'Show on Mobile'">
                            <i data-lucide="smartphone" class="w-3.5 h-3.5"
                                :class="block.show_on_mobile ? 'text-forest' : 'text-gray-300'"></i>
                        </button>
                        {{-- Move up --}}
                        <button type="button" x-on:click="moveBlock(index, -1)" :disabled="index === 0"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition disabled:opacity-30">
                            <i data-lucide="chevron-up" class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        {{-- Move down --}}
                        <button type="button" x-on:click="moveBlock(index, 1)" :disabled="index === blocks.length - 1"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition disabled:opacity-30">
                            <i data-lucide="chevron-down" class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        {{-- Expand/collapse --}}
                        <button type="button" x-on:click="block._open = !block._open"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition">
                            <i data-lucide="settings-2" class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        <button type="button" x-on:click="copyBlock(index)"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
                            title="Copy block configuration">
                            <i data-lucide="copy" class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        <button type="button" x-on:click="duplicateBlock(index)"
                            class="p-1.5 rounded-lg hover:bg-gray-100 transition"
                            title="Duplicate block">
                            <i data-lucide="files" class="w-3.5 h-3.5 text-gray-400"></i>
                        </button>
                        {{-- Delete --}}
                        <button type="button" x-on:click="deleteBlock(index)"
                            class="p-1.5 rounded-lg hover:bg-red-50 transition">
                            <i data-lucide="trash-2" class="w-3.5 h-3.5 text-red-400"></i>
                        </button>
                    </div>
                </div>

                {{-- Block content fields (collapsible) --}}
                <div x-show="block._open" x-cloak x-collapse class="divide-y divide-gray-50">
                    <div class="p-5 space-y-4">
                        <div class="space-y-4">
                            <template x-if="getTypeFields(block).length === 0">
                                    <p class="text-xs text-gray-400 italic">No configurable fields for this block type.
                                    </p>
                                </template>
                                <template x-for="field in getTypeFields(block)" :key="field.key">
                                    <div>
                                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                                            <label class="block text-xs font-semibold text-gray-600"
                                                x-text="field.label"></label>
                                            <template x-if="['text', 'textarea'].includes(field.type)">
                                                <div class="relative" x-data="{ dynOpen: false, dynSearch: '' }">
                                                    <button type="button"
                                                        @click="dynOpen = !dynOpen; if (dynOpen) { $nextTick(() => $refs.dynamicVariableSearch?.focus()); }"
                                                        class="text-[10px] text-forest hover:text-forest-dark flex items-center gap-1 font-medium bg-forest/5 px-2 py-0.5 rounded cursor-pointer transition">
                                                        <i data-lucide="database" class="w-3 h-3"></i> Insert Variable
                                                    </button>
                                                    <div x-show="dynOpen" @click.outside="dynOpen = false" x-cloak
                                                        class="absolute right-0 mt-1 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl z-50">
                                                        <div class="border-b border-gray-100 p-2">
                                                            <input x-ref="dynamicVariableSearch" type="text" x-model="dynSearch"
                                                                placeholder="Search variables..."
                                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-forest/20">
                                                        </div>
                                                        <div class="max-h-80 space-y-2 overflow-y-auto p-2">
                                                            <template x-if="filteredDynamicVariableGroups(dynSearch).length === 0">
                                                                <p class="px-2 py-3 text-xs text-gray-400">No matching variables found.</p>
                                                            </template>
                                                            <template x-for="group in filteredDynamicVariableGroups(dynSearch)" :key="group.key">
                                                                <div class="space-y-1">
                                                                    <div class="px-2 pt-1">
                                                                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400" x-text="group.label"></p>
                                                                        <p class="text-[10px] text-gray-400" x-text="group.description"></p>
                                                                    </div>
                                                                    <template x-for="variable in group.variables" :key="variable.token">
                                                                        <button type="button"
                                                                            @click="insertDynamicVariable(block, field.key, variable.token); dynOpen = false; dynSearch = ''"
                                                                            class="w-full rounded-lg px-2 py-2 text-left transition hover:bg-gray-50">
                                                                            <span class="block text-[11px] font-semibold text-forest" x-text="'{' + variable.token + '}'"></span>
                                                                            <span class="block text-xs text-gray-700" x-text="variable.label"></span>
                                                                            <span class="block text-[10px] text-gray-400" x-text="variable.description"></span>
                                                                        </button>
                                                                    </template>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- text --}}
                                        <template x-if="field.type === 'text'">
                                            <input type="text" x-model="block.content[field.key]"
                                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest">
                                        </template>

                                        {{-- number --}}
                                        <template x-if="field.type === 'number'">
                                            <input type="number" x-model="block.content[field.key]"
                                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest"
                                                :min="field.min ?? ''" :max="field.max ?? ''" :step="field.step ?? 1">
                                        </template>

                                        {{-- select_model (model picker — renders as text input with slug hint) --}}
                                        <template x-if="field.type === 'select_model'">
                                            <div>
                                                <input type="text" x-model="block.content[field.key]"
                                                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest"
                                                    :placeholder="'Enter slug or ID'">
                                                <p class="text-[10px] text-gray-400 mt-1">Enter the slug or numeric ID of the record.</p>
                                            </div>
                                        </template>

                                        {{-- media_multi --}}
                                        <template x-if="field.type === 'media_multi'">
                                            @include('components.admin.partials.block-media-multi-picker', [
                                                'fieldKeyExpression' => 'field.key',
                                                'modelExpression' => 'block.content[field.key]',
                                            ])
                                        </template>

                                        {{-- textarea --}}
                                        <template x-if="field.type === 'textarea'">
                                            <textarea x-model="block.content[field.key]" rows="4"
                                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest resize-y"></textarea>
                                        </template>

                                        {{-- code / richtext (fallback to textarea for now) --}}
                                        <template x-if="['code', 'richtext'].includes(field.type)">
                                            <textarea x-model="block.content[field.key]" rows="6"
                                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest font-mono resize-y"></textarea>
                                        </template>

                                        {{-- select --}}
                                        <template x-if="field.type === 'select'">
                                            <select x-model="block.content[field.key]"
                                                class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest bg-white">
                                                <template x-for="(optLabel, optVal) in field.options" :key="optVal">
                                                    <option :value="optVal" x-text="optLabel"></option>
                                                </template>
                                            </select>
                                        </template>

                                        {{-- toggle --}}
                                        <template x-if="field.type === 'toggle'">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <div class="relative">
                                                    <input type="checkbox" x-model="block.content[field.key]"
                                                        class="sr-only">
                                                    <div class="w-10 h-5 rounded-full transition"
                                                        :class="block.content[field.key] ? 'bg-forest' : 'bg-gray-200'">
                                                    </div>
                                                    <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform"
                                                        :class="block.content[field.key] ? 'translate-x-5' : 'translate-x-0'">
                                                    </div>
                                                </div>
                                                <span class="text-xs text-gray-500"
                                                    x-text="block.content[field.key] ? 'Enabled' : 'Disabled'"></span>
                                            </label>
                                        </template>

                                        {{-- media picker --}}
                                        <template x-if="field.type === 'media'">
                                            @include('components.admin.partials.block-media-picker', [
                                                'fieldKeyExpression' => 'field.key',
                                                'modelExpression' => 'block.content[field.key]',
                                            ])
                                        </template>

                                        {{-- repeater --}}
                                        <template x-if="field.type === 'repeater'">
                                            <div class="space-y-3">
                                                <template x-for="(item, ri) in (block.content[field.key] || [])"
                                                    :key="ri">
                                                    <div class="bg-gray-50 rounded-xl p-3 space-y-2">
                                                        <template x-for="sf in field.sub_fields" :key="sf.key">
                                                            <div>
                                                                <label class="block text-xs text-gray-500 mb-1"
                                                                    x-text="sf.label"></label>
                                                                <template x-if="['textarea', 'richtext', 'code'].includes(sf.type)">
                                                                    <textarea x-model="item[sf.key]" rows="2"
                                                                        class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30 resize-y"></textarea>
                                                                </template>
                                                                <template x-if="sf.type === 'select'">
                                                                    <select x-model="item[sf.key]"
                                                                        class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30 bg-white">
                                                                        <template x-for="(optLabel, optVal) in sf.options" :key="optVal">
                                                                            <option :value="optVal" x-text="optLabel"></option>
                                                                        </template>
                                                                    </select>
                                                                </template>
                                                                <template x-if="sf.type === 'toggle'">
                                                                    <label class="flex items-center gap-2 cursor-pointer">
                                                                        <div class="relative">
                                                                            <input type="checkbox" x-model="item[sf.key]" class="sr-only">
                                                                            <div class="w-10 h-5 rounded-full transition"
                                                                                :class="item[sf.key] ? 'bg-forest' : 'bg-gray-200'"></div>
                                                                            <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow transition-transform"
                                                                                :class="item[sf.key] ? 'translate-x-5' : 'translate-x-0'"></div>
                                                                        </div>
                                                                        <span class="text-xs text-gray-500"
                                                                            x-text="item[sf.key] ? 'Enabled' : 'Disabled'"></span>
                                                                    </label>
                                                                </template>
                                                                <template x-if="sf.type === 'number'">
                                                                    <input type="number" x-model="item[sf.key]"
                                                                        class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                        :min="sf.min ?? ''" :max="sf.max ?? ''" :step="sf.step ?? 1">
                                                                </template>
                                                                <template x-if="sf.type === 'media'">
                                                                    @include('components.admin.partials.block-media-picker', [
                                                                        'fieldKeyExpression' => 'sf.key',
                                                                        'modelExpression' => 'item[sf.key]',
                                                                    ])
                                                                </template>
                                                                <template x-if="!['textarea', 'richtext', 'code', 'select', 'toggle', 'number', 'media'].includes(sf.type)">
                                                                    <input type="text" x-model="item[sf.key]"
                                                                        class="w-full px-2 py-1.5 text-sm border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                </template>
                                                            </div>
                                                        </template>
                                                        <button type="button"
                                                            x-on:click="block.content[field.key].splice(ri, 1)"
                                                            class="text-xs text-red-400 hover:text-red-600 transition">
                                                            <i data-lucide="trash-2"
                                                                class="w-3 h-3 inline mr-1"></i>Remove
                                                        </button>
                                                    </div>
                                                </template>
                                                <button type="button"
                                                    x-on:click="if (!block.content[field.key]) block.content[field.key] = []; block.content[field.key].push({})"
                                                    class="flex items-center gap-1.5 text-xs text-forest hover:text-forest-dark font-medium transition">
                                                    <i data-lucide="plus" class="w-3.5 h-3.5"></i>Add Item
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <template x-if="hasDataSource(block)">
                        <div class="border-t border-gray-100">
                            <button type="button" x-on:click="block._dataSourceOpen = !block._dataSourceOpen"
                                class="flex w-full items-center gap-2 px-5 py-3 text-left transition hover:bg-gray-50/50">
                                <i data-lucide="database" class="w-3.5 h-3.5 text-gray-400"></i>
                                <span class="flex-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Data Source</span>
                                <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700" x-text="dataSourceSummary(block)"></span>
                                <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transition-transform"
                                    :class="block._dataSourceOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="block._dataSourceOpen" x-cloak x-collapse class="space-y-4 px-5 pb-5">
                                <div class="rounded-xl border border-blue-100 bg-blue-50/60 p-3 text-[11px] leading-relaxed text-blue-700">
                                    These settings control how dynamic blocks query content. Filter values accept literal IDs/values or special values like <code class="rounded bg-white px-1 py-0.5">auto</code>, <code class="rounded bg-white px-1 py-0.5">all</code>, <code class="rounded bg-white px-1 py-0.5">null</code>, <code class="rounded bg-white px-1 py-0.5">true</code>, and <code class="rounded bg-white px-1 py-0.5">false</code>.
                                </div>

                                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                    <div>
                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Resolved Model</label>
                                        <input type="text" :value="dataSourceSummary(block)" readonly
                                            class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Query Scope</label>
                                        <input type="text" x-model="block.data_source.scope"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                            placeholder="published">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Limit</label>
                                        <input type="text" x-model="block.data_source.limit"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                            placeholder="8">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Order By</label>
                                        <input type="text" x-model="block.data_source.order_by"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                            placeholder="sort_order">
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Order Direction</label>
                                        <select x-model="block.data_source.order_dir"
                                            class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                            <option value="asc">Ascending</option>
                                            <option value="desc">Descending</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Manual IDs</label>
                                        <input type="text" x-model="block.data_source.manual_ids"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                            placeholder="1, 2, 3">
                                    </div>
                                </div>

                                <div class="space-y-3 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                    <div class="flex flex-wrap items-center justify-between gap-2">
                                        <div>
                                            <p class="text-xs font-semibold text-gray-600">Filters</p>
                                            <p class="text-[10px] text-gray-400">Each filter maps a field name to a query value.</p>
                                        </div>
                                        <div class="flex flex-wrap gap-2">
                                            <button type="button" x-on:click="addDataFilter(block)"
                                                class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-[11px] font-medium text-gray-600 transition hover:bg-white">
                                                Add Filter
                                            </button>
                                            <button type="button" x-on:click="resetDataSource(block)"
                                                class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-[11px] font-medium text-gray-600 transition hover:bg-white">
                                                Reset Defaults
                                            </button>
                                        </div>
                                    </div>

                                    <template x-if="block._filterPairs.length === 0">
                                        <p class="text-[11px] italic text-gray-400">No filters configured for this block.</p>
                                    </template>
                                    <template x-for="(pair, filterIndex) in block._filterPairs" :key="'filter-' + filterIndex">
                                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                                            <input type="text" x-model="pair.key"
                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                placeholder="category_id">
                                            <input type="text" x-model="pair.value"
                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                placeholder="auto">
                                            <button type="button" x-on:click="removeDataFilter(block, filterIndex)"
                                                class="rounded-lg border border-red-100 px-3 py-2 text-xs font-medium text-red-500 transition hover:bg-red-50">
                                                Remove
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </template>

                    <div class="border-t border-gray-100">
                        <button type="button" x-on:click="block._advancedOpen = !block._advancedOpen"
                            class="flex w-full items-center gap-2 px-5 py-3 text-left transition hover:bg-gray-50/50">
                            <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-gray-400"></i>
                            <span class="flex-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Behavior & Metadata</span>
                            <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transition-transform"
                                :class="block._advancedOpen ? 'rotate-180' : ''"></i>
                        </button>
                        <div x-show="block._advancedOpen" x-cloak x-collapse class="space-y-4 px-5 pb-5">
                            <div x-data="{}">
                                <div class="mb-1 flex items-center justify-between gap-2">
                                    <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Visibility Window</label>
                                    <button type="button" @click="clearVisibilityRange(block, $refs.visibilityRange)" class="text-[10px] font-medium text-gray-400 transition hover:text-gray-600">
                                        Clear
                                    </button>
                                </div>
                                <input type="text" x-ref="visibilityRange" x-init="initVisibilityRangePicker($refs.visibilityRange, block)"
                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                    placeholder="Choose start and end date/time">
                                <p class="mt-1 text-[10px] text-gray-400">Pick a start and end in one range. Leave blank for always visible.</p>
                            </div>

                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Custom HTML ID</label>
                                    <input type="text" x-model="block.custom_id"
                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                        placeholder="pricing-section">
                                    <p class="mt-1 text-[10px] text-gray-400">Useful for anchor links and targeted navigation.</p>
                                </div>
                                <div>
                                    <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Animation</label>
                                    <select x-model="block.animation"
                                        class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                        <template x-for="option in animationOptions" :key="option.value || 'none'">
                                            <option :value="option.value" x-text="option.label"></option>
                                        </template>
                                    </select>
                                    <p class="mt-1 text-[10px] text-gray-400">Animations run when the block enters the viewport.</p>
                                </div>
                            </div>

                            <div class="space-y-3 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                <div class="flex flex-wrap items-center justify-between gap-2">
                                    <div>
                                        <p class="text-xs font-semibold text-gray-600">Custom Attributes</p>
                                        <p class="text-[10px] text-gray-400">Add semantic or tracking attributes such as <code class="rounded bg-white px-1 py-0.5">data-*</code> and <code class="rounded bg-white px-1 py-0.5">aria-*</code>.</p>
                                    </div>
                                    <button type="button" x-on:click="addAttribute(block)"
                                        class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-[11px] font-medium text-gray-600 transition hover:bg-white">
                                        Add Attribute
                                    </button>
                                </div>

                                <template x-if="block._attributePairs.length === 0">
                                    <p class="text-[11px] italic text-gray-400">No custom attributes configured.</p>
                                </template>
                                <template x-for="(pair, attributeIndex) in block._attributePairs" :key="'attribute-' + attributeIndex">
                                    <div class="grid grid-cols-1 gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                                        <input type="text" x-model="pair.key"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                            placeholder="data-tracking-id">
                                        <input type="text" x-model="pair.value"
                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                            placeholder="hero-cta">
                                        <button type="button" x-on:click="removeAttribute(block, attributeIndex)"
                                            class="rounded-lg border border-red-100 px-3 py-2 text-xs font-medium text-red-500 transition hover:bg-red-50">
                                            Remove
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Advanced Styling Panel (Responsive) --}}
                    <template x-if="styleFields.length > 0">
                        <div class="border-t border-gray-100">
                            <button type="button" x-on:click="block._styleOpen = !block._styleOpen"
                                class="w-full flex items-center gap-2 px-5 py-3 text-left hover:bg-gray-50/50 transition">
                                <i data-lucide="paintbrush" class="w-3.5 h-3.5 text-gray-400"></i>
                                <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide flex-1">Block
                                    Style</span>
                                <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transition-transform"
                                    :class="block._styleOpen ? 'rotate-180' : ''"></i>
                            </button>
                            <div x-show="block._styleOpen" x-cloak x-collapse class="px-5 pb-5">
                                <div x-data="{ styleDevice: 'desktop' }">
                                    {{-- Device Tabs --}}
                                    <div class="flex items-center gap-1 mb-4 border-b border-gray-100 pb-2">
                                        <button type="button" @click="styleDevice = 'desktop'"
                                            class="px-3 py-1 text-xs rounded-lg font-medium transition"
                                            :class="styleDevice === 'desktop' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'">
                                            <i data-lucide="monitor" class="w-3.5 h-3.5 inline mr-1"></i> Desktop
                                        </button>
                                        <button type="button" @click="styleDevice = 'tablet'"
                                            class="px-3 py-1 text-xs rounded-lg font-medium transition"
                                            :class="styleDevice === 'tablet' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'">
                                            <i data-lucide="tablet" class="w-3.5 h-3.5 inline mr-1"></i> Tablet
                                        </button>
                                        <button type="button" @click="styleDevice = 'mobile'"
                                            class="px-3 py-1 text-xs rounded-lg font-medium transition"
                                            :class="styleDevice === 'mobile' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'">
                                            <i data-lucide="smartphone" class="w-3.5 h-3.5 inline mr-1"></i> Mobile
                                        </button>
                                    </div>

                                    {{-- Style Fields Grid --}}
                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                        <div class="sm:col-span-2 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                            <div class="mb-3">
                                                <p class="text-xs font-semibold text-gray-600">Padding</p>
                                                <p class="text-[10px] text-gray-400">Manage all four sides together from one spacing control.</p>
                                            </div>
                                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                                <template x-for="wf in groupedStyleFields('padding')" :key="'padding-' + wf.key">
                                                    <div>
                                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400" x-text="wf.label.replace('Padding ', '')"></label>
                                                        <select x-model="block.styles[styleDevice][wf.key]"
                                                            class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                            <template x-for="(optLabel, optVal) in wf.options" :key="optVal">
                                                                <option :value="optVal" x-text="optLabel"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <div class="sm:col-span-2 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                            <div class="mb-3">
                                                <p class="text-xs font-semibold text-gray-600">Margins</p>
                                                <p class="text-[10px] text-gray-400">Top and bottom spacing outside the block.</p>
                                            </div>
                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                <template x-for="wf in groupedStyleFields('margin')" :key="'margin-' + wf.key">
                                                    <div>
                                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400" x-text="wf.label"></label>
                                                        <select x-model="block.styles[styleDevice][wf.key]"
                                                            class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                            <template x-for="(optLabel, optVal) in wf.options" :key="optVal">
                                                                <option :value="optVal" x-text="optLabel"></option>
                                                            </template>
                                                        </select>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>

                                        <template x-for="wf in renderableStyleFields()" :key="wf.key">
                                            <div :class="wf.type === 'toggle' ? 'col-span-2' : ''">
                                                <label
                                                    class="block text-[10px] uppercase font-bold tracking-wider text-gray-400 mb-1"
                                                    x-text="wf.label"></label>

                                                <template x-if="wf.type === 'select'">
                                                    <select x-model="block.styles[styleDevice][wf.key]"
                                                        class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30 bg-white shadow-sm">
                                                        <template x-for="(optLabel, optVal) in wf.options"
                                                            :key="optVal">
                                                            <option :value="optVal" x-text="optLabel"></option>
                                                        </template>
                                                    </select>
                                                </template>

                                                <template x-if="wf.type === 'toggle'">
                                                    <label class="flex items-center gap-2 cursor-pointer mt-1">
                                                        <div class="relative">
                                                            <input type="checkbox"
                                                                x-model="block.styles[styleDevice][wf.key]"
                                                                class="sr-only">
                                                            <div class="w-9 h-[18px] rounded-full transition shadow-inner"
                                                                :class="block.styles[styleDevice][wf.key] ? 'bg-forest' : 'bg-gray-200'">
                                                            </div>
                                                            <div class="absolute top-0.5 left-0.5 w-3.5 h-3.5 bg-white rounded-full shadow transition-transform"
                                                                :class="block.styles[styleDevice][wf.key] ? 'translate-x-[18px]' : 'translate-x-0'">
                                                            </div>
                                                        </div>
                                                        <span class="text-xs font-medium text-gray-500"
                                                            x-text="block.styles[styleDevice][wf.key] ? 'Enabled' : 'Disabled'"></span>
                                                    </label>
                                                </template>

                                                <template x-if="wf.type === 'text'">
                                                    <input type="text"
                                                        x-model="block.styles[styleDevice][wf.key]"
                                                        class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30 bg-white shadow-sm">
                                                </template>

                                                <template x-if="wf.type === 'number'">
                                                    <input type="number"
                                                        x-model="block.styles[styleDevice][wf.key]"
                                                        :min="wf.min ?? ''" :max="wf.max ?? ''" :step="wf.step ?? 1"
                                                        class="w-full px-2.5 py-1.5 text-xs border border-gray-200 rounded-lg focus:outline-none focus:ring-1 focus:ring-forest/30 bg-white shadow-sm">
                                                </template>

                                                <template x-if="wf.type === 'range'">
                                                    <div class="flex items-center gap-2">
                                                        <input type="range"
                                                            x-model="block.styles[styleDevice][wf.key]"
                                                            :min="wf.min ?? 0" :max="wf.max ?? 100" :step="wf.step ?? 5"
                                                            class="flex-1 h-1.5 bg-gray-200 rounded-full accent-forest cursor-pointer">
                                                        <span class="text-[10px] font-bold text-gray-500 w-8 text-right" x-text="(block.styles[styleDevice][wf.key] ?? wf.default ?? 50) + '%'"></span>
                                                    </div>
                                                </template>

                                                {{-- style media picker --}}
                                                <template x-if="wf.type === 'media'">
                                                    @include('components.admin.partials.block-media-picker', [
                                                        'fieldKeyExpression' => 'wf.key',
                                                        'modelExpression' => 'block.styles[styleDevice][wf.key]',
                                                        'containerClass' => 'flex items-start gap-4 p-3 border border-gray-100 rounded-xl bg-gray-50/50 mt-1 col-span-2',
                                                    ])
                                                </template>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>

                    <template x-if="block.supports_children">
                        <div class="border-t border-gray-100">
                            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3">
                                <div>
                                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Child Blocks</p>
                                    <p class="mt-0.5 text-[11px] text-gray-400">Build column content directly inside this layout block and assign each child to the correct slot.</p>
                                </div>
                                <div class="relative" x-data="{ addChildOpen: false, addChildSearch: '' }">
                                    <button type="button" x-on:click="addChildOpen = !addChildOpen"
                                        class="flex items-center gap-2 rounded-lg bg-forest px-3 py-1.5 text-xs font-medium text-white transition hover:bg-forest-dark">
                                        <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                                        Add Child
                                    </button>
                                    <div x-show="addChildOpen" x-cloak x-on:click.outside="addChildOpen = false"
                                        class="absolute right-0 top-full z-50 mt-2 w-80 max-w-[calc(100vw-3rem)] overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl">
                                        <div class="border-b border-gray-100 p-3">
                                            <input type="text" x-model="addChildSearch" placeholder="Search child blocks..."
                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/20">
                                        </div>
                                        <div class="max-h-80 overflow-y-auto py-2">
                                            <template x-if="filteredChildBlockTypes(block, addChildSearch).length === 0">
                                                <p class="px-4 py-6 text-center text-sm text-gray-400">No compatible child blocks found.</p>
                                            </template>
                                            <template x-for="type in filteredChildBlockTypes(block, addChildSearch)" :key="type.key">
                                                <button type="button" x-on:click="addChildBlock(block, type.key); addChildOpen = false; addChildSearch = ''"
                                                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left transition hover:bg-forest-50">
                                                    <div class="flex h-7 w-7 items-center justify-center rounded-lg bg-forest/10 shrink-0">
                                                        <i :data-lucide="type.icon" class="h-3.5 w-3.5 text-forest"></i>
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex flex-wrap items-center gap-1.5">
                                                            <span class="text-sm font-medium text-gray-700" x-text="type.label"></span>
                                                            <span class="rounded-full bg-gray-100 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-gray-500" x-text="type.category"></span>
                                                            <span x-show="type.data_source" x-cloak class="rounded-full bg-blue-50 px-1.5 py-0.5 text-[10px] font-semibold uppercase tracking-wide text-blue-700">Data</span>
                                                        </div>
                                                        <p class="mt-0.5 text-[11px] text-gray-400" x-text="(type.content_fields?.length || 0) + ' configurable field' + ((type.content_fields?.length || 0) === 1 ? '' : 's')"></p>
                                                    </div>
                                                </button>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3 px-5 pb-5">
                                <template x-if="(block.children || []).length === 0">
                                    <div class="rounded-2xl border border-dashed border-gray-200 bg-gray-50/40 px-4 py-6 text-center">
                                        <p class="text-sm font-medium text-gray-500">No child blocks yet.</p>
                                        <p class="mt-1 text-[11px] text-gray-400">Add blocks here to populate this container and explicitly place them into each column.</p>
                                    </div>
                                </template>

                                <template x-for="(child, childIndex) in (block.children || [])" :key="child._uid">
                                    <div class="overflow-hidden rounded-2xl border border-amber-100 bg-amber-50/30 shadow-sm" :class="child.is_enabled ? '' : 'opacity-60'">
                                        <div class="flex flex-wrap items-start gap-3 border-b border-amber-100 bg-white/70 px-4 py-3 sm:flex-nowrap sm:items-center">
                                            <div class="flex min-w-0 flex-1 items-center gap-2">
                                                <div class="flex h-6 w-6 items-center justify-center rounded-md bg-amber-100 shrink-0">
                                                    <i :data-lucide="getTypeIcon(child.block_type)" class="h-3 w-3 text-amber-700"></i>
                                                </div>
                                                <span class="text-xs font-semibold uppercase tracking-wide text-gray-600" x-text="getTypeLabel(child.block_type)"></span>
                                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-semibold text-amber-700" x-text="childSlotLabel(block, child)"></span>
                                                <span class="hidden truncate text-xs text-gray-400 sm:inline" x-text="blockPreview(child)"></span>
                                            </div>

                                            <div class="ml-auto flex flex-wrap items-center gap-1 shrink-0 sm:ml-0">
                                                <button type="button" x-on:click="child.is_enabled = !child.is_enabled"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100"
                                                    :title="child.is_enabled ? 'Disable child block' : 'Enable child block'">
                                                    <i :data-lucide="child.is_enabled ? 'eye' : 'eye-off'" class="h-3.5 w-3.5 text-gray-400"></i>
                                                </button>
                                                <button type="button" x-on:click="child.show_on_desktop = !child.show_on_desktop"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100"
                                                    :title="child.show_on_desktop ? 'Hide on Desktop' : 'Show on Desktop'">
                                                    <i data-lucide="monitor" class="h-3.5 w-3.5" :class="child.show_on_desktop ? 'text-forest' : 'text-gray-300'"></i>
                                                </button>
                                                <button type="button" x-on:click="child.show_on_tablet = !child.show_on_tablet"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100"
                                                    :title="child.show_on_tablet ? 'Hide on Tablet' : 'Show on Tablet'">
                                                    <i data-lucide="tablet" class="h-3.5 w-3.5" :class="child.show_on_tablet ? 'text-forest' : 'text-gray-300'"></i>
                                                </button>
                                                <button type="button" x-on:click="child.show_on_mobile = !child.show_on_mobile"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100"
                                                    :title="child.show_on_mobile ? 'Hide on Mobile' : 'Show on Mobile'">
                                                    <i data-lucide="smartphone" class="h-3.5 w-3.5" :class="child.show_on_mobile ? 'text-forest' : 'text-gray-300'"></i>
                                                </button>
                                                <button type="button" x-on:click="moveBlockInCollection(block.children, childIndex, -1)" :disabled="childIndex === 0"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100 disabled:opacity-30">
                                                    <i data-lucide="chevron-up" class="h-3.5 w-3.5 text-gray-400"></i>
                                                </button>
                                                <button type="button" x-on:click="moveBlockInCollection(block.children, childIndex, 1)" :disabled="childIndex === (block.children.length - 1)"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100 disabled:opacity-30">
                                                    <i data-lucide="chevron-down" class="h-3.5 w-3.5 text-gray-400"></i>
                                                </button>
                                                <button type="button" x-on:click="child._open = !child._open"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100">
                                                    <i data-lucide="settings-2" class="h-3.5 w-3.5 text-gray-400"></i>
                                                </button>
                                                <button type="button" x-on:click="copyBlockNode(child)"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100"
                                                    title="Copy child block configuration">
                                                    <i data-lucide="copy" class="h-3.5 w-3.5 text-gray-400"></i>
                                                </button>
                                                <button type="button" x-on:click="duplicateBlockInCollection(block.children, childIndex, block)"
                                                    class="rounded-lg p-1.5 transition hover:bg-gray-100"
                                                    title="Duplicate child block">
                                                    <i data-lucide="files" class="h-3.5 w-3.5 text-gray-400"></i>
                                                </button>
                                                <button type="button" x-on:click="deleteBlockFromCollection(block.children, childIndex)"
                                                    class="rounded-lg p-1.5 transition hover:bg-red-50">
                                                    <i data-lucide="trash-2" class="h-3.5 w-3.5 text-red-400"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <div x-show="child._open" x-cloak x-collapse class="divide-y divide-gray-50 bg-white">
                                            <div class="space-y-4 p-4">
                                                <template x-if="getTypeFields(child).length === 0">
                                                    <p class="text-xs italic text-gray-400">No configurable fields for this child block type.</p>
                                                </template>
                                                <template x-for="field in getTypeFields(child)" :key="field.key">
                                                    <div>
                                                        <div class="mb-1.5 flex flex-wrap items-center justify-between gap-2">
                                                            <label class="block text-xs font-semibold text-gray-600" x-text="field.label"></label>
                                                            <template x-if="['text', 'textarea'].includes(field.type)">
                                                                <div class="relative" x-data="{ dynOpen: false, dynSearch: '' }">
                                                                    <button type="button"
                                                                        @click="dynOpen = !dynOpen; if (dynOpen) { $nextTick(() => $refs.dynamicVariableSearch?.focus()); }"
                                                                        class="flex cursor-pointer items-center gap-1 rounded bg-forest/5 px-2 py-0.5 text-[10px] font-medium text-forest transition hover:text-forest-dark">
                                                                        <i data-lucide="database" class="w-3 h-3"></i> Insert Variable
                                                                    </button>
                                                                    <div x-show="dynOpen" @click.outside="dynOpen = false" x-cloak
                                                                        class="absolute right-0 z-50 mt-1 w-80 max-w-[calc(100vw-2rem)] overflow-hidden rounded-xl border border-gray-100 bg-white shadow-xl">
                                                                        <div class="border-b border-gray-100 p-2">
                                                                            <input x-ref="dynamicVariableSearch" type="text" x-model="dynSearch"
                                                                                placeholder="Search variables..."
                                                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-forest/20">
                                                                        </div>
                                                                        <div class="max-h-80 space-y-2 overflow-y-auto p-2">
                                                                            <template x-if="filteredDynamicVariableGroups(dynSearch).length === 0">
                                                                                <p class="px-2 py-3 text-xs text-gray-400">No matching variables found.</p>
                                                                            </template>
                                                                            <template x-for="group in filteredDynamicVariableGroups(dynSearch)" :key="group.key">
                                                                                <div class="space-y-1">
                                                                                    <div class="px-2 pt-1">
                                                                                        <p class="text-[10px] font-bold uppercase tracking-wide text-gray-400" x-text="group.label"></p>
                                                                                        <p class="text-[10px] text-gray-400" x-text="group.description"></p>
                                                                                    </div>
                                                                                    <template x-for="variable in group.variables" :key="variable.token">
                                                                                        <button type="button"
                                                                                            @click="insertDynamicVariable(child, field.key, variable.token); dynOpen = false; dynSearch = ''"
                                                                                            class="w-full rounded-lg px-2 py-2 text-left transition hover:bg-gray-50">
                                                                                            <span class="block text-[11px] font-semibold text-forest" x-text="'{' + variable.token + '}'"></span>
                                                                                            <span class="block text-xs text-gray-700" x-text="variable.label"></span>
                                                                                            <span class="block text-[10px] text-gray-400" x-text="variable.description"></span>
                                                                                        </button>
                                                                                    </template>
                                                                                </div>
                                                                            </template>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </template>
                                                        </div>

                                                        <template x-if="field.type === 'text'">
                                                            <input type="text" x-model="child.content[field.key]"
                                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-forest focus:outline-none focus:ring-2 focus:ring-forest/30">
                                                        </template>

                                                        <template x-if="field.type === 'number'">
                                                            <input type="number" x-model="child.content[field.key]"
                                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-forest focus:outline-none focus:ring-2 focus:ring-forest/30"
                                                                :min="field.min ?? ''" :max="field.max ?? ''" :step="field.step ?? 1">
                                                        </template>

                                                        <template x-if="field.type === 'select_model'">
                                                            <div>
                                                                <input type="text" x-model="child.content[field.key]"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-forest focus:outline-none focus:ring-2 focus:ring-forest/30"
                                                                    placeholder="Enter slug or ID">
                                                                <p class="mt-1 text-[10px] text-gray-400">Enter the slug or numeric ID of the record.</p>
                                                            </div>
                                                        </template>

                                                        <template x-if="field.type === 'media_multi'">
                                                            @include('components.admin.partials.block-media-multi-picker', [
                                                                'fieldKeyExpression' => 'field.key',
                                                                'modelExpression' => 'child.content[field.key]',
                                                            ])
                                                        </template>

                                                        <template x-if="field.type === 'textarea'">
                                                            <textarea x-model="child.content[field.key]" rows="4"
                                                                class="w-full resize-y rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-forest focus:outline-none focus:ring-2 focus:ring-forest/30"></textarea>
                                                        </template>

                                                        <template x-if="['code', 'richtext'].includes(field.type)">
                                                            <textarea x-model="child.content[field.key]" rows="6"
                                                                class="w-full resize-y rounded-lg border border-gray-200 px-3 py-2 font-mono text-sm focus:border-forest focus:outline-none focus:ring-2 focus:ring-forest/30"></textarea>
                                                        </template>

                                                        <template x-if="field.type === 'select'">
                                                            <select x-model="child.content[field.key]"
                                                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:border-forest focus:outline-none focus:ring-2 focus:ring-forest/30">
                                                                <template x-for="(optLabel, optVal) in field.options" :key="optVal">
                                                                    <option :value="optVal" x-text="optLabel"></option>
                                                                </template>
                                                            </select>
                                                        </template>

                                                        <template x-if="field.type === 'toggle'">
                                                            <label class="flex cursor-pointer items-center gap-2">
                                                                <div class="relative">
                                                                    <input type="checkbox" x-model="child.content[field.key]" class="sr-only">
                                                                    <div class="h-5 w-10 rounded-full transition" :class="child.content[field.key] ? 'bg-forest' : 'bg-gray-200'"></div>
                                                                    <div class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform" :class="child.content[field.key] ? 'translate-x-5' : 'translate-x-0'"></div>
                                                                </div>
                                                                <span class="text-xs text-gray-500" x-text="child.content[field.key] ? 'Enabled' : 'Disabled'"></span>
                                                            </label>
                                                        </template>

                                                        <template x-if="field.type === 'media'">
                                                            @include('components.admin.partials.block-media-picker', [
                                                                'fieldKeyExpression' => 'field.key',
                                                                'modelExpression' => 'child.content[field.key]',
                                                            ])
                                                        </template>

                                                        <template x-if="field.type === 'repeater'">
                                                            <div class="space-y-3">
                                                                <template x-for="(item, ri) in (child.content[field.key] || [])" :key="ri">
                                                                    <div class="space-y-2 rounded-xl bg-gray-50 p-3">
                                                                        <template x-for="sf in field.sub_fields" :key="sf.key">
                                                                            <div>
                                                                                <label class="mb-1 block text-xs text-gray-500" x-text="sf.label"></label>
                                                                                <template x-if="['textarea', 'richtext', 'code'].includes(sf.type)">
                                                                                    <textarea x-model="item[sf.key]" rows="2"
                                                                                        class="w-full resize-y rounded-lg border border-gray-200 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-forest/30"></textarea>
                                                                                </template>
                                                                                <template x-if="sf.type === 'select'">
                                                                                    <select x-model="item[sf.key]"
                                                                                        class="w-full rounded-lg border border-gray-200 bg-white px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                                        <template x-for="(optLabel, optVal) in sf.options" :key="optVal">
                                                                                            <option :value="optVal" x-text="optLabel"></option>
                                                                                        </template>
                                                                                    </select>
                                                                                </template>
                                                                                <template x-if="sf.type === 'toggle'">
                                                                                    <label class="flex cursor-pointer items-center gap-2">
                                                                                        <div class="relative">
                                                                                            <input type="checkbox" x-model="item[sf.key]" class="sr-only">
                                                                                            <div class="h-5 w-10 rounded-full transition" :class="item[sf.key] ? 'bg-forest' : 'bg-gray-200'"></div>
                                                                                            <div class="absolute left-0.5 top-0.5 h-4 w-4 rounded-full bg-white shadow transition-transform" :class="item[sf.key] ? 'translate-x-5' : 'translate-x-0'"></div>
                                                                                        </div>
                                                                                        <span class="text-xs text-gray-500" x-text="item[sf.key] ? 'Enabled' : 'Disabled'"></span>
                                                                                    </label>
                                                                                </template>
                                                                                <template x-if="sf.type === 'number'">
                                                                                    <input type="number" x-model="item[sf.key]"
                                                                                        class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                                        :min="sf.min ?? ''" :max="sf.max ?? ''" :step="sf.step ?? 1">
                                                                                </template>
                                                                                <template x-if="sf.type === 'media'">
                                                                                    @include('components.admin.partials.block-media-picker', [
                                                                                        'fieldKeyExpression' => 'sf.key',
                                                                                        'modelExpression' => 'item[sf.key]',
                                                                                    ])
                                                                                </template>
                                                                                <template x-if="!['textarea', 'richtext', 'code', 'select', 'toggle', 'number', 'media'].includes(sf.type)">
                                                                                    <input type="text" x-model="item[sf.key]"
                                                                                        class="w-full rounded-lg border border-gray-200 px-2 py-1.5 text-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                                </template>
                                                                            </div>
                                                                        </template>
                                                                        <button type="button" x-on:click="child.content[field.key].splice(ri, 1)"
                                                                            class="text-xs text-red-400 transition hover:text-red-600">
                                                                            <i data-lucide="trash-2" class="mr-1 inline h-3 w-3"></i>Remove
                                                                        </button>
                                                                    </div>
                                                                </template>
                                                                <button type="button" x-on:click="if (!child.content[field.key]) child.content[field.key] = []; child.content[field.key].push({})"
                                                                    class="flex items-center gap-1.5 text-xs font-medium text-forest transition hover:text-forest-dark">
                                                                    <i data-lucide="plus" class="h-3.5 w-3.5"></i>Add Item
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </template>
                                            </div>

                                            <template x-if="hasDataSource(child)">
                                                <div class="border-t border-gray-100">
                                                    <button type="button" x-on:click="child._dataSourceOpen = !child._dataSourceOpen"
                                                        class="flex w-full items-center gap-2 px-4 py-3 text-left transition hover:bg-gray-50/50">
                                                        <i data-lucide="database" class="w-3.5 h-3.5 text-gray-400"></i>
                                                        <span class="flex-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Data Source</span>
                                                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700" x-text="dataSourceSummary(child)"></span>
                                                        <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transition-transform" :class="child._dataSourceOpen ? 'rotate-180' : ''"></i>
                                                    </button>
                                                    <div x-show="child._dataSourceOpen" x-cloak x-collapse class="space-y-4 px-4 pb-4">
                                                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                            <div>
                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Resolved Model</label>
                                                                <input type="text" :value="dataSourceSummary(child)" readonly
                                                                    class="w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-xs text-gray-500">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Query Scope</label>
                                                                <input type="text" x-model="child.data_source.scope"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Limit</label>
                                                                <input type="text" x-model="child.data_source.limit"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Order By</label>
                                                                <input type="text" x-model="child.data_source.order_by"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Order Direction</label>
                                                                <select x-model="child.data_source.order_dir"
                                                                    class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                    <option value="asc">Ascending</option>
                                                                    <option value="desc">Descending</option>
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Manual IDs</label>
                                                                <input type="text" x-model="child.data_source.manual_ids"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                            </div>
                                                        </div>

                                                        <div class="space-y-3 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                                            <div class="flex flex-wrap items-center justify-between gap-2">
                                                                <div>
                                                                    <p class="text-xs font-semibold text-gray-600">Filters</p>
                                                                    <p class="text-[10px] text-gray-400">Map database fields to literal values or dynamic <code class="rounded bg-white px-1 py-0.5">auto</code> values.</p>
                                                                </div>
                                                                <div class="flex flex-wrap gap-2">
                                                                    <button type="button" x-on:click="addDataFilter(child)"
                                                                        class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-[11px] font-medium text-gray-600 transition hover:bg-white">
                                                                        Add Filter
                                                                    </button>
                                                                    <button type="button" x-on:click="resetDataSource(child)"
                                                                        class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-[11px] font-medium text-gray-600 transition hover:bg-white">
                                                                        Reset Defaults
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <template x-if="child._filterPairs.length === 0">
                                                                <p class="text-[11px] italic text-gray-400">No filters configured for this child block.</p>
                                                            </template>
                                                            <template x-for="(pair, filterIndex) in child._filterPairs" :key="'child-filter-' + filterIndex">
                                                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                                                                    <input type="text" x-model="pair.key"
                                                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                        placeholder="category_id">
                                                                    <input type="text" x-model="pair.value"
                                                                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                        placeholder="auto">
                                                                    <button type="button" x-on:click="removeDataFilter(child, filterIndex)"
                                                                        class="rounded-lg border border-red-100 px-3 py-2 text-xs font-medium text-red-500 transition hover:bg-red-50">
                                                                        Remove
                                                                    </button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>

                                            <div class="border-t border-gray-100">
                                                <button type="button" x-on:click="child._advancedOpen = !child._advancedOpen"
                                                    class="flex w-full items-center gap-2 px-4 py-3 text-left transition hover:bg-gray-50/50">
                                                    <i data-lucide="sliders-horizontal" class="w-3.5 h-3.5 text-gray-400"></i>
                                                    <span class="flex-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Behavior & Metadata</span>
                                                    <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transition-transform" :class="child._advancedOpen ? 'rotate-180' : ''"></i>
                                                </button>
                                                <div x-show="child._advancedOpen" x-cloak x-collapse class="space-y-4 px-4 pb-4">
                                                    <template x-if="childSlotOptions(block).length > 0">
                                                        <div>
                                                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Container Slot</label>
                                                            <select x-model="child.content._layout_slot"
                                                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                <template x-for="slot in childSlotOptions(block)" :key="slot.value">
                                                                    <option :value="slot.value" x-text="slot.label"></option>
                                                                </template>
                                                            </select>
                                                            <p class="mt-1 text-[10px] text-gray-400">Choose exactly where this block should render inside the parent container.</p>
                                                        </div>
                                                    </template>

                                                    <div x-data="{}">
                                                        <div class="mb-1 flex items-center justify-between gap-2">
                                                            <label class="block text-[10px] font-bold uppercase tracking-wider text-gray-400">Visibility Window</label>
                                                            <button type="button" @click="clearVisibilityRange(child, $refs.childVisibilityRange)" class="text-[10px] font-medium text-gray-400 transition hover:text-gray-600">
                                                                Clear
                                                            </button>
                                                        </div>
                                                        <input type="text" x-ref="childVisibilityRange" x-init="initVisibilityRangePicker($refs.childVisibilityRange, child)"
                                                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                            placeholder="Choose start and end date/time">
                                                        <p class="mt-1 text-[10px] text-gray-400">Pick a start and end in one range. Leave blank for always visible.</p>
                                                    </div>

                                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                        <div>
                                                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Custom HTML ID</label>
                                                            <input type="text" x-model="child.custom_id"
                                                                class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                placeholder="content-card">
                                                        </div>
                                                        <div>
                                                            <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400">Animation</label>
                                                            <select x-model="child.animation"
                                                                class="w-full rounded-lg border border-gray-200 bg-white px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                <template x-for="option in animationOptions" :key="option.value || 'none'">
                                                                    <option :value="option.value" x-text="option.label"></option>
                                                                </template>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-3 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                                        <div class="flex flex-wrap items-center justify-between gap-2">
                                                            <div>
                                                                <p class="text-xs font-semibold text-gray-600">Custom Attributes</p>
                                                                <p class="text-[10px] text-gray-400">Attach tracking or accessibility metadata such as <code class="rounded bg-white px-1 py-0.5">data-*</code> or <code class="rounded bg-white px-1 py-0.5">aria-*</code>.</p>
                                                            </div>
                                                            <button type="button" x-on:click="addAttribute(child)"
                                                                class="rounded-lg border border-gray-200 px-2.5 py-1.5 text-[11px] font-medium text-gray-600 transition hover:bg-white">
                                                                Add Attribute
                                                            </button>
                                                        </div>
                                                        <template x-if="child._attributePairs.length === 0">
                                                            <p class="text-[11px] italic text-gray-400">No custom attributes configured.</p>
                                                        </template>
                                                        <template x-for="(pair, attributeIndex) in child._attributePairs" :key="'child-attribute-' + attributeIndex">
                                                            <div class="grid grid-cols-1 gap-2 sm:grid-cols-[minmax(0,1fr)_minmax(0,1fr)_auto]">
                                                                <input type="text" x-model="pair.key"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                    placeholder="data-tracking-id">
                                                                <input type="text" x-model="pair.value"
                                                                    class="w-full rounded-lg border border-gray-200 px-3 py-2 text-xs focus:outline-none focus:ring-1 focus:ring-forest/30"
                                                                    placeholder="feature-card">
                                                                <button type="button" x-on:click="removeAttribute(child, attributeIndex)"
                                                                    class="rounded-lg border border-red-100 px-3 py-2 text-xs font-medium text-red-500 transition hover:bg-red-50">
                                                                    Remove
                                                                </button>
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>

                                            <template x-if="styleFields.length > 0">
                                                <div class="border-t border-gray-100">
                                                    <button type="button" x-on:click="child._styleOpen = !child._styleOpen"
                                                        class="flex w-full items-center gap-2 px-4 py-3 text-left transition hover:bg-gray-50/50">
                                                        <i data-lucide="paintbrush" class="w-3.5 h-3.5 text-gray-400"></i>
                                                        <span class="flex-1 text-xs font-semibold uppercase tracking-wide text-gray-500">Child Style</span>
                                                        <i data-lucide="chevron-down" class="w-3 h-3 text-gray-400 transition-transform" :class="child._styleOpen ? 'rotate-180' : ''"></i>
                                                    </button>
                                                    <div x-show="child._styleOpen" x-cloak x-collapse class="px-4 pb-4">
                                                        <div x-data="{ styleDevice: 'desktop' }">
                                                            <div class="mb-4 flex items-center gap-1 border-b border-gray-100 pb-2">
                                                                <button type="button" @click="styleDevice = 'desktop'"
                                                                    class="rounded-lg px-3 py-1 text-xs font-medium transition"
                                                                    :class="styleDevice === 'desktop' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'">
                                                                    <i data-lucide="monitor" class="mr-1 inline h-3.5 w-3.5"></i> Desktop
                                                                </button>
                                                                <button type="button" @click="styleDevice = 'tablet'"
                                                                    class="rounded-lg px-3 py-1 text-xs font-medium transition"
                                                                    :class="styleDevice === 'tablet' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'">
                                                                    <i data-lucide="tablet" class="mr-1 inline h-3.5 w-3.5"></i> Tablet
                                                                </button>
                                                                <button type="button" @click="styleDevice = 'mobile'"
                                                                    class="rounded-lg px-3 py-1 text-xs font-medium transition"
                                                                    :class="styleDevice === 'mobile' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'">
                                                                    <i data-lucide="smartphone" class="mr-1 inline h-3.5 w-3.5"></i> Mobile
                                                                </button>
                                                            </div>

                                                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                                <div class="sm:col-span-2 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                                                    <div class="mb-3">
                                                                        <p class="text-xs font-semibold text-gray-600">Padding</p>
                                                                        <p class="text-[10px] text-gray-400">Manage all four sides together from one spacing control.</p>
                                                                    </div>
                                                                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                                                        <template x-for="wf in groupedStyleFields('padding')" :key="'child-padding-' + wf.key">
                                                                            <div>
                                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400" x-text="wf.label.replace('Padding ', '')"></label>
                                                                                <select x-model="child.styles[styleDevice][wf.key]"
                                                                                    class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                                    <template x-for="(optLabel, optVal) in wf.options" :key="optVal">
                                                                                        <option :value="optVal" x-text="optLabel"></option>
                                                                                    </template>
                                                                                </select>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                                <div class="sm:col-span-2 rounded-xl border border-gray-100 bg-gray-50/70 p-3">
                                                                    <div class="mb-3">
                                                                        <p class="text-xs font-semibold text-gray-600">Margins</p>
                                                                        <p class="text-[10px] text-gray-400">Top and bottom spacing outside the child block.</p>
                                                                    </div>
                                                                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                                                        <template x-for="wf in groupedStyleFields('margin')" :key="'child-margin-' + wf.key">
                                                                            <div>
                                                                                <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400" x-text="wf.label"></label>
                                                                                <select x-model="child.styles[styleDevice][wf.key]"
                                                                                    class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                                    <template x-for="(optLabel, optVal) in wf.options" :key="optVal">
                                                                                        <option :value="optVal" x-text="optLabel"></option>
                                                                                    </template>
                                                                                </select>
                                                                            </div>
                                                                        </template>
                                                                    </div>
                                                                </div>

                                                                <template x-for="wf in renderableStyleFields()" :key="'child-style-' + wf.key">
                                                                    <div :class="wf.type === 'toggle' ? 'col-span-2' : ''">
                                                                        <label class="mb-1 block text-[10px] font-bold uppercase tracking-wider text-gray-400" x-text="wf.label"></label>

                                                                        <template x-if="wf.type === 'select'">
                                                                            <select x-model="child.styles[styleDevice][wf.key]"
                                                                                class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                                <template x-for="(optLabel, optVal) in wf.options" :key="optVal">
                                                                                    <option :value="optVal" x-text="optLabel"></option>
                                                                                </template>
                                                                            </select>
                                                                        </template>

                                                                        <template x-if="wf.type === 'toggle'">
                                                                            <label class="mt-1 flex cursor-pointer items-center gap-2">
                                                                                <div class="relative">
                                                                                    <input type="checkbox" x-model="child.styles[styleDevice][wf.key]" class="sr-only">
                                                                                    <div class="h-[18px] w-9 rounded-full shadow-inner transition" :class="child.styles[styleDevice][wf.key] ? 'bg-forest' : 'bg-gray-200'"></div>
                                                                                    <div class="absolute left-0.5 top-0.5 h-3.5 w-3.5 rounded-full bg-white shadow transition-transform" :class="child.styles[styleDevice][wf.key] ? 'translate-x-[18px]' : 'translate-x-0'"></div>
                                                                                </div>
                                                                                <span class="text-xs font-medium text-gray-500" x-text="child.styles[styleDevice][wf.key] ? 'Enabled' : 'Disabled'"></span>
                                                                            </label>
                                                                        </template>

                                                                        <template x-if="wf.type === 'text'">
                                                                            <input type="text" x-model="child.styles[styleDevice][wf.key]"
                                                                                class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                        </template>

                                                                        <template x-if="wf.type === 'number'">
                                                                            <input type="number" x-model="child.styles[styleDevice][wf.key]"
                                                                                :min="wf.min ?? ''" :max="wf.max ?? ''" :step="wf.step ?? 1"
                                                                                class="w-full rounded-lg border border-gray-200 bg-white px-2.5 py-1.5 text-xs shadow-sm focus:outline-none focus:ring-1 focus:ring-forest/30">
                                                                        </template>

                                                                        <template x-if="wf.type === 'range'">
                                                                            <div class="flex items-center gap-2">
                                                                                <input type="range" x-model="child.styles[styleDevice][wf.key]"
                                                                                    :min="wf.min ?? 0" :max="wf.max ?? 100" :step="wf.step ?? 5"
                                                                                    class="h-1.5 flex-1 cursor-pointer rounded-full bg-gray-200 accent-forest">
                                                                                <span class="w-8 text-right text-[10px] font-bold text-gray-500" x-text="(child.styles[styleDevice][wf.key] ?? wf.default ?? 50) + '%'"></span>
                                                                            </div>
                                                                        </template>

                                                                        <template x-if="wf.type === 'media'">
                                                                            @include('components.admin.partials.block-media-picker', [
                                                                                'fieldKeyExpression' => 'wf.key',
                                                                                'modelExpression' => 'child.styles[styleDevice][wf.key]',
                                                                                'containerClass' => 'flex items-start gap-4 p-3 border border-gray-100 rounded-xl bg-gray-50/50 mt-1 col-span-2',
                                                                            ])
                                                                        </template>
                                                                    </div>
                                                                </template>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>

@push('scripts')
@endpush
