<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Taxonomy extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_hierarchical',
        'schema_json',
    ];

    protected $casts = [
        'is_hierarchical' => 'boolean',
        'schema_json' => 'array',
    ];

    public function terms()
    {
        return $this->hasMany(Term::class);
    }
}
