<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class UserLimit
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

        if(saas() && defined('USER_LIMIT')){
            $totalUsers = User::count();
            if($totalUsers > USER_LIMIT && !empty(USER_LIMIT)){
                return redirect()->route('admin.users.index')->with('flash_message',__('site.limit-exceeded'));
            }


        }
        return $next($request);
    }
}
