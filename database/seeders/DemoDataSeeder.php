<?php

namespace Database\Seeders;

use App\Models\Campeonato;
use App\Models\Deporte;
use App\Models\Equipo;
use App\Models\Inscripcion;
use App\Models\Jugador;
use App\Models\Partido;
use App\Models\Puntuacion;
use App\Models\Resultado;
use App\Models\User;
use App\Models\Lookup\ChampionshipState;
use App\Models\Lookup\MatchState;
use App\Models\Lookup\RegistrationState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Usuarios ────────────────────────────────────────────────────────
        $organizador = User::firstOrCreate(
            ['email' => 'organizador@demo.com'],
            [
                'name'     => 'Carlos Organizador',
                'username' => 'organizador',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $entrenador1 = User::firstOrCreate(
            ['email' => 'entrenador1@demo.com'],
            [
                'name'     => 'Luis Ramírez',
                'username' => 'entrenador1',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        $entrenador2 = User::firstOrCreate(
            ['email' => 'entrenador2@demo.com'],
            [
                'name'     => 'Andrés Gómez',
                'username' => 'entrenador2',
                'password' => Hash::make('password'),
                'is_active' => true,
            ]
        );

        // ─── Deportes ─────────────────────────────────────────────────────────
        $futbol = Deporte::firstOrCreate(
            ['slug' => 'futbol'],
            [
                'nombre'        => 'Fútbol',
                'descripcion'   => 'Deporte de equipo con 11 jugadores por lado',
                'es_equipo'     => true,
                'min_jugadores' => 7,
                'max_jugadores' => 11,
                'is_active'     => true,
            ]
        );

        $baloncesto = Deporte::firstOrCreate(
            ['slug' => 'baloncesto'],
            [
                'nombre'        => 'Baloncesto',
                'descripcion'   => 'Deporte de equipo con 5 jugadores por lado',
                'es_equipo'     => true,
                'min_jugadores' => 5,
                'max_jugadores' => 5,
                'is_active'     => true,
            ]
        );

        // ─── Estado del campeonato ────────────────────────────────────────────
        $estadoEnCurso      = ChampionshipState::where('code', 'ongoing')->first();
        $estadoInscripciones = ChampionshipState::where('code', 'registration')->first();
        $estadoPartidoFin   = MatchState::where('code', 'finished')->first();
        $estadoPartidoProg  = MatchState::where('code', 'scheduled')->first();
        $estadoInscAprobada = RegistrationState::where('code', 'approved')->first();

        // ─── Campeonatos ──────────────────────────────────────────────────────
        $copa = Campeonato::firstOrCreate(
            ['slug' => 'copa-bogota-2026'],
            [
                'nombre'         => 'Copa Bogotá 2026',
                'deporte_id'     => $futbol->id,
                'organizador_id' => $organizador->id,
                'creado_por'     => $organizador->id,
                'descripcion'    => 'Torneo distrital de fútbol categoría adultos',
                'fecha_inicio'   => '2026-03-01',
                'fecha_fin'      => '2026-06-30',
                'estado_id'      => $estadoEnCurso?->id,
                'formato'        => 'Liga todos contra todos',
                'lugar'          => 'Bogotá, Colombia',
                'puntos_ganado'  => 3,
                'puntos_empate'  => 1,
                'puntos_perdida' => 0,
                'max_equipos'    => 8,
                'is_active'      => true,
                'is_public'      => true,
            ]
        );

        $ligaBogota = Campeonato::firstOrCreate(
            ['slug' => 'liga-baloncesto-bogota-2026'],
            [
                'nombre'         => 'Liga de Baloncesto Bogotá 2026',
                'deporte_id'     => $baloncesto->id,
                'organizador_id' => $organizador->id,
                'creado_por'     => $organizador->id,
                'descripcion'    => 'Liga distrital de baloncesto',
                'fecha_inicio'   => '2026-04-01',
                'fecha_fin'      => '2026-08-31',
                'estado_id'      => $estadoInscripciones?->id,
                'formato'        => 'Grupos + eliminatorias',
                'lugar'          => 'Bogotá, Colombia',
                'puntos_ganado'  => 2,
                'puntos_empate'  => 0,
                'puntos_perdida' => 1,
                'max_equipos'    => 6,
                'is_active'      => true,
                'is_public'      => true,
            ]
        );

        // ─── Equipos ──────────────────────────────────────────────────────────
        $atletico = Equipo::firstOrCreate(
            ['campeonato_id' => $copa->id, 'nombre' => 'Atlético Bogotá'],
            [
                'tag'            => 'ATB',
                'entrenador_id'  => $entrenador1->id,
                'descripcion'    => 'Club tradicional de la capital',
                'color_primario' => '#EF4444',
                'ciudad'         => 'Bogotá',
                'is_active'      => true,
            ]
        );

        $deportivo = Equipo::firstOrCreate(
            ['campeonato_id' => $copa->id, 'nombre' => 'Deportivo Norte'],
            [
                'tag'            => 'DNO',
                'entrenador_id'  => $entrenador2->id,
                'descripcion'    => 'Equipo del norte de la ciudad',
                'color_primario' => '#3B82F6',
                'ciudad'         => 'Bogotá',
                'is_active'      => true,
            ]
        );

        $central = Equipo::firstOrCreate(
            ['campeonato_id' => $copa->id, 'nombre' => 'Real Central'],
            [
                'tag'        => 'RCE',
                'descripcion' => 'Equipo del centro histórico',
                'color_primario' => '#10B981',
                'ciudad'     => 'Bogotá',
                'is_active'  => true,
            ]
        );

        // ─── Jugadores de Atlético Bogotá ─────────────────────────────────────
        $jugadoresAtletico = [
            ['nombre' => 'Juan',   'apellido' => 'García',   'dorsal' => 1],
            ['nombre' => 'Pedro',  'apellido' => 'Martínez', 'dorsal' => 5],
            ['nombre' => 'Carlos', 'apellido' => 'López',    'dorsal' => 9],
            ['nombre' => 'Miguel', 'apellido' => 'Torres',   'dorsal' => 10],
            ['nombre' => 'Andrés', 'apellido' => 'Herrera',  'dorsal' => 11],
        ];

        foreach ($jugadoresAtletico as $datos) {
            Jugador::firstOrCreate(
                ['equipo_id' => $atletico->id, 'dorsal' => $datos['dorsal']],
                array_merge($datos, ['equipo_id' => $atletico->id, 'is_active' => true])
            );
        }

        // ─── Jugadores de Deportivo Norte ────────────────────────────────────
        $jugadoresDeportivo = [
            ['nombre' => 'Luis',   'apellido' => 'Ramírez',  'dorsal' => 1],
            ['nombre' => 'Diego',  'apellido' => 'Vargas',   'dorsal' => 7],
            ['nombre' => 'Felipe', 'apellido' => 'Castro',   'dorsal' => 8],
            ['nombre' => 'Sergio', 'apellido' => 'Mora',     'dorsal' => 9],
            ['nombre' => 'David',  'apellido' => 'Nieto',    'dorsal' => 11],
        ];

        foreach ($jugadoresDeportivo as $datos) {
            Jugador::firstOrCreate(
                ['equipo_id' => $deportivo->id, 'dorsal' => $datos['dorsal']],
                array_merge($datos, ['equipo_id' => $deportivo->id, 'is_active' => true])
            );
        }

        // ─── Inscripciones ────────────────────────────────────────────────────
        foreach ([$atletico, $deportivo, $central] as $equipo) {
            Inscripcion::firstOrCreate(
                ['campeonato_id' => $copa->id, 'equipo_id' => $equipo->id],
                ['estado_id' => $estadoInscAprobada?->id, 'fecha_inscripcion' => now()]
            );

            // Crear puntuación inicial si no existe
            Puntuacion::firstOrCreate(
                ['campeonato_id' => $copa->id, 'equipo_id' => $equipo->id],
                []
            );
        }

        // ─── Partidos ─────────────────────────────────────────────────────────
        $partido1 = Partido::firstOrCreate(
            [
                'campeonato_id'       => $copa->id,
                'equipo_local_id'     => $atletico->id,
                'equipo_visitante_id' => $deportivo->id,
                'jornada'             => 1,
            ],
            [
                'fase'      => 'Liga',
                'fecha_hora' => '2026-03-08 15:00:00',
                'lugar'     => 'Estadio El Campín',
                'estado_id' => $estadoPartidoFin?->id,
                'creado_por' => $organizador->id,
            ]
        );

        $partido2 = Partido::firstOrCreate(
            [
                'campeonato_id'       => $copa->id,
                'equipo_local_id'     => $central->id,
                'equipo_visitante_id' => $atletico->id,
                'jornada'             => 2,
            ],
            [
                'fase'       => 'Liga',
                'fecha_hora' => '2026-03-15 16:00:00',
                'lugar'      => 'Estadio La Independencia',
                'estado_id'  => $estadoPartidoProg?->id,
                'creado_por' => $organizador->id,
            ]
        );

        // ─── Resultados ───────────────────────────────────────────────────────
        if ($partido1->wasRecentlyCreated || ! $partido1->resultado) {
            $resultado = Resultado::firstOrCreate(
                ['partido_id' => $partido1->id],
                [
                    'goles_local'     => 2,
                    'goles_visitante' => 1,
                    'ganador_id'      => $atletico->id,
                    'es_oficial'      => true,
                    'registrado_por'  => $organizador->id,
                ]
            );

            // Recalcular puntuaciones
            Puntuacion::where('campeonato_id', $copa->id)
                ->where('equipo_id', $atletico->id)
                ->first()?->recalcular();

            Puntuacion::where('campeonato_id', $copa->id)
                ->where('equipo_id', $deportivo->id)
                ->first()?->recalcular();
        }

        $this->command->info('✅ Datos de demo creados correctamente.');
        $this->command->info('   Campeonatos: Copa Bogotá 2026, Liga Baloncesto Bogotá 2026');
        $this->command->info('   Equipos: Atlético Bogotá, Deportivo Norte, Real Central');
        $this->command->info('   Usuario organizador: organizador@demo.com / password');
    }
}
