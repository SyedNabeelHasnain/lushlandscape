@php
    $bg    = $content['bg'] ?? 'cream';
    $layout = $content['layout'] ?? 'inline';
    $buttonText = $content['button_text'] ?? 'Subscribe';
    $bgCls = match($bg) { 'forest' => 'bg-luxury-green-deep text-white', 'white' => 'bg-white', default => 'bg-cream' };
    $textClr = $bg === 'forest' ? 'text-white' : 'text-text';
    $subClr  = $bg === 'forest' ? 'text-white/75' : 'text-text-secondary';
    $btnCls  = $bg === 'forest' ? 'btn-luxury btn-luxury-white' : 'btn-luxury btn-luxury-primary';
    $inputBg = $bg === 'forest' ? 'bg-white/10 border-white/20 text-white placeholder:text-white/50' : 'bg-white border-stone text-text';
    $formId = 'newsletter-cta-'.($block->id ?? uniqid('newsletter-cta-', false));
@endphp
@if(!empty($content['heading']))
<section class="{{ $bgCls }} py-12 md:py-16">
    <div class="max-w-2xl mx-auto px-4 text-center">
        @if(!empty($content['eyebrow']))
        <p class="text-[10px] font-semibold uppercase tracking-[0.2em] {{ $bg === 'forest' ? 'text-white/45' : 'text-text-secondary' }} mb-4">{{ $content['eyebrow'] }}</p>
        @endif
        <h2 class="text-2xl md:text-3xl font-heading font-bold {{ $textClr }} mb-3">{{ $content['heading'] }}</h2>
        @if(!empty($content['description']))
        <p class="{{ $subClr }} mb-6">{{ $content['description'] }}</p>
        @endif
        <div x-data="contactForm('{{ $formId }}', 'subscribe')" x-cloak>
        <form id="{{ $formId }}" x-on:submit.prevent="submitForm()" class="{{ $layout === 'stacked' ? 'max-w-md mx-auto space-y-3' : 'flex flex-col sm:flex-row gap-3 max-w-md mx-auto' }}">
            <input type="hidden" name="source" value="newsletter_cta">
            <label for="{{ $formId }}-email" class="sr-only">Email address</label>
            <input type="email" id="{{ $formId }}-email" name="email" required
                   placeholder="{{ $content['placeholder'] ?? 'Enter your email' }}"
                   autocomplete="email"
                   class="field-luxury flex-1 {{ $inputBg }}">
            <button type="submit" class="{{ $btnCls }} whitespace-nowrap">
                <span x-text="formSubmitting ? '...' : @js($buttonText)">{{ $buttonText }}</span>
            </button>
        </form>
        <p x-show="formMessage" x-cloak class="mt-3 text-sm {{ $bg === 'forest' ? 'text-white/70' : 'text-text-secondary' }}" x-text="formMessage"></p>
        </div>
    </div>
</section>
@endif
