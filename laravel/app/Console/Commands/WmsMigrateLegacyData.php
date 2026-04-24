<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ContentType;
use App\Models\Taxonomy;
use App\Models\Term;
use App\Models\Entry;
use App\Models\RouteAlias;
use App\Models\EntryRelation;

class WmsMigrateLegacyData extends Command
{
    protected $signature = 'wms:migrate-legacy';
    protected $description = 'Migrates legacy hardcoded tables into the Super WMS dynamic engine';

    public function handle()
    {
        $this->info('Starting Super WMS Migration...');

        DB::transaction(function () {
            // 1. Clean existing WMS data for idempotency
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::table('route_aliases')->truncate();
            DB::table('entry_relations')->truncate();
            DB::table('termables')->truncate();
            DB::table('terms')->truncate();
            DB::table('entries')->truncate();
            DB::table('taxonomies')->truncate();
            DB::table('content_types')->truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // 2. Create Content Types
            $ctService = ContentType::create(['name' => 'Service', 'slug' => 'service', 'layout_template' => 'service']);
            $ctCity = ContentType::create(['name' => 'City', 'slug' => 'city', 'layout_template' => 'city']);
            $ctPortfolio = ContentType::create(['name' => 'Portfolio Project', 'slug' => 'portfolio-project', 'layout_template' => 'portfolio-project']);
            $ctStatic = ContentType::create(['name' => 'Static Page', 'slug' => 'static-page', 'layout_template' => 'static-page']);
            $ctBlog = ContentType::create(['name' => 'Blog Post', 'slug' => 'blog-post', 'layout_template' => 'blog-post']);
            $ctMatrix = ContentType::create(['name' => 'Service City Page', 'slug' => 'service-city-page', 'layout_template' => 'service-city-page']);

            // 3. Create Taxonomies
            $taxServiceCat = Taxonomy::create(['name' => 'Service Categories', 'slug' => 'service-categories', 'is_hierarchical' => true]);
            $taxPortfolioCat = Taxonomy::create(['name' => 'Portfolio Categories', 'slug' => 'portfolio-categories', 'is_hierarchical' => true]);
            $taxBlogCat = Taxonomy::create(['name' => 'Blog Categories', 'slug' => 'blog-categories', 'is_hierarchical' => true]);
            $taxBlogTag = Taxonomy::create(['name' => 'Blog Tags', 'slug' => 'blog-tags', 'is_hierarchical' => false]);

            $this->info('Core WMS Schema created.');

            // 4. Migrate Service Categories -> Terms
            $serviceCatMap = [];
            $serviceCats = DB::table('service_categories')->get();
            foreach ($serviceCats as $cat) {
                $term = Term::create([
                    'taxonomy_id' => $taxServiceCat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug_final,
                    'description' => $cat->short_description,
                    'data' => [
                        'icon' => $cat->icon,
                        'hero_media_id' => $cat->hero_media_id,
                        'status' => $cat->status
                    ],
                    'sort_order' => $cat->sort_order,
                ]);
                $serviceCatMap[$cat->id] = $term->id;
                
                RouteAlias::create([
                    'slug' => 'services/' . $cat->slug_final,
                    'routable_type' => Term::class,
                    'routable_id' => $term->id,
                    'is_active' => $cat->status === 'published'
                ]);
            }

            // 5. Migrate Services -> Entries
            $serviceMap = [];
            $services = DB::table('services')->get();
            foreach ($services as $service) {
                $entry = Entry::create([
                    'content_type_id' => $ctService->id,
                    'title' => $service->name,
                    'slug' => $service->slug_final,
                    'status' => $service->status,
                    'published_at' => $service->status === 'published' ? now() : null,
                    'sort_order' => $service->sort_order,
                    'data' => [
                        'service_code' => $service->service_code,
                        'service_summary' => $service->service_summary,
                        'service_body' => json_decode($service->service_body, true),
                        'default_meta_title' => $service->default_meta_title,
                        'default_meta_description' => $service->default_meta_description,
                        'icon' => $service->icon,
                        'hero_media_id' => $service->hero_media_id,
                    ]
                ]);
                $serviceMap[$service->id] = $entry->id;

                if ($service->category_id && isset($serviceCatMap[$service->category_id])) {
                    $entry->terms()->attach($serviceCatMap[$service->category_id]);
                    $catSlug = DB::table('service_categories')->where('id', $service->category_id)->value('slug_final');
                    
                    RouteAlias::create([
                        'slug' => 'services/' . $catSlug . '/' . $service->slug_final,
                        'routable_type' => Entry::class,
                        'routable_id' => $entry->id,
                        'is_active' => $service->status === 'published'
                    ]);
                }

                // Map PageBlocks
                DB::table('page_blocks')->where('page_type', 'service')->where('page_id', $service->id)
                    ->update(['page_type' => 'entry', 'page_id' => $entry->id]);
            }
            $this->info('Services migrated.');

            // 6. Migrate Cities -> Entries
            $cityMap = [];
            $cities = DB::table('cities')->get();
            foreach ($cities as $city) {
                $entry = Entry::create([
                    'content_type_id' => $ctCity->id,
                    'title' => $city->name,
                    'slug' => $city->slug_final,
                    'status' => $city->status,
                    'published_at' => $city->status === 'published' ? now() : null,
                    'sort_order' => $city->sort_order,
                    'data' => [
                        'province_name' => $city->province_name,
                        'region_name' => $city->region_name,
                        'latitude' => $city->latitude,
                        'longitude' => $city->longitude,
                        'city_summary' => $city->city_summary,
                        'city_body' => json_decode($city->city_body, true),
                        'local_conditions_json' => json_decode($city->local_conditions_json, true),
                        'hero_media_id' => $city->hero_media_id,
                    ]
                ]);
                $cityMap[$city->id] = $entry->id;

                RouteAlias::create([
                    'slug' => 'professional-' . $city->slug_final, // Used to be landscaping-
                    'routable_type' => Entry::class,
                    'routable_id' => $entry->id,
                    'is_active' => $city->status === 'published'
                ]);

                // Map PageBlocks
                DB::table('page_blocks')->where('page_type', 'city')->where('page_id', $city->id)
                    ->update(['page_type' => 'entry', 'page_id' => $entry->id]);
            }
            $this->info('Cities migrated.');

            // 7. Migrate Portfolio Categories & Projects
            $portCatMap = [];
            $portCats = DB::table('portfolio_categories')->get();
            foreach ($portCats as $cat) {
                $term = Term::create([
                    'taxonomy_id' => $taxPortfolioCat->id,
                    'name' => $cat->name,
                    'slug' => $cat->slug,
                    'description' => $cat->description,
                    'sort_order' => $cat->sort_order,
                ]);
                $portCatMap[$cat->id] = $term->id;
                
                RouteAlias::create([
                    'slug' => 'portfolio/category/' . $cat->slug,
                    'routable_type' => Term::class,
                    'routable_id' => $term->id,
                    'is_active' => $cat->status === 'published'
                ]);
            }

            $projects = DB::table('portfolio_projects')->get();
            foreach ($projects as $project) {
                $entry = Entry::create([
                    'content_type_id' => $ctPortfolio->id,
                    'title' => $project->title,
                    'slug' => $project->slug,
                    'status' => $project->status,
                    'published_at' => $project->status === 'published' ? now() : null,
                    'sort_order' => $project->sort_order,
                    'data' => [
                        'description' => $project->description,
                        'body' => $project->body,
                        'hero_media_id' => $project->hero_media_id,
                        'before_image_id' => $project->before_image_id,
                        'after_image_id' => $project->after_image_id,
                        'gallery_media_ids' => json_decode($project->gallery_media_ids, true),
                        'project_value_range' => $project->project_value_range,
                        'completion_date' => $project->completion_date,
                    ]
                ]);

                if ($project->category_id && isset($portCatMap[$project->category_id])) {
                    $entry->terms()->attach($portCatMap[$project->category_id]);
                }

                if ($project->service_id && isset($serviceMap[$project->service_id])) {
                    EntryRelation::create([
                        'source_entry_id' => $entry->id,
                        'target_entry_id' => $serviceMap[$project->service_id],
                        'relation_type' => 'portfolio_service'
                    ]);
                }

                if ($project->city_id && isset($cityMap[$project->city_id])) {
                    EntryRelation::create([
                        'source_entry_id' => $entry->id,
                        'target_entry_id' => $cityMap[$project->city_id],
                        'relation_type' => 'portfolio_city'
                    ]);
                }

                RouteAlias::create([
                    'slug' => 'portfolio/' . $project->slug,
                    'routable_type' => Entry::class,
                    'routable_id' => $entry->id,
                    'is_active' => $project->status === 'published'
                ]);

                // Map PageBlocks
                DB::table('page_blocks')->where('page_type', 'portfolio_project')->where('page_id', $project->id)
                    ->update(['page_type' => 'entry', 'page_id' => $entry->id]);
            }
            $this->info('Portfolio migrated.');

            // 8. Migrate Static Pages
            $staticPages = DB::table('static_pages')->get();
            foreach ($staticPages as $page) {
                $entry = Entry::create([
                    'content_type_id' => $ctStatic->id,
                    'title' => $page->title,
                    'slug' => $page->slug,
                    'status' => $page->status,
                    'published_at' => $page->status === 'published' ? now() : null,
                    'data' => [
                        'excerpt' => $page->excerpt,
                        'meta_title' => $page->meta_title,
                        'meta_description' => $page->meta_description,
                    ]
                ]);

                RouteAlias::create([
                    'slug' => $page->slug,
                    'routable_type' => Entry::class,
                    'routable_id' => $entry->id,
                    'is_active' => $page->status === 'published'
                ]);

                // Map PageBlocks
                DB::table('page_blocks')->where('page_type', 'static_page')->where('page_id', $page->id)
                    ->update(['page_type' => 'entry', 'page_id' => $entry->id]);
            }
            $this->info('Static Pages migrated.');

            // 9. Migrate ServiceCityPages (The Matrix)
            $matrixPages = DB::table('service_city_pages')->get();
            $this->info('Migrating ' . count($matrixPages) . ' Matrix Pages...');
            foreach ($matrixPages as $page) {
                $this->info('Processing Matrix Page: ' . $page->id);
                $entry = Entry::create([
                    'content_type_id' => $ctMatrix->id,
                    'title' => $page->page_title,
                    'slug' => $page->slug_final,
                    'status' => $page->is_active ? 'published' : 'draft',
                    'published_at' => $page->is_active ? now() : null,
                    'sort_order' => $page->sort_order,
                    'data' => [
                        'h1' => $page->h1,
                        'local_intro' => $page->local_intro,
                        'hero_media_id' => $page->hero_media_id,
                    ]
                ]);

                if ($page->service_id && isset($serviceMap[$page->service_id])) {
                    EntryRelation::create([
                        'source_entry_id' => $entry->id,
                        'target_entry_id' => $serviceMap[$page->service_id],
                        'relation_type' => 'matrix_service'
                    ]);
                }

                if ($page->city_id && isset($cityMap[$page->city_id])) {
                    EntryRelation::create([
                        'source_entry_id' => $entry->id,
                        'target_entry_id' => $cityMap[$page->city_id],
                        'relation_type' => 'matrix_city'
                    ]);
                }

                RouteAlias::create([
                    'slug' => $page->slug_final,
                    'routable_type' => Entry::class,
                    'routable_id' => $entry->id,
                    'is_active' => $page->is_active
                ]);

                // Map PageBlocks
                DB::table('page_blocks')->where('page_type', 'service_city_page')->where('page_id', $page->id)
                    ->update(['page_type' => 'entry', 'page_id' => $entry->id]);
            }
            $this->info('Matrix Pages (ServiceCityPages) migrated.');

            // 10. Migrate Blog Categories & Tags & Posts
            $blogCatMap = [];
            if (Schema::hasTable('blog_categories')) {
                $blogCats = DB::table('blog_categories')->get();
                foreach ($blogCats as $cat) {
                    $term = Term::create([
                        'taxonomy_id' => $taxBlogCat->id,
                        'name' => $cat->name,
                        'slug' => $cat->slug,
                        'description' => $cat->description,
                    ]);
                    $blogCatMap[$cat->id] = $term->id;
                    
                    RouteAlias::create([
                        'slug' => 'blog/category/' . $cat->slug,
                        'routable_type' => Term::class,
                        'routable_id' => $term->id,
                        'is_active' => true
                    ]);
                }
            }

            $blogTagMap = [];
            if (Schema::hasTable('blog_tags')) {
                $blogTags = DB::table('blog_tags')->get();
                foreach ($blogTags as $tag) {
                    $term = Term::create([
                        'taxonomy_id' => $taxBlogTag->id,
                        'name' => $tag->name,
                        'slug' => $tag->slug,
                    ]);
                    $blogTagMap[$tag->id] = $term->id;
                    
                    RouteAlias::create([
                        'slug' => 'blog/tag/' . $tag->slug,
                        'routable_type' => Term::class,
                        'routable_id' => $term->id,
                        'is_active' => true
                    ]);
                }
            }

            if (Schema::hasTable('blog_posts')) {
                $blogPosts = DB::table('blog_posts')->get();
                foreach ($blogPosts as $post) {
                    $entry = Entry::create([
                        'content_type_id' => $ctBlog->id,
                        'title' => $post->title,
                        'slug' => $post->slug,
                        'status' => $post->status,
                        'author_id' => $post->author_id,
                        'published_at' => $post->published_at,
                        'data' => [
                            'excerpt' => $post->excerpt,
                            'body' => $post->body,
                            'content_json' => json_decode($post->content_json, true),
                            'featured_image_id' => $post->featured_image_id,
                            'meta_title' => $post->meta_title,
                            'meta_description' => $post->meta_description,
                        ]
                    ]);

                    if ($post->category_id && isset($blogCatMap[$post->category_id])) {
                        $entry->terms()->attach($blogCatMap[$post->category_id]);
                    }

                    if (Schema::hasTable('blog_post_tag')) {
                        $postTags = DB::table('blog_post_tag')->where('blog_post_id', $post->id)->get();
                        foreach ($postTags as $pt) {
                            if (isset($blogTagMap[$pt->blog_tag_id])) {
                                $entry->terms()->attach($blogTagMap[$pt->blog_tag_id]);
                            }
                        }
                    }

                    RouteAlias::create([
                        'slug' => 'blog/' . $post->slug,
                        'routable_type' => Entry::class,
                        'routable_id' => $entry->id,
                        'is_active' => $post->status === 'published'
                    ]);

                    if (Schema::hasTable('page_blocks')) {
                        DB::table('page_blocks')->where('page_type', 'blog_post')->where('page_id', $post->id)
                            ->update(['page_type' => 'entry', 'page_id' => $entry->id]);
                    }
                }
            }
            $this->info('Blog Posts migrated.');

            $this->info('All legacy data successfully ported to the Super WMS Engine!');
        });
    }
}
