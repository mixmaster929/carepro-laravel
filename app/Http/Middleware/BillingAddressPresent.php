<?php

namespace App\Http\Middleware;

use App\Lib\HelperTrait;
use Closure;
use Illuminate\Support\Facades\Auth;

class BillingAddressPresent
{
    use HelperTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(setting('order_require_address')==1){
            $user = Auth::user();
            $addresses = $user->billingAddresses()->count();
            if(empty($addresses)){
                $this->warningMessage(__('site.add-billing-msg'));
                return redirect()->route('user.billing-address.create');
            }
        }


        return $next($request);
    }
}
