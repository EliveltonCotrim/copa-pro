<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Filament\Resources\ChampionshipResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Championship;
use App\Enum\ChampionshipStatusEnum;

class ListChampionships extends ListRecords
{
    protected static string $resource = ChampionshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Criar campeonato'),
        ];
    }

    public function getTabs(): array
    {
        return [

            "Todos" => Tab::make('Todos')
                ->badge($this->statusCount(null))
                ->badgeColor('info'),

            "Ativo" => Tab::make('Ativo')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ChampionshipStatusEnum::ACTIVE->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::ACTIVE->value))
                ->badgeColor(ChampionshipStatusEnum::ACTIVE->getColor()),

            "Inativo" => Tab::make('Inativo')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ChampionshipStatusEnum::INACTIVE->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::INACTIVE->value))
                ->badgeColor(ChampionshipStatusEnum::INACTIVE->getColor()),

            "Finalizado" => Tab::make('Finalizado')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ChampionshipStatusEnum::FINISHED->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::FINISHED->value))
                ->badgeColor(ChampionshipStatusEnum::FINISHED->getColor()),

            "Em andamento" => Tab::make('Em andamento')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ChampionshipStatusEnum::IN_PROGRESS->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::IN_PROGRESS->value))
                ->badgeColor(ChampionshipStatusEnum::IN_PROGRESS->getColor()),
        ];
    }

    private function statusCount(?int $status): int
    {
        $query = Championship::query();

        if ($status !== null) {
            $query->where('status', $status);
        }

        return $query->count();
    }

}
