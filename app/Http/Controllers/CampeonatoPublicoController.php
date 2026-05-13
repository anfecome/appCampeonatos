<?php

namespace App\Http\Controllers;

use App\Models\Campeonato;
use App\Models\Equipo;
use App\Models\Partido;
use App\Models\Puntuacion;
use Illuminate\View\View;

class CampeonatoPublicoController extends Controller
{
    public function index(): View
    {
        $campeonatos = Campeonato::where('is_active', true)
            ->where('is_public', true)
            ->with(['estado'])
            ->withCount(['equipos', 'partidos'])
            ->latest()
            ->get();

        return view('public.campeonatos.index', compact('campeonatos'));
    }

    public function show(string $slug): View
    {
        $campeonato = Campeonato::where('slug', $slug)
            ->where('is_public', true)
            ->with(['estado', 'organizador'])
            ->firstOrFail();

        $posiciones = Puntuacion::where('campeonato_id', $campeonato->id)
            ->with('equipo')
            ->orderByDesc('puntos')
            ->orderByDesc('diferencia')
            ->orderByDesc('goles_favor')
            ->get();

        $partidos = Partido::where('campeonato_id', $campeonato->id)
            ->with(['equipoLocal', 'equipoVisitante', 'resultado', 'estado'])
            ->orderBy('jornada')
            ->orderBy('fecha_hora')
            ->get()
            ->groupBy('fase');

        $equipos = Equipo::where('campeonato_id', $campeonato->id)
            ->where('is_active', true)
            ->withCount('jugadores')
            ->get();

        return view('public.campeonatos.show', compact(
            'campeonato', 'posiciones', 'partidos', 'equipos'
        ));
    }

    public function equipo(string $slug, int $equipoId): View
    {
        $campeonato = Campeonato::where('slug', $slug)
            ->where('is_public', true)
            ->firstOrFail();

        $equipo = Equipo::where('id', $equipoId)
            ->where('campeonato_id', $campeonato->id)
            ->with(['jugadores.posicion', 'entrenador'])
            ->firstOrFail();

        $puntuacion = $equipo->puntuacionEnCampeonato($campeonato->id)->first();

        $partidos = Partido::where('campeonato_id', $campeonato->id)
            ->where(fn ($q) => $q
                ->where('equipo_local_id', $equipo->id)
                ->orWhere('equipo_visitante_id', $equipo->id)
            )
            ->with(['equipoLocal', 'equipoVisitante', 'resultado', 'estado'])
            ->orderBy('jornada')
            ->get();

        return view('public.campeonatos.equipo', compact(
            'campeonato', 'equipo', 'puntuacion', 'partidos'
        ));
    }
}