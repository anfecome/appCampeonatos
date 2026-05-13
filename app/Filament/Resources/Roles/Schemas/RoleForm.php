<?php

namespace App\Filament\Resources\Roles\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\CheckboxList::make('permissions')
                ->relationship('permissions', 'name')
                ->columns(2),
        ]);
    }
}
