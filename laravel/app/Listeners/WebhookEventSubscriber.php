<?php

declare(strict_types=1);

namespace App\Listeners;

use App\Jobs\DispatchWebhook;
use App\Models\Webhook;
use Illuminate\Events\Dispatcher;

class WebhookEventSubscriber
{
    /**
     * Handle the event.
     */
    public function handle(string $event, mixed $payload = null): void
    {
        // When using subscribe with class string in Laravel, the event name is passed first,
        // followed by an array of arguments (the payload).
        $actualPayload = is_array($payload) && count($payload) > 0 ? $payload[0] : $payload;

        $webhooks = Webhook::where('is_active', true)
            ->where('event', $event)
            ->get();

        foreach ($webhooks as $webhook) {
            $data = is_array($actualPayload) ? ($actualPayload[0] ?? $actualPayload) : $actualPayload;

            if (is_object($data) && method_exists($data, 'toArray')) {
                $data = $data->toArray();
            } elseif (is_object($data)) {
                $data = (array) $data;
            }

            DispatchWebhook::dispatch($webhook, $event, (array) $data);
        }
    }

    /**
     * Register the listeners for the subscriber.
     */
    public function subscribe(Dispatcher $events): array
    {
        return [
            'entry.saved' => 'handle',
            'entry.deleted' => 'handle',
            'form.submitted' => 'handle',
            'system.deploy' => 'handle',
        ];
    }
}
