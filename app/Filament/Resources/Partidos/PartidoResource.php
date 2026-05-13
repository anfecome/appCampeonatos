<?php
namespace App\Filament\Resources\Partidos;

use App\Filament\Resources\Partidos\Pages\CreatePartido;
use App\Filament\Resources\Partidos\Pages\EditPartido;
use App\Filament\Resources\Partidos\Pages\ListPartidos;
use App\Filament\Resources\Partidos\Schemas\PartidoForm;
use App\Filament\Resources\Partidos\Tables\PartidosTable;
use App\Models\Partido;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartidoResource extends Resource
{
    protected static ?string $model = Partido::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;
    protected static ?string $navigationLabel = 'Partidos';
    protected static string|UnitEnum|null $navigationGroup = 'Fútbol';
    protected static ?int    $navigationSort  = 4;
    protected static ?string $recordTitleAttribute = 'titulo';

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
        return PartidoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartidosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPartidos::route('/'),
            'create' => CreatePartido::route('/create'),
            'edit'   => EditPartido::route('/{record}/edit'),
        ];
    }
}
