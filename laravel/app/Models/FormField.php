<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    protected $fillable = [
        'form_id', 'name', 'label', 'type', 'placeholder', 'help_text',
        'options', 'validation_rules', 'is_required', 'width', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'options' => 'array',
            'validation_rules' => 'array',
            'is_required' => 'boolean',
        ];
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
