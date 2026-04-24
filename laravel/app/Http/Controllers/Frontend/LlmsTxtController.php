<?php

declare(strict_types=1);

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\Setting;
use Illuminate\Support\Facades\URL;
class LlmsTxtController extends Controller
{
    public function show()
    {
        $siteName = Setting::get('site_name', 'Super WMS');
        $phone = Setting::get('phone', '');
        $email = Setting::get('email', 'hello@example.com');

        $lines = [];
        $lines[] = "# {$siteName}";
        $lines[] = '';
        $lines[] = "> {$siteName} is a professional construction contractor serving Our Region, Canada.";
        $lines[] = '> We specialize in interlocking, concrete, natural stone, and softscaping services.';
        $lines[] = '> 10-year workmanship warranty on every project.';
        $lines[] = '';
        $lines[] = '## Contact';
        $lines[] = "- Phone: {$phone}";
        $lines[] = "- Email: {$email}";
        $lines[] = '- Website: '.config('app.url');
        $lines[] = '';

        $lines[] = '## Service Categories';
        $categories = \App\Models\Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'service-categories'))->orderBy('sort_order')->get();
        foreach ($categories as $cat) {
            $lines[] = "- [{$cat->title}](".url('/services/'.$cat->slug).'): '.($cat->data['description'] ?? '');
        }
        $lines[] = '';

        $lines[] = '## Services';
        $services = \App\Models\Entry::with('terms')->whereHas('contentType', fn($q) => $q->where('slug', 'service'))->where('status', 'published')->orderBy('sort_order')->get();
        foreach ($services as $svc) {
            $catSlug = $svc->terms->first()->slug ?? '_';
            $lines[] = "- [{$svc->title}](".url('/services/'.$catSlug.'/'.$svc->slug).'): '.($svc->data['service_summary'] ?? '');
        }
        $lines[] = '';

        $lines[] = '## Service Areas';
        $cities = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')->orderBy('sort_order')->get();
        foreach ($cities as $city) {
            $lines[] = "- [{$city->title}, Our Region](".url('/professional-'.$city->slug).')';
        }
        $lines[] = '';

        $lines[] = '## Credentials';
        $lines[] = '- 10-Year Workmanship Warranty';
        $lines[] = '- WSIB Certified';
        $lines[] = '- Fully Insured';
        $lines[] = '';

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }

    public function full()
    {
        $siteName = Setting::get('site_name', 'Super WMS');
        $phone = Setting::get('phone', '');
        $email = Setting::get('email', 'hello@example.com');
        $address = Setting::get('address', '');
        $tagline = Setting::get('tagline', '');

        $lines = [];
        $lines[] = "# {$siteName}";
        $lines[] = '';
        $lines[] = "> {$siteName} is a professional construction contractor serving Our Region, Canada.";
        $lines[] = '> We specialize in interlocking, concrete, natural stone, and softscaping services.';
        $lines[] = '> 10-year workmanship warranty on every project.';
        if ($tagline) {
            $lines[] = "> {$tagline}";
        }
        $lines[] = '';

        $lines[] = '## Contact';
        $lines[] = "- Phone: {$phone}";
        $lines[] = "- Email: {$email}";
        if ($address) {
            $lines[] = "- Address: {$address}";
        }
        $lines[] = '- Website: '.config('app.url');
        $lines[] = '';

        // Service categories with full descriptions
        $lines[] = '## Service Categories';
        $categories = \App\Models\Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'service-categories'))->orderBy('sort_order')->get();
        foreach ($categories as $cat) {
            $lines[] = "### [{$cat->title}](".url('/services/'.$cat->slug).')';
            if (!empty($cat->data['description'])) {
                $lines[] = $cat->data['description'];
            }
            $lines[] = '';
        }

        // Services with summaries
        $lines[] = '## Services';
        $services = \App\Models\Entry::with('terms')->whereHas('contentType', fn($q) => $q->where('slug', 'service'))->where('status', 'published')->orderBy('sort_order')->get();
        foreach ($services as $svc) {
            $catSlug = $svc->terms->first()->slug ?? '_';
            $lines[] = "### [{$svc->title}](".url('/services/'.$catSlug.'/'.$svc->slug).')';
            if (!empty($svc->data['service_summary'])) {
                $lines[] = $svc->data['service_summary'];
            }
            $lines[] = '';
        }

        // Service areas
        $lines[] = '## Service Areas';
        $cities = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'city'))->where('status', 'published')->orderBy('sort_order')->get();
        foreach ($cities as $city) {
            $region = $city->data['region_name'] ?? 'Our Region';
            $lines[] = "- [{$city->title}, {$region}](".url('/professional-'.$city->slug).')';
        }
        $lines[] = '';

        // Blog posts
        $posts = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'blog-post'))->where('status', 'published')->orderByDesc('published_at')->take(50)->get();
        if ($posts->isNotEmpty()) {
            $lines[] = '## Blog';
            foreach ($posts as $post) {
                $lines[] = "### [{$post->title}](".url('/blog/'.$post->slug).')';
                if (!empty($post->data['excerpt'])) {
                    $lines[] = $post->data['excerpt'];
                }
                $lines[] = '';
            }
        }

        // FAQs
        $faqs = Faq::where('status', 'published')->orderBy('display_order')->take(100)->get();
        if ($faqs->isNotEmpty()) {
            $lines[] = '## Frequently Asked Questions';
            foreach ($faqs as $faq) {
                $lines[] = "**Q: {$faq->question}**";
                $lines[] = strip_tags($faq->answer);
                $lines[] = '';
            }
        }

        // Portfolio
        $projects = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published')->orderBy('sort_order')->take(50)->get();
        if ($projects->isNotEmpty()) {
            $lines[] = '## Portfolio';
            foreach ($projects as $project) {
                $lines[] = "- [{$project->title}](".url('/portfolio/'.$project->slug).'): '.($project->data['description'] ?? '');
            }
            $lines[] = '';
        }

        // Static pages
        $pages = \App\Models\Entry::whereHas('contentType', fn($q) => $q->where('slug', 'static-page'))->where('status', 'published')->get();
        if ($pages->isNotEmpty()) {
            $lines[] = '## Pages';
            foreach ($pages as $page) {
                $lines[] = "- [{$page->title}](".url('/'.$page->slug).')';
            }
            $lines[] = '';
        }

        $lines[] = '## Credentials';
        $lines[] = '- 10-Year Workmanship Warranty';
        $lines[] = '- WSIB Certified';
        $lines[] = '- Fully Insured';
        $lines[] = '';

        return response(implode("\n", $lines), 200)->header('Content-Type', 'text/plain');
    }
}
