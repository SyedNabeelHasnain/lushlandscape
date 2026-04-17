@props([
    'fieldKeyExpression',
    'modelExpression',
    'containerClass' => 'flex items-start gap-4 p-3 border border-gray-100 rounded-xl bg-gray-50/50',
    'emptyText' => 'No media selected',
])

<div x-data="mediaPickerModal({!! $fieldKeyExpression !!}, {!! $modelExpression !!} || null, '')"
     x-effect="if (fieldId !== null && {!! $modelExpression !!} !== fieldId) {!! $modelExpression !!} = fieldId; if (!fieldId && {!! $modelExpression !!} !== null) {!! $modelExpression !!} = null; if (!fieldId && !open) previewUrl = ''"
     class="{{ $containerClass }}">

    <div class="w-16 h-16 rounded-lg border border-gray-200 bg-white flex items-center justify-center overflow-hidden shrink-0">
        <img x-show="previewUrl" :src="previewUrl" class="w-full h-full object-cover" x-cloak>
        <i x-show="!previewUrl" x-cloak data-lucide="image" class="w-6 h-6 text-gray-300"></i>
    </div>

    <div class="flex-1 space-y-2">
        <div class="flex flex-col gap-2 sm:flex-row">
            <button type="button" @click="openModal()" class="px-3 py-1.5 bg-forest text-white rounded-lg text-xs hover:bg-forest-light transition">
                <span x-text="fieldId ? 'Change Image' : 'Browse Library'"></span>
            </button>
            <button type="button" x-show="fieldId" x-cloak @click="clear(); {!! $modelExpression !!} = null" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs text-gray-500 hover:bg-gray-50 transition">
                Remove
            </button>
        </div>
        <p class="text-[10px] text-gray-400 font-medium" x-text="fieldId ? 'Selected ID: ' + fieldId : '{{ $emptyText }}'"></p>
    </div>

    <div x-show="open" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/60" @keydown.escape.window="open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" @click.stop>
            <div class="flex flex-wrap items-start justify-between gap-3 px-4 py-4 border-b border-gray-100 sm:px-6">
                <h3 class="font-semibold text-text">Select Media</h3>
                <div class="flex flex-wrap items-center justify-end gap-3">
                    <div class="flex flex-wrap gap-1">
                        <button type="button" @click="tab = 'library'" :class="tab === 'library' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition">Library</button>
                        <button type="button" @click="tab = 'upload'" :class="tab === 'upload' ? 'bg-forest text-white' : 'text-gray-500 hover:bg-gray-100'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition">Upload</button>
                    </div>
                    <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>

            <div x-show="tab === ' x-cloaklibrary'" class="flex-1 overflow-hidden flex flex-col min-h-[400px]">
                <div class="flex flex-col gap-3 border-b border-gray-100 px-4 py-3 sm:flex-row sm:px-6">
                    <input type="text" x-model="search" @input.debounce.400ms="fetchMedia(1)" placeholder="Search by title…" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    <select x-model="mediaType" @change="fetchMedia(1)" class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30 sm:w-44">
                        <option value="">All Types</option>
                        <option value="image">Images</option>
                        <option value="video">Videos</option>
                    </select>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    <div x-show="loading" x-cloak class="flex justify-center py-12">
                        <i data-lucide="loader-2" class="w-6 h-6 animate-spin text-forest"></i>
                    </div>
                    <div x-show="!loading && assets.length === 0" x-cloak class="text-center py-12 text-sm text-gray-400">No media found.</div>
                    <div x-show="!loading && assets.length > 0" x-cloak class="grid grid-cols-2 gap-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6">
                        <template x-for="asset in assets" :key="asset.id">
                            <button type="button" @click="select(asset)"
                                :class="fieldId === asset.id ? 'ring-2 ring-forest' : 'hover:ring-2 hover:ring-forest/40'"
                                class="relative aspect-square rounded-lg overflow-hidden bg-gray-100 border border-gray-200 transition">
                                <img :src="asset.url" :alt="asset.default_alt_text" class="w-full h-full object-cover">
                                <div x-show="fieldId === asset.id" x-cloak class="absolute inset-0 bg-forest/20 flex items-center justify-center">
                                    <i data-lucide="check-circle" class="w-6 h-6 text-forest"></i>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>
                <div x-show="meta.last_page > 1" x-cloak class="flex flex-col gap-3 border-t border-gray-100 px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-6">
                    <button type="button" @click="fetchMedia(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs disabled:opacity-40">Prev</button>
                    <span class="text-xs text-gray-500" x-text="'Page ' + meta.current_page + ' of ' + meta.last_page"></span>
                    <button type="button" @click="fetchMedia(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs disabled:opacity-40">Next</button>
                </div>
            </div>

            <div x-show="tab === ' x-cloakupload'" class="flex-1 overflow-y-auto p-4 min-h-[400px] sm:p-6">
                <div x-ref="uploadForm" class="max-w-2xl mx-auto space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">File <span class="text-red-500">*</span></label>
                        <input type="file" x-ref="fileInput" accept="image/*,video/*" @change="handleFileSelect()" class="sr-only">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="w-20 h-20 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
                                <img x-show="uploadPreviewSrc" :src="uploadPreviewSrc" alt="" class="w-full h-full object-cover" x-cloak>
                                <i x-show="!uploadPreviewSrc" x-cloak data-lucide="image" class="w-7 h-7 text-gray-300"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <button type="button" @click="$refs.fileInput.click()" class="flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-gray-500 hover:border-forest hover:text-forest hover:bg-forest-50 transition w-full justify-center">
                                    <i data-lucide="folder-open" class="w-4 h-4"></i> Choose File
                                </button>
                                <p class="mt-1.5 text-xs text-gray-400 truncate" x-text="uploadFileName || 'No file selected. Images or videos.'"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Internal Title <span class="text-red-500">*</span></label>
                        <input type="text" data-upload="title" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Description</label>
                        <textarea data-upload="description" rows="2" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Alt Text</label>
                        <input type="text" data-upload="alt" class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    </div>
                    <div x-show="uploadError" x-cloak class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700" x-text="uploadError"></div>
                    <button type="button" @click="uploadFile()" :disabled="uploading" class="flex items-center justify-center gap-2 px-5 py-2.5 bg-forest text-white rounded-xl text-sm hover:bg-forest-light transition disabled:opacity-50 w-full font-medium">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        <span x-text="uploading ? 'Uploading…' : 'Upload & Select'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
