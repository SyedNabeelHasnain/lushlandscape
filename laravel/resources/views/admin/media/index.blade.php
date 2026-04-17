@extends('admin.layouts.app')
@section('title', 'Media Library')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Media Library" subtitle="Upload and manage images and videos" :createRoute="route('admin.media.create')" createLabel="Upload Media">
    <x-admin.import-export-buttons table="media_assets" />
</x-admin.page-header>

{{-- Stats & Actions Bar --}}
<div x-data="mediaActions()" class="space-y-5 mb-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-3">
        <div class="bg-white rounded-xl border border-gray-100 p-4 text-center">
            <p class="text-2xl font-bold text-text">{{ $stats['total'] }}</p>
            <p class="text-xs text-text-secondary">Total Assets</p>
        </div>
        <div class="bg-white rounded-xl border border-green-100 p-4 text-center">
            <p class="text-2xl font-bold text-green-700">{{ $stats['with_files'] }}</p>
            <p class="text-xs text-green-600">With Files</p>
        </div>
        <div class="bg-white rounded-xl border border-blue-100 p-4 text-center">
            <p class="text-2xl font-bold text-blue-700">{{ $stats['with_urls'] }}</p>
            <p class="text-xs text-blue-600">With URLs</p>
        </div>
        <div class="bg-white rounded-xl border border-amber-100 p-4 text-center">
            <p class="text-2xl font-bold text-amber-700">{{ $stats['pending'] }}</p>
            <p class="text-xs text-amber-600">Ready to Download</p>
        </div>
        <div class="bg-white rounded-xl border border-red-100 p-4 text-center">
            <p class="text-2xl font-bold text-red-700">{{ $stats['no_url'] }}</p>
            <p class="text-xs text-red-600">No URL Set</p>
        </div>
    </div>

    {{-- Action Buttons --}}
    <div class="flex flex-wrap gap-3">
        @if($stats['pending'] > 0)
        <button type="button" x-on:click="downloadAll()" :disabled="running"
            class="inline-flex items-center gap-2 bg-forest hover:bg-forest-light text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
            <i data-lucide="download" class="w-4 h-4"></i>
            <span x-text="running && action === 'download' ? 'Downloading...' : 'Download All Images ({{ $stats['pending'] }})'"></span>
        </button>
        @endif

        @if($stats['no_url'] > 0)
        <button type="button" x-on:click="populateUrls()" :disabled="running"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-5 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
            <i data-lucide="search" class="w-4 h-4"></i>
            <span x-text="running && action === 'populate' ? 'Finding URLs...' : 'Populate URLs via API ({{ $stats['no_url'] }})'"></span>
        </button>
        @endif
    </div>

    {{-- Progress Panel --}}
    <div x-show="running" x-cloak class="bg-white rounded-xl border border-gray-100 p-5 space-y-4">
        <div>
            <div class="flex justify-between text-sm mb-2">
                <span class="font-medium text-text"><span x-text="done"></span> / <span x-text="total"></span></span>
                <span class="text-text-secondary" x-text="percent + '%'"></span>
            </div>
            <div class="w-full h-3 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-forest rounded-full transition-all duration-300" :style="'width:' + percent + '%'"></div>
            </div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="p-3 bg-green-50 border border-green-200 rounded-xl text-center">
                <p class="text-lg font-bold text-green-700" x-text="successCount"></p>
                <p class="text-xs text-green-600">Success</p>
            </div>
            <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-center">
                <p class="text-lg font-bold text-amber-700" x-text="skippedCount"></p>
                <p class="text-xs text-amber-600">Skipped</p>
            </div>
            <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-center">
                <p class="text-lg font-bold text-red-700" x-text="failedCount"></p>
                <p class="text-xs text-red-600">Failed</p>
            </div>
        </div>
        <div class="max-h-48 overflow-y-auto border border-gray-200 rounded-xl text-sm">
            <template x-for="log in logs.slice(-30)" :key="log.idx">
                <div class="flex items-center gap-3 px-4 py-1.5 border-b border-gray-50 last:border-0"
                     :class="{'bg-green-50/50': log.s === 'success', 'bg-amber-50/50': log.s === 'skipped', 'bg-red-50/50': log.s === 'failed'}">
                    <span class="w-4 h-4 shrink-0" :class="{'text-green-500': log.s === 'success', 'text-amber-500': log.s === 'skipped', 'text-red-500': log.s === 'failed'}">
                        <template x-if="log.s === 'success'"><i data-lucide="check-circle" class="w-4 h-4"></i></template>
                        <template x-if="log.s === 'skipped'"><i data-lucide="skip-forward" class="w-4 h-4"></i></template>
                        <template x-if="log.s === 'failed'"><i data-lucide="x-circle" class="w-4 h-4"></i></template>
                    </span>
                    <span class="flex-1 truncate text-xs" x-text="log.t"></span>
                    <span class="text-xs text-text-secondary shrink-0" x-text="log.m"></span>
                </div>
            </template>
        </div>
    </div>

    {{-- Complete Message --}}
    <div x-show="completed" x-cloak class="p-4 bg-green-50 border border-green-200 rounded-xl">
        <p class="text-sm text-green-700 font-medium">
            Operation complete. <span x-text="successCount"></span> succeeded, <span x-text="skippedCount"></span> skipped, <span x-text="failedCount"></span> failed.
            <a href="" class="underline ml-2">Refresh page</a>
        </p>
    </div>
