<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Enum\RegistrationPlayerStatusEnum;
use App\Filament\Resources\ChampionshipResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;

class EditChampionship extends EditRecord
{
    protected static string $resource = ChampionshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            // Actions\DeleteAction::make(),
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

    protected function beforeSave(): void
    {
        $formMaxPlayers = $this->data['max_players'];

        if ($formMaxPlayers < $this->record->max_players) {
            $countRegisterPlayers = $this->record->registrationPlayers()->whereIn('status', [
                RegistrationPlayerStatusEnum::APPROVED,
                RegistrationPlayerStatusEnum::REGISTERED,
            ])->count();

            if ($countRegisterPlayers > $formMaxPlayers) {
                Notification::make()
                    ->title('Limite de vagas insuficiente')
                    ->body("O campeonato já possui {$countRegisterPlayers} jogadores inscritos. O novo limite não pode ser menor que esse valor.")
                    ->warning()
                    ->persistent()
                    ->send();

                throw new Halt();
            }
        }
    }

    protected function afterSave(): void
    {
        if ($this->record->is_in_person === false) {
            $this->record->address()->forceDelete();
        }
    }
}
