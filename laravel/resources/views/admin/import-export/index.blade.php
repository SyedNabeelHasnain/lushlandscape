@extends('admin.layouts.app')
@section('title', 'Import / Export')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Import / Export" subtitle="Export website data as CSV or import data from CSV files" />

<div x-data="importExport()" class="space-y-6">
    {{-- Export Section --}}
    <x-admin.card title="Export Data">
        <div class="space-y-4">
            <p class="text-sm text-text-secondary">Download any table as a CSV file. The export includes all columns and rows.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                @foreach($tables as $key => $label)
                <form method="POST" action="{{ route('admin.import-export.export') }}">
                    @csrf
                    <input type="hidden" name="table" value="{{ $key }}">
                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 bg-white border border-gray-200 rounded-xl text-sm text-text hover:border-forest/30 hover:text-forest transition">
                        <i data-lucide="download" class="w-4 h-4 text-forest"></i>
                        {{ $label }}
                    </button>
                </form>
                @endforeach
            </div>
        </div>
    </x-admin.card>

    {{-- Import Section --}}
    <x-admin.card title="Import Data">
        <div class="space-y-5">
            <p class="text-sm text-text-secondary">Upload a CSV file to import data. The system will validate the file first, then show a preview before applying changes.</p>

            {{-- Step 1: Upload --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">Target Table</label>
                    <select x-model="importTable" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm bg-white">
                        <option value="">Select table...</option>
                        @foreach($tables as $key => $label)
                        <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-text mb-1.5">CSV File</label>
                    <input type="file" accept=".csv,.txt" x-ref="csvFile"
                           class="w-full px-4 py-2 border border-gray-200 rounded-xl text-sm file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:bg-forest file:text-white file:text-xs file:font-medium">
                </div>
            </div>

            <button type="button" x-on:click="uploadFile()"
                    :disabled="uploading || !importTable"
                    class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
                <i data-lucide="upload" class="w-4 h-4"></i>
                <span x-text="uploading ? 'Validating...' : 'Validate & Preview'"></span>
            </button>

            {{-- Validation Errors --}}
            <div x-show="uploadError" x-cloak class="p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-sm text-red-700 font-medium" x-text="uploadError"></p>
            </div>

            {{-- Step 2: Preview --}}
            <div x-show="preview" x-cloak class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-center">
                        <p class="text-2xl font-bold text-green-700" x-text="preview?.creates ?? 0"></p>
                        <p class="text-xs text-green-600">New Records</p>
                    </div>
                    <div class="p-4 bg-blue-50 border border-blue-200 rounded-xl text-center">
                        <p class="text-2xl font-bold text-blue-700" x-text="preview?.updates ?? 0"></p>
                        <p class="text-xs text-blue-600">Updates</p>
                    </div>
                    <div class="p-4 bg-gray-50 border border-gray-200 rounded-xl text-center">
                        <p class="text-2xl font-bold text-text" x-text="preview?.total ?? 0"></p>
                        <p class="text-xs text-text-secondary">Total Rows</p>
                    </div>
                </div>

                <template x-if="preview?.invalid_headers?.length > 0">
                    <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl">
                        <p class="text-xs text-amber-700">Unknown columns (will be ignored): <span x-text="preview.invalid_headers.join(', ')"></span></p>
                    </div>
                </template>

                {{-- Preview table --}}
                <template x-if="preview?.preview?.length > 0">
                    <div class="overflow-x-auto border border-gray-200 rounded-xl overscroll-x-contain">
                        <table class="min-w-[640px] w-full text-xs">
                            <thead class="bg-gray-50">
                                <tr>
                                    <template x-for="header in preview.headers" :key="header">
                                        <th class="px-3 py-2 text-left font-medium text-text-secondary" x-text="header"></th>
                                    </template>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(row, idx) in preview.preview" :key="idx">
                                    <tr class="border-t border-gray-100">
                                        <template x-for="header in preview.headers" :key="header">
                                            <td class="px-3 py-2 text-text max-w-[200px] truncate" x-text="row[header] ?? ''"></td>
                                        </template>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                        <p class="px-3 py-2 text-xs text-text-secondary bg-gray-50 border-t border-gray-100">Showing first 10 rows of <span x-text="preview.total"></span> total</p>
                    </div>
                </template>

                {{-- Confirm Import --}}
                <div class="flex flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:gap-4">
                    <button type="button" x-on:click="confirmImport()"
                            :disabled="confirming"
                            class="inline-flex items-center justify-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span x-text="confirming ? 'Importing...' : 'Confirm Import'"></span>
                    </button>
                    <button type="button" x-on:click="resetImport()" class="text-sm text-text-secondary hover:text-text transition">Cancel</button>
                </div>
            </div>

            {{-- Step 3: Result --}}
            <div x-show="importResult" x-cloak class="p-4 bg-green-50 border border-green-200 rounded-xl">
                <p class="text-sm text-green-700 font-medium" x-text="importResult"></p>
            </div>
        </div>
    </x-admin.card>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('importExport', () => ({
        importTable: new URLSearchParams(window.location.search).get('table') || '',
        uploading: false,
        confirming: false,
        uploadError: null,
        preview: null,
        tempPath: null,
        importResult: null,

        async uploadFile() {
            const file = this.$refs.csvFile?.files[0];
            if (!file) { this.uploadError = 'Please select a CSV file.'; return; }
            if (!this.importTable) { this.uploadError = 'Please select a target table.'; return; }

            this.uploading = true;
            this.uploadError = null;
            this.preview = null;
            this.importResult = null;

            const formData = new FormData();
            formData.append('table', this.importTable);
            formData.append('file', file);

            try {
                const res = await fetch('{{ route("admin.import-export.upload") }}', {
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
                    this.preview = data.preview;
                    this.tempPath = data.temp_path;
                } else {
                    this.uploadError = data.message || 'Validation failed.';
                }
            } catch (e) {
                this.uploadError = 'Network error. Please try again.';
            } finally {
                this.uploading = false;
            }
        },

        async confirmImport() {
            this.confirming = true;
            this.importResult = null;

            try {
                const res = await fetch('{{ route("admin.import-export.confirm") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ table: this.importTable, temp_path: this.tempPath }),
                });
                const data = await res.json();
                if (data.success) {
                    this.importResult = data.message;
                    this.preview = null;
                } else {
                    this.uploadError = data.message || 'Import failed.';
                }
            } catch (e) {
                this.uploadError = 'Network error. Please try again.';
            } finally {
                this.confirming = false;
            }
        },

        resetImport() {
            this.preview = null;
            this.tempPath = null;
            this.uploadError = null;
            this.importResult = null;
        },
    }));
});
</script>
@endsection
