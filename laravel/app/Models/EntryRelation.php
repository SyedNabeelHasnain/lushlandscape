<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class EntryRelation extends Pivot
{
    protected $table = 'entry_relations';

    protected $fillable = [
        'source_entry_id',
        'target_entry_id',
        'relation_type',
        'sort_order',
    ];
}
