<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Application;
use App\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ApplicationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request, Vacancy $vacancy)
    {
        $this->authorize('access','view_applications');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $applications = $vacancy->applications()->whereHas('user',function($query) use ($keyword) {
                $query->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            });
        } else {
            $applications = $vacancy->applications()->latest();
        }
        $params = $request->all();


        if(isset($params['gender']) && $params['gender'] != ''){

            $applications = $applications->whereHas('user',function($query) use ($params) {

                $query->whereHas('candidate',function ($query) use($params) {
                    $query->where('gender',$params['gender']);
                });

            });

        }

        if(isset($params['shortlisted']) &&  $params['shortlisted'] != '' ){
            $applications = $applications->where('shortlisted',$params['shortlisted']);
        }

        //do age
        if(isset($params['min_age'])){
            $year = date('Y') - $params['min_age'];
            $minDate = $year.'-12-31';


            $applications = $applications->whereHas('user',function($query) use ($minDate) {

                $query->whereHas('candidate',function ($query) use($minDate) {
                    $query->where('date_of_birth','<=',$minDate);
                });

            });


        }

        if(isset($params['max_age'])){
            $year = date('Y') - $params['max_age'];
            $maxDate = $year.'-01-01';

            $applications = $applications->whereHas('user',function($query) use ($maxDate) {

                $query->whereHas('candidate',function ($query) use($maxDate) {
                    $query->where('date_of_birth','>=',$maxDate);
                });

            });
        }

        if(isset($params['field_id']) && isset($params['custom_field'])){

            $applications = $applications->whereHas('user',function($query) use ($params) {

                $query->whereHas('candidateFields',function($query) use ($params) {
                    //  $query->where('value','LIKE','%'.$params['custom_field'].'%');
                    $query->whereRaw("match(value) against (? IN NATURAL LANGUAGE MODE)", [$params['custom_field']]);
                });

            });


        }


        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $applications = $applications->where('created_at','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $applications = $applications->where('created_at','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }


        $applications = $applications->paginate($perPage);


        unset($params['page'],$params['field_id']);

        $filterParams = [];

        foreach($params as $key=>$value){
            $filterParams[] = $key;//str_ireplace('_',' ',$key);
        }

        return view('admin.applications.index', compact('applications','perPage','vacancy','filterParams'));
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
        $this->authorize('access','view_application');
        $application = Application::findOrFail($id);

        $file = $application->cv_path;
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $name = 'CV_'.$application->id.'_'.safeUrl($application->user->name).'.'.$ext;
        return response()->download($file,$name);

       // return view('admin.applications.show', compact('application'));
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
        $this->authorize('access','delete_application');
        Application::destroy($id);

        return redirect('admin/applications')->with('flash_message', __('site.record-deleted'));
    }

    public function shortlist(Application $application,Request $request){
        $this->authorize('access','shortlist_application');
        $application->shortlisted = $request->status;
        $application->save();
        return back()->with('flash_message',__('site.changes-saved'));
    }
}
