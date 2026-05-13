<?php

namespace App\Models\Lookup;

class Position extends BaseLookup
{
    protected $table = 'lookup_positions';

    protected $fillable = [
        'code',
        'name',
        'abbreviation',
        'zone',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function scopeByZone(\Illuminate\Database\Eloquent\Builder $query, string $zone): \Illuminate\Database\Eloquent\Builder
    {
        return $query->where('zone', $zone);
    }

    public static function toGroupedSelectArray(): array
    {
        return static::query()
            ->active()
            ->ordered()
            ->get()
            ->groupBy('zone')
            ->map(fn($group) => $group->pluck('name', 'code'))
            ->toArray();
    }
}
