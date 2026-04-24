<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewAssignment extends Model
{
    protected $table = 'review_assignments';

    protected $fillable = [
        'review_id',
        'assignable_id',
        'assignable_type',
    ];

    public function review()
    {
        return $this->belongsTo(Review::class);
    }

    public function assignable()
    {
        return $this->morphTo();
    }
}
