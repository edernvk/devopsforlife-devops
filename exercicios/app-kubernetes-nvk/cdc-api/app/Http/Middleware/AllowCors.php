<?php

namespace App\Http\Middleware;

use Closure;

class AllowCors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!\App::environment('testing')) {
            header("Access-Control-Allow-Origin: *");
            $headers = [
                'Access-Control-Expose-Headers' => 'Access-Control-*',
                'Access-Control-Allow-Methods' => 'POST, GET, PUT, DELETE, OPTIONS, HEAD',
                'Access-Control-Allow-Headers' => 'Content-Type, X-Auth-Token, Origin, Authorization, Access-Control-*, Origin, X-Requested-With, Accept',
                'Allow' => 'GET, POST, PUT, DELETE, OPTIONS, HEAD',
                'Vary' => 'Origin',
                'Access-Control-Allow-Credentials' => 'true'
            ];
        }

        $response = $next($request);

        if (!\App::environment('testing')) {
            foreach ($headers as $key => $value)
                $response->headers->set($key, $value);
        }

        return $response;
    }
}
