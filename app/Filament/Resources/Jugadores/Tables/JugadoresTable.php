<?php
namespace App\Filament\Resources\Jugadores\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class JugadoresTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('foto_path')
                    ->label('')
                    ->disk('public')
                    ->circular(),

                TextColumn::make('nombre')
                    ->label('Jugador')
                    ->formatStateUsing(fn ($record) => $record->nombre_completo)
                    ->searchable(['nombre', 'apellido'])
                    ->sortable(),

                TextColumn::make('dorsal')
                    ->label('#')
                    ->badge()
                    ->sortable(),

                TextColumn::make('equipo.nombre')
                    ->label('Equipo')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('posicion.name')
                    ->label('Posición')
                    ->badge()
                    ->toggleable(),

                TextColumn::make('fecha_nacimiento')
                    ->label('Edad')
                    ->formatStateUsing(fn ($record) =>
                        $record->edad ? $record->edad . ' años' : '—'
                    )
                    ->toggleable(),

                TextColumn::make('nacionalidad')
                    ->label('País')
                    ->toggleable(isToggledHiddenByDefault: true),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('equipo_id')
                    ->label('Equipo')
                    ->relationship('equipo', 'nombre'),

                SelectFilter::make('posicion_id')
                    ->label('Posición')
                    ->relationship('posicion', 'name'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('apellido');
    }
}
