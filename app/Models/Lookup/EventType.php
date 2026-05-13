<?php

namespace App\Models\Lookup;

class EventType extends BaseLookup
{
    protected $table = 'lookup_event_types';

    protected $fillable = [
        'code',
        'name',
        'description',
        'affects_score',
        'is_sanction',
        'requires_player',
        'color_hex',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'affects_score' => 'boolean',
        'is_sanction' => 'boolean',
        'requires_player' => 'boolean',
        'is_active' => 'boolean',
    ];
}
