<?php

namespace App\Models\Lookup;

class RegistrationState extends BaseLookup
{
    protected $table = 'lookup_registration_states';

    protected $fillable = [
        'code',
        'name',
        'description',
        'color_hex',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
