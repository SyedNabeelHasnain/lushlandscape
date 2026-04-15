<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqAssignment extends Model
{
    protected $fillable = [
        'faq_id', 'assignable_type', 'assignable_id',
        'local_title_override', 'local_display_order', 'is_collapsed', 'is_visible',
    ];

    protected function casts(): array
    {
        return [
            'is_collapsed' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    public function faq()
    {
        return $this->belongsTo(Faq::class);
    }

    public function assignable()
    {
        return $this->morphTo();
    }
}
