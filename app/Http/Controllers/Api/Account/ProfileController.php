<?php

namespace App\Http\Controllers\Api\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{


    public function updatePassword(Request $request){
        $this->validate($request,[
            'password'=>'required|min:8'
        ]);

        $password = Hash::make($request->password);
        $user = $request->user();
        $user->password = $password;
        $user->save();

        return response()->json([
            'status'=>true,
            'message'=>__('site.changes-saved')
        ]);

    }

}
