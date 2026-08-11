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
            // abort(403, 'Inscrições fechadas.');
            return response()->view('championship.close-inscription', [
                'championship' => $championship,
                'message' => 'Inscrições fechadas.',
                'description' => 'A inscrição para o campeonato foi fechada, portanto, nenhuma nova inscrição pode ser realizada.'
            ], 403);
        }

        $totalPlayersApproved = $championship->registrationPlayers()->where('status', RegistrationPlayerStatusEnum::APPROVED)->whereHas('payments', function (Builder $query) {
            $query->where('status', PaymentStatusEnum::RECEIVED);
        })->count();

        if ($totalPlayersApproved === $championship->max_players && $championship->status === ChampionshipStatusEnum::REGISTRATION_OPEN) {
            // abort(403, 'Inscrições encerradas, limite de jogadores atingido.');
            return response()->view('championship.close-inscription', [
                'championship' => $championship,
                'message' => 'Inscrições encerradas, limite de jogadores atingido.',
                'description' => 'O número máximo de jogadores para este campeonato foi atingido e novas inscrições não podem ser realizadas.'
            ], 403);
        }

        if ($championship->status === ChampionshipStatusEnum::REGISTRATION_OPEN) {
            return $next($request);
        }

        // abort(403, 'Campeonato indisponível para inscrição.');
        return response()->view('championship.close-inscription', [
            'championship' => $championship,
            'message' => 'Campeonato indisponível para inscrição.',
            'description' => 'As inscrições para este campeonato estão encerradas e não é mais possível realizar novas inscrições.'
        ], 403);
    }
}
