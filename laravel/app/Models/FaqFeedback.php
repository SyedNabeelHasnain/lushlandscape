<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqFeedback extends Model
{
    protected $table = 'faq_feedback';

    protected $fillable = [
        'faq_id', 'is_helpful', 'comment', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'is_helpful' => 'boolean',
        ];
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }
}
