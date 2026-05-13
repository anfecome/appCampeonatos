<?php
namespace App\Filament\Resources\Partidos\Schemas;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Lookup\MatchState;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PartidoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Equipos')
                ->columns(2)
                ->schema([
                    Select::make('campeonato_id')
                        ->label('Campeonato')
                        ->options(Campeonato::active()->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->live()
                        ->columnSpanFull()
                        ->afterStateUpdated(function ($set) {
                            $set('equipo_local_id', null);
                            $set('equipo_visitante_id', null);
                        }),

                    Select::make('equipo_local_id')
                        ->label('Equipo local')
                        ->options(fn ($get) =>
                            $get('campeonato_id')
                                ? Equipo::where('campeonato_id', $get('campeonato_id'))
                                    ->active()->pluck('nombre', 'id')
                                : []
                        )
                        ->required()
                        ->searchable(),

                    Select::make('equipo_visitante_id')
                        ->label('Equipo visitante')
                        ->options(fn ($get) =>
                            $get('campeonato_id')
                                ? Equipo::where('campeonato_id', $get('campeonato_id'))
                                    ->active()->pluck('nombre', 'id')
                                : []
                        )
                        ->required()
                        ->searchable()
                        ->different('equipo_local_id'),
                ]),

            Section::make('Programación')
                ->columns(2)
                ->schema([
                    TextInput::make('jornada')
                        ->label('Jornada')
                        ->numeric()
                        ->minValue(1)
                        ->nullable(),

                    TextInput::make('fase')
                        ->label('Fase')
                        ->default('Liga')
                        ->maxLength(100),

                    DateTimePicker::make('fecha_hora')
                        ->label('Fecha y hora')
                        ->nullable(),

                    TextInput::make('lugar')
                        ->label('Estadio / Lugar')
                        ->maxLength(255),

                    Select::make('estado_id')
                        ->label('Estado del partido')
                        ->options(MatchState::active()->ordered()->pluck('name', 'id'))
                        ->nullable(),
                ]),

            Textarea::make('notas')
                ->label('Notas')
                ->rows(2)
                ->nullable(),
        ]);
    }
}
