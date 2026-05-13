<?php

namespace App\Filament\Pages;

use App\Models\Campeonato;
use Filament\Pages\Page;

class Reglamento extends Page
{

    public static function getNavigationIcon(): string|\BackedEnum|null
    {
        return 'heroicon-o-document-text';
    }

    public static function getNavigationLabel(): string
    {
        return 'Reglamento';
    }

    public  function getTitle(): string|\Illuminate\Contracts\Support\Htmlable
    {
        return 'Reglamento del campeonato';
    }

    public static function getNavigationSort(): ?int
    {
        return 6;
    }

    public function getView(): string
    {
        return 'filament.pages.reglamento';
    }

    public ?Campeonato $campeonato = null;
    public array $campeonatos      = [];
    public ?int $campeonatoId      = null;

    public function mount(): void
    {
        $this->campeonatos  = Campeonato::active()
            ->pluck('nombre', 'id')
            ->toArray();

        $primero = Campeonato::active()->latest()->first();
        if ($primero) {
            $this->campeonatoId = $primero->id;
            $this->campeonato   = $primero;
        }
    }

    public function updatedCampeonatoId(): void
    {
        $this->campeonato = Campeonato::find($this->campeonatoId);
    }
}