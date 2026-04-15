{{-- Section: local_intro (service_city_page context) --}}
@if(isset($page) && !empty($page->local_intro) && strlen($page->local_intro) > 100)
<section class="section-editorial bg-cream">
    <div class="max-w-7xl mx-auto px-6 lg:px-12 reveal">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-24 mb-20">
            <div class="lg:col-span-7">
                <span class="text-eyebrow text-forest mb-5 block">About This Service</span>
                <h2 class="text-h2 font-heading font-bold text-ink">{{ $page->service->name }} in {{ $page->city->name }}</h2>
            </div>
            <div class="lg:col-span-5 flex items-end">
                <p class="text-text-secondary text-body-lg">Professional service tailored to your neighbourhood.</p>
            </div>
        </div>

        @if(!empty($page->local_intro) && strlen($page->local_intro) > 100)
        <div class="prose prose-lg max-w-none text-text-secondary leading-relaxed">
            {!! nl2br(e($page->local_intro)) !!}
        </div>
        @endif
    </div>
</section>
@endif
