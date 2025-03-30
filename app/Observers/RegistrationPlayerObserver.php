<?php

namespace App\Observers;

use App\Enum\ChampionshipStatusEnum;
use App\Enum\PaymentStatusEnum;
use App\Enum\RegistrationPlayerStatusEnum;
use App\Models\RegistrationPlayer;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Database\Eloquent\Builder;

class RegistrationPlayerObserver implements ShouldHandleEventsAfterCommit
{
    /**
     * Handle the RegistrationPlayer "created" event.
     */
    public function created(RegistrationPlayer $registrationPlayer): void
    {
        //
    }

    /**
     * Handle the RegistrationPlayer "updated" event.
     */
    public function updated(RegistrationPlayer $registrationPlayer): void
    {
        if(!$registrationPlayer->wasChanged('status') && $registrationPlayer->status !== RegistrationPlayerStatusEnum::APPROVED) {
            return;
        }

        $registrationPlayer->load('championship');
        $championship = $registrationPlayer->championship;

        if(!$championship){
            return;
        }

        $totalPlayersApproved = RegistrationPlayer::where('status', RegistrationPlayerStatusEnum::APPROVED)
            ->where('championship_id', $championship->id)
            ->whereHas('payments', function (Builder $query) {
                $query->where('status', PaymentStatusEnum::RECEIVED);
        })->count();

        if($totalPlayersApproved >= $championship->max_players) {
            $championship->update([
                'status' => ChampionshipStatusEnum::REGISTRATION_CLOSED,
            ]);
        }

    }

    /**
     * Handle the RegistrationPlayer "deleted" event.
     */
    public function deleted(RegistrationPlayer $registrationPlayer): void
    {
        //
    }

    /**
     * Handle the RegistrationPlayer "restored" event.
     */
    public function restored(RegistrationPlayer $registrationPlayer): void
    {
        //
    }

    /**
     * Handle the RegistrationPlayer "force deleted" event.
     */
    public function forceDeleted(RegistrationPlayer $registrationPlayer): void
    {
        //
    }
}
