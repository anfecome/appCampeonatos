<?php
namespace App\Filament\Resources\Partidos\Pages;

use App\Filament\Resources\Partidos\PartidoResource;
use App\Models\Campeonato;
use App\Models\Partido;
use App\Models\Puntuacion;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPartidos extends ListRecords
{
    protected static string $resource = PartidoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Botón para generar fixture automáticamente (todos vs todos)
            Action::make('generar_fixture')
                ->label('Generar fixture')
                ->icon('heroicon-o-sparkles')
                ->color('success')
                ->form([
                    Select::make('campeonato_id')
                        ->label('Campeonato')
                        ->options(Campeonato::active()->pluck('nombre', 'id'))
                        ->required()
                        ->searchable()
                        ->helperText('Se generarán todos los partidos entre los equipos inscritos.'),
                ])
                ->action(function (array $data) {
                    $campeonato = Campeonato::with('equipos')->findOrFail($data['campeonato_id']);
                    $equipos    = $campeonato->equipos()->active()->pluck('id')->toArray();

                    if (count($equipos) < 2) {
                        Notification::make()
                            ->title('Se necesitan al menos 2 equipos inscritos')
                            ->warning()
                            ->send();
                        return;
                    }

                    // Obtener estado "pendiente" del partido
                    $estadoPendiente = \App\Models\Lookup\MatchState::where('code', 'pending')->first();

                    $generados = 0;
                    $jornada   = 1;

                    // Algoritmo round-robin: cada equipo juega contra todos los demás
                    // Si n es impar, se añade un "bye" (equipo fantasma)
                    $n = count($equipos);
                    if ($n % 2 !== 0) {
                        $equipos[] = null; // bye
                        $n++;
                    }

                    $totalJornadas = $n - 1;

                    for ($ronda = 0; $ronda < $totalJornadas; $ronda++) {
                        for ($i = 0; $i < $n / 2; $i++) {
                            $local     = $equipos[$i];
                            $visitante = $equipos[$n - 1 - $i];

                            // Saltar si alguno es el "bye"
                            if ($local === null || $visitante === null) {
                                continue;
                            }

                            // Evitar duplicados
                            $existe = Partido::where('campeonato_id', $campeonato->id)
                                ->where(function ($q) use ($local, $visitante) {
                                    $q->where(fn ($q) =>
                                        $q->where('equipo_local_id', $local)
                                          ->where('equipo_visitante_id', $visitante)
                                    )->orWhere(fn ($q) =>
                                        $q->where('equipo_local_id', $visitante)
                                          ->where('equipo_visitante_id', $local)
                                    );
                                })
                                ->exists();

                            if (!$existe) {
                                Partido::create([
                                    'campeonato_id'       => $campeonato->id,
                                    'equipo_local_id'     => $local,
                                    'equipo_visitante_id' => $visitante,
                                    'jornada'             => $jornada,
                                    'fase'                => 'Liga',
                                    'estado_id'           => $estadoPendiente?->id,
                                    'creado_por'          => auth()->id(),
                                ]);
                                $generados++;
                            }
                        }

                        // Rotar equipos (el primero fijo, los demás rotan)
                        $ultimo  = array_pop($equipos);
                        array_splice($equipos, 1, 0, [$ultimo]);
                        $jornada++;
                    }

                    // Crear filas vacías en puntuaciones para los equipos
                    foreach ($campeonato->equipos()->active()->get() as $equipo) {
                        Puntuacion::firstOrCreate([
                            'campeonato_id' => $campeonato->id,
                            'equipo_id'     => $equipo->id,
                        ]);
                    }

                    Notification::make()
                        ->title("✅ {$generados} partidos generados para {$campeonato->nombre}")
                        ->success()
                        ->send();
                }),

            CreateAction::make()->label('Agregar partido'),
        ];
    }
}
