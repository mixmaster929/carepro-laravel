<?php

namespace App\Http\Controllers\Api\Employer;

use App\Http\Controllers\Controller;
use App\Http\Resources\InterviewResource;
use App\Interview;
use App\Lib\HelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class InterviewController extends Controller
{
    use HelperTrait;
    public function interviews(Request $request){
        $perPage = 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $interviews = $request->user()->interviews()->latest()->paginate($perPage);
        return InterviewResource::collection($interviews);
    }

    public function view(Interview $interview)
    {
        $this->authorize('view',$interview);
        return new InterviewResource($interview);
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
        return response()->json([
            'status'=>true,
            'message'=>__('site.changes-saved')
        ]);
    }
}
