<?php

namespace App\Models\Lookup;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

abstract class BaseLookup extends Model
{
    public $timestamps = false;

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order');
    }

    public static function toSelectArray(): array
    {
        return static::query()
            ->active()
            ->ordered()
            ->pluck('name', 'code')
            ->toArray();
    }
}
