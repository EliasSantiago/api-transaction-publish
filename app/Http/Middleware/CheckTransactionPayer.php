<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Contracts\Routing\ResponseFactory;

class CheckTransactionPayer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ResponseFactory $response)
    {
        $payerId = $request->input('payer');
        $user = Auth()->user();

        if ($payerId !== $user->id || (isset($user->cnpj) && !empty($user->cnpj))) {
            return $response->json(['error' => 'Unauthorized.'], 401);
        }

        return $next($request);
    }
}
