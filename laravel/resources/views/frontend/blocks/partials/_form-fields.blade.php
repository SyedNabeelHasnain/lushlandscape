@php
    $fieldStyleClass = $fieldStyleClass ?? '';
    $fieldColumns = $fieldColumns ?? 'auto';
    $inputClass = trim('field-luxury '.$fieldToneClass.' '.$fieldStyleClass);
    $isInline = $variant === 'inline';
    $requiresVerification = (bool) ($form->requires_email_verification ?? false);
    $gridClass = 'grid grid-cols-1 gap-5 md:grid-cols-12';
    $messageToneClass = $tone === 'dark' ? 'text-white/75' : 'text-text-secondary';

    $widthClassFor = function ($field) use ($fieldColumns) {
        if ($fieldColumns === '1') {
            return 'md:col-span-12';
        }

        if ($fieldColumns === '2') {
            return in_array($field->type, ['textarea'], true) || $field->width === 'full'
                ? 'md:col-span-12'
                : 'md:col-span-6';
        }

        return match ($field->width) {
            'full' => 'md:col-span-12',
            'third' => 'md:col-span-4',
            'quarter' => 'md:col-span-3',
            'two-thirds' => 'md:col-span-8',
            'three-quarters' => 'md:col-span-9',
            'half', null, '' => in_array($field->type, ['textarea'], true) ? 'md:col-span-12' : 'md:col-span-6',
            default => 'md:col-span-6',
        };
    };
@endphp

