<?php
namespace App\Filament\Resources\Campeonatos\Pages;
use App\Filament\Resources\Campeonatos\CampeonatoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListCampeonatos extends ListRecords
{
    protected static string $resource = CampeonatoResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
