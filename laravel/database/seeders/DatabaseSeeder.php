<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // Core data
            AdminUserSeeder::class,
            ServiceCategorySeeder::class,
            CitySeeder::class,
            SettingSeeder::class,
            FormSeeder::class,
            StaticPageSeeder::class,

            // Content enrichment (run after core data)
            CityContentSeeder::class,
            NeighborhoodSeeder::class,
            CityServiceRelationSeeder::class,

            // Service-city pages (140 pages across 10 cities)
            Content\MississaugaContentSeeder::class,
            Content\HamiltonContentSeeder::class,
            Content\BurlingtonContentSeeder::class,
            Content\OakvilleContentSeeder::class,
            Content\MiltonContentSeeder::class,
            Content\TorontoContentSeeder::class,
            Content\VaughanContentSeeder::class,
            Content\RichmondHillContentSeeder::class,
            Content\GeorgetownContentSeeder::class,
            Content\BramptonContentSeeder::class,

            // FAQs (700 localized FAQs assigned to service-city pages)
            FaqContentSeeder::class,

            // General, compliance, billing, booking, and city-specific FAQs
            FaqGeneralSeeder::class,

            // Portfolio projects (42 projects across 14 services and 10 cities)
            PortfolioSeeder::class,

            // Home page content blocks (6 blocks: intro, features, stats, CTA, areas, map)
            HomePageContentSeeder::class,
            ConsultationPageSeeder::class,
            PortfolioPageSeeder::class,

            // Interactive map blocks for city + service-city pages
            MapContentSeeder::class,

            // Static page content (10 pages: about, process, warranty, financing, permits, awards, careers, referral, privacy, terms)
            StaticPageContentSeeder::class,

            // Media metadata (placeholder records for future image uploads)
            MediaMetadataSeeder::class,
        ]);
    }
}
