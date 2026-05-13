<?php
namespace App\Filament\Resources\Equipos\Schemas;

use App\Models\Campeonato;
use App\Models\User;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class EquipoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Datos del equipo')
                ->columns(2)
                ->schema([
                    Select::make('campeonato_id')
                        ->label('Campeonato')
                        ->options(Campeonato::active()->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->columnSpanFull(),

                    TextInput::make('nombre')
                        ->label('Nombre del equipo')
                        ->required()
                        ->maxLength(200),

                    TextInput::make('tag')
                        ->label('Abreviatura (ej. ATB)')
                        ->maxLength(10),

                    Select::make('entrenador_id')
                        ->label('Entrenador')
                        ->options(User::where('is_active', true)->pluck('name', 'id'))
                        ->searchable()
                        ->nullable(),

                    TextInput::make('ciudad')
                        ->label('Ciudad')
                        ->maxLength(100),

                    Textarea::make('descripcion')
                        ->label('Descripción')
                        ->rows(2)
                        ->columnSpanFull()
                        ->nullable(),
                ]),

            Section::make('Identidad visual')
                ->columns(2)
                ->collapsed()
                ->schema([
                    FileUpload::make('logo_path')
                        ->label('Logo del equipo')
                        ->image()
                        ->directory('equipos/logos')
                        ->disk('public')
                        ->nullable(),

                    ColorPicker::make('color_primario')
                        ->label('Color primario'),

                    ColorPicker::make('color_secundario')
                        ->label('Color secundario'),
                ]),

            Toggle::make('is_active')
                ->label('Equipo activo')
                ->default(true),
        ]);
    }
}
