<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Interview;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

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


}
