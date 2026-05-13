<?php

namespace App\Models\Lookup;

class MedicalState extends BaseLookup
{
    protected $table = 'lookup_medical_states';

    protected $fillable = [
        'code',
        'name',
        'description',
        'can_play',
        'color_hex',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'can_play' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function scopePlayable(\Illuminate\Database\Eloquent\Builder $query): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('can_play', true);
    }
}
