<?php

namespace App\Models\Lookup;

class DominantFoot extends BaseLookup
{
    protected $table = 'lookup_dominant_feet';

    protected $fillable = [
        'code',
        'name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
