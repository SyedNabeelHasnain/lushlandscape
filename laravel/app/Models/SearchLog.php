<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['query', 'results_count', 'session_id', 'page_context', 'ip'];

    protected function casts(): array
    {
        return ['created_at' => 'datetime'];
    }
}
