<?php

namespace App\Filament\Resources\Permissions\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;

class PermissionForm
{
    public static function configure(Schema $schema): Schema
    {
         return $schema->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->unique(ignoreRecord: true)
                ->helperText('Ejemplo: posts.create'),

            Forms\Components\TextInput::make('guard_name')
                ->default('web')
                ->disabled(),
        ]);
    }
}
