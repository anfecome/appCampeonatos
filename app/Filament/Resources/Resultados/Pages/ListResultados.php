<?php
namespace App\Filament\Resources\Resultados\Pages;
use App\Filament\Resources\Resultados\ResultadoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
class ListResultados extends ListRecords {
    protected static string $resource = ResultadoResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
