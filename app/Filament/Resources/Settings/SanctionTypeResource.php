<?php

namespace App\Filament\Resources\Settings;

use App\Models\Lookup\SanctionType;
use App\Filament\Resources\Settings\SanctionTypeResource\Pages;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class SanctionTypeResource extends Resource
{
    protected static ?string $model = SanctionType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    protected static ?string $navigationLabel = 'Sanction Types';

    protected static ?string $modelLabel = 'Sanction Type';

    protected static ?string $pluralModelLabel = 'Sanction Types';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return 'Settings';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->label('Name')
                ->required()
                ->maxLength(100),

            Forms\Components\TextInput::make('code')
                ->label('Code')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(20),

            Forms\Components\Toggle::make('triggers_suspension')
                ->label('Triggers Suspension'),

            Forms\Components\TextInput::make('yellows_to_accumulate')
                ->label('Yellows to Accumulate')
                ->numeric()
                ->nullable(),

            Forms\Components\Toggle::make('is_active')
                ->label('Active')
                ->default(true),

            Forms\Components\TextInput::make('sort_order')
                ->label('Sort Order')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('code')->badge()->sortable(),
                IconColumn::make('triggers_suspension')->boolean()->label('Suspension'),
                TextColumn::make('yellows_to_accumulate')->label('Yellows')->numeric(),
                IconColumn::make('is_active')->boolean()->label('Active'),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->visible(
                            fn($records) =>
                            ! $records->contains(
                                fn($user) => $user->hasRole('admin')
                            )
                        ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSanctionTypes::route('/'),
        ];
    }
}