</div>

{{-- Filters --}}
<div class="mb-6">
    <form class="flex flex-col gap-3 sm:flex-row sm:flex-wrap" method="GET" x-data="{}">
        <label for="media-search" class="sr-only">Search media</label>
        <input type="text" id="media-search" name="search" value="{{ request('search') }}" placeholder="Search media..." class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm sm:w-auto sm:min-w-[220px]">
        <label for="media-type" class="sr-only">Filter by type</label>
        <select id="media-type" name="type" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white sm:w-auto" x-on:change="$el.form.submit()">
            <option value="">All Types</option>
            <option value="image" {{ request('type') === 'image' ? 'selected' : '' }}>Images</option>
            <option value="video" {{ request('type') === 'video' ? 'selected' : '' }}>Videos</option>
        </select>
        <select name="status" class="w-full px-3 py-2 border border-gray-200 rounded-xl text-sm bg-white sm:w-auto" x-on:change="$el.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
            <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-forest text-white rounded-xl text-sm sm:w-auto">Search</button>
    </form>
</div>

{{-- Media Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
    @forelse($media as $item)
    <div class="bg-white rounded-xl border border-gray-100 overflow-hidden group relative">
        <div class="aspect-square bg-gray-100 relative">
            @if($item->media_type === 'image' && $item->file_size > 0)
            <img src="{{ $item->url }}" alt="{{ $item->default_alt_text }}" class="w-full h-full object-cover" loading="lazy">
            @elseif($item->media_type === 'video' && $item->file_size > 0)
            <div class="w-full h-full flex items-center justify-center"><i data-lucide="film" class="w-8 h-8 text-text-secondary"></i></div>
            @else
            <div class="w-full h-full flex items-center justify-center bg-gray-50">
                <div class="text-center px-2">
                    <i data-lucide="image-off" class="w-6 h-6 text-gray-300 mx-auto mb-1"></i>
                    <p class="text-[10px] text-gray-400">{{ $item->source_url ? 'Ready' : 'No URL' }}</p>
                </div>
            </div>
            @endif
            @if($item->status === 'draft')
            <span class="absolute top-1.5 left-1.5 px-1.5 py-0.5 bg-amber-500 text-white text-[10px] font-medium rounded">Draft</span>
            @endif
            <div class="absolute inset-0 flex items-center justify-center gap-2 bg-black/50 opacity-100 transition sm:opacity-0 sm:group-hover:opacity-100">
                <a href="{{ route('admin.media.edit', $item) }}" class="p-2 bg-white rounded-lg text-sm" aria-label="Edit media"><i data-lucide="edit" class="w-4 h-4"></i></a>
            </div>
        </div>
        <div class="p-3">
            <p class="text-xs font-medium text-text truncate">{{ $item->internal_title }}</p>
            <p class="text-xs text-text-secondary">
                @if($item->file_size > 0)
                {{ strtoupper($item->extension) }} {{ $item->width }}x{{ $item->height }}
                @else
                <span class="text-amber-500">No file</span>
                @endif
            </p>
        </div>
    </div>
    @empty
    <div class="col-span-full py-12 text-center text-sm text-text-secondary">No media found.</div>
    @endforelse
</div>
<div class="mt-6">{{ $media->links() }}</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('mediaActions', () => ({
        running: false,
        completed: false,
        action: '',
        total: 0,
        done: 0,
        successCount: 0,
        skippedCount: 0,
        failedCount: 0,
        logs: [],

        get percent() {
            return this.total > 0 ? Math.round((this.done / this.total) * 100) : 0;
        },

        downloadAll() {
            this._runSSE('{{ route("admin.media.download-all") }}', 'download');
        },

        populateUrls() {
            this._runSSE('{{ route("admin.media.populate-urls") }}', 'populate');
        },

        _runSSE(url, actionName) {
            this.running = true;
            this.completed = false;
            this.action = actionName;
            this.done = 0;
            this.total = 0;
            this.successCount = 0;
            this.skippedCount = 0;
            this.failedCount = 0;
            this.logs = [];

            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'text/event-stream',
                },
            }).then(response => {
                // Check if JSON response (no pending items)
                const ct = response.headers.get('content-type') || '';
                if (ct.includes('application/json')) {
                    return response.json().then(data => {
                        this.running = false;
                        this.completed = true;
                    });
                }

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let buffer = '';

                const read = () => {
                    reader.read().then(({ done, value }) => {
                        if (done) {
                            this.running = false;
                            this.completed = true;
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
                                    if (eventType === 'start') {
                                        this.total = data.total;
                                    } else if (eventType === 'progress') {
                                        this.done = data.index + 1;
                                        this.logs.push({ idx: data.index, s: data.status, t: data.title, m: data.message });
                                        if (data.status === 'success') this.successCount++;
                                        else if (data.status === 'skipped') this.skippedCount++;
                                        else this.failedCount++;
                                    } else if (eventType === 'complete') {
                                        this.running = false;
                                        this.completed = true;
                                    }
                                } catch (e) {}
                            }
                        }
                        read();
                    });
                };
                read();
            }).catch(err => {
                this.running = false;
                alert('Connection error: ' + err.message);
            });
        },
    }));
});
</script>
@endsection
