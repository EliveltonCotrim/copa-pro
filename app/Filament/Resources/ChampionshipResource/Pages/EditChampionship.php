<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Filament\Resources\ChampionshipResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditChampionship extends EditRecord
{
    protected static string $resource = ChampionshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            //Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Campeonato';
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getSavedNotification(): ?Notification
    {
        $championship = $this->record;

        return Notification::make()->success()->title('Campeonato atualizado')->body("O campeonato {$championship->name} foi alterado com sucesso.");
    }
}
