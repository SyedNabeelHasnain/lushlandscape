@props(['type', 'id'])
<div class="bg-cream rounded-xl border border-gray-100 p-5"
     x-data="{
        importing: false,
        previewing: false,
        preview: null,
        error: null,
        file: null,

        async doPreview() {
            if (!this.file) return;
            this.previewing = true;
            this.preview = null;
            this.error = null;
            const fd = new FormData();
            fd.append('file', this.file);
            fd.append('_token', '{{ csrf_token() }}');
            try {
                const res = await fetch('{{ route('admin.content-blocks.preview', [$type, $id]) }}', { method: 'POST', body: fd });
                const json = await res.json();
                if (json.success) { this.preview = json.preview; }
                else { this.error = json.message || 'Preview failed.'; }
            } catch { this.error = 'Network error.'; }
            finally { this.previewing = false; }
        },
        async doImport() {
            if (!this.file) return;
            this.importing = true;
            this.error = null;
            const fd = new FormData();
            fd.append('file', this.file);
            fd.append('_token', '{{ csrf_token() }}');
            try {
                const res = await fetch('{{ route('admin.content-blocks.import', [$type, $id]) }}', { method: 'POST', body: fd });
                const json = await res.json();
                if (json.success) {
                    if (window.adminToast) window.adminToast(json.message, 'success');
                    this.preview = null;
                    this.file = null;
                    setTimeout(() => location.reload(), 1000);
                } else { this.error = json.message || 'Import failed.'; }
            } catch { this.error = 'Network error.'; }
            finally { this.importing = false; }
        }
     }">
    <h4 class="text-sm font-bold text-text mb-3 flex items-center gap-2">
        <i data-lucide="package" class="w-4 h-4 text-forest"></i>
        Page Builder Import / Export
    </h4>

    {{-- Export --}}
    <a href="{{ route('admin.content-blocks.export', [$type, $id]) }}"
       class="inline-flex items-center gap-2 text-sm font-medium text-forest bg-white border border-gray-200 px-4 py-2 rounded-lg hover:bg-forest-50 transition w-full justify-center"
       data-tippy-content="Download all page-builder blocks for this page as JSON">
        <i data-lucide="download" class="w-4 h-4"></i>Export Builder JSON
    </a>

    {{-- Import --}}
    <div class="mt-3">
        <label class="block text-xs font-medium text-text-secondary mb-1.5">Import Builder JSON</label>
        <input type="file" accept=".json"
               x-on:change="file = $event.target.files[0]; preview = null; error = null;"
               class="w-full text-xs file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-forest-50 file:text-forest hover:file:bg-forest-100 cursor-pointer">
    </div>

    <div class="mt-2 flex gap-2">
        <button type="button" x-on:click="doPreview()" :disabled="!file || previewing"
                class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-medium bg-white border border-gray-200 px-3 py-2 rounded-lg hover:bg-gray-50 transition disabled:opacity-40 disabled:cursor-not-allowed">
            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
            <span x-text="previewing ? 'Checking...' : 'Preview'"></span>
        </button>
        <button type="button" x-on:click="doImport()" :disabled="!file || !preview || importing"
                class="flex-1 inline-flex items-center justify-center gap-1.5 text-xs font-bold bg-forest text-white px-3 py-2 rounded-lg hover:bg-forest-light transition disabled:opacity-40 disabled:cursor-not-allowed">
            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
            <span x-text="importing ? 'Importing...' : 'Confirm Import'"></span>
        </button>
    </div>

    {{-- Error --}}
    <template x-if="error">
        <p class="mt-2 text-xs text-red-600 bg-red-50 rounded-lg px-3 py-2" x-text="error"></p>
    </template>

    {{-- Preview --}}
    <template x-if="preview">
        <div class="mt-3 bg-white rounded-lg border border-gray-200 p-3">
            <p class="text-xs font-medium text-text mb-2">
                Import Preview: <span class="text-forest" x-text="preview.import_count + ' items'"></span>
                (replacing <span class="text-amber-600" x-text="preview.current_count + ' existing'"></span>)
            </p>
            <div class="space-y-1 max-h-40 overflow-y-auto">
                <template x-for="(b, i) in preview.blocks" :key="i">
                    <div class="flex items-center gap-2 text-xs text-text-secondary px-2 py-1 rounded bg-gray-50">
                        <span class="w-5 h-5 bg-forest-50 rounded flex items-center justify-center text-[10px] font-bold text-forest shrink-0" x-text="i + 1"></span>
                        <span class="truncate" x-text="b.block_type"></span>
                        <span class="text-gray-400 truncate ml-auto text-[10px]" x-text="b.is_layout_section ? 'Layout Section' : (b.category || 'Block')"></span>
                    </div>
                </template>
            </div>
            <p class="mt-2 text-[10px] text-amber-600">This will replace all existing page-builder items for this page.</p>
        </div>
    </template>
</div>
