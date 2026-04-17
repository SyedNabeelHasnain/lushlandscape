@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $tone = $content['tone'] ?? 'dark';
    $layout = $content['layout'] ?? 'split';
    $formId = 'theme-newsletter-'.($block->id ?? uniqid('newsletter-', false));
    $buttonText = $content['button_text'] ?? 'Subscribe';

    $shellClass = match ($tone) {
        'cream' => 'bg-cream text-ink border border-stone',
        'light' => 'bg-white text-ink border border-stone shadow-luxury',
        default => 'bg-luxury-green-deep text-white border border-white/10 shadow-luxury',
    };
    $inputClass = $tone === 'dark'
        ? 'bg-white/8 border-white/15 text-white placeholder:text-white/45'
        : 'bg-white border-stone text-ink placeholder:text-text-secondary';
    $buttonClass = $tone === 'dark' ? 'btn-luxury btn-luxury-white' : 'btn-luxury btn-luxury-primary';
    $subtextClass = $tone === 'dark' ? 'text-white/68' : 'text-text-secondary';
@endphp

<section class="rounded-[2rem] {{ $shellClass }}">
    <div class="px-6 py-8 lg:px-10 lg:py-10">
        <div class="grid gap-8 {{ $layout === 'split' ? 'lg:grid-cols-[1.2fr_1fr] lg:items-center' : '' }}">
            <div>
                @if(!empty($content['eyebrow']))
                    <p class="text-[10px] font-semibold uppercase tracking-[0.24em] {{ $tone === 'dark' ? 'text-white/45' : 'text-text-secondary' }}">
                        {{ $content['eyebrow'] }}
                    </p>
                @endif
                <h3 class="mt-3 text-2xl lg:text-3xl font-heading font-bold leading-tight">
                    {{ $content['heading'] ?: $theme->newsletterHeading() }}
                </h3>
                <p class="mt-4 text-sm leading-relaxed {{ $subtextClass }}">
                    {{ $content['description'] ?: $theme->newsletterSubtext() }}
                </p>
            </div>

            <div x-data="contactForm('{{ $formId }}', 'subscribe')" x-cloak>
                <form id="{{ $formId }}" x-on:submit.prevent="submitForm()" class="flex flex-col sm:flex-row gap-3">
                    <input type="hidden" name="source" value="theme_newsletter">
                    <label for="{{ $formId }}-email" class="sr-only">Email address</label>
                    <input type="email" id="{{ $formId }}-email" name="email" required
                        placeholder="{{ $content['placeholder'] ?? 'your@email.com' }}"
                        autocomplete="email"
                        class="field-luxury flex-1 {{ $inputClass }}">
                    <button type="submit" :disabled="formSubmitting" class="{{ $buttonClass }} whitespace-nowrap">
                        <span x-text="formSubmitting ? '...' : @js($buttonText)">{{ $buttonText }}</span>
                    </button>
                </form>
                <p x-show="formMessage" x-cloak class="mt-3 text-sm" :class="formSuccess ? 'text-green-300' : 'text-red-300'" x-text="formMessage"></p>
            </div>
        </div>
    </div>
</section>
