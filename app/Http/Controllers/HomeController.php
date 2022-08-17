<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        if($user->role_id==1){
            return redirect()->route('admin.dashboard');
        }
        elseif($user->role_id==2){
            return redirect()->route('employer.dashboard');
        }
        elseif($user->role_id==3){
            return redirect()->route('candidate.dashboard');
        }
    }

    public function profile(){
        $user = Auth::user();
        if($user->role_id==1){
            return redirect()->route('admin.profile');
        }

        elseif($user->role_id==2){
            return redirect()->route('employer.profile');
        }

        elseif($user->role_id==3){
            return redirect()->route('candidate.profile');
        }
    }
}
