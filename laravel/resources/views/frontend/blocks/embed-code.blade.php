@php
    $maxW = match($content['max_width'] ?? 'full') { 'lg' => 'max-w-5xl', 'md' => 'max-w-3xl', 'sm' => 'max-w-xl', default => 'max-w-7xl' };
    $rawHtml = $content['html'] ?? '';
    // Wrap inline scripts in a loader that waits for Vite modules to finish (GSAP, Alpine)
    $safeHtml = preg_replace_callback('/<script\b[^>]*>(.*?)<\/script>/is', function($m) {
        if (trim($m[1]) === '') return $m[0]; // Empty script or src="..."
        return "<script>\ndocument.addEventListener('DOMContentLoaded', () => {\n" .
               "  const _runInit = () => {\n" .
               "    if (typeof window.gsap === 'undefined') { setTimeout(_runInit, 50); return; }\n" .
               "    try { \n" . $m[1] . "\n    } catch(e) { console.error('Custom block error:', e); }\n" .
               "  };\n  _runInit();\n});\n</script>";
    }, $rawHtml);
@endphp
@if(!empty($rawHtml))
<div class="{{ $maxW }} mx-auto px-4 py-4">
    <div class="overflow-hidden embed-block-scope">
        {!! $safeHtml !!}
    </div>
    @if(!empty($content['caption']))
    <p class="mt-2 text-sm text-text-secondary text-center">{{ $content['caption'] }}</p>
    @endif
</div>
@endif
