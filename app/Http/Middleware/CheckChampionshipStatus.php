<?php

namespace App\Http\Middleware;

use App\Enum\ChampionshipStatusEnum;
use Closure;
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

        if ($championship->status !== ChampionshipStatusEnum::ACTIVE) {
            abort(403, 'Campeonato indisponível para inscrição.');
        }

        return $next($request);
    }
}
