{{-- Block: form_block --}}
@php
    $formSlug = $content['form_slug'] ?? 'consultation';
    $showTitle = $content['show_title'] ?? true;
    $form = \App\Models\Form::where('slug', $formSlug)->where('status', 'active')->with('fields')->first();
    $theme = app(\App\Services\ThemePresentationService::class);
    $variant = $content['variant'] ?? 'minimal';
    $tone = $content['tone'] ?? 'light';
    $panelStyle = $content['panel_style'] ?? 'luxury';
    $fieldStyle = $content['field_style'] ?? 'luxury';
    $fieldColumns = $content['field_columns'] ?? 'auto';
    $formId = 'content-form-'.($block->id ?? uniqid('form-', false));
    $heading = $content['heading'] ?? ($showTitle && $form ? $form->name : '');
    $description = $content['description'] ?? '';
    $contactPhone = filled($content['contact_phone'] ?? null) ? $content['contact_phone'] : $theme->phone();
    $contactEmail = filled($content['contact_email'] ?? null) ? $content['contact_email'] : $theme->email();
    $contactAddress = filled($content['contact_address'] ?? null) ? $content['contact_address'] : $theme->address();
    $submitText = $content['submit_text'] ?? 'Submit';

    $shellClass = match ($panelStyle) {
        'glass' => $tone === 'dark'
            ? 'panel-luxury-glass border-white/14 bg-[rgba(21,56,35,0.78)] text-white'
            : 'panel-luxury-glass text-ink',
        'minimal' => $tone === 'dark'
            ? 'panel-luxury-minimal border-white/12 text-white'
            : 'panel-luxury-minimal border-stone text-ink',
        default => match ($tone) {
            'dark' => 'panel-luxury bg-luxury-green-deep border border-white/10 text-white',
            'cream' => 'panel-luxury bg-cream border border-stone text-ink',
            default => 'panel-luxury bg-white border border-stone shadow-luxury text-ink',
        },
    };
    $subClass = $tone === 'dark' ? 'text-white/72' : 'text-text-secondary';
    $labelClass = $tone === 'dark' ? 'text-white/55' : 'text-text-secondary';
    $fieldToneClass = $tone === 'dark'
        ? 'bg-white/8 border-white/15 text-white placeholder:text-white/45'
        : ($tone === 'cream'
            ? 'bg-cream-light border-stone text-ink placeholder:text-text-secondary'
            : 'bg-white border-stone text-ink placeholder:text-text-secondary');
    $fieldStyleClass = match ($fieldStyle) {
        'soft' => 'field-luxury-soft',
        'underline' => 'field-luxury-underline',
        default => '',
    };
    $buttonClass = $tone === 'dark' ? 'btn-luxury btn-luxury-white' : 'btn-luxury btn-luxury-primary';

    $splitShellClass = match ($panelStyle) {
        'glass' => 'overflow-hidden rounded-[2rem] border border-white/10 shadow-luxury',
        'minimal' => 'overflow-hidden border border-stone/60',
        default => 'overflow-hidden rounded-[2rem] border border-stone shadow-luxury',
    };
    $splitInfoPanelClass = match ($tone) {
        'cream' => 'bg-cream-warm text-ink',
        default => 'bg-luxury-green-deep text-white',
    };
    $splitInfoSubClass = $tone === 'cream' ? 'text-text-secondary' : 'text-white/78';
    $splitInfoLabelClass = $tone === 'cream' ? 'text-text-secondary' : 'text-white/70';
    $splitFormPanelClass = $panelStyle === 'glass'
        ? 'bg-white/94 backdrop-blur-xl'
        : 'bg-white';
@endphp

