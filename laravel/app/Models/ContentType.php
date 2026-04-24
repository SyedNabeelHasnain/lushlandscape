<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'layout_template',
        'is_hierarchical',
        'has_archives',
        'schema_json',
    ];

    protected $casts = [
        'is_hierarchical' => 'boolean',
        'has_archives' => 'boolean',
        'schema_json' => 'array',
    ];

    public function entries()
    {
        return $this->hasMany(Entry::class);
    }
}
