<?php

use App\Http\Controllers\Admin\AiContentController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\BlockEditorController;
use App\Http\Controllers\Admin\BlogPostController;
use App\Http\Controllers\Admin\BulkMediaImportController;
use App\Http\Controllers\Admin\CacheController;
use App\Http\Controllers\Admin\CardTemplateController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\ContentBlockExportController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\FormSubmissionController;
use App\Http\Controllers\Admin\HomePageController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\MediaAssetController;
use App\Http\Controllers\Admin\PopupController;
use App\Http\Controllers\Admin\PortfolioProjectController;
use App\Http\Controllers\Admin\RedirectController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\SearchAnalyticsController;
use App\Http\Controllers\Admin\SecurityRuleController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\ServiceCityPageController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SingletonPageBuilderController;
use App\Http\Controllers\Admin\StaticPageController as AdminStaticPageController;
use App\Http\Controllers\Admin\TaxonomyCrudController;
use App\Http\Controllers\Admin\ThemeLayoutController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\FaqPageController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ServicePageController;
use App\Http\Controllers\Frontend\LocationPageController;
use App\Http\Controllers\Frontend\PortfolioController;
use App\Http\Controllers\Frontend\LlmsTxtController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SitemapController;
use App\Http\Controllers\Frontend\SlugResolverController;
use Illuminate\Support\Facades\Route;

Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [LoginController::class, 'login'])->middleware('throttle:5,15')->name('login.submit');
Route::post('/admin/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    
    // Super WMS Dynamic Core
    Route::resource('content-types', \App\Http\Controllers\Admin\ContentTypeController::class)->except(['show']);
    Route::resource('entries', \App\Http\Controllers\Admin\EntryController::class)->except(['show']);
    
    // Legacy Routes (To be deprecated)
    Route::resource('card-templates', CardTemplateController::class)->except(['show']);
    Route::resource('theme-layouts', ThemeLayoutController::class)->except(['show']);
    Route::get('media/json', [MediaAssetController::class, 'json'])->name('media.json');
    Route::post('media/download-all', [MediaAssetController::class, 'downloadAll'])->name('media.download-all');
    Route::post('media/populate-urls', [MediaAssetController::class, 'populateUrls'])->name('media.populate-urls');
    Route::post('media/{medium}/download', [MediaAssetController::class, 'downloadSingle'])->name('media.download-single');
    Route::resource('media', MediaAssetController::class)->except(['show']);
    Route::resource('faqs', FaqController::class)->except(['show']);
    Route::resource('reviews', ReviewController::class)->except(['show']);
    Route::resource('forms', FormController::class)->except(['show']);
    Route::get('submissions', [FormSubmissionController::class, 'index'])->name('submissions.index');
    Route::get('submissions/{submission}', [FormSubmissionController::class, 'show'])->name('submissions.show');
    Route::patch('submissions/{submission}', [FormSubmissionController::class, 'update'])->name('submissions.update');
    Route::delete('submissions/{submission}', [FormSubmissionController::class, 'destroy'])->name('submissions.destroy');
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::resource('popups', PopupController::class)->except(['show']);
    Route::resource('redirects', RedirectController::class)->except(['show']);
    Route::resource('security-rules', SecurityRuleController::class)->except(['show']);
    Route::get('search-analytics', [SearchAnalyticsController::class, 'index'])->name('search-analytics');
    Route::get('home-page', [HomePageController::class, 'edit'])->name('home-page.edit');
    Route::put('home-page', [HomePageController::class, 'update'])->name('home-page.update');
    Route::get('page-builders/{page}/edit', [SingletonPageBuilderController::class, 'edit'])->name('page-builders.edit');
    Route::put('page-builders/{page}', [SingletonPageBuilderController::class, 'update'])->name('page-builders.update');
    // Unified Block Editor
    Route::get('blocks/{pageType}/{pageId?}', [BlockEditorController::class, 'edit'])->name('blocks.edit');
    Route::post('blocks/{pageType}/{pageId?}', [BlockEditorController::class, 'update'])->name('blocks.update');
    Route::post('clear-cache', [CacheController::class, 'clear'])->name('clear-cache');
    Route::post('ai/generate', [AiContentController::class, 'generate'])->name('ai.generate');
    Route::get('import-export', [ImportExportController::class, 'index'])->name('import-export.index');
    Route::post('import-export/export', [ImportExportController::class, 'export'])->name('import-export.export');
    Route::post('import-export/upload', [ImportExportController::class, 'upload'])->name('import-export.upload');
    Route::post('import-export/confirm', [ImportExportController::class, 'confirm'])->name('import-export.confirm');
    Route::get('bulk-import', [BulkMediaImportController::class, 'index'])->name('bulk-import.index');
    Route::post('bulk-import/process', [BulkMediaImportController::class, 'process'])->name('bulk-import.process');
    Route::post('bulk-import/upload', [BulkMediaImportController::class, 'upload'])->name('bulk-import.upload');
    Route::get('bulk-import/export-library', [BulkMediaImportController::class, 'exportLibrary'])->name('bulk-import.export-library');
    Route::post('bulk-import/import-library', [BulkMediaImportController::class, 'importLibrary'])->name('bulk-import.import-library');
    Route::get('bulk-import/generate-dataset', [BulkMediaImportController::class, 'generateDataset'])->name('bulk-import.generate-dataset');
});

Route::get('/llms.txt', [LlmsTxtController::class, 'show'])->name('llms.txt');
Route::get('/llms-full.txt', [LlmsTxtController::class, 'full'])->name('llms-full.txt');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServicePageController::class, 'hub'])->name('services.hub');
Route::get('/locations', [LocationPageController::class, 'hub'])->name('locations.hub');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio.index');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/contact', [ContactController::class, 'show'])->name('contact.show');
Route::get('/consultation', [ContactController::class, 'consultation'])->name('contact.consultation');
Route::get('/search/live', [SearchController::class, 'live'])->middleware('throttle:30,1')->name('search.live');
Route::get('/search', [SearchController::class, 'results'])->middleware('throttle:30,1')->name('search.results');
Route::get('/faqs', [FaqPageController::class, 'index'])->name('faqs.index');

// Super WMS Engine Catch-All Route Resolver
Route::get('/{slug}', [\App\Http\Controllers\Frontend\EntityController::class, 'resolve'])
    ->where('slug', '.*')
    ->name('wms.resolve');
