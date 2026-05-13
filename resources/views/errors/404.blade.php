<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página no encontrada — Campeonatos</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Bebas Neue', sans-serif; }

        .bg-hero {
            background: linear-gradient(135deg, #14532d 0%, #166534 50%, #15803d 100%);
            position: relative; overflow: hidden;
        }
        .bg-hero::before {
            content: '';
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 40px 40px;
        }

        @keyframes flotar {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-12px); }
        }
        .flotar { animation: flotar 3s ease-in-out infinite; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim-1 { animation: fadeUp .5s ease both .1s; }
        .anim-2 { animation: fadeUp .5s ease both .2s; }
        .anim-3 { animation: fadeUp .5s ease both .3s; }
        .anim-4 { animation: fadeUp .5s ease both .4s; }
    </style>
</head>
<body class="bg-hero min-h-screen flex flex-col items-center justify-center px-4 text-center">

    {{-- Pelota flotante --}}
    <div class="flotar text-8xl mb-6 anim-1">⚽</div>

    {{-- 404 --}}
    <h1 class="font-display text-[10rem] leading-none text-white/10 select-none absolute anim-1"
        style="font-size: clamp(6rem, 20vw, 14rem);">404</h1>

    <div class="relative z-10">
        <p class="font-display text-5xl md:text-7xl text-white tracking-wide mb-3 anim-2">
            FUERA DE JUEGO
        </p>
        <p class="text-green-200 text-base md:text-lg max-w-sm mx-auto mb-8 anim-3">
            Esta página no existe o fue movida. Vuelve al inicio y sigue disfrutando el torneo.
        </p>

        <div class="flex flex-wrap gap-3 justify-center anim-4">
            <a href="{{ route('inicio') }}"
               class="px-6 py-3 bg-white text-green-700 font-bold rounded-xl hover:bg-green-50 transition shadow-lg text-sm">
                ← Volver al inicio
            </a>
            <a href="/admin"
               class="px-6 py-3 bg-white/10 border border-white/20 text-white font-medium rounded-xl hover:bg-white/20 transition text-sm backdrop-blur-sm">
                Panel Admin
            </a>
        </div>
    </div>

    {{-- Footer mínimo --}}
    <p class="absolute bottom-6 text-green-400/60 text-xs">
        Universidad Central © {{ date('Y') }}
    </p>

</body>
</html>