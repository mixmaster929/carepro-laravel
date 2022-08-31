<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Test;
use Illuminate\Support\Facades\Auth;

class TestsController extends Controller
{
    public function attempts(Request $request,Test $test){
        $perPage = 25;
        $results = $test->userTests();

        $results= $results->paginate($perPage);
        return view('employer.tests.attempts', compact('results','perPage','test'));
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $perPage = 25;
        $tests = Test::where('user_id', $user->id)->latest()->paginate($perPage);

        return view('employer.tests.index', compact('tests','perPage'));
    }

    public function create()
    {
        $user = Auth::user();
        return view('employer.tests.create', compact(['user']));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
           'name'=>'required',
        ]);
        $requestData = $request->all();

        $requestData['description'] = saveInlineImages($requestData['description']);
        
        Test::create($requestData);

        return redirect('employer/tests')->with('flash_message', __('site.changes-saved'));
    }

    public function show($id)
    {
        $test = Test::findOrFail($id);

        return view('employer.tests.show', compact('test'));
    }

    public function edit($id)
    {
        $user = Auth::user();
        $test = Test::findOrFail($id);

        return view('employer.tests.edit', compact(['test', 'user']));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request,[
            'name'=>'required'
        ]);
        $requestData = $request->all();

        $requestData['description'] = saveInlineImages($requestData['description']);
        
        $test = Test::findOrFail($id);
        $test->update($requestData);

        return redirect('employer/tests')->with('flash_message', __('site.changes-saved'));
    }
}
