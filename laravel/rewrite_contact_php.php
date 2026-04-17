<?php

$file = '/Users/syednabeelhasnain/Nabeel Dev/Lush 2.0/Lush/laravel/app/Console/Services/ListingPageBlueprintService.php';
$content = file_get_contents($file);

$buildMethods = <<<'PHP'
    private function buildContact(): array
    {
        return [
            $this->block(
                'faq_section',
                [
                    'heading' => 'Common Inquiries',
                    'subtitle' => 'Before we connect, you might find these answers helpful.',
                    'style' => 'list',
                ],
                $this->styles([
                    'spacing_preset' => 'section',
                    'max_width' => 'xl',
                    'surface_preset' => 'cream',
                ]),
                customId: 'contact-faqs',
                dataSource: [
                    'limit' => 4,
                ]
            ),
        ];
    }

    private function buildConsultation(): array
    {
        return [
            $this->block(
                'process_steps',
                [
                    'eyebrow' => 'Our Approach',
                    'heading' => 'What to Expect',
                    'subtitle' => 'A clear, professional process from consultation to project completion.',
                    'variant' => 'numbered',
                    'tone' => 'cream',
                ],
                $this->styles([
                    'max_width' => 'xl',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'cream',
                ]),
                customId: 'consultation-process'
            ),
        ];
    }

    private function buildFaqIndex(): array
    {
        [$heroMediaId] = $this->showcaseMediaPair();

        return [
            $this->block(
                'parallax_media_band',
                [
                    'heading' => 'Frequently Asked Questions',
                    'subheadline' => 'Clear answers regarding our services, processes, and what to expect when working with Lush Landscaping.',
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
                customId: 'faq-hero'
            ),
            $this->block(
                'faq_directory',
                [
                    'eyebrow' => 'Help & Support',
                    'heading' => 'Find the answers you need',
                    'subtitle' => 'Browse our FAQ categories below or use the search to find specific information.',
                    'tone' => 'light',
                ],
                $this->styles([
                    'max_width' => 'full',
                    'spacing_preset' => 'section',
                    'surface_preset' => 'white',
                ]),
                customId: 'faq-directory'
            ),
            $this->block(
                'split_consultation_panel',
                [
                    'eyebrow' => 'Still Have Questions?',
                    'heading' => 'Talk to our team directly',
                    'editorial_copy' => 'If you couldn\'t find the answer you were looking for, or if you\'re ready to start discussing your specific property, reach out to us.',
                    'trust_lines' => 'Clear communication, Expert advice, Timely responses',
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
                customId: 'faq-contact'
            ),
        ];
    }
PHP;

$content = preg_replace('/}\s*$/s', "\n" . $buildMethods . "\n}\n", $content);

file_put_contents($file, $content);
echo "Replaced Contact/Consultation/FAQ methods successfully using PHP.\n";
