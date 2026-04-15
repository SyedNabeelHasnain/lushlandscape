<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Neighborhood extends Model
{
    protected $fillable = [
        'city_id', 'name', 'slug', 'latitude', 'longitude',
        'summary', 'meta_title', 'meta_description',
        'status', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
