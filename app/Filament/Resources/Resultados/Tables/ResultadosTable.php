<?php
namespace App\Filament\Resources\Resultados\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ResultadosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('partido.jornada')
                    ->label('J')
                    ->badge()
                    ->sortable(),

                TextColumn::make('partido.equipoLocal.nombre')
                    ->label('Local')
                    ->searchable(),

                TextColumn::make('marcador')
                    ->label('Marcador')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn ($record) =>
                        "{$record->goles_local} — {$record->goles_visitante}"
                    ),

                TextColumn::make('partido.equipoVisitante.nombre')
                    ->label('Visitante')
                    ->searchable(),

                TextColumn::make('partido.campeonato.nombre')
                    ->label('Campeonato')
                    ->toggleable(),

                IconColumn::make('es_oficial')
                    ->label('Oficial')
                    ->boolean(),

                TextColumn::make('updated_at')
                    ->label('Actualizado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('partido.campeonato_id')
                    ->label('Campeonato')
                    ->relationship('partido.campeonato', 'nombre'),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('updated_at', 'desc');
    }
}
