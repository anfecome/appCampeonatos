<?php
namespace App\Filament\Resources\Jugadores;

use App\Filament\Resources\Jugadores\Pages\CreateJugador;
use App\Filament\Resources\Jugadores\Pages\EditJugador;
use App\Filament\Resources\Jugadores\Pages\ListJugadors;
use App\Filament\Resources\Jugadores\Schemas\JugadorForm;
use App\Filament\Resources\Jugadores\Tables\JugadoresTable;
use App\Models\Jugador;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class JugadorResource extends Resource
{
    protected static ?string $model = Jugador::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;
    protected static ?string $navigationLabel = 'Jugadores';
    protected static string|UnitEnum|null $navigationGroup = 'Fútbol';
    protected static ?int    $navigationSort  = 3;
    protected static ?string $recordTitleAttribute = 'nombre';

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador', 'entrenador']);
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador', 'entrenador']);
    }

    public static function canEdit($record): bool
    {
        $user = auth()->user();
        if ($user->hasRole('admin')) return true;
        if ($user->hasRole('organizador')) {
            return $record->equipo?->campeonato?->organizador_id === $user->id;
        }
        if ($user->hasRole('entrenador')) {
            return $record->equipo?->entrenador_id === $user->id;
        }
        return false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasAnyRole(['admin', 'organizador']);
    }

    public static function form(Schema $schema): Schema
    {
        return JugadorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return JugadoresTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListJugadors::route('/'),
            'create' => CreateJugador::route('/create'),
            'edit'   => EditJugador::route('/{record}/edit'),
        ];
    }
}
