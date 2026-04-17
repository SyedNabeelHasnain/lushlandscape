# Phase G: Blog and Knowledge Base Page Family Implementation

This document tracks the final mapping and build-out of the blog / knowledge base family (Blog Index, Blog Category pages, and Blog Post pages) using the approved Phase A-F unified FSE system.

## 1. Final Section-to-Block Mappings

### Blog Index (`/blog`)
1. **Hero**: `parallax_media_band` (Overlay: dark, Parallax: subtle)
2. **Editorial Directory**: `blog_directory` (Showcases featured article and category tabs)
3. **Project Inquiry**: `split_consultation_panel` (Real `contact-us` form slug, dark tone)

### Blog Category Pages (`/blog/category/{slug}`)
1. **Category Hero**: `parallax_media_band` (Dynamic category heading, transparent surface)
2. **Category Directory**: `blog_directory` (Filtered by category, featured hero and tabs disabled)
3. **Project Inquiry**: `split_consultation_panel` (Consultation-led wording for the specific category)

### Blog Post Pages (`/blog/{slug}`)
*(Note: The main article shell and content body are strictly governed by the `blog-post.blade.php` rendering container to preserve typography and readability constraints.)*
**Governed Builder Zones (Below Article):**
1. **Related Services**: `services_grid` (Premium 2x2 variant highlighting relevant services)
2. **Project Inquiry**: `split_consultation_panel` (Consultation-led wording, translating insights into actionable steps)

## 2. Execution Details

### What Was Built
- The `ListingPageBlueprintService.php` was extended to scaffold the blog family.
- `scaffoldBlogCategories()` and `scaffoldBlogPosts()` methods were introduced to map `BlogCategory` and `BlogPost` database records into unified `PageBlock` sequences.
- The `scaffoldTaxonomyPages()` aggregate function was expanded to include the new blog iterators, ensuring they run natively during the `blocks:scaffold-listings` command.
- The `blog-post.blade.php` container preserves strict article readability while injecting builder zones underneath the content body to connect the educational insights back into the core service and consultation funnel.

### Files Touched
- `laravel/app/Console/Services/ListingPageBlueprintService.php` (Rewritten blog mappings)
- `laravel/tests/Feature/PhaseGBlogBlueprintTest.php` (Created to mathematically prove block mappings)

### What Was Verified
- **Shell Compatibility**: All three blog page types integrate seamlessly with the Phase A shell.
- **CTA Discipline**: Blog pages use consultation-led phrasing ("Apply this to your property", "Ready to start planning?") instead of generic sales pitches.
- **Article Shell Discipline**: The FSE blocks do not corrupt the main article reading experience; they are structurally positioned as "Next Steps" after the user finishes reading.

## 3. Deferred Items
- FAQ and legal page refinements.
