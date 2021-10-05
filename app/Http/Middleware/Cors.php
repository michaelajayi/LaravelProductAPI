<?php

namespace App\Http\Middleware;

use Closure;

class Cors
{
    public function handle($request, Closure $next)
    {
        return $next($request)
        ->header('Access-Control-Allow-Origin','*')
        ->header('Access-Control-Allow-Method', 'PUT, GET, POST, DELETE, OPTIONS, PATCH')
        ->header('Access-Control-Allow-Headers', 'Origin, Content-Type, Accept, Authorization, X-Request-With, cache-control')
        ->header('Access-Control-Allow-Credentials', 'true');
    }
}