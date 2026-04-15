@extends('admin.layouts.app')
@section('title', isset($popup) ? 'Edit Popup' : 'New Popup')
@section('content')
<x-admin.flash-message />
<x-admin.page-header
    :title="isset($popup) ? 'Edit Popup: ' . $popup->name : 'New Popup'"
    :backRoute="route('admin.popups.index')"
    backLabel="Back to Popups"
/>

<form method="POST" action="{{ isset($popup) ? route('admin.popups.update', $popup) : route('admin.popups.store') }}"
      data-ajax-form="true"
      x-data="{
          triggerType: '{{ old('trigger_type', $popup->trigger_type ?? 'delay') }}',
          status: '{{ old('status', $popup->status ?? 'draft') }}'
      }">
    @csrf
    @if(isset($popup)) @method('PUT') @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left Column: Main Content --}}
        <div class="lg:col-span-2 space-y-6">

            <x-admin.card title="Popup Content">
                <div class="space-y-5">
                    <x-admin.form-input name="name" label="Internal Name (admin only)" :value="old('name', $popup->name ?? '')" required />
                    <x-admin.form-input name="heading" label="Popup Heading" :value="old('heading', $popup->heading ?? '')" help="The bold title shown at the top of the popup." />
                    <x-admin.form-textarea name="body_content" label="Body Text" :value="old('body_content', $popup->body_content ?? '')" :rows="4" help="Supporting message below the heading." />

                    {{-- Image --}}
                    <x-admin.form-media
                        name="image_media_id"
                        label="Popup Image (optional)"
                        :mediaAsset="$imageAsset ?? null"
                        help="Recommended: 600×400px or similar. Displayed above or beside content."
                    />

                    {{-- Form attachment --}}
                    <div>
                        <label for="form_id" class="block text-sm font-medium text-text mb-1.5">Attached Form (optional)</label>
                        <select id="form_id" name="form_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                            <option value="">- No form (content only) -</option>
                            @foreach($forms as $form)
                            <option value="{{ $form->id }}" {{ old('form_id', $popup->form_id ?? '') == $form->id ? 'selected' : '' }}>
                                {{ $form->name }} ({{ $form->slug }})
                            </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-text-secondary mt-1.5">Select a form to embed inside the popup (e.g. Project Inquiry, Newsletter Subscribe).</p>
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Trigger Settings">
                <div class="space-y-5">
                    {{-- Trigger type --}}
                    <div>
                        <p class="text-sm font-medium text-text mb-3">When should the popup appear?</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                            <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="triggerType === 'delay' ? 'border-forest bg-forest-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="trigger_type" value="delay" x-model="triggerType" class="mt-0.5 accent-forest">
                                <div>
                                    <p class="text-sm font-semibold text-text">Time Delay</p>
                                    <p class="text-xs text-text-secondary mt-0.5">Show after X seconds on page</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="triggerType === 'scroll_percent' ? 'border-forest bg-forest-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="trigger_type" value="scroll_percent" x-model="triggerType" class="mt-0.5 accent-forest">
                                <div>
                                    <p class="text-sm font-semibold text-text">Scroll Depth</p>
                                    <p class="text-xs text-text-secondary mt-0.5">Show after scrolling X% down</p>
                                </div>
                            </label>
                            <label class="flex items-start gap-3 p-4 rounded-xl border-2 cursor-pointer transition"
                                   :class="triggerType === 'exit_intent' ? 'border-forest bg-forest-50' : 'border-gray-200 hover:border-gray-300'">
                                <input type="radio" name="trigger_type" value="exit_intent" x-model="triggerType" class="mt-0.5 accent-forest">
                                <div>
                                    <p class="text-sm font-semibold text-text">Exit Intent</p>
                                    <p class="text-xs text-text-secondary mt-0.5">Show when cursor moves to close tab (desktop)</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div x-show="triggerType === 'delay'" x-cloak x-transition>
                        <x-admin.form-input type="number" name="trigger_delay_seconds" label="Delay (seconds)" :value="old('trigger_delay_seconds', $popup->trigger_delay_seconds ?? 5)" min="0" max="300" help="How many seconds after page load before the popup appears." />
                    </div>

                    <div x-show="triggerType === 'scroll_percent'" x-cloak x-transition>
                        <x-admin.form-input type="number" name="trigger_scroll_percent" label="Scroll Percentage" :value="old('trigger_scroll_percent', $popup->trigger_scroll_percent ?? 50)" min="0" max="100" help="What % of the page the visitor must scroll before the popup triggers." />
                    </div>
                </div>
            </x-admin.card>

            <x-admin.card title="Audience & Suppression">
                <div class="space-y-5">
                    <x-admin.form-input type="number" name="suppress_days" label="Suppress for (days)" :value="old('suppress_days', $popup->suppress_days ?? 7)" min="0" max="365" help="Once a visitor closes this popup, it won't show again for this many days (via cookie). Set 0 to always show." />

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="show_on_mobile" value="0">
                                <input type="checkbox" name="show_on_mobile" value="1"
                                       {{ old('show_on_mobile', $popup->show_on_mobile ?? true) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded accent-forest">
                                <span class="text-sm font-medium text-text">Show on Mobile Devices</span>
                            </label>
                            <p class="text-xs text-text-secondary mt-1 ml-7">Uncheck to hide popup on screens smaller than 768px.</p>
                        </div>
                        <div>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="show_to_returning" value="0">
                                <input type="checkbox" name="show_to_returning" value="1"
                                       {{ old('show_to_returning', $popup->show_to_returning ?? false) ? 'checked' : '' }}
                                       class="w-4 h-4 rounded accent-forest">
                                <span class="text-sm font-medium text-text">Show to Returning Visitors</span>
                            </label>
                            <p class="text-xs text-text-secondary mt-1 ml-7">Check to show even to visitors who have been to the site before.</p>
                        </div>
                    </div>

                    <div>
                        <label for="excluded_pages" class="block text-sm font-medium text-text mb-1.5">Excluded Page Prefixes</label>
                        <textarea id="excluded_pages" name="excluded_pages" rows="4"
                            placeholder="/thank-you&#10;/admin&#10;/contact"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm font-mono">{{ old('excluded_pages', isset($popup) ? implode("\n", $popup->excluded_pages ?? []) : '') }}</textarea>
                        <p class="text-xs text-text-secondary mt-1.5">One URL path prefix per line. Popup will not appear on any page whose path starts with these prefixes.</p>
                    </div>
                </div>
            </x-admin.card>

        </div>

        {{-- Right Column: Status & Scheduling --}}
        <div class="space-y-6">

            <x-admin.card title="Status">
                <div class="space-y-4">
                    <div>
                        <label for="status" class="block text-sm font-medium text-text mb-1.5">Status</label>
                        <select id="status" name="status" x-model="status"
                                class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                            @php $currentStatus = old('status', $popup->status ?? 'draft'); @endphp
                            <option value="draft" {{ $currentStatus === 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="active" {{ $currentStatus === 'active' ? 'selected' : '' }}>Active (Live)</option>
                            <option value="archived" {{ $currentStatus === 'archived' ? 'selected' : '' }}>Archived</option>
                        </select>
                    </div>

                    <div x-show="status === 'active'" x-cloak class="p-3 bg-green-50 rounded-xl border border-green-200">
                        <p class="text-xs text-green-700 font-medium">This popup is live and will be shown to visitors matching the trigger conditions.</p>
                    </div>

                    <x-admin.form-input type="number" name="sort_order" label="Sort Order" :value="old('sort_order', $popup->sort_order ?? 0)" help="Lower numbers appear first if multiple popups are active." />
                </div>
            </x-admin.card>

            <x-admin.card title="Date Scheduling (optional)">
                <div class="space-y-4">
                    <div>
                        <label for="starts_at" class="block text-sm font-medium text-text mb-1.5">Start Date & Time</label>
                        <input type="datetime-local" id="starts_at" name="starts_at"
                               value="{{ old('starts_at', isset($popup) && $popup->starts_at ? $popup->starts_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                        <p class="text-xs text-text-secondary mt-1">Leave blank to show immediately when Active.</p>
                    </div>
                    <div>
                        <label for="ends_at" class="block text-sm font-medium text-text mb-1.5">End Date & Time</label>
                        <input type="datetime-local" id="ends_at" name="ends_at"
                               value="{{ old('ends_at', isset($popup) && $popup->ends_at ? $popup->ends_at->format('Y-m-d\TH:i') : '') }}"
                               class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-forest/30 focus:border-forest transition text-sm">
                        <p class="text-xs text-text-secondary mt-1">Leave blank for no expiry.</p>
                    </div>
                </div>
            </x-admin.card>

            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-6 rounded-xl transition text-sm">
                    {{ isset($popup) ? 'Update Popup' : 'Create Popup' }}
                </button>
                <a href="{{ route('admin.popups.index') }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-text font-medium py-2.5 px-6 rounded-xl transition text-sm text-center">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</form>
@endsection
