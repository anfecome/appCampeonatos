<?php

namespace App\Models\Lookup;

class PhaseType extends BaseLookup
{
    protected $table = 'lookup_phase_types';

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
