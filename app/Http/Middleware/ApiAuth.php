<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApiAuth
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
        $authToken = trim($request->header('Authorization'));
        $authToken = str_ireplace('Bearer','',$authToken);
        $authToken = trim($authToken);

        //get user
        $now = Carbon::now()->toDateTimeString();
        $user = \App\User::where('api_token',$authToken)->first();

        if(!$user){
            return response()->json([
               'status'=>false,
               'code'=>401,
               'message'=> 'Unauthorized request: '.$authToken
            ]);
          //  return abort(401,'Unauthorized request: '.$authToken);
        }
        return $next($request);
    }
}
