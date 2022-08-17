<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiToken
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
        $authToken = trim($request->header('ApiToken'));
        $authToken = str_ireplace('Bearer','',$authToken);
        $authToken = trim($authToken);


        $tokenRecord = \App\ApiToken::where('token',$authToken)->where('enabled',1)->first();

        if(!$tokenRecord){
            return response()->json([
                'status'=>false,
                'code'=>401,
                'message'=> 'Unauthorized api request: '.$authToken
            ]);
            //  return abort(401,'Unauthorized request: '.$authToken);
        }
        return $next($request);
    }
}
