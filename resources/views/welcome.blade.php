<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campeonatos de Fútbol — Universidad Central</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --verde:       #16a34a;
            --verde-dark:  #14532d;
            --verde-light: #22c55e;
            --gris-bg:     #f0fdf4;
        }

        body { font-family: 'Inter', sans-serif; }

        .font-display { font-family: 'Bebas Neue', sans-serif; }

        /* ── Hero ──────────────────────────────────────── */
        .hero {
            background: linear-gradient(135deg, #14532d 0%, #166534 40%, #15803d 100%);
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(34,197,94,.15) 0%, transparent 60%),
                radial-gradient(circle at 80% 20%, rgba(255,255,255,.05) 0%, transparent 50%);
        }
        .hero-grid {
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* ── Campo SVG decorativo ──────────────────────── */
        .campo-deco {
            position: absolute;
            right: -60px;
            top: 50%;
            transform: translateY(-50%);
            width: 520px;
            opacity: .07;
        }

        /* ── Stat chips ────────────────────────────────── */
        .stat-chip {
            background: rgba(255,255,255,.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,.15);
        }

        /* ── Cards ─────────────────────────────────────── */
        .card-campeonato {
            transition: transform .2s, box-shadow .2s;
        }
        .card-campeonato:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(22,101,52,.15);
        }

        /* ── Status badge pulse ────────────────────────── */
        @keyframes pulso {
            0%, 100% { opacity: 1; }
            50%       { opacity: .4; }
        }
        .badge-live::before {
            content: '';
            display: inline-block;
            width: 7px; height: 7px;
            background: #4ade80;
            border-radius: 50%;
            margin-right: 5px;
            animation: pulso 1.8s infinite;
        }

        /* ── Nav fade-in ───────────────────────────────── */
        @keyframes fadeDown {
            from { opacity: 0; transform: translateY(-12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        header { animation: fadeDown .5s ease both; }

        /* ── Hero content ──────────────────────────────── */
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .hero-content > * {
            animation: fadeUp .6s ease both;
        }
        .hero-content > *:nth-child(1) { animation-delay: .1s; }
        .hero-content > *:nth-child(2) { animation-delay: .2s; }
        .hero-content > *:nth-child(3) { animation-delay: .3s; }
        .hero-content > *:nth-child(4) { animation-delay: .4s; }

        /* ── Section fade ──────────────────────────────── */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .section-anim {
            animation: fadeIn .7s ease both;
            animation-delay: .5s;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">

    {{-- ── NAVBAR ─────────────────────────────────────── --}}
    <header class="bg-white border-b border-gray-100 shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">

            {{-- Logo --}}
            <a href="{{ route('inicio') }}" class="flex items-center gap-3 group">
                <div class="w-9 h-9 bg-green-600 rounded-lg flex items-center justify-center shadow-md group-hover:bg-green-700 transition">
                    <span class="text-white text-lg">⚽</span>
                </div>
                <div class="leading-tight">
                    <p class="font-display text-xl text-gray-900 leading-none tracking-wide">CAMPEONATOS</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-widest">Universidad Central</p>
                </div>
            </a>

            {{-- Nav links --}}
            <nav class="flex items-center gap-2">
                <a href="#torneos"
                   class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-green-600 transition rounded-lg hover:bg-green-50">
                    Ver torneos
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}"
                       class="px-4 py-2 text-sm font-semibold text-green-700 bg-green-50 rounded-lg hover:bg-green-100 transition">
                        Mi panel
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-green-700 transition rounded-lg hover:bg-gray-100">
                        Iniciar sesión
                    </a>
                    <a href="/admin"
                       class="px-4 py-2 text-sm font-semibold text-white bg-green-600 rounded-lg hover:bg-green-700 transition shadow-sm">
                        Panel Admin
                    </a>
                @endauth
            </nav>

        </div>
    </header>

    {{-- ── HERO ────────────────────────────────────────── --}}
    <section class="hero py-20 md:py-28">
        <div class="hero-grid"></div>

        {{-- Decoración campo de fútbol --}}
        <svg class="campo-deco" viewBox="0 0 400 260" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="2" width="396" height="256" rx="4" stroke="white" stroke-width="3"/>
            <line x1="200" y1="2" x2="200" y2="258" stroke="white" stroke-width="2"/>
            <circle cx="200" cy="130" r="40" stroke="white" stroke-width="2"/>
            <circle cx="200" cy="130" r="3" fill="white"/>
            <rect x="2" y="80" width="60" height="100" stroke="white" stroke-width="2"/>
            <rect x="338" y="80" width="60" height="100" stroke="white" stroke-width="2"/>
            <rect x="2" y="100" width="28" height="60" stroke="white" stroke-width="2"/>
            <rect x="370" y="100" width="28" height="60" stroke="white" stroke-width="2"/>
            <path d="M62 80 A 60 60 0 0 1 62 180" stroke="white" stroke-width="2" fill="none"/>
            <path d="M338 80 A 60 60 0 0 0 338 180" stroke="white" stroke-width="2" fill="none"/>
        </svg>

        <div class="max-w-7xl mx-auto px-4 relative z-10">
            <div class="max-w-2xl hero-content">

                {{-- Eyebrow --}}
                <div class="inline-flex items-center gap-2 bg-white/10 border border-white/20 text-green-200 text-xs font-semibold uppercase tracking-widest px-3 py-1.5 rounded-full mb-6">
                    <span class="w-1.5 h-1.5 bg-green-400 rounded-full"></span>
                    Sistema de Gestión Deportiva
                </div>

                {{-- Título --}}
                <h1 class="font-display text-6xl md:text-8xl text-white leading-none tracking-wide mb-4">
                    CAMPEONATOS<br>
                    <span class="text-green-400">DE FÚTBOL</span>
                </h1>

                {{-- Descripción --}}
                <p class="text-green-100 text-lg leading-relaxed mb-8 max-w-lg">
                    Consulta resultados, tabla de posiciones y fixture de todos los torneos en tiempo real.
                </p>

                {{-- CTAs --}}
                <div class="flex flex-wrap gap-3">
                    <a href="#torneos"
                       class="px-6 py-3 bg-white text-green-700 font-semibold rounded-xl hover:bg-green-50 transition shadow-lg text-sm">
                        Ver torneos activos →
                    </a>
                    <a href="/admin"
                       class="px-6 py-3 bg-white/10 border border-white/20 text-white font-medium rounded-xl hover:bg-white/20 transition text-sm backdrop-blur-sm">
                        Panel administrador
                    </a>
                </div>

            </div>

            {{-- Stats --}}
            @if($campeonatos->isNotEmpty())
            <div class="flex flex-wrap gap-4 mt-12">
                <div class="stat-chip rounded-xl px-5 py-3 text-center">
                    <p class="font-display text-3xl text-white">{{ $campeonatos->count() }}</p>
                    <p class="text-green-200 text-xs uppercase tracking-wider mt-0.5">Torneos</p>
                </div>
                <div class="stat-chip rounded-xl px-5 py-3 text-center">
                    <p class="font-display text-3xl text-white">{{ $campeonatos->sum('equipos_count') }}</p>
                    <p class="text-green-200 text-xs uppercase tracking-wider mt-0.5">Equipos</p>
                </div>
                <div class="stat-chip rounded-xl px-5 py-3 text-center">
                    <p class="font-display text-3xl text-white">{{ $campeonatos->sum('partidos_count') }}</p>
                    <p class="text-green-200 text-xs uppercase tracking-wider mt-0.5">Partidos</p>
                </div>
            </div>
            @endif

        </div>
    </section>

    {{-- ── TORNEOS ─────────────────────────────────────── --}}
    <main class="max-w-7xl mx-auto px-4 py-14 flex-1 w-full section-anim" id="torneos">

        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="text-green-600 text-xs font-semibold uppercase tracking-widest mb-1">Disponibles</p>
                <h2 class="font-display text-4xl text-gray-900 tracking-wide">TORNEOS ACTIVOS</h2>
            </div>
            @if($campeonatos->isNotEmpty())
                <p class="text-sm text-gray-400">{{ $campeonatos->count() }} {{ $campeonatos->count() == 1 ? 'torneo' : 'torneos' }}</p>
            @endif
        </div>

        @if($campeonatos->isEmpty())
            <div class="text-center py-24">
                <div class="w-20 h-20 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-4xl">⚽</span>
                </div>
                <h3 class="text-gray-700 font-semibold text-lg mb-1">No hay torneos disponibles</h3>
                <p class="text-gray-400 text-sm">Pronto se publicarán nuevos campeonatos.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($campeonatos as $c)
                    @php
                        $esEnCurso = $c->estado?->code === 'en_curso';
                        $esFinalizado = $c->estado?->code === 'finished';
                        [$fmtLabel, $fmtColor] = match($c->formato_torneo ?? 'liga') {
                            'cuadrangulares' => ['Cuadrangulares', 'bg-yellow-100 text-yellow-800'],
                            'champions'      => ['Champions',      'bg-blue-100 text-blue-800'],
                            default          => ['Liga',           'bg-green-100 text-green-700'],
                        };
                    @endphp
                    <a href="{{ route('campeonatos.show', $c->slug) }}"
                       class="card-campeonato bg-white rounded-2xl border border-gray-100 overflow-hidden shadow-sm group block">

                        {{-- Header de la tarjeta --}}
                        <div class="p-5 border-b border-gray-50"
                             style="background: linear-gradient(135deg, #166534, #15803d);">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-white font-bold text-base leading-snug">{{ $c->nombre }}</h3>
                                {{-- Badge estado --}}
                                @if($c->estado)
                                    @if($esEnCurso)
                                        <span class="badge-live shrink-0 text-xs bg-green-500/20 border border-green-400/30 text-green-300 px-2.5 py-1 rounded-full font-medium whitespace-nowrap">
                                            En curso
                                        </span>
                                    @elseif($esFinalizado)
                                        <span class="shrink-0 text-xs bg-yellow-400/20 border border-yellow-400/30 text-yellow-300 px-2.5 py-1 rounded-full font-medium whitespace-nowrap">
                                            🏆 Finalizado
                                        </span>
                                    @else
                                        <span class="shrink-0 text-xs bg-white/10 text-white/70 border border-white/10 px-2.5 py-1 rounded-full font-medium">
                                            {{ $c->estado->name }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                            @if($c->lugar)
                                <p class="text-green-200 text-xs mt-2 flex items-center gap-1">
                                    <span>📍</span> {{ $c->lugar }}
                                </p>
                            @endif
                        </div>

                        {{-- Cuerpo de la tarjeta --}}
                        <div class="p-5 space-y-4">
                            <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-full {{ $fmtColor }}">
                                {{ $fmtLabel }}
                            </span>

                            {{-- Métricas --}}
                            <div class="grid grid-cols-2 gap-3">
                                <div class="bg-gray-50 rounded-xl p-3 text-center">
                                    <p class="font-display text-2xl text-green-700">{{ $c->equipos_count }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Equipos</p>
                                </div>
                                <div class="bg-gray-50 rounded-xl p-3 text-center">
                                    <p class="font-display text-2xl text-green-700">{{ $c->partidos_count }}</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Partidos</p>
                                </div>
                            </div>

                            {{-- Fechas --}}
                            @if($c->fecha_inicio)
                                <p class="text-xs text-gray-400 flex items-center gap-1.5">
                                    <span>📅</span>
                                    {{ $c->fecha_inicio->format('d/m/Y') }}
                                    @if($c->fecha_fin) — {{ $c->fecha_fin->format('d/m/Y') }} @endif
                                </p>
                            @endif

                            {{-- CTA --}}
                            <div class="pt-1">
                                <span class="text-sm font-semibold text-green-600 group-hover:text-green-700 flex items-center gap-1 transition">
                                    Ver tabla y fixture
                                    <span class="group-hover:translate-x-1 transition-transform inline-block">→</span>
                                </span>
                            </div>
                        </div>

                    </a>
                @endforeach
            </div>
        @endif

    </main>

    {{-- ── FOOTER ──────────────────────────────────────── --}}
    <footer class="bg-white border-t border-gray-100 mt-auto">
        <div class="max-w-7xl mx-auto px-4 py-8 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center">
                    <span class="text-white text-sm">⚽</span>
                </div>
                <div>
                    <p class="font-display text-base text-gray-800 tracking-wide leading-none">CAMPEONATOS</p>
                    <p class="text-xs text-gray-400">Universidad Central</p>
                </div>
            </div>
            <p class="text-xs text-gray-400">
                Sistema de gestión de campeonatos de fútbol © {{ date('Y') }}
            </p>
            <a href="/admin"
               class="text-xs text-green-600 font-medium hover:text-green-700 transition">
                Panel administrador →
            </a>
        </div>
    </footer>

</body>
</html>