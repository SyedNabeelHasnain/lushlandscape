<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\RouteAlias;
use App\Models\Entry;
use App\Models\Term;
use Illuminate\Http\Request;

class EntityController extends Controller
{
    /**
     * Universal route resolver for the Super WMS.
     * O(1) lookup mapping URL directly to the corresponding polymorphic entity.
     */
    public function resolve(Request $request, $slug)
    {
        // 1. High-speed lookup in the RouteAlias table
        $alias = RouteAlias::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // 2. Resolve the underlying entity (Entry, Term, etc.)
        $entity = $alias->routable;

        if (!$entity) {
            abort(404);
        }

        // 3. Determine the rendering path based on the entity type
        if ($entity instanceof Entry) {
            return $this->renderEntry($entity, $request);
        }

        if ($entity instanceof Term) {
            return $this->renderTerm($entity, $request);
        }

        abort(404);
    }

    /**
     * Render a standard Content Entry.
     */
    protected function renderEntry(Entry $entry, Request $request)
    {
        // Check publish status
        if ($entry->status !== 'published') {
            abort(404);
        }

        $contentType = $entry->contentType;
        $template = $contentType->layout_template ?? 'default';

        $viewName = "frontend.entries.{$template}";
        if (!view()->exists($viewName)) {
            $viewName = 'frontend.entries.default'; // Fallback
        }

        // Fetch Unified Blocks
        $blocks = \App\Services\BlockBuilderService::getUnifiedBlocks('entry', $entry->id);

        // Generate generic context for WMS Variables
        $context = [
            'entry' => $entry->toArray(),
            'data' => $entry->data ? $entry->data->toArray() : [],
            'content_type' => $contentType->slug,
            'title' => $entry->title,
            'slug' => $entry->slug,
        ];

        // Generic SEO Schema fallback
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $entry->title,
            'url' => url($entry->slug),
        ];

        // Breadcrumbs fallback
        $breadcrumbs = [
            ['title' => 'Home', 'url' => url('/')],
            ['title' => $entry->title, 'url' => null],
        ];

        return view($viewName, [
            'entry' => $entry,
            'contentType' => $contentType,
            'data' => $entry->data,
            'blocks' => $blocks,
            'context' => $context,
            'schema' => json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }

    /**
     * Render a Taxonomy Term (Archive/Category Page).
     */
    protected function renderTerm(Term $term, Request $request)
    {
        $taxonomy = $term->taxonomy;
        
        $viewName = "frontend.taxonomies.{$taxonomy->slug}";
        if (!view()->exists($viewName)) {
            $viewName = 'frontend.taxonomies.default'; // Fallback
        }

        // Paginate the entries associated with this term
        $entries = $term->entries()->where('status', 'published')->paginate(12);

        return view($viewName, [
            'term' => $term,
            'taxonomy' => $taxonomy,
            'entries' => $entries,
        ]);
    }
}
