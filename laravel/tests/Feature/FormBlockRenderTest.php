<?php

namespace Tests\Feature;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class FormBlockRenderTest extends TestCase
{
    public function test_form_fields_partial_renders_widths_help_text_verification_and_checkbox_groups(): void
    {
        $form = (object) [
            'slug' => 'consultation',
            'requires_email_verification' => true,
            'fields' => new Collection([
                (object) [
                    'name' => 'full_name',
                    'label' => 'Full Name',
                    'type' => 'text',
                    'placeholder' => 'John Doe',
                    'help_text' => 'Tell us who we should contact.',
                    'is_required' => true,
                    'width' => 'half',
                    'options' => null,
                ],
                (object) [
                    'name' => 'email',
                    'label' => 'Email Address',
                    'type' => 'email',
                    'placeholder' => 'name@example.com',
                    'help_text' => null,
                    'is_required' => true,
                    'width' => 'half',
                    'options' => null,
                ],
                (object) [
                    'name' => 'project_goals',
                    'label' => 'Project Goals',
                    'type' => 'checkbox',
                    'placeholder' => null,
                    'help_text' => 'Select all that apply.',
                    'is_required' => false,
                    'width' => 'full',
                    'options' => ['Driveway', 'Patio', 'Lighting'],
                ],
                (object) [
                    'name' => 'details',
                    'label' => 'Project Details',
                    'type' => 'textarea',
                    'placeholder' => 'Describe the vision.',
                    'help_text' => 'Include timing, scope, and materials.',
                    'is_required' => true,
                    'width' => 'full',
                    'options' => null,
                ],
            ]),
        ];

        $html = Blade::render(
            file_get_contents(resource_path('views/frontend/blocks/partials/_form-fields.blade.php')),
            [
                'form' => $form,
                'formId' => 'contact-form',
                'variant' => 'split',
                'tone' => 'light',
                'labelClass' => 'text-text-secondary',
                'fieldToneClass' => 'bg-white border-stone text-ink placeholder:text-text-secondary',
                'fieldStyleClass' => 'field-luxury-soft',
                'fieldColumns' => 'auto',
                'buttonClass' => 'btn-luxury btn-luxury-primary',
                'submitText' => 'Send Inquiry',
            ]
        );

        $this->assertStringContainsString('md:col-span-6', $html);
        $this->assertStringContainsString('Tell us who we should contact.', $html);
        $this->assertStringContainsString('Verify', $html);
        $this->assertStringContainsString('project_goals[]', $html);
        $this->assertStringContainsString('Include timing, scope, and materials.', $html);
        $this->assertStringContainsString('Please verify your email before submitting this form.', $html);
    }

    public function test_split_form_block_falls_back_cleanly_when_optional_contact_fields_are_missing(): void
    {
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
        ]);

        DB::purge('sqlite');
        DB::reconnect('sqlite');

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group')->nullable();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->nullable();
            $table->string('label')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('form_type')->default('general');
            $table->text('description')->nullable();
            $table->text('success_message')->nullable();
            $table->json('email_to')->nullable();
            $table->json('email_cc')->nullable();
            $table->json('email_bcc')->nullable();
            $table->boolean('requires_email_verification')->default(false);
            $table->boolean('honeypot_enabled')->default(true);
            $table->string('status')->default('draft');
            $table->timestamps();
        });

        Schema::create('form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('form_id');
            $table->string('name');
            $table->string('label');
            $table->string('type')->default('text');
            $table->string('placeholder')->nullable();
            $table->text('help_text')->nullable();
            $table->json('options')->nullable();
            $table->json('validation_rules')->nullable();
            $table->boolean('is_required')->default(false);
            $table->string('width')->default('full');
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        DB::table('forms')->insert([
            'id' => 1,
            'name' => 'Project Inquiry',
            'slug' => 'consultation',
            'form_type' => 'consultation',
            'status' => 'active',
            'requires_email_verification' => false,
            'honeypot_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('form_fields')->insert([
            'form_id' => 1,
            'name' => 'full_name',
            'label' => 'Full Name',
            'type' => 'text',
            'placeholder' => 'John Doe',
            'is_required' => true,
            'width' => 'full',
            'sort_order' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $html = Blade::render(
            file_get_contents(resource_path('views/frontend/blocks/partials/form-block.blade.php')),
            [
                'block' => (object) ['id' => 99],
                'content' => [
                    'form_slug' => 'consultation',
                    'variant' => 'split',
                    'heading' => 'Start Your Landscape Transformation',
                    'description' => 'Schedule an on-site consultation to discuss your vision.',
                    'show_contact_details' => true,
                ],
            ]
        );

        $this->assertStringContainsString('Start Your Landscape Transformation', $html);
        $this->assertStringContainsString('Schedule an on-site consultation to discuss your vision.', $html);
        $this->assertStringContainsString('Full Name', $html);

        Schema::dropIfExists('form_fields');
        Schema::dropIfExists('forms');
        Schema::dropIfExists('settings');
    }
}
