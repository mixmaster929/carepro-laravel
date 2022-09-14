<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Interview;
use App\User;
use App\Vacancy;
use App\Application;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\InterviewAlert;
use App\Employment;

class InterviewController extends Controller
{
    use HelperTrait;
    public function interviews(){

        $interviews = Auth::user()->interviews()->latest()->paginate(30);
  //      $interviews = Auth::user()->interviews()->paginate(30);
        return view('employer.interview.interviews',compact('interviews'));
    }

    public function view(Interview $interview)
    {
        $this->authorize('view',$interview);
        return view('employer.interview.view',compact('interview'));
    }

    public function update(Interview $interview,Request $request){
        $this->authorize('view',$interview);
        $this->validate($request,[
            'employer_feedback'=>'required'
        ]);

        $interview->update($request->all());

        //notify admins
        $subject = __('site.interview-feedback');
        $message = __('site.interview-feedback->msg',[
            'name'=>$interview->user->name,
            'date'=>Carbon::parse($interview->interview_date)->format('d/M/Y'),
            'feedback'=>$request->employer_feedback
        ]);
        $this->notifyAdmins($subject,$message,'view_interview');
        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function createInterview(Request $request, Application $application){
        $employer = Auth::user();
        return view('employer.interview.create', compact(['application', 'employer']));
    }

    public function createPlacement(Request $request, Application $application){
        $employer = Auth::user();
        return view('employer.placement.create-employment', compact(['application', 'employer']));
    }

    public function storePlacementt(Request $request){
        $application_id = $request->application_id;
        $application = Application::find($application_id);

        $this->validate($request,[
            'employer_user_id'=>'required',
            'user_id'=>'required',
            'start_date'=>'required',
            'active'=>'required'
        ]);

        $requestData = $request->all();
        $requestData['employer_id'] = User::find($requestData['employer_user_id'])->employer->id;
        $requestData['candidate_id'] = User::find($requestData['user_id'])->candidate->id;

        Employment::create($requestData);

        $application->update(['status' => 'Placed']);
        $employer = User::find($requestData['employer_user_id']);
        $candidate = User::find($requestData['user_id']);
        $end_date = $request->end_date? $request->end_date : '';

        $subject = __('site.make-placement');

        $this->sendEmail($employer->email, $subject, $candidate->name." is placed at ".$employer->name." from date: ".$requestData['start_date']." to ".$end_date);
        $this->sendEmail($candidate->email, $subject, $candidate->name." is placed at ".$employer->name." from date: ".$requestData['start_date']." to ".$end_date);
        $this->sendEmail(setting('general_admin_email'), $subject, "Employer ".$employer->name." has placed candidate ".$candidate->name);
        
        // try{
        //     // Mail::to($interview->user->email)->send(New InterviewAlert($interview));
        //     Mail::to($application->user->email)->send(New InterviewAlert($interview));
        // }
        // catch(\Exception $ex){
        //     $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
        // }

        return redirect('employer/application-records/'.$application->vacancy_id)->with('flash_message', __('site.changes-saved'));
    }
    
    public function store(Request $request){
        $application_id = $request->application_id;

        $this->validate($request,[
            'user_id'=>'required',
            'interview_date'=>'required'
        ]);
        $requestData = $request->all();

        $requestData['hash'] = Str::random(30);

        $interview= Interview::create($requestData);
        
        //sync candidates
        if(!empty($request->candidate_id)){
            $interview->candidates()->attach($requestData['candidate_id']);
        }

        $application = Application::find($application_id);
        $application->update(['status' => 'Interview Planned']);

        //send mail to employer
        if($interview->reminder==1){
            try{
                // Mail::to($interview->user->email)->send(New InterviewAlert($interview));
                Mail::to($application->user->email)->send(New InterviewAlert($interview));
            }
            catch(\Exception $ex){
                $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
            }
        }

        return redirect('employer/application-records/'.$application->vacancy_id)->with('flash_message', __('site.changes-saved'));
    }

    public function edit(Interview $interview){
        $employer = Auth::user();
        $application = $interview->application_id? Application::find($interview->application_id) : [];
        
        return view('employer.interview.edit', compact(['interview', 'employer', 'application']));
    }

    public function updateInterview(Interview $interview, Request $request){
        $application_id = $request->application_id;

        $requestData = $request->all();
        
        $interview = Interview::findOrFail($request->id);
        $interview->update($requestData);

        $interview->candidates()->sync($request->candidate_id);
        $application = Application::find($application_id);

        //send mail to employer
        if($interview->reminder==1){
            try{
                Mail::to($application->user->email)->send(New InterviewAlert($interview));
            }
            catch(\Exception $ex){
                $this->warningMessage(__('site.mail-error').': '.$ex->getMessage());
            }
        }

        return redirect('employer/interviews')->with('flash_message', __('site.changes-saved'));
    }

    public function destroy(Interview $interview){
        // $application_id = $interview->application_id? $interview->application_id : 0;
        $email = Auth::user()->email;
        if($interview->application_id){
            $application = Application::find($interview->application_id);
            $application->update(['status' => 'pending']);
            $email = $application->user->email;
        }

        // $email = $application? $application->user->email : Auth::user()->email;

        Interview::destroy($interview->id);

        $this->sendEmail($email, __('site.delete-interview'), __('site.interview_canceled'));

        return redirect('employer/interviews')->with('flash_message', __('site.changes-saved'));
    }
}
