<div class="bg-forest px-6 lg:px-12 py-20 lg:py-24 relative overflow-hidden" style="background-color: #153823;">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-20 items-center">
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.25em] text-white/60 mb-4">{{ $content['eyebrow'] ?? 'Stay Updated' }}</p>
                <h3 class="text-white font-heading text-3xl lg:text-4xl font-bold leading-tight">{{ $content['heading'] ?? 'Landscape Insights & Project Planning' }}</h3>
                <p class="text-sm text-white/60 mt-4 leading-relaxed max-w-md">{{ $content['subtext'] ?? 'Join 2,000+ Our Region homeowners getting our free monthly newsletter.' }}</p>
            </div>
            <div x-data="contactForm('newsletter-form', 'subscribe')" x-cloak>
                <form id="newsletter-form" x-on:submit.prevent="submitForm()" class="flex flex-col sm:flex-row gap-0">
                    <input type="hidden" name="source" value="footer_newsletter">
                    <label for="newsletter-email" class="sr-only">Email address for newsletter</label>
                    <input type="email" id="newsletter-email" name="email" autocomplete="email" required
                        placeholder="your@email.com" aria-label="Email address for newsletter"
                        class="flex-1 px-6 py-4 bg-white/6 border border-white/10 text-white placeholder-white/30 text-sm focus:outline-none focus:border-white/25 transition">
                    <button type="submit" :disabled="formSubmitting"
                        class="shrink-0 bg-white hover:bg-white/90 disabled:opacity-60 text-forest font-semibold px-8 py-4 text-[11px] tracking-[0.1em] uppercase transition flex items-center justify-center gap-2">
                        <i data-lucide="loader-2" x-show="formSubmitting" x-cloak class="w-4 h-4 animate-spin"></i>
                        <span x-text="formSubmitting ? '...' : 'Subscribe'">Subscribe</span>
                    </button>
                </form>
                <div x-show="formSuccess && formMessage" x-cloak class="mt-3 text-sm text-green-300 text-left"
                    x-text="formMessage"></div>
                <div x-show="!formSuccess && formMessage" x-cloak class="mt-3 text-sm text-red-300 text-left"
                    x-text="formMessage"></div>
            </div>
        </div>
    </div>
</div>