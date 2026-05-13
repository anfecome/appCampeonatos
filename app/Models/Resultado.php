<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Resultado extends Model
{
    protected $table = 'resultados';

    protected $fillable = [
        'partido_id',
        'goles_local',
        'goles_visitante',
        'sets_local',
        'sets_visitante',
        'ganador_id',
        'es_oficial',
        'observaciones',
        'registrado_por',
    ];

    protected $casts = [
        'goles_local'      => 'integer',
        'goles_visitante'  => 'integer',
        'sets_local'       => 'array',
        'sets_visitante'   => 'array',
        'es_oficial'       => 'boolean',
    ];

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }

    public function ganador(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'ganador_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getMarcadorAttribute(): string
    {
        return "{$this->goles_local} - {$this->goles_visitante}";
    }

    public function esEmpate(): bool
    {
        return $this->goles_local === $this->goles_visitante;
    }

    public function ganadorEs(int $equipoId): bool
    {
        return $this->ganador_id === $equipoId;
    }
}
