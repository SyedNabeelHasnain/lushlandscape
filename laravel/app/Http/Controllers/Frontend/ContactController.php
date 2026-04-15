<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Form;
use App\Services\BlockBuilderService;
use App\Services\PageContextService;
use App\Services\SchemaService;

class ContactController extends Controller
{
    public function show()
    {
        $form = Form::where('slug', 'contact-us')->where('status', 'active')->with('fields')->firstOrFail();

        $breadcrumbs = [['label' => 'Contact']];
        $schema = SchemaService::breadcrumbList($breadcrumbs).SchemaService::localBusiness();
        $context = app(PageContextService::class)->listing('Contact', 'contact', url('/contact'));
        $blocks = BlockBuilderService::getBlocks('contact', 0);

        return view('frontend.pages.contact', compact('form', 'breadcrumbs', 'schema', 'blocks', 'context'));
    }

    public function quote()
    {
        $form = Form::where('slug', 'request-quote')->where('status', 'active')->with('fields')->firstOrFail();

        $breadcrumbs = [['label' => 'Project Consultation']];
        $schema = SchemaService::breadcrumbList($breadcrumbs).SchemaService::localBusiness();
        $context = app(PageContextService::class)->listing('Project Consultation', 'request-quote', url('/request-quote'));
        $blocks = BlockBuilderService::getBlocks('consultation', 0);

        return view('frontend.pages.request-quote', compact('form', 'breadcrumbs', 'schema', 'blocks', 'context'));
    }
}
