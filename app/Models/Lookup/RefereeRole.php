<?php

namespace App\Models\Lookup;

class RefereeRole extends BaseLookup
{
    protected $table = 'lookup_referee_roles';

    protected $fillable = [
        'code',
        'name',
        'description',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
