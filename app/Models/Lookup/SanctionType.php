<?php

namespace App\Models\Lookup;

class SanctionType extends BaseLookup
{
    protected $table = 'lookup_sanction_types';

    protected $fillable = [
        'code',
        'name',
        'description',
        'triggers_suspension',
        'yellows_to_accumulate',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'triggers_suspension' => 'boolean',
        'yellows_to_accumulate' => 'integer',
        'is_active' => 'boolean',
    ];
}
