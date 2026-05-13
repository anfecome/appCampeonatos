<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Campeonatos') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        .hero-auth {
            background: linear-gradient(155deg, #14532d 0%, #166534 50%, #15803d 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-auth::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .hero-auth::after {
            content: '';
            position: absolute;
            bottom: -80px; left: -80px;
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(34,197,94,.2) 0%, transparent 70%);
        }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim { animation: fadeUp .5s ease both; }
        .anim-1 { animation-delay: .1s; }
        .anim-2 { animation-delay: .2s; }
        .anim-3 { animation-delay: .3s; }
    </style>
</head>
<body class="antialiased min-h-screen flex">

    {{-- ── Lado izquierdo — Branding ──────────────────── --}}
    <div class="hero-auth hidden lg:flex lg:w-1/2 flex-col justify-between p-12 relative z-10">

        {{-- Logo --}}
        <a href="{{ route('inicio') }}" class="flex items-center gap-3">
            <div class="w-10 h-10 bg-white/15 border border-white/20 rounded-xl flex items-center justify-center text-xl backdrop-blur-sm">
                ⚽
            </div>
            <div>
                <p class="font-display text-2xl text-white tracking-wide leading-none">CAMPEONATOS</p>
                <p class="text-green-300 text-xs uppercase tracking-widest">Universidad Central</p>
            </div>
        </a>

        {{-- Texto central --}}
        <div>
            <p class="text-green-300 text-sm font-semibold uppercase tracking-widest mb-3">Sistema deportivo</p>
            <h1 class="font-display text-6xl text-white leading-none tracking-wide mb-4">
                GESTIÓN<br>DE<br><span class="text-green-400">FÚTBOL</span>
            </h1>
            <p class="text-green-200 text-base leading-relaxed max-w-xs">
                Administra campeonatos, equipos, jugadores y resultados desde un solo lugar.
            </p>
        </div>

        {{-- Features --}}
        <div class="space-y-3">
            @foreach(['Tabla de posiciones en tiempo real', 'Fixture y resultados', 'Gestión de equipos y jugadores'] as $feat)
            <div class="flex items-center gap-3">
                <div class="w-6 h-6 bg-green-500/30 border border-green-400/30 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-3 h-3 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-green-100 text-sm">{{ $feat }}</span>
            </div>
            @endforeach
        </div>

    </div>

    {{-- ── Lado derecho — Formulario ───────────────────── --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center items-center bg-gray-50 px-6 py-12">

        {{-- Logo móvil --}}
        <a href="{{ route('inicio') }}" class="flex lg:hidden items-center gap-2 mb-8">
            <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center text-white">⚽</div>
            <span class="font-display text-xl text-gray-800 tracking-wide">CAMPEONATOS</span>
        </a>

        <div class="w-full max-w-sm anim anim-1">

            {{-- Card del formulario --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                {{ $slot }}
            </div>

            {{-- Link de vuelta --}}
            <p class="text-center text-xs text-gray-400 mt-6">
                <a href="{{ route('inicio') }}" class="hover:text-green-600 transition">
                    ← Volver a los campeonatos
                </a>
            </p>

        </div>
    </div>

</body>
</html>