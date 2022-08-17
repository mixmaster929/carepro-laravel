<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\CandidateFieldGroup;
use Illuminate\Http\Request;

class CandidateFieldGroupsController extends Controller
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


            $candidatefieldgroups = CandidateFieldGroup::orderBy('sort_order')->paginate($perPage);


        return view('admin.candidate-field-groups.index', compact('candidatefieldgroups'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.candidate-field-groups.create');
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
        
        CandidateFieldGroup::create($requestData);

        return redirect('admin/candidate-field-groups')->with('flash_message', __('site.changes-saved'));
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
        $candidatefieldgroup = CandidateFieldGroup::findOrFail($id);

        return view('admin.candidate-field-groups.show', compact('candidatefieldgroup'));
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
        $candidatefieldgroup = CandidateFieldGroup::findOrFail($id);

        return view('admin.candidate-field-groups.edit', compact('candidatefieldgroup'));
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
        
        $candidatefieldgroup = CandidateFieldGroup::findOrFail($id);
        $candidatefieldgroup->update($requestData);

        return redirect('admin/candidate-field-groups')->with('flash_message', __('site.changes-saved'));
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
        CandidateFieldGroup::destroy($id);

        return redirect('admin/candidate-field-groups')->with('flash_message', __('site.record-deleted'));
    }
}
