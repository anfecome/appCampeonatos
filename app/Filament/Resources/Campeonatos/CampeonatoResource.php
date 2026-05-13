<?php

namespace App\Filament\Resources\Campeonatos;

use App\Filament\Resources\Campeonatos\Pages\CreateCampeonato;
use App\Filament\Resources\Campeonatos\Pages\EditCampeonato;
use App\Filament\Resources\Campeonatos\Pages\ListCampeonatos;
use App\Filament\Resources\Campeonatos\Schemas\CampeonatoForm;
use App\Filament\Resources\Campeonatos\Tables\CampeonatosTable;
use App\Models\Campeonato;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CampeonatoResource extends Resource
{
    protected static ?string $model = Campeonato::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFlag;
    protected static ?string $navigationLabel = 'Campeonatos';
    protected static string|UnitEnum|null $navigationGroup = 'Fútbol';
    protected static ?int    $navigationSort  = 1;
    protected static ?string $recordTitleAttribute = 'nombre';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador', 'entrenador']);
    }

    // ¿Puede crear?
    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador']);
    }

    // ¿Puede editar?
    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('organizador')) return $record->organizador_id === $user->id;
        return false;
    }

    // ¿Puede eliminar?
    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Schema $schema): Schema
    {
        return CampeonatoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CampeonatosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListCampeonatos::route('/'),
            'create' => CreateCampeonato::route('/create'),
            'edit'   => EditCampeonato::route('/{record}/edit'),
        ];
    }
}
