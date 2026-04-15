@props(['table'])
@php $tables = \App\Services\ExportService::getExportableTables(); @endphp
@if(isset($tables[$table]))
<div class="flex items-center gap-2">
    <form method="POST" action="{{ route('admin.import-export.export') }}" class="inline">
        @csrf
        <input type="hidden" name="table" value="{{ $table }}">
        <button type="submit" class="inline-flex items-center gap-1.5 text-sm font-medium text-text-secondary hover:text-forest transition px-3 py-1.5 rounded-lg hover:bg-forest-50" data-tippy-content="Export {{ $tables[$table] }} as CSV">
            <i data-lucide="download" class="w-3.5 h-3.5"></i>Export
        </button>
    </form>
    <a href="{{ route('admin.import-export.index', ['table' => $table]) }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-text-secondary hover:text-forest transition px-3 py-1.5 rounded-lg hover:bg-forest-50" data-tippy-content="Import {{ $tables[$table] }} from CSV">
        <i data-lucide="upload" class="w-3.5 h-3.5"></i>Import
    </a>
</div>
@endif