<form id="{{ $formId }}" @submit.prevent="submitForm()" class="space-y-6">
    <label for="{{ $formId }}-website_url_hp" class="sr-only">Leave this field empty</label>
    <input type="text" id="{{ $formId }}-website_url_hp" name="website_url_hp" value="" class="hidden" tabindex="-1" autocomplete="off">
    @if(isset($hiddenHtml))
        {!! $hiddenHtml !!}
    @endif

    <div class="{{ $gridClass }}">
        @foreach($form->fields as $field)
            @php
                $fieldId = $formId.'-'.$field->name;
                $widthClass = $widthClassFor($field);
                $fieldLabel = $field->label;
                $fieldPlaceholder = $field->placeholder ?? '';
                
                // Determine autocomplete attribute
                $autocomplete = 'on';
                if ($field->type === 'email') {
                    $autocomplete = 'email';
                } elseif ($field->type === 'tel') {
                    $autocomplete = 'tel';
                } elseif (in_array(strtolower($field->name), ['name', 'full_name', 'fullname'])) {
                    $autocomplete = 'name';
                } elseif (in_array(strtolower($field->name), ['first_name', 'firstname'])) {
                    $autocomplete = 'given-name';
                } elseif (in_array(strtolower($field->name), ['last_name', 'lastname'])) {
                    $autocomplete = 'family-name';
                } elseif (in_array(strtolower($field->name), ['company', 'organization'])) {
                    $autocomplete = 'organization';
                } elseif (in_array(strtolower($field->name), ['address'])) {
                    $autocomplete = 'street-address';
                } elseif (in_array(strtolower($field->name), ['city'])) {
                    $autocomplete = 'address-level2';
                } elseif (in_array(strtolower($field->name), ['postal_code', 'zip', 'zip_code'])) {
                    $autocomplete = 'postal-code';
                }

                $normalizedOptions = collect($field->options ?? [])
                    ->map(fn ($option) => is_array($option)
                        ? [
                            'value' => $option['value'] ?? $option['label'] ?? '',
                            'label' => $option['label'] ?? $option['value'] ?? '',
                        ]
                        : ['value' => (string) $option, 'label' => (string) $option])
                    ->filter(fn ($option) => $option['label'] !== '');

                if ($form->slug === 'consultation') {
                    if ($field->name === 'service') {
                        $fieldLabel = 'Project Scope';
                        $normalizedOptions = collect([
                            ['value' => 'Front Entrance and Driveway', 'label' => 'Front Entrance and Driveway'],
                            ['value' => 'Rear Yard and Outdoor Living', 'label' => 'Rear Yard and Outdoor Living'],
                            ['value' => 'Full Property Transformation', 'label' => 'Full Property Transformation'],
                            ['value' => 'Structural Hardscape and Retaining', 'label' => 'Structural Hardscape and Retaining'],
                            ['value' => 'Corrective Repair and Restoration', 'label' => 'Corrective Repair and Restoration'],
                            ['value' => 'Other', 'label' => 'Other'],
                        ]);
                    }

                    if ($field->name === 'property_type') {
                        $fieldLabel = 'Property Type';
                        $normalizedOptions = collect([
                            ['value' => 'Private Residence', 'label' => 'Private Residence'],
                            ['value' => 'Estate Property', 'label' => 'Estate Property'],
                            ['value' => 'New Build Residence', 'label' => 'New Build Residence'],
                            ['value' => 'Ravine Lot', 'label' => 'Ravine Lot'],
                            ['value' => 'Waterfront Property', 'label' => 'Waterfront Property'],
                            ['value' => 'Other', 'label' => 'Other'],
                        ]);
                    }

                    if ($field->name === 'project_details') {
                        $fieldLabel = 'Project Summary';
                        $fieldPlaceholder = 'Tell us what you are planning, the timeline you are aiming for, and any material preferences.';
                    }
                }
                $isCheckboxGroup = $field->type === 'checkbox' && $normalizedOptions->count() > 1;
                $isSingleCheckbox = $field->type === 'checkbox' && !$isCheckboxGroup;
                $requiresHtmlValidation = $field->is_required && !$isCheckboxGroup;
            @endphp

            <div class="{{ $widthClass }}">
                @if(!$isSingleCheckbox)
                    <label for="{{ $fieldId }}" class="mb-2 block text-[10px] font-semibold uppercase tracking-[0.2em] {{ $labelClass }}">
                        {{ $fieldLabel }}
                        @if($field->is_required)
                            <span class="ml-1 text-accent">*</span>
                        @endif
                    </label>
                @endif

                @if($field->type === 'textarea')
                    <textarea
                        id="{{ $fieldId }}"
                        name="{{ $field->name }}"
                        placeholder="{{ $fieldPlaceholder }}"
                        class="{{ $inputClass }} min-h-[170px]"
                        autocomplete="{{ $autocomplete }}"
                        {{ $requiresHtmlValidation ? 'required' : '' }}
                    ></textarea>
                @elseif($field->type === 'select')
                    <div class="field-select-wrap {{ $tone === 'dark' ? 'text-white' : 'text-forest' }}">
                        <select
                            id="{{ $fieldId }}"
                            name="{{ $field->name }}"
                            class="{{ $inputClass }} appearance-none pr-12"
                            autocomplete="{{ $autocomplete }}"
                            {{ $requiresHtmlValidation ? 'required' : '' }}
                        >
                            <option value="">{{ $fieldPlaceholder ?: 'Select an option' }}</option>
                            @foreach($normalizedOptions as $option)
                                <option value="{{ $option['value'] }}">{{ $option['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                @elseif($field->type === 'radio')
                    <div class="space-y-3">
                        @foreach($normalizedOptions as $index => $option)
                            <label class="block">
                                <input
                                    type="radio"
                                    id="{{ $fieldId }}-{{ $index }}"
                                    name="{{ $field->name }}"
                                    value="{{ $option['value'] }}"
                                    class="field-choice-input peer sr-only"
                                    autocomplete="{{ $autocomplete }}"
                                    {{ $requiresHtmlValidation && $index === 0 ? 'required' : '' }}
                                >
                                <span class="field-choice-panel rounded-2xl" data-tone="{{ $tone }}">
                                    <span class="field-choice-indicator" aria-hidden="true"></span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold {{ $tone === 'dark' ? 'text-white' : 'text-ink' }}">{{ $option['label'] }}</span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @elseif($isCheckboxGroup)
                    <div class="space-y-3">
                        @foreach($normalizedOptions as $index => $option)
                            <label class="block">
                                <input
                                    type="checkbox"
                                    id="{{ $fieldId }}-{{ $index }}"
                                    name="{{ $field->name }}[]"
                                    value="{{ $option['value'] }}"
                                    autocomplete="{{ $autocomplete }}"
                                    class="field-choice-input peer sr-only"
                                >
                                <span class="field-choice-panel rounded-2xl" data-tone="{{ $tone }}">
                                    <span class="field-choice-indicator" aria-hidden="true"></span>
                                    <span class="min-w-0">
                                        <span class="block text-sm font-semibold {{ $tone === 'dark' ? 'text-white' : 'text-ink' }}">{{ $option['label'] }}</span>
                                    </span>
                                </span>
                            </label>
                        @endforeach
                    </div>
                @elseif($isSingleCheckbox)
                    <label class="block">
                        <input
                            type="checkbox"
                            id="{{ $fieldId }}"
                            name="{{ $field->name }}"
                            value="1"
                            autocomplete="{{ $autocomplete }}"
                            class="field-choice-input peer sr-only"
                            {{ $requiresHtmlValidation ? 'required' : '' }}
                        >
                        <span class="field-choice-panel rounded-2xl" data-tone="{{ $tone }}">
                            <span class="field-choice-indicator" aria-hidden="true"></span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold {{ $tone === 'dark' ? 'text-white' : 'text-ink' }}">{{ $field->label }}</span>
                                @if($field->help_text)
                                    <span class="mt-1 block text-xs {{ $messageToneClass }}">{{ $field->help_text }}</span>
                                @endif
                            </span>
                        </span>
                    </label>
                @elseif($field->type === 'email')
                    <div class="space-y-3">
                        <div class="relative">
                            <input
                                type="email"
                                id="{{ $fieldId }}"
                                name="{{ $field->name }}"
                        placeholder="{{ $fieldPlaceholder }}"
                                class="{{ $inputClass }}"
                                autocomplete="{{ $autocomplete }}"
                                @if($requiresVerification)
                                    x-on:blur="checkEmail($event.target.value)"
                                @endif
                                {{ $requiresHtmlValidation ? 'required' : '' }}
                            >
                            @if($requiresVerification)
                                <button
                                    type="button"
                                    x-show="showVerifyBtn" x-cloak
                                    x-on:click="sendOtp()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 rounded-full bg-forest px-3 py-1.5 text-[10px] font-semibold uppercase tracking-[0.14em] text-white"
                                >
                                    Verify
                                </button>
                                <span x-show="emailVerified" x-cloak class="absolute right-3 top-1/2 -translate-y-1/2 text-xs font-semibold text-green-600">
                                    Verified
                                </span>
                            @endif
                        </div>

                        @if($requiresVerification)
                            <div x-show="showOtpField" x-cloak class="flex flex-col gap-2 sm:flex-row">
                                <label for="{{ $formId }}-otp" class="sr-only">One-time verification code</label>
                                <input
                                    type="text"
                                    id="{{ $formId }}-otp"
                                    name="otp_code"
                                    x-model="otpCode"
                                    maxlength="6"
                                    placeholder="Enter 6-digit code"
                                    aria-label="One-time verification code"
                                    autocomplete="one-time-code"
                                    class="{{ $inputClass }} flex-1"
                                >
                                <button type="button" x-on:click="verifyOtp()" class="btn-luxury btn-luxury-primary whitespace-nowrap">
                                    Confirm
                                </button>
                            </div>
                            <p x-show="otpMessage" x-cloak class="text-xs {{ $messageToneClass }}" x-text="otpMessage"></p>
                        @endif
                    </div>
                @else
                    <input
                        type="{{ $field->type }}"
                        id="{{ $fieldId }}"
                        name="{{ $field->name }}"
                        placeholder="{{ $fieldPlaceholder }}"
                        autocomplete="{{ $autocomplete }}"
                        class="{{ $inputClass }}"
                        {{ $requiresHtmlValidation ? 'required' : '' }}
                    >
                @endif

                @if(!$isSingleCheckbox && $field->help_text)
                    <p class="mt-2 text-xs {{ $messageToneClass }}">{{ $field->help_text }}</p>
                @endif
            </div>
        @endforeach
    </div>

    <div class="space-y-3">
        <button
            type="submit"
            x-bind:disabled="formSubmitting{{ $requiresVerification ? ' || !emailVerified' : '' }}"
            class="{{ $buttonClass }} {{ $isInline ? '' : 'w-full justify-center' }}"
        >
            <span x-text="formSubmitting ? 'Submitting...' : @js($submitText)">{{ $submitText }}</span>
        </button>

        @if($requiresVerification)
            <p class="text-xs {{ $messageToneClass }}">Please verify your email before submitting this form.</p>
        @endif

        <p
            x-show="formMessage"
            x-cloak
            class="text-sm"
            :class="formSuccess ? 'text-green-500' : '{{ $tone === 'dark' ? 'text-red-200' : 'text-red-500' }}'"
            x-text="formMessage"
        ></p>
    </div>
</form>
