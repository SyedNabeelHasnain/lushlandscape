<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property string $form_type
 * @property string|null $description
 * @property string|null $success_message
 * @property array|null $email_to
 * @property array|null $email_cc
 * @property array|null $email_bcc
 * @property bool $requires_email_verification
 * @property bool $honeypot_enabled
 * @property string $status
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormField[] $fields
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\FormSubmission[] $submissions
 */
class Form extends Model
{
    protected $fillable = [
        'name', 'slug', 'form_type', 'description', 'success_message',
        'email_subject', 'confirmation_message', 'admin_badge',
        'email_to', 'email_cc', 'email_bcc',
        'requires_email_verification', 'honeypot_enabled', 'status',
    ];

    protected function casts(): array
    {
        return [
            'email_to' => 'array',
            'email_cc' => 'array',
            'email_bcc' => 'array',
            'requires_email_verification' => 'boolean',
            'honeypot_enabled' => 'boolean',
        ];
    }

    public function fields(): HasMany
    {
        return $this->hasMany(FormField::class)->orderBy('sort_order');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(FormSubmission::class);
    }
}
