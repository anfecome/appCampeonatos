<?php

namespace App\Models;

use App\Models\Lookup\ChampionshipState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Campeonato extends Model
{
    protected $table = 'campeonatos';

    protected $fillable = [
        'nombre',
        'slug',
        'deporte_id',
        'organizador_id',
        'creado_por',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'estado_id',
        'reglas',
        'puntos_ganado',
        'puntos_empate',
        'puntos_perdida',
        'max_equipos',
        'formato',
        'lugar',
        'logo_path',
        'banner_path',
        'is_active',
        'is_public',
        'fase_actual',
    ];

    protected $casts = [
        'fecha_inicio'    => 'date',
        'fecha_fin'       => 'date',
        'reglas'          => 'array',
        'puntos_ganado'   => 'integer',
        'puntos_empate'   => 'integer',
        'puntos_perdida'  => 'integer',
        'max_equipos'     => 'integer',
        'is_active'       => 'boolean',
        'is_public'       => 'boolean',
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

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('is_public', true);
    }

    public function scopeEnCurso(Builder $query): Builder
    {
        return $query->whereHas('estado', fn ($q) => $q->where('code', 'en_curso'));
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function deporte(): BelongsTo
    {
        return $this->belongsTo(Deporte::class, 'deporte_id');
    }

    public function organizador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizador_id');
    }

    public function creadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creado_por');
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(ChampionshipState::class, 'estado_id');
    }

    public function equipos(): HasMany
    {
        return $this->hasMany(Equipo::class, 'campeonato_id');
    }

    public function partidos(): HasMany
    {
        return $this->hasMany(Partido::class, 'campeonato_id');
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class, 'campeonato_id');
    }

    public function puntuaciones(): HasMany
    {
        return $this->hasMany(Puntuacion::class, 'campeonato_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getFormatoTorneoAttribute(): string
    {
        return $this->formato ?? 'liga_normal';
    }

    public function getFaseActualAttribute(): string
    {
        return $this->attributes['fase_actual'] ?? 'regular';
    }

    public function getLimitEquiposAlcanzadoAttribute(): bool
    {
        if (! $this->max_equipos) {
            return false;
        }
        return $this->equipos()->count() >= $this->max_equipos;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->logo_path ? asset('storage/' . $this->logo_path) : null;
    }

    /**
     * Indica si el campeonato ha finalizado.
     */
    public function esFinalizado(): bool
    {
        return $this->estado?->code === 'finished';
    }

    /**
     * Devuelve el equipo campeón (el primero en la tabla de posiciones).
     * Solo retorna valor si el campeonato está finalizado.
     */
    public function getCampeonAttribute(): ?Equipo
    {
        if (! $this->esFinalizado()) {
            return null;
        }

        return $this->puntuaciones()
            ->with('equipo')
            ->orderByDesc('puntos')
            ->orderByDesc('diferencia')
            ->orderByDesc('goles_favor')
            ->first()
            ?->equipo;
    }
}