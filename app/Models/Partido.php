<?php

namespace App\Models;

use App\Models\Lookup\MatchState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;

class Partido extends Model
{
    protected $table = 'partidos';

    protected $fillable = [
        'campeonato_id',
        'jornada',
        'fase',
        'equipo_local_id',
        'equipo_visitante_id',
        'fecha_hora',
        'lugar',
        'estado_id',
        'duracion_minutos',
        'notas',
        'creado_por',
    ];

    protected $casts = [
        'fecha_hora'       => 'datetime',
        'jornada'          => 'integer',
        'duracion_minutos' => 'integer',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeProximos(Builder $query): Builder
    {
        return $query->where('fecha_hora', '>', now())
                     ->orderBy('fecha_hora');
    }

    public function scopeFinalizados(Builder $query): Builder
    {
        return $query->whereHas('estado', fn ($q) => $q->where('code', 'finalizado'));
    }

    public function scopeEnJuego(Builder $query): Builder
    {
        return $query->whereHas('estado', fn ($q) => $q->where('code', 'en_juego'));
    }

    public function scopePorJornada(Builder $query, int $jornada): Builder
    {
        return $query->where('jornada', $jornada);
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function campeonato(): BelongsTo
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function equipoLocal(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_local_id');
    }

    public function equipoVisitante(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_visitante_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(MatchState::class, 'estado_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function resultado(): HasOne
    {
        return $this->hasOne(Resultado::class, 'partido_id');
    }

    public function novedades(): HasMany
    {
        return $this->hasMany(Novedad::class, 'partido_id')->orderBy('minuto');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getTituloAttribute(): string
    {
        $local     = $this->equipoLocal?->nombre     ?? 'Local';
        $visitante = $this->equipoVisitante?->nombre ?? 'Visitante';
        return "{$local} vs {$visitante}";
    }

    public function getMarcadorAttribute(): string
    {
        if (! $this->resultado) {
            return '- vs -';
        }
        return "{$this->resultado->goles_local} - {$this->resultado->goles_visitante}";
    }

    public function involucraEquipo(int $equipoId): bool
    {
        return $this->equipo_local_id === $equipoId
            || $this->equipo_visitante_id === $equipoId;
    }
}
