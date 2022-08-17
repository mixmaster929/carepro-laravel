<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $keyword = $request->get('search');
        $perPage = 25;


            $admins = User::where('role_id',1)->latest()->paginate($perPage);

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.admins.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name'=>'required',
            'email'=>'required|email|string|max:255|unique:users',
            'password'=>'required',
            'roles'=>'required'
        ]);
        $requestData = $request->all();
        $requestData['password']= Hash::make($requestData['password']);
        $requestData['role_id'] = 1;
        
        $admin= User::create($requestData);
        $admin->adminRoles()->attach($request->roles);

        return redirect('admin/admins')->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $admin = User::findOrFail($id);

        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $admin = User::findOrFail($id);

        return view('admin.admins.edit', compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'name'=>'required',
            'email'=>'required|email|string|max:255',
            'roles'=>'required'
        ];


        $user = User::find($id);
        if($user->email != $request->email){
            $rules['email'] = 'required|email|string|max:255|unique:users';
        }

        $this->validate($request,$rules);
        $requestData = $request->all();
        $admin = User::findOrFail($id);

        if(!empty($requestData['password'])){
            $requestData['password']= Hash::make($requestData['password']);
        }
        else{
            $requestData['password'] = $admin->password;
        }


        

        $admin->update($requestData);
        $admin->adminRoles()->sync($request->roles);
        return redirect('admin/admins')->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy($id)
    {
        User::destroy($id);

        return redirect('admin/admins')->with('flash_message', __('site.record-deleted'));
    }
}
