<?php

namespace App\Http\Controllers\Candidate;

use App\Http\Controllers\Controller;
use App\Test;
use App\TestOption;
use App\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(){
        $perPage= 30;
        $tests= Test::where('status',1)->latest()->paginate($perPage);

        return view('candidate.test.index',compact('tests','perPage'));
    }

    public function start(Test $test){
        $user = Auth::user();
        //check if test is enabled
        if(empty($test->status)){
            abort(404);
        }

        //check if user has taken test
        if($user->userTests()->where('test_id',$test->id)->first() && $test->allow_multiple==0){
            return back()->with('flash_message',__('site.test-taken'));
        }

        $userTest = $user->userTests()->create([
            'test_id'=>$test->id,
            'complete'=>0,
            'score'=>0
        ]);

        $totalQuestions = $test->testQuestions()->count();

        return view('candidate.test.start',compact('test','userTest','totalQuestions'));
    }



    public function processTest(UserTest $userTest,Request $request){
        //get user test record
        $user = Auth::user();

        if($user->id != $userTest->user_id || $userTest->complete==1){
            abort(403);
        }

        //check if user has exceeded the time

        if($userTest->test->minutes > 0 && Carbon::parse($userTest->created_at)->addMinutes($userTest->test->minutes+2)->lessThan(Carbon::now())){
            //complete user test and redirect
            $userTest->complete = 1;
            $userTest->save();
            return redirect()->route('candidate.tests')->with('flash_message',__('site.time-exceeded'));
        }

        //now store test results
        $questions = $userTest->test->testQuestions;
        $data = $request->all();
        $correct = 0;
        $totalQuestions = $userTest->test->testQuestions()->count();

        foreach($questions as $row){
            if(!empty($data['question_'.$row->id]))
            {
                $optionId = $data['question_'.$row->id];

                $userTest->userTestOptions()->create([
                    'test_option_id'=>$optionId
                ]);

                //check if option is correct
                $optionRow = TestOption::find($optionId);
                if($optionRow->is_correct==1){
                    $correct++;
                }

            }
        }

        //calculate score
        $score = ($correct/$totalQuestions)  * 100;
        //update
        $userTest->score = $score;
        $userTest->complete = 1;
        $userTest->save();

        //check if result showing is enabled
        if($userTest->test->show_result==1){
            return redirect()->route('candidate.tests.result',['userTest'=>$userTest->id]);
        }
        else{
            return redirect()->route('candidate.tests')->with('flash_message',__('site.test-completed'));
        }


    }


    public function result(UserTest $userTest){
        $user = Auth::user();

        if($user->id != $userTest->user_id || $userTest->complete!=1 || $userTest->test->show_result != 1){
            abort(403);
        }


        return view('candidate.test.result',compact('userTest'));

    }

    public function results(Test $test){
        if($test->show_result!=1){
            abort(403);
        }
        $perPage = 30;

        $results = Auth::user()->userTests()->where('test_id',$test->id)->latest()->paginate($perPage);
        return view('candidate.test.results',compact('results','perPage'));
    }

}
