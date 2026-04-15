@extends('admin.layouts.app')
@section('title', isset($rule) ? 'Edit Rule' : 'Create Rule')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($rule) ? 'Edit Security Rule' : 'Create Security Rule'" />
<form method="POST" action="{{ isset($rule) ? route('admin.security-rules.update', $rule) : route('admin.security-rules.store') }}" data-ajax-form="true" data-success-message="{{ isset($rule) ? 'Rule updated.' : 'Rule created.' }}">
    @csrf
    @if(isset($rule)) @method('PUT') @endif
    <x-admin.card title="Rule Details">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-select name="type" label="Type" :options="['ip'=>'IP Address','country'=>'Country','region'=>'Region']" :value="$rule->type ?? 'ip'" required />
            <x-admin.form-input name="value" label="Value" :value="$rule->value ?? ''" required placeholder="e.g. 192.168.1.1 or CN" />
            <x-admin.form-select name="action" label="Action" :options="['block'=>'Block','allow'=>'Allow']" :value="$rule->action ?? 'block'" required />
            <x-admin.form-toggle name="is_active" label="Active" :checked="$rule->is_active ?? true" />
        </div>
        <div class="mt-5"><x-admin.form-textarea name="description" label="Description" :value="$rule->description ?? ''" :rows="2" /></div>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" data-loading-label="Saving…" class="bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-6 rounded-xl transition text-sm">{{ isset($rule) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('admin.security-rules.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-center text-sm text-text-secondary hover:bg-gray-50 transition">Cancel</a>
        </div>
    </x-admin.card>
</form>
@endsection
