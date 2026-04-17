@extends('admin.layouts.app')
@section('title', 'Bulk Media Import')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Bulk Media Import" subtitle="Download and import curated media from approved URLs, or export/import your media library as JSON" />

<div x-data="bulkMediaImport()" class="space-y-6">

    {{-- Import from URLs --}}
    <x-admin.card title="Import Media from URLs">
        <div class="space-y-5">
            <p class="text-sm text-text-secondary">Upload a curated JSON dataset file or paste approved image URLs to bulk-download and import media into the library.</p>

            {{-- Tab toggle --}}
            <div class="flex w-full flex-col gap-1 rounded-xl bg-gray-100 p-1 sm:w-fit sm:flex-row">
                <button type="button" x-on:click="mode = 'file'"
                    :class="mode === 'file' ? 'bg-white shadow-sm text-text font-medium' : 'text-text-secondary hover:text-text'"
                    class="flex-1 px-4 py-2 text-sm rounded-lg transition">
                    <i data-lucide="file-json" class="w-4 h-4 inline -mt-0.5"></i> JSON File
                </button>
                <button type="button" x-on:click="mode = 'manual'"
                    :class="mode === 'manual' ? 'bg-white shadow-sm text-text font-medium' : 'text-text-secondary hover:text-text'"
                    class="flex-1 px-4 py-2 text-sm rounded-lg transition">
                    <i data-lucide="list-plus" class="w-4 h-4 inline -mt-0.5"></i> Manual Entry
                </button>
            </div>

            {{-- File Upload Mode --}}
            <div x-show="mode === 'file'" x-cloak class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">JSON Dataset File</label>
                    <input type="file" accept=".json,.txt" x-ref="jsonFile"
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-forest file:text-white file:text-xs file:font-medium">
                    <p class="text-xs text-text-secondary mt-1">Expected format: <code class="bg-gray-100 px-1 rounded">{"items": [{"url": "...", "internal_title": "...", ...}]}</code></p>
                </div>
                <button type="button" x-on:click="validateFile()" :disabled="validating"
                    class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    <span x-text="validating ? 'Validating...' : 'Validate Dataset'"></span>
                </button>
            </div>

            {{-- Manual Entry Mode --}}
            <div x-show="mode === 'manual'" x-cloak class="space-y-4">
                <template x-for="(entry, idx) in manualEntries" :key="idx">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3 p-4 bg-gray-50 rounded-xl relative">
                        <div class="md:col-span-5">
                            <label class="block text-xs font-medium text-text-secondary mb-1">Image URL *</label>
                            <input type="url" x-model="entry.url" placeholder="https://unilock.com/wp-content/uploads/2024/09/Driveway.png"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-text-secondary mb-1">Title *</label>
                            <input type="text" x-model="entry.internal_title" placeholder="Lawn Mowing Hero"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-text-secondary mb-1">Alt Text</label>
                            <input type="text" x-model="entry.default_alt_text" placeholder="Professional lawn mowing"
                                   class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <div class="md:col-span-1 flex items-end">
                            <button type="button" x-on:click="manualEntries.splice(idx, 1)" x-show="manualEntries.length > 1" x-cloak
                                class="p-2 text-red-400 hover:text-red-600 transition" aria-label="Remove entry">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </template>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="button" x-on:click="manualEntries.push({url: '', internal_title: '', default_alt_text: '', credit: '', description: ''})"
                        class="inline-flex items-center gap-2 px-4 py-2 border border-gray-200 rounded-xl text-sm text-text-secondary hover:text-text hover:border-forest/30 transition">
                        <i data-lucide="plus" class="w-4 h-4"></i> Add Row
                    </button>
                    <button type="button" x-on:click="startManualImport()" :disabled="importing"
                        class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2 rounded-xl transition text-sm disabled:opacity-50">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span x-text="importing ? 'Importing...' : 'Import All'"></span>
                    </button>
                </div>
            </div>

            {{-- Validation Result --}}
            <div x-show="validationResult" x-cloak class="space-y-3">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-center">
                        <p class="text-2xl font-bold text-green-700" x-text="validationResult?.valid ?? 0"></p>
                        <p class="text-xs text-green-600">Valid Items</p>
                    </div>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-xl text-center">
                        <p class="text-2xl font-bold text-red-700" x-text="validationResult?.invalid ?? 0"></p>
                        <p class="text-xs text-red-600">Invalid Items</p>
                    </div>
                </div>

                <template x-if="validationResult?.errors?.length > 0">
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-xs font-medium text-amber-700 mb-1">Validation Errors:</p>
                        <template x-for="err in validationResult.errors" :key="err">
                            <p class="text-xs text-amber-600" x-text="err"></p>
                        </template>
                    </div>
                </template>

                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:gap-4" x-show="validationResult?.valid > 0" x-cloak>
                    <button type="button" x-on:click="startFileImport()" :disabled="importing"
                        class="inline-flex items-center justify-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
                        <i data-lucide="download" class="w-4 h-4"></i>
                        <span x-text="importing ? 'Importing...' : 'Start Import (' + validationResult.valid + ' items)'"></span>
                    </button>
                    <button type="button" x-on:click="validationResult = null; pendingItems = []" class="text-sm text-text-secondary hover:text-text transition">Cancel</button>
                </div>
            </div>

            {{-- Error Message --}}
            <div x-show="errorMsg" x-cloak class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm text-red-700 font-medium" x-text="errorMsg"></p>
            </div>
        </div>
    </x-admin.card>

    {{-- Progress Panel --}}
    <div x-show="importing" x-cloak>
        <x-admin.card title="Import Progress">
            <div class="space-y-4">
                {{-- Progress bar --}}
                <div>
                    <div class="mb-2 flex flex-col gap-1 text-sm sm:flex-row sm:items-center sm:justify-between">
                        <span class="font-medium text-text">
                            <span x-text="progressDone"></span> / <span x-text="progressTotal"></span> items
                        </span>
                        <span class="text-text-secondary" x-text="progressPercent + '%'"></span>
                    </div>
                    <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-forest rounded-full transition-all duration-300 ease-out"
                             :style="'width: ' + progressPercent + '%'"></div>
                    </div>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-center">
                        <p class="text-xl font-bold text-green-700" x-text="stats.success"></p>
                        <p class="text-xs text-green-600">Downloaded</p>
                    </div>
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-center">
                        <p class="text-xl font-bold text-amber-700" x-text="stats.skipped"></p>
                        <p class="text-xs text-amber-600">Duplicates</p>
                    </div>
                    <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-center">
                        <p class="text-xl font-bold text-red-700" x-text="stats.failed"></p>
                        <p class="text-xs text-red-600">Failed</p>
                    </div>
                </div>

                {{-- Live log --}}
                <div class="max-h-64 overflow-y-auto border border-gray-200 rounded-xl">
                    <template x-for="log in progressLog.slice(-50)" :key="log.index + '-' + log.status">
                        <div class="flex flex-col items-start gap-2 px-4 py-2 text-sm border-b border-gray-50 last:border-0 sm:flex-row sm:items-center sm:gap-3"
                             :class="{
                                 'bg-green-50/50': log.status === 'success',
                                 'bg-amber-50/50': log.status === 'skipped',
                                 'bg-red-50/50': log.status === 'failed'
                             }">
                            <i :data-lucide="log.status === 'success' ? 'check-circle' : log.status === 'skipped' ? 'skip-forward' : 'x-circle'"
                               :class="{
                                   'text-green-500': log.status === 'success',
                                   'text-amber-500': log.status === 'skipped',
                                   'text-red-500': log.status === 'failed'
                               }"
                               class="w-4 h-4 shrink-0"></i>
                            <span class="flex-1 truncate" x-text="log.title"></span>
                            <span class="text-xs text-text-secondary sm:shrink-0" x-text="log.message"></span>
                        </div>
                    </template>
                </div>
            </div>
        </x-admin.card>
    </div>

    {{-- Import Complete --}}
    <div x-show="importComplete" x-cloak>
        <x-admin.card title="Import Complete">
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl space-y-3">
                <p class="text-sm text-green-700 font-medium">
                    Import finished. <span x-text="stats.success"></span> downloaded, <span x-text="stats.skipped"></span> skipped, <span x-text="stats.failed"></span> failed.
                </p>
                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('admin.media.index') }}" class="inline-flex items-center justify-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-4 py-2 rounded-xl transition text-sm">
                        <i data-lucide="image" class="w-4 h-4"></i> View Media Library
                    </a>
                    <button type="button" x-on:click="resetAll()" class="text-sm text-text-secondary hover:text-text transition">Import More</button>
                </div>
            </div>
        </x-admin.card>
    </div>

    {{-- Generate Dataset --}}
    <x-admin.card title="Auto-Generate Dataset">
        <div class="space-y-4">
            <p class="text-sm text-text-secondary">Generate the curated media dataset built from approved official-source image links. The generated JSON contains verified URLs, metadata, and placement mappings for the site-wide media library import pack.</p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('admin.bulk-import.generate-dataset') }}"
                   class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm">
                    <i data-lucide="sparkles" class="w-4 h-4"></i> Generate & Download Dataset
                </a>
            </div>
            <div class="p-3 bg-blue-50 border border-blue-200 rounded-xl">
                <p class="text-xs text-blue-700"><strong>CLI alternative:</strong> Run <code class="bg-blue-100 px-1 rounded">python3 scripts/build_curated_media_catalog.py</code> to rebuild the official-source catalog locally, then run <code class="bg-blue-100 px-1 rounded">php artisan media:acquire --generate-dataset</code> to export the curated dataset JSON.</p>
            </div>
        </div>
    </x-admin.card>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Export Library --}}
        <x-admin.card title="Export Media Library">
            <div class="space-y-4">
                <p class="text-sm text-text-secondary">Download all media asset metadata as a JSON file. This includes titles, descriptions, alt text, credits, and tags (not the actual image files).</p>
                <a href="{{ route('admin.bulk-import.export-library') }}"
                   class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm">
                    <i data-lucide="download" class="w-4 h-4"></i> Export as JSON
                </a>
            </div>
        </x-admin.card>

        {{-- Import Library Metadata --}}
        <x-admin.card title="Import Library Metadata">
            <div class="space-y-4">
                <p class="text-sm text-text-secondary">Update existing media asset metadata from a previously exported JSON file. Only metadata fields are updated (no file uploads).</p>
                <div>
                    <input type="file" accept=".json,.txt" x-ref="libraryFile"
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-forest file:text-white file:text-xs file:font-medium">
                </div>
                <button type="button" x-on:click="importMetadata()" :disabled="metadataImporting"
                    class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
                    <i data-lucide="upload" class="w-4 h-4"></i>
                    <span x-text="metadataImporting ? 'Importing...' : 'Import Metadata'"></span>
                </button>
                <div x-show="metadataResult" x-cloak class="p-3 bg-green-50 border border-green-200 rounded-xl">
                    <p class="text-sm text-green-700" x-text="metadataResult"></p>
                </div>
            </div>
        </x-admin.card>
    </div>

    {{-- Recent Bulk Imports --}}
    @if($recentImports->isNotEmpty())
    <x-admin.card title="Recent Bulk Imports">
        <div class="overflow-x-auto overscroll-x-contain">
            <table class="min-w-[720px] w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left font-medium text-text-secondary">Preview</th>
                        <th class="px-4 py-2 text-left font-medium text-text-secondary">Title</th>
                        <th class="px-4 py-2 text-left font-medium text-text-secondary">Credit</th>
                        <th class="px-4 py-2 text-left font-medium text-text-secondary">Dimensions</th>
                        <th class="px-4 py-2 text-left font-medium text-text-secondary">Imported</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentImports as $asset)
                    <tr class="border-t border-gray-100">
                        <td class="px-4 py-2">
                            @if($asset->media_type === 'image')
                            <img src="{{ $asset->url }}" alt="{{ $asset->alt_text ?? $asset->title ?? 'Imported media' }}" class="w-12 h-12 object-cover rounded-lg">
                            @else
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="video" class="w-5 h-5 text-gray-400"></i>
                            </div>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-text font-medium">{{ Str::limit($asset->internal_title, 40) }}</td>
                        <td class="px-4 py-2 text-text-secondary">{{ $asset->credit }}</td>
                        <td class="px-4 py-2 text-text-secondary">{{ $asset->width }}x{{ $asset->height }}</td>
                        <td class="px-4 py-2 text-text-secondary">{{ $asset->created_at->diffForHumans() }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-admin.card>
    @endif
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('bulkMediaImport', () => ({
        mode: 'file',
        validating: false,
        importing: false,
        importComplete: false,
        errorMsg: null,
        validationResult: null,
        pendingItems: [],
        metadataImporting: false,
        metadataResult: null,

        // Manual entry
        manualEntries: [{ url: '', internal_title: '', default_alt_text: '', credit: '', description: '' }],

        // Progress tracking
        progressTotal: 0,
        progressDone: 0,
        progressLog: [],
        stats: { success: 0, skipped: 0, failed: 0 },

        get progressPercent() {
            return this.progressTotal > 0 ? Math.round((this.progressDone / this.progressTotal) * 100) : 0;
        },

        async validateFile() {
            const file = this.$refs.jsonFile?.files[0];
            if (!file) { this.errorMsg = 'Please select a JSON file.'; return; }

            this.validating = true;
            this.errorMsg = null;
            this.validationResult = null;

            const formData = new FormData();
            formData.append('file', file);

            try {
                const res = await fetch('{{ route("admin.bulk-import.upload") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });
                const data = await res.json();
                if (data.success) {
                    this.validationResult = data;
                    this.pendingItems = data.items || [];
                } else {
                    this.errorMsg = data.message || 'Validation failed.';
                }
            } catch (e) {
                this.errorMsg = 'Network error. Please try again.';
            } finally {
                this.validating = false;
            }
        },

        startFileImport() {
            if (!this.pendingItems.length) return;
            this._runImport(this.pendingItems);
        },

        startManualImport() {
            const valid = this.manualEntries.filter(e => e.url && e.internal_title);
            if (!valid.length) { this.errorMsg = 'Add at least one entry with URL and title.'; return; }
            this._runImport(valid);
        },

        _runImport(items) {
            // Process in batches of 100 via SSE
            this.importing = true;
            this.importComplete = false;
            this.errorMsg = null;
            this.progressTotal = items.length;
            this.progressDone = 0;
            this.progressLog = [];
            this.stats = { success: 0, skipped: 0, failed: 0 };

            const batchSize = 50;
            let currentBatch = 0;
            const totalBatches = Math.ceil(items.length / batchSize);

            const processBatch = (batchIndex) => {
                const start = batchIndex * batchSize;
                const batch = items.slice(start, start + batchSize);
                if (!batch.length) {
                    this.importing = false;
                    this.importComplete = true;
                    return;
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                fetch('{{ route("admin.bulk-import.process") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'text/event-stream',
                    },
                    body: JSON.stringify({ items: batch }),
                }).then(response => {
                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();
                    let buffer = '';

                    const read = () => {
                        reader.read().then(({ done, value }) => {
                            if (done) {
                                if (batchIndex + 1 < totalBatches) {
                                    processBatch(batchIndex + 1);
                                } else {
                                    this.importing = false;
                                    this.importComplete = true;
                                }
                                return;
                            }

                            buffer += decoder.decode(value, { stream: true });
                            const lines = buffer.split('\n');
                            buffer = lines.pop();

                            let eventType = '';
                            for (const line of lines) {
                                if (line.startsWith('event: ')) {
                                    eventType = line.substring(7).trim();
                                } else if (line.startsWith('data: ')) {
                                    try {
                                        const data = JSON.parse(line.substring(6));
                                        if (eventType === 'progress') {
                                            this.progressDone = start + data.index + 1;
                                            this.progressLog.push({
                                                index: start + data.index,
                                                status: data.status,
                                                title: data.title,
                                                message: data.message,
                                            });
                                            if (data.status === 'success') this.stats.success++;
                                            else if (data.status === 'skipped') this.stats.skipped++;
                                            else this.stats.failed++;

                                            // Re-render Lucide icons for new log entries
                                            this.$nextTick(() => {
                                                if (window.refreshIcons) window.refreshIcons();
                                            });
                                        }
                                    } catch (e) { /* skip malformed JSON */ }
                                }
                            }

                            read();
                        });
                    };

                    read();
                }).catch(err => {
                    this.errorMsg = 'Connection error during import: ' + err.message;
                    this.importing = false;
                });
            };

            processBatch(0);
        },

        async importMetadata() {
            const file = this.$refs.libraryFile?.files[0];
            if (!file) { this.errorMsg = 'Please select a JSON file.'; return; }

            this.metadataImporting = true;
            this.metadataResult = null;
            this.errorMsg = null;

            const formData = new FormData();
            formData.append('file', file);

            try {
                const res = await fetch('{{ route("admin.bulk-import.import-library") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: formData,
                });
                const data = await res.json();
                this.metadataResult = data.message;
            } catch (e) {
                this.errorMsg = 'Network error.';
            } finally {
                this.metadataImporting = false;
            }
        },

        resetAll() {
            this.importing = false;
            this.importComplete = false;
            this.errorMsg = null;
            this.validationResult = null;
            this.pendingItems = [];
            this.progressTotal = 0;
            this.progressDone = 0;
            this.progressLog = [];
            this.stats = { success: 0, skipped: 0, failed: 0 };
        },
    }));
});
</script>
@endsection
