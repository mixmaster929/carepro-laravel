<?php

namespace App\Http\Controllers\Candidate;

use App\Application;
use App\Http\Controllers\Controller;
use App\JobCategory;
use App\JobRegion;
use App\Lib\HelperTrait;
use App\Vacancy;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacanciesController extends Controller
{
    use HelperTrait;

    public function index(Request $request){
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

        if(isCandidate()){
            return view('candidate.vacancies.index',compact('vacancies','perPage','title','categories', 'regions'));
        }

        return tview('candidate.vacancies.index',compact('vacancies','perPage','title','categories', 'regions'));
    }

    public function view(Vacancy $vacancy){
        $this->validateVacancy($vacancy);
        if (isCandidate()){
            return view('candidate.vacancies.view',compact('vacancy'));
        }
        return tview('candidate.vacancies.view',compact('vacancy'));
    }

    private function validateVacancy(Vacancy $vacancy){
        if(empty($vacancy->active)){
            abort(403);
        }

        if(Carbon::parse($vacancy->closes_at)->lessThanOrEqualTo(Carbon::now())){
            abort(403);
        }
    }

    public function apply(Vacancy $vacancy){
        $this->validateVacancy($vacancy);
        //check if user has a cv
        $user = Auth::user();
        // dd($user);
        // $cv = $user->candidate->cv_path;

        if(Application::where('user_id',$user->id)->where('vacancy_id',$vacancy->id)->first()){

            return back()->with('flash_message',__('site.already-applied'));
        }
        if($user->candidate->locked){
            return back()->with('flash_message',__('site.vacancy-locked'));
        }
        else{
            return redirect()->route('candidate.profile-vacancy', ['vacancy' => $vacancy]);
        }

        
        // if(!empty($cv) && file_exists($cv)){

        //     Application::create([
        //         'vacancy_id'=>$vacancy->id,
        //         'user_id'=>$user->id,
        //     ]);
        //     return redirect()->route('candidate.vacancy.complete');
        // }

        // if (isCandidate()){
        //     return view('candidate.vacancies.apply',compact('vacancy'));
        // }

        // $subject = __('site.apply_vacancy');
        // // $admin_link = route('admin.applications.index',['vacancy'=>$vacancy->id],true);
        // $employer_link = route('employer.applications.index',['vacancy'=>$vacancy->id],true);
        // $employer_message = __('site.apply_vacancy_to_admin',[
        //     'name'=>$user->name,
        //     'title'=> $vacancy->title,
        //     'location' => $vacancy->location,
        //     'closes_at' => $vacancy->closes_at,
        //     'application-records' => $employer_link,
        // ]);
        // $admin_message = __('site.apply_vacancy_to_admin',[
        //     'name'=>$user->name,
        //     'title'=> $vacancy->title,
        //     'location' => $vacancy->location,
        //     'closes_at' => $vacancy->closes_at,
        //     'application-records' => $employer_link,
        // ]);
        // if($vacancy->user){
        //     $employer = $vacancy->user->email;
        //     $this->sendEmail($employer, $subject, $employer_message);
        // }

        // $this->sendEmail(setting('general_admin_email'), $subject, $admin_message);
        

        // return tview('candidate.vacancies.apply',compact('vacancy'));
    }

    public function submit(Vacancy $vacancy,Request $request){
        $this->validateVacancy($vacancy);
        $this->validate($request,[
            'cv' => 'required|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ]);

        $user = Auth::user();
        //check if candidate has already applied
        if(Application::where('user_id',$user->id)->where('vacancy_id',$vacancy->id)->first()){
            return back()->with('flash_message',__('site.already-applied'));
        }

        $name = $_FILES['cv']['name'];

        //dd($name);


        $extension = $request->cv->extension();
        //  dd($extension);

        $name = str_ireplace('.'.$extension,'',$name);

        $name = $user->id.'_'.time().'_'.safeUrl($name).'.'.$extension;

        $path =  $request->file('cv')->storeAs(CANDIDATE_FILES,$name,'public_uploads');
        $file = UPLOAD_PATH.'/'.$path;

        $candidate = $user->candidate;
        $candidate->cv_path = $file;
        $candidate->save();

        Application::create([
            'vacancy_id'=>$vacancy->id,
            'user_id'=>$user->id,
        ]);
        return redirect()->route('candidate.vacancy.complete');
    }

    public function complete(){
        if (isCandidate()){
            return view('candidate.vacancies.complete');
        }
        return tview('candidate.vacancies.complete');
    }
}
