<?php

namespace App\Filament\Resources\PlayerResource\Pages;

use App\Filament\Resources\PlayerResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreatePlayer extends CreateRecord
{
    protected static string $resource = PlayerResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public function getCreatedNotification(): ?Notification
    {
        $player = $this->record;

        return Notification::make()
            ->success()
            ->title('Jogador criado(a)')
            ->body("<strong>{$player->user->name}</strong> foi criado(a).");
    }
}
