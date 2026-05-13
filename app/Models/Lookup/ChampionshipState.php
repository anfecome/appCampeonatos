<?php

namespace App\Models\Lookup;

use App\Models\Campeonato;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChampionshipState extends BaseLookup
{
    protected $table = 'lookup_championship_states';

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

    public function campeonatos(): HasMany
    {
        return $this->hasMany(Campeonato::class, 'estado_id');
    }
}
