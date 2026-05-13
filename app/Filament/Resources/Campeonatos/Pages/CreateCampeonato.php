<?php
namespace App\Filament\Resources\Campeonatos\Pages;
use App\Filament\Resources\Campeonatos\CampeonatoResource;
use Filament\Resources\Pages\CreateRecord;
class CreateCampeonato extends CreateRecord
{
    protected static string $resource = CampeonatoResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creado_por']     = auth()->id();
        $data['puntos_perdida'] = 0;

        // Siempre fútbol
        $futbol = \App\Models\Deporte::firstOrCreate(
            ['slug' => 'futbol'],
            ['nombre' => 'Fútbol', 'es_equipo' => true, 'min_jugadores' => 7, 'max_jugadores' => 11]
        );
        $data['deporte_id'] = $futbol->id;

        return $data;
    }
}
