<x-filament-panels::page>

    {{-- Selector de campeonato --}}
    <div class="mb-6 max-w-sm">
        <label class="block text-sm font-medium text-gray-700 mb-1">
            Campeonato
        </label>
        <select
            wire:model.live="campeonatoId"
            class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:ring-green-500 focus:border-green-500"
        >
            @foreach($this->campeonatos as $id => $nombre)
                <option value="{{ $id }}">{{ $nombre }}</option>
            @endforeach
        </select>
    </div>

    @if(!$this->campeonato)
        <div class="text-center py-12 text-gray-400">
            No hay campeonatos activos.
        </div>
    @else
        @php $c = $this->campeonato; @endphp

        {{-- Info general --}}
        <x-filament::card class="mb-4">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-2">

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Formato</p>
                    <p class="font-semibold text-gray-800">
                        {{ match($c->formato) {
                            'liga_normal'    => '🏆 Liga Normal',
                            'cuadrangulares' => '⚽ Cuadrangulares',
                            'champions'      => '🌟 Champions',
                            default          => $c->formato ?? '—'
                        } }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Período</p>
                    <p class="font-semibold text-gray-800">
                        @if($c->fecha_inicio && $c->fecha_fin)
                            {{ $c->fecha_inicio->format('d/m/Y') }} — {{ $c->fecha_fin->format('d/m/Y') }}
                        @else
                            —
                        @endif
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Lugar</p>
                    <p class="font-semibold text-gray-800">{{ $c->lugar ?? '—' }}</p>
                </div>

            </div>
        </x-filament::card>

        {{-- Sistema de puntuación --}}
        <x-filament::card class="mb-4">
            <div class="p-2">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">
                    Sistema de puntuación
                </h3>
                <div class="grid grid-cols-3 gap-3 text-center">
                    <div class="bg-green-50 rounded-xl py-3">
                        <p class="text-2xl font-bold text-green-600">
                            {{ $c->puntos_ganado }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Victoria</p>
                    </div>
                    <div class="bg-yellow-50 rounded-xl py-3">
                        <p class="text-2xl font-bold text-yellow-500">
                            {{ $c->puntos_empate }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Empate</p>
                    </div>
                    <div class="bg-red-50 rounded-xl py-3">
                        <p class="text-2xl font-bold text-red-500">
                            {{ $c->puntos_perdida }}
                        </p>
                        <p class="text-xs text-gray-500 mt-1">Derrota</p>
                    </div>
                </div>
            </div>
        </x-filament::card>

        {{-- Reglas adicionales --}}
        @if($c->reglas && count($c->reglas) > 0)
        <x-filament::card>
            <div class="p-2">
                <h3 class="text-sm font-semibold text-gray-700 uppercase tracking-wide mb-3">
                    Reglas adicionales
                </h3>
                <ul class="space-y-2">
                    @foreach($c->reglas as $regla)
                        <li class="flex items-start gap-2 text-sm text-gray-600">
                            <span class="text-green-500 mt-0.5">✓</span>
                            {{ $regla }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </x-filament::card>
        @endif

    @endif

</x-filament-panels::page>