<?php

namespace App\Http\Middleware;

use Closure;

class LastLoginMiddleware
{
    /**
     * Handle an incoming request. Triggered when user open a message.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->user()->asAuthenticated($request);

        return $next($request);
    }
}
