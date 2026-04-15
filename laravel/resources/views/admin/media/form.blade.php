@extends('admin.layouts.app')
@section('title', isset($asset) ? 'Edit Media' : 'Upload Media')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($asset) ? 'Edit: ' . $asset->internal_title : 'Upload Media'" />
<form method="POST" action="{{ isset($asset) ? route('admin.media.update', $asset) : route('admin.media.store') }}" enctype="multipart/form-data" data-ajax-form="true">
    @csrf
    @if(isset($asset)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Media Details">
                @if(!isset($asset))
                <div class="mb-5">
                    <label class="block text-sm font-medium text-text mb-1.5">File <span class="text-red-500">*</span></label>
                    <input type="file" name="file" required accept="image/*,video/mp4,video/webm" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
                    @error('file')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>
                @else
                <div class="mb-5" x-data="{ replacing: false }">
                    @if($asset->media_type === 'image' && $asset->file_size > 0)
                    <img src="{{ $asset->url }}" alt="{{ $asset->default_alt_text }}" class="max-h-64 rounded-xl" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden items-center justify-center w-full h-48 bg-gray-100 rounded-xl border-2 border-dashed border-gray-300">
                        <div class="text-center">
                            <i data-lucide="image-off" class="w-8 h-8 text-text-secondary mx-auto mb-2"></i>
                            <p class="text-xs text-text-secondary">Image file missing or broken</p>
                        </div>
                    </div>
                    @elseif($asset->file_size > 0)
                    <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl">
                        <i data-lucide="film" class="w-8 h-8 text-text-secondary"></i>
                        <span class="text-sm text-text">{{ $asset->canonical_filename }}</span>
                    </div>
                    @else
                    <div class="flex items-center justify-center w-full h-48 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                        <div class="text-center">
                            <i data-lucide="image-off" class="w-8 h-8 text-gray-300 mx-auto mb-2"></i>
                            <p class="text-xs text-text-secondary">No file uploaded yet</p>
                            @if($asset->source_url)
                            <p class="text-xs text-blue-500 mt-1">Source URL is set. Use "Download From URL" below.</p>
                            @endif
                        </div>
                    </div>
                    @endif
                    @if($asset->file_size > 0)
                    <p class="text-xs text-text-secondary mt-2">{{ $asset->canonical_filename }} ({{ number_format($asset->file_size / 1024) }} KB)</p>
                    @endif
                    <div class="mt-3">
                        <button type="button" x-on:click="replacing = !replacing" class="inline-flex items-center gap-1.5 text-xs font-medium text-forest hover:text-forest-light transition">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            <span x-text="replacing ? 'Cancel Replace' : 'Replace File'"></span>
                        </button>
                        <div x-show="replacing" x-cloak class="mt-3">
                            <input type="file" name="file" accept="image/*,video/mp4,video/webm" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl text-sm">
                            <p class="text-xs text-text-secondary mt-1">Upload a new file to replace the current one. Metadata fields below will be preserved.</p>
                        </div>
                    </div>
                </div>
                @endif
                <div class="space-y-5">
                    <x-admin.form-input name="internal_title" label="Internal Title" :value="$asset->internal_title ?? ''" required />
                    <x-admin.form-textarea name="description" label="Description" :value="$asset->description ?? ''" required :rows="3" help="What does this media show? For internal use and AI understanding." />
                    <x-admin.form-input name="default_alt_text" label="Default Alt Text" :value="$asset->default_alt_text ?? ''" help="Descriptive text for accessibility. Required for informative images." />
                    <x-admin.form-textarea name="default_caption" label="Default Caption" :value="$asset->default_caption ?? ''" :rows="2" />
                    @php
                        $focalX = isset($asset) ? ($asset->focal_point['x'] ?? null) : null;
                        $focalY = isset($asset) ? ($asset->focal_point['y'] ?? null) : null;
                    @endphp
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="focal_x" label="Focal Point X (0–1)" :value="$focalX" help="Horizontal focus point for crops and object-position. Example: 0.5 = centered." />
                        <x-admin.form-input name="focal_y" label="Focal Point Y (0–1)" :value="$focalY" help="Vertical focus point for crops and object-position. Example: 0.35 = slightly above center." />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="credit" label="Credit / Rights" :value="$asset->credit ?? 'Lush Landscape Service'" />
                        <x-admin.form-select name="image_purpose" label="Image Purpose" :options="['informative' => 'Informative', 'decorative' => 'Decorative', 'functional' => 'Functional', 'product' => 'Product', 'logo' => 'Logo']" :value="$asset->image_purpose ?? 'informative'" />
                    </div>
                    <x-admin.form-input name="location_city" label="City Relevance" :value="$asset->location_city ?? ''" help="Only if this media is genuinely local" />
                </div>
            </x-admin.card>

            {{-- Source URL Card (edit mode only) --}}
            @if(isset($asset))
            <x-admin.card title="Source URL">
                <div x-data="sourceUrlDownloader()" class="space-y-4">
                    <p class="text-sm text-text-secondary">Set an external image URL. The system will download the image from this URL and save it locally.</p>
                    <div class="flex flex-col gap-3 sm:flex-row">
                        <input type="url" name="source_url" value="{{ $asset->source_url ?? '' }}" placeholder="https://unilock.com/wp-content/uploads/2024/09/Driveway.png"
                               class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl text-sm min-w-0" x-ref="sourceUrlInput">
                        <button type="button" x-on:click="downloadFromUrl()" :disabled="downloading"
                            class="inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2.5 rounded-xl transition text-sm disabled:opacity-50">
                            <i data-lucide="download" class="w-4 h-4"></i>
                            <span x-text="downloading ? 'Downloading...' : 'Download From URL'"></span>
                        </button>
                    </div>
                    <div x-show="resultMsg" x-cloak class="text-sm" :class="resultOk ? 'text-green-600' : 'text-red-600'" x-text="resultMsg"></div>
                    @if($asset->source_url)
                    <p class="text-xs text-text-secondary break-all">Current: {{ $asset->source_url }}</p>
                    @endif
                </div>
            </x-admin.card>
            @endif
        </div>
        <div class="space-y-6">
            <x-admin.card title="Eligibility">
                <div class="space-y-5">
                    <x-admin.form-toggle name="social_preview_eligible" label="Social Preview Eligible" :checked="$asset->social_preview_eligible ?? false" />
                    <x-admin.form-toggle name="schema_eligible" label="Schema Eligible" :checked="$asset->schema_eligible ?? false" />
                </div>
            </x-admin.card>

            @if(isset($asset))
            <x-admin.card title="File Info">
                <div class="space-y-2 text-sm">
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"><span class="text-text-secondary">Status</span><span class="font-medium {{ $asset->status === 'active' ? 'text-green-600' : 'text-amber-600' }}">{{ ucfirst($asset->status) }}</span></div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"><span class="text-text-secondary">Type</span><span>{{ $asset->media_type ?? 'N/A' }}</span></div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"><span class="text-text-secondary">Dimensions</span><span>{{ $asset->width && $asset->height ? $asset->width . 'x' . $asset->height : 'N/A' }}</span></div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"><span class="text-text-secondary">File Size</span><span>{{ $asset->file_size > 0 ? number_format($asset->file_size / 1024) . ' KB' : 'No file' }}</span></div>
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"><span class="text-text-secondary">Extension</span><span>{{ strtoupper($asset->extension ?? 'N/A') }}</span></div>
                    @if($asset->checksum)
                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between"><span class="text-text-secondary">Checksum</span><span class="break-all text-xs font-mono">{{ Str::limit($asset->checksum, 12) }}</span></div>
                    @endif
                </div>
            </x-admin.card>
            @endif

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="{{ isset($asset) ? 'Saving…' : 'Uploading…' }}" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($asset) ? 'Update' : 'Upload' }}</button>
                <a href="{{ route('admin.media.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>

@if(isset($asset))
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sourceUrlDownloader', () => ({
        downloading: false,
        resultMsg: null,
        resultOk: false,

        async downloadFromUrl() {
            const url = this.$refs.sourceUrlInput?.value;
            if (!url) { this.resultMsg = 'Please enter a URL first.'; this.resultOk = false; return; }

            this.downloading = true;
            this.resultMsg = null;

            try {
                const res = await fetch('{{ route("admin.media.download-single", $asset) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                    body: JSON.stringify({ url: url }),
                });
                const data = await res.json();
                this.resultOk = data.success;
                this.resultMsg = data.message;
                if (data.success) {
                    // Save the source_url first via form, then reload
                    setTimeout(() => location.reload(), 1500);
                }
            } catch (e) {
                this.resultMsg = 'Network error.';
                this.resultOk = false;
            } finally {
                this.downloading = false;
            }
        },
    }));
});
</script>
@endif
@endsection
