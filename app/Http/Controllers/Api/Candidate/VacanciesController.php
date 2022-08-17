<?php

namespace App\Http\Controllers\Api\Candidate;

use App\Application;
use App\Http\Controllers\Controller;
use App\Http\Resources\VacancyCollection;
use App\Http\Resources\VacancyResource;
use App\JobCategory;
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
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $params = $request->all();
        $vacancies = Vacancy::latest()->where('active',1)->where(function($q){
            $q->where('closes_at','>',Carbon::now()->toDateTimeString())->orWhere('closes_at','');
        });

        if(isset($params['category']) && JobCategory::find($params['category']) )
        {
            $vacancies = $vacancies->whereHas('jobCategories',function($query) use($params){
                $query->where('id',$params['category']);
            });
        }

        $vacancies = $vacancies->paginate($perPage);
        return new VacancyCollection($vacancies);
    }

    public function view(Vacancy $vacancy){
        $this->validateVacancy($vacancy);
        return new VacancyResource($vacancy);
    }

    private function validateVacancy(Vacancy $vacancy){

        if(empty($vacancy->active)){
            abort(403);
        }

        if(Carbon::parse($vacancy->closes_at)->lessThanOrEqualTo(Carbon::now())){
            abort(403);
        }

    }

    public function getVacancyApplication(Request $request,$vacancy){
        $user = $request->user();
        $application= Application::where('user_id',$user->id)->where('vacancy_id',$vacancy)->first();
        if($application){
            return response()->json([
                'status'=>true
            ]);
        }
        else{
            return response()->json([
                'status'=>false
            ]);
        }
    }

    public function apply(Request $request,Vacancy $vacancy){
        $this->validateVacancy($vacancy);
        //check if user has a cv
        $user = $request->user();
        $cv = $user->candidate->cv_path;

        if(Application::where('user_id',$user->id)->where('vacancy_id',$vacancy->id)->first()){
            return response()->json([
                'status'=>false,
                'message'=>__('site.already-applied')
            ]);
        }

        if(!empty($cv) && file_exists($cv)){

            Application::create([
                'vacancy_id'=>$vacancy->id,
                'user_id'=>$user->id,
            ]);

            return response()->json([
                'status'=>true
            ]);
        }

        return tview('candidate.vacancies.apply',compact('vacancy'));
    }

    public function submit(Vacancy $vacancy,Request $request){
        $this->validateVacancy($vacancy);



        $user = $request->user();
        $cv = $user->candidate->cv_path;

        if(Application::where('user_id',$user->id)->where('vacancy_id',$vacancy->id)->first()){
            return response()->json([
                'status'=>false,
                'message'=>__('site.already-applied')
            ]);
        }

        if(!empty($cv) && file_exists($cv)){

            Application::create([
                'vacancy_id'=>$vacancy->id,
                'user_id'=>$user->id,
            ]);

            return response()->json([
                'status'=>true
            ]);
        }



        $this->validate($request,[
            'cv' => 'required|max:'.config('app.upload_size').'|mimes:'.config('app.upload_files'),
        ]);


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

        return response()->json([
            'status'=>true
        ]);
    }


}
