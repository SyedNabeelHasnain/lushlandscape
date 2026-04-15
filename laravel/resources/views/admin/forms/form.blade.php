@extends('admin.layouts.app')
@section('title', isset($form) ? 'Edit Form' : 'Create Form')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($form) ? 'Edit: ' . $form->name : 'Create Form'" />
<form method="POST" action="{{ isset($form) ? route('admin.forms.update', $form) : route('admin.forms.store') }}" data-ajax-form="true">
    @csrf
    @if(isset($form)) @method('PUT') @endif
    <x-admin.card title="Form Settings">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-input name="name" label="Form Name" :value="$form->name ?? ''" required />
            <x-admin.form-input name="slug" label="Slug" :value="$form->slug ?? ''" required />
            <x-admin.form-select name="form_type" label="Form Type" :options="['contact'=>'Contact','quote'=>'Quote/Estimate','subscriber'=>'Subscriber','booking'=>'Booking']" :value="$form->form_type ?? ''" required />
            <x-admin.form-select name="status" label="Status" :options="['active'=>'Active','inactive'=>'Inactive']" :value="$form->status ?? 'active'" required />
        </div>
        <div class="mt-5"><x-admin.form-textarea name="description" label="Description" :value="$form->description ?? ''" :rows="2" /></div>
        <div class="mt-5"><x-admin.form-textarea name="success_message" label="Success Message" :value="$form->success_message ?? ''" :rows="2" /></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
            <x-admin.form-toggle name="requires_email_verification" label="Require Email OTP" :checked="$form->requires_email_verification ?? true" />
            <x-admin.form-toggle name="honeypot_enabled" label="Honeypot Anti-Spam" :checked="$form->honeypot_enabled ?? true" />
        </div>
        @if(isset($form) && $form->fields->count() > 0)
        <div class="mt-6 border-t border-gray-100 pt-6">
            <h3 class="text-sm font-semibold text-text mb-4">Form Fields ({{ $form->fields->count() }})</h3>
            <div class="space-y-2">
                @foreach($form->fields as $field)
                <div class="flex flex-col gap-1 px-4 py-3 bg-gray-50 rounded-xl sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <span class="text-sm font-medium text-text">{{ $field->label }}</span>
                        <span class="text-xs text-text-secondary ml-2">({{ $field->type }}{{ $field->is_required ? ', required' : '' }})</span>
                    </div>
                    <span class="text-xs text-text-secondary">{{ $field->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" data-loading-label="Saving…" class="bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-6 rounded-xl transition text-sm">{{ isset($form) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('admin.forms.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
        </div>
    </x-admin.card>
</form>
@endsection
