<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Test;
use App\TestQuestion;

class TestQuestionsController extends Controller
{
    public function index(Request $request,Test $test)
    {
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $testquestions = $test->testQuestions()->whereRaw("match(question) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $testquestions = $test->testQuestions()->latest()->paginate($perPage);
        }

        return view('employer.test-questions.index', compact('testquestions','perPage','test'));
    }

    public function create(Test $test)
    {
        return view('employer.test-questions.create',compact('test'));
    }

    public function store(Request $request,Test $test)
    {
        $requestData = $request->all();

        $requestData['question'] = saveInlineImages($requestData['question']);

        $testQuestion=  $test->testQuestions()->create($requestData);

        //now create options
        $correct = $requestData['correct_option'];
        for($i=1;$i<=5;$i++){

            if(!empty($requestData['option_'.$i])){

                $optionData = [
                    'option'=> trim($requestData['option_'.$i])
                ];

                if($i==$correct){
                    $optionData['is_correct'] = 1;
                }
                else{
                    $optionData['is_correct'] = 0;
                }
                $testQuestion->testOptions()->create($optionData);
            }
        }

        return redirect()->route('employer.test-questions.index',['test'=>$test->id])->with('flash_message', __('site.changes-saved'));
    }

    public function show($id)
    {
        $testquestion = TestQuestion::findOrFail($id);

        return view('employer.test-questions.show', compact('testquestion'));
    }

    public function edit($id)
    {
        $testquestion = TestQuestion::findOrFail($id);

        return view('employer.test-questions.edit', compact('testquestion'));
    }

    public function update(Request $request, $id)
    {
        $requestData = $request->all();
        $requestData['question'] = saveInlineImages($requestData['question']);
        $testquestion = TestQuestion::findOrFail($id);
        $testquestion->update($requestData);

        return redirect()->route('employer.test-questions.index',['test'=>$testquestion->test_id])->with('flash_message', __('site.changes-saved'));
    }

    public function destroy($id)
    {
        $testId = TestQuestion::find($id)->test_id;
        TestQuestion::destroy($id);

        return redirect()->route('employer.test-questions.index',['test'=>$testId])->with('flash_message', __('site.record-deleted'));
    }

    public function storeOptions(Request $request,TestQuestion $testQuestion)
    {
        $requestData = $request->all();

        //now create options
        $correct = $request->correct_option;
        for ($i = 1; $i <= 5; $i++) {

            if (!empty($requestData['option_' . $i])) {

                $optionData = [
                    'option' => trim($requestData['option_' . $i])
                ];

                if ($i == $correct) {
                    $optionData['is_correct'] = 1;
                    $testQuestion->testOptions()->update([
                       'is_correct'=>0
                    ]);

                } else {
                    $optionData['is_correct'] = 0;
                }
                $testQuestion->testOptions()->create($optionData);

            }

        }
        return back()->with('flash_message',__('site.changes-saved'));
    }

    public function editOption(TestOption $testOption){
        return view('employer.test-questions.option',compact('testOption'));
    }

    public function updateOption(Request $request,TestOption $testOption){
        $this->validate($request,[
            'option'=>'required'
        ]);

        if($request->is_correct==1){
            $question = TestQuestion::find($testOption->test_question_id);
            $question->testOptions()->update(['is_correct'=>0]);
        }
        $data = $request->all();

        $testOption->update($data);
        return redirect(url('/employer/test-questions/' . $testOption->test_question_id . '/edit'))->with('flash_message',__('site.changes-saved'));
    }

    public function deleteOption(TestOption $testOption){
        $testOption->delete();
        return back()->with('flash_message', __('site.record-deleted'));
    }
}
