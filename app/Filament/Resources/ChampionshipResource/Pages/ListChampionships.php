<?php

namespace App\Filament\Resources\ChampionshipResource\Pages;

use App\Enum\ChampionshipStatusEnum;
use App\Filament\Resources\ChampionshipResource;
use App\Models\Championship;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

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

            'Todos' => Tab::make('Todos')
                ->badge($this->statusCount(null))
                ->badgeColor('info'),

            'Ativo' => Tab::make('Inscrições abertas')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ChampionshipStatusEnum::REGISTRATION_OPEN->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::REGISTRATION_OPEN->value))
                ->badgeColor(ChampionshipStatusEnum::REGISTRATION_OPEN->getColor()),
            "Inativo" => Tab::make('Inativo')
                ->modifyQueryUsing(fn(Builder $query) => $query->where('status', ChampionshipStatusEnum::INACTIVE->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::INACTIVE->value))
                ->badgeColor(ChampionshipStatusEnum::INACTIVE->getColor()),

            'Finalizado' => Tab::make('Finalizado')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('status', ChampionshipStatusEnum::FINISHED->value))
                ->badge($this->statusCount(ChampionshipStatusEnum::FINISHED->value))
                ->badgeColor(ChampionshipStatusEnum::FINISHED->getColor()),

            'Em andamento' => Tab::make('Em andamento')
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
