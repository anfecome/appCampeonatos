<?php

namespace App\Filament\Widgets;

use App\Models\Resultado;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class UltimosResultadosWidget extends BaseWidget
{
    protected static ?string $heading = 'Últimos resultados';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Resultado::query()
                    ->where('es_oficial', true)
                    ->with(['partido.equipoLocal', 'partido.equipoVisitante', 'partido.campeonato'])
                    ->latest()
                    ->limit(8)
            )
            ->columns([
                TextColumn::make('partido.campeonato.nombre')
                    ->label('Campeonato')
                    ->badge()
                    ->color('success'),

                TextColumn::make('partido.jornada')
                    ->label('J')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('partido.equipoLocal.nombre')
                    ->label('Local')
                    ->weight(\Filament\Support\Enums\FontWeight::Medium),

                TextColumn::make('marcador')
                    ->label('Resultado')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn ($record) =>
                        $record->goles_local . ' — ' . $record->goles_visitante
                    ),

                TextColumn::make('partido.equipoVisitante.nombre')
                    ->label('Visitante')
                    ->weight(\Filament\Support\Enums\FontWeight::Medium),

                TextColumn::make('updated_at')
                    ->label('Registrado')
                    ->dateTime('d/m/Y H:i')
                    ->color('gray'),
            ])
            ->paginated(false);
    }

    public static function canView(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador']);
    }
}