<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\JobCategory;
use Illuminate\Http\Request;

class JobCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_vacancy_categories');
        $perPage = 25;


        $jobcategories = JobCategory::orderBy('name')->paginate($perPage);


        return view('admin.job-categories.index', compact('jobcategories','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_vacancy_category');
        return view('admin.job-categories.create');
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
        $this->authorize('access','create_vacancy_category');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();
        
        JobCategory::create($requestData);

        return redirect('admin/job-categories')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_vacancy_category');
        $jobcategory = JobCategory::findOrFail($id);

        return view('admin.job-categories.show', compact('jobcategory'));
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
        $this->authorize('access','edit_vacancy_category');
        $jobcategory = JobCategory::findOrFail($id);

        return view('admin.job-categories.edit', compact('jobcategory'));
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
        $this->authorize('access','edit_vacancy_category');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();
        
        $jobcategory = JobCategory::findOrFail($id);
        $jobcategory->update($requestData);

        return redirect('admin/job-categories')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_vacancy_category');
        JobCategory::destroy($id);

        return redirect('admin/job-categories')->with('flash_message', __('site.record-deleted'));
    }
}
