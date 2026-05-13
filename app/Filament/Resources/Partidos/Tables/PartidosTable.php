<?php
namespace App\Filament\Resources\Partidos\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartidosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('jornada')
                    ->label('J')
                    ->badge()
                    ->sortable(),

                TextColumn::make('equipoLocal.nombre')
                    ->label('Local')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('marcador')
                    ->label('Marcador')
                    ->badge()
                    ->color('gray')
                    ->getStateUsing(fn ($record) => $record->marcador),

                TextColumn::make('equipoVisitante.nombre')
                    ->label('Visitante')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('estado.name')
                    ->label('Estado')
                    ->badge()
                    ->color(fn ($record) => match ($record->estado?->code) {
                        'live'       => 'success',
                        'finished'   => 'gray',
                        'scheduled'  => 'info',
                        'cancelled'  => 'danger',
                        'suspended'  => 'warning',
                        default      => 'gray',
                    }),

                TextColumn::make('fecha_hora')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('lugar')
                    ->label('Lugar')
                    ->toggleable(),

                TextColumn::make('campeonato.nombre')
                    ->label('Campeonato')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('campeonato_id')
                    ->label('Campeonato')
                    ->relationship('campeonato', 'nombre'),

                SelectFilter::make('estado_id')
                    ->label('Estado')
                    ->relationship('estado', 'name'),
            ])
            ->recordActions([
                EditAction::make(),
                \Filament\Actions\Action::make('registrar_resultado')
                    ->label('Resultado')
                    ->icon('heroicon-o-trophy')
                    ->color('success')
                    ->url(fn ($record) => \App\Filament\Resources\Resultados\ResultadoResource::getUrl('create') . '?partido_id=' . $record->id)
                    ->visible(fn ($record) => !$record->resultado()->exists()),
                \Filament\Actions\Action::make('ver_resultado')
                    ->label('Ver resultado')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->url(fn ($record) => $record->resultado
                        ? \App\Filament\Resources\Resultados\ResultadoResource::getUrl('edit', ['record' => $record->resultado->id])
                        : '#')
                    ->visible(fn ($record) => $record->resultado()->exists()),
                DeleteAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('fecha_hora', 'asc');
    }
}
