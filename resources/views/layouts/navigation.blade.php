<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            {{-- Logo --}}
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-8 h-8 bg-green-600 rounded-lg flex items-center justify-center shadow-sm group-hover:bg-green-700 transition">
                        <span class="text-white text-sm">⚽</span>
                    </div>
                    <div class="leading-tight hidden sm:block">
                        <p class="font-bold text-gray-800 text-sm leading-none" style="font-family: 'Bebas Neue', sans-serif; letter-spacing: .05em; font-size: 1.1rem;">CAMPEONATOS</p>
                        <p class="text-gray-400 text-[10px] uppercase tracking-widest leading-none mt-0.5">Universidad Central</p>
                    </div>
                </a>

                {{-- Links de navegación --}}
                <div class="hidden sm:flex items-center gap-1 sm:ms-8">
                    <a href="{{ route('dashboard') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium transition
                              {{ request()->routeIs('dashboard') ? 'bg-green-50 text-green-700' : 'text-gray-500 hover:text-gray-800 hover:bg-gray-50' }}">
                        Inicio
                    </a>
                    <a href="/admin"
                       class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition">
                        Panel Admin
                    </a>
                    <a href="{{ route('inicio') }}"
                       class="px-3 py-2 rounded-lg text-sm font-medium text-gray-500 hover:text-gray-800 hover:bg-gray-50 transition">
                        Vista pública
                    </a>
                </div>
            </div>

            {{-- Usuario --}}
            <div class="hidden sm:flex items-center gap-3">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-700 leading-none">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ Auth::user()->email }}</p>
                </div>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="w-9 h-9 bg-green-600 hover:bg-green-700 rounded-full flex items-center justify-center text-white font-bold text-sm transition shadow-sm">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            👤 &nbsp;Mi perfil
                        </x-dropdown-link>
                        <x-dropdown-link :href="route('inicio')">
                            🌐 &nbsp;Vista pública
                        </x-dropdown-link>
                        <div class="border-t border-gray-100 my-1"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 &nbsp;Cerrar sesión
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Hamburger móvil --}}
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden"
                              stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

        </div>
    </div>

    {{-- Menú móvil --}}
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 px-4 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                Inicio
            </x-responsive-nav-link>
            <x-responsive-nav-link href="/admin">
                Panel Admin
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('inicio')">
                Vista pública
            </x-responsive-nav-link>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-100 px-4">
            <p class="font-semibold text-gray-800 text-sm">{{ Auth::user()->name }}</p>
            <p class="text-xs text-gray-400">{{ Auth::user()->email }}</p>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    Mi perfil
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>