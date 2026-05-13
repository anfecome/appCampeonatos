<?php

namespace App\Models;

use App\Models\Lookup\EventType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Novedad extends Model
{
    protected $table = 'novedades';

    protected $fillable = [
        'partido_id',
        'jugador_id',
        'equipo_id',
        'tipo_id',
        'minuto',
        'descripcion',
        'creado_por',
    ];

    protected $casts = [
        'minuto' => 'integer',
    ];

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function partido(): BelongsTo
    {
        return $this->belongsTo(Partido::class, 'partido_id');
    }

    public function jugador(): BelongsTo
    {
        return $this->belongsTo(Jugador::class, 'jugador_id');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function tipo(): BelongsTo
    {
        return $this->belongsTo(EventType::class, 'tipo_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}
