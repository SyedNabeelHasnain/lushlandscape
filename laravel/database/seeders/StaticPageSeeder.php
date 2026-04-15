<?php

namespace Database\Seeders;

use App\Models\StaticPage;
use Illuminate\Database\Seeder;

class StaticPageSeeder extends Seeder
{
    public function run(): void
    {
        $pages = [
            ['title' => 'About Us', 'slug' => 'about', 'page_type' => 'about', 'template' => 'about', 'status' => 'draft', 'sort_order' => 1],
            ['title' => 'Our Process', 'slug' => 'process', 'page_type' => 'process', 'template' => 'process', 'status' => 'draft', 'sort_order' => 2],
            ['title' => 'Warranty & Maintenance', 'slug' => 'warranty', 'page_type' => 'warranty', 'template' => 'default', 'status' => 'draft', 'sort_order' => 3],
            ['title' => 'Financing', 'slug' => 'financing', 'page_type' => 'financing', 'template' => 'default', 'status' => 'draft', 'sort_order' => 4],
            ['title' => 'Permits & Regulations', 'slug' => 'permits', 'page_type' => 'permits', 'template' => 'default', 'status' => 'draft', 'sort_order' => 5],
            ['title' => 'Awards & Certifications', 'slug' => 'awards', 'page_type' => 'awards', 'template' => 'default', 'status' => 'draft', 'sort_order' => 6],
            ['title' => 'Reviews', 'slug' => 'reviews', 'page_type' => 'reviews', 'template' => 'reviews', 'status' => 'draft', 'sort_order' => 7],
            ['title' => 'Careers', 'slug' => 'careers', 'page_type' => 'careers', 'template' => 'default', 'status' => 'draft', 'sort_order' => 8],
            ['title' => 'Referral Program', 'slug' => 'referral-program', 'page_type' => 'referral', 'template' => 'default', 'status' => 'draft', 'sort_order' => 9],
            ['title' => 'Privacy Policy', 'slug' => 'privacy-policy', 'page_type' => 'legal', 'template' => 'legal', 'status' => 'draft', 'sort_order' => 10],
            ['title' => 'Terms & Conditions', 'slug' => 'terms', 'page_type' => 'legal', 'template' => 'legal', 'status' => 'draft', 'sort_order' => 11],
        ];

        foreach ($pages as $pageData) {
            StaticPage::updateOrCreate(
                ['slug' => $pageData['slug']],
                $pageData
            );
        }
    }
}
