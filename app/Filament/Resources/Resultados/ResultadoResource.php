<?php
namespace App\Filament\Resources\Resultados;

use App\Filament\Resources\Resultados\Pages\CreateResultado;
use App\Filament\Resources\Resultados\Pages\EditResultado;
use App\Filament\Resources\Resultados\Pages\ListResultados;
use App\Filament\Resources\Resultados\Schemas\ResultadoForm;
use App\Filament\Resources\Resultados\Tables\ResultadosTable;
use App\Models\Resultado;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ResultadoResource extends Resource
{
    protected static ?string $model = Resultado::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static ?string $navigationLabel = 'Resultados';
    protected static string|UnitEnum|null $navigationGroup = 'Fútbol';
    protected static ?int    $navigationSort  = 5;

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
            return $record->partido?->campeonato?->organizador_id === $user->id;
        }
        return false;
    }

    public static function canDelete($record): bool
    {
        return auth()->user()?->hasRole('admin');
    }

    public static function form(Schema $schema): Schema
    {
        return ResultadoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResultadosTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListResultados::route('/'),
            'create' => CreateResultado::route('/create'),
            'edit'   => EditResultado::route('/{record}/edit'),
        ];
    }
}
