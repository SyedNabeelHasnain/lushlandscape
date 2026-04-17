import re

file_path = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php'
with open(file_path, 'r') as f:
    content = f.read()

scaffold_blog_methods = r"""
    private function scaffoldBlogCategories(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedBlogCategories() as $category) {
            $existingBlocks = \App\Models\PageBlock::forPage('blog_category', $category->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $category->id,
                    'slug' => $category->slug,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];
                continue;
            }

            $blocks = $this->buildBlogCategory($category);

            if ($replace) {
                \App\Services\BlockBuilderService::deleteAllBlocksForPage('blog_category', $category->id);
            }

            \App\Services\BlockBuilderService::saveUnifiedBlocks('blog_category', $category->id, $blocks);

            $results[] = [
                'id' => $category->id,
                'slug' => $category->slug,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function scaffoldBlogPosts(bool $replace): array
    {
        $results = [];

        foreach ($this->publishedBlogPosts() as $post) {
            $existingBlocks = \App\Models\PageBlock::forPage('blog_post', $post->id)->count();

            if (! $replace && $existingBlocks > 0) {
                $results[] = [
                    'id' => $post->id,
                    'slug' => $post->slug,
                    'applied' => false,
                    'replaced' => false,
                    'existing_blocks' => $existingBlocks,
                    'block_count' => 0,
                    'reason' => 'existing_content',
                ];
                continue;
            }

            $blocks = $this->buildBlogPost($post);

            if ($replace) {
                \App\Services\BlockBuilderService::deleteAllBlocksForPage('blog_post', $post->id);
            }

            \App\Services\BlockBuilderService::saveUnifiedBlocks('blog_post', $post->id, $blocks);

            $results[] = [
                'id' => $post->id,
                'slug' => $post->slug,
                'applied' => true,
                'replaced' => $replace,
                'existing_blocks' => $existingBlocks,
                'block_count' => count($blocks),
                'reason' => 'scaffolded',
            ];
        }

        return $results;
    }

    private function publishedBlogCategories(): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\BlogCategory::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }

    private function publishedBlogPosts(): \Illuminate\Support\Collection
    {
        try {
            return \App\Models\BlogPost::query()->where('status', 'published')->get();
        } catch (\Exception $e) {
            return collect();
        }
    }
"""

content = re.sub(r'    private function scaffoldBlogCategories\(bool \$replace\): array\n    \{.*?(?=    private function showcaseMediaPair|\Z)', scaffold_blog_methods + "\n", content, flags=re.DOTALL)


scaffold_taxonomies = r"""
    public function scaffoldTaxonomyPages(bool $replace = false): array
    {
        return [
            'service_categories' => $this->scaffoldServiceCategories($replace),
            'services' => $this->scaffoldServices($replace),
            'cities' => $this->scaffoldCities($replace),
            'service_cities' => $this->scaffoldServiceCities($replace),
            'portfolio_categories' => $this->scaffoldPortfolioCategories($replace),
            'portfolio_projects' => $this->scaffoldPortfolioProjects($replace),
            'blog_categories' => $this->scaffoldBlogCategories($replace),
            'blog_posts' => $this->scaffoldBlogPosts($replace),
        ];
    }
"""
content = re.sub(r'    public function scaffoldTaxonomyPages\(bool \$replace = false\): array\n    \{.*?\n    \}\n', scaffold_taxonomies, content, flags=re.DOTALL)


