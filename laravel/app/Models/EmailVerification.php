<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $email
 * @property string $otp
 * @property bool $is_verified
 * @property Carbon $expires_at
 * @property Carbon|null $verified_at
 * @property int $attempts
 * @property string|null $ip_address
 */
class EmailVerification extends Model
{
    protected $fillable = [
        'email', 'otp', 'is_verified', 'expires_at', 'verified_at', 'attempts', 'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'expires_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public static function isEmailVerified(string $email): bool
    {
        return static::where('email', $email)->where('is_verified', true)->exists();
    }
}
