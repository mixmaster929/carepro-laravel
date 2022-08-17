<?php

namespace App\Http\Controllers\Api\Candidate;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestResource;
use App\Http\Resources\UserTestResource;
use App\Test;
use App\TestOption;
use App\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class TestController extends Controller
{
    public function index(Request $request){
        $perPage= 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $tests= Test::where('status',1)->latest()->paginate($perPage);

        return TestResource::collection($tests);
    }

    public function start(Request $request){

        $this->validate($request,[
            'test_id'=>'required'
        ]);

        $test = Test::findOrFail($request->test_id);

        $user = $request->user();
        //check if test is enabled
        if(empty($test->status)){
            abort(404);
        }

        //check if user has taken test
        if($user->userTests()->where('test_id',$test->id)->first() && $test->allow_multiple==0){
            return response()->json([
                'status'=>false,
                'message'=>__('site.test-taken')
            ]);
        }

        $userTest = $user->userTests()->create([
            'test_id'=>$test->id,
            'complete'=>0,
            'score'=>0
        ]);

        $totalQuestions = $test->testQuestions()->count();

        return response()->json([
            'test'=>new TestResource($test),
            'user_test'=> $userTest,
            'total_questions'=>$totalQuestions,
            'status'=>true
        ]);

    }

    public function processTest(UserTest $userTest,Request $request){
        //get user test record
        $user = $request->user();



        if ($user->id != $userTest->user_id ){
            return response()->json([
                'status'=>false,
                'message'=>__('site.unauthorized-access')
            ]);
        }

        if($userTest->complete==1){
            return response()->json([
                'status'=>false,
                'message'=>__('site.test-completed-already')
            ]);
        }

/*        if($user->id != $userTest->user_id || $userTest->complete==1){
            abort(403);
        }*/

        //check if user has exceeded the time

        if($userTest->test->minutes > 0 && Carbon::parse($userTest->created_at)->addMinutes($userTest->test->minutes+2)->lessThan(Carbon::now())){
            //complete user test and redirect
            $userTest->complete = 1;
            $userTest->save();
            return response()->json([
                'status'=>false,
                'message'=>__('site.time-exceeded')
            ]);
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
            return response()->json([
                'status'=>true,
                'show_result'=>true,
                'userTest'=>new UserTestResource($userTest)
            ]);
        }
        else{
            return response()->json([
                'status'=>true,
                'show_result'=>false,
                'message'=>__('site.test-completed')
            ]);
        }


    }

    public function result(Request $request,UserTest $userTest){
        $user = $request->user();

        if($user->id != $userTest->user_id || $userTest->complete!=1 || $userTest->test->show_result != 1){
            abort(403);
        }

        return new UserTestResource($userTest);

    }

    public function results(Request $request,Test $test){
        if($test->show_result!=1){
            abort(403);
        }
        $perPage = 30;
        if($request->has('per_page')){
            $perPage = $request->per_page;
        }
        $results = $request->user()->userTests()->where('test_id',$test->id)->latest()->paginate($perPage);
        return UserTestResource::collection($results);
    }

}
