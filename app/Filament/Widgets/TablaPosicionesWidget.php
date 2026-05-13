<?php
namespace App\Filament\Widgets;

use App\Models\Campeonato;
use App\Models\Puntuacion;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

class TablaPosicionesWidget extends BaseWidget implements HasForms
{

    use InteractsWithForms;

    protected static ?string $heading = 'Tabla de posiciones';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public ?int $campeonatoId = null;
    public ?string $campeonatoEstado = null;

    public function mount(): void
    {
        $campeonato = Campeonato::active()
            ->with('estado')
            ->latest()
            ->first();

        $this->campeonatoId     = $campeonato?->id;
        $this->campeonatoEstado = $campeonato?->estado?->code;
    }

    public function updatedCampeonatoId(): void
    {
        $campeonato = Campeonato::with('estado')->find($this->campeonatoId);
        $this->campeonatoEstado = $campeonato?->estado?->code;
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getFormSchema(): array
    {
        return [
            Select::make('campeonatoId')
                ->label('Campeonato')
                ->options(Campeonato::active()->pluck('nombre', 'id'))
                ->default($this->campeonatoId)
                ->live()
                ->placeholder('Selecciona un campeonato'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Puntuacion::query()
                    ->where('campeonato_id', $this->campeonatoId)
                    ->with('equipo')
                    ->orderByDesc('puntos')
                    ->orderByDesc('diferencia')
                    ->orderByDesc('goles_favor')
            )
            ->columns([
                TextColumn::make('posicion')
                    ->label('#')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        static $pos = 0;
                        $pos++;
                        return $pos;
                    })
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        if ($state === 1 && $this->campeonatoEstado === 'finished') {
                            return '🏆 1';
                        }
                        return $state;
                    })
                    ->color(function ($state) {
                        if ($state === 1 && $this->campeonatoEstado === 'finished') {
                            return 'warning'; // dorado
                        }
                        return $state === 1 ? 'success' : 'gray';
                    }),

                TextColumn::make('equipo.nombre')
                    ->label('Equipo')
                    ->formatStateUsing(function ($state, $record) {
                        static $pos = 0;
                        $pos++;
                        if ($pos === 1 && $this->campeonatoEstado === 'finished') {
                            return $state . ' — Campeón 🏆';
                        }
                        return $state;
                    })
                    ->searchable()
                    ->sortable(),

                TextColumn::make('partidos_jugados')
                    ->label('PJ')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('ganados')
                    ->label('G')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('empatados')
                    ->label('E')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('perdidos')
                    ->label('P')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('goles_favor')
                    ->label('GF')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('goles_contra')
                    ->label('GC')
                    ->alignCenter()
                    ->sortable(),

                TextColumn::make('diferencia')
                    ->label('DG')
                    ->alignCenter()
                    ->sortable()
                    ->color(fn ($state) => $state > 0 ? 'success' : ($state < 0 ? 'danger' : 'gray')),

                TextColumn::make('puntos')
                    ->label('Pts')
                    ->alignCenter()
                    ->sortable()
                    ->badge()
                    ->color('info')
                    ->weight(\Filament\Support\Enums\FontWeight::Bold),
            ])
            ->paginated(false);
    }
    private function getPrimerPuntos(): int
    {
        return \App\Models\Puntuacion::where('campeonato_id', $this->campeonatoId)
            ->orderByDesc('puntos')
            ->orderByDesc('diferencia')
            ->orderByDesc('goles_favor')
            ->value('puntos') ?? 0;
    }
}