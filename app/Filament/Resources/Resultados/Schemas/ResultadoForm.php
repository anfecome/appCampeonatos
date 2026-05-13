<?php
namespace App\Filament\Resources\Resultados\Schemas;

use App\Models\Partido;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ResultadoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Partido')
                ->schema([
                    Select::make('partido_id')
                        ->label('Partido')
                        ->options(function () {
                            return Partido::with(['equipoLocal', 'equipoVisitante', 'campeonato'])
                                ->whereDoesntHave('resultado')
                                ->latest()
                                ->limit(200)
                                ->get()
                                ->mapWithKeys(fn ($p) =>
                                    [$p->id => "[J{$p->jornada}] {$p->equipoLocal?->nombre} vs {$p->equipoVisitante?->nombre} — {$p->campeonato?->nombre}"]
                                );
                        })
                        ->required()
                        ->searchable()
                        ->getSearchResultsUsing(fn (string $search) =>
                            Partido::with(['equipoLocal', 'equipoVisitante', 'campeonato'])
                                ->whereDoesntHave('resultado')
                                ->where(function ($q) use ($search) {
                                    $q->whereHas('equipoLocal',     fn ($q) => $q->where('nombre', 'like', "%{$search}%"))
                                    ->orWhereHas('equipoVisitante', fn ($q) => $q->where('nombre', 'like', "%{$search}%"))
                                    ->orWhereHas('campeonato',      fn ($q) => $q->where('nombre', 'like', "%{$search}%"));
                                })
                                ->limit(50)
                                ->get()
                                ->mapWithKeys(fn ($p) =>
                                    [$p->id => "[J{$p->jornada}] {$p->equipoLocal?->nombre} vs {$p->equipoVisitante?->nombre} — {$p->campeonato?->nombre}"]
                                )
                        )
                        ->unique(ignoreRecord: true)
                        ->helperText('Solo muestra partidos sin resultado registrado.'),
                ]),

            Section::make('Marcador')
                ->columns(2)
                ->schema([
                    TextInput::make('goles_local')
                        ->label('Goles local')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->required(),

                    TextInput::make('goles_visitante')
                        ->label('Goles visitante')
                        ->numeric()
                        ->default(0)
                        ->minValue(0)
                        ->required(),
                ]),

            Section::make('Cierre')
                ->columns(2)
                ->schema([
                    Toggle::make('es_oficial')
                        ->label('Resultado oficial')
                        ->helperText('Al marcarlo como oficial se actualizará la tabla de posiciones.')
                        ->default(false),

                    Textarea::make('observaciones')
                        ->label('Observaciones')
                        ->rows(2)
                        ->nullable(),
                ]),
        ]);
    }
}
