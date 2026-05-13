<?php

namespace App\Filament\Pages;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Novedad;
use App\Models\Partido;
use App\Models\Puntuacion;
use App\Models\Resultado;
use Filament\Pages\Page;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Estadisticas extends Page
{

    protected static ?string $title           = 'Estadísticas del campeonato';
    protected static ?int    $navigationSort  = 5;

    public array $stats = [];

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getNavigationLabel(): string
    {
        return 'Estadísticas';
    }

    public  function getView(): string
    {
        return 'filament.pages.estadisticas';
    }

    public function mount(): void
    {
        $campeonato = Campeonato::active()->latest()->first();

        if (!$campeonato) {
            $this->stats = [];
            return;
        }

        $resultadosOficiales = Resultado::whereHas('partido', fn ($q) =>
            $q->where('campeonato_id', $campeonato->id)
        )->where('es_oficial', true)->get();

        $totalPartidos    = $resultadosOficiales->count();
        $totalGoles       = $resultadosOficiales->sum('goles_local')
                          + $resultadosOficiales->sum('goles_visitante');
        $promedio         = $totalPartidos > 0
                          ? round($totalGoles / $totalPartidos, 1)
                          : 0;

        // Equipo con más victorias
        $mejorEquipo = Puntuacion::where('campeonato_id', $campeonato->id)
            ->with('equipo')
            ->orderByDesc('ganados')
            ->first();

        // Goleador
        $goleador = Jugador::whereHas('equipo', fn ($q) =>
                $q->where('campeonato_id', $campeonato->id)
            )
            ->withCount(['novedades as goles' => fn ($q) =>
                $q->whereHas('tipo', fn ($q) =>
                    $q->whereIn('code', ['goal', 'penalty'])
                )
            ])
            ->orderByDesc('goles')
            ->first();

        // Jugador más sancionado
        $masSancionado = Jugador::whereHas('equipo', fn ($q) =>
                $q->where('campeonato_id', $campeonato->id)
            )
            ->withCount(['novedades as sanciones' => fn ($q) =>
                $q->whereHas('tipo', fn ($q) =>
                    $q->whereIn('code', ['yellow_card', 'red_card'])
                )
            ])
            ->orderByDesc('sanciones')
            ->first();

        $pendientes = Partido::where('campeonato_id', $campeonato->id)
            ->whereDoesntHave('resultado')
            ->count();

        $this->stats = [
            'campeonato'       => $campeonato->nombre,
            'total_partidos'   => $totalPartidos,
            'pendientes'       => $pendientes,
            'total_goles'      => $totalGoles,
            'promedio_goles'   => $promedio,
            'mejor_equipo'     => $mejorEquipo?->equipo?->nombre ?? '—',
            'mejor_equipo_v'   => $mejorEquipo?->ganados ?? 0,
            'goleador'         => $goleador
                                    ? $goleador->nombre_completo . ' (' . $goleador->goles . ' goles)'
                                    : '—',
            'mas_sancionado'   => $masSancionado && $masSancionado->sanciones > 0
                                    ? $masSancionado->nombre_completo . ' (' . $masSancionado->sanciones . ')'
                                    : '—',
        ];
    }
}