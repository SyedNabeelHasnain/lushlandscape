@extends('frontend.layouts.app')
@section('seo')
<x-frontend.seo-head title="Page Not Found | Lush Landscape Service" description="The page you are looking for could not be found." :noindex="true" />
@endsection
@section('content')
<section class="py-20 md:py-32 bg-cream">
    <div class="max-w-2xl mx-auto px-4 text-center">
        <p class="text-6xl font-bold text-forest mb-4">404</p>
        <h1 class="text-2xl font-bold text-text mb-4">Page Not Found</h1>
        <p class="text-text-secondary mb-8">The page you are looking for might have been moved or no longer exists.</p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ url('/') }}" class="bg-forest hover:bg-forest-light text-white font-medium px-6 py-3 rounded-xl transition">Go Home</a>
            <a href="{{ url('/services') }}" class="border border-gray-200 text-text font-medium px-6 py-3 rounded-xl hover:bg-white transition">View Services</a>
            <a href="{{ url('/contact') }}" class="border border-gray-200 text-text font-medium px-6 py-3 rounded-xl hover:bg-white transition">Contact Us</a>
        </div>
    </div>
</section>
@endsection
