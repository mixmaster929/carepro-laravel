<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Employer
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

        //set url in session
        $url = $request->url();
        session()->put('employer_destination',$url);

        if(!Auth::check()){
            return redirect()->route('login');
        }

        $user = Auth::user();
        if($user->role_id != 2){
            Auth::logout();
            return redirect()->route('login');
        }
        return $next($request);
    }


}
