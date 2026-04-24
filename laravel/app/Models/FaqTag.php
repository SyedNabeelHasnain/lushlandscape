<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FaqTag extends Model
{
    protected $fillable = ['name', 'slug'];

    public function faqs()
    {
        return $this->belongsToMany(Faq::class, 'faq_tag_map');
    }
}
