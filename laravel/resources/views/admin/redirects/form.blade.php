@extends('admin.layouts.app')
@section('title', isset($redirect) ? 'Edit Redirect' : 'Create Redirect')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($redirect) ? 'Edit Redirect' : 'Create Redirect'" />
<form method="POST" action="{{ isset($redirect) ? route('admin.redirects.update', $redirect) : route('admin.redirects.store') }}" data-ajax-form="true" data-success-message="{{ isset($redirect) ? 'Redirect updated.' : 'Redirect created.' }}">
    @csrf
    @if(isset($redirect)) @method('PUT') @endif
    <x-admin.card title="Redirect Details">
        <div class="space-y-5">
            <x-admin.form-input name="old_url" label="Old URL" :value="$redirect->old_url ?? ''" required placeholder="/old-page-slug" />
            <x-admin.form-input name="new_url" label="New URL" :value="$redirect->new_url ?? ''" required placeholder="/new-page-slug" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <x-admin.form-select name="status_code" label="Redirect Type" :options="['301'=>'301 (Permanent)','302'=>'302 (Temporary)']" :value="$redirect->status_code ?? '301'" required />
                <x-admin.form-toggle name="is_active" label="Active" :checked="$redirect->is_active ?? true" />
            </div>
        </div>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" data-loading-label="Saving…" class="bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-6 rounded-xl transition text-sm">{{ isset($redirect) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('admin.redirects.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-center text-sm text-text-secondary hover:bg-gray-50 transition">Cancel</a>
        </div>
    </x-admin.card>
</form>
@endsection
