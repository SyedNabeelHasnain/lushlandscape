<?php

// Update ListingPageBlueprintService.php

$file = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php';
$content = file_get_contents($file);

// 1. Add scaffoldServices to scaffoldTaxonomyPages
$search = "'service_categories' => \$this->scaffoldServiceCategories(\$replace),";
$replace = "'service_categories' => \$this->scaffoldServiceCategories(\$replace),\n            'services' => \$this->scaffoldServices(\$replace),";
$content = str_replace($search, $replace, $content);

// 2. Add publishedServices() helper
$publishedCategoriesMethod = 'private function publishedServiceCategories(): Collection';
$publishedServicesMethod = "
    private function publishedServices(): Collection
    {
        try {
            return \App\Models\Service::query()
                ->where('status', 'published')
                ->get();
        } catch (\Exception \$e) {
            return collect();
        }
    }

    ";
$content = str_replace($publishedCategoriesMethod, $publishedServicesMethod.$publishedCategoriesMethod, $content);

file_put_contents($file, $content);
echo "Updated helpers.\n";
