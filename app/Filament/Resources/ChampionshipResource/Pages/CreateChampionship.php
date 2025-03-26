<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Filament\Resources\ChampionshipResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateChampionship extends CreateRecord
{
    protected static string $resource = ChampionshipResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getCreatedNotification(): ?Notification
    {
        $campeonato = $this->record;

        return Notification::make()
            ->success()
            ->title('Campeonato criado')
            ->body("<strong>{$campeonato->name}</strong> foi criado.");
    }

    public function getTitle(): string
    {
        return 'Criar Campeonato';
    }
}
