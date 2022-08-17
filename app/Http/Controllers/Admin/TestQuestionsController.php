<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Test;
use App\TestOption;
use App\TestQuestion;
use Illuminate\Http\Request;

class TestQuestionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request,Test $test)
    {
        $this->authorize('access','view_test_questions');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $testquestions = $test->testQuestions()->whereRaw("match(question) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $testquestions = $test->testQuestions()->latest()->paginate($perPage);
        }

        return view('admin.test-questions.index', compact('testquestions','perPage','test'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create(Test $test)
    {
        $this->authorize('access','create_test_question');
        return view('admin.test-questions.create',compact('test'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request,Test $test)
    {
        $this->authorize('access','create_test_question');
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

        return redirect()->route('admin.test-questions.index',['test'=>$test->id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_test_question');
        $testquestion = TestQuestion::findOrFail($id);

        return view('admin.test-questions.show', compact('testquestion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $this->authorize('access','edit_test_question');
        $testquestion = TestQuestion::findOrFail($id);

        return view('admin.test-questions.edit', compact('testquestion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, $id)
    {
        $this->authorize('access','edit_test_question');
        $requestData = $request->all();
        $requestData['question'] = saveInlineImages($requestData['question']);
        $testquestion = TestQuestion::findOrFail($id);
        $testquestion->update($requestData);

        return redirect()->route('admin.test-questions.index',['test'=>$testquestion->test_id])->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_test_question');
        $testId = TestQuestion::find($id)->test_id;
        TestQuestion::destroy($id);

        return redirect()->route('admin.test-questions.index',['test'=>$testId])->with('flash_message', __('site.record-deleted'));
    }



    public function storeOptions(Request $request,TestQuestion $testQuestion)
    {
        $this->authorize('access', 'edit_test_question');
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
        $this->authorize('access','edit_test_question');


        return view('admin.test-questions.option',compact('testOption'));
    }

    public function updateOption(Request $request,TestOption $testOption){
        $this->authorize('access','edit_test_question');
        $this->validate($request,[
            'option'=>'required'
        ]);

        if($request->is_correct==1){
            $question = TestQuestion::find($testOption->test_question_id);
            $question->testOptions()->update(['is_correct'=>0]);
        }
        $data = $request->all();

        $testOption->update($data);
        return redirect(url('/admin/test-questions/' . $testOption->test_question_id . '/edit'))->with('flash_message',__('site.changes-saved'));
    }

    public function deleteOption(TestOption $testOption){
        $this->authorize('access','edit_test_question');
        $testOption->delete();
        return back()->with('flash_message', __('site.record-deleted'));
    }
}
