<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $campeonato->nombre }} — Campeonatos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }

        .hero-camp {
            background: linear-gradient(135deg, #14532d 0%, #166534 50%, #15803d 100%);
            position: relative; overflow: hidden;
        }
        .hero-camp::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        /* Tabs */
        .tab-btn {
            padding: .6rem 1.2rem;
            font-size: .875rem;
            font-weight: 600;
            border-bottom: 3px solid transparent;
            color: #6b7280;
            transition: all .2s;
            white-space: nowrap;
        }
        .tab-btn:hover { color: #374151; }
        .tab-btn.active { border-color: #16a34a; color: #16a34a; }

        /* Cards fixture */
        .partido-card { transition: box-shadow .2s; }
        .partido-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.07); }

        /* Equipo card */
        .equipo-card { transition: transform .2s, box-shadow .2s; }
        .equipo-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(22,101,52,.1); }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .section-content { animation: fadeIn .3s ease both; }

        @keyframes pulso {
            0%, 100% { opacity: 1; }
            50%       { opacity: .3; }
        }
        .badge-live::before {
            content: '';
            display: inline-block;
            width: 6px; height: 6px;
            background: #4ade80;
            border-radius: 50%;
            margin-right: 5px;
            animation: pulso 1.8s infinite;
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ── NAVBAR ──────────────────────────────────────── --}}
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('inicio') }}" class="flex items-center gap-3 group">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center group-hover:bg-green-700 transition">
                    <span class="text-white text-sm">⚽</span>
                </div>
                <div class="hidden sm:block leading-tight">
                    <p class="font-display text-lg text-gray-900 leading-none tracking-wide">CAMPEONATOS</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Universidad Central</p>
                </div>
            </a>
            <a href="/admin"
               class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
                Panel Admin
            </a>
        </div>
    </header>

    {{-- ── HERO CAMPEONATO ─────────────────────────────── --}}
    <div class="hero-camp text-white py-10 relative z-10">
        <div class="max-w-7xl mx-auto px-4">

            <a href="{{ route('inicio') }}"
               class="inline-flex items-center gap-1 text-green-300 text-sm hover:text-white transition mb-5">
                ← Todos los campeonatos
            </a>

            <div class="flex flex-wrap items-start justify-between gap-6">
                <div>
                    {{-- Formato badge --}}
                    @php
                        [$label, $cls] = match($campeonato->formato_torneo ?? 'liga') {
                            'cuadrangulares' => ['🇨🇴 Cuadrangulares', 'bg-yellow-400/20 text-yellow-300 border-yellow-400/30'],
                            'champions'      => ['⭐ Champions',        'bg-blue-400/20 text-blue-300 border-blue-400/30'],
                            default          => ['🏆 Liga',             'bg-green-400/20 text-green-300 border-green-400/30'],
                        };
                    @endphp
                    <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full border {{ $cls }} mb-3">
                        {{ $label }}
                    </span>

                    <h1 class="font-display text-4xl md:text-5xl tracking-wide leading-none mb-3">
                        {{ strtoupper($campeonato->nombre) }}
                    </h1>

                    <div class="flex flex-wrap gap-3 text-sm text-green-200">
                        @if($campeonato->lugar)
                            <span class="flex items-center gap-1">📍 {{ $campeonato->lugar }}</span>
                        @endif
                        @if($campeonato->fecha_inicio)
                            <span class="flex items-center gap-1">📅 {{ $campeonato->fecha_inicio->format('d/m/Y') }}</span>
                        @endif
                        @if($campeonato->estado)
                            @if($campeonato->estado->code === 'en_curso')
                                <span class="badge-live bg-green-500/20 border border-green-400/30 text-green-300 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    En curso
                                </span>
                            @elseif($campeonato->estado->code === 'finished')
                                <span class="bg-yellow-400/20 border border-yellow-400/30 text-yellow-300 px-2.5 py-0.5 rounded-full text-xs font-medium">
                                    🏆 Finalizado
                                </span>
                            @else
                                <span class="bg-white/10 text-white/70 px-2.5 py-0.5 rounded-full text-xs">
                                    {{ $campeonato->estado->name }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Stats rápidas --}}
                @if($posiciones->isNotEmpty())
                <div class="flex gap-3">
                    <div class="bg-white/10 border border-white/15 rounded-xl px-5 py-3 text-center backdrop-blur-sm">
                        <p class="font-display text-3xl text-white">{{ $posiciones->count() }}</p>
                        <p class="text-green-200 text-xs uppercase tracking-wide mt-0.5">Equipos</p>
                    </div>
                    <div class="bg-white/10 border border-white/15 rounded-xl px-5 py-3 text-center backdrop-blur-sm">
                        <p class="font-display text-3xl text-white">{{ $partidos->flatten()->count() }}</p>
                        <p class="text-green-200 text-xs uppercase tracking-wide mt-0.5">Partidos</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <main class="max-w-7xl mx-auto px-4 py-8 flex-1 w-full">

        {{-- Banner campeón --}}
        @if($campeonato->esFinalizado() && $posiciones->isNotEmpty())
            @php $campeon = $posiciones->first()->equipo; @endphp
            <div class="mb-6 rounded-2xl bg-gradient-to-r from-yellow-400 to-amber-500 shadow-lg px-6 py-5 flex items-center gap-5">
                <div class="text-5xl">🏆</div>
                <div class="flex-1">
                    <p class="text-yellow-900 text-xs font-bold uppercase tracking-widest mb-0.5">Campeón del torneo</p>
                    <p class="text-white text-2xl font-bold">{{ $campeon->nombre }}</p>
                </div>
                @if($campeon->logo_path)
                    <img src="{{ asset('storage/'.$campeon->logo_path) }}"
                         class="w-14 h-14 rounded-full object-cover border-4 border-white/60 shadow">
                @else
                    <div class="w-14 h-14 rounded-full flex items-center justify-center text-xl font-bold text-white border-4 border-white/60"
                         style="background:{{ $campeon->color_primario ?? '#16a34a' }}">
                        {{ substr($campeon->nombre, 0, 1) }}
                    </div>
                @endif
            </div>
        @endif

        {{-- ── TABS ────────────────────────────────────── --}}
        <div class="flex gap-0 border-b border-gray-200 mb-7 overflow-x-auto">
            <button onclick="showTab('posiciones')" id="tab-posiciones" class="tab-btn active">
                📊 Posiciones
            </button>
            <button onclick="showTab('fixture')" id="tab-fixture" class="tab-btn">
                📅 Fixture
            </button>
            <button onclick="showTab('equipos')" id="tab-equipos" class="tab-btn">
                👥 Equipos
            </button>
        </div>

        {{-- ── TABLA DE POSICIONES ─────────────────────── --}}
        <div id="section-posiciones" class="section-content">
            @if($posiciones->isEmpty())
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-3xl">📊</div>
                    <p class="text-gray-500 font-medium">La tabla se actualizará cuando haya resultados oficiales.</p>
                </div>
            @else
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gradient-to-r from-green-700 to-green-600 text-white text-xs uppercase tracking-wider">
                                    <th class="px-4 py-3.5 text-left w-10">#</th>
                                    <th class="px-4 py-3.5 text-left">Equipo</th>
                                    <th class="px-3 py-3.5 text-center">PJ</th>
                                    <th class="px-3 py-3.5 text-center">G</th>
                                    <th class="px-3 py-3.5 text-center">E</th>
                                    <th class="px-3 py-3.5 text-center">P</th>
                                    <th class="px-3 py-3.5 text-center hidden sm:table-cell">GF</th>
                                    <th class="px-3 py-3.5 text-center hidden sm:table-cell">GC</th>
                                    <th class="px-3 py-3.5 text-center hidden sm:table-cell">DG</th>
                                    <th class="px-3 py-3.5 text-center font-bold">Pts</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($posiciones as $i => $p)
                                    @php $esCampeon = $i === 0 && $campeonato->esFinalizado(); @endphp
                                    <tr class="hover:bg-gray-50 transition
                                        {{ $esCampeon ? 'bg-yellow-50' : ($campeonato->formato_torneo !== 'liga' && $i < ($campeonato->clasificados ?? 8) ? 'bg-green-50/60' : '') }}">

                                        <td class="px-4 py-3.5 font-bold {{ $esCampeon ? 'text-yellow-500 text-base' : 'text-gray-300' }}">
                                            @if($esCampeon) 🏆 @else {{ $i + 1 }} @endif
                                        </td>

                                        <td class="px-4 py-3.5">
                                            <a href="{{ route('campeonatos.equipo', [$campeonato->slug, $p->equipo_id]) }}"
                                               class="flex items-center gap-2.5 font-semibold hover:text-green-600 transition {{ $esCampeon ? 'text-yellow-700' : 'text-gray-800' }}">
                                                @if($p->equipo->logo_path)
                                                    <img src="{{ asset('storage/'.$p->equipo->logo_path) }}"
                                                         class="w-7 h-7 rounded-full object-cover {{ $esCampeon ? 'ring-2 ring-yellow-400' : '' }}">
                                                @else
                                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 {{ $esCampeon ? 'ring-2 ring-yellow-400' : '' }}"
                                                          style="background:{{ $p->equipo->color_primario ?? '#16a34a' }}">
                                                        {{ substr($p->equipo->nombre, 0, 1) }}
                                                    </span>
                                                @endif
                                                <span>{{ $p->equipo->nombre }}</span>
                                                @if($esCampeon)
                                                    <span class="text-xs bg-yellow-400 text-yellow-900 font-bold px-2 py-0.5 rounded-full">Campeón</span>
                                                @endif
                                            </a>
                                        </td>

                                        <td class="px-3 py-3.5 text-center text-gray-600">{{ $p->partidos_jugados }}</td>
                                        <td class="px-3 py-3.5 text-center text-gray-600">{{ $p->ganados }}</td>
                                        <td class="px-3 py-3.5 text-center text-gray-600">{{ $p->empatados }}</td>
                                        <td class="px-3 py-3.5 text-center text-gray-600">{{ $p->perdidos }}</td>
                                        <td class="px-3 py-3.5 text-center text-gray-500 hidden sm:table-cell">{{ $p->goles_favor }}</td>
                                        <td class="px-3 py-3.5 text-center text-gray-500 hidden sm:table-cell">{{ $p->goles_contra }}</td>
                                        <td class="px-3 py-3.5 text-center font-medium hidden sm:table-cell
                                            {{ $p->diferencia > 0 ? 'text-green-600' : ($p->diferencia < 0 ? 'text-red-500' : 'text-gray-400') }}">
                                            {{ $p->diferencia > 0 ? '+' : '' }}{{ $p->diferencia }}
                                        </td>
                                        <td class="px-3 py-3.5 text-center font-bold text-lg {{ $esCampeon ? 'text-yellow-600' : 'text-green-700' }}">
                                            {{ $p->puntos }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex flex-wrap gap-4 text-xs text-gray-500">
                        @if($campeonato->esFinalizado())
                            <span>🏆 Campeón del torneo</span>
                        @endif
                        @if($campeonato->formato_torneo !== 'liga')
                            <span class="flex items-center gap-1.5">
                                <span class="w-3 h-3 rounded-sm bg-green-100 border border-green-300 inline-block"></span>
                                Clasificados (top {{ $campeonato->clasificados ?? 8 }})
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        {{-- ── FIXTURE ─────────────────────────────────── --}}
        <div id="section-fixture" class="hidden section-content">
            @if($partidos->isEmpty())
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-3xl">📅</div>
                    <p class="text-gray-500 font-medium">No hay partidos programados aún.</p>
                </div>
            @else
                @foreach($partidos as $fase => $grupoPartidos)
                    <div class="mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-1 h-6 bg-green-500 rounded-full"></span>
                            <h3 class="font-bold text-gray-700 text-base">{{ $fase }}</h3>
                        </div>
                        @foreach($grupoPartidos->groupBy('jornada') as $jornada => $jornadaPartidos)
                            @if($jornada)
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 mt-5 ml-3">
                                    Jornada {{ $jornada }}
                                </p>
                            @endif
                            <div class="space-y-2">
                                @foreach($jornadaPartidos as $partido)
                                    <div class="partido-card bg-white rounded-xl border border-gray-100 px-4 py-3.5 flex items-center gap-4 shadow-sm">

                                        {{-- Local --}}
                                        <div class="flex-1 flex items-center justify-end gap-2">
                                            <span class="font-semibold text-gray-800 text-sm text-right">
                                                {{ $partido->equipoLocal?->nombre ?? '—' }}
                                            </span>
                                            @if($partido->equipoLocal?->logo_path)
                                                <img src="{{ asset('storage/'.$partido->equipoLocal->logo_path) }}"
                                                     class="w-7 h-7 rounded-full object-cover shrink-0">
                                            @else
                                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                                      style="background:{{ $partido->equipoLocal?->color_primario ?? '#16a34a' }}">
                                                    {{ substr($partido->equipoLocal?->nombre ?? '?', 0, 1) }}
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Marcador --}}
                                        <div class="text-center min-w-[80px]">
                                            @if($partido->resultado)
                                                <div class="bg-gray-900 text-white rounded-lg px-3 py-1.5 font-bold text-base inline-flex items-center gap-1.5">
                                                    <span>{{ $partido->resultado->goles_local }}</span>
                                                    <span class="text-gray-500 text-xs">—</span>
                                                    <span>{{ $partido->resultado->goles_visitante }}</span>
                                                </div>
                                            @else
                                                <div class="bg-gray-100 text-gray-500 rounded-lg px-3 py-1.5 text-xs font-medium inline-block">
                                                    {{ $partido->fecha_hora ? $partido->fecha_hora->format('d/m H:i') : 'vs' }}
                                                </div>
                                            @endif
                                            @if($partido->estado)
                                                <div class="text-xs text-gray-400 mt-1">{{ $partido->estado->name }}</div>
                                            @endif
                                        </div>

                                        {{-- Visitante --}}
                                        <div class="flex-1 flex items-center gap-2">
                                            @if($partido->equipoVisitante?->logo_path)
                                                <img src="{{ asset('storage/'.$partido->equipoVisitante->logo_path) }}"
                                                     class="w-7 h-7 rounded-full object-cover shrink-0">
                                            @else
                                                <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0"
                                                      style="background:{{ $partido->equipoVisitante?->color_primario ?? '#6b7280' }}">
                                                    {{ substr($partido->equipoVisitante?->nombre ?? '?', 0, 1) }}
                                                </span>
                                            @endif
                                            <span class="font-semibold text-gray-800 text-sm">
                                                {{ $partido->equipoVisitante?->nombre ?? '—' }}
                                            </span>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>

        {{-- ── EQUIPOS ─────────────────────────────────── --}}
        <div id="section-equipos" class="hidden section-content">
            @if($equipos->isEmpty())
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-3 text-3xl">👥</div>
                    <p class="text-gray-500 font-medium">No hay equipos inscritos aún.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                    @foreach($equipos as $equipo)
                        <a href="{{ route('campeonatos.equipo', [$campeonato->slug, $equipo->id]) }}"
                           class="equipo-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 group">
                            @if($equipo->logo_path)
                                <img src="{{ asset('storage/'.$equipo->logo_path) }}"
                                     class="w-14 h-14 rounded-full object-cover border-2 border-gray-100 shrink-0">
                            @else
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-xl font-bold text-white shrink-0 shadow-sm"
                                     style="background:{{ $equipo->color_primario ?? '#16a34a' }}">
                                    {{ substr($equipo->nombre, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <p class="font-bold text-gray-800 group-hover:text-green-600 transition">
                                    {{ $equipo->nombre }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $equipo->jugadores_count }} jugadores</p>
                                <p class="text-xs text-green-600 font-medium mt-1.5 group-hover:underline">Ver plantel →</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </main>

    <footer class="bg-white border-t border-gray-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-6 flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-2">
                <span class="text-lg">⚽</span>
                <p class="text-xs text-gray-400">Sistema de gestión de campeonatos — Universidad Central</p>
            </div>
            <p class="text-xs text-gray-400">© {{ date('Y') }}</p>
        </div>
    </footer>

    <script>
        function showTab(tab) {
            ['posiciones','fixture','equipos'].forEach(t => {
                document.getElementById('section-' + t).classList.add('hidden');
                document.getElementById('section-' + t).classList.remove('section-content');
                const btn = document.getElementById('tab-' + t);
                btn.classList.remove('active');
            });
            const section = document.getElementById('section-' + tab);
            section.classList.remove('hidden');
            setTimeout(() => section.classList.add('section-content'), 10);
            document.getElementById('tab-' + tab).classList.add('active');
        }
    </script>
</body>
</html>