@if($form)
    <div x-data="contactForm('{{ $formId }}', '{{ $form->slug }}')" class="{{ in_array($variant, ['panel', 'split'], true) ? 'p-0' : 'max-w-3xl mx-auto' }}">
        @if($variant === 'split')
            <div class="{{ $splitShellClass }} grid gap-0 lg:grid-cols-[0.9fr_1.1fr] lg:items-stretch">
                <div class="{{ $splitInfoPanelClass }} relative p-8 lg:p-12">
                    @if(!empty($content['eyebrow']))
                        <p class="mb-4 text-[10px] font-semibold uppercase tracking-[0.22em] {{ $splitInfoLabelClass }}">{{ $content['eyebrow'] }}</p>
                    @endif
                    @if(!empty($heading))
                        <h3 class="text-h2 font-heading font-bold {{ $tone === 'cream' ? 'text-ink' : 'text-white' }}">{{ $heading }}</h3>
                    @endif
                    @if(!empty($description))
                        <p class="mt-4 leading-relaxed {{ $splitInfoSubClass }}">{{ $description }}</p>
                    @endif

                    @if($content['show_contact_details'] ?? false)
                        <div class="mt-8 space-y-4">
                            @if($contactPhone)
                                <a href="tel:{{ preg_replace('/[^+\d]/', '', $contactPhone) }}" class="flex items-center gap-3 {{ $tone === 'cream' ? 'text-ink' : 'text-white/80' }}">
                                    <i data-lucide="phone" class="w-4 h-4 text-accent"></i>
                                    <span>{{ $contactPhone }}</span>
                                </a>
                            @endif
                            @if($contactEmail)
                                <a href="mailto:{{ $contactEmail }}" class="flex items-center gap-3 {{ $tone === 'cream' ? 'text-ink' : 'text-white/80' }}">
                                    <i data-lucide="mail" class="w-4 h-4 text-accent"></i>
                                    <span>{{ $contactEmail }}</span>
                                </a>
                            @endif
                            @if($contactAddress)
                                <div class="flex items-start gap-3 {{ $splitInfoSubClass }}">
                                    <i data-lucide="map-pin" class="w-4 h-4 text-accent mt-0.5"></i>
                                    <span class="whitespace-pre-line">{{ $contactAddress }}</span>
                                </div>
                            @endif
                            @if(!empty($content['support_cta_text']) && !empty($content['support_cta_url']))
                                <a href="{{ $content['support_cta_url'] }}" class="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.18em] font-semibold {{ $tone === 'cream' ? 'text-forest' : 'text-white' }}">
                                    {{ $content['support_cta_text'] }}
                                    <span class="w-4 h-px bg-current/40"></span>
                                </a>
                            @endif
                        </div>
                    @endif
                </div>

                <div class="{{ $splitFormPanelClass }} p-8 lg:p-12">
                    @include('frontend.blocks.partials._form-fields', [
                        'form' => $form,
                        'formId' => $formId,
                        'variant' => $variant,
                        'tone' => 'light',
                        'labelClass' => 'text-text-secondary',
                        'fieldToneClass' => 'bg-white border-stone text-ink placeholder:text-text-secondary',
                        'fieldStyleClass' => $fieldStyleClass,
                        'fieldColumns' => $fieldColumns,
                        'buttonClass' => 'btn-luxury btn-luxury-primary',
                        'submitText' => $submitText,
                    ])
                </div>
            </div>
        @else
            <div class="{{ in_array($variant, ['panel'], true) ? 'rounded-[2rem] '.$shellClass.' p-6 lg:p-10' : '' }}">
            @if(!empty($content['eyebrow']) || !empty($heading) || !empty($description))
                <div class="mb-8">
                    @if(!empty($content['eyebrow']))
                        <p class="mb-4 text-[10px] font-semibold uppercase tracking-[0.22em] {{ $labelClass }}">{{ $content['eyebrow'] }}</p>
                    @endif
                    @if(!empty($heading))
                        <h3 class="{{ $variant === 'panel' ? 'text-h2' : 'text-h3' }} font-heading font-bold {{ $tone === 'dark' ? 'text-white' : 'text-ink' }}">{{ $heading }}</h3>
                    @endif
                    @if(!empty($description))
                        <p class="mt-4 leading-relaxed {{ $subClass }}">{{ $description }}</p>
                    @endif
                </div>
            @endif

            @include('frontend.blocks.partials._form-fields', [
                'form' => $form,
                'formId' => $formId,
                'variant' => $variant,
                'tone' => $tone,
                'labelClass' => $labelClass,
                'fieldToneClass' => $fieldToneClass,
                'fieldStyleClass' => $fieldStyleClass,
                'fieldColumns' => $fieldColumns,
                'buttonClass' => $buttonClass,
                'submitText' => $submitText,
            ])
            </div>
        @endif
    </div>
@endif
