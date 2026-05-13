<?php

namespace App\Models\Lookup;

class MatchState extends BaseLookup
{
    protected $table = 'lookup_match_states';

    protected $fillable = [
        'code',
        'name',
        'description',
        'color_hex',
        'is_terminal',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_terminal' => 'boolean',
        'is_active' => 'boolean',
    ];
}
