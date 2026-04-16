@php
    $activePopups = \App\Models\Popup::active()
        ->with(['image', 'form.fields'])
        ->orderBy('sort_order')
        ->get();
@endphp

@foreach($activePopups as $popup)
@php
    $excludedJson   = json_encode($popup->excluded_pages ?? []);
    $showMobile     = $popup->show_on_mobile ? 'true' : 'false';
    $showReturning  = $popup->show_to_returning ? 'true' : 'false';
    $formSlug       = $popup->form?->slug ?? '';
@endphp
<div
    x-data="lushPopup({{ $popup->id }}, {{ $popup->suppress_days }}, '{{ $popup->trigger_type }}', {{ $popup->trigger_delay_seconds }}, {{ $popup->trigger_scroll_percent }}, {!! $excludedJson !!}, {{ $showMobile }}, {{ $showReturning }})"
    x-init="init()"
    x-show="visible"
    x-cloak
    x-trap.inert.noscroll="visible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm"
    x-on:keydown.escape.window="dismiss()"
    role="dialog"
    aria-modal="true"
    aria-labelledby="popup-heading-{{ $popup->id }}"
>
    <div
        class="relative bg-white border border-stone w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-luxury"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-on:click.stop
    >
        <button x-on:click="dismiss()" type="button"
                class="absolute top-4 right-4 z-10 w-8 h-8 flex items-center justify-center bg-stone-light hover:bg-stone text-text-secondary hover:text-ink transition"
                aria-label="Close popup">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>

        @if($popup->image)
        <div class="aspect-16/7 overflow-hidden">
            <img src="{{ $popup->image->url }}"
                 alt="{{ $popup->image->default_alt_text ?? $popup->heading ?? '' }}"
                 class="w-full h-full object-cover"
                 width="640" height="280" loading="lazy">
        </div>
        @endif

        <div class="p-10 {{ $popup->image ? '' : 'pt-14' }}">
            @if($popup->heading)
            <h2 id="popup-heading-{{ $popup->id }}" class="text-xl font-heading font-bold text-ink mb-3">
                {{ $popup->heading }}
            </h2>
            @endif

            @if($popup->body_content)
            <div class="text-sm text-text-secondary leading-relaxed mb-6">
                {!! nl2br(e($popup->body_content)) !!}
            </div>
            @endif

            @if($popup->form && $formSlug)
            <div x-data="contactForm('popup-form-{{ $popup->id }}', '{{ $formSlug }}')" x-cloak>
                <form id="popup-form-{{ $popup->id }}" x-on:submit.prevent="submitForm()">
                    @foreach($popup->form->fields ?? [] as $field)
                    @php $fName = $field['name'] ?? ''; $fLabel = $field['label'] ?? $fName; $fType = $field['type'] ?? 'text'; @endphp
                    @if($fType === 'textarea')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-ink mb-1.5">{{ $fLabel }}</label>
                        <textarea name="{{ $fName }}" rows="3" class="w-full px-4 py-3 border border-stone text-sm focus:outline-none focus:border-forest transition"></textarea>
                    </div>
                    @elseif($fType === 'select' && !empty($field['options']))
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-ink mb-1.5">{{ $fLabel }}</label>
                        <select name="{{ $fName }}" class="w-full px-4 py-3 border border-stone text-sm focus:outline-none focus:border-forest transition">
                            <option value="">Select...</option>
                            @foreach($field['options'] as $opt)
                            <option value="{{ $opt }}">{{ $opt }}</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-ink mb-1.5">{{ $fLabel }}</label>
                        <input type="{{ $fType }}" id="popup-{{ $fName }}" name="{{ $fName }}" autocomplete="{{ $fType === 'email' ? 'email' : ($fType === 'tel' ? 'tel' : 'off') }}" class="w-full px-4 py-3 border border-stone text-sm focus:outline-none focus:border-forest transition">
                    </div>
                    @endif
                    @endforeach

                    <div x-show="formSuccess && formMessage" x-cloak class="mb-4 p-3 bg-forest-50 border border-forest-200 text-sm text-forest" x-text="formMessage"></div>
                    <div x-show="!formSuccess && formMessage" x-cloak class="mb-4 p-3 bg-red-50 border border-red-200 text-sm text-red-700" x-text="formMessage"></div>

                    <button type="submit" :disabled="loading"
                            class="btn-luxury btn-luxury-primary w-full disabled:opacity-60 flex items-center justify-center gap-2">
                        <i data-lucide="loader-2" x-show="loading" class="w-4 h-4 animate-spin"></i>
                        <span x-text="loading ? 'Sending...' : '{{ $popup->form->name ?? 'Submit' }}'"></span>
                    </button>
                </form>
            </div>
            @endif

            <div class="mt-5 text-center">
                <button x-on:click="dismiss()" class="text-xs text-text-secondary hover:text-ink transition underline underline-offset-2">
                    No thanks, close this
                </button>
            </div>
        </div>
    </div>
</div>
@endforeach
