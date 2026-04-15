@php
    $theme = app(\App\Services\ThemePresentationService::class);
    $variant = $content['variant'] ?? 'compact';
    $tone = $content['tone'] ?? 'dark';

    $items = [];
    if ($content['show_phone'] ?? true) {
        $items[] = [
            'icon' => 'phone',
            'label' => $theme->phone(),
            'url' => $theme->phone() ? 'tel:'.$theme->phoneClean() : null,
        ];
    }
    if ($content['show_email'] ?? false) {
        $items[] = [
            'icon' => 'mail',
            'label' => $theme->email(),
            'url' => $theme->email() ? 'mailto:'.$theme->email() : null,
        ];
    }
    if (($content['show_rating'] ?? true) && $theme->ratingValue() !== '') {
        $items[] = [
            'icon' => 'star',
            'label' => trim($theme->ratingValue().' '.($theme->reviewCount() ? "({$theme->reviewCount()})" : '')),
            'url' => null,
        ];
    }
    if ($content['show_hours'] ?? false) {
        $items[] = [
            'icon' => 'clock',
            'label' => trim($theme->weekdayHours().' · '.$theme->weekendHours()),
            'url' => null,
        ];
    }

    $textClass = $tone === 'light' ? 'text-ink' : 'text-white/75';
    $chipClass = $tone === 'light'
        ? 'bg-white border border-stone text-ink'
        : 'bg-white/8 border border-white/10 text-white/80';
@endphp

@if(!empty($items))
    <div class="@if($variant === 'stacked') space-y-3 @else flex flex-wrap items-center gap-3 @endif">
        @foreach($items as $item)
            @php
                $inner = '<i data-lucide="'.$item['icon'].'" class="w-3.5 h-3.5"></i><span>'.$item['label'].'</span>';
            @endphp
            @if($variant === 'chips')
                @if($item['url'])
                    <a href="{{ $item['url'] }}" class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-[11px] font-medium transition hover:-translate-y-0.5 {{ $chipClass }}">
                        {!! $inner !!}
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-[11px] font-medium {{ $chipClass }}">
                        {!! $inner !!}
                    </span>
                @endif
            @else
                @if($item['url'])
                    <a href="{{ $item['url'] }}" class="inline-flex items-center gap-2 text-[11px] font-medium transition hover:opacity-100 {{ $textClass }}">
                        {!! $inner !!}
                    </a>
                @else
                    <span class="inline-flex items-center gap-2 text-[11px] font-medium {{ $textClass }}">
                        {!! $inner !!}
                    </span>
                @endif
            @endif
        @endforeach
    </div>
@endif
