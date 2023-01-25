<?php

namespace App\Http\Middleware;

use Closure;

class ApprovedOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        abort_if(!auth()->user()->approved, 401, "Desculpe, você não possui permissão para acessar o sistema.");

        return $next($request);
    }
}
