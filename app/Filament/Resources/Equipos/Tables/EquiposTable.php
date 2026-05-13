<?php
namespace App\Filament\Resources\Equipos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EquiposTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo_path')
                    ->label('')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('nombre')
                    ->label('Equipo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('tag')
                    ->label('Tag')
                    ->badge(),

                TextColumn::make('campeonato.nombre')
                    ->label('Campeonato')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('entrenador.name')
                    ->label('Entrenador')
                    ->toggleable(),

                TextColumn::make('jugadores_count')
                    ->label('Jugadores')
                    ->counts('jugadores')
                    ->badge()
                    ->sortable(),

                ColorColumn::make('color_primario')
                    ->label('Color')
                    ->toggleable(),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('campeonato_id')
                    ->label('Campeonato')
                    ->relationship('campeonato', 'nombre'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('nombre');
    }
}
