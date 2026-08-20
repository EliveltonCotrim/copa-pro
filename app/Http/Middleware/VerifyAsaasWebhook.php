<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyAsaasWebhook
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info('request-all', [
            'assaasToken' => $request->header('asaas-access-token'),
            'expectedToken' => config('asaas.sandbox.webhook_token'),
            'ip' => $request->ip()
        ]);

        $asaasToken = $request->header('asaas-access-token');
        $expectedToken = config('asaas.sandbox.webhook_token');

        if (empty($asaasToken) || !hash_equals((string) $expectedToken, (string) $asaasToken)) {
            abort(401, 'Token de autenticação inválido.');
        }

        // $allowedIps = [
        //     // Production
        //     '52.67.12.206',
        //     '18.230.8.159',
        //     '54.94.136.112',
        //     '54.94.183.101',

        //     // SandBox
        //     '54.233.45.238'
        // ];

        // if (!in_array($request->ip(), $allowedIps)) {
        //     abort(403, 'IP não autorizado.');
        // }

        return $next($request);
    }
}
