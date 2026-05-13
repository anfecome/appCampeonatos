<?php
namespace App\Filament\Resources\Resultados\Pages;

use App\Filament\Resources\Resultados\ResultadoResource;
use App\Models\Lookup\MatchState;
use App\Models\Partido;
use App\Models\Puntuacion;
use App\Services\FixtureService;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditResultado extends EditRecord
{
    protected static string $resource = ResultadoResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function afterSave(): void
    {
        $resultado = $this->record->fresh();
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
        if ($campeonato->formato_torneo === 'liga') return;
        if ($campeonato->fase_actual !== 'regular') return;

        $pendientes = Partido::where('campeonato_id', $campeonato->id)
            ->where('fase', 'Liga — Fase Regular')
            ->whereDoesntHave('resultado', fn ($q) => $q->where('es_oficial', true))
            ->count();

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