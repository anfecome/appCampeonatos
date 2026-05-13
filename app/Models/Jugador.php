<?php

namespace App\Models;

use App\Models\Lookup\DominantFoot;
use App\Models\Lookup\MedicalState;
use App\Models\Lookup\Position;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Jugador extends Model
{
    protected $table = 'jugadores';

    protected $fillable = [
        'user_id',
        'equipo_id',
        'nombre',
        'apellido',
        'fecha_nacimiento',
        'genero',
        'nacionalidad',
        'documento_tipo',
        'documento_numero',
        'dorsal',
        'posicion_id',
        'pie_dominante_id',
        'estado_medico_id',
        'foto_path',
        'metadata',
        'is_active',
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
        'metadata'         => 'array',
        'is_active'        => 'boolean',
        'dorsal'           => 'integer',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopePuedeJugar(Builder $query): Builder
    {
        return $query->whereHas('estadoMedico', fn ($q) => $q->where('can_play', true))
                     ->orWhereNull('estado_medico_id');
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    public function posicion(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'posicion_id');
    }

    public function pieDominante(): BelongsTo
    {
        return $this->belongsTo(DominantFoot::class, 'pie_dominante_id');
    }

    public function estadoMedico(): BelongsTo
    {
        return $this->belongsTo(MedicalState::class, 'estado_medico_id');
    }

    public function novedades(): HasMany
    {
        return $this->hasMany(Novedad::class, 'jugador_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getNombreCompletoAttribute(): string
    {
        return trim("{$this->nombre} {$this->apellido}");
    }

    public function getEdadAttribute(): ?int
    {
        return $this->fecha_nacimiento?->age;
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto_path ? asset('storage/' . $this->foto_path) : null;
    }
}
