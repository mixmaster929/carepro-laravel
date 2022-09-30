<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Vacancy;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Lib\HelperTrait;
use App\JobCategory;
use App\JobRegion;

class VacanciesController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */

    public function view(Vacancy $vacancy){
        $this->validateVacancy($vacancy);
        
        return tview('employer.vacancies.view',compact('vacancy'));
    }

    private function validateVacancy(Vacancy $vacancy){
        if(empty($vacancy->active)){
            abort(403);
        }

        if(Carbon::parse($vacancy->closes_at)->lessThanOrEqualTo(Carbon::now())){
            abort(403);
        }
    }

    public function myvacancies(Request $request){
        $perPage = 24;
        $params = $request->all();
        $title= __('site.vacancies');
        $vacancies = Vacancy::latest()->where('active',1)->where(function($q){
            $q->where('closes_at','>',Carbon::now()->toDateTimeString())->orWhere('closes_at','');
        });

        if(isset($params['category']) && JobCategory::find($params['category']) )
        {

            //$vacancies = $vacancies->where('invoice_category_id',$params['category']);
            $vacancies = $vacancies->whereHas('jobCategories',function($query) use($params){
                $query->where('id',$params['category']);
            });
            $title .= ': '.JobCategory::find($params['category'])->name;
        }

        if(isset($params['region']) && JobRegion::find($params['region']) )
        {

            //$vacancies = $vacancies->where('invoice_category_id',$params['category']);
            $vacancies = $vacancies->whereHas('jobRegions',function($query) use($params){
                $query->where('id',$params['region']);
            });
            $title .= ': '.JobRegion::find($params['region'])->name;
        }

        $vacancies = $vacancies->paginate($perPage);
        $categories = JobCategory::orderBy('sort_order')->get();
        $regions = JobRegion::orderBy('sort_order')->get();

        return tview('employer.vacancies.myindex',compact('vacancies','perPage','title','categories', 'regions'));
    }
    public function index(Request $request)
    {
        // $this->authorize('access','view_vacancies');
        $user_id = Auth::user()->id;

        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $vacancies = Vacancy::whereRaw("match(title,description) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
        } else {
            $vacancies = Vacancy::where('user_id', "=", $user_id)->latest();
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

        return view('employer.vacancies.index', compact('vacancies','perPage','filterParams'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // $this->authorize('access','create_vacancy');
        return view('employer.vacancies.create');
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
        // $this->authorize('access','create_vacancy');
        $user = Auth::user();
        $requestData = $request->all();
        $requestData['description'] = saveInlineImages($requestData['description']);
        $requestData['user_id'] = $user->id;

        $vacancy = Vacancy::create($requestData);
        if (isset($requestData['categories'])){
            $vacancy->jobCategories()->attach($requestData['categories']);
        }

        if (isset($requestData['regions'])){
            $vacancy->jobRegions()->attach($requestData['regions']);
        }

        $subject = __('site.create-vacancy');
        $message = __('site.create-vacancy_to_admin',[
            'name'=>$user->name,
            'title'=> $requestData['title'],
            'location' => $requestData['location'],
            'closes_at' => $requestData['closes_at']
        ]);

        $this->sendEmail(setting('general_admin_email'), $subject, $message);

        return redirect('employer/vacancies')->with('flash_message', __('site.changes-saved'));
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
        // $this->authorize('access','view_vacancy');
        $vacancy = Vacancy::findOrFail($id);

        return view('employer.vacancies.show', compact('vacancy'));
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
        // $this->authorize('access','edit_vacancy');
        $vacancy = Vacancy::findOrFail($id);

        return view('employer.vacancies.edit', compact('vacancy'));
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
        // $this->authorize('access','edit_vacancy');
        $requestData = $request->all();
        $requestData['description'] = saveInlineImages($request->description);
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->update($requestData);
        $vacancy->jobCategories()->sync($request->categories);
        $vacancy->jobRegions()->sync($request->regions);

        return redirect('employer/vacancies')->with('flash_message', __('site.changes-saved'));
    }
}
