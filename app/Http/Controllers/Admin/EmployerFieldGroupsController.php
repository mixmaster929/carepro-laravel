<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\EmployerFieldGroup;
use Illuminate\Http\Request;

class EmployerFieldGroupsController extends Controller
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
            $employerfieldgroups = EmployerFieldGroup::latest()->paginate($perPage);
        } else {
            $employerfieldgroups = EmployerFieldGroup::latest()->paginate($perPage);
        }

        return view('admin.employer-field-groups.index', compact('employerfieldgroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.employer-field-groups.create');
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
        
        $requestData = $request->all();
        
        EmployerFieldGroup::create($requestData);

        return redirect('admin/employer-field-groups')->with('flash_message', __('site.changes-saved'));
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
        $employerfieldgroup = EmployerFieldGroup::findOrFail($id);

        return view('admin.employer-field-groups.show', compact('employerfieldgroup'));
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
        $employerfieldgroup = EmployerFieldGroup::findOrFail($id);

        return view('admin.employer-field-groups.edit', compact('employerfieldgroup'));
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
        
        $requestData = $request->all();
        
        $employerfieldgroup = EmployerFieldGroup::findOrFail($id);
        $employerfieldgroup->update($requestData);

        return redirect('admin/employer-field-groups')->with('flash_message', __('site.changes-saved'));
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
        EmployerFieldGroup::destroy($id);

        return redirect('admin/employer-field-groups')->with('flash_message', __('site.record-deleted'));
    }
}
