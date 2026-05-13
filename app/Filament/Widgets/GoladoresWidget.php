<?php
namespace App\Filament\Widgets;

use App\Models\Campeonato;
use App\Models\Jugador;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class GoladoresWidget extends BaseWidget
{
    protected static ?string $heading = 'Goleadores';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        $campeonatoId = Campeonato::active()->latest()->value('id');

        return $table
            ->query(
                Jugador::query()
                    ->select('jugadores.*')
                    ->whereHas('equipo', fn ($q) => $q->where('campeonato_id', $campeonatoId))
                    ->withCount(['novedades as goles' => fn ($q) => $q
                        ->whereHas('tipo', fn ($q) => $q->whereIn('code', ['goal', 'penalty']))
                    ])
                    ->having('goles', '>', 0)
                    ->orderByDesc('goles')
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('nombre_completo')
                    ->label('Jugador')
                    ->getStateUsing(fn ($record) => $record->nombre . ' ' . $record->apellido),

                TextColumn::make('equipo.nombre')
                    ->label('Equipo'),

                TextColumn::make('goles')
                    ->label('⚽ Goles')
                    ->alignCenter()
                    ->badge()
                    ->color('success'),
            ])
            ->paginated(false);
    }
}