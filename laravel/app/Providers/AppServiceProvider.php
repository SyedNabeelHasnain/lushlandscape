<?php

declare(strict_types=1);

namespace App\Providers;

use App\Listeners\WebhookEventSubscriber;
use App\Models\ContentType;
use App\Models\Entry;
use App\Models\Setting;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\ThemeLayout;
use App\Services\BlockBuilderService;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Event::subscribe(WebhookEventSubscriber::class);

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            'entry' => Entry::class,
            'term' => Term::class,
            'content_type' => ContentType::class,
            'taxonomy' => Taxonomy::class,
        ]);

        View::composer('frontend.layouts.app', function ($view) {
            $settings = Setting::getAll();
            $globalCities = Cache::remember('global_cities_footer', 3600, function () {
                return Entry::whereHas('contentType', fn ($q) => $q->where('slug', 'city'))
                    ->where('status', 'published')
                    ->orderBy('sort_order')
                    ->limit(12)
                    ->get(['id', 'title', 'slug']);
            });
            $globalServiceCategories = Cache::remember('global_service_categories_footer', 3600, function () {
                return Term::whereHas('taxonomy', fn ($q) => $q->where('slug', 'service-categories'))
                    ->orderBy('sort_order')
                    ->get(['id', 'name', 'slug']);
            });

            $globalThemeHeader = Cache::remember('global_theme_header', 3600, function () {
                return ThemeLayout::where('type', 'header')->where('is_active', true)->latest('updated_at')->first();
            });
            $globalThemeHeaderBlocks = $globalThemeHeader ? BlockBuilderService::getBlocks('theme_layout', $globalThemeHeader->id) : collect();

            $globalThemeFooter = Cache::remember('global_theme_footer', 3600, function () {
                return ThemeLayout::where('type', 'footer')->where('is_active', true)->latest('updated_at')->first();
            });
            $globalThemeFooterBlocks = $globalThemeFooter ? BlockBuilderService::getBlocks('theme_layout', $globalThemeFooter->id) : collect();

            $view->with('globalSettings', $settings)
                ->with('globalCities', $globalCities)
                ->with('globalServiceCategories', $globalServiceCategories)
                ->with('globalThemeHeader', $globalThemeHeader)
                ->with('globalThemeHeaderBlocks', $globalThemeHeaderBlocks)
                ->with('globalThemeFooter', $globalThemeFooter)
                ->with('globalThemeFooterBlocks', $globalThemeFooterBlocks);
        });
    }
}
