@extends('admin.layouts.app')

@section('title', ($webhook->exists ? 'Edit' : 'Create') . ' Webhook | Super WMS')
@section('header', ($webhook->exists ? 'Edit' : 'Create') . ' Webhook')

@section('content')
<div class="card p-8">
    <div class="mb-6">
        <a href="{{ route('admin.webhooks.index') }}" class="text-xs font-semibold text-stone/50 hover:text-forest uppercase tracking-widest">&larr; Back to Webhooks</a>
    </div>

    <form action="{{ $webhook->exists ? route('admin.webhooks.update', $webhook) : route('admin.webhooks.store') }}" method="POST" class="space-y-6 max-w-4xl">
        @csrf
        @if($webhook->exists) @method('PUT') @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Name *</label>
                <input type="text" name="name" value="{{ old('name', $webhook->name) }}" class="field-ui w-full" placeholder="e.g. Vercel Production Deploy" required>
            </div>
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Event Trigger *</label>
                <select name="event" class="field-ui w-full" required>
                    <option value="entry.saved" {{ old('event', $webhook->event) == 'entry.saved' ? 'selected' : '' }}>Entry Saved / Published</option>
                    <option value="entry.deleted" {{ old('event', $webhook->event) == 'entry.deleted' ? 'selected' : '' }}>Entry Deleted</option>
                    <option value="form.submitted" {{ old('event', $webhook->event) == 'form.submitted' ? 'selected' : '' }}>Form Submitted</option>
                    <option value="system.deploy" {{ old('event', $webhook->event) == 'system.deploy' ? 'selected' : '' }}>Manual Site Deploy</option>
                </select>
            </div>
        </div>

        <div class="field-wrapper">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Payload URL *</label>
            <input type="url" name="url" value="{{ old('url', $webhook->url) }}" class="field-ui w-full" placeholder="https://api.vercel.com/v1/integrations/deploy/..." required>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Secret Token</label>
                <input type="text" name="secret" value="{{ old('secret', $webhook->secret) }}" class="field-ui w-full" placeholder="Optional HMAC signing secret">
            </div>
            <div class="field-wrapper flex items-center pt-8">
                <label class="flex items-center gap-2 cursor-pointer text-sm font-medium text-forest">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $webhook->exists ? $webhook->is_active : true) ? 'checked' : '' }} class="rounded border-stone/50 text-forest focus:ring-forest">
                    Active
                </label>
            </div>
        </div>

        <div class="field-wrapper mt-8 pt-8 border-t border-gray-100">
            <label class="block text-sm font-bold tracking-widest text-forest mb-2">Custom Headers (JSON)</label>
            <p class="text-xs text-stone/70 mb-2">Optional JSON object of headers to send with the request.</p>
            <textarea name="headers_json" class="field-ui w-full font-mono text-sm bg-gray-50" rows="5" placeholder='{"Authorization": "Bearer token123"}'>{{ old('headers_json', $webhook->headers ? json_encode($webhook->headers, JSON_PRETTY_PRINT) : '') }}</textarea>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Timeout (seconds)</label>
                <input type="number" name="timeout" value="{{ old('timeout', $webhook->timeout ?? 5) }}" min="1" max="30" class="field-ui w-full" required>
            </div>
            <div class="field-wrapper">
                <label class="block text-sm font-bold tracking-widest text-forest mb-2">Retry Count</label>
                <input type="number" name="retry_count" value="{{ old('retry_count', $webhook->retry_count ?? 0) }}" min="0" max="5" class="field-ui w-full" required>
            </div>
        </div>

        <div class="flex justify-end gap-4 mt-8 pt-6 border-t border-stone/20">
            <a href="{{ route('admin.webhooks.index') }}" class="btn-outline px-6 py-2 rounded-sm border border-stone/50 text-forest hover:bg-stone/5">Cancel</a>
            <button type="submit" class="btn-solid bg-forest text-white px-8 py-2 rounded-sm text-sm font-bold uppercase tracking-widest">Save Webhook</button>
        </div>
    </form>
</div>
@endsection