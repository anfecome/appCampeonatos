<?php

namespace App\Filament\Widgets;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Partido;
use App\Models\Resultado;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AdminStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Campeonatos activos', Campeonato::where('is_active', true)->count())
                ->description('Total en el sistema')
                ->descriptionIcon('heroicon-o-flag')
                ->color('success'),

            Stat::make('Equipos registrados', Equipo::where('is_active', true)->count())
                ->description('En todos los campeonatos')
                ->descriptionIcon('heroicon-o-user-group')
                ->color('info'),

            Stat::make('Jugadores registrados', Jugador::where('is_active', true)->count())
                ->description('En todos los equipos')
                ->descriptionIcon('heroicon-o-user')
                ->color('warning'),

            Stat::make('Partidos jugados', Resultado::where('es_oficial', true)->count())
                ->description('Con resultado oficial')
                ->descriptionIcon('heroicon-o-clipboard-document-list')
                ->color('success'),

            Stat::make('Partidos pendientes',
                Partido::whereDoesntHave('resultado')->count()
            )
                ->description('Sin resultado aún')
                ->descriptionIcon('heroicon-o-clock')
                ->color('warning'),

            Stat::make('Usuarios en el sistema', User::where('is_active', true)->count())
                ->description('Cuentas activas')
                ->descriptionIcon('heroicon-o-users')
                ->color('info'),
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasRole('admin');
    }
}