<?php

namespace App\Http\Middleware;

use Closure;

class InvoicePresent
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
        $invoiceId = session()->get('invoice');

        if(empty($invoiceId)){
            return redirect()->route('home');
        }
        return $next($request);
    }
}
