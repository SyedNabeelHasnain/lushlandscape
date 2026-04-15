@if(!empty($content['html']))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-4">
    <div class="prose prose-lg prose-forest max-w-4xl
        prose-headings:font-heading prose-headings:font-bold prose-headings:text-text prose-headings:tracking-tight
        prose-h2:text-2xl prose-h2:mt-10 prose-h2:mb-4
        prose-h3:text-xl prose-h3:mt-8 prose-h3:mb-3
        prose-p:text-text-secondary prose-p:leading-relaxed
        prose-li:text-text-secondary
        prose-a:text-forest prose-a:underline hover:prose-a:no-underline
        prose-strong:text-text prose-strong:font-semibold">{!! $content['html'] !!}</div>
</div>
@endif
