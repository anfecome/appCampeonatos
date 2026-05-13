<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Equipo extends Model
{
    protected $table = 'equipos';

    protected $fillable = [
        'nombre',
        'tag',
        'campeonato_id',
        'entrenador_id',
        'descripcion',
        'logo_path',
        'color_primario',
        'color_secundario',
        'ciudad',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function campeonato(): BelongsTo
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function entrenador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'entrenador_id');
    }

    public function jugadores(): HasMany
    {
        return $this->hasMany(Jugador::class, 'equipo_id');
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'equipo_id');
    }

    public function puntuacion(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Puntuacion::class, 'equipo_id')
                    ->where('campeonato_id', $this->campeonato_id);
    }

    public function puntuacionEnCampeonato(int $campeonatoId): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Puntuacion::class, 'equipo_id')
                    ->where('campeonato_id', $campeonatoId);
    }

    /** Partidos donde este equipo es local */
    public function partidosLocal(): HasMany
    {
        return $this->hasMany(Partido::class, 'equipo_local_id');
    }

    /** Partidos donde este equipo es visitante */
    public function partidosVisitante(): HasMany
    {
        return $this->hasMany(Partido::class, 'equipo_visitante_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /** Todos los partidos del equipo (local + visitante) */
    public function getTodosPartidosAttribute()
    {
        return Partido::where('equipo_local_id', $this->id)
            ->orWhere('equipo_visitante_id', $this->id)
            ->get();
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    public function getNombreCompletoAttribute(): string
    {
        return $this->tag ? "[{$this->tag}] {$this->nombre}" : $this->nombre;
    }
}
