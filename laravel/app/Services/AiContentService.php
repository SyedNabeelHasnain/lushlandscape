<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiContentService
{
    public static function isAvailable(): bool
    {
        return Setting::get('ai_features_enabled', '0') === '1'
            && ! empty(Setting::get('openai_api_key', ''));
    }

    /**
     * Generate content via the OpenAI chat completions API.
     */
    public static function generate(
        string $fieldContext,
        ?string $currentValue = null,
        ?string $customInstructions = null,
        ?string $pageContext = null
    ): array {
        $apiKey = Setting::get('openai_api_key', '');
        $model = Setting::get('openai_model', 'gpt-4o');
        $temperature = (float) Setting::get('openai_temperature', '0.7');

        if (empty($apiKey)) {
            return ['success' => false, 'error' => 'OpenAI API key is not configured.'];
        }

        $systemPrompt = self::buildSystemPrompt();
        $userPrompt = self::buildUserPrompt($fieldContext, $currentValue, $customInstructions, $pageContext);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer '.$apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post(config('services.openai.base_url', 'https://api.openai.com/v1/chat/completions'), [
                'model' => config('services.openai.model', $model),
                'temperature' => $temperature,
                'max_tokens' => 2000,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
            ]);

            if ($response->failed()) {
                $error = $response->json('error.message', 'Unknown API error.');
                Log::error('OpenAI API error', ['status' => $response->status(), 'error' => $error]);

                return ['success' => false, 'error' => $error];
            }

            $content = $response->json('choices.0.message.content', '');

            return ['success' => true, 'content' => trim($content)];
        } catch (\Throwable $e) {
            Log::error('OpenAI API exception', ['message' => $e->getMessage()]);

            return ['success' => false, 'error' => 'Failed to connect to OpenAI API.'];
        }
    }

    private static function buildSystemPrompt(): string
    {
        $contextDoc = Setting::get('ai_context_markdown', '');
        $brandRules = implode("\n", [
            'You are a content writer for Super WMS, a premium professional construction company in Our Region, Canada.',
            'Write in a professional yet conversational tone, speaking directly to the customer using "you" and "your".',
            'Never use em dashes. Never use emojis.',
            'Content must be factually accurate and hyper-localized when city context is provided.',
            'Keep content concise and conversion-oriented.',
            'Do not include any markdown formatting unless the field specifically requires it.',
            'Return only the generated content, no explanations or preamble.',
        ]);

        $prompt = $brandRules;
        if (! empty($contextDoc)) {
            $prompt .= "\n\n--- Website Context ---\n".$contextDoc;
        }

        return $prompt;
    }

    private static function buildUserPrompt(
        string $fieldContext,
        ?string $currentValue,
        ?string $customInstructions,
        ?string $pageContext
    ): string {
        $parts = ["Generate content for: {$fieldContext}"];

        if (! empty($pageContext)) {
            $parts[] = "Page context: {$pageContext}";
        }

        if (! empty($currentValue)) {
            $parts[] = "Current value (improve or replace this): {$currentValue}";
        }

        if (! empty($customInstructions)) {
            $parts[] = "Additional instructions: {$customInstructions}";
        }

        return implode("\n\n", $parts);
    }
}
