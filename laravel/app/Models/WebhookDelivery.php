<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebhookDelivery extends Model
{
    protected $fillable = [
        'webhook_id',
        'event',
        'payload',
        'response_headers',
        'response_body',
        'status_code',
        'is_successful',
        'error_message',
        'completed_at',
    ];

    protected $casts = [
        'payload' => 'array',
        'response_headers' => 'array',
        'is_successful' => 'boolean',
        'completed_at' => 'datetime',
    ];

    public function webhook(): BelongsTo
    {
        return $this->belongsTo(Webhook::class);
    }
}