<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\MediaAsset;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Models\StaticPage;
use Illuminate\Console\Command;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';

    protected $description = 'Generate XML sitemap';

    public function handle()
    {
        $baseUrl = config('app.url');
        $urls = [];

        $urls[] = ['loc' => $baseUrl.'/', 'changefreq' => 'weekly', 'priority' => '1.0'];
        $urls[] = ['loc' => $baseUrl.'/services', 'changefreq' => 'weekly', 'priority' => '0.9'];
        $urls[] = ['loc' => $baseUrl.'/locations', 'changefreq' => 'weekly', 'priority' => '0.9'];
        $urls[] = ['loc' => $baseUrl.'/blog', 'changefreq' => 'daily', 'priority' => '0.8'];
        $urls[] = ['loc' => $baseUrl.'/portfolio', 'changefreq' => 'weekly', 'priority' => '0.7'];
        $urls[] = ['loc' => $baseUrl.'/contact', 'changefreq' => 'monthly', 'priority' => '0.8'];

        ServiceCategory::where('status', 'published')->with('heroMedia')->cursor()->each(function ($cat) use (&$urls, $baseUrl) {
            $entry = ['loc' => $baseUrl.'/services/'.$cat->slug_final, 'changefreq' => 'weekly', 'priority' => '0.8'];
            if ($cat->heroMedia) {
                $entry['images'][] = ['url' => $cat->heroMedia->url, 'title' => $cat->name];
            }
            $urls[] = $entry;
        });

        Service::where('status', 'published')->with(['category', 'heroMedia'])->cursor()->each(function ($svc) use (&$urls, $baseUrl) {
            if ($svc->category) {
                $entry = ['loc' => $baseUrl.'/services/'.$svc->category->slug_final.'/'.$svc->slug_final, 'changefreq' => 'weekly', 'priority' => '0.8'];
                if ($svc->heroMedia) {
                    $entry['images'][] = ['url' => $svc->heroMedia->url, 'title' => $svc->name];
                }
                $urls[] = $entry;
            }
        });

        City::where('status', 'published')->with('heroMedia')->cursor()->each(function ($city) use (&$urls, $baseUrl) {
            $entry = ['loc' => $baseUrl.'/landscaping-'.$city->slug_final, 'changefreq' => 'weekly', 'priority' => '0.8'];
            if ($city->heroMedia) {
                $entry['images'][] = ['url' => $city->heroMedia->url, 'title' => $city->name];
            }
            $urls[] = $entry;
        });

        ServiceCityPage::where('is_active', true)->where('is_indexable', true)->with('heroMedia')->cursor()->each(function ($page) use (&$urls, $baseUrl) {
            $entry = ['loc' => $baseUrl.'/'.$page->slug_final, 'changefreq' => 'weekly', 'priority' => '0.9'];
            if ($page->heroMedia) {
                $entry['images'][] = ['url' => $page->heroMedia->url, 'title' => $page->meta_title ?? $page->slug_final];
            }
            $urls[] = $entry;
        });

        StaticPage::where('status', 'published')->where('is_indexable', true)->with('heroMedia')->cursor()->each(function ($page) use (&$urls, $baseUrl) {
            $entry = ['loc' => $baseUrl.'/'.$page->slug, 'changefreq' => 'monthly', 'priority' => '0.6'];
            if ($page->heroMedia) {
                $entry['images'][] = ['url' => $page->heroMedia->url, 'title' => $page->title];
            }
            $urls[] = $entry;
        });

        BlogPost::where('status', 'published')->with('heroMedia')->cursor()->each(function ($post) use (&$urls, $baseUrl) {
            $entry = [
                'loc' => $baseUrl.'/blog/'.$post->slug,
                'changefreq' => 'monthly',
                'priority' => '0.7',
                'lastmod' => $post->updated_at->toW3cString(),
            ];
            if ($post->heroMedia) {
                $entry['images'][] = ['url' => $post->heroMedia->url, 'title' => $post->title];
            }
            $urls[] = $entry;
        });

        $portfolioProjects = PortfolioProject::where('status', 'published')->with('heroMedia')->get();
        $allMediaIds = [];
        foreach ($portfolioProjects as $proj) {
            if (! empty($proj->gallery_media_ids)) {
                $allMediaIds = array_merge($allMediaIds, $proj->gallery_media_ids);
            }
        }
        $allMediaIds = array_unique($allMediaIds);
        $mediaAssets = $allMediaIds ? MediaAsset::whereIn('id', $allMediaIds)->get()->keyBy('id') : collect();

        foreach ($portfolioProjects as $proj) {
            $entry = ['loc' => $baseUrl.'/portfolio/'.$proj->slug, 'changefreq' => 'monthly', 'priority' => '0.6'];
            if ($proj->heroMedia) {
                $entry['images'][] = ['url' => $proj->heroMedia->getAttribute('url'), 'title' => $proj->title];
            }

            if (! empty($proj->gallery_media_ids)) {
                foreach ($proj->gallery_media_ids as $mediaId) {
                    if ($img = $mediaAssets->get($mediaId)) {
                        $entry['images'][] = ['url' => $img->getAttribute('url'), 'title' => $img->getAttribute('default_alt_text') ?? $proj->title];
                    }
                }
            }

            $urls[] = $entry;
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">'."\n";

        foreach ($urls as $url) {
            $xml .= '  <url>'."\n";
            $xml .= '    <loc>'.htmlspecialchars($url['loc']).'</loc>'."\n";
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>'.$url['lastmod'].'</lastmod>'."\n";
            }
            $xml .= '    <changefreq>'.$url['changefreq'].'</changefreq>'."\n";
            $xml .= '    <priority>'.$url['priority'].'</priority>'."\n";
            foreach ($url['images'] ?? [] as $image) {
                $xml .= '    <image:image>'."\n";
                $xml .= '      <image:loc>'.htmlspecialchars($image['url']).'</image:loc>'."\n";
                if (! empty($image['title'])) {
                    $xml .= '      <image:title>'.htmlspecialchars($image['title']).'</image:title>'."\n";
                }
                $xml .= '    </image:image>'."\n";
            }
            $xml .= '  </url>'."\n";
        }

        $xml .= '</urlset>';

        file_put_contents(public_path('sitemap.xml'), $xml);

        $this->info('Sitemap generated with '.count($urls).' URLs.');

        return Command::SUCCESS;
    }
}
