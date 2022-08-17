<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class VacanciesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_vacancies');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $vacancies = Vacancy::whereRaw("match(title,description) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
        } else {
            $vacancies = Vacancy::latest();
        }

        $params = $request->all();

        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $vacancies = $vacancies->where('created_at','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $vacancies = $vacancies->where('created_at','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }

        //filter by enabled
        if(isset($params['enabled']) && $params['enabled'] != '' )
        {
            $vacancies = $vacancies->where('active',$params['enabled']);
        }


        if(isset($params['category']) && $params['category'] != '' )
        {

            //$vacancies = $vacancies->where('invoice_category_id',$params['category']);
            $vacancies = $vacancies->whereHas('jobCategories',function($query) use($params){
                $query->where('id',$params['category']);
            });

        }

        unset($params['search'],$params['page'],$params['field_id']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;
        }

        $vacancies = $vacancies->paginate($perPage);

        return view('admin.vacancies.index', compact('vacancies','perPage','filterParams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_vacancy');
        return view('admin.vacancies.create');
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
        $this->authorize('access','create_vacancy');
        $requestData = $request->all();
        $requestData['description'] = saveInlineImages($requestData['description']);

        $vacancy = Vacancy::create($requestData);
        if (isset($requestData['categories'])){
            $vacancy->jobCategories()->attach($requestData['categories']);
        }


        return redirect('admin/vacancies')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_vacancy');
        $vacancy = Vacancy::findOrFail($id);

        return view('admin.vacancies.show', compact('vacancy'));
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
        $this->authorize('access','edit_vacancy');
        $vacancy = Vacancy::findOrFail($id);

        return view('admin.vacancies.edit', compact('vacancy'));
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
        $this->authorize('access','edit_vacancy');
        $requestData = $request->all();
        $requestData['description'] = saveInlineImages($request->description);
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update($requestData);
        $vacancy->jobCategories()->sync($request->categories);

        return redirect('admin/vacancies')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_vacancy');
        Vacancy::destroy($id);

        return redirect('admin/vacancies')->with('flash_message', __('site.record-deleted'));
    }
}
