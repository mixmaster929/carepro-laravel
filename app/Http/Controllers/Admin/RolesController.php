<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\AdminRole;
use Illuminate\Http\Request;

class RolesController extends Controller
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

        if (!empty($keyword)) {
            $roles = AdminRole::latest()->paginate($perPage);
        } else {
            $roles = AdminRole::latest()->paginate($perPage);
        }

        return view('admin.roles.index', compact('roles','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.roles.create');
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
            'name'=>'required'
        ]);
        $requestData = $request->all();
        
        $role = AdminRole::create($requestData);
        unset($requestData['name'],$requestData['_token']);
        $role->permissions()->attach($requestData);

        return redirect('admin/roles')->with('flash_message', __('site.changes-saved'));
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
        $role = AdminRole::findOrFail($id);

        return view('admin.roles.show', compact('role'));
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
        if($id ==1 ){
            return back()->with('flash_message',__('site.no-role-modify'));
        }
        $role = AdminRole::findOrFail($id);

        return view('admin.roles.edit', compact('role'));
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
        $this->validate($request,[
            'name'=>'required'
        ]);
        if($id ==1 ){
            return back()->with('flash_message',__('site.no-role-modify'));
        }
        $requestData = $request->all();

        
        $role = AdminRole::findOrFail($id);
        $role->update($requestData);

        unset($requestData['name'],$requestData['_token'],$requestData['_method']);
        $role->permissions()->sync($requestData);

        return redirect('admin/roles')->with('flash_message', __('site.changes-saved'));
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
        if($id < 3){
            return back()->with('flash_message',__('site.no-role-delete'));
        }
        AdminRole::destroy($id);

        return redirect('admin/roles')->with('flash_message', __('site.record-deleted'));
    }
}
