<?php

namespace App\Console\Commands;

use App\Enum\ChampionshipStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Models\Championship;
use App\Notifications\ChampionshipStartingTomorrow;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Notification;

class NotifyUpcomingChampionships extends Command
{
    protected $signature = 'app:notify-upcoming-championships';
    protected $description = 'Notifica os jogadores sobre campeonatos que começam em 1 dia.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $championships = Championship::whereHas('registrationPlayers', function (Builder $query) {
            $query->with('player.user')->where('status', RegistrationPlayerStatusEnum::APPROVED)
                ->whereNull('notified_about_start_championship_at');
        })->with([
                    'address',
                    'registrationPlayers' => function ($query) {
                        $query->where('status', RegistrationPlayerStatusEnum::APPROVED)
                            ->whereNull('notified_about_start_championship_at')
                            ->with('player.user');
                    }
                ])->whereDate('start_date', $tomorrow)
            ->whereIn('status', [ChampionshipStatusEnum::REGISTRATION_OPEN, ChampionshipStatusEnum::REGISTRATION_CLOSED])
            ->get();

        foreach ($championships as $championship) {
            $usersToNotify = $championship->registrationPlayers->pluck('player.user');

            if ($usersToNotify->isNotEmpty()) {
                // $registrationPlayer->notified_about_start_championship_at = Carbon::now();
                // $registrationPlayer->save();
                
                Notification::send($usersToNotify, new ChampionshipStartingTomorrow($championship));
            }

        }
    }
}
