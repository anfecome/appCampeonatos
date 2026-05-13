<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        $permissions = [
            // Usuarios
            'users.view', 'users.create', 'users.edit', 'users.delete',
            // Roles
            'roles.view', 'roles.create', 'roles.edit', 'roles.delete',
            // Campeonatos
            'campeonatos.view', 'campeonatos.create', 'campeonatos.edit', 'campeonatos.delete',
            'campeonatos.generar_fixture',
            // Equipos
            'equipos.view', 'equipos.create', 'equipos.edit', 'equipos.delete',
            // Jugadores
            'jugadores.view', 'jugadores.create', 'jugadores.edit', 'jugadores.delete',
            // Partidos
            'partidos.view', 'partidos.create', 'partidos.edit', 'partidos.delete',
            // Resultados
            'resultados.view', 'resultados.create', 'resultados.edit', 'resultados.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // ADMIN — acceso total
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // ORGANIZADOR — gestiona su campeonato asignado
        $organizador = Role::firstOrCreate(['name' => 'organizador']);
        $organizador->givePermissionTo([
            'campeonatos.view', 'campeonatos.edit', 'campeonatos.generar_fixture',
            'equipos.view', 'equipos.create', 'equipos.edit',
            'jugadores.view',
            'partidos.view', 'partidos.create', 'partidos.edit',
            'resultados.view', 'resultados.create', 'resultados.edit',
        ]);

        // ENTRENADOR — gestiona su equipo
        $entrenador = Role::firstOrCreate(['name' => 'entrenador']);
        $entrenador->givePermissionTo([
            'campeonatos.view',
            'equipos.view', 'equipos.edit',
            'jugadores.view', 'jugadores.create', 'jugadores.edit',
            'partidos.view',
            'resultados.view',
        ]);
    }
}