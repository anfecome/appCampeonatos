<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Panel de Usuario
        </h2>
    </x-slot>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap');
        .font-display { font-family: 'Bebas Neue', sans-serif; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .anim { animation: fadeUp .5s ease both; }
        .anim-1 { animation-delay: .05s; }
        .anim-2 { animation-delay: .15s; }
        .anim-3 { animation-delay: .25s; }
        .anim-4 { animation-delay: .35s; }
        .card-hover { transition: transform .2s, box-shadow .2s; }
        .card-hover:hover { transform: translateY(-3px); box-shadow: 0 12px 28px rgba(22,101,52,.12); }
    </style>

    <div class="py-10 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- ── Bienvenida ─────────────────────────────── --}}
            <div class="anim anim-1 bg-gradient-to-r from-green-700 to-green-600 rounded-2xl p-7 flex items-center justify-between shadow-md overflow-hidden relative">
                <div class="absolute right-0 top-0 bottom-0 w-64 opacity-10"
                     style="background: radial-gradient(circle at 80% 50%, white 0%, transparent 70%)"></div>
                <div>
                    <p class="text-green-200 text-sm font-medium mb-1">Bienvenido de nuevo</p>
                    <h1 class="font-display text-4xl text-white tracking-wide">
                        {{ strtoupper(Auth::user()->name) }}
                    </h1>
                    <p class="text-green-200 text-sm mt-1">{{ Auth::user()->email }}</p>
                </div>
                <div class="hidden sm:flex flex-col items-end gap-2">
                    <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-4xl border border-white/20">
                        ⚽
                    </div>
                    <p class="text-green-300 text-xs">{{ now()->format('d/m/Y') }}</p>
                </div>
            </div>

            {{-- ── Accesos rápidos ─────────────────────────── --}}
            <div class="anim anim-2">
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest mb-4">Accesos rápidos</p>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">

                    <a href="/admin" class="card-hover bg-white rounded-2xl border border-gray-100 p-5 flex flex-col items-center gap-3 shadow-sm text-center">
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center text-2xl">🏟️</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Panel Admin</p>
                            <p class="text-xs text-gray-400 mt-0.5">Gestionar todo</p>
                        </div>
                    </a>

                    <a href="/admin/campeonatos" class="card-hover bg-white rounded-2xl border border-gray-100 p-5 flex flex-col items-center gap-3 shadow-sm text-center">
                        <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-2xl">🏆</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Campeonatos</p>
                            <p class="text-xs text-gray-400 mt-0.5">Ver y crear</p>
                        </div>
                    </a>

                    <a href="/admin/equipos" class="card-hover bg-white rounded-2xl border border-gray-100 p-5 flex flex-col items-center gap-3 shadow-sm text-center">
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl">👥</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Equipos</p>
                            <p class="text-xs text-gray-400 mt-0.5">Gestionar</p>
                        </div>
                    </a>

                    <a href="{{ route('inicio') }}" class="card-hover bg-white rounded-2xl border border-gray-100 p-5 flex flex-col items-center gap-3 shadow-sm text-center">
                        <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-2xl">🌐</div>
                        <div>
                            <p class="font-semibold text-gray-800 text-sm">Vista pública</p>
                            <p class="text-xs text-gray-400 mt-0.5">Ver como usuario</p>
                        </div>
                    </a>

                </div>
            </div>

            {{-- ── Info de cuenta ──────────────────────────── --}}
            <div class="anim anim-3 grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 bg-green-50 rounded-lg flex items-center justify-center text-sm">👤</span>
                        Mi cuenta
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Nombre</span>
                            <span class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-50">
                            <span class="text-sm text-gray-500">Correo</span>
                            <span class="text-sm font-medium text-gray-800">{{ Auth::user()->email }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <span class="text-sm text-gray-500">Miembro desde</span>
                            <span class="text-sm font-medium text-gray-800">
                                {{ Auth::user()->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    <a href="{{ route('profile.edit') }}"
                       class="mt-4 block w-full text-center text-sm font-semibold text-green-600 bg-green-50 hover:bg-green-100 rounded-xl py-2.5 transition">
                        Editar perfil
                    </a>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
                    <h3 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                        <span class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center text-sm">⚡</span>
                        Acciones
                    </h3>
                    <div class="space-y-2">
                        <a href="/admin"
                           class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 transition group">
                            <span class="text-sm font-medium text-gray-700">Ir al panel de administración</span>
                            <span class="text-gray-300 group-hover:text-green-500 group-hover:translate-x-1 transition-all text-lg">→</span>
                        </a>
                        <a href="{{ route('inicio') }}"
                           class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 transition group">
                            <span class="text-sm font-medium text-gray-700">Ver campeonatos públicos</span>
                            <span class="text-gray-300 group-hover:text-green-500 group-hover:translate-x-1 transition-all text-lg">→</span>
                        </a>
                        <a href="{{ route('profile.edit') }}"
                           class="flex items-center justify-between px-4 py-3 rounded-xl hover:bg-gray-50 transition group">
                            <span class="text-sm font-medium text-gray-700">Editar mi perfil</span>
                            <span class="text-gray-300 group-hover:text-green-500 group-hover:translate-x-1 transition-all text-lg">→</span>
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center justify-between px-4 py-3 rounded-xl hover:bg-red-50 transition group">
                                <span class="text-sm font-medium text-gray-700 group-hover:text-red-600 transition">Cerrar sesión</span>
                                <span class="text-gray-300 group-hover:text-red-400 transition text-lg">↗</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- ── Nota al pie ─────────────────────────────── --}}
            <div class="anim anim-4 text-center py-2">
                <p class="text-xs text-gray-400">
                    Sistema de gestión de campeonatos de fútbol — Universidad Central © {{ date('Y') }}
                </p>
            </div>

        </div>
    </div>
</x-app-layout>