<x-filament-panels::page>

    @if(empty($this->stats))
        <div class="text-center py-12 text-gray-400">
            No hay campeonatos activos con datos aún.
        </div>
    @else
        <p class="text-sm text-gray-500 mb-6">
            Mostrando datos del campeonato: 
            <span class="font-semibold text-gray-700">{{ $this->stats['campeonato'] }}</span>
        </p>

        {{-- Fila 1: Partidos --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">

            <x-filament::card>
                <div class="text-center p-2">
                    <p class="text-3xl font-bold text-green-600">
                        {{ $this->stats['total_partidos'] }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Partidos jugados</p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center p-2">
                    <p class="text-3xl font-bold text-yellow-500">
                        {{ $this->stats['pendientes'] }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Partidos pendientes</p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="text-center p-2">
                    <p class="text-3xl font-bold text-blue-600">
                        {{ $this->stats['total_goles'] }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Goles totales</p>
                </div>
            </x-filament::card>

        </div>

        {{-- Fila 2: Promedios y destacados --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            <x-filament::card>
                <div class="text-center p-2">
                    <p class="text-3xl font-bold text-purple-600">
                        {{ $this->stats['promedio_goles'] }}
                    </p>
                    <p class="text-sm text-gray-500 mt-1">Promedio goles/partido</p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="p-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                        Equipo más victorias
                    </p>
                    <p class="text-base font-semibold text-gray-800">
                        {{ $this->stats['mejor_equipo'] }}
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        {{ $this->stats['mejor_equipo_v'] }} victorias
                    </p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="p-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                        ⚽ Goleador
                    </p>
                    <p class="text-base font-semibold text-gray-800">
                        {{ $this->stats['goleador'] }}
                    </p>
                </div>
            </x-filament::card>

            <x-filament::card>
                <div class="p-2">
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">
                        🟨 Más sancionado
                    </p>
                    <p class="text-base font-semibold text-gray-800">
                        {{ $this->stats['mas_sancionado'] }}
                    </p>
                </div>
            </x-filament::card>

        </div>
    @endif

</x-filament-panels::page>