@props(['block', 'content', 'data', 'context' => [], 'wrapperConfig' => []])

@php
    $layout = $content['layout'] ?? 'grid';
    $columns = $content['columns'] ?? '3';
    $templateId = $content['template_id'] ?? null;
    $variableService = app(\App\Services\BlockVariableService::class);
    $isAdmin = auth()->check() && auth()->user()?->isAdmin();
    $emptyTitle = $content['empty_title'] ?? 'Nothing to display yet';
    $emptyDescription = $content['empty_description'] ?? 'This section will be updated as new items are published.';
    $emptyButtonText = $content['empty_button_text'] ?? null;
    $emptyButtonUrl = $content['empty_button_url'] ?? null;
    
    // Grid classes based on columns
    $gridCols = [
        '1' => 'grid-cols-1',
        '2' => 'grid-cols-1 md:grid-cols-2',
        '3' => 'grid-cols-1 sm:grid-cols-2 lg:grid-cols-3',
        '4' => 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4',
    ][$columns] ?? 'grid-cols-1 md:grid-cols-3';

    // Get the template blocks
    $templateBlocks = collect();
    if ($templateId) {
        $templateBlocks = \App\Services\BlockBuilderService::getBlocks('template_card', $templateId);
    }
@endphp

<div class="dynamic-loop-block max-w-7xl mx-auto px-6 lg:px-12">
    
    @if(!empty($content['heading']) || !empty($content['subtitle']))
        <div class="mb-10 max-w-4xl mx-auto text-center">
            @if(!empty($content['heading']))
                <h2 class="text-h2 font-heading font-bold tracking-tight text-ink">{{ $content['heading'] }}</h2>
            @endif
            @if(!empty($content['subtitle']))
                <p class="mt-4 text-body-lg text-text-secondary max-w-2xl mx-auto">{{ $content['subtitle'] }}</p>
            @endif
        </div>
    @endif

    @if($data->isEmpty())
        <div class="rounded-[2rem] border border-stone bg-white/70 px-8 py-12 text-center shadow-luxury">
            <p class="text-[10px] font-semibold uppercase tracking-[0.22em] text-text-secondary">Collection</p>
            <h3 class="mt-4 text-h3 font-heading font-bold text-ink">{{ $emptyTitle }}</h3>
            <p class="mx-auto mt-4 max-w-2xl text-body-lg text-text-secondary">{{ $emptyDescription }}</p>
            @if($emptyButtonText && $emptyButtonUrl)
                <div class="mt-8">
                    <a href="{{ $emptyButtonUrl }}" class="btn-luxury btn-luxury-primary">{{ $emptyButtonText }}</a>
                </div>
            @endif
        </div>
    @else
        <div class="{{ $layout === 'grid' ? 'grid gap-6 md:gap-8 ' . $gridCols : 'space-y-6' }}">
            @foreach($data as $item)
                @php
                    $itemUrl = $variableService->urlForSubject($item, 'item');
                    $heroMedia = $item->heroMedia ?? $item->heroMediaAsset ?? null;
                    $itemImageUrl = $heroMedia?->url ?? null;

                    // Merge item properties into context so {variables} can be parsed
                    // e.g. {item.title}, {item.name}, {item.featured_image_url}
                    $loopContext = array_merge($context, [
                        'item' => array_merge($item->toArray(), array_filter([
                            'url' => $itemUrl,
                            'image_url' => $itemImageUrl,
                        ])),
                    ]);
                    
                    // If the item is a specific model, we can bind specific keys directly for convenience
                    // This allows {service.name} instead of just {item.name} if it's a Service model.
                    if (class_basename($item) === 'Service') {
                        $loopContext['service'] = array_merge($item->toArray(), array_filter([
                            'url' => $variableService->urlForSubject($item, 'service'),
                        ]));
                    } elseif (class_basename($item) === 'City') {
                        $loopContext['city'] = array_merge($item->toArray(), array_filter([
                            'url' => $variableService->urlForSubject($item, 'city'),
                        ]));
                    } elseif (class_basename($item) === 'ServiceCategory') {
                        $loopContext['category'] = array_merge($item->toArray(), array_filter([
                            'url' => $variableService->urlForSubject($item, 'category'),
                        ]));
                    } elseif (class_basename($item) === 'BlogPost') {
                        $loopContext['post'] = array_merge($item->toArray(), array_filter([
                            'url' => $variableService->urlForSubject($item, 'post'),
                        ]));
                    } elseif (class_basename($item) === 'PortfolioProject') {
                        $loopContext['project'] = array_merge($item->toArray(), array_filter([
                            'url' => $variableService->urlForSubject($item, 'project'),
                        ]));
                    }
                @endphp
                
                <div class="dynamic-loop-item relative group h-full">
                    @if($templateBlocks->isNotEmpty())
                        @foreach($templateBlocks as $tBlock)
                            <x-frontend.block-renderer :block="$tBlock" :context="$loopContext" />
                        @endforeach
                        
                        {{-- Fallback click overlay if the model has a URL --}}
                        @if($itemUrl)
                            <a href="{{ $itemUrl }}" class="absolute inset-0 z-20">
                                <span class="sr-only">View Details</span>
                            </a>
                        @endif
                    @else
                        {{-- Fallback default layout if no template_card is selected or found --}}
                        <div class="h-full overflow-hidden rounded-[2rem] border border-stone bg-white transition-shadow duration-300 group-hover:shadow-luxury">
                            @if($isAdmin)
                                <div class="border-b border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold text-amber-900">
                                    Missing template card (dynamic_loop.template_id)
                                </div>
                            @endif
                            @php
                                $title = $item->name ?? $item->title ?? ('Item #'.$item->id);
                                $desc = $item->short_description ?? $item->excerpt ?? '';
                                $media = $item->heroMedia ?? $item->heroMediaAsset ?? null;
                                $asset = $media instanceof \App\Models\MediaAsset ? $media : null;
                            @endphp
                            @if($asset)
                                <x-frontend.media :asset="$asset" :alt="$title" class="w-full aspect-[4/3] object-cover" loading="lazy" />
                            @else
                                <div class="w-full aspect-[4/3] bg-cream flex items-center justify-center text-forest/60">
                                    <i data-lucide="image" class="w-8 h-8"></i>
                                </div>
                            @endif
                            <div class="p-6">
                                <div class="text-luxury-label text-text-secondary">{{ str(class_basename($item))->headline() }}</div>
                                <h3 class="mt-3 text-h4 font-heading font-bold text-ink leading-tight line-clamp-2">{{ $title }}</h3>
                                @if($desc)
                                    <p class="mt-3 text-body text-text-secondary leading-relaxed line-clamp-3">{{ $desc }}</p>
                                @endif
                                @if($itemUrl)
                                    <div class="mt-4">
                                        <a href="{{ $itemUrl }}" class="inline-flex items-center gap-2 text-sm font-semibold text-forest hover:underline">
                                            View Details
                                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
