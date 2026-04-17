<?php

$file = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php';
$content = file_get_contents($file);

$scaffoldServicesMethod = "
    /**
     * @return array<int, array<string, mixed>>
     */
    private function scaffoldServices(bool \$replace): array
    {
        \$results = [];

        foreach (\$this->publishedServices() as \$service) {
            \$existingBlocks = PageBlock::forPage('service', \$service->id)->count();

            if (! \$replace && \$existingBlocks > 0) {
                \$results[] = [
                    'id' => \$service->id,
                    'slug' => \$service->slug_final,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => \$existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];

                continue;
            }

            \$blocks = \$this->buildServiceDetail(\$service);

            if (\$replace) {
                BlockBuilderService::deleteAllBlocksForPage('service', \$service->id);
            }

            BlockBuilderService::saveUnifiedBlocks('service', \$service->id, \$blocks);

            \$results[] = [
                'id' => \$service->id,
                'slug' => \$service->slug_final,
                'applied' => true,
                'replaced' => \$replace,
                'existing_blocks' => \$existingBlocks,
                'block_count' => count(\$blocks),
                'reason' => 'scaffolded',
            ];
        }

        return \$results;
    }
";

$search = 'private function scaffoldServiceCategories(bool $replace): array';
$content = str_replace($search, $scaffoldServicesMethod."\n    ".$search, $content);
file_put_contents($file, $content);
echo "Added scaffoldServices.\n";
