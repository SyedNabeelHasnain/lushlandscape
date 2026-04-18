<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouteAlias extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'routable_type',
        'routable_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function routable()
    {
        return $this->morphTo();
    }
}
