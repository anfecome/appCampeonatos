<?php

namespace Database\Seeders;

use App\Models\Campeonato;
use App\Models\Deporte;
use App\Models\Equipo;
use App\Models\Jugador;
use App\Models\Partido;
use App\Models\Puntuacion;
use App\Models\Resultado;
use App\Models\Lookup\ChampionshipState;
use App\Models\Lookup\MatchState;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatosPruebaSeeder extends Seeder
{
    public function run(): void
    {
        $futbol = Deporte::firstOrCreate(
            ['slug' => 'futbol'],
            ['nombre' => 'Fútbol', 'es_equipo' => true, 'min_jugadores' => 7, 'max_jugadores' => 11]
        );

        $estadoCamp = ChampionshipState::where('code', 'ongoing')->first();
        $estadoFin  = MatchState::where('code', 'finished')->first();

        $campeonato = Campeonato::create([
            'nombre'         => 'Liga Prueba Bogotá 2025',
            'slug'           => 'liga-prueba-bogota-2025',
            'deporte_id'     => $futbol->id,
            'descripcion'    => 'Campeonato de prueba formato Liga Normal',
            'fecha_inicio'   => now()->subMonths(2),
            'fecha_fin'      => now()->addMonths(1),
            'estado_id'      => $estadoCamp?->id,
            'puntos_ganado'  => 3,
            'puntos_empate'  => 1,
            'puntos_perdida' => 0,
            'formato'        => 'liga_normal',
            'lugar'          => 'Bogotá, Colombia',
            'is_public'      => true,
            'is_active'      => true,
            'creado_por'     => 1,
        ]);

        $nombresEquipos = [
            ['nombre' => 'Atlético Central',   'tag' => 'ACE', 'color' => '#e63946'],
            ['nombre' => 'Deportivo Norte',    'tag' => 'DNO', 'color' => '#2a9d8f'],
            ['nombre' => 'Real Bogotá FC',     'tag' => 'RBF', 'color' => '#e9c46a'],
            ['nombre' => 'Sporting Sur',       'tag' => 'SSR', 'color' => '#264653'],
            ['nombre' => 'Club Unión',         'tag' => 'CUN', 'color' => '#f4a261'],
            ['nombre' => 'Estrella FC',        'tag' => 'EFC', 'color' => '#6a4c93'],
        ];

        $posiciones = ['Portero', 'Defensa', 'Defensa', 'Defensa', 'Defensa',
                       'Mediocampista', 'Mediocampista', 'Mediocampista',
                       'Delantero', 'Delantero', 'Delantero'];

        $equipos = [];
        foreach ($nombresEquipos as $datos) {
            $equipo = Equipo::create([
                'nombre'         => $datos['nombre'],
                'tag'            => $datos['tag'],
                'campeonato_id'  => $campeonato->id,
                'color_primario' => $datos['color'],
                'is_active'      => true,
            ]);

            foreach ($posiciones as $i => $pos) {
                Jugador::create([
                    'equipo_id'  => $equipo->id,
                    'nombre'     => fake('es_CO')->firstName(),
                    'apellido'   => fake('es_CO')->lastName(),
                    'dorsal'     => $i + 1,
                    'genero'     => 'Masculino',
                    'is_active'  => true,
                ]);
            }

            Puntuacion::firstOrCreate([
                'campeonato_id' => $campeonato->id,
                'equipo_id'     => $equipo->id,
            ]);

            $equipos[] = $equipo;
        }

        // Generar todos vs todos (Liga Normal)
        $jornada = 1;
        $partidos = [];
        for ($i = 0; $i < count($equipos); $i++) {
            for ($j = $i + 1; $j < count($equipos); $j++) {
                $partido = Partido::create([
                    'campeonato_id'       => $campeonato->id,
                    'equipo_local_id'     => $equipos[$i]->id,
                    'equipo_visitante_id' => $equipos[$j]->id,
                    'jornada'             => $jornada,
                    'fecha_hora'          => now()->subWeeks(rand(1, 6))->addDays(rand(0, 4)),
                    'lugar'               => 'Estadio El Campín',
                    'estado_id'           => $estadoFin?->id,
                    'fase'                => 'Liga',
                ]);
                $partidos[] = $partido;
                $jornada++;
            }
        }

        // Registrar resultados y recalcular posiciones
        foreach ($partidos as $partido) {
            $golesLocal     = rand(0, 4);
            $golesVisitante = rand(0, 4);

            $ganadorId = null;
            if ($golesLocal > $golesVisitante)      $ganadorId = $partido->equipo_local_id;
            elseif ($golesVisitante > $golesLocal)  $ganadorId = $partido->equipo_visitante_id;

            Resultado::create([
                'partido_id'      => $partido->id,
                'goles_local'     => $golesLocal,
                'goles_visitante' => $golesVisitante,
                'ganador_id'      => $ganadorId,
                'es_oficial'      => true,
                'registrado_por'  => 1,
            ]);
        }

        // Recalcular tabla de posiciones
        foreach ($equipos as $equipo) {
            $puntuacion = Puntuacion::where('campeonato_id', $campeonato->id)
                ->where('equipo_id', $equipo->id)
                ->first();
            $puntuacion?->recalcular();
        }
    }
}