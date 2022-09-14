<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Application;
use App\Vacancy;
use App\User;
use Illuminate\Support\Carbon;
use App\Lib\HelperTrait;

class ApplicationsController extends Controller
{
    use HelperTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Vacancy $vacancy)
    {
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

        return view('employer.applications.index', compact('applications','perPage','vacancy','filterParams'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Application::destroy($id);
        return redirect('employer/applications')->with('flash_message', __('site.record-deleted'));
    }

    public function shortlist(Application $application,Request $request){
        $application->shortlisted = $request->status;
        $application->save();
        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function tests(User $user){
        $perPage = 30;
        $tests = $user->userTests()->paginate($perPage);
        return view('employer.candidates.tests',compact('tests','perPage','user'));
    }

    public function allow($id)
    {
        $application = Application::findOrFail($id);
        $update = $application->update(['status' => 'allow']);
        $this->sendEmail($application->user->email, __('site.application'), __('site.app_allowed'));
        return redirect('employer/application-records/'.$application->vacancy_id)->with('flash_message', __('Status changes'));
    }

    public function deny($id)
    {
        $application = Application::findOrFail($id);
        $application->update(['status' => 'deny']);
        $this->sendEmail($application->user->email, __('site.application'), __('site.app_denied'));
        return redirect('employer/application-records/'.$application->vacancy_id)->with('flash_message', __('Status changes'));
    }

    public function show($id)
    {
        $candidate = User::findOrFail($id);
        return view('employer.candidates.show', compact('candidate'));
    }
}
