<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $form_id
 * @property array|null $data
 * @property bool $email_verified
 * @property string|null $ip_address
 * @property string|null $user_agent
 * @property array|null $device_info
 * @property string|null $referrer
 * @property array|null $utm_data
 * @property string $status
 * @property-read Form|null $form
 * @property-read int $total
 */
class FormSubmission extends Model
{
    protected $fillable = [
        'form_id', 'data', 'email_verified', 'ip_address', 'user_agent',
        'device_info', 'referrer', 'utm_data', 'status',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array',
            'device_info' => 'array',
            'utm_data' => 'array',
            'email_verified' => 'boolean',
        ];
    }

    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }
}
