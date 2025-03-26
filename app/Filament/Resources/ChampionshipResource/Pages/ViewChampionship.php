<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Filament\Resources\ChampionshipResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;

class ViewChampionship extends ViewRecord
{
    protected static string $resource = ChampionshipResource::class;

    protected function getHeaderActions(): array
    {
        return [

            Action::make('view')
                ->label('Voltar')
                ->color('gray')
                ->icon('heroicon-o-arrow-uturn-left')
                ->url(fn ($record) => route('filament.admin.resources.championships.index')),
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Campeonato';
    }
}
