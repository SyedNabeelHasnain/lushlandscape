@props(['name', 'label', 'mediaAsset' => null, 'help' => '', 'tooltip' => '', 'croppable' => false])
<div x-data="mediaPickerModal('{{ $name }}', {{ $mediaAsset?->id ?? 'null' }}, '{{ $mediaAsset?->url ?? '' }}')">
    <div class="flex items-center gap-1.5 mb-1.5">
        <label class="text-sm font-medium text-text">{{ $label }}</label>
        <x-admin.tooltip :content="$tooltip" />
    </div>
    <input type="hidden" :name="fieldName" :value="fieldId">

    <div class="flex flex-col items-start gap-4 sm:flex-row">
        {{-- Preview --}}
        <div class="w-24 h-24 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
            <img x-show="previewUrl" :src="previewUrl" alt="" class="w-full h-full object-cover" x-cloak>
            <i x-show="!previewUrl" x-cloak data-lucide="image" class="w-8 h-8 text-gray-300"></i>
        </div>
        <div class="flex-1 space-y-2">
            <div class="flex flex-col gap-2 sm:flex-row">
                <button type="button" x-on:click="openModal()" class="px-3 py-2 bg-forest text-white rounded-lg text-xs hover:bg-forest-light transition sm:w-auto">
                    <span x-text="fieldId ? 'Change Image' : 'Browse Library'"></span>
                </button>
                <button type="button" x-show="fieldId" x-cloak x-on:click="clear()" class="px-3 py-2 border border-gray-200 rounded-lg text-xs text-text-secondary hover:bg-gray-50 transition">Remove</button>
                @if($croppable)
                <button type="button" x-show="fieldId && previewUrl" x-cloak x-on:click="openCrop()" class="px-3 py-2 border border-gray-200 rounded-lg text-xs text-text-secondary hover:bg-gray-50 transition flex items-center gap-1">
                    <i data-lucide="crop" class="w-3.5 h-3.5"></i> Crop
                </button>
                @endif
            </div>
            <p class="text-xs text-text-secondary" x-text="fieldId ? 'Selected ID: ' + fieldId : 'No image selected'"></p>
            @if($help)<p class="text-xs text-text-secondary">{{ $help }}</p>@endif
            @error($name)<p class="text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
    </div>

    {{-- Modal — rendered outside the flow but inside Alpine scope --}}
    <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60"
        x-on:keydown.escape.window="open = false">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col" x-on:click.stop>
            <div class="flex flex-wrap items-center justify-between gap-3 px-4 py-4 border-b border-gray-100 sm:px-6">
                <h3 class="font-semibold text-text">Select Media</h3>
                <div class="flex flex-wrap items-center gap-3 sm:gap-4">
                    <div class="flex flex-wrap gap-1">
                        <button type="button" x-on:click="tab = 'library'" :class="tab === 'library' ? 'bg-forest text-white' : 'text-text-secondary hover:bg-gray-100'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition">Library</button>
                        <button type="button" x-on:click="tab = 'upload'" :class="tab === 'upload' ? 'bg-forest text-white' : 'text-text-secondary hover:bg-gray-100'" class="px-3 py-1.5 rounded-lg text-xs font-medium transition">Upload</button>
                    </div>
                    <button type="button" x-on:click="open = false" class="text-text-secondary hover:text-text"><i data-lucide="x" class="w-5 h-5"></i></button>
                </div>
            </div>

            {{-- Library Tab --}}
            <div x-show="tab === 'library'" x-cloak class="flex-1 overflow-hidden flex flex-col">
                <div class="flex flex-col gap-3 px-4 py-3 border-b border-gray-100 sm:flex-row sm:px-6">
                    <input type="text" x-model="search" x-on:input.debounce.400ms="fetchMedia(1)" placeholder="Search by title…" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    <select x-model="mediaType" x-on:change="fetchMedia(1)" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">All Types</option>
                        <option value="image">Images</option>
                        <option value="video">Videos</option>
                    </select>
                </div>
                <div class="flex-1 overflow-y-auto p-4">
                    <div x-show="loading" x-cloak class="flex justify-center py-12"><i data-lucide="loader-2" class="w-6 h-6 animate-spin text-forest"></i></div>
                    <div x-show="!loading && assets.length === 0" x-cloak class="text-center py-12 text-sm text-text-secondary">No media found.</div>
                    <div x-show="!loading && assets.length > 0" x-cloak class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                        <template x-for="asset in assets" :key="asset.id">
                            <button type="button" x-on:click="select(asset)"
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
                <div x-show="meta.last_page > 1" x-cloak class="flex flex-wrap items-center justify-between gap-3 px-4 py-3 border-t border-gray-100 sm:px-6">
                    <button type="button" x-on:click="fetchMedia(meta.current_page - 1)" :disabled="meta.current_page <= 1" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs disabled:opacity-40">Prev</button>
                    <span class="text-xs text-text-secondary" x-text="'Page ' + meta.current_page + ' of ' + meta.last_page"></span>
                    <button type="button" x-on:click="fetchMedia(meta.current_page + 1)" :disabled="meta.current_page >= meta.last_page" class="px-3 py-1.5 border border-gray-200 rounded-lg text-xs disabled:opacity-40">Next</button>
                </div>
            </div>

            {{-- Upload Tab --}}
            <div x-show="tab === 'upload'" x-cloak class="flex-1 overflow-y-auto p-4 sm:p-6">
                <div x-ref="uploadForm" class="space-y-4">
                    {{-- Styled file picker --}}
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">File <span class="text-red-500">*</span></label>
                        <input type="file" x-ref="fileInput" accept="image/*,video/*" x-on:change="handleFileSelect()" class="sr-only">
                        <div class="flex flex-col items-start gap-4 sm:flex-row sm:items-center">
                            <div class="w-20 h-20 rounded-xl border border-gray-200 bg-gray-50 flex items-center justify-center overflow-hidden shrink-0">
                                <img x-show="uploadPreviewSrc" :src="uploadPreviewSrc" alt="" class="w-full h-full object-cover" x-cloak>
                                <i x-show="!uploadPreviewSrc" x-cloak data-lucide="image" class="w-7 h-7 text-gray-300"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <button type="button" x-on:click="$refs.fileInput.click()"
                                    class="flex items-center gap-2 px-4 py-2.5 border-2 border-dashed border-gray-300 rounded-xl text-sm text-text-secondary hover:border-forest hover:text-forest hover:bg-forest-50 transition w-full justify-center">
                                    <i data-lucide="folder-open" class="w-4 h-4"></i>
                                    Choose File
                                </button>
                                <p class="mt-1.5 text-xs text-text-secondary truncate" x-text="uploadFileName || 'No file selected. Images or videos.'"></p>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Internal Title <span class="text-red-500">*</span></label>
                        <input type="text" data-upload="title" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Description</label>
                        <textarea data-upload="description" rows="2" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-text mb-1.5">Alt Text</label>
                        <input type="text" data-upload="alt" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-forest/30">
                    </div>
                    <div x-show="uploadError" x-cloak class="p-3 bg-red-50 border border-red-200 rounded-lg text-sm text-red-700" x-text="uploadError"></div>
                    <button type="button" x-on:click="uploadFile()" :disabled="uploading" class="flex items-center gap-2 px-5 py-2.5 bg-forest text-white rounded-xl text-sm hover:bg-forest-light transition disabled:opacity-50 font-medium">
                        <i data-lucide="upload" class="w-4 h-4"></i>
                        <span x-text="uploading ? 'Uploading…' : 'Upload & Select'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @if($croppable)
    {{-- Hidden input for crop coordinates --}}
    <input type="hidden" :name="fieldName + '_crop_data'" :value="cropData">

    {{-- Crop modal --}}
    <div x-show="cropOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/70"
        x-on:keydown.escape.window="cancelCrop()">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl flex flex-col" x-on:click.stop>
            <div class="flex flex-wrap items-start justify-between gap-3 px-4 py-4 border-b border-gray-100 sm:px-6">
                <h3 class="font-semibold text-text">Crop Image</h3>
                <button type="button" x-on:click="cancelCrop()" class="text-text-secondary hover:text-text"><i data-lucide="x" class="w-5 h-5"></i></button>
            </div>
            <div class="flex items-center gap-2 flex-wrap px-4 py-3 border-b border-gray-100 sm:px-6">
                <span class="text-xs font-medium text-text-secondary mr-1">Aspect:</span>
                <template x-for="preset in cropPresets" :key="preset.label">
                    <button type="button" x-on:click="setCropAspect(preset.ratio)" x-text="preset.label"
                        class="px-2.5 py-1 border border-gray-200 rounded-lg text-xs text-text-secondary hover:bg-forest hover:text-white hover:border-forest transition"></button>
                </template>
            </div>
            <div class="p-4 flex justify-center sm:p-6" style="max-height: 60vh; overflow: hidden;">
                <img x-ref="cropImage" alt="" style="max-width: 100%; max-height: 55vh; display: block;">
            </div>
            <div class="flex flex-col gap-3 px-4 py-4 border-t border-gray-100 sm:flex-row sm:items-center sm:justify-end sm:px-6">
                <button type="button" x-on:click="cancelCrop()" class="px-4 py-2 border border-gray-200 rounded-xl text-sm text-text-secondary hover:bg-gray-50 transition">Cancel</button>
                <button type="button" x-on:click="applyCrop()" class="px-4 py-2 bg-forest text-white rounded-xl text-sm hover:bg-forest-light transition flex items-center gap-1.5">
                    <i data-lucide="check" class="w-4 h-4"></i> Apply Crop
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
