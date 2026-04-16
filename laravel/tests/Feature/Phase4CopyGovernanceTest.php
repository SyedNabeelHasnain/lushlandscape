<?php

namespace Tests\Feature;

use Tests\TestCase;

class Phase4CopyGovernanceTest extends TestCase
{
    public function test_frontend_consultation_page_is_consultation_led(): void
    {
        $path = base_path('resources/views/frontend/pages/request-quote.blade.php');
        $contents = file_get_contents($path);

        $this->assertIsString($contents);
        $this->assertStringContainsString('Project Consultation', $contents);
        $this->assertStringContainsString('Request a Consultation', $contents);
    }

    public function test_forbidden_quote_language_is_not_present_in_public_facing_defaults(): void
    {
        $roots = [
            base_path('resources/views/frontend'),
            base_path('database/seeders'),
            base_path('app/Console/Services'),
            base_path('app/Services'),
            base_path('config'),
        ];

        $forbidden = [
            'free estimate',
            'free estimates',
            'free quote',
            'free quotes',
            'get a quote',
            'request a quote',
            'quote request',
            'no obligation',
            'no-obligation',
            'seasonal deals',
            'transparent pricing',
        ];

        $matches = [];
        foreach ($roots as $root) {
            $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($root));

            foreach ($iterator as $file) {
                if (! $file instanceof \SplFileInfo || ! $file->isFile()) {
                    continue;
                }

                $path = (string) $file->getPathname();
                if (! preg_match('/\.(php|blade\.php|md)$/', $path)) {
                    continue;
                }

                $contents = file_get_contents($path);
                if ($contents === false) {
                    continue;
                }

                $haystack = strtolower($contents);
                foreach ($forbidden as $needle) {
                    if (str_contains($haystack, $needle)) {
                        $matches[] = $path.' :: '.$needle;
                        break;
                    }
                }
            }
        }

        $this->assertSame([], $matches);
    }
}
