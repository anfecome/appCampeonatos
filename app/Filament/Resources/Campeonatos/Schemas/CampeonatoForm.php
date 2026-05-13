<?php

namespace App\Filament\Resources\Campeonatos\Schemas;

use App\Models\Lookup\ChampionshipState;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CampeonatoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Información general')
                ->columns(2)
                ->schema([
                    Select::make('deporte_id')
                        ->label('Deporte')
                        ->options(\App\Models\Deporte::where('is_active', true)->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->columnSpanFull(),
                        
                    TextInput::make('nombre')
                        ->label('Nombre del campeonato')
                        ->required()
                        ->maxLength(250)
                        ->columnSpanFull()
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    TextInput::make('slug')
                        ->label('Slug (URL)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(250)
                        ->helperText('Se genera automáticamente.'),

                    Select::make('estado_id')
                        ->label('Estado')
                        ->options(
                            ChampionshipState::query()->ordered()->pluck('name', 'id')
                        )
                        ->nullable(),

                    Select::make('organizador_id')
                        ->label('Organizador')
                        ->options(
                            User::where('is_active', true)->pluck('name', 'id')
                        )
                        ->searchable()
                        ->nullable(),

                    Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(3)
                        ->columnSpanFull()
                        ->nullable(),
                ]),

            Section::make('Fechas y lugar')
                ->columns(3)
                ->schema([
                    DatePicker::make('fecha_inicio')
                        ->label('Fecha de inicio')
                        ->nullable(),

                    DatePicker::make('fecha_fin')
                        ->label('Fecha de fin')
                        ->nullable(),

                    TextInput::make('lugar')
                        ->label('Ciudad / Lugar')
                        ->maxLength(255)
                        ->placeholder('Bogotá, Colombia'),
                ]),

            Section::make('Configuración de la liga')
                ->columns(3)
                ->schema([
                    TextInput::make('max_equipos')
                        ->label('Máximo de equipos')
                        ->numeric()
                        ->minValue(2)
                        ->nullable()
                        ->helperText('Déjalo vacío para sin límite.'),

                    TextInput::make('puntos_ganado')
                        ->label('Puntos por victoria')
                        ->numeric()
                        ->default(3)
                        ->required(),

                    TextInput::make('puntos_empate')
                        ->label('Puntos por empate')
                        ->numeric()
                        ->default(1)
                        ->required(),

                    // puntos_perdida siempre es 0 en fútbol, lo manejamos con default
                ]),
                
            Section::make('Formato del torneo')
                            ->columns(1)
                            ->schema([
                                Select::make('formato')
                                    ->label('Tipo de torneo')
                                    ->required()
                                    ->options([
                                        'liga_normal'    => '🏆 Liga Normal — Todos vs todos, gana el de más puntos',
                                        'cuadrangulares' => '⚽ Cuadrangulares (FPC) — Fase regular → Top 8 → 2 grupos de 4 → Final',
                                        'champions'      => '🌟 Champions — Fase regular → Top 8 → Cuartos → Semis → Final',
                                    ])
                                    ->helperText('Define la estructura y las fases del campeonato.')
                                    ->native(false),
                            ]),
                            
            Section::make('Visibilidad')
                ->columns(2)
                ->schema([
                    Toggle::make('is_public')
                        ->label('Visible al público')
                        ->default(true),

                    Toggle::make('is_active')
                        ->label('Activo')
                        ->default(true),
                ]),

        ]);
    }
}
