@props(['content' => ''])
@if($content)
<button type="button"
        data-tippy-content="{{ $content }}"
        class="inline-flex items-center justify-center w-4 h-4 rounded-full text-text-secondary hover:text-forest transition shrink-0 cursor-help"
        aria-label="More info">
    <svg viewBox="0 0 16 16" fill="currentColor" class="w-4 h-4" aria-hidden="true">
        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14Zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16Z"/>
        <path d="M6.649 4.696a1.352 1.352 0 0 1 2.702 0c0 .744-.5 1.165-1.08 1.665C7.672 6.899 7 7.492 7 8.5v.25a.75.75 0 0 0 1.5 0V8.5c0-.453.342-.796.82-1.194C9.94 6.787 10.85 5.98 10.85 4.696a2.852 2.852 0 0 0-5.702 0 .75.75 0 0 0 1.5 0ZM8 12a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"/>
    </svg>
</button>
@endif
