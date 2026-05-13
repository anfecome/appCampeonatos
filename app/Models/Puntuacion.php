<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

class Puntuacion extends Model
{
    protected $table = 'puntuaciones';

    protected $fillable = [
        'campeonato_id',
        'equipo_id',
        'partidos_jugados',
        'ganados',
        'empatados',
        'perdidos',
        'goles_favor',
        'goles_contra',
        'diferencia',
        'puntos',
    ];

    protected $casts = [
        'partidos_jugados' => 'integer',
        'ganados'          => 'integer',
        'empatados'        => 'integer',
        'perdidos'         => 'integer',
        'goles_favor'      => 'integer',
        'goles_contra'     => 'integer',
        'diferencia'       => 'integer',
        'puntos'           => 'integer',
    ];

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeClasificacion(Builder $query): Builder
    {
        return $query->orderByDesc('puntos')
                     ->orderByDesc('diferencia')
                     ->orderByDesc('goles_favor');
    }

    // ─── Relaciones ──────────────────────────────────────────────────────────

    public function campeonato(): BelongsTo
    {
        return $this->belongsTo(Campeonato::class, 'campeonato_id');
    }

    public function equipo(): BelongsTo
    {
        return $this->belongsTo(Equipo::class, 'equipo_id');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    /**
     * Recalcula esta fila a partir de los resultados oficiales del campeonato.
     * Llámalo después de registrar/actualizar un Resultado.
     */
    public function recalcular(): void
    {
        $campeonato = $this->campeonato;

        $partidos = Partido::where('campeonato_id', $this->campeonato_id)
            ->where(function ($q) {
                $q->where('equipo_local_id', $this->equipo_id)
                  ->orWhere('equipo_visitante_id', $this->equipo_id);
            })
            ->with('resultado')
            ->get();

        $pj = $g = $e = $p = $gf = $gc = 0;

        foreach ($partidos as $partido) {
            $r = $partido->resultado;
            if (! $r || ! $r->es_oficial) {
                continue;
            }

            $esLocal = $partido->equipo_local_id === $this->equipo_id;
            $miGoles  = $esLocal ? $r->goles_local      : $r->goles_visitante;
            $susGoles = $esLocal ? $r->goles_visitante   : $r->goles_local;

            $pj++;
            $gf += $miGoles;
            $gc += $susGoles;

            if ($miGoles > $susGoles)       $g++;
            elseif ($miGoles === $susGoles)  $e++;
            else                             $p++;
        }

        $this->update([
            'partidos_jugados' => $pj,
            'ganados'          => $g,
            'empatados'        => $e,
            'perdidos'         => $p,
            'goles_favor'      => $gf,
            'goles_contra'     => $gc,
            'diferencia'       => $gf - $gc,
            'puntos'           => $g * $campeonato->puntos_ganado
                                + $e * $campeonato->puntos_empate
                                + $p * $campeonato->puntos_perdida,
        ]);
    }
}
