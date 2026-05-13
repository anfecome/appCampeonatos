<?php

namespace App\Models;

use App\Models\Lookup\RegistrationState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inscripcion extends Model
{
    protected $table = 'inscripciones';

    protected $fillable = [
        'campeonato_id',
        'equipo_id',
        'estado_id',
        'fecha_inscripcion',
        'notas',
    ];

    protected $casts = [
        'fecha_inscripcion' => 'datetime',
    ];

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function campeonato(): BelongsTo
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(RegistrationState::class, 'estado_id');
    }
}
