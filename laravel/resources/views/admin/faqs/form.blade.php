@extends('admin.layouts.app')
@section('title', isset($faq) ? 'Edit FAQ' : 'Create FAQ')
@section('content')
<x-admin.flash-message />
<x-admin.page-header :title="isset($faq) ? 'Edit FAQ' : 'Create FAQ'" />
<form method="POST" action="{{ isset($faq) ? route('admin.faqs.update', $faq) : route('admin.faqs.store') }}" data-ajax-form="true" data-success-message="{{ isset($faq) ? 'FAQ updated successfully.' : 'FAQ created.' }}">
    @csrf
    @if(isset($faq)) @method('PUT') @endif
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <x-admin.card title="Question & Answer">
                <div class="space-y-5">
                    <x-admin.form-textarea name="question" label="Question" :value="$faq->question ?? ''" required :rows="2" tooltip="The FAQ question exactly as it appears on the page and in structured data (FAQPage schema). Write it as a natural question a customer would ask." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="short_answer" context="Short FAQ answer. 1 sentence summary." />
                    </div>
                    <x-admin.form-textarea name="short_answer" label="Short Answer" :value="$faq->short_answer ?? ''" :rows="2" help="Brief answer for chatbot and AI summaries" tooltip="Concise one or two sentence answer used by chatbots and AI summaries. Should directly answer the question without additional context." />
                    <div class="flex flex-wrap items-center justify-end gap-2">
                        <x-admin.ai-generate-button field="answer" context="FAQ answer. 2-3 sentences. Direct, helpful, customer-facing. Reference the question context." />
                    </div>
                    <x-admin.rich-editor name="answer" label="Full Answer" :value="$faq->answer ?? ''" required tooltip="The complete answer to the FAQ shown on the FAQ page and used in rich results." />
                    <x-admin.form-textarea name="chatbot_summary" label="Chatbot Summary" :value="$faq->chatbot_summary ?? ''" :rows="2" tooltip="Ultra-short answer optimized for chatbot responses and voice search. Should be a single direct sentence." />
                </div>
            </x-admin.card>
            <x-admin.card title="Classification">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <x-admin.form-select name="category_id" label="Category" :options="$categories->toArray()" :value="$faq->category_id ?? ''" required tooltip="Groups this FAQ under a category for organized display on the FAQ page. Choose the most relevant category." />
                    <x-admin.form-select name="faq_type" label="FAQ Type" :options="['general' => 'General', 'service' => 'Service', 'technical' => 'Technical', 'compliance' => 'Compliance', 'billing' => 'Billing', 'booking' => 'Booking', 'local' => 'Local']" :value="$faq->faq_type ?? 'general'" tooltip="Type of FAQ used to filter and organize questions. Service FAQs relate to specific services; Local FAQs are city-specific; Compliance FAQs cover permits and regulations." />
                    <x-admin.form-select name="audience_type" label="Audience" :options="['customer' => 'Customer', 'contractor' => 'Contractor', 'commercial' => 'Commercial']" :value="$faq->audience_type ?? 'customer'" tooltip="The intended audience for this FAQ. Affects where it is displayed. Customer FAQs appear on the public FAQ page; contractor and commercial FAQs may be scoped differently." />
                    <x-admin.form-input name="city_relevance" label="City Relevance" :value="$faq->city_relevance ?? ''" help="Leave empty if not city-specific" tooltip="Enter the city slug or name if this FAQ applies only to a specific city. Leave empty for FAQs that apply site-wide." />
                </div>
            </x-admin.card>
        </div>
        <div class="space-y-6">
            <x-admin.card title="Publishing">
                <div class="space-y-5">
                    <x-admin.form-select name="status" label="Status" :options="['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived']" :value="$faq->status ?? 'draft'" required tooltip="Published FAQs appear on the frontend FAQ page and are included in FAQPage structured data for rich results in search engines." />
                    <x-admin.form-input name="display_order" label="Order" type="number" :value="$faq->display_order ?? 0" tooltip="Display order within the category on the FAQ page. Lower numbers appear first at the top of the section." />
                    <x-admin.form-toggle name="is_featured" label="Featured" :checked="$faq->is_featured ?? false" tooltip="Featured FAQs are highlighted or shown first in curated FAQ sections across the site, such as on service pages or the homepage." />
                    <x-admin.form-toggle name="local_relevance" label="Local Relevance" :checked="$faq->local_relevance ?? false" tooltip="Mark this FAQ as locally relevant. Local FAQs may be surfaced on city landing pages to improve local SEO signals." />
                    <x-admin.form-toggle name="schema_eligible" label="Schema Eligible" :checked="$faq->schema_eligible ?? true" tooltip="Include this FAQ in the FAQPage JSON-LD structured data markup. Eligible FAQs can appear as rich results in Google search." />
                </div>
            </x-admin.card>
            <div class="flex flex-col gap-3 sm:flex-row">
                <button type="submit" data-loading-label="Saving…" class="flex-1 bg-forest hover:bg-forest-light text-white font-medium py-2.5 px-4 rounded-xl transition text-sm">{{ isset($faq) ? 'Update' : 'Create' }}</button>
                <a href="{{ route('admin.faqs.index') }}" class="px-4 py-2.5 border border-gray-200 rounded-xl text-sm text-center text-text-secondary hover:bg-gray-50 transition">Cancel</a>
            </div>
        </div>
    </div>
</form>
@endsection
