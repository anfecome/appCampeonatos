<?php
namespace App\Filament\Resources\Resultados\Pages;

use App\Filament\Resources\Resultados\ResultadoResource;
use App\Models\Lookup\MatchState;
use App\Models\Partido;
use App\Models\Puntuacion;
use App\Services\FixtureService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateResultado extends CreateRecord
{
    protected static string $resource = ResultadoResource::class;

    protected function afterCreate(): void
    {
        $resultado = $this->record;
        $partido   = $resultado->partido;

        if (!$partido) return;

        // Marcar partido como finalizado
        $estadoFinalizado = MatchState::where('code', 'finished')->first();
        if ($estadoFinalizado) {
            $partido->update(['estado_id' => $estadoFinalizado->id]);
        }

        // Determinar ganador
        if ($resultado->goles_local > $resultado->goles_visitante) {
            $resultado->update(['ganador_id' => $partido->equipo_local_id]);
        } elseif ($resultado->goles_visitante > $resultado->goles_local) {
            $resultado->update(['ganador_id' => $partido->equipo_visitante_id]);
        } else {
            $resultado->update(['ganador_id' => null]);
        }

        // Recalcular tabla de posiciones
        if ($resultado->es_oficial) {
            foreach ([$partido->equipo_local_id, $partido->equipo_visitante_id] as $equipoId) {
                $puntuacion = Puntuacion::firstOrCreate([
                    'campeonato_id' => $partido->campeonato_id,
                    'equipo_id'     => $equipoId,
                ]);
                $puntuacion->recalcular();
            }

            // Verificar si la fase regular terminó y avanzar automáticamente
            $this->verificarYAvanzarFase($partido->campeonato);
        }
    }

    private function verificarYAvanzarFase($campeonato): void
    {
        // Solo aplica para cuadrangulares y champions
        if ($campeonato->formato_torneo === 'liga') return;

        // Solo actúa si estamos en fase regular
        if ($campeonato->fase_actual !== 'regular') return;

        // Contar partidos de fase regular sin resultado oficial
        $pendientes = Partido::where('campeonato_id', $campeonato->id)
            ->where('fase', 'Liga — Fase Regular')
            ->whereDoesntHave('resultado', fn ($q) => $q->where('es_oficial', true))
            ->count();

        // Si ya no hay pendientes, avanzar automáticamente
        if ($pendientes === 0) {
            $resultado = app(FixtureService::class)->avanzarFase($campeonato);

            if (isset($resultado['generados'])) {
                Notification::make()
                    ->title('⚽ Fase regular completada')
                    ->body($resultado['mensaje'])
                    ->success()
                    ->seconds(8)
                    ->send();
            }
        }
    }
}