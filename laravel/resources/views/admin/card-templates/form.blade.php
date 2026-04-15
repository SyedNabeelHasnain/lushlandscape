@extends('admin.layouts.app')
@section('title', isset($template) ? 'Edit Template' : 'Create Template')
@section('content')
    <x-admin.flash-message />
    <x-admin.page-header :title="isset($template) ? 'Edit Template: ' . $template->name : 'Create Card Template'" />
    <form method="POST"
        action="{{ isset($template) ? route('admin.card-templates.update', $template) : route('admin.card-templates.store') }}"
        data-ajax-form="true"
        data-success-message="{{ isset($template) ? 'Template updated successfully.' : 'Template created.' }}">
        @csrf
        @if(isset($template)) @method('PUT') @endif
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <div class="lg:col-span-3 space-y-6">
                <x-admin.card title="Template Details">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <x-admin.form-input name="name" label="Template Name" :value="$template->name ?? ''" required
                            tooltip="Just a helpful name for you to identify this template." />
                    </div>
                </x-admin.card>

                @if(isset($template))
                    <x-admin.card title="Template Builder Design" class="mt-6">
                        <p class="text-xs text-text-secondary mb-4">Design the layout of this card. You can use dynamic
                            variables like `{item.name}` inside heading/text bindings to render dynamic loops correctly.</p>
                        <div class="bg-gray-100 p-4 mb-4 rounded-lg flex items-start gap-3">
                            <i data-lucide="info" class="w-5 h-5 text-blue-500 mt-0.5"></i>
                            <div class="text-xs text-text-secondary">
                                <strong class="text-text block mb-1">How dynamic fields work here:</strong>
                                When this template is used inside a "Dynamic Loop" block, it will loop through the query items.
                                <ul>
                                    <li>Use <code>{item.name}</code> for the Title.</li>
                                    <li>Use <code>{item.short_description}</code> for excerpt strings.</li>
                                    <li>If querying specifically services, you can also use <code>{service.name}</code> or
                                        <code>{service.url}</code>.
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <x-admin.block-editor pageType="template_card" :pageId="$template->id" :blocks="$blocks ?? collect()"
                            :blockTypes="$blockTypes ?? []" />
                    </x-admin.card>
                    <x-admin.content-block-export type="template_card" :id="$template->id" />
                @endif
            </div>
            <div class="space-y-6">
                <x-admin.card title="Publishing">
                    <div class="space-y-5">
                        <x-admin.form-toggle name="is_active" label="Active Status" :checked="$template->is_active ?? true"
                            help="Enable or disable globally." />
                    </div>
                </x-admin.card>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <button type="submit" data-loading-label="Saving…"
                        class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($template) ? 'Update' : 'Create' }}</button>
                    <a href="{{ route('admin.card-templates.index') }}"
                        class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
                </div>
            </div>
        </div>
    </form>
@endsection
