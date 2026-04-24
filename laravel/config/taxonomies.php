<?php

use App\Models\FaqCategory;
use App\Models\ReviewCategory;
use App\Models\Term;

/**
 * Taxonomy Registry — drives TaxonomyCrudController and admin sidebar/views.
 *
 * Keys match the admin route prefix (e.g. 'blog-categories' → /admin/blog-categories/*).
 * Required keys per entry:
 *   model         – fully-qualified Eloquent model class
 *   label         – plural human label (used in headings, sidebar)
 *   singular      – singular human label (used in flash messages)
 *   icon          – Lucide icon name for sidebar link
 *   post_rel      – relationship method name on the taxonomy model (for withCount + delete guard)
 *   has_icon      – whether the icon field is shown in the form
 *   has_language  – whether the language selector is shown in the form
 *   schema_default– default schema.org type pre-filled in the form
 */
return [

    'blog-categories' => [
        'model' => Term::class,
        'label' => 'Blog Categories',
        'singular' => 'Blog Category',
        'icon' => 'folder',
        'post_rel' => 'entries',
        'has_icon' => false,
        'has_language' => false,
        'schema_default' => 'CollectionPage',
        'supports_page_builder' => true,
        'frontend_route' => 'blog.category',
    ],

    'portfolio-categories' => [
        'model' => Term::class,
        'label' => 'Portfolio Categories',
        'singular' => 'Portfolio Category',
        'icon' => 'layers',
        'post_rel' => 'entries',
        'has_icon' => true,
        'has_language' => false,
        'schema_default' => 'ItemList',
        'supports_page_builder' => true,
        'frontend_route' => 'portfolio.category',
    ],

    'faq-categories' => [
        'model' => FaqCategory::class,
        'label' => 'FAQ Categories',
        'singular' => 'FAQ Category',
        'icon' => 'help-circle',
        'post_rel' => 'faqs',
        'has_icon' => true,
        'has_language' => true,
        'schema_default' => 'FAQPage',
        'supports_page_builder' => false,
        'frontend_route' => null,
    ],

    'review-categories' => [
        'model' => ReviewCategory::class,
        'label' => 'Review Categories',
        'singular' => 'Review Category',
        'icon' => 'star',
        'post_rel' => 'reviews',
        'has_icon' => false,
        'has_language' => false,
        'schema_default' => 'ItemList',
        'supports_page_builder' => false,
        'frontend_route' => null,
    ],

];
