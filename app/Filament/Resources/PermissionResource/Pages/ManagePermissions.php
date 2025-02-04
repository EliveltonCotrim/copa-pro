<?php

namespace App\Filament\Resources\PermissionResource\Pages;

use App\Filament\Resources\PermissionResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManagePermissions extends ManageRecords
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label("Criar permissão"),
        ];
    }

    public function getCreatedNotification(): ?Notification
    {
        $permission = $this->record;

        return Notification::make()
            ->success()
            ->title('Permissão criada')
            ->body("<strong>{$permission->name}</strong> foi criada.");
    }


}
