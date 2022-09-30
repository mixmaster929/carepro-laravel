<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\JobRegion;

class JobRegionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('access','view_vacancy_categories');
        $perPage = 25;


        $jobregions = JobRegion::orderBy('name')->paginate($perPage);


        return view('admin.job-regions.index', compact('jobregions','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('access','create_vacancy_category');
        return view('admin.job-regions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('access','create_vacancy_category');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();
        
        JobRegion::create($requestData);

        return redirect('admin/job-regions')->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('access','view_vacancy_category');
        $jobregions = JobRegion::findOrFail($id);

        return view('admin.job-regions.show', compact('jobregions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $this->authorize('access','edit_vacancy_category');
        $jobregions = JobRegion::findOrFail($id);

        return view('admin.job-regions.edit', compact('jobregions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->authorize('access','edit_vacancy_category');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();
        
        $jobregions = JobRegion::findOrFail($id);
        $jobregions->update($requestData);

        return redirect('admin/job-regions')->with('flash_message', __('site.changes-saved'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->authorize('access','delete_vacancy_category');
        JobRegion::destroy($id);

        return redirect('admin/job-regions')->with('flash_message', __('site.record-deleted'));
    }
}
