<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
use Illuminate\Support\Facades\DB;

class AuthToken
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
        $token = $request->header('Authorization');

        if (!$token) {
            return response('Unauthorized.', 401);
        }else {
            $check_token = User::where('api_token', $token)->first();
            if (!$check_token) {
                return response('Invalid Token.', 401);
            }else {
                return $next($request);
            }
        }
    }
}
