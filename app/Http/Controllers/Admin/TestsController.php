<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;

use App\Test;
use App\UserTest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TestsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('access','view_tests');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $tests = Test::whereRaw("match(name,description) against (? IN NATURAL LANGUAGE MODE)", [$keyword])->paginate($perPage);
        } else {
            $tests = Test::latest()->paginate($perPage);
        }

        return view('admin.tests.index', compact('tests','perPage'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $this->authorize('access','create_test');
        return view('admin.tests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        $this->authorize('access','create_test');
        $this->validate($request,[
           'name'=>'required',
        ]);
        $requestData = $request->all();

        $requestData['description'] = saveInlineImages($requestData['description']);
        
        Test::create($requestData);

        return redirect('admin/tests')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','view_test');
        $test = Test::findOrFail($id);

        return view('admin.tests.show', compact('test'));
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
        $this->authorize('access','edit_test');
        $test = Test::findOrFail($id);

        return view('admin.tests.edit', compact('test'));
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
        $this->authorize('access','edit_test');
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();

        $requestData['description'] = saveInlineImages($requestData['description']);
        
        $test = Test::findOrFail($id);
        $test->update($requestData);

        return redirect('admin/tests')->with('flash_message', __('site.changes-saved'));
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
        $this->authorize('access','delete_test');
        Test::destroy($id);

        return redirect('admin/tests')->with('flash_message', __('site.record-deleted'));
    }
    
    
    public function attempts(Request $request,Test $test){

        $this->authorize('access','view_test_results');
        $keyword = $request->get('search');
        $perPage = 25;

        if (!empty($keyword)) {
            $results = $test->userTests()->WhereHas('user',function($q) use ($keyword) {
                $q->whereRaw("match(name,email,telephone) against (? IN NATURAL LANGUAGE MODE)", [$keyword]);
            });
        } else {
            $results = $test->userTests();
        }
        $params = $request->all();
        //filter by min_date
        if(isset($params['min_date']) && $params['min_date'] != '' )
        {
            $results = $results->where('created_at','>=',$params['min_date']);
        }

        //filter by max_date
        if(isset($params['max_date']) && $params['max_date'] != '' )
        {
            $results = $results->where('created_at','<=',Carbon::parse($params['max_date'].' 23:59:59')->toDateTimeString());
        }


        if(isset($params['user']) && $params['user'] != '' )
        {
            $results = $results->where('user_id',$params['user']);
        }

        if(isset($params['min_score']) && $params['min_score'] != '' )
        {
            $results = $results->where('score','>=',$params['min_score']);
        }

        //filter by max_date
        if(isset($params['max_score']) && $params['max_score'] != '' )
        {
            $results = $results->where('score','<=',$params['max_score']);
        }

        if(isset($params['sort']) && $params['sort'] != ''){

            switch($params['sort']){
                case 'a':
                    $results = $results->orderBy('score','asc');
                    break;
                case 'd':
                    $results = $results->orderby('score','desc');
                    break;
            }

        }
        elseif(empty($keyword)){
            $results = $results->latest();
        }


        $results= $results->paginate($perPage);
        return view('admin.tests.attempts', compact('results','perPage','test'));
        
        
    }
    

    public function results(UserTest $userTest){
        $this->authorize('access','view_test_results');
        $test = Test::find($userTest->test_id);
        return view('admin.tests.results', compact('userTest','test'));
    }

    public function deleteResult(UserTest $userTest){
        $this->authorize('access','delete_test_result');
        $userTest->delete();
        return back()->with('flash_message', __('site.record-deleted'));
    }

}
