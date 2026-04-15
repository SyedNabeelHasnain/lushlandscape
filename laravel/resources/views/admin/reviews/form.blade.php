@extends('admin.layouts.app')
@section('title', isset($review) ? 'Edit Review' : 'Create Review')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($review) ? 'Edit Review' : 'Add Review'" />
<form method="POST" action="{{ isset($review) ? route('admin.reviews.update', $review) : route('admin.reviews.store') }}" data-ajax-form="true" data-success-message="{{ isset($review) ? 'Review updated successfully.' : 'Review created.' }}">
    @csrf
    @if(isset($review)) @method('PUT') @endif
    <x-admin.card title="Review Details">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <x-admin.form-input name="reviewer_name" label="Reviewer Name" :value="$review->reviewer_name ?? ''" required />
            <x-admin.form-select name="rating" label="Rating" :options="[5=>'5 Stars',4=>'4 Stars',3=>'3 Stars',2=>'2 Stars',1=>'1 Star']" :value="$review->rating ?? 5" required />
        </div>
        <div class="mt-5"><x-admin.form-textarea name="content" label="Review Content" :value="$review->content ?? ''" required :rows="4" /></div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
            <x-admin.form-select name="source" label="Source" :options="['direct'=>'Direct','google'=>'Google','homestars'=>'HomeStars','houzz'=>'Houzz','yelp'=>'Yelp','bbb'=>'BBB']" :value="$review->source ?? 'direct'" />
            <x-admin.form-input name="source_url" label="Source URL" :value="$review->source_url ?? ''" />
            <x-admin.form-input name="city_relevance" label="City" :value="$review->city_relevance ?? ''" />
            <x-admin.form-input name="neighborhood_mention" label="Neighborhood" :value="$review->neighborhood_mention ?? ''" />
            <x-admin.form-input name="service_relevance" label="Service" :value="$review->service_relevance ?? ''" />
            <x-admin.form-input name="project_type" label="Project Type" :value="$review->project_type ?? ''" help="e.g. Backyard Renovation" />
            <x-admin.form-input name="review_date" label="Review Date" type="date" :value="$review->review_date ?? ''" />
            <x-admin.form-input name="reviewer_avatar_url" label="Reviewer Avatar URL" :value="$review->reviewer_avatar_url ?? ''" help="Optional photo URL (Google profile pic, etc.)" />
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mt-5">
            <x-admin.form-select name="status" label="Status" :options="['draft'=>'Draft','published'=>'Published','archived'=>'Archived']" :value="$review->status ?? 'draft'" required />
            <x-admin.form-toggle name="is_featured" label="Featured" :checked="$review->is_featured ?? false" />
        </div>
        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
            <button type="submit" data-loading-label="Saving…" class="bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-6 rounded-xl transition text-sm">{{ isset($review) ? 'Update' : 'Create' }}</button>
            <a href="{{ route('admin.reviews.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-center text-sm text-text-secondary hover:bg-gray-50 transition">Cancel</a>
        </div>
    </x-admin.card>
</form>
@endsection
