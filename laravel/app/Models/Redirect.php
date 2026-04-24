<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Redirect extends Model
{
    protected $fillable = ['old_url', 'new_url', 'status_code', 'is_active', 'hit_count'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
