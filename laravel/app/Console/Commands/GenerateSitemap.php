<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Entry;
use App\Models\Term;
use App\Models\MediaAsset;
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

        $addMedia = function (&$entry, $model, $title) {
            if (!empty($model->data['hero_media_id']) && $media = MediaAsset::find($model->data['hero_media_id'])) {
                $entry['images'][] = ['url' => $media->url, 'title' => $title];
            }
        };

        $resolveUrl = function ($slug) use ($baseUrl) {
            return rtrim($baseUrl, '/') . '/' . ltrim($slug, '/');
        };

        Term::whereHas('taxonomy', fn($q) => $q->where('slug', 'service-categories'))->cursor()->each(function ($cat) use (&$urls, $resolveUrl, $addMedia) {
            $entry = ['loc' => $resolveUrl('services/' . $cat->slug), 'changefreq' => 'weekly', 'priority' => '0.8'];
            $addMedia($entry, $cat, $cat->name);
            $urls[] = $entry;
        });

        $dynamicEntryTypes = [
            'service' => ['changefreq' => 'weekly', 'priority' => '0.8'],
            'city' => ['changefreq' => 'weekly', 'priority' => '0.8'],
            'service-city-page' => ['changefreq' => 'weekly', 'priority' => '0.9'],
            'static-page' => ['changefreq' => 'monthly', 'priority' => '0.6'],
            'blog-post' => ['changefreq' => 'monthly', 'priority' => '0.7'],
        ];

        foreach ($dynamicEntryTypes as $slug => $meta) {
            Entry::whereHas('contentType', fn($q) => $q->where('slug', $slug))->where('status', 'published')
                ->with('routeAlias')->cursor()->each(function ($page) use (&$urls, $resolveUrl, $addMedia, $meta) {
                if (!$page->routeAlias) return;

                $entry = [
                    'loc' => $resolveUrl($page->routeAlias->slug),
                    'changefreq' => $meta['changefreq'],
                    'priority' => $meta['priority'],
                ];
                if (!empty($page->updated_at)) {
                    $entry['lastmod'] = $page->updated_at->toW3cString();
                }
                $addMedia($entry, $page, $page->title);
                $urls[] = $entry;
            });
        }

        $portfolioProjects = Entry::whereHas('contentType', fn($q) => $q->where('slug', 'portfolio-project'))->where('status', 'published')
            ->with('routeAlias')->get();
        
        $allMediaIds = [];
        foreach ($portfolioProjects as $proj) {
            if (!empty($proj->data['gallery_media_ids'])) {
                $allMediaIds = array_merge($allMediaIds, $proj->data['gallery_media_ids']);
            }
            if (!empty($proj->data['hero_media_id'])) {
                $allMediaIds[] = $proj->data['hero_media_id'];
            }
        }
        $mediaAssets = array_unique($allMediaIds) ? MediaAsset::whereIn('id', array_unique($allMediaIds))->get()->keyBy('id') : collect();

        foreach ($portfolioProjects as $proj) {
            if (!$proj->routeAlias) continue;
            $entry = ['loc' => $resolveUrl($proj->routeAlias->slug), 'changefreq' => 'monthly', 'priority' => '0.6'];
            
            if (!empty($proj->data['hero_media_id']) && $img = $mediaAssets->get($proj->data['hero_media_id'])) {
                $entry['images'][] = ['url' => $img->getAttribute('url'), 'title' => $proj->title];
            }

            if (!empty($proj->data['gallery_media_ids'])) {
                foreach ($proj->data['gallery_media_ids'] as $mediaId) {
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
                if (!empty($image['title'])) {
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