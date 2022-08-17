<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{

    public function pay(Request $request,$hash){
        $invoice = Invoice::where('hash',$hash)->first();
        if(!$invoice){
            abort(404);
        }

        if(Auth::check()){
            Auth::logout();
        }

        //login user

        $user= $invoice->user;
        Auth::login($user,true);

        //add invoice to session
        session(['invoice'=>$invoice->id]);

        if($request->has('home-url')){
            $url = $request->get('home-url');
             session(['homeUrl'=>$url]);
        }

        return redirect()->route('user.invoice.cart');

    }

}
