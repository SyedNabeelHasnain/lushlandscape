@extends('admin.layouts.app')
@section('title', 'View Submission')
@section('content')
<x-admin.flash-message />
<x-admin.page-header title="Submission Details" subtitle="From: {{ $submission->form->name ?? 'Unknown' }}" />
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <x-admin.card title="Submitted Data">
            <div class="space-y-4">
                @foreach($submission->data as $key => $value)
                <div class="flex flex-col sm:flex-row sm:items-start gap-1">
                    <span class="text-sm font-medium text-text w-40 shrink-0">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                    <span class="text-sm text-text-secondary">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                </div>
                @endforeach
            </div>
        </x-admin.card>
    </div>
    <div class="space-y-6">
        <x-admin.card title="Status">
            <form method="POST" action="{{ route('admin.submissions.update', $submission) }}" data-ajax-form="true">
                @csrf
                @method('PATCH')
                <x-admin.form-select name="status" label="Status" :options="['new'=>'New','read'=>'Read','replied'=>'Replied','archived'=>'Archived']" :value="$submission->status" required />
                <button type="submit" data-loading-label="Saving…" class="mt-4 w-full bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">Update Status</button>
            </form>
        </x-admin.card>
        <x-admin.card title="Meta">
            <div class="space-y-2 text-xs text-text-secondary">
                <p>Submitted: {{ $submission->created_at->format('M d, Y H:i:s') }}</p>
                <p>IP: {{ $submission->ip_address ?? '-' }}</p>
                <p>Email Verified: {{ $submission->email_verified ? 'Yes' : 'No' }}</p>
                <p>Referrer: {{ $submission->referrer ?? '-' }}</p>
            </div>
        </x-admin.card>
        <a href="{{ route('admin.submissions.index') }}" class="block text-center px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-text-secondary hover:bg-gray-50 transition">Back to List</a>
    </div>
</div>
@endsection
