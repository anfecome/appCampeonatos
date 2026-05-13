<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $equipo->nombre }} — {{ $campeonato->nombre }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <header class="bg-green-600 shadow">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('inicio') }}" class="text-white font-bold text-lg flex items-center gap-2">
                ⚽ Campeonatos de Fútbol
            </a>
            <a href="/admin" class="bg-white text-green-700 text-sm font-medium px-3 py-1.5 rounded-lg hover:bg-green-50 transition">
                Panel Admin
            </a>
        </div>
    </header>

    {{-- Header del equipo --}}
    <div class="text-white pt-8 pb-10"
         style="background-color: {{ $equipo->color_primario ?? '#16a34a' }}">
        <div class="max-w-7xl mx-auto px-4">
            <a href="{{ route('campeonatos.show', $campeonato->slug) }}"
               class="text-white/70 text-sm hover:text-white mb-3 inline-block">
                ← {{ $campeonato->nombre }}
            </a>
            <div class="flex items-center gap-5">
                @if($equipo->logo_path)
                    <img src="{{ asset('storage/'.$equipo->logo_path) }}"
                         class="w-16 h-16 rounded-full border-2 border-white/30 object-cover">
                @else
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center text-2xl font-bold shrink-0">
                        {{ substr($equipo->nombre, 0, 1) }}
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-bold">{{ $equipo->nombre }}</h1>
                    <div class="flex items-center gap-2 mt-1">
                        @if($equipo->tag)
                            <span class="text-sm bg-white/20 px-2 py-0.5 rounded-full">{{ $equipo->tag }}</span>
                        @endif
                        @if($equipo->ciudad)
                            <span class="text-white/70 text-sm">📍 {{ $equipo->ciudad }}</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-8 flex-1 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Columna izquierda --}}
            <div class="space-y-6">

                {{-- Estadísticas --}}
                @if($puntuacion)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="font-bold text-gray-700 mb-4">Estadísticas</h3>
                        <div class="grid grid-cols-2 gap-3 text-center">
                            @foreach([
                                ['Puntos',   $puntuacion->puntos,           'text-green-600 text-2xl font-bold'],
                                ['PJ',       $puntuacion->partidos_jugados, 'text-gray-700 text-xl font-semibold'],
                                ['Victorias',$puntuacion->ganados,          'text-green-600 text-xl font-semibold'],
                                ['Empates',  $puntuacion->empatados,        'text-yellow-500 text-xl font-semibold'],
                                ['Derrotas', $puntuacion->perdidos,         'text-red-500 text-xl font-semibold'],
                                ['DG',       ($puntuacion->diferencia > 0 ? '+' : '').$puntuacion->diferencia,
                                             ($puntuacion->diferencia >= 0 ? 'text-green-600' : 'text-red-500').' text-xl font-semibold'],
                            ] as [$label, $valor, $clase])
                                <div class="bg-gray-50 rounded-lg p-3">
                                    <div class="{{ $clase }}">{{ $valor }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $label }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Info del equipo --}}
                @if($equipo->entrenador || $equipo->descripcion)
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">
                        <h3 class="font-bold text-gray-700 mb-3">Información</h3>
                        @if($equipo->entrenador)
                            <p class="text-sm text-gray-600">
                                <span class="font-medium">Entrenador:</span>
                                {{ $equipo->entrenador->name }}
                            </p>
                        @endif
                        @if($equipo->descripcion)
                            <p class="text-sm text-gray-500 mt-2">{{ $equipo->descripcion }}</p>
                        @endif
                    </div>
                @endif

            </div>

            {{-- Columna derecha --}}
            <div class="lg:col-span-2 space-y-8">

                {{-- Plantilla --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="font-bold text-gray-700">
                            Plantilla
                            <span class="text-gray-400 font-normal text-sm ml-1">
                                ({{ $equipo->jugadores->count() }} jugadores)
                            </span>
                        </h3>
                    </div>
                    @if($equipo->jugadores->isEmpty())
                        <div class="text-center text-gray-400 py-8 text-sm">
                            No hay jugadores registrados.
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm">
                                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                                    <tr>
                                        <th class="px-4 py-2 text-center w-10">#</th>
                                        <th class="px-4 py-2 text-left">Jugador</th>
                                        <th class="px-4 py-2 text-left">Posición</th>
                                        <th class="px-4 py-2 text-center">Edad</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($equipo->jugadores->sortBy('dorsal') as $jugador)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-2 text-center text-gray-400 font-medium">
                                                {{ $jugador->dorsal ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2">
                                                <div class="flex items-center gap-2">
                                                    @if($jugador->foto_path)
                                                        <img src="{{ asset('storage/'.$jugador->foto_path) }}"
                                                             class="w-7 h-7 rounded-full object-cover">
                                                    @endif
                                                    <span class="font-medium text-gray-800">
                                                        {{ $jugador->nombre_completo }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-4 py-2 text-gray-500">
                                                {{ $jugador->posicion?->name ?? '—' }}
                                            </td>
                                            <td class="px-4 py-2 text-center text-gray-400">
                                                {{ $jugador->edad ? $jugador->edad.' años' : '—' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                {{-- Partidos del equipo --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100">
                        <h3 class="font-bold text-gray-700">Partidos</h3>
                    </div>
                    @if($partidos->isEmpty())
                        <div class="text-center text-gray-400 py-8 text-sm">
                            No hay partidos programados.
                        </div>
                    @else
                        <div class="divide-y divide-gray-50">
                            @foreach($partidos as $partido)
                                @php $esLocal = $partido->equipo_local_id === $equipo->id; @endphp
                                <div class="px-5 py-3 flex items-center gap-3 text-sm">

                                    {{-- Jornada --}}
                                    <div class="text-xs text-gray-400 w-14 shrink-0">
                                        @if($partido->jornada) J{{ $partido->jornada }} @endif
                                        <div class="text-gray-300 text-xs">{{ $partido->fase }}</div>
                                    </div>

                                    {{-- Local --}}
                                    <div class="flex-1 text-right {{ $esLocal ? 'font-semibold text-gray-800' : 'text-gray-500' }}">
                                        {{ $partido->equipoLocal?->nombre }}
                                    </div>

                                    {{-- Marcador --}}
                                    <div class="text-center min-w-[60px]">
                                        @if($partido->resultado)
                                            @php
                                                $gl = $partido->resultado->goles_local;
                                                $gv = $partido->resultado->goles_visitante;
                                                $miGoles  = $esLocal ? $gl : $gv;
                                                $susGoles = $esLocal ? $gv : $gl;
                                                $color = $miGoles > $susGoles
                                                    ? 'text-green-600'
                                                    : ($miGoles < $susGoles ? 'text-red-500' : 'text-yellow-500');
                                            @endphp
                                            <span class="font-bold {{ $color }}">{{ $gl }} — {{ $gv }}</span>
                                        @else
                                            <span class="text-gray-300">vs</span>
                                        @endif
                                    </div>

                                    {{-- Visitante --}}
                                    <div class="flex-1 text-left {{ !$esLocal ? 'font-semibold text-gray-800' : 'text-gray-500' }}">
                                        {{ $partido->equipoVisitante?->nombre }}
                                    </div>

                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 text-center text-xs text-gray-400 py-4 mt-auto">
        Sistema de gestión de campeonatos de fútbol — Universidad Central © {{ date('Y') }}
    </footer>

</body>
</html>