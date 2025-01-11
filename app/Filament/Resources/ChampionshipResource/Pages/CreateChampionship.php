<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Filament\Resources\ChampionshipResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateChampionship extends CreateRecord
{
    protected static string $resource = ChampionshipResource::class;

    // protected function getHeaderActions(): array{
    //     return [
    //         Actions\CreateAction::make()->label('Criar Campeonato 2'),
    //     ];
    // }

    public function getTitle(): string
    {
        return 'Criar Campeonato';
    }
}
