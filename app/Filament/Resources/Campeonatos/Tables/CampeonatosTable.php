<?php

namespace App\Filament\Resources\Campeonatos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CampeonatosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Campeonato')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('estado.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($record) => match ($record->estado?->code) {
                        'ongoing'      => 'success',
                        'registration' => 'info',
                        'finished'     => 'gray',
                        'cancelled'    => 'danger',
                        default        => 'warning',
                    }),

                TextColumn::make('fecha_inicio')
                    ->label('Inicio')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('fecha_fin')
                    ->label('Fin')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('lugar')
                    ->label('Lugar')
                    ->toggleable(),

                TextColumn::make('equipos_count')
                    ->label('Equipos')
                    ->counts('equipos')
                    ->badge()
                    ->sortable(),

                TextColumn::make('partidos_count')
                    ->label('Partidos')
                    ->counts('partidos')
                    ->badge()
                    ->sortable(),

                IconColumn::make('is_public')
                    ->label('Público')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                SelectFilter::make('estado_id')
                    ->label('Estado')
                    ->relationship('estado', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
