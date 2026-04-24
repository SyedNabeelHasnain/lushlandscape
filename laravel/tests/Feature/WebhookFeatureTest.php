<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Jobs\DispatchWebhook;
use App\Models\ContentType;
use App\Models\Entry;
use App\Models\Webhook;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WebhookFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_entry_saved_event_dispatches_webhook_job()
    {
        Queue::fake();

        $webhook = Webhook::create([
            'name' => 'Test Webhook',
            'url' => 'https://example.com/webhook',
            'event' => 'entry.saved',
            'is_active' => true,
        ]);

        $ct = ContentType::create([
            'name' => 'Test',
            'slug' => 'test',
        ]);

        $entry = Entry::create([
            'content_type_id' => $ct->id,
            'title' => 'Test Entry',
            'slug' => 'test-entry',
            'status' => 'draft',
        ]);

        event('entry.saved', [$entry]);

        Queue::assertPushed(DispatchWebhook::class, function ($job) use ($webhook) {
            return $job->webhook->id === $webhook->id && $job->event === 'entry.saved';
        });
    }

    public function test_dispatch_webhook_job_sends_http_request()
    {
        Http::fake([
            'example.com/*' => Http::response(['ok' => true], 200),
        ]);

        $webhook = Webhook::create([
            'name' => 'Test Webhook',
            'url' => 'https://example.com/webhook',
            'event' => 'test.event',
            'is_active' => true,
            'secret' => 'supersecret',
            'headers' => ['X-Custom' => 'Value'],
        ]);

        $job = new DispatchWebhook($webhook, 'test.event', ['data' => 'test']);
        $job->handle();

        Http::assertSent(function (Request $request) {
            return $request->url() === 'https://example.com/webhook' &&
                   $request['data'] === 'test' &&
                   $request->hasHeader('X-Custom', 'Value') &&
                   $request->hasHeader('X-WMS-Event', 'test.event') &&
                   $request->hasHeader('X-WMS-Signature');
        });

        $this->assertDatabaseHas('webhook_deliveries', [
            'webhook_id' => $webhook->id,
            'status_code' => 200,
            'is_successful' => true,
        ]);
    }
}
