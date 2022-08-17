<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{

    public function index(Request $request){

        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $users = User::orderBy('name')->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
        } else {
            $users = User::orderBy('name');
        }

        if(!empty($request->role)){
            $users = $users->where('role_id',$request->role);
        }

        $users = $users->paginate($perPage);

        $total = User::count();
        return view('admin.users.index',compact('users','total','perPage'));
    }


    public function destroy(User $user){
        $this->authorize('access','manage_settings');
        $user->delete();
        return back()->with('flash_message', __('site.record-deleted'));

    }

}
