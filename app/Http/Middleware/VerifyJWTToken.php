<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;


class VerifyJWTToken
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
        try {
             $user = JWTAuth::toUser($request->header('Authorization'));
        } catch (JWTException $e) {
            if($e instanceof TOkenExpiredException){
                return response()->json([
                    'error' => 'Token Expired',
                    'code' => $e->getStatusCode()
                ], $e->getStatusCode());
            }
            elseif ($e instanceof TokenInvalidException){
                return response()->json([
                    'error' => 'Token Invalid',
                    'code' => $e->getStatusCode()
                ], $e->getStatusCode());
            }
            else {
                return response()->json([
                    'error' => 'No Authentication, Token is Required',
                    'code' => $e->getStatusCode()
                ], $e->getStatusCode());
            }
        }
        return $next($request);
    }
}