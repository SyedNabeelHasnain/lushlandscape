@props(['items' => []])
@if(count($items) > 0)
<nav aria-label="Breadcrumb" class="py-4">
    <ol class="flex flex-wrap items-center gap-1.5 text-sm" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="{{ url('/') }}" itemprop="item" class="text-text-secondary hover:text-forest transition"><span itemprop="name">Home</span></a>
            <meta itemprop="position" content="1">
        </li>
        @foreach($items as $i => $item)
        <li class="text-stone" aria-hidden="true">/</li>
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            @if(isset($item['url']) && $i < count($items) - 1)
            <a href="{{ $item['url'] }}" itemprop="item" class="text-text-secondary hover:text-forest transition"><span itemprop="name">{{ $item['label'] }}</span></a>
            @else
            <span itemprop="name" class="text-ink font-medium">{{ $item['label'] }}</span>
            @if(isset($item['url']))<meta itemprop="item" content="{{ $item['url'] }}">@endif
            @endif
            <meta itemprop="position" content="{{ $i + 2 }}">
        </li>
        @endforeach
    </ol>
</nav>
@endif
