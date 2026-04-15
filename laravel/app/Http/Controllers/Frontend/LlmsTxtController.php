<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\City;
use App\Models\Faq;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\Setting;
use App\Models\StaticPage;

class LlmsTxtController extends Controller
{
    public function show()
    {
        $siteName = Setting::get('site_name', 'Lush Landscape Service');
        $phone = Setting::get('phone', '');
        $email = Setting::get('email', 'info@lushlandscape.ca');

        $lines = [];
        $lines[] = "# {$siteName}";
        $lines[] = '';
        $lines[] = "> {$siteName} is a landscaping construction contractor serving Ontario, Canada.";
        $lines[] = '> We specialize in interlocking, concrete, natural stone, and softscaping services.';
        $lines[] = '> 10-year workmanship warranty on every project.';
        $lines[] = '';
        $lines[] = '## Contact';
        $lines[] = "- Phone: {$phone}";
        $lines[] = "- Email: {$email}";
        $lines[] = '- Website: '.config('app.url');
        $lines[] = '';

        $lines[] = '## Service Categories';
        $categories = ServiceCategory::where('status', 'published')->orderBy('sort_order')->get();
        foreach ($categories as $cat) {
            $lines[] = "- [{$cat->name}](".url('/services/'.$cat->slug_final).'): '.($cat->short_description ?? '');
        }
        $lines[] = '';

        $lines[] = '## Services';
        $services = Service::with('category')->where('status', 'published')->orderBy('sort_order')->get();
        foreach ($services as $svc) {
            $catSlug = $svc->category->slug_final ?? '_';
            $lines[] = "- [{$svc->name}](".url('/services/'.$catSlug.'/'.$svc->slug_final).'): '.($svc->service_summary ?? '');
        }
        $lines[] = '';

        $lines[] = '## Service Areas';
        $cities = City::where('status', 'published')->orderBy('sort_order')->get();
        foreach ($cities as $city) {
            $lines[] = "- [{$city->name}, Ontario](".url('/landscaping-'.$city->slug_final).')';
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
        $siteName = Setting::get('site_name', 'Lush Landscape Service');
        $phone = Setting::get('phone', '');
        $email = Setting::get('email', 'info@lushlandscape.ca');
        $address = Setting::get('address', '');
        $tagline = Setting::get('tagline', '');

        $lines = [];
        $lines[] = "# {$siteName}";
        $lines[] = '';
        $lines[] = "> {$siteName} is a landscaping construction contractor serving Ontario, Canada.";
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
        $categories = ServiceCategory::where('status', 'published')->orderBy('sort_order')->get();
        foreach ($categories as $cat) {
            $lines[] = "### [{$cat->name}](".url('/services/'.$cat->slug_final).')';
            if ($cat->short_description) {
                $lines[] = $cat->short_description;
            }
            if ($cat->long_description) {
                $lines[] = $cat->long_description;
            }
            $lines[] = '';
        }

        // Services with summaries
        $lines[] = '## Services';
        $services = Service::with('category')->where('status', 'published')->orderBy('sort_order')->get();
        foreach ($services as $svc) {
            $catSlug = $svc->category->slug_final ?? '_';
            $lines[] = "### [{$svc->name}](".url('/services/'.$catSlug.'/'.$svc->slug_final).')';
            if ($svc->service_summary) {
                $lines[] = $svc->service_summary;
            }
            $lines[] = '';
        }

        // Service areas
        $lines[] = '## Service Areas';
        $cities = City::where('status', 'published')->orderBy('sort_order')->get();
        foreach ($cities as $city) {
            $lines[] = "- [{$city->name}, {$city->region_name}](".url('/landscaping-'.$city->slug_final).')';
        }
        $lines[] = '';

        // Blog posts
        $posts = BlogPost::where('status', 'published')->orderByDesc('published_at')->take(50)->get();
        if ($posts->isNotEmpty()) {
            $lines[] = '## Blog';
            foreach ($posts as $post) {
                $lines[] = "### [{$post->title}](".url('/blog/'.$post->slug).')';
                if ($post->excerpt) {
                    $lines[] = $post->excerpt;
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
        $projects = PortfolioProject::where('status', 'published')->orderBy('sort_order')->take(50)->get();
        if ($projects->isNotEmpty()) {
            $lines[] = '## Portfolio';
            foreach ($projects as $project) {
                $lines[] = "- [{$project->title}](".url('/portfolio/'.$project->slug).'): '.($project->description ?? '');
            }
            $lines[] = '';
        }

        // Static pages
        $pages = StaticPage::where('status', 'published')->get();
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
