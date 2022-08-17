<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApiCandidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {


        $user = $request->user();
        if(!$user){
            return abort(401,'Unauthorized request: '.$user->api_token);
        }

        if ($user->role_id != 3){
            return abort(401,'Unauthorized request. Candidates only: '.$user->api_token);
        }

        return $next($request);
    }
}
