<?php

namespace App\Providers;

use App\Models\BlogPost;
use App\Models\City;
use App\Models\PortfolioProject;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\ServiceCityPage;
use App\Models\StaticPage;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Relation::enforceMorphMap([
            'service_category' => ServiceCategory::class,
            'service' => Service::class,
            'city' => City::class,
            'service_city_page' => ServiceCityPage::class,
            'static_page' => StaticPage::class,
            'blog_post' => BlogPost::class,
            'portfolio_project' => PortfolioProject::class,
        ]);

        View::composer('frontend.layouts.app', function ($view) {
            $settings = Setting::getAll();
            $globalCities = Cache::remember('global_cities_footer', 3600, function () {
                return City::where('status', 'published')->orderBy('sort_order')->limit(12)->get(['id', 'name', 'slug_final']);
            });
            $globalServiceCategories = Cache::remember('global_service_categories_footer', 3600, function () {
                return ServiceCategory::where('status', 'published')->orderBy('sort_order')->get(['id', 'name', 'slug_final']);
            });

            $globalThemeHeader = Cache::remember('global_theme_header', 3600, function () {
                return \App\Models\ThemeLayout::where('type', 'header')->where('is_active', true)->latest('updated_at')->first();
            });
            $globalThemeHeaderBlocks = $globalThemeHeader ? \App\Services\BlockBuilderService::getBlocks('theme_layout', $globalThemeHeader->id) : collect();

            $globalThemeFooter = Cache::remember('global_theme_footer', 3600, function () {
                return \App\Models\ThemeLayout::where('type', 'footer')->where('is_active', true)->latest('updated_at')->first();
            });
            $globalThemeFooterBlocks = $globalThemeFooter ? \App\Services\BlockBuilderService::getBlocks('theme_layout', $globalThemeFooter->id) : collect();

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
