<?php

namespace App\Filament\Widgets;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Partido;
use App\Models\Resultado;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class OrganizadorStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    private function getCampeonato(): ?Campeonato
    {
        return Campeonato::where('organizador_id', auth()->id())
            ->where('is_active', true)
            ->latest()
            ->first();
    }

    protected function getStats(): array
    {
        $campeonato = $this->getCampeonato();

        if (!$campeonato) {
            return [
                Stat::make('Sin campeonato asignado', '—')
                    ->description('Contacta al administrador')
                    ->color('danger'),
            ];
        }

        $totalPartidos   = Partido::where('campeonato_id', $campeonato->id)->count();
        $partidosJugados = Resultado::whereHas('partido', fn ($q) =>
            $q->where('campeonato_id', $campeonato->id)
        )->where('es_oficial', true)->count();
        $pendientes = $totalPartidos - $partidosJugados;

        return [
            Stat::make('Campeonato', $campeonato->nombre)
                ->description($campeonato->estado?->name ?? 'Sin estado')
                ->descriptionIcon('heroicon-o-flag')
                ->color('success'),

            Stat::make('Equipos inscritos',
                Equipo::where('campeonato_id', $campeonato->id)->count()
            )
                ->description('De ' . ($campeonato->max_equipos ?? '∞') . ' máximo')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Partidos jugados', $partidosJugados)
                ->description("De {$totalPartidos} en total")
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('success'),

            Stat::make('Partidos pendientes', $pendientes)
                ->description('Sin resultado oficial')
                ->descriptionIcon('heroicon-o-clock')
                ->color($pendientes > 0 ? 'warning' : 'success'),

            Stat::make('Fase actual',
                match($campeonato->fase_actual ?? 'regular') {
                    'regular'        => '📋 Fase Regular',
                    'cuadrangulares' => '🇨🇴 Cuadrangulares',
                    'cuartos'        => '⭐ Cuartos de Final',
                    'semifinal'      => '🏆 Semifinales',
                    'final'          => '🥇 Final',
                    default          => 'Regular',
                }
            )
                ->description('Etapa del torneo')
                ->descriptionIcon('heroicon-o-chart-bar')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('organizador');
    }
}