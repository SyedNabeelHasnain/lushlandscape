@props([
    'fieldKeyExpression',
    'modelExpression',
    'containerClass' => 'space-y-3 rounded-xl border border-gray-100 bg-gray-50/50 p-3',
    'emptyText' => 'No media selected',
    'mediaType' => 'image',
])

<div
    x-data="mediaMultiPickerModal({!! $fieldKeyExpression !!}, {!! $modelExpression !!} || [], @js($mediaType))"
    x-effect="{ const current = Array.isArray({!! $modelExpression !!}) ? {!! $modelExpression !!} : []; if (JSON.stringify(current) !== JSON.stringify(selectedIds)) {!! $modelExpression !!} = [...selectedIds]; }"
    class="{{ $containerClass }}"
>
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-xs font-semibold text-gray-600">Selected Media</p>
            <p class="mt-1 text-[10px] text-gray-400" x-text="selectedIds.length ? selectedIds.length + ' item(s) selected' : '{{ $emptyText }}'"></p>
        </div>
        <div class="flex flex-wrap gap-2">
            <button type="button" @click="openModal()" class="rounded-lg bg-forest px-3 py-1.5 text-xs text-white transition hover:bg-forest-light">
                Browse Library
            </button>
            <button type="button" x-show="selectedIds.length" x-cloak @click="clear()" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs text-gray-500 transition hover:bg-gray-50">
                Clear
            </button>
        </div>
    </div>

    <div x-show="selectedAssets.length" class="grid grid-cols-2 gap-3 sm:grid-cols-4 xl:grid-cols-5" x-cloak>
        <template x-for="(asset, index) in selectedAssets" :key="'selected-' + asset.id">
            <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                <div class="relative aspect-square bg-gray-100">
                    <img :src="asset.url" :alt="asset.default_alt_text || ''" class="h-full w-full object-cover">
                    <div class="absolute inset-x-0 bottom-0 flex items-center justify-between bg-black/55 px-2 py-1 text-[10px] text-white">
                        <span class="truncate">ID: <span x-text="asset.id"></span></span>
                        <span class="rounded-full bg-white/15 px-1.5 py-0.5" x-text="'#' + (index + 1)"></span>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-1 p-2">
                    <div class="flex gap-1">
                        <button type="button" @click="moveSelected(index, -1)" :disabled="index === 0" class="rounded-md border border-gray-200 p-1 text-gray-500 transition hover:bg-gray-50 disabled:opacity-30">
                            <i data-lucide="arrow-left" class="h-3.5 w-3.5"></i>
                        </button>
                        <button type="button" @click="moveSelected(index, 1)" :disabled="index === selectedAssets.length - 1" class="rounded-md border border-gray-200 p-1 text-gray-500 transition hover:bg-gray-50 disabled:opacity-30">
                            <i data-lucide="arrow-right" class="h-3.5 w-3.5"></i>
                        </button>
                    </div>
                    <button type="button" @click="remove(asset.id)" class="rounded-md border border-red-100 p-1 text-red-500 transition hover:bg-red-50">
                        <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                    </button>
                </div>
            </div>
        </template>
    </div>

    <div x-show="!selectedAssets.length" x-cloak class="rounded-xl border border-dashed border-gray-200 bg-white px-4 py-5 text-center text-xs text-gray-400">
        {{ $emptyText }}
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 p-4" @keydown.escape.window="open = false">
        <div class="flex max-h-[90vh] w-full max-w-5xl flex-col rounded-2xl bg-white shadow-2xl" @click.stop>
            <div class="flex flex-wrap items-start justify-between gap-3 border-b border-gray-100 px-4 py-4 sm:px-6">
                <div>
                    <h3 class="font-semibold text-text">Select Media</h3>
                    <p class="mt-1 text-xs text-gray-400">Choose one or more assets and arrange their order for sliders or galleries.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <div class="flex flex-wrap gap-1">
                        <button type="button" @click="tab = 'library'" :class="tab === 'library' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'" class="rounded-lg px-3 py-1.5 text-xs font-medium transition">Library</button>
                        <button type="button" @click="tab = 'upload'" :class="tab === 'upload' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'" class="rounded-lg px-3 py-1.5 text-xs font-medium transition">Upload</button>
                    </div>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>
            </div>

            <div x-show="tab === ' x-cloaklibrary'" class="flex min-h-[420px] flex-1 flex-col overflow-hidden">
                <div class="flex flex-col gap-3 border-b border-gray-100 px-4 py-3 sm:flex-row sm:px-6">
                    <input type="text" x-model="search" @input.debounce.400ms="fetchMedia(1)" placeholder="Search by title…" class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    <select x-model="filterType" @change="fetchMedia(1)" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/30 sm:w-44">
                        <option value="">All Types</option>
                        <option value="image">Images</option>
                        <option value="video">Videos</option>
                    </select>
                </div>

                <div class="grid flex-1 gap-0 lg:grid-cols-[minmax(0,1fr)_20rem]">
                    <div class="overflow-y-auto p-4 sm:p-6">
                        <div x-show="loading" x-cloak class="flex justify-center py-16">
                            <i data-lucide="loader-2" class="h-6 w-6 animate-spin text-forest"></i>
                        </div>
                        <div x-show="!loading && assets.length === 0" x-cloak class="py-16 text-center text-sm text-gray-400">No media found.</div>
                        <div x-show="!loading && assets.length > 0" class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4" x-cloak>
                            <template x-for="asset in assets" :key="'library-' + asset.id">
                                <button type="button" @click="toggle(asset)"
                                    :class="isSelected(asset.id) ? 'border-forest ring-2 ring-forest' : 'border-gray-200 hover:ring-2 hover:ring-forest/35'"
                                    class="group overflow-hidden rounded-xl border bg-white text-left shadow-sm transition">
                                    <div class="relative aspect-square bg-gray-100">
                                        <img :src="asset.url" :alt="asset.default_alt_text || ''" class="h-full w-full object-cover">
                                        <div class="absolute right-2 top-2 flex h-6 w-6 items-center justify-center rounded-full border border-white/50 bg-black/45 text-white">
                                            <i x-show="isSelected(asset.id)" x-cloak data-lucide="check" class="h-3.5 w-3.5"></i>
                                            <i x-show="!isSelected(asset.id)" x-cloak data-lucide="plus" class="h-3.5 w-3.5 opacity-0 transition group-hover:opacity-100"></i>
                                        </div>
                                    </div>
                                    <div class="space-y-1 p-2">
                                        <p class="truncate text-[11px] font-semibold text-gray-700" x-text="asset.internal_title || asset.default_alt_text || ('Asset #' + asset.id)"></p>
                                        <p class="text-[10px] text-gray-400" x-text="'ID: ' + asset.id"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    <aside class="border-t border-gray-100 bg-gray-50/80 p-4 lg:border-l lg:border-t-0">
                        <div class="flex items-center justify-between gap-2">
                            <div>
                                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Selection Order</p>
                                <p class="mt-1 text-[10px] text-gray-400">The frontend slider uses this exact order.</p>
                            </div>
                            <button type="button" x-show="selectedIds.length" x-cloak @click="clear()" class="rounded-lg border border-gray-200 px-2.5 py-1 text-[11px] text-gray-500 transition hover:bg-white">
                                Clear
                            </button>
                        </div>
                        <div class="mt-4 space-y-2">
                            <template x-if="selectedAssets.length === 0">
                                <p class="rounded-xl border border-dashed border-gray-200 bg-white px-3 py-6 text-center text-[11px] text-gray-400">No assets selected yet.</p>
                            </template>
                            <template x-for="(asset, index) in selectedAssets" :key="'ordered-' + asset.id">
                                <div class="flex items-center gap-3 rounded-xl border border-gray-200 bg-white p-2 shadow-sm">
                                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-forest/10 text-[11px] font-bold text-forest" x-text="index + 1"></div>
                                    <img :src="asset.url" :alt="asset.default_alt_text || ''" class="h-10 w-10 rounded-lg object-cover">
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-xs font-medium text-gray-700" x-text="asset.internal_title || ('Asset #' + asset.id)"></p>
                                        <p class="text-[10px] text-gray-400" x-text="'ID: ' + asset.id"></p>
                                    </div>
                                    <div class="flex gap-1">
                                        <button type="button" @click="moveSelected(index, -1)" :disabled="index === 0" class="rounded-md border border-gray-200 p-1 text-gray-500 transition hover:bg-gray-50 disabled:opacity-30">
                                            <i data-lucide="arrow-up" class="h-3.5 w-3.5"></i>
                                        </button>
                                        <button type="button" @click="moveSelected(index, 1)" :disabled="index === selectedAssets.length - 1" class="rounded-md border border-gray-200 p-1 text-gray-500 transition hover:bg-gray-50 disabled:opacity-30">
                                            <i data-lucide="arrow-down" class="h-3.5 w-3.5"></i>
                                        </button>
                                        <button type="button" @click="remove(asset.id)" class="rounded-md border border-red-100 p-1 text-red-500 transition hover:bg-red-50">
                                            <i data-lucide="trash-2" class="h-3.5 w-3.5"></i>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </aside>
                </div>

                <div x-show="meta.last_page > 1" x-cloak class="flex flex-col gap-3 border-t border-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <button type="button" @click="fetchMedia(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs disabled:opacity-40">Prev</button>
                    <span class="text-xs text-gray-500" x-text="'Page ' + meta.current_page + ' of ' + meta.last_page"></span>
                    <button type="button" @click="fetchMedia(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="rounded-lg border border-gray-200 px-3 py-1.5 text-xs disabled:opacity-40">Next</button>
                </div>
            </div>

            <div x-show="tab === ' x-cloakupload'" class="min-h-[420px] flex-1 overflow-y-auto p-4 sm:p-6">
                <div x-ref="uploadForm" class="mx-auto max-w-2xl space-y-4">
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-text">File <span class="text-red-500">*</span></label>
                        <input type="file" x-ref="fileInput" accept="image/*,video/*" @change="handleFileSelect()" class="sr-only">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="flex h-20 w-20 items-center justify-center overflow-hidden rounded-xl border border-gray-200 bg-gray-50">
                                <img x-show="uploadPreviewSrc" :src="uploadPreviewSrc" alt="" class="h-full w-full object-cover" x-cloak>
                                <i x-show="!uploadPreviewSrc" x-cloak data-lucide="image" class="h-7 w-7 text-gray-300"></i>
                            </div>
                            <div class="min-w-0 flex-1">
                                <button type="button" @click="$refs.fileInput.click()" class="flex w-full items-center justify-center gap-2 rounded-xl border-2 border-dashed border-gray-300 px-4 py-2.5 text-sm text-gray-500 transition hover:border-forest hover:bg-forest-50 hover:text-forest">
                                    <i data-lucide="folder-open" class="h-4 w-4"></i> Choose File
                                </button>
                                <p class="mt-1.5 truncate text-xs text-gray-400" x-text="uploadFileName || 'No file selected. Images or videos.'"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-text">Internal Title <span class="text-red-500">*</span></label>
                        <input type="text" data-upload="title" class="w-full rounded-xl border border-gray-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-text">Description</label>
                        <textarea data-upload="description" rows="2" class="w-full rounded-xl border border-gray-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/30"></textarea>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-text">Alt Text</label>
                        <input type="text" data-upload="alt" class="w-full rounded-xl border border-gray-200 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    </div>
                    <div x-show="uploadError" x-cloak class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-700" x-text="uploadError"></div>
                    <button type="button" @click="uploadFile()" :disabled="uploading" class="flex w-full items-center justify-center gap-2 rounded-xl bg-forest px-5 py-2.5 text-sm font-medium text-white transition hover:bg-forest-light disabled:opacity-50">
                        <i data-lucide="upload" class="h-4 w-4"></i>
                        <span x-text="uploading ? 'Uploading…' : 'Upload & Add to Selection'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
