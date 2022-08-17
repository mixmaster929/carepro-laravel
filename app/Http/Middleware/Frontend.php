<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Frontend
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
        if(file_exists('../storage/installed')){
            $status = setting('frontend_status');
            if($status=='0'){
                return redirect('/home');
            }

        }


        return $next($request);
    }
}
