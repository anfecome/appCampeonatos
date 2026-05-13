<?php
namespace App\Filament\Resources\Campeonatos\Pages;

use App\Filament\Resources\Campeonatos\CampeonatoResource;
use App\Models\Equipo;
use App\Models\Partido;
use App\Models\Puntuacion;
use App\Models\Lookup\MatchState;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCampeonato extends EditRecord
{
    protected static string $resource = CampeonatoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generar_fixture')
                ->label('⚽ Generar fixture')
                ->color('success')
                ->icon('heroicon-o-calendar-days')
                ->visible(fn () => auth()->user()->hasAnyRole(['admin', 'organizador']))
                ->requiresConfirmation()
                ->modalHeading('Generar fixture automático')
                ->modalDescription('Esto generará todos los partidos según el formato del campeonato. Si ya existen partidos, no se duplicarán.')
                ->modalSubmitActionLabel('Sí, generar')
                ->action(function () {
                    $campeonato = $this->record;
                    $equipos = Equipo::where('campeonato_id', $campeonato->id)
                        ->where('is_active', true)
                        ->get();

                    if ($equipos->count() < 2) {
                        Notification::make()
                            ->title('Se necesitan al menos 2 equipos inscritos.')
                            ->danger()
                            ->send();
                        return;
                    }

                    $estadoPendiente = MatchState::where('code', 'pending')->first();
                    $formato = $campeonato->formato ?? 'liga_normal';
                    $creados = 0;
                    $jornada = 1;

                    // Todos vs todos (Liga Normal, Fase regular de Cuadrangulares y Champions)
                    for ($i = 0; $i < $equipos->count(); $i++) {
                        for ($j = $i + 1; $j < $equipos->count(); $j++) {
                            $existe = Partido::where('campeonato_id', $campeonato->id)
                                ->where('equipo_local_id', $equipos[$i]->id)
                                ->where('equipo_visitante_id', $equipos[$j]->id)
                                ->exists();

                            if (!$existe) {
                                Partido::create([
                                    'campeonato_id'       => $campeonato->id,
                                    'equipo_local_id'     => $equipos[$i]->id,
                                    'equipo_visitante_id' => $equipos[$j]->id,
                                    'jornada'             => $jornada,
                                    'estado_id'           => $estadoPendiente?->id,
                                    'fase'                => 'Fase regular',
                                ]);
                                $creados++;
                            }
                            $jornada++;
                        }
                    }

                    // Inicializar puntuaciones si no existen
                    foreach ($equipos as $equipo) {
                        Puntuacion::firstOrCreate([
                            'campeonato_id' => $campeonato->id,
                            'equipo_id'     => $equipo->id,
                        ]);
                    }

                    Notification::make()
                        ->title("Fixture generado: {$creados} partidos creados.")
                        ->success()
                        ->send();
                }),

            DeleteAction::make()
                ->visible(fn () => auth()->user()->hasRole('admin')),
        ];
    }
}