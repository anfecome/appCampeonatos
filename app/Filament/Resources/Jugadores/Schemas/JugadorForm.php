<?php
namespace App\Filament\Resources\Jugadores\Schemas;

use App\Models\Equipo;
use App\Models\Lookup\Position;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class JugadorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Datos del jugador')
                ->columns(2)
                ->schema([
                    Select::make('equipo_id')
                        ->label('Equipo')
                        ->options(Equipo::active()->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->columnSpanFull(),

                    TextInput::make('nombre')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('apellido')
                        ->label('Apellido')
                        ->maxLength(200),

                    TextInput::make('dorsal')
                        ->label('Número (dorsal)')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(99)
                        ->nullable(),

                    Select::make('posicion_id')
                        ->label('Posición')
                        ->options(Position::active()->ordered()->pluck('name', 'id'))
                        ->searchable()
                        ->nullable(),

                    Select::make('genero')
                        ->label('Género')
                        ->options([
                            'masculino' => 'Masculino',
                            'femenino'  => 'Femenino',
                            'otro'      => 'Otro',
                        ])
                        ->nullable(),

                    DatePicker::make('fecha_nacimiento')
                        ->label('Fecha de nacimiento')
                        ->nullable(),

                    TextInput::make('nacionalidad')
                        ->label('Nacionalidad')
                        ->maxLength(80),
                ]),

            Section::make('Documento')
                ->columns(2)
                ->collapsed()
                ->schema([
                    Select::make('documento_tipo')
                        ->label('Tipo')
                        ->options([
                            'CC'  => 'Cédula de ciudadanía',
                            'TI'  => 'Tarjeta de identidad',
                            'CE'  => 'Cédula de extranjería',
                            'PAS' => 'Pasaporte',
                        ])
                        ->nullable(),

                    TextInput::make('documento_numero')
                        ->label('Número')
                        ->maxLength(60),
                ]),

            Section::make('Foto')
                ->collapsed()
                ->schema([
                    FileUpload::make('foto_path')
                        ->label('Foto del jugador')
                        ->image()
                        ->directory('jugadores/fotos')
                        ->disk('public')
                        ->circleCropper()
                        ->nullable(),
                ]),

            Toggle::make('is_active')
                ->label('Jugador activo')
                ->default(true),
        ]);
    }
}