build_blog_methods = r"""
    private function buildBlogIndex(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Landscaping Knowledge & Advice',
                    'subheadline' => 'Expert tips, cost guides, and project inspiration for Ontario homeowners.',
                    'media_id' => $heroMediaId,
                    'parallax_intensity' => 'subtle',
                    'overlay_preset' => 'dark',
                ],
                $this->styles([
                    'spacing_preset' => 'none',
                    'padding_top' => 'none',
                    'padding_bottom' => 'none',
                    'margin_bottom' => 'none',
                    'max_width' => 'full',
                    'surface_preset' => 'transparent',
                ]),
                customId: 'blog-hero'
            ),
            $this->block(
                'blog_directory',
                [
                    'eyebrow' => 'Editorial & Advice',
                    'heading' => 'Explore the Lush Landscape Blog',
                    'subtitle' => 'Browse our latest articles to help you plan, budget, and execute your outdoor space.',
                    'tone' => 'light',
                    'show_featured_hero' => true,
                    'show_category_tabs' => true,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'blog-directory'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Next Steps',
                    'heading' => 'Ready to Start Planning?',
                    'editorial_copy' => 'If our articles have inspired you, connect with our team to discuss how we can bring those ideas to your property.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'contact-us',
                    'tone' => 'dark',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'none',
                    'padding_top' => 'none',
                    'padding_bottom' => 'none',
                    'margin_bottom' => 'none',
                    'surface_preset' => 'transparent',
                ]),
                customId: 'blog-contact'
            ),
        ];
    }

    public function buildBlogCategory(\App\Models\BlogCategory $category): array
    {
        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => $category->name . ' Articles',
                    'subheadline' => $category->short_description ?: 'Explore our expert advice and guidance regarding ' . strtolower($category->name) . '.',
                    'media_id' => $category->image_id,
                    'parallax_intensity' => 'subtle',
                    'overlay_preset' => 'dark',
                ],
                $this->styles([
                    'spacing_preset' => 'none',
                    'padding_top' => 'none',
                    'padding_bottom' => 'none',
                    'margin_bottom' => 'none',
                    'max_width' => 'full',
                    'surface_preset' => 'transparent',
                ]),
                customId: 'blog-category-hero'
            ),
            $this->block(
                'blog_directory',
                [
                    'eyebrow' => $category->name,
                    'heading' => 'Articles about ' . $category->name,
                    'subtitle' => 'Browse our latest insights and project advice for ' . strtolower($category->name) . '.',
                    'tone' => 'cream',
                    'show_featured_hero' => false,
                    'show_category_tabs' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'blog-category-directory'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Next Steps',
                    'heading' => 'Need advice on ' . strtolower($category->name) . '?',
                    'editorial_copy' => 'Connect with our team to discuss your specific requirements and receive expert guidance tailored to your property.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'contact-us',
                    'tone' => 'dark',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'none',
                    'padding_top' => 'none',
                    'padding_bottom' => 'none',
                    'margin_bottom' => 'none',
                    'surface_preset' => 'transparent',
                ]),
                customId: 'blog-category-contact'
            ),
        ];
    }

    public function buildBlogPost(\App\Models\BlogPost $post): array
    {
        return [
            $this->block(
                'services_grid',
                [
                    'eyebrow' => 'Related Services',
                    'heading' => 'Relevant Services',
                    'subtitle' => 'Explore professional services related to this article.',
                    'layout' => 'grid',
                    'columns' => '3',
                    'variant' => 'premium-2x2',
                    'show_icon' => true,
                    'show_divider' => true,
                    'show_usp_list' => false,
                    'card_cta_label' => 'View Service',
                    'tone' => 'cream',
                    'show_category_nav' => false,
                    'show_view_all' => false,
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'post-services'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Project Inquiry',
                    'heading' => 'Apply this to your property',
                    'editorial_copy' => 'If this article sparked an idea, connect with our team to discuss how we can execute it professionally on your property.',
                    'trust_lines' => 'Comprehensive property assessment, Expert design and material advice, Clear execution timelines',
                    'media_id' => null,
                    'form_slug' => 'contact-us',
                    'tone' => 'dark',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'none',
                    'padding_top' => 'none',
                    'padding_bottom' => 'none',
                    'margin_bottom' => 'none',
                    'surface_preset' => 'transparent',
                ]),
                customId: 'post-contact'
            ),
        ];
    }
"""

content = re.sub(r'    private function buildBlogIndex\(\): array\n    \{.*?(?=    private function getPlaceholderImageId|\Z)', build_blog_methods + "\n\n", content, flags=re.DOTALL)

with open(file_path, 'w') as f:
    f.write(content)
print("Updated blog methods successfully.")
