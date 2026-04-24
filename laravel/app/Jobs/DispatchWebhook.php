<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Webhook;
use App\Models\WebhookDelivery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DispatchWebhook implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $webhook;
    public $payload;
    public $event;

    /**
     * Create a new job instance.
     */
    public function __construct(Webhook $webhook, string $event, array $payload)
    {
        $this->webhook = $webhook;
        $this->event = $event;
        $this->payload = $payload;
        
        // Use the webhook's configured retry count
        $this->tries = $webhook->retry_count + 1;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->webhook->is_active) {
            return;
        }

        $headers = $this->webhook->headers ?? [];
        $headers['User-Agent'] = 'SuperWMS/1.0';
        $headers['X-WMS-Event'] = $this->event;

        $jsonPayload = json_encode($this->payload);

        if ($this->webhook->secret) {
            $signature = hash_hmac('sha256', $jsonPayload, $this->webhook->secret);
            $headers['X-WMS-Signature'] = $signature;
        }

        try {
            $timeout = (int) ($this->webhook->timeout ?? 5);
            $response = Http::timeout($timeout > 0 ? $timeout : 5)
                ->withHeaders($headers)
                ->post($this->webhook->url, $this->payload);

            $this->recordDelivery($response->status(), $response->body(), $response->headers(), $response->successful(), null);

            if ($response->failed()) {
                Log::warning("Webhook [{$this->webhook->name}] failed with status {$response->status()}", [
                    'url' => $this->webhook->url,
                    'response' => $response->body(),
                ]);
                $this->release(60); // Retry after 60 seconds
            }
        } catch (\Exception $e) {
            $this->recordDelivery(null, null, null, false, $e->getMessage());
            
            Log::error("Webhook [{$this->webhook->name}] connection error: " . $e->getMessage());
            $this->release(60);
        }
    }

    protected function recordDelivery($statusCode, $responseBody, $responseHeaders, $isSuccessful, $errorMessage)
    {
        WebhookDelivery::create([
            'webhook_id' => $this->webhook->id,
            'event' => $this->event,
            'payload' => $this->payload,
            'response_headers' => $responseHeaders,
            'response_body' => $responseBody ? substr($responseBody, 0, 65535) : null,
            'status_code' => $statusCode,
            'is_successful' => $isSuccessful,
            'error_message' => $errorMessage ? substr($errorMessage, 0, 255) : null,
            'completed_at' => now(),
        ]);
    }
}