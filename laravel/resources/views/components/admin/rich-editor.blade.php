@props(['name', 'label', 'value' => '', 'required' => false, 'help' => '', 'tooltip' => ''])
<div>
    <label class="flex items-center gap-1.5 text-sm font-medium text-text mb-1.5">
        <span>{{ $label }}@if($required)<span class="text-red-500 ml-0.5">*</span>@endif</span>
        <x-admin.tooltip :content="$tooltip" />
    </label>
    <div x-data="richEditor('{{ $name }}', {{ json_encode(old($name, $value)) }})" class="border border-gray-200 rounded-xl overflow-hidden focus-within:ring-2 focus-within:ring-forest/30 focus-within:border-forest transition @error($name) border-red-300 @enderror">
        {{-- Toolbar --}}
        <div class="flex flex-wrap items-center gap-0.5 px-2 py-1.5 bg-gray-50 border-b border-gray-200">
            {{-- Text formatting --}}
            <button type="button" x-on:click="toggleBold()" :class="isActive('bold') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs font-bold transition" title="Bold">B</button>
            <button type="button" x-on:click="toggleItalic()" :class="isActive('italic') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs italic transition" title="Italic">I</button>
            <button type="button" x-on:click="toggleUnderline()" :class="isActive('underline') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs underline transition" title="Underline">U</button>
            <button type="button" x-on:click="toggleStrike()" :class="isActive('strike') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg text-xs line-through transition" title="Strikethrough">S</button>

            <span class="w-px h-5 bg-gray-300 mx-1"></span>

            {{-- Headings --}}
            <button type="button" x-on:click="toggleH2()" :class="isActive('heading', {level:2}) ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="h-7 px-1.5 flex items-center justify-center rounded-lg text-xs font-semibold transition" title="Heading 2">H2</button>
            <button type="button" x-on:click="toggleH3()" :class="isActive('heading', {level:3}) ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="h-7 px-1.5 flex items-center justify-center rounded-lg text-xs font-semibold transition" title="Heading 3">H3</button>
            <button type="button" x-on:click="toggleH4()" :class="isActive('heading', {level:4}) ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="h-7 px-1.5 flex items-center justify-center rounded-lg text-xs font-semibold transition" title="Heading 4">H4</button>

            <span class="w-px h-5 bg-gray-300 mx-1"></span>

            {{-- Lists --}}
            <button type="button" x-on:click="toggleBulletList()" :class="isActive('bulletList') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Bullet List"><i data-lucide="list" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="toggleOrderedList()" :class="isActive('orderedList') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Numbered List"><i data-lucide="list-ordered" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="toggleBlockquote()" :class="isActive('blockquote') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Blockquote"><i data-lucide="quote" class="w-3.5 h-3.5"></i></button>

            <span class="w-px h-5 bg-gray-300 mx-1"></span>

            {{-- Alignment --}}
            <button type="button" x-on:click="alignLeft()" :class="isActive({textAlign:'left'}) ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Align Left"><i data-lucide="align-left" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="alignCenter()" :class="isActive({textAlign:'center'}) ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Align Center"><i data-lucide="align-center" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="alignRight()" :class="isActive({textAlign:'right'}) ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Align Right"><i data-lucide="align-right" class="w-3.5 h-3.5"></i></button>

            <span class="w-px h-5 bg-gray-300 mx-1"></span>

            {{-- Insert --}}
            <button type="button" x-on:click="setLink()" :class="isActive('link') ? 'bg-forest text-white' : 'text-gray-600 hover:bg-gray-200'" class="w-7 h-7 flex items-center justify-center rounded-lg transition" title="Link"><i data-lucide="link" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="insertImage()" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-200 transition" title="Image"><i data-lucide="image" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="setHorizontalRule()" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-200 transition" title="Horizontal Rule"><i data-lucide="minus" class="w-3.5 h-3.5"></i></button>

            <span class="w-px h-5 bg-gray-300 mx-1"></span>

            {{-- Undo/Redo --}}
            <button type="button" x-on:click="undo()" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-200 transition" title="Undo"><i data-lucide="undo-2" class="w-3.5 h-3.5"></i></button>
            <button type="button" x-on:click="redo()" class="w-7 h-7 flex items-center justify-center rounded-lg text-gray-600 hover:bg-gray-200 transition" title="Redo"><i data-lucide="redo-2" class="w-3.5 h-3.5"></i></button>
        </div>

        {{-- Editor content area --}}
        <div x-ref="editorContent" class="bg-white"></div>

        {{-- Hidden input syncs content to form --}}
        <input type="hidden" name="{{ $name }}" :value="content">
    </div>
    @if($help)<p class="text-xs text-text-secondary mt-1">{{ $help }}</p>@endif
    @error($name)<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
</div>
