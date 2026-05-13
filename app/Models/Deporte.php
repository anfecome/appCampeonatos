<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Deporte extends Model
{
    protected $table = 'deportes';

    protected $fillable = [
        'nombre',
        'slug',
        'descripcion',
        'icono',
        'es_equipo',
        'min_jugadores',
        'max_jugadores',
        'reglas',
        'is_active',
    ];

    protected $casts = [
        'es_equipo'      => 'boolean',
        'is_active'      => 'boolean',
        'reglas'         => 'array',
        'min_jugadores'  => 'integer',
        'max_jugadores'  => 'integer',
    ];

    // ─── Boot ────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->nombre);
            }
        });
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function campeonatos(): HasMany
    {
        return $this->hasMany(Campeonato::class, 'deporte_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
