<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{

    public function index(){
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

    public function password()
    {

        return view('account.profile.password');
    }

    public function updatePassword(Request $request){
        $this->validate($request,[
            'password'=>'required|min:8|confirmed'
        ]);

        $password = Hash::make($request->password);
        $user = Auth::user();
        $user->password = $password;
        $user->save();

        return back()->with('flash_message',__('site.changes-saved'));
    }

}
