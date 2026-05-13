<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('username')
                ->label('Usuario')
                ->unique(ignoreRecord: true)
                ->maxLength(50),

            Forms\Components\FileUpload::make('avatar_path')
                ->label('Avatar')
                ->image()
                ->directory('avatars')
                ->disk('public')
                ->imageEditor()
                ->circleCropper(),

            Forms\Components\Textarea::make('bio')
                ->label('Biografía')
                ->rows(3)
                ->maxLength(500),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->label('Contraseña')
                ->password()
                ->revealable()
                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                ->dehydrated(fn ($state) => filled($state))
                ->helperText('Al editar, déjalo vacío para no cambiarla.'),

            Forms\Components\TextInput::make('last_login_at')
                ->label('Último acceso')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\TextInput::make('last_login_ip')
                ->label('IP último acceso')
                ->disabled()
                ->dehydrated(false),

            Forms\Components\Select::make('role')
                ->label('Rol')
                ->options([
                    'admin'       => 'Administrador',
                    'organizador' => 'Organizador',
                    'entrenador'  => 'Entrenador',
                    'jugador'     => 'Jugador',
                ])
                ->default(fn($record) => $record?->roles?->first()?->name)
                ->required()
                ->reactive()
                ->afterStateHydrated(
                    fn($state, $set, $record) =>
                    $set('role', $record?->roles?->first()?->name)
                )
                ->afterStateUpdated(function ($state, $set, $record) {
                    if ($record) {
                        $record->syncRoles([$state]);
                    }
                }),
        ]);
    }
}
