<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\EmployerField;
use App\EmployerFieldGroup;
use Illuminate\Http\Request;

class EmployerFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, EmployerFieldGroup $employerFieldGroup)
    {

        $keyword = $request->get('search');
        $perPage = 25;

        $employerfields = $employerFieldGroup->employerFields()->orderBy('sort_order')->paginate($perPage);


        return view('admin.employer-fields.index', compact('employerfields','employerFieldGroup'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(EmployerFieldGroup $employerFieldGroup)
    {
        $sortOrder = $employerFieldGroup->employerFields()->count() + 1;
        return view('admin.employer-fields.create',compact('employerFieldGroup','sortOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,EmployerFieldGroup $employerFieldGroup)
    {
        $this->validate($request,[
            'name'=>'required',
            'type'=>'required',
            'sort_order'=>'integer'
        ]);
        $requestData = $request->all();
        $requestData['employer_field_group_id']= $employerFieldGroup->id;

        EmployerField::create($requestData);

        return redirect()->route('admin.employer-fields.index',['employerFieldGroup'=>$employerFieldGroup->id])->with('flash_message', __('site.changes-saved'));

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
        $employerfield = EmployerField::findOrFail($id);

        return view('admin.employer-fields.show', compact('employerfield'));
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
        $employerfield = EmployerField::findOrFail($id);

        return view('admin.employer-fields.edit', compact('employerfield'));
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
            'name'=>'required',
            'type'=>'required',
            'sort_order'=>'integer'
        ]);
        $requestData = $request->all();

        $employerfield = EmployerField::findOrFail($id);
        $employerfield->update($requestData);

        return redirect()->route('admin.employer-fields.index',['employerFieldGroup'=>$employerfield->employer_field_group_id])->with('flash_message', __('site.changes-saved'));
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
        $groupId = EmployerField::find($id)->employer_field_group_id;
        EmployerField::destroy($id);

        // return redirect('admin/employer-fields')->with('flash_message', __('site.record-deleted'));

        return redirect()->route('admin.employer-fields.index',['employerFieldGroup'=>$groupId])->with('flash_message', __('site.record-deleted'));

    }
}
