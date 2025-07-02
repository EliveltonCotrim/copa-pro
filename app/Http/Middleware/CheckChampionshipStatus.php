<?php

namespace App\Http\Middleware;

use App\Enum\{ChampionshipStatusEnum, PaymentStatusEnum, RegistrationPlayerStatusEnum};
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckChampionshipStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $championship = $request->route('championship');

        if ($championship->status === ChampionshipStatusEnum::REGISTRATION_CLOSED) {
            abort(403, 'Inscrições fechadas.');
        }

        $totalPlayersApproved = $championship->registrationPlayers()->where('status', RegistrationPlayerStatusEnum::APPROVED)->whereHas('payments', function (Builder $query) {
            $query->where('status', PaymentStatusEnum::RECEIVED);
        })->count();

        if ($totalPlayersApproved === $championship->max_players && $championship->status === ChampionshipStatusEnum::REGISTRATION_OPEN) {
            abort(403, 'Inscrições encerradas, limite de jogadores atingido.');
        }

        if ($championship->status === ChampionshipStatusEnum::REGISTRATION_OPEN) {
            return $next($request);
        }

        abort(403, 'Campeonato indisponível para inscrição.');
    }
}
