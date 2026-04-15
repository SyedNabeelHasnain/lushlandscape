@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $links = ($content['source'] ?? 'manual') === 'settings' ? $theme->socialLinks() : ($content['links'] ?? []);
    $align = match($content['align'] ?? 'center') { 'left' => 'justify-start', default => 'justify-center' };
    $size  = match($content['size'] ?? 'md') { 'sm' => 'w-9 h-9', 'lg' => 'w-14 h-14', default => 'w-11 h-11' };
    $iconSz = match($content['size'] ?? 'md') { 'sm' => 'w-4 h-4', 'lg' => 'w-6 h-6', default => 'w-5 h-5' };
    $variant = $content['variant'] ?? 'filled';
    $platformIcons = [
        'facebook'  => 'facebook',
        'instagram' => 'instagram',
        'twitter'   => 'twitter',
        'x'         => 'twitter',
        'linkedin'  => 'linkedin',
        'youtube'   => 'youtube',
        'tiktok'    => 'music',
        'pinterest' => 'pin',
        'github'    => 'github',
        'whatsapp'  => 'message-circle',
    ];
    $linkClass = match($variant) {
        'outline' => 'bg-transparent border border-forest/20 hover:bg-forest hover:text-white',
        'minimal' => 'bg-transparent hover:bg-forest/6',
        default => 'bg-forest/6 hover:bg-forest hover:text-white',
    };
@endphp
@if(!empty($links))
<div class="max-w-7xl mx-auto px-6 lg:px-12 py-8">
    @if(!empty($content['heading']))
    <p class="text-sm font-medium text-text-secondary mb-4 {{ ($content['align'] ?? 'center') === 'center' ? 'text-center' : '' }}">{{ $content['heading'] }}</p>
    @endif
    <div class="flex flex-wrap gap-3 {{ $align }}">
        @foreach($links as $link)
        @if(!empty($link['url']))
        @php $icon = $platformIcons[strtolower($link['platform'] ?? '')] ?? 'link'; @endphp
        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer"
           class="{{ $size }} {{ $linkClass }} flex items-center justify-center text-forest transition-all duration-500"
           aria-label="{{ ucfirst($link['platform'] ?? 'Link') }}">
            <i data-lucide="{{ $icon }}" class="{{ $iconSz }}"></i>
        </a>
        @endif
        @endforeach
    </div>
</div>
@endif
