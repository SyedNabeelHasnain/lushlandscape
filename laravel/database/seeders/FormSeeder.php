<?php

namespace Database\Seeders;

use App\Models\Form;
use App\Models\FormField;
use Illuminate\Database\Seeder;

class FormSeeder extends Seeder
{
    public function run(): void
    {
        $contactForm = Form::updateOrCreate(
            ['slug' => 'contact-us'],
            [
                'name' => 'Contact Us',
                'form_type' => 'contact',
                'description' => 'General contact form',
                'success_message' => 'Thank you for reaching out. We will get back to you within 24 hours.',
                'email_to' => ['info@lushlandscape.ca'],
                'requires_email_verification' => true,
                'honeypot_enabled' => true,
            ]
        );

        $this->syncFields($contactForm, [
            ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'is_required' => true, 'width' => 'half', 'sort_order' => 1],
            ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'is_required' => true, 'width' => 'half', 'sort_order' => 2],
            ['name' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'is_required' => true, 'width' => 'half', 'sort_order' => 3],
            ['name' => 'city', 'label' => 'City', 'type' => 'select', 'is_required' => true, 'width' => 'half', 'sort_order' => 4, 'options' => ['Hamilton', 'Burlington', 'Oakville', 'Mississauga', 'Milton', 'Toronto', 'Vaughan', 'Richmond Hill', 'Georgetown', 'Brampton']],
            ['name' => 'message', 'label' => 'Your Message', 'type' => 'textarea', 'is_required' => true, 'width' => 'full', 'sort_order' => 5],
        ]);

        $quoteForm = Form::updateOrCreate(
            ['slug' => 'request-quote'],
            [
                'name' => 'Project Inquiry',
                'form_type' => 'quote',
                'description' => 'Project inquiry and consultation request',
                'success_message' => 'Thank you for your inquiry. Our team will review your submission and follow up with next steps.',
                'email_to' => ['info@lushlandscape.ca'],
                'requires_email_verification' => true,
                'honeypot_enabled' => true,
            ]
        );

        $this->syncFields($quoteForm, [
            ['name' => 'full_name', 'label' => 'Full Name', 'type' => 'text', 'is_required' => true, 'width' => 'half', 'sort_order' => 1],
            ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'is_required' => true, 'width' => 'half', 'sort_order' => 2],
            ['name' => 'phone', 'label' => 'Phone Number', 'type' => 'tel', 'is_required' => true, 'width' => 'half', 'sort_order' => 3],
            ['name' => 'city', 'label' => 'City', 'type' => 'select', 'is_required' => true, 'width' => 'half', 'sort_order' => 4, 'options' => ['Hamilton', 'Burlington', 'Oakville', 'Mississauga', 'Milton', 'Toronto', 'Vaughan', 'Richmond Hill', 'Georgetown', 'Brampton']],
            ['name' => 'service', 'label' => 'Project Scope', 'type' => 'select', 'is_required' => true, 'width' => 'half', 'sort_order' => 5, 'options' => ['Front Entrance and Driveway', 'Rear Yard and Outdoor Living', 'Full Property Transformation', 'Structural Hardscape and Retaining', 'Corrective Repair and Restoration', 'Other']],
            ['name' => 'property_type', 'label' => 'Property Type', 'type' => 'select', 'is_required' => false, 'width' => 'half', 'sort_order' => 6, 'options' => ['Private Residence', 'Estate Property', 'New Build Residence', 'Ravine Lot', 'Waterfront Property', 'Other']],
            ['name' => 'project_details', 'label' => 'Project Summary', 'type' => 'textarea', 'is_required' => true, 'width' => 'full', 'sort_order' => 7, 'placeholder' => 'Tell us what you are planning, the timeline you are aiming for, and any material preferences.'],
            ['name' => 'preferred_contact', 'label' => 'Preferred Contact Method', 'type' => 'select', 'is_required' => false, 'width' => 'half', 'sort_order' => 8, 'options' => ['Phone', 'Email', 'Either']],
        ]);

        $subscriberForm = Form::updateOrCreate(
            ['slug' => 'subscribe'],
            [
                'name' => 'Newsletter Subscriber',
                'form_type' => 'subscriber',
                'description' => 'Newsletter subscription form',
                'success_message' => 'You have been subscribed successfully.',
                'email_to' => ['info@lushlandscape.ca'],
                'requires_email_verification' => true,
                'honeypot_enabled' => true,
            ]
        );

        $this->syncFields($subscriberForm, [
            ['name' => 'email', 'label' => 'Email Address', 'type' => 'email', 'is_required' => true, 'width' => 'full', 'sort_order' => 1, 'placeholder' => 'Enter your email for tips and updates'],
        ]);
    }

    // Upsert fields by name within a form
    private function syncFields(Form $form, array $fields): void
    {
        foreach ($fields as $field) {
            FormField::updateOrCreate(
                ['form_id' => $form->id, 'name' => $field['name']],
                $field
            );
        }
    }
}
