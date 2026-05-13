<?php
namespace App\Filament\Resources\Equipos;

use App\Filament\Resources\Equipos\Pages\CreateEquipo;
use App\Filament\Resources\Equipos\Pages\EditEquipo;
use App\Filament\Resources\Equipos\Pages\ListEquipos;
use App\Filament\Resources\Equipos\Schemas\EquipoForm;
use App\Filament\Resources\Equipos\Tables\EquiposTable;
use App\Models\Equipo;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EquipoResource extends Resource
{
    protected static ?string $model = Equipo::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static ?string $navigationLabel = 'Equipos';
    protected static string|UnitEnum|null $navigationGroup = 'Fútbol';
    protected static ?int    $navigationSort  = 2;
    protected static ?string $recordTitleAttribute = 'nombre';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador', 'entrenador']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador']);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('organizador')) {
            return $record->campeonato?->organizador_id === $user->id;
        }
        return false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Schema $schema): Schema
    {
        return EquipoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EquiposTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListEquipos::route('/'),
            'create' => CreateEquipo::route('/create'),
            'edit'   => EditEquipo::route('/{record}/edit'),
        ];
    }
}
