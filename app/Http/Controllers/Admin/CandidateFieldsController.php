<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\CandidateField;
use App\CandidateFieldGroup;
use Illuminate\Http\Request;

class CandidateFieldsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, CandidateFieldGroup $candidateFieldGroup)
    {


        $perPage = 25;

        $candidatefields = $candidateFieldGroup->candidateFields()->orderBy('sort_order')->paginate($perPage);


        return view('admin.candidate-fields.index', compact('candidatefields','candidateFieldGroup'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(CandidateFieldGroup $candidateFieldGroup)
    {
        $sortOrder = $candidateFieldGroup->candidateFields()->count() + 1;
        return view('admin.candidate-fields.create',compact('candidateFieldGroup','sortOrder'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,CandidateFieldGroup $candidateFieldGroup)
    {
        $this->validate($request,[
            'name'=>'required',
            'type'=>'required',
            'sort_order'=>'integer'
        ]);
        $requestData = $request->all();
        $requestData['candidate_field_group_id']= $candidateFieldGroup->id;

        CandidateField::create($requestData);

        return redirect()->route('admin.candidate-fields.index',['candidateFieldGroup'=>$candidateFieldGroup->id])->with('flash_message', __('site.changes-saved'));

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
        $candidatefield = CandidateField::findOrFail($id);

        return view('admin.candidate-fields.show', compact('candidatefield'));
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
        $candidatefield = CandidateField::findOrFail($id);

        return view('admin.candidate-fields.edit', compact('candidatefield'));
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

        $candidatefield = CandidateField::findOrFail($id);
        $candidatefield->update($requestData);

        return redirect()->route('admin.candidate-fields.index',['candidateFieldGroup'=>$candidatefield->candidate_field_group_id])->with('flash_message', __('site.changes-saved'));
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
        $groupId = CandidateField::find($id)->candidate_field_group_id;
        CandidateField::destroy($id);

        // return redirect('admin/candidate-fields')->with('flash_message', __('site.record-deleted'));

        return redirect()->route('admin.candidate-fields.index',['candidateFieldGroup'=>$groupId])->with('flash_message', __('site.record-deleted'));

    }
}